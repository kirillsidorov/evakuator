<?php
// sitemap_gen.php — генерит ДВА sitemap:
//   sitemap.xml       — все страницы (услуги, локации, статика), БЕЗ блоговых статей
//   sitemap-blog.xml  — только статьи блога, lastmod = дата статьи, будущие скрыты
require_once $_SERVER['DOCUMENT_ROOT'] . '/db.php';

$site_url = "https://evakuator-kharkov.kh.ua";
$today = date("Y-m-d");

// --- один <url> с hreflang ru/ua/x-default ---
function urlNode($loc, $ru, $ua, $lastmod, $priority) {
    $s  = "\t<url>" . PHP_EOL;
    $s .= "\t\t<loc>" . htmlspecialchars($loc) . "</loc>" . PHP_EOL;
    $s .= "\t\t<xhtml:link rel=\"alternate\" hreflang=\"ru-UA\" href=\"" . htmlspecialchars($ru) . "\" />" . PHP_EOL;
    $s .= "\t\t<xhtml:link rel=\"alternate\" hreflang=\"uk-UA\" href=\"" . htmlspecialchars($ua) . "\" />" . PHP_EOL;
    $s .= "\t\t<xhtml:link rel=\"alternate\" hreflang=\"x-default\" href=\"" . htmlspecialchars($ru) . "\" />" . PHP_EOL;
    $s .= "\t\t<lastmod>" . $lastmod . "</lastmod>" . PHP_EOL;
    $s .= "\t\t<priority>" . $priority . "</priority>" . PHP_EOL;
    $s .= "\t</url>" . PHP_EOL;
    return $s;
}
function wrapUrlset($body) {
    return '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL
        . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">' . PHP_EOL
        . $body . '</urlset>';
}

// =======================================================
// 1. РАЗБИРАЕМ СТРАНИЦЫ ИЗ БД: статьи отдельно от остального
// =======================================================
$pages = $db->select("pages", ["slug", "type", "date"]);
$page_slugs = [];     // не-статьи
$articles   = [];     // slug => date (последнее обновление)

if ($pages) {
    foreach ($pages as $page) {
        $slug = trim($page['slug'], '/');
        if (($page['type'] ?? '') === 'articles') {
            // будущие статьи (ещё не вышли) в sitemap не включаем
            if (!empty($page['date']) && $page['date'] > $today) continue;
            if (!isset($articles[$slug])) {
                $articles[$slug] = !empty($page['date']) ? $page['date'] : $today;
            }
        } else {
            if (!in_array($slug, $page_slugs)) $page_slugs[] = $slug;
        }
    }
}

// Статические страницы (все — НЕ статьи)
$static_pages = [
    '', 'price', 'avtopark-evakuatorov', 'phone-number',
    'gruzovoy-evakuator-kharkov', 'evakuator-manipulator-kharkov', 'poputnyy-evakuator',
    'Perevozka-spetstekhniki-Kharkov', 'avtosos', 'avtovykup-kharkov', 'sto-kharkov',
    'blog', 'evakuator-pri-dtp',
    // Районы
    'evakuator-aleksseyevka', 'evakuator-saltovka', 'evakuator-pesochin',
    'evakuator-kholodnaya-gora', 'evakuator-novyye-doma', 'evakuator-xtz',
    // Область и межгород
    'evakuator-po-kharkovskoy-oblasti', 'evakuator-po-ukraine', 'evakuator-merefa',
    'evakuator-Chuguyev', 'evakuator-balakleya', 'evakuator-Izyum',
    'evakuator-Kupyansk', 'evakuator-Lozovaya', 'evakuator-valki'
];
$page_slugs = array_values(array_unique(array_merge($page_slugs, $static_pages)));

// =======================================================
// 2. sitemap.xml — СТРАНИЦЫ
// =======================================================
$body = "";
$cnt_pages = 0;
foreach ($page_slugs as $slug) {
    $priority = ($slug === '') ? '1.0' : '0.8';
    $ru = ($slug === '') ? $site_url . '/' : $site_url . '/' . $slug;
    $ua = ($slug === '') ? $site_url . '/ua/' : $site_url . '/ua/' . $slug;
    $body .= urlNode($ru, $ru, $ua, $today, $priority);
    $body .= urlNode($ua, $ru, $ua, $today, $priority);
    $cnt_pages += 2;
}
$ok_pages = file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/sitemap.xml', wrapUrlset($body));

// =======================================================
// Карта блога (/sitemap-blog.xml) — ДИНАМИЧЕСКАЯ (sitemap-blog.php),
// обновляется сама, здесь её НЕ генерим.
// =======================================================
if ($ok_pages !== false) {
    echo "<h3>✅ sitemap.xml обновлён (страницы).</h3>";
    echo "<p>URL страниц: <strong>{$cnt_pages}</strong></p>";
    echo "<p>Карта блога динамическая: <a href='/sitemap-blog.xml' target='_blank'>/sitemap-blog.xml</a> — обновляется сама, генерить не нужно.</p>";
    echo "<a href='/sitemap.xml' target='_blank' style='display:inline-block;margin-right:12px;padding:10px 15px;background:#0dcaf0;color:#fff;text-decoration:none;border-radius:5px;'>sitemap.xml</a>";
    echo "<a href='/admin.php' style='display:inline-block;padding:10px 15px;background:#6c757d;color:#fff;text-decoration:none;border-radius:5px;'>В админку</a>";
} else {
    echo "<h3 style='color:red;'>❌ Ошибка записи sitemap.xml. Проверьте права (CHMOD).</h3>";
}
?>
