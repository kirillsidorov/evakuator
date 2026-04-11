<?php
/**
 * ШАБЛОН: Индекс блога (список статей)
 * blog-grid с карточками
 */

// 1. Header
require_smart('header.php', $lang, $ua_includes, $root_includes);

// 2. Хлебные крошки
require_smart('breadcrumbs.php', $lang, $ua_includes, $root_includes);

// 3. Получаем статьи
$articles = $db->select('pages', '*', [
    'type'  => 'articles',
    'lang'  => $lang,
    'ORDER' => ['id' => 'DESC']
]);

$btn_text   = ($lang == 'ua') ? 'Детальніше' : 'Подробнее';
$empty_text = ($lang == 'ua') ? 'Статей поки немає.' : 'Статей пока нет.';
$prefix     = ($lang == 'ua') ? '/ua/' : '/';
?>

<section class="sec">
    <div class="sec-inner">
        <h1 class="sec-title"><?= $custom_h1 ?></h1>
        <?php if (!empty($custom_p)): ?>
            <div class="text-block" style="margin-bottom:32px;"><?= $custom_p ?></div>
        <?php endif; ?>

        <?php if (!empty($articles)): ?>
        <div class="blog-grid">
            <?php foreach ($articles as $item):
                $link = $prefix . ltrim($item['slug'], '/');
            ?>
            <a href="<?= $link ?>" class="blog-card">
                <?php if (!empty($item['hero_image'])): ?>
                    <img src="<?= htmlspecialchars($item['hero_image']) ?>"
                         alt="<?= htmlspecialchars($item['breadcrumb_title']) ?>"
                         loading="lazy">
                <?php endif; ?>
                <div class="blog-body">
                    <div class="blog-title"><?= htmlspecialchars($item['breadcrumb_title']) ?></div>
                    <div class="blog-desc"><?= htmlspecialchars(mb_substr($item['meta_description'] ?? '', 0, 140)) ?></div>
                    <span class="blog-btn"><?= $btn_text ?></span>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
            <p style="text-align:center;color:#888;"><?= $empty_text ?></p>
        <?php endif; ?>
    </div>
</section>

<?php
// SEO-текст если есть
if (!empty($blocks)) {
    foreach ($blocks as $block) {
        if ($block['block_type'] == 'text') {
            echo '<section class="sec"><div class="sec-inner"><div class="text-block">';
            echo $block['content'];
            echo '</div></div></section>';
        }
    }
}

// Footer
require_smart('footer.php', $lang, $ua_includes, $root_includes);
?>
