<?php
/**
 * ШАБЛОН: Локации + Услуги (unified)
 * Работает для type: locations, district, services
 *
 * Обновлено (SEO-фикс):
 *   - guard от повторного подключения одного и того же партиала.
 *     route_seo_block.php подключался дважды: хардкодом на шаге 4
 *     и ещё раз из content_blocks (block_type = include).
 *     Отсюда были дубль текста маршрута и второй FAQPage в ld+json.
 */

global $settings;

// === ПОДСТАНОВКА ПЛЕЙСХОЛДЕРОВ ===
if (!empty($page)) {
    if (!empty($page['meta_title']))
        $title = apply_placeholders($page['meta_title'], $city_val, $in_city_val, $price_val, $dist_val, $time_val, $settings);
    if (!empty($page['meta_description']))
        $description = apply_placeholders($page['meta_description'], $city_val, $in_city_val, $price_val, $dist_val, $time_val, $settings);

    // H1
    if (!empty($custom_h1)) {
        $custom_h1 = apply_placeholders($custom_h1, $city_val, $in_city_val, $price_val, $dist_val, $time_val, $settings);
    } elseif (!empty($page['h1'])) {
        $custom_h1 = apply_placeholders($page['h1'], $city_val, $in_city_val, $price_val, $dist_val, $time_val, $settings);
    }

    // Hero P
    $raw_p = !empty($custom_p) ? $custom_p : (!empty($page['custom_p']) ? $page['custom_p'] : '');
    if (!empty($raw_p)) {
        if ($time_val) {
            $raw_p = str_replace(
                ['в течение 20-40 минут', 'протягом 20-40 хвилин'],
                ($lang == 'ua' ? "протягом {$time_val}" : "в течение {$time_val}"),
                $raw_p
            );
        }
        $custom_p = apply_placeholders($raw_p, $city_val, $in_city_val, $price_val, $dist_val, $time_val, $settings);
    }

    if (empty($custom_bg) && !empty($page['hero_image'])) $custom_bg = $page['hero_image'];
    if (empty($custom_btn) && !empty($page['custom_btn'])) $custom_btn = $page['custom_btn'];
}

// === СБОРКА СТРАНИЦЫ ===

// Реестр уже отрисованных партиалов — защита от дублей
$rendered_includes = [];

// 1. Header
require_smart('header.php');

// 2. Хлебные крошки (не на главной)
if ($slug !== 'home' && $slug !== '') {
    require_smart('breadcrumbs.php');
}

// 3. Hero блок
// Для сервисных страниц используем h1_service.php (без бейджа "Работаем 24/7",
// который не всегда уместен — например, у СТО обычный график работы).
// Для локаций/районов/маршрутов оставляем прежний h1_block.php.
$hero_partial = ($page_type == 'services') ? 'h1_service.php' : 'h1_block.php';
require_smart($hero_partial);
$rendered_includes[$hero_partial] = true;

// 4. SEO-блок маршрута (для межгорода — показывает расстояние, время, цену)
//    Не показываем на страницах с выездом по согласованию: там нет
//    гарантированного времени подачи, и блок противоречил бы плашке.
$on_request = !empty($attrs['on_request']);

if (!$on_request && !empty($dist_val) && !empty($time_val)) {
    require_smart('route_seo_block.php');
    $rendered_includes['route_seo_block.php'] = true;
}

// 5. Контентные блоки из БД
if (!empty($blocks)) {
    foreach ($blocks as $block) {
        if ($block['block_type'] == 'include') {

            $path = $block['block_path'] ?? '';
            if ($path === '' || !empty($rendered_includes[$path])) {
                // уже отрисован выше — второй раз не выводим
                continue;
            }
            $rendered_includes[$path] = true;

            if ($path == 'maps.php' && !empty($attrs['maps'])) {
                $loc_map = $attrs['maps'];
            }
            require_smart($path);
        }
        elseif ($block['block_type'] == 'structured_content'
            || ($block['block_type'] == 'text' && strpos(trim($block['content']), '[') === 0 && json_decode(trim($block['content'])))) {
            // JSON контент — парсим и рендерим (защита от неправильного block_type)
            $items = json_decode(trim($block['content']), true);
            if ($items) render_structured_content($items);
        }
        elseif ($block['block_type'] == 'text') {
            echo '<section class="sec"><div class="sec-inner"><div class="text-block">';
            echo apply_placeholders($block['content'], $city_val, $in_city_val, $price_val, $dist_val, $time_val, $settings);
            echo '</div></div></section>';
        }
    }
}

// 6. Footer
require_smart('footer.php');
?>
