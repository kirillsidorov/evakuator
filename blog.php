<?php
// news.php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

$page_type = 'archive';

// Мета-теги для самой страницы блога (можно тоже вынести в базу, но пока оставим как есть)
$title = "ᐈ Блог | 《Услуги Эвакуатора》Харьков и Область |  " . $settings['tel_one_view'] ;
$description = "【Услуги Эвакуатора Харьков и Область 】⭐ 050-851-7555. Быстрая подача от 20 минут. ";

$breadcrumb_title = ($lang == 'ua') ? 'Блог компанії "Евакуатор Харків"' : 'Блог компании "Эвакуатор Харьков"';
$h1_title = ($lang == 'ua') ? 'Блог компанії "Евакуатор Харків"' : 'Блог компании "Эвакуатор Харьков"';
$btn_text = ($lang == 'ua') ? 'Детальніше' : 'Подробнее';

// --- НОВАЯ ЛОГИКА: БЕРЕМ СТАТЬИ ИЗ БАЗЫ ---
$articles = $db->select('pages', '*', [
    'type' => 'articles',
    'lang' => $lang,           // Фильтруем по текущему языку сайта
    'ORDER' => ['id' => 'DESC'] // Сначала новые (по ID)
]);

include $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/breadcrumbs.php';
?>

<section class="mbr-section content4 cid-sDSrw8qCmX" id="ij">
    <div class="container">
        <div class="media-container-row">
            <div class="title col-12 col-md-8">
                <h1 class="align-center pb-3 mbr-fonts-style display-2"><?= $h1_title ?></h1>
            </div>
        </div>
    </div>
</section>

<section class="features3 cid-sDH4ywWIgz" id="features3-ft">
    <div class="container">
        <div class="row">
            <?php if (!empty($articles)): ?>
                <?php foreach ($articles as $item): 
                    // Формируем правильную ссылку в зависимости от языка
                    $link_prefix = ($lang == 'ua') ? '/ua/' : '/';
                    $article_link = $link_prefix . $item['slug'];
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
                    <p class="text-center">
                        <?= ($lang == 'ua') ? 'Статей поки немає.' : 'Статей пока нет.' ?>
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>