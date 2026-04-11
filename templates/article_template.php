<?php
/**
 * ШАБЛОН: Статья блога
 * Простой: H1 + картинка + текст + related
 */

// 1. Header
require_smart('header.php', $lang, $ua_includes, $root_includes);

// 2. Хлебные крошки
require_smart('breadcrumbs.php', $lang, $ua_includes, $root_includes);

// 3. Заголовок H1 (простой, без фона)
require_smart('h1_article.php', $lang, $ua_includes, $root_includes);
?>

<?php // 4. Главная картинка статьи ?>
<?php if (!empty($page['hero_image'])): ?>
<section class="sec" style="padding-top:0;padding-bottom:0;">
    <div class="sec-inner">
        <img src="<?= htmlspecialchars($page['hero_image']) ?>"
             alt="<?= htmlspecialchars($page['h1']) ?>"
             style="border-radius:12px;box-shadow:0 4px 16px rgba(0,0,0,.06);"
             loading="lazy">
    </div>
</section>
<?php endif; ?>

<?php // 5. Контент статьи ?>
<section class="sec">
    <div class="sec-inner">
        <div class="text-block" style="max-width:780px;">
            <?php
            if (!empty($blocks)) {
                foreach ($blocks as $block) {
                    if ($block['block_type'] == 'text') {
                        echo $block['content'];
                    } elseif ($block['block_type'] == 'structured_content') {
                        $items = json_decode($block['content'], true);
                        if ($items) render_structured_content($items);
                    } elseif ($block['block_type'] == 'include') {
                        require_smart($block['block_path'], $lang, $ua_includes, $root_includes);
                    }
                }
            }
            ?>
        </div>
    </div>
</section>

<?php // 6. Блок "Читайте также" ?>
<?php require_smart('related.php', $lang, $ua_includes, $root_includes); ?>

<?php // 7. Кнопка "Назад в блог" ?>
<section class="sec" style="padding-top:0;text-align:center;">
    <div class="sec-inner">
        <a href="<?= ($lang == 'ua' ? '/ua/news' : '/news') ?>" class="blog-btn" style="padding:14px 28px;font-size:15px;">
            ← <?= ($lang == 'ua' ? 'Повернутися до блогу' : 'Вернуться в блог') ?>
        </a>
    </div>
</section>

<?php
// 8. Footer
require_smart('footer.php', $lang, $ua_includes, $root_includes);
?>
