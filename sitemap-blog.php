<?php
// sitemap-blog.php — ДИНАМИЧЕСКАЯ карта блога.
// Отдаётся на /sitemap-blog.xml (rewrite в .htaccess). Всегда актуальна:
// статья с наступившей датой попадает в карту автоматически, без перегенерации.
//
// Обновлено (фиксы):
//   1. ОТЛОЖЕННАЯ ПУБЛИКАЦИЯ ПРОВЕРЯЕТСЯ ОТДЕЛЬНО ДЛЯ КАЖДОГО ЯЗЫКА.
//      Раньше выборка шла без поля lang, на один слаг приходило две строки
//      (RU и UA) с разными датами, дедупликация оставляла одну, а цикл
//      безусловно печатал ОБЕ ссылки. Если русская версия вышла, а
//      украинская запланирована на будущее — /ua/slug всё равно попадал
//      в карту, и Googlebot получал по нему 404 от роутера.
//   2. Ссылка на языковую версию печатается, только если эта версия
//      реально существует в таблице pages и уже опубликована.
//      Раньше /ua/slug и xhtml:link на него печатались всегда, даже когда
//      украинского перевода не было вовсе. Hreflang на 404 заставляет
//      Google отбросить всю языковую связку — выпадают обе версии.
//   3. Блок xhtml:link выводится только при наличии обеих версий.
//      Одноязычной статье альтернаты не нужны.

require_once $_SERVER['DOCUMENT_ROOT'] . '/db.php';
header('Content-Type: application/xml; charset=UTF-8');

$site_url = "https://evakuator-kharkov.kh.ua";
$today    = date("Y-m-d");

// lang нужен обязательно — без него две языковые строки неразличимы
$rows = $db->select("pages", ["slug", "date", "lang"], [
    "type"  => "articles",
    "ORDER" => ["date" => "DESC"]
]);

// slug => ['ru' => lastmod, 'ua' => lastmod] — только опубликованные версии
$published = [];

if ($rows) {
    foreach ($rows as $p) {
        $slug = trim((string)$p['slug'], '/');
        if ($slug === '') continue;

        $lang = (($p['lang'] ?? 'ru') === 'ua') ? 'ua' : 'ru';

        // Будущая дата — версия ещё не вышла, в карту не идёт.
        // Проверяем ИМЕННО эту языковую версию, а не слаг целиком.
        if (!empty($p['date']) && $p['date'] > $today) continue;

        if (!isset($published[$slug][$lang])) {
            $published[$slug][$lang] = !empty($p['date']) ? $p['date'] : $today;
        }
    }
}

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">' . "\n";

foreach ($published as $slug => $versions) {

    $has_ru = isset($versions['ru']);
    $has_ua = isset($versions['ua']);
    $both   = $has_ru && $has_ua;

    $ru = $site_url . '/' . $slug;
    $ua = $site_url . '/ua/' . $slug;

    // Печатаем <url> только для тех версий, что реально опубликованы
    $entries = [];
    if ($has_ru) $entries['ru'] = [$ru, $versions['ru']];
    if ($has_ua) $entries['ua'] = [$ua, $versions['ua']];

    foreach ($entries as $entry_lang => $entry) {
        list($loc, $lastmod) = $entry;

        echo "\t<url>\n";
        echo "\t\t<loc>" . htmlspecialchars($loc) . "</loc>\n";

        // Альтернаты — только когда обе версии опубликованы.
        // Иначе получился бы hreflang на несуществующую страницу.
        if ($both) {
            echo "\t\t<xhtml:link rel=\"alternate\" hreflang=\"ru-UA\" href=\"" . htmlspecialchars($ru) . "\" />\n";
            echo "\t\t<xhtml:link rel=\"alternate\" hreflang=\"uk-UA\" href=\"" . htmlspecialchars($ua) . "\" />\n";
            echo "\t\t<xhtml:link rel=\"alternate\" hreflang=\"x-default\" href=\"" . htmlspecialchars($ru) . "\" />\n";
        }

        echo "\t\t<lastmod>" . htmlspecialchars($lastmod) . "</lastmod>\n";
        echo "\t\t<priority>0.7</priority>\n";
        echo "\t</url>\n";
    }
}

echo '</urlset>';
