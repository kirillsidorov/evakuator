<?php
// config.php

// 1. Подключаем базу
require_once $_SERVER['DOCUMENT_ROOT'] . '/db.php';

// 2. Глобальные данные (ТЕПЕРЬ ИЗ БД)
$settings_raw = $db->select("settings", ["setting_key", "setting_value"]);
$settings = [];
if ($settings_raw) {
    $settings = array_column($settings_raw, 'setting_value', 'setting_key');
} else {
    // Дефолт, если база вдруг недоступна
    $settings = ['price_car' => '1000', 'tel_one_link' => '#', 'tel_one_view' => 'Error'];
}

// 3. Определяем язык
$current_uri = $_SERVER['REQUEST_URI'];
$lang = (strpos($current_uri, '/ua') === 0) ? 'ua' : 'ru';

// 4. ГИБРИДНЫЙ РЕЖИМ
$current_file = basename($_SERVER['PHP_SELF'], '.php');
$system_files = ['index', 'router', 'home', '404'];

if (!in_array($current_file, $system_files)) {
    // Пытаемся найти страницу в базе по имени файла
    $db_page = $db->get('pages', '*', [
        'slug' => $current_file,
        'lang' => $lang
    ]);

    if ($db_page) {
        // Если нашли — записываем в глобальную переменную $page
        // Теперь breadcrumbs.php увидит её и построит правильную цепочку!
        $page = $db_page;

        global $page_type; // Указываем, что работаем с глобальной переменной

        if (!empty($db_page)) {
            $page = $db_page;
            // Если в файле уже вручную задан тип (например, $page_type = 'hub'), 
            // то мы его НЕ переопределяем данными из базы.
            if (empty($page_type)) {
                $page_type = $page['type'] ?? 'standard';
            }
        } else {
            // Если страницы нет в базе (физический файл)
            // и тип не задан в самом файле — ставим заглушку 'physical'
            if (empty($page_type)) {
                $page_type = 'physical'; 
            }
        }

        // Получаем цену из json (у вас она лежит в $settings['price_car'])
        $current_price = $settings['price_car'] ?? '1000'; 

        // Добавляем замену шорткода в Title и Description
        $title = str_replace('{price}', $current_price, $db_page['meta_title']);
        $description = str_replace('{price}', $current_price, $db_page['meta_description']);
        
        $attrs = json_decode($db_page['attributes'], true) ?? [];
        
        $loc = [
            "name"    => $db_page['breadcrumb_title'],
            "in_city" => $attrs['in_city'] ?? (($lang == 'ua') ? "у " . $db_page['breadcrumb_title'] : "в " . $db_page['breadcrumb_title'])
        ];
        $loc_map = $attrs['maps'] ?? '';
    }
}

// 5. Значения по умолчанию
if (!isset($loc)) {
    $loc = ["name" => ($lang == 'ua' ? "Харків" : "Харьков"), "in_city" => ($lang == 'ua' ? "у Харкові" : "в Харькове")];
}
?>