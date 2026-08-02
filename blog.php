<?php
// news.php — Блог (новый дизайн)
include_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

$page_type = 'archive';

// Мета-теги страницы блога
$title = ($lang == 'ua')
    ? "Блог компанії Евакуатор Харків | Корисні статті"
    : "Блог компании Эвакуатор Харьков | Полезные статьи";
$description = ($lang == 'ua')
    ? "Корисні статті про евакуацію авто, обслуговування СТО та автовикуп у Харкові. Поради, інструкції та новини компанії Евакуатор Харків."
    : "Полезные статьи об эвакуации авто, обслуживании СТО и автовыкупе в Харькове. Советы, инструкции и новости компании Эвакуатор Харьков.";

$breadcrumb_title = ($lang == 'ua') ? 'Блог' : 'Блог';
$h1_title = ($lang == 'ua') ? 'Блог компанії "Евакуатор Харків"' : 'Блог компании "Эвакуатор Харьков"';
$btn_text = ($lang == 'ua') ? 'Детальніше' : 'Подробнее';
$empty_text = ($lang == 'ua') ? 'Статей поки немає.' : 'Статей пока нет.';
$link_prefix = ($lang == 'ua') ? '/ua/' : '/';

// Берём статьи из базы (будущие даты скрыты — автопубликация)
$articles = $db->select('pages', '*', [
    'type'  => 'articles',
    'lang'  => $lang,
    'OR' => [
        'date[<=]' => date('Y-m-d'),
        'date'     => null,
    ],
    'ORDER' => ['date' => 'DESC', 'id' => 'DESC']
]);

// человекочитаемая дата
$ru_months = ['', 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'];
$ua_months = ['', 'січня', 'лютого', 'березня', 'квітня', 'травня', 'червня', 'липня', 'серпня', 'вересня', 'жовтня', 'листопада', 'грудня'];
$fmt_date = function ($d) use ($lang, $ru_months, $ua_months) {
    if (empty($d)) return '';
    $ts = strtotime($d);
    $m = ($lang == 'ua') ? $ua_months : $ru_months;
    return (int)date('j', $ts) . ' ' . $m[(int)date('n', $ts)] . ' ' . date('Y', $ts);
};

include $_SERVER['DOCUMENT_ROOT'] . '/components/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/components/breadcrumbs.php';
?>

<section class="sec" style="padding-bottom:10px;">
    <div class="sec-inner">
        <h1 class="sec-title" style="margin-bottom:10px;font-size:clamp(32px,8vw,48px);"><?= $h1_title ?></h1>
    </div>
</section>

<section class="sec">
    <div class="sec-inner">
        <?php if (!empty($articles)): ?>
        <div class="blog-grid">
            <?php foreach ($articles as $item):
                $link = $link_prefix . ltrim($item['slug'], '/');
            ?>
            <a href="<?= $link ?>" class="blog-card">
                <?php if (!empty($item['hero_image'])): ?>
                    <img src="<?= htmlspecialchars($item['hero_image']) ?>"
                         alt="<?= htmlspecialchars($item['breadcrumb_title']) ?>"
                         loading="lazy">
                <?php endif; ?>
                <div class="blog-body">
                    <?php if (!empty($item['date'])): ?><div class="blog-date" style="color:#8a8a8a;font-size:13px;margin-bottom:6px;"><?= $fmt_date($item['date']) ?></div><?php endif; ?>
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

<?php include $_SERVER['DOCUMENT_ROOT'] . '/components/footer.php'; ?>
