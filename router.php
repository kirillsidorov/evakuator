<?php
// router.php

// 1. ПОДКЛЮЧАЕМ БАЗУ И КОНФИГ
require_once 'db.php'; 
require_once 'config.php';
require_once 'includes/theme_functions.php';

// 2. РАЗБИРАЕМ URL
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$slug = trim($request_uri, '/');

// Языковая логика
$lang = 'ru';
if (strpos($slug, 'ua/') === 0 || $slug === 'ua') {
    $lang = 'ua';
    $slug = str_replace('ua/', '', $slug);
    if ($slug === 'ua') $slug = '';
}
if (empty($slug)) $slug = 'home';

// 3. ИЩЕМ СТРАНИЦУ В MYSQL
$page = $db->get('pages', '*', [
    'slug' => $slug,
    'lang' => $lang
]);

// Если страница не найдена
if (!$page) {
    header("HTTP/1.0 404 Not Found");
    echo "<h1>404</h1><p>Страница не найдена.</p>";
    exit;
}

// 4. РАСПАКОВКА ПЕРЕМЕННЫХ И PRICE ENGINE

// 4. РАСПАКОВКА ПЕРЕМЕННЫХ И PRICE ENGINE
$page_type = $page['type'] ?? 'standard'; // Глобальный тип страницы
$location_type = $page['location_type'] ?? 'city'; // Тип локации (город/район)

$title = $page['meta_title'];
$description = $page['meta_description'];

// Исправляем ключи согласно структуре БД
$custom_h1 = $page['h1'] ?? ''; 
$custom_p  = $page['custom_p'] ?? '';
$custom_bg = $page['hero_image'] ?? '';

$attrs = json_decode($page['attributes'], true) ?: [];
$loc_map = $attrs['maps'] ?? ''; // Карту берем из атрибутов

// Определяем переменные для шаблонов (чтобы ушли Warning)
$city_val    = $page['breadcrumb_title'] ?? (($lang == 'ua') ? 'Харків' : 'Харьков');
$in_city_val = $attrs['in_city'] ?? (($lang == 'ua') ? 'у Харкові' : 'в Харькове');

// --- ЛОГИКА РАСЧЕТА ЦЕН (PRICE ENGINE) ---
$price_per_km = (int)($settings['price_km'] ?? 30);
$base_min     = (int)($settings['price_feed'] ?? 1000);

$final_price = 0;

// Используем колонку location_type из базы
if ($location_type == 'district') {
    $final_price = !empty($attrs['price']) ? $attrs['price'] : ($settings['price_car'] ?? 1000);
} else {
    if (!empty($attrs['distance'])) {
        $distance = (float)$attrs['distance'];
        $calculated = ($distance * $price_per_km * 2) + $base_min;
        $final_price = round($calculated, -2);
    } else {
        $final_price = !empty($attrs['price']) ? $attrs['price'] : ($settings['price_car'] ?? 1000);
    }
}

$price_val = $final_price;
$dist_val  = $attrs['distance'] ?? '';
$time_val  = $attrs['time'] ?? '';

// Формируем массив локации
$loc = [
    'name'    => $city_val,
    'type'    => $location_type,
    'in_city' => $in_city_val
];

// 5. ДОСТАЕМ КОНТЕНТНЫЕ БЛОКИ
$blocks = $db->select('content_blocks', '*', [
    'page_id' => $page['id'],
    'ORDER' => ['sort_order' => 'ASC']
]);

// Передача данных для кнопок
$custom_btn_text = $page['custom_btn'] ?? ''; 
$custom_btn_link = "tel:" . ($settings['tel_one_link'] ?? ''); // БЕРЕМ ИЗ БАЗЫ

// 6. ПОДКЛЮЧАЕМ ПРАВИЛЬНЫЙ ШАБЛОН

if ($page['type'] == 'locations' || $page['type'] == 'district') {
    // 1. Города и Районы
    include 'templates/location_template.php';

} elseif ($page['type'] == 'services') {
    // 2. Услуги (Манипулятор, СТО) - Богатый шаблон
    include 'templates/service_template.php';

} elseif ($page['type'] == 'articles') {
    // 3. Статьи блога - Простой шаблон
    include 'templates/article_template.php';

} elseif ($page['type'] == 'archive') {
    // 4. Главная страница Блога (список)
    include 'templates/blog_index_template.php';

} else {
    // 5. Остальные...
    include 'templates/location_template.php'; 
}
?>