<?php
/**
 * ШАБЛОН: Хаб (агрегатор)
 * Для страниц "Харьковская область", "По Украине"
 * Показывает hero + текст + сетку дочерних страниц с ценами
 *
 * Обновлено (фиксы):
 *   1. ЦЕНА СЧИТАЕТСЯ ОБЩЕЙ ФУНКЦИЕЙ calc_page_price().
 *      Раньше здесь была своя копия формулы с обратным приоритетом:
 *      расстояние перебивало ручную attributes.price, а в router.php —
 *      наоборот. При выставленной ручной цене таблица хаба и сама
 *      страница направления показывали разные цифры.
 *      Двойка (обратная дорога) тоже была захардкожена — теперь
 *      берётся из настройки price_return_factor.
 *   2. Guard от повторного подключения одного и того же партиала.
 */

global $settings;

// === ПОДСТАНОВКА ПЛЕЙСХОЛДЕРОВ ===
if (!empty($page)) {
    if (!empty($page['meta_title']))
        $title = apply_placeholders($page['meta_title'], $city_val, $in_city_val, $price_val, $dist_val, $time_val, $settings);
    if (!empty($page['meta_description']))
        $description = apply_placeholders($page['meta_description'], $city_val, $in_city_val, $price_val, $dist_val, $time_val, $settings);

    if (!empty($custom_h1)) {
        $custom_h1 = apply_placeholders($custom_h1, $city_val, $in_city_val, $price_val, $dist_val, $time_val, $settings);
    } elseif (!empty($page['h1'])) {
        $custom_h1 = apply_placeholders($page['h1'], $city_val, $in_city_val, $price_val, $dist_val, $time_val, $settings);
    }

    $raw_p = !empty($custom_p) ? $custom_p : (!empty($page['custom_p']) ? $page['custom_p'] : '');
    if (!empty($raw_p)) {
        $custom_p = apply_placeholders($raw_p, $city_val, $in_city_val, $price_val, $dist_val, $time_val, $settings);
    }

    if (empty($custom_bg) && !empty($page['hero_image'])) $custom_bg = $page['hero_image'];
    if (empty($custom_btn) && !empty($page['custom_btn'])) $custom_btn = $page['custom_btn'];
}

// === СБОРКА ===

$rendered_includes = [];

require_smart('header.php');

if ($slug !== 'home' && $slug !== '') {
    require_smart('breadcrumbs.php');
    $rendered_includes['breadcrumbs.php'] = true;
}

require_smart('h1_block.php');
$rendered_includes['h1_block.php'] = true;

// === ДОЧЕРНИЕ СТРАНИЦЫ (таблица маршрутов) ===
$children = $db->select('pages', '*', [
    'parent_id' => $page['id'],
    'lang'      => $lang,
    'ORDER'     => ['breadcrumb_title' => 'ASC']
]);

$is_ua  = ($lang === 'ua');
$prefix = $is_ua ? '/ua/' : '/';

if (!empty($children)):
    $col_city  = $is_ua ? 'Напрямок'  : 'Направление';
    $col_dist  = $is_ua ? 'Відстань'  : 'Расстояние';
    $col_time  = $is_ua ? 'Час подачі': 'Время подачи';
    $col_price = $is_ua ? 'Від, грн'  : 'От, грн';
?>
<section class="sec">
    <div class="sec-inner">
        <h2 class="sec-title"><?= $is_ua ? 'Напрямки та тарифи' : 'Направления и тарифы' ?></h2>
        <div class="table-wrap">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th><?= $col_city ?></th>
                        <th><?= $col_dist ?></th>
                        <th><?= $col_time ?></th>
                        <th><?= $col_price ?></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($children as $child):
                    $c_attrs = json_decode($child['attributes'], true) ?: [];
                    $c_dist  = $c_attrs['distance'] ?? '';
                    $c_time  = $c_attrs['time'] ?? '';
                    $c_name  = $child['breadcrumb_title'] ?? strip_tags($child['h1']);
                    $c_link  = $prefix . ltrim($child['slug'], '/');

                    // Общая функция — та же, что в router.php
                    $c_price = calc_page_price($c_attrs, $child['location_type'] ?? 'city', $settings);
                ?>
                <tr>
                    <td><a href="<?= $c_link ?>"><?= htmlspecialchars($c_name) ?></a></td>
                    <td><?= $c_dist ? $c_dist . ' км' : '—' ?></td>
                    <td><?= $c_time ?: '—' ?></td>
                    <td><strong><?= number_format((float)$c_price, 0, '', ' ') ?></strong></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<?php endif; ?>

<?php
// Контентные блоки из БД
if (!empty($blocks)) {
    foreach ($blocks as $block) {
        $bt = $block['block_type'] ?? '';

        if ($bt === 'include') {
            $path = $block['block_path'] ?? '';
            if ($path === '' || !empty($rendered_includes[$path])) continue;
            $rendered_includes[$path] = true;

            if ($path === 'maps.php' && !empty($attrs['maps'])) {
                $loc_map = $attrs['maps'];
            }
            require_smart($path);
        }
        elseif ($bt === 'text') {
            echo '<section class="sec"><div class="sec-inner"><div class="text-block">';
            echo apply_placeholders($block['content'], $city_val, $in_city_val, $price_val, $dist_val, $time_val, $settings);
            echo '</div></div></section>';
        }
        elseif ($bt === 'structured_content') {
            $items = json_decode(trim((string)$block['content']), true);
            if ($items) render_structured_content($items);
        }
    }
}

require_smart('footer.php');
?>
