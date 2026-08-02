<?php
/**
 * Шаблон H1 для статей блога — с мета-строкой (дата, автор, время чтения)
 */
global $page, $lang, $blocks, $custom_h1;
$is_ua = ($lang === 'ua');

// Дата публикации (человекочитаемо)
$date_str = '';
if (!empty($page['date'])) {
    $ts = strtotime($page['date']);
    $months = $is_ua
        ? ['', 'січня', 'лютого', 'березня', 'квітня', 'травня', 'червня', 'липня', 'серпня', 'вересня', 'жовтня', 'листопада', 'грудня']
        : ['', 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'];
    $date_str = (int)date('j', $ts) . ' ' . $months[(int)date('n', $ts)] . ' ' . date('Y', $ts);
}

// Время чтения (≈ по объёму текста)
$txt = '';
if (!empty($blocks)) {
    foreach ($blocks as $b) {
        $c = $b['content'] ?? '';
        $txt .= ' ' . (is_string($c) ? $c : json_encode($c));
    }
}
$mins = max(1, (int)round(mb_strlen(strip_tags($txt)) / 1200));
$read_str = $mins . ' ' . ($is_ua ? 'хв читання' : 'мин чтения');

$author = $is_ua ? 'Редакція «Евакуатор Харків»' : 'Редакция «Эвакуатор Харьков»';
?>
<section class="sec" style="padding-bottom: 10px;">
  <div class="sec-inner">
    <h1 class="sec-title" style="margin-bottom: 12px; font-size: clamp(32px, 8vw, 48px);">
        <?= $custom_h1 ?>
    </h1>
    <div class="article-meta" style="color:#8a8a8a;font-size:14px;display:flex;flex-wrap:wrap;gap:6px 14px;align-items:center;">
        <span><?= $author ?></span>
        <?php if ($date_str): ?><span aria-hidden="true">•</span><span><?= ($is_ua ? 'Оновлено ' : 'Обновлено ') . $date_str ?></span><?php endif; ?>
        <span aria-hidden="true">•</span><span><?= $read_str ?></span>
    </div>
  </div>
</section>
