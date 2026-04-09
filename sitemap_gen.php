<?php
// Подключаем Medoo
require_once $_SERVER['DOCUMENT_ROOT'] . '/db.php'; 

// НАСТРОЙКИ
$site_url = "https://evakuator-kharkov.kh.ua";
$sitemap_file = $_SERVER['DOCUMENT_ROOT'] . '/sitemap.xml';

// Начало XML файла
$xml_content = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
$xml_content .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

// --- ФУНКЦИЯ ДОБАВЛЕНИЯ ССЫЛКИ ---
function addUrl($url, $lastmod, $priority) {
    global $xml_content;
    $xml_content .= "\t<url>" . PHP_EOL;
    $xml_content .= "\t\t<loc>" . htmlspecialchars($url) . "</loc>" . PHP_EOL;
    $xml_content .= "\t\t<lastmod>" . $lastmod . "</lastmod>" . PHP_EOL;
    $xml_content .= "\t\t<priority>" . $priority . "</priority>" . PHP_EOL;
    $xml_content .= "\t</url>" . PHP_EOL;
}

$last_mod = date("Y-m-d");
$count = 0;

// =======================================================
// 1. БЕРЕМ СТРАНИЦЫ ИЗ БАЗЫ ДАННЫХ (Medoo)
// =======================================================
$pages = $db->select("pages", ["slug", "lang"]);

if ($pages) {
    foreach ($pages as $page) {
        $slug = trim($page['slug'], '/');
        $lang = $page['lang'];
        
        // Для страниц из базы приоритет 0.8
        if ($lang === 'ua') {
            $url = $site_url . '/ua/' . $slug;
        } else {
            $url = $site_url . '/' . $slug;
        }
        
        addUrl($url, $last_mod, '0.8');
        $count++;
    }
}

// =======================================================
// 2. ВРЕМЕННОЕ РЕШЕНИЕ: ДОБАВЛЯЕМ СТРАНИЦЫ ВНЕ БАЗЫ
// =======================================================
$missing_pages = [
    '', // Главная страница (index)
    'price',
    'avtopark-evakuatorov',
    'phone-number',
    'evakuator-services',
    'gruzovoy-evakuator-kharkov',
    'evakuator-manipulator-kharkov',
    'poputnyy-evakuator',
    'Perevozka-spetstekhniki-Kharkov',
    'avtosos',
    'avtovykup-kharkov',
    'sto-kharkov'
];

foreach ($missing_pages as $slug) {
    // Главной странице даем приоритет 1.0, остальным 0.8
    $priority = ($slug === '') ? '1.0' : '0.8';
    
    // Генерируем русскую ссылку
    $ru_url = $site_url . '/' . $slug;
    addUrl($ru_url, $last_mod, $priority);
    $count++;

    // Генерируем украинскую ссылку
    $ua_url = $site_url . '/ua/' . $slug;
    addUrl($ua_url, $last_mod, $priority);
    $count++;
}

// =======================================================

// Закрываем XML
$xml_content .= '</urlset>';

// Записываем в файл sitemap.xml
if (file_put_contents($sitemap_file, $xml_content)) {
    echo "<h3>✅ Карта сайта успешно обновлена!</h3>";
    echo "<p>Всего добавлено ссылок (из БД + статические): <strong>{$count}</strong></p>";
    echo "<a href='/sitemap.xml' target='_blank' style='display:inline-block; margin-right: 15px; padding: 10px 15px; background: #0dcaf0; color: #fff; text-decoration: none; border-radius: 5px;'>Посмотреть sitemap.xml</a>";
    echo "<a href='/admin.php' style='display:inline-block; padding: 10px 15px; background: #6c757d; color: #fff; text-decoration: none; border-radius: 5px;'>Вернуться в админку</a>";
} else {
    echo "<h3 style='color: red;'>❌ Ошибка при записи файла sitemap.xml.</h3>";
}
?>