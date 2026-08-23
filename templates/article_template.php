<?php
/**
 * ШАБЛОН: Статья блога
 *
 * Обновлено (фиксы):
 *   1. Контентные блоки больше не заворачиваются в общий
 *      <section class="sec"><div class="text-block">. render_structured_content()
 *      сам выдаёт <section>, и раньше получалась секция внутри секции:
 *      двойные паддинги и наследование font-size/color на заголовки.
 *      Ширина колонки теперь задаётся классом body.page-articles в CSS.
 *   2. apply_placeholders() применяется к текстовым блокам — раньше
 *      {tel1}, {price} и т.д. выводились фигурными скобками как есть.
 *   3. Главная картинка статьи — это LCP-элемент, loading="lazy" на нём
 *      прямо бил по Core Web Vitals. Заменено на fetchpriority="high".
 *   4. Guard от повторного подключения одного и того же партиала.
 */

global $settings;

// 1. Header
require_smart('header.php');

// 2. Хлебные крошки
require_smart('breadcrumbs.php');

// 3. Заголовок H1 (простой, без фона)
require_smart('h1_article.php');

// Реестр уже отрисованных партиалов
$rendered_includes = ['h1_article.php' => true, 'breadcrumbs.php' => true];
?>

<?php // 4. Главная картинка статьи — LCP, грузим приоритетно ?>
<?php if (!empty($page['hero_image'])): ?>
<section class="sec" style="padding-top:0;padding-bottom:0;">
    <div class="sec-inner">
        <img src="<?= htmlspecialchars($page['hero_image']) ?>"
             alt="<?= htmlspecialchars(strip_tags((string)($page['h1'] ?? ''))) ?>"
             style="width:100%;height:auto;max-height:480px;object-fit:cover;border-radius:12px;box-shadow:0 4px 16px rgba(0,0,0,.06);"
             fetchpriority="high"
             decoding="async">
    </div>
</section>
<?php endif; ?>

<?php
// 5. Контент статьи.
//    Каждый тип блока рендерится на своём уровне, без общей обёртки.
if (!empty($blocks)) {
    foreach ($blocks as $block) {
        $bt = $block['block_type'] ?? '';

        if ($bt === 'text') {
            echo '<section class="sec"><div class="sec-inner"><div class="text-block">';
            echo apply_placeholders($block['content'], $city_val, $in_city_val, $price_val, $dist_val, $time_val, $settings);
            echo '</div></div></section>';
        }
        elseif ($bt === 'structured_content') {
            $items = json_decode(trim((string)$block['content']), true);
            if ($items) render_structured_content($items);
        }
        elseif ($bt === 'include') {
            $path = $block['block_path'] ?? '';
            if ($path === '' || !empty($rendered_includes[$path])) continue;
            $rendered_includes[$path] = true;
            require_smart($path);
        }
    }
}
?>

<?php // 6. Блок "Читайте также" ?>
<?php
if (empty($rendered_includes['related.php'])) {
    $rendered_includes['related.php'] = true;
    require_smart('related.php');
}
?>

<?php // 7. Кнопка "Назад в блог" ?>
<section class="sec" style="padding-top:0;text-align:center;">
    <div class="sec-inner">
        <a href="<?= ($lang == 'ua' ? '/ua/blog' : '/blog') ?>" class="blog-btn" style="padding:14px 28px;font-size:15px;">
            ← <?= ($lang == 'ua' ? 'Повернутися до блогу' : 'Вернуться в блог') ?>
        </a>
    </div>
</section>

<?php
// 8. Footer
require_smart('footer.php');
?>
