<?php
/**
 * Блок "Читайте также" — Новый дизайн (blog-card стиль)
 */
global $db, $lang, $page;

$related = $db->select('pages', '*', [
    'type'  => 'articles',
    'lang'  => $lang,
    'id[!]' => $page['id'],
    'ORDER' => $db->raw('RAND()'),
    'LIMIT' => 3
]);

if (empty($related)) return;

$is_ua = ($lang === 'ua');
$title = $is_ua ? 'Читайте також' : 'Читайте также';
$btn   = $is_ua ? 'Детальніше' : 'Подробнее';
$prefix = $is_ua ? '/ua/' : '/';
?>
<section class="sec defer-render" style="background:#f8f8f6">
    <div class="sec-inner">
        <h2 class="sec-title"><?= $title ?></h2>
        <div class="blog-grid">
            <?php foreach ($related as $post):
                $link = $prefix . ltrim($post['slug'], '/');
            ?>
            <a href="<?= $link ?>" class="blog-card">
                <?php if (!empty($post['hero_image'])): ?>
                    <img src="<?= htmlspecialchars($post['hero_image']) ?>"
                         alt="<?= htmlspecialchars($post['breadcrumb_title']) ?>"
                         loading="lazy">
                <?php endif; ?>
                <div class="blog-body">
                    <div class="blog-title"><?= htmlspecialchars($post['breadcrumb_title']) ?></div>
                    <div class="blog-desc"><?= htmlspecialchars(mb_substr($post['meta_description'] ?? '', 0, 120)) ?>...</div>
                    <span class="blog-btn"><?= $btn ?></span>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
