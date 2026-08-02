<?php
// sitemap-blog.php — ДИНАМИЧЕСКАЯ карта блога.
// Отдаётся на /sitemap-blog.xml (rewrite в .htaccess). Всегда актуальна:
// статья с наступившей датой попадает в карту автоматически, без перегенерации.
require_once $_SERVER['DOCUMENT_ROOT'] . '/db.php';
header('Content-Type: application/xml; charset=UTF-8');

$site_url = "https://evakuator-kharkov.kh.ua";
$today    = date("Y-m-d");

$rows = $db->select("pages", ["slug", "date"], [
    "type"  => "articles",
    "ORDER" => ["date" => "DESC"]
]);

$articles = []; // slug => lastmod
if ($rows) {
    foreach ($rows as $p) {
        $slug = trim($p['slug'], '/');
        // будущие статьи (ещё не вышли) в карту не включаем
        if (!empty($p['date']) && $p['date'] > $today) continue;
        if (!isset($articles[$slug])) {
            $articles[$slug] = !empty($p['date']) ? $p['date'] : $today;
        }
    }
}

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">' . "\n";
foreach ($articles as $slug => $date) {
    $ru = $site_url . '/' . $slug;
    $ua = $site_url . '/ua/' . $slug;
    foreach ([$ru, $ua] as $loc) {
        echo "\t<url>\n";
        echo "\t\t<loc>" . htmlspecialchars($loc) . "</loc>\n";
        echo "\t\t<xhtml:link rel=\"alternate\" hreflang=\"ru-UA\" href=\"" . htmlspecialchars($ru) . "\" />\n";
        echo "\t\t<xhtml:link rel=\"alternate\" hreflang=\"uk-UA\" href=\"" . htmlspecialchars($ua) . "\" />\n";
        echo "\t\t<xhtml:link rel=\"alternate\" hreflang=\"x-default\" href=\"" . htmlspecialchars($ru) . "\" />\n";
        echo "\t\t<lastmod>" . $date . "</lastmod>\n";
        echo "\t\t<priority>0.7</priority>\n";
        echo "\t</url>\n";
    }
}
echo '</urlset>';
