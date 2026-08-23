<?php
/**
 * ШАБЛОН: Индекс блога (список статей)
 *
 * Обновлено (фиксы):
 *   1. ОТЛОЖЕННЫЕ СТАТЬИ БОЛЬШЕ НЕ ПОПАДАЮТ В СПИСОК.
 *      router.php отдаёт 404 на статью с будущей датой, а индекс её
 *      всё равно показывал — ссылка вела в никуда и для людей, и для
 *      Googlebot. Фильтр зеркалит проверку из роутера.
 *   2. Заголовки карточек — <h2> вместо <div> (та же история, что была
 *      с sec-title: реальных заголовков в разметке не было).
 *   3. apply_placeholders() применяется к SEO-тексту внизу.
 *   4. Обрезка описания по границе слова, а не по 140-му символу.
 */

global $settings;

// 1. Header
require_smart('header.php');

// 2. Хлебные крошки
require_smart('breadcrumbs.php');

// 3. Получаем статьи
$articles = $db->select('pages', '*', [
    'type'  => 'articles',
    'lang'  => $lang,
    'ORDER' => ['id' => 'DESC']
    // Если захочешь сортировку по дате публикации, замени строку выше на:
    // 'ORDER' => ['date' => 'DESC', 'id' => 'DESC']
    // Учти: у статей с пустым date порядок изменится.
]);

// 3.1 Прячем отложенные публикации — та же проверка, что в router.php
$today = date('Y-m-d');
if (!empty($articles)) {
    $articles = array_values(array_filter($articles, function ($a) use ($today) {
        return empty($a['date']) || $a['date'] <= $today;
    }));
}

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

                // Обрезаем по границе слова, чтобы не рвать посреди слова
                $desc = (string)($item['meta_description'] ?? '');
                if (mb_strlen($desc) > 140) {
                    $desc = mb_substr($desc, 0, 140);
                    $cut  = mb_strrpos($desc, ' ');
                    if ($cut !== false && $cut > 90) $desc = mb_substr($desc, 0, $cut);
                    $desc .= '…';
                }
            ?>
            <a href="<?= $link ?>" class="blog-card">
                <?php if (!empty($item['hero_image'])): ?>
                    <img src="<?= htmlspecialchars($item['hero_image']) ?>"
                         alt="<?= htmlspecialchars($item['breadcrumb_title']) ?>"
                         loading="lazy"
                         decoding="async">
                <?php endif; ?>
                <div class="blog-body">
                    <h2 class="blog-title"><?= htmlspecialchars($item['breadcrumb_title']) ?></h2>
                    <div class="blog-desc"><?= htmlspecialchars($desc) ?></div>
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
    $rendered_includes = ['breadcrumbs.php' => true];

    foreach ($blocks as $block) {
        $bt = $block['block_type'] ?? '';

        if ($bt === 'text') {
            echo '<section class="sec"><div class="sec-inner"><div class="text-block">';
            echo apply_placeholders($block['content'], $city_val, $in_city_val, $price_val, $dist_val, $time_val, $settings);
            echo '</div></div></section>';
        }
        elseif ($bt === 'structured_content') {
            $items = json_decode(trim((string)$block['content']), true);
            if ($items) render_structured_content($items);
        }
        elseif ($bt === 'include') {
            $path = $block['block_path'] ?? '';
            if ($path === '' || !empty($rendered_includes[$path])) continue;
            $rendered_includes[$path] = true;
            require_smart($path);
        }
    }
}

// Footer
require_smart('footer.php');
?>
