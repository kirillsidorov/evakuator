<?php
/**
 * Блок "Отзывы" — Новый дизайн (карточки, без картинок)
 * Берёт из БД 3 отзыва, детерминированный рандом по URL (для SEO)
 */
global $db, $lang;

$all_reviews = $db->select("testimonials", "*");

$filtered = array_filter($all_reviews, function($r) {
    return !empty($r['text_ru']) && $r['rating'] >= 4;
});
$clean = array_values($filtered);

$reviews = [];
$count = count($clean);
$show = 3;

if ($count > 0) {
    $seed = crc32($_SERVER['REQUEST_URI'] ?? 'home');
    mt_srand($seed);
    $idx = array_keys($clean);
    for ($i = count($idx) - 1; $i > 0; $i--) {
        $j = mt_rand(0, $i);
        [$idx[$i], $idx[$j]] = [$idx[$j], $idx[$i]];
    }
    foreach (array_slice($idx, 0, $show) as $k) {
        $reviews[] = $clean[$k];
    }
    mt_srand();
}

if (empty($reviews)) return;

$is_ua = ($lang === 'ua');
$sec_label = $is_ua ? 'Відгуки' : 'Отзывы';
$sec_title = $is_ua ? 'Клієнти про нас' : 'Клиенты о нас';
?>
<section class="sec defer-render">
    <div class="sec-inner">
        <div class="sec-label"><?= $sec_label ?></div>
        <h2 class="sec-title"><?= $sec_title ?></h2>
        <div class="reviews-grid">
            <?php foreach ($reviews as $review):
                $author = $review['author_' . $lang] ?? $review['author_ru'];
                $text   = $review['text_' . $lang]   ?? $review['text_ru'];
                $rating = (int)$review['rating'];
                // Инициалы
                $parts = explode(' ', trim($author));
                $initials = '';
                foreach (array_slice($parts, 0, 2) as $p) {
                    $initials .= mb_substr($p, 0, 1);
                }
                $initials = mb_strtoupper($initials);
                // Звёзды
                $stars = str_repeat('★', $rating) . str_repeat('☆', 5 - $rating);
            ?>
            <div class="review" itemscope itemtype="https://schema.org/Review">
                <div itemprop="itemReviewed" itemscope itemtype="https://schema.org/Organization">
                    <meta itemprop="name" content="<?= $is_ua ? 'Евакуатор Харків' : 'Эвакуатор Харьков' ?>">
                </div>
                <div itemprop="reviewRating" itemscope itemtype="https://schema.org/Rating">
                    <meta itemprop="ratingValue" content="<?= $rating ?>">
                    <meta itemprop="bestRating" content="5">
                </div>
                <meta itemprop="datePublished" content="<?= $review['date'] ?>">
                <div class="review-header">
                    <div class="review-avatar"><?= $initials ?></div>
                    <div>
                        <div class="review-name" itemprop="author" itemscope itemtype="https://schema.org/Person">
                            <span itemprop="name"><?= htmlspecialchars($author) ?></span>
                        </div>
                        <div class="review-stars"><?= $stars ?></div>
                    </div>
                </div>
                <p class="review-text" itemprop="reviewBody"><?= htmlspecialchars($text) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
