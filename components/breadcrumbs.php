<?php
/**
 * Хлебные крошки — Новый дизайн
 * Классы совпадают с theme.css (.breadcrumbs, .breadcrumbs-inner, etc.)
 */

$home_url  = ($lang == 'ua') ? '/ua/' : '/';
$home_name = ($lang == 'ua') ? 'Евакуатор' : 'Эвакуатор';

$breadcrumbs = [];

// =========================================================
// ВАРИАНТ 1: СТРАНИЦА ИЗ БАЗЫ (ROUTER)
// =========================================================
if (isset($page) && !empty($page['id']) && ($page['type'] ?? '') !== 'physical_file') {
    global $db;

    $current_name = !empty($page['breadcrumb_title']) ? $page['breadcrumb_title'] : strip_tags($page['h1']);
    $breadcrumbs[] = ['name' => $current_name, 'url' => ''];

    $parent_id = $page['parent_id'] ?? 0;
    $depth = 0;
    while ($parent_id && $depth < 5) {
        $parent = $db->get('pages', '*', ['id' => $parent_id]);
        if ($parent) {
            $p_url  = ($parent['lang'] == 'ua') ? "/ua/{$parent['slug']}" : "/{$parent['slug']}";
            $p_name = !empty($parent['breadcrumb_title']) ? $parent['breadcrumb_title'] : strip_tags($parent['h1']);
            $breadcrumbs[] = ['name' => $p_name, 'url' => $p_url];
            $parent_id = $parent['parent_id'];
            $depth++;
        } else {
            break;
        }
    }

    $breadcrumbs[] = ['name' => $home_name, 'url' => $home_url];
    $breadcrumbs = array_reverse($breadcrumbs);
}
// =========================================================
// ВАРИАНТ 2: ФИЗИЧЕСКИЕ ФАЙЛЫ (LEGACY)
// =========================================================
else {
    $breadcrumbs[] = ['name' => $home_name, 'url' => $home_url];

    $is_district = false;
    if (isset($location_type) && $location_type == 'district') $is_district = true;
    elseif (isset($loc['type']) && $loc['type'] == 'district') $is_district = true;

    $is_location   = (isset($page_type) && $page_type == 'locations');
    $is_oblast_url = (strpos($_SERVER['REQUEST_URI'], 'evakuator-po-kharkovskoy-oblasti') !== false);

    if ($is_location && !$is_oblast_url && !$is_district) {
        $oblast_name = ($lang == 'ua') ? 'Харківська область' : 'Харьковская область';
        $oblast_url  = ($lang == 'ua') ? '/ua/evakuator-po-kharkovskoy-oblasti' : '/evakuator-po-kharkovskoy-oblasti';
        $breadcrumbs[] = ['name' => $oblast_name, 'url' => $oblast_url];
    }

    $last = '';
    if (isset($breadcrumb_title))   $last = $breadcrumb_title;
    elseif (isset($loc['name']))    $last = $loc['name'];
    elseif (isset($custom_h1))      $last = strip_tags($custom_h1);
    elseif (isset($title))          $last = $title;
    else                            $last = ($lang == 'ua') ? 'Сторінка' : 'Страница';

    if (mb_strlen($last) > 50) $last = mb_substr($last, 0, 50) . '...';
    $breadcrumbs[] = ['name' => $last, 'url' => ''];
}
?>
<nav class="breadcrumbs" aria-label="breadcrumb">
    <ol class="breadcrumbs-inner" itemscope itemtype="https://schema.org/BreadcrumbList">
        <?php foreach ($breadcrumbs as $i => $crumb):
            $is_last = ($i === count($breadcrumbs) - 1);
        ?>
        <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" style="display:inline-flex;align-items:center;gap:8px;">
            <?php if (!$is_last): ?>
                <a itemprop="item" href="<?= $crumb['url'] ?>"><span itemprop="name"><?= $crumb['name'] ?></span></a>
                <span class="breadcrumbs-sep">/</span>
            <?php else: ?>
                <span itemprop="name" style="color:#111;"><?= $crumb['name'] ?></span>
            <?php endif; ?>
            <meta itemprop="position" content="<?= $i + 1 ?>">
        </li>
        <?php endforeach; ?>
    </ol>
</nav>
