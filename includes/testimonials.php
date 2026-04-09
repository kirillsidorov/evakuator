<?php
// testimonials.php
global $db, $lang;

// 1. Получаем ВСЕ отзывы из базы
$all_reviews = $db->select("testimonials", "*");

// 2. Фильтрация: только с текстом и рейтингом >= 4
$filtered_reviews = array_filter($all_reviews, function($review) {
    return !empty($review['text_ru']) && $review['rating'] >= 4;
});
$clean_reviews = array_values($filtered_reviews);

// 3. SEO-РАНДОМ: зависит от адреса страницы — одни и те же отзывы
//    на одной странице при каждом заходе (хорошо для индексации)
$random_reviews = [];
$count = count($clean_reviews);
$show_limit = 3;

if ($count > 0) {
    $current_page_url = $_SERVER['REQUEST_URI'] ?? 'home';
    $seed = crc32($current_page_url);
    mt_srand($seed);

    $indices = array_keys($clean_reviews);

    // Кастомное перемешивание на основе seed
    for ($i = count($indices) - 1; $i > 0; $i--) {
        $j = mt_rand(0, $i);
        $temp      = $indices[$i];
        $indices[$i] = $indices[$j];
        $indices[$j] = $temp;
    }

    $selected_keys = array_slice($indices, 0, $show_limit);
    foreach ($selected_keys as $key) {
        $random_reviews[] = $clean_reviews[$key];
    }

    mt_srand(); // сбрасываем seed
}

// 4. Локализация интерфейса
$labels = [
    'ru' => ['title' => 'ОТЗЫВЫ НАШИХ КЛИЕНТОВ',  'prev' => 'Назад', 'next' => 'Вперед',
             'img_alt' => 'Эвакуатор Харьков отзывы', 'org_name' => 'Эвакуатор Харьков'],
    'ua' => ['title' => 'ВІДГУКИ НАШИХ КЛІЄНТІВ',  'prev' => 'Назад', 'next' => 'Вперед',
             'img_alt' => 'Евакуатор Харків відгуки', 'org_name' => 'Евакуатор Харків'],
];
$l = $labels[$lang] ?? $labels['ru'];

$carousel_id = "reviewsCarousel" . strtoupper($lang);
?>

<section class="carousel slide testimonials-slider cid-s3YpLCYQ5l mbr-parallax-background" id="testimonials-slider1-cp">
    <div class="mbr-overlay" style="opacity: 0.6; background-color: rgb(0, 0, 0);"></div>

    <div class="container text-center">
        <h2 class="pb-5 mbr-fonts-style display-2"><?= $l['title'] ?></h2>

        <div class="carousel slide" data-ride="carousel" id="<?= $carousel_id ?>">
            <div class="carousel-inner">

                <?php foreach ($random_reviews as $index => $review): ?>
                    <?php
                        $author = $review['author_' . $lang] ?? $review['author_ru'];
                        $text   = $review['text_' . $lang]   ?? $review['text_ru'];
                        
                        $webp_url = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $review['image']);
                    ?>
                    <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>" itemscope itemtype="https://schema.org/Review">

                        <div itemprop="itemReviewed" itemscope itemtype="https://schema.org/Organization">
                            <meta itemprop="name"  content="<?= htmlspecialchars($l['org_name']) ?>">
                            <meta itemprop="image" content="/assets/images/logo.png">
                        </div>

                        <div itemprop="reviewRating" itemscope itemtype="https://schema.org/Rating">
                            <meta itemprop="ratingValue" content="<?= $review['rating'] ?>">
                            <meta itemprop="bestRating"  content="5">
                        </div>
                        <meta itemprop="datePublished" content="<?= $review['date'] ?>">

                        <div class="user col-md-8">
                            <div class="user_image">
                                <picture>
                                    <source srcset="<?= htmlspecialchars($webp_url) ?>" type="image/webp">
                                    <img src="<?= htmlspecialchars($review['image']) ?>"
                                        alt="<?= htmlspecialchars($l['img_alt']) ?>"
                                        title="<?= htmlspecialchars($l['img_alt']) ?>"
                                        loading="lazy">
                                </picture>
                            </div>
                            <div class="user_text pb-3">
                                <p class="mbr-fonts-style display-7" itemprop="reviewBody">
                                    <?= htmlspecialchars($text) ?>
                                </p>
                            </div>
                            <div class="user_name mbr-bold pb-2 mbr-fonts-style display-7"
                                 itemprop="author" itemscope itemtype="https://schema.org/Person">
                                <span itemprop="name"><?= htmlspecialchars($author) ?></span>
                            </div>
                        </div>

                    </div>
                <?php endforeach; ?>

            </div>

            <div class="carousel-controls">
                <a class="carousel-control-prev" href="#<?= $carousel_id ?>" role="button" data-slide="prev" aria-label="<?= $l['prev'] ?>">
                    <span aria-hidden="true" class="mbri-arrow-prev mbr-iconfont"></span>
                    <span class="sr-only"><?= $l['prev'] ?></span>
                </a>
                <a class="carousel-control-next" href="#<?= $carousel_id ?>" role="button" data-slide="next" aria-label="<?= $l['next'] ?>">
                    <span aria-hidden="true" class="mbri-arrow-next mbr-iconfont"></span>
                    <span class="sr-only"><?= $l['next'] ?></span>
                </a>
            </div>
        </div>
    </div>
</section>