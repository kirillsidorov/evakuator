<?php
// config.php
//
// Обновлено (фиксы по аудиту, п.6 / п.7 / п.10):
//   1. Определение языка вынесено в detect_lang() (components/theme_functions.php).
//      Было: strpos($current_uri, '/ua') === 0 — без слэша и по REQUEST_URI
//      вместе с query. Страница вроде /uaz-evakuator считалась украинской.
//   2. Убраны мёртвый код и дубли: недостижимая ветка else внутри if ($db_page),
//      повторное $page = $db_page, бесполезный global $page_type на файловом
//      уровне.
//   3. Цена для физических файлов считается общей calc_page_price(), а не
//      плоским $settings['price_car'] мимо формулы.
//   4. Title/Description для физических файлов проходят через
//      apply_placeholders() — раньше подменялся только {price}, а {city},
//      {tel1} и остальные выводились фигурными скобками.
//   5. theme_functions.php подключается здесь, чтобы функции были доступны
//      и на страницах-физических-файлах, которые не идут через router.php.

// 1. Подключаем базу и общие функции
require_once $_SERVER['DOCUMENT_ROOT'] . '/db.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/components/theme_functions.php';

// 2. Глобальные данные (из БД)
$settings_raw = $db->select("settings", ["setting_key", "setting_value"]);
$settings = [];
if ($settings_raw) {
    $settings = array_column($settings_raw, 'setting_value', 'setting_key');
} else {
    // Дефолт, если база вдруг недоступна
    $settings = ['price_car' => '1000', 'tel_one_link' => '#', 'tel_one_view' => 'Error'];
}

// 3. Определяем язык — одна функция на весь проект
$lang = detect_lang($_SERVER['REQUEST_URI'] ?? '/');

// 4. ГИБРИДНЫЙ РЕЖИМ
//    Страницы-физические-файлы (не через router.php) подтягивают свои данные
//    из БД по имени файла, чтобы breadcrumbs и мета работали одинаково.
$current_file = basename($_SERVER['PHP_SELF'], '.php');
$system_files = ['index', 'router', 'home', '404'];

if (!in_array($current_file, $system_files, true)) {

    $db_page = $db->get('pages', '*', [
        'slug' => $current_file,
        'lang' => $lang
    ]);

    if ($db_page) {
        $page = $db_page;

        // Тип, заданный вручную в самом файле, не переопределяем
        if (empty($page_type)) {
            $page_type = $db_page['type'] ?? 'standard';
        }

        $attrs = json_decode($db_page['attributes'], true) ?? [];

        // Та же формула, что в router.php и hub_template.php
        $price_val   = calc_page_price($attrs, $db_page['location_type'] ?? 'city', $settings);
        $dist_val    = $attrs['distance'] ?? '';
        $time_val    = $attrs['time'] ?? '';
        $city_val    = $db_page['breadcrumb_title'] ?? (($lang == 'ua') ? 'Харків' : 'Харьков');
        $in_city_val = $attrs['in_city'] ?? (($lang == 'ua') ? 'у ' . $city_val : 'в ' . $city_val);

        // Полная подстановка плейсхолдеров, а не только {price}
        $title = apply_placeholders(
            $db_page['meta_title'], $city_val, $in_city_val, $price_val, $dist_val, $time_val, $settings
        );
        $description = apply_placeholders(
            $db_page['meta_description'], $city_val, $in_city_val, $price_val, $dist_val, $time_val, $settings
        );

        $loc = [
            'name'    => $city_val,
            'type'    => $db_page['location_type'] ?? 'city',
            'in_city' => $in_city_val,
        ];
        $loc_map = $attrs['maps'] ?? '';
    }
    else {
        // Физического файла нет в БД — ставим заглушку типа
        if (empty($page_type)) {
            $page_type = 'physical';
        }
    }
}

// 5. Значения по умолчанию
if (!isset($loc)) {
    $loc = [
        'name'    => ($lang == 'ua' ? 'Харків' : 'Харьков'),
        'type'    => 'city',
        'in_city' => ($lang == 'ua' ? 'у Харкові' : 'в Харькове'),
    ];
}
if (!isset($city_val))    $city_val    = $loc['name'];
if (!isset($in_city_val)) $in_city_val = $loc['in_city'];
if (!isset($price_val))   $price_val   = $settings['price_car'] ?? 1000;
if (!isset($dist_val))    $dist_val    = '';
if (!isset($time_val))    $time_val    = '';
