<?php
// sitemap_gen.php — генерит sitemap.xml (страницы, БЕЗ блоговых статей).
// Карта блога динамическая: sitemap-blog.php, здесь не трогается.
//
// Обновлено (фиксы):
//   1. СТРАНИЦЫ ИЩУТСЯ И В БД, И НА ДИСКЕ.
//      Часть страниц — физические файлы (price.php, phone-number.php,
//      avtopark-evakuatorov.php) и в таблице pages их нет. Украинские
//      версии лежат в /ua/ тонкими обёртками. Раньше генератор знал о них
//      только из захардкоженного списка $static_pages, который разъезжается
//      с реальностью. Теперь: слаг существует, если есть строка в pages
//      ЛИБО файл на диске — отдельно для каждого языка.
//   2. LASTMOD НЕ ЗАТИРАЕТСЯ ЦЕЛИКОМ.
//      Раньше каждый прогон ставил $today всем ~60 адресам, и Google
//      переставал доверять lastmod — ровно тогда, когда сигнал нужен.
//      Теперь даты берутся из существующего sitemap.xml.
//      ?force=1            — обновить дату всем
//      ?touch=slug1,slug2  — поднять дату конкретным страницам
//   3. HREFLANG ТОЛЬКО ПРИ НАЛИЧИИ ОБЕИХ ВЕРСИЙ.
//      Раньше xhtml:link на /ua/slug печатался всегда. Hreflang на 404
//      заставляет Google отбросить всю языковую связку — выпадают обе.
//   4. Слаг 'home' исключён: главная живёт по / и /ua/.

require_once $_SERVER['DOCUMENT_ROOT'] . '/db.php';

$site_url = "https://evakuator-kharkov.kh.ua";
$today    = date("Y-m-d");
$doc      = rtrim($_SERVER['DOCUMENT_ROOT'], '/');
$map_path = $doc . '/sitemap.xml';

$force = !empty($_GET['force']);
$touch = [];
if (!empty($_GET['touch'])) {
    foreach (explode(',', (string)$_GET['touch']) as $t) {
        $t = trim($t, " \t/");
        if ($t !== '') $touch[$t] = true;
    }
}

// Служебные PHP в корне — это не страницы
$system_files = [
    'index', 'router', 'config', 'db', 'Medoo', 'admin', 'edit_page',
    'pages_manager', 'sitemap_gen', 'sitemap-blog', 'secrets', '404', 'home',
];

function urlNode($loc, $ru, $ua, $lastmod, $priority, $both) {
    $s  = "\t<url>" . PHP_EOL;
    $s .= "\t\t<loc>" . htmlspecialchars($loc) . "</loc>" . PHP_EOL;
    if ($both) {
        $s .= "\t\t<xhtml:link rel=\"alternate\" hreflang=\"ru-UA\" href=\"" . htmlspecialchars($ru) . "\" />" . PHP_EOL;
        $s .= "\t\t<xhtml:link rel=\"alternate\" hreflang=\"uk-UA\" href=\"" . htmlspecialchars($ua) . "\" />" . PHP_EOL;
        $s .= "\t\t<xhtml:link rel=\"alternate\" hreflang=\"x-default\" href=\"" . htmlspecialchars($ru) . "\" />" . PHP_EOL;
    }
    $s .= "\t\t<lastmod>" . htmlspecialchars($lastmod) . "</lastmod>" . PHP_EOL;
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
// 0. ПРЕДЫДУЩАЯ КАРТА — чтобы сохранить lastmod
// =======================================================
$prev_lastmod = [];
if (!$force && is_readable($map_path)) {
    $prev_xml = @simplexml_load_file($map_path);
    if ($prev_xml !== false) {
        foreach ($prev_xml->url as $u) {
            $loc = (string)$u->loc;
            $lm  = (string)$u->lastmod;
            if ($loc !== '' && $lm !== '') $prev_lastmod[$loc] = $lm;
        }
    }
}

// =======================================================
// 1. ИСТОЧНИК А — БАЗА ДАННЫХ
// =======================================================
$langs = [];   // slug => ['ru'=>true,'ua'=>true]
$source = [];  // slug => ['db'=>true,'file'=>true]

$rows = $db->select("pages", ["slug", "type", "lang"]);
if ($rows) {
    foreach ($rows as $r) {
        if (($r['type'] ?? '') === 'articles') continue;   // блог — своя карта

        $slug = trim((string)$r['slug'], '/');
        if ($slug === '' || $slug === 'home') continue;
        if (strpos($slug, '/') !== false || strpos($slug, '..') !== false) continue;

        $lg = (($r['lang'] ?? 'ru') === 'ua') ? 'ua' : 'ru';
        $langs[$slug][$lg]  = true;
        $source[$slug]['db'] = true;
    }
}

// =======================================================
// 2. ИСТОЧНИК Б — ФИЗИЧЕСКИЕ ФАЙЛЫ
//    Корень = русская версия, /ua/ = украинская.
// =======================================================
foreach ([['', 'ru'], ['/ua', 'ua']] as $pair) {
    list($dir, $lg) = $pair;
    foreach ((glob($doc . $dir . '/*.php') ?: []) as $file) {
        $slug = basename($file, '.php');
        if (in_array($slug, $system_files, true)) continue;

        $langs[$slug][$lg]     = true;
        $source[$slug]['file'] = true;
    }
}

// =======================================================
// 3. СВЕРКА С ЗАХАРДКОЖЕННЫМ СПИСКОМ (страховка)
// =======================================================
$static_pages = [
    'price', 'avtopark-evakuatorov', 'phone-number',
    'gruzovoy-evakuator-kharkov', 'evakuator-manipulator-kharkov', 'poputnyy-evakuator',
    'Perevozka-spetstekhniki-Kharkov', 'avtosos', 'avtovykup-kharkov', 'sto-kharkov',
    'blog', 'evakuator-pri-dtp',
    'evakuator-aleksseyevka', 'evakuator-saltovka', 'evakuator-pesochin',
    'evakuator-kholodnaya-gora', 'evakuator-novyye-doma', 'evakuator-xtz',
    'evakuator-po-kharkovskoy-oblasti', 'evakuator-po-ukraine', 'evakuator-merefa',
    'evakuator-Chuguyev', 'evakuator-balakleya', 'evakuator-Izyum',
    'evakuator-Kupyansk', 'evakuator-Lozovaya', 'evakuator-valki',
];
$orphans = [];
foreach ($static_pages as $slug) {
    if (!isset($langs[$slug])) $orphans[] = $slug;
}

ksort($langs, SORT_NATURAL | SORT_FLAG_CASE);

// =======================================================
// 4. СБОРКА
// =======================================================
$body = "";
$cnt = 0; $cnt_new = 0; $cnt_touch = 0;
$only_ru = []; $only_ua = []; $file_only = [];

// Главная — всегда обе версии
$home_ru = $site_url . '/';
$home_ua = $site_url . '/ua/';
foreach ([$home_ru, $home_ua] as $loc) {
    $lm = $force ? $today : ($prev_lastmod[$loc] ?? $today);
    if (!isset($prev_lastmod[$loc])) $cnt_new++;
    $body .= urlNode($loc, $home_ru, $home_ua, $lm, '1.0', true);
    $cnt++;
}

foreach ($langs as $slug => $versions) {
    $has_ru = !empty($versions['ru']);
    $has_ua = !empty($versions['ua']);
    $both   = $has_ru && $has_ua;

    if ($has_ru && !$has_ua) $only_ru[] = $slug;
    if ($has_ua && !$has_ru) $only_ua[] = $slug;
    if (empty($source[$slug]['db']) && !empty($source[$slug]['file'])) $file_only[] = $slug;

    $ru = $site_url . '/' . $slug;
    $ua = $site_url . '/ua/' . $slug;

    $targets = [];
    if ($has_ru) $targets[] = $ru;
    if ($has_ua) $targets[] = $ua;

    foreach ($targets as $loc) {
        if ($force || isset($touch[$slug])) {
            $lm = $today;
            if (isset($touch[$slug])) $cnt_touch++;
        } elseif (isset($prev_lastmod[$loc])) {
            $lm = $prev_lastmod[$loc];
        } else {
            $lm = $today;
            $cnt_new++;
        }
        $body .= urlNode($loc, $ru, $ua, $lm, '0.8', $both);
        $cnt++;
    }
}

$ok = file_put_contents($map_path, wrapUrlset($body));

// =======================================================
// 5. ОТЧЁТ
// =======================================================
header('Content-Type: text/html; charset=UTF-8');
echo '<meta charset="utf-8"><div style="font-family:system-ui,sans-serif;max-width:780px;margin:40px auto;line-height:1.6">';

if ($ok === false) {
    echo "<h3 style='color:#c00'>❌ Ошибка записи sitemap.xml. Проверьте права (CHMOD).</h3>";
} else {
    echo "<h3>✅ sitemap.xml обновлён</h3>";
    echo "<p>Всего URL: <strong>{$cnt}</strong>";
    if ($force) {
        echo " · <strong style='color:#c60'>lastmod обновлён у всех (force)</strong>";
    } else {
        echo " · новых: <strong>{$cnt_new}</strong>";
        if ($cnt_touch) echo " · принудительно обновлено: <strong>{$cnt_touch}</strong>";
        echo " · у остальных lastmod сохранён";
    }
    echo "</p>";

    if ($file_only) {
        echo "<div style='background:#f0fdf4;border-left:4px solid #22c55e;padding:12px 16px;margin:16px 0'>";
        echo "<strong>Найдены как физические файлы</strong> (в таблице pages их нет):<br>";
        echo "<code>" . htmlspecialchars(implode(', ', $file_only)) . "</code><br>";
        echo "<small>Это нормально — гибридный режим. Но hreflang в header.php ищет вторую версию именно в БД, ";
        echo "поэтому на этих страницах он не выводится. Если нужен — заведи строки в pages.</small>";
        echo "</div>";
    }

    if ($orphans) {
        echo "<div style='background:#fff7e6;border-left:4px solid #f5a623;padding:12px 16px;margin:16px 0'>";
        echo "<strong>В списке \$static_pages, но нет ни в БД, ни на диске</strong> — в карту не попали:<br>";
        echo "<code>" . htmlspecialchars(implode(', ', $orphans)) . "</code><br>";
        echo "<small>Либо страницы удалены и слаги пора убрать из кода, либо опечатка в слаге.</small>";
        echo "</div>";
    }

    if ($only_ru || $only_ua) {
        echo "<div style='background:#eef6ff;border-left:4px solid #3b82f6;padding:12px 16px;margin:16px 0'>";
        echo "<strong>Нет второй языковой версии</strong> — hreflang для них не выводится:<br>";
        if ($only_ru) echo "Только RU: <code>" . htmlspecialchars(implode(', ', $only_ru)) . "</code><br>";
        if ($only_ua) echo "Только UA: <code>" . htmlspecialchars(implode(', ', $only_ua)) . "</code><br>";
        echo "<small>Не ошибка. Но если перевод должен быть — его стоит завести.</small>";
        echo "</div>";
    }

    echo "<p style='background:#f5f5f3;padding:12px 16px;border-radius:8px'>";
    echo "<strong>Точечное обновление даты:</strong><br>";
    echo "<code>/sitemap_gen.php?touch=evakuator-Chuguyev</code> — поднять lastmod одной странице<br>";
    echo "<code>/sitemap_gen.php?force=1</code> — обновить всем (только когда правда менялось всё)";
    echo "</p>";

    echo "<p>Карта блога динамическая: <a href='/sitemap-blog.xml' target='_blank'>/sitemap-blog.xml</a>.</p>";
    echo "<a href='/sitemap.xml' target='_blank' style='display:inline-block;margin-right:12px;padding:10px 15px;background:#0dcaf0;color:#fff;text-decoration:none;border-radius:5px;'>sitemap.xml</a>";
    echo "<a href='/admin.php' style='display:inline-block;padding:10px 15px;background:#6c757d;color:#fff;text-decoration:none;border-radius:5px;'>В админку</a>";
}
echo '</div>';
