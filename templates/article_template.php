<?php
// templates/article_template.php
// ШАБЛОН ДЛЯ ОДИНОЧНОЙ СТАТЬИ

require_smart('header.php', $lang, $ua_includes, $root_includes);

// Хлебные крошки
if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/breadcrumbs.php')) {
    include $_SERVER['DOCUMENT_ROOT'] . '/breadcrumbs.php';
} else {
    require_smart('breadcrumbs.php', $lang, $ua_includes, $root_includes);
}

// 1. Заголовок (H1 Simple - без фона)
require_smart('h1_article.php', $lang, $ua_includes, $root_includes);

// 2. ГЛАВНАЯ КАРТИНКА (Новая секция)
// Если в базе у страницы есть hero_image, выводим её отдельным блоком
if (!empty($page['hero_image'])) {
?>
    <section class="cid-article-image" id="article-image-section" style="padding-bottom: 20px; background-color: #ffffff;">
        <div class="container">
            <div class="media-container-row">
                <div class="col-12 col-md-10">
                    <div class="image-wrapper" style="border-radius: 8px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
                        <img src="<?= $page['hero_image'] ?>"
                            alt="<?= htmlspecialchars($page['h1']) ?>"
                            title="<?= htmlspecialchars($page['h1']) ?>"
                            style="width: 100%; height: auto; display: block;">
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php
}
?>

<section class="mbr-section article content1 cid-sfh9tj5sqS">
    <div class="container">
        <div class="media-container-row">
            <div class="mbr-text col-12 mbr-fonts-style display-7 col-md-8">

                <?php
                // Вывод блоков контента из базы
                if (!empty($blocks)) {
                    foreach ($blocks as $block) {
                        if ($block['block_type'] == 'text') {
                            echo $block['content'];
                        } elseif ($block['block_type'] == 'structured_content') {
                            $items_array = json_decode($block['content'], true);
                            render_structured_content($items_array);
                        } elseif ($block['block_type'] == 'include') {
                            require_smart($block['block_path'], $lang, $ua_includes, $root_includes);
                        }
                    }
                }
                ?>

            </div>
        </div>
    </div>
</section>

<?php
if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/includes/related.php')) {
    include $_SERVER['DOCUMENT_ROOT'] . '/includes/related.php';
}
?>

<section class="cid-back-btn pb-5" style="background-color: #f9f9f9; padding-top: 30px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4 text-center">
                <a href="<?= ($lang == 'ua' ? '/ua/news' : '/news') ?>" class="btn btn-secondary display-4">
                    <?= ($lang == 'ua' ? '← Повернутися до блогу' : '← Вернуться в блог') ?>
                </a>
            </div>
        </div>
    </div>
</section>

<?php
require_smart('footer.php', $lang, $ua_includes, $root_includes);
?>