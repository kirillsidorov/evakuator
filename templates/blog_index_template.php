<?php
// templates/blog_index_template.php
// ШАБЛОН СТРАНИЦЫ БЛОГА (АРХИВ НОВОСТЕЙ)

require_smart('header.php', $lang, $ua_includes, $root_includes);
require_smart('breadcrumbs.php', $lang, $ua_includes, $root_includes);

// 1. Получаем список статей из базы
// Берем только тип 'articles' и текущий язык, сортируем от новых к старым
$articles = $db->select('pages', '*', [
    'type' => 'articles',
    'lang' => $lang,
    'ORDER' => ['id' => 'DESC']
]);

// Тексты для кнопки и пустого состояния
$btn_text = ($lang == 'ua') ? 'Детальніше' : 'Подробнее';
$empty_text = ($lang == 'ua') ? 'Статей поки немає.' : 'Статей пока нет.';

?>

<section class="mbr-section content4 cid-sDSrw8qCmX" id="ij">
    <div class="container">
        <div class="media-container-row">
            <div class="title col-12 col-md-8">
                <h1 class="align-center pb-3 mbr-fonts-style display-2">
                    <?= $custom_h1 ?>
                </h1>
                
                <?php if (!empty($custom_p)): ?>
                    <p class="mbr-text align-center pb-3 mbr-fonts-style display-5">
                        <?= $custom_p ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<section class="features3 cid-sDH4ywWIgz" id="features3-ft">
    <div class="container">
        <div class="row">
            <?php if (!empty($articles)): ?>
                <?php foreach ($articles as $item): 
                    // Формируем ссылку: если язык UA, добавляем префикс /ua/
                    $link_prefix = ($lang == 'ua') ? '/ua/' : '/';
                    // Если slug уже содержит слэш в начале, убираем дублирование (на всякий случай)
                    $slug_clean = ltrim($item['slug'], '/');
                    $article_link = $link_prefix . $slug_clean;
                ?>
                <div class="card p-3 col-12 col-md-6 col-lg-4">
                    <div class="card-wrapper">
                        <div class="card-img">
                            <img src="<?= $item['hero_image'] ?>" alt="<?= $item['breadcrumb_title'] ?>">
                        </div>
                        <div class="card-box">
                            <h4 class="card-title mbr-fonts-style display-7">
                                <?= $item['breadcrumb_title'] ?>
                            </h4>
                            <p class="mbr-text mbr-fonts-style display-7">
                                <?= $item['meta_description'] ?>
                            </p>
                        </div>
                        <div class="mbr-section-btn text-center">
                            <a href="<?= $article_link ?>" class="btn btn-primary display-4">
                                <?= $btn_text ?>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <p class="text-center"><?= $empty_text ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php
// Если вдруг для страницы Блога мы добавим SEO-текст в админке, выведем его внизу
if (!empty($blocks)) {
    foreach ($blocks as $block) {
        if ($block['block_type'] == 'text') {
             echo "<div class='container'><div class='row'><div class='col-12'>";
             echo $block['content'];
             echo "</div></div></div>";
        }
    }
}

require_smart('footer.php', $lang, $ua_includes, $root_includes);
?>