<?php
// router.php
// Обновлено (SEO-фикс, редакция 3):
//   URL-нормализацию (.html, .php, слэш, www, HTTPS, index) делает .htaccess —
//   в роутере её НЕТ, чтобы не плодить цепочки редиректов.
//   Роутер отвечает за то, чего .htaccess не умеет:
//
//   1. КАНОНИЧЕСКИЙ РЕГИСТР СЛАГА. Коллация MySQL регистронезависима,
//      поэтому /evakuator-chuguyev раньше отдавал 200 наравне с
//      /evakuator-Chuguyev. Теперь несовпадение регистра -> 301.
//   2. НОРМАЛЬНАЯ 404: components/404.php + запись в error_log с реферером.
//   3. Защита от soft-404: пустой контент -> 503 + Retry-After, не 200.
//   4. Price engine: attributes.price имеет приоритет над расчётом по
//      расстоянию; множитель обратной дороги вынесен в price_return_factor.

// 1. ПОДКЛЮЧАЕМ БАЗУ И КОНФИГ
require_once 'db.php';
require_once 'config.php';
require_once 'components/theme_functions.php';

// ---------------------------------------------------------------
// 2. РАЗБИРАЕМ URL
// ---------------------------------------------------------------
$request_uri = $_SERVER['REQUEST_URI'];
$path        = parse_url($request_uri, PHP_URL_PATH);
$raw_query   = parse_url($request_uri, PHP_URL_QUERY);

// Служебные параметры от .htaccess (slug/lang) в редиректы не тащим
if ($raw_query) {
    parse_str($raw_query, $q_arr);
    unset($q_arr['slug'], $q_arr['lang']);
    $qs = $q_arr ? '?' . http_build_query($q_arr) : '';
} else {
    $qs = '';
}

$slug = trim((string)$path, '/');

// Языковая логика
$lang = 'ru';
if (strpos($slug, 'ua/') === 0 || $slug === 'ua') {
    $lang = 'ua';
    $slug = preg_replace('~^ua/~', '', $slug);
    if ($slug === 'ua') $slug = '';
}
// /home и /ua/home — дубль главной. В БД слаг называется 'home', но
// наружу главная доступна только по / и /ua/. Раньше оба адреса отдавали
// 200 с разными каноникалами и попадали в sitemap.
if ($slug === 'home') {
    header('Location: ' . ($lang === 'ua' ? '/ua/' : '/') . $qs, true, 301);
    exit;
}

if (empty($slug)) $slug = 'home';

// ---------------------------------------------------------------
// 3. ИЩЕМ СТРАНИЦУ В MYSQL
// ---------------------------------------------------------------
$page = $db->get('pages', '*', [
    'slug' => $slug,
    'lang' => $lang
]);

// 3.1 Не найдена — отдаём 404
if (!$page) {
    render_404_page($settings, $lang, $slug);
}

// 3.2 Отложенная публикация: статья с будущей датой ещё недоступна
if (($page['type'] ?? '') === 'articles' && !empty($page['date']) && $page['date'] > date('Y-m-d')) {
    render_404_page($settings, $lang, $slug);
}

// 3.3 КАНОНИЧЕСКИЙ РЕГИСТР
// Коллация MySQL регистронезависима: запрос по 'evakuator-chuguyev'
// возвращает строку 'evakuator-Chuguyev'. Раньше оба URL отдавали 200.
if ($slug !== 'home' && strcmp((string)$page['slug'], $slug) !== 0) {
    $target = ($lang === 'ua' ? '/ua/' : '/') . $page['slug'];
    header('Location: ' . $target . $qs, true, 301);
    exit;
}

// ---------------------------------------------------------------
// 4. РАСПАКОВКА ПЕРЕМЕННЫХ И PRICE ENGINE
// ---------------------------------------------------------------
$page_type     = $page['type'] ?? 'standard';
$location_type = $page['location_type'] ?? 'city';

$title       = $page['meta_title'];
$description = $page['meta_description'];

$custom_h1 = $page['h1'] ?? '';
$custom_p  = $page['custom_p'] ?? '';
$custom_bg = $page['hero_image'] ?? '';

$attrs   = json_decode($page['attributes'], true) ?: [];
$loc_map = $attrs['maps'] ?? '';

$city_val    = $page['breadcrumb_title'] ?? (($lang == 'ua') ? 'Харків' : 'Харьков');
$in_city_val = $attrs['in_city'] ?? (($lang == 'ua') ? 'у Харкові' : 'в Харькове');

// --- PRICE ENGINE ---
// Логика живёт в calc_page_price() (components/theme_functions.php),
// чтобы hub_template.php считал ровно так же и цифры не расходились.
$price_val = calc_page_price($attrs, $location_type, $settings);
$dist_val  = $attrs['distance'] ?? '';
$time_val  = $attrs['time'] ?? '';

$loc = [
    'name'    => $city_val,
    'type'    => $location_type,
    'in_city' => $in_city_val
];

// ---------------------------------------------------------------
// 5. ДОСТАЁМ КОНТЕНТНЫЕ БЛОКИ
// ---------------------------------------------------------------
$blocks = $db->select('content_blocks', '*', [
    'page_id' => $page['id'],
    'ORDER'   => ['sort_order' => 'ASC']
]);

// 5.1 ЗАЩИТА ОТ SOFT-404
// Март 2026: content_blocks перестали подтягиваться, страницы месяц отдавали
// 200 с пустым телом. Google переоценил их как thin и снёс позиции.
$content_types_need_body = ['locations', 'district', 'services', 'articles'];

if (in_array($page_type, $content_types_need_body, true)) {

    $content_len = 0;
    $has_include = false;

    if (!empty($blocks)) {
        foreach ($blocks as $b) {
            $bt = $b['block_type'] ?? '';
            if ($bt === 'include') {
                $has_include = true;
            } elseif ($bt === 'text' || $bt === 'structured_content') {
                $content_len += strlen(trim((string)($b['content'] ?? '')));
            }
        }
    }

    if (empty($blocks) || ($content_len < 300 && !$has_include)) {

        error_log(sprintf(
            '[SOFT404-GUARD] empty content: slug=%s lang=%s page_id=%s blocks=%d len=%d',
            $slug, $lang, $page['id'] ?? '?', is_array($blocks) ? count($blocks) : 0, $content_len
        ));

        http_response_code(503);
        header('Retry-After: 3600');
        header('Content-Type: text/html; charset=utf-8');
        header('Cache-Control: no-store');

        $tel_view = $settings['tel_one_view'] ?? '';
        $tel_link = $settings['tel_one_link'] ?? '';

        echo '<!doctype html><html lang="' . ($lang === 'ua' ? 'uk' : 'ru') . '"><meta charset="utf-8">'
           . '<meta name="viewport" content="width=device-width,initial-scale=1">'
           . '<title>Технические работы</title>'
           . '<div style="max-width:600px;margin:15vh auto;padding:0 24px;font-family:system-ui,sans-serif;line-height:1.6">'
           . '<h1 style="font-size:28px;margin:0 0 12px">Технические работы</h1>'
           . '<p style="color:#555;margin:0 0 20px">Страница временно недоступна. Заказ принимаем по телефону круглосуточно.</p>'
           . '<a href="tel:' . htmlspecialchars($tel_link) . '" style="display:inline-block;background:#e9ff00;color:#000;font-weight:700;padding:14px 28px;border-radius:10px;text-decoration:none">'
           . htmlspecialchars($tel_view) . '</a>'
           . '</div>';
        exit;
    }
}

// Кнопка
$custom_btn_text = $page['custom_btn'] ?? '';
$custom_btn_link = "tel:" . ($settings['tel_one_link'] ?? '');

// ---------------------------------------------------------------
// 6. ПОДКЛЮЧАЕМ ШАБЛОН ПО ТИПУ СТРАНИЦЫ
// ---------------------------------------------------------------
switch ($page['type']) {
    case 'locations':
    case 'district':
        include 'templates/location_template.php';
        break;

    case 'services':
        include 'templates/service_template.php';
        break;

    case 'articles':
        include 'templates/article_template.php';
        break;

    case 'archive':
        include 'templates/blog_index_template.php';
        break;

    case 'hub':
        include 'templates/hub_template.php';
        break;

    default:
        // Fallback — локация
        include 'templates/location_template.php';
        break;
}


// ===============================================================
// РЕНДЕР 404
// ===============================================================
function render_404_page($settings, $lang, $requested_slug = '') {

    // Логируем с реферером — так находятся битые внутренние ссылки
    error_log(sprintf(
        '[404] slug=%s lang=%s uri=%s ref=%s ua=%s',
        $requested_slug,
        $lang,
        $_SERVER['REQUEST_URI'] ?? '',
        $_SERVER['HTTP_REFERER'] ?? '-',
        substr($_SERVER['HTTP_USER_AGENT'] ?? '-', 0, 120)
    ));

    http_response_code(404);
    header('Content-Type: text/html; charset=utf-8');
    header('Cache-Control: no-store');

    // Синтетический $page — header.php и footer.php обращаются к нему.
    // type = '404' гасит canonical, hreflang, schema и включает noindex.
    $GLOBALS['page'] = [
        'id'               => 0,
        'type'             => '404',
        'location_type'    => '',
        'slug'             => '',
        'lang'             => $lang,
        'meta_title'       => ($lang === 'ua' ? 'Сторінку не знайдено' : 'Страница не найдена'),
        'meta_description' => '',
        'h1'               => '',
        'custom_p'         => '',
        'custom_btn'       => '',
        'hero_image'       => '',
        'breadcrumb_title' => '',
        'attributes'       => '{}',
        'date'             => '',
    ];

    $GLOBALS['page_type']   = '404';
    $GLOBALS['slug']        = '';
    $GLOBALS['title']       = $GLOBALS['page']['meta_title'];
    $GLOBALS['description'] = '';
    $GLOBALS['custom_h1']   = '';
    $GLOBALS['custom_p']    = '';
    $GLOBALS['custom_bg']   = '';
    $GLOBALS['attrs']       = [];
    $GLOBALS['loc_map']     = '';
    $GLOBALS['blocks']      = [];
    $GLOBALS['dist_val']    = '';
    $GLOBALS['time_val']    = '';
    $GLOBALS['price_val']   = $settings['price_car'] ?? '';
    $GLOBALS['city_val']    = ($lang === 'ua') ? 'Харків' : 'Харьков';
    $GLOBALS['in_city_val'] = ($lang === 'ua') ? 'у Харкові' : 'в Харькове';
    $GLOBALS['loc']         = [
        'name'    => $GLOBALS['city_val'],
        'type'    => '',
        'in_city' => $GLOBALS['in_city_val'],
    ];
    $GLOBALS['custom_btn_text'] = '';
    $GLOBALS['custom_btn_link'] = 'tel:' . ($settings['tel_one_link'] ?? '');

    require_smart('404.php');
    exit;
}
