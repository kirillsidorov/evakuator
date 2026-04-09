<?php include $_SERVER['DOCUMENT_ROOT'] . '/config.php'; ?>
<?php
// --- ЛОГИКА ОПРЕДЕЛЕНИЯ ПУТЕЙ И ЯЗЫКОВ ---

global $page, $settings;
if (!isset($page) || !is_array($page)) {
    $page = [
        'id' => 0, 
        'type' => 'physical_file', 
        'meta_title' => $title ?? '', 
        'meta_description' => $description ?? ''
    ];
}

// 1. Протокол и домен
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$host        = $_SERVER['HTTP_HOST'];
$request_uri = $_SERVER['REQUEST_URI'];

// 2. Определяем язык по URL
$is_ua = (strpos($request_uri, '/ua') === 0);
$lang  = $is_ua ? 'ua' : 'ru';

// 3. Чистый путь без /ua
$clean_path = preg_replace('#^/ua#', '', $request_uri);
if ($clean_path === '') {
    $clean_path = '/';
}

// 4. Ссылки для переключателя языков
$link_ru = $clean_path;
$link_ua = '/ua' . $clean_path;
$link_ua = str_replace('/ua//', '/ua/', $link_ua); // защита от двойного слеша

// 5. Каноническая ссылка
if ($is_ua) {
    // UA: канонический всегда указывает на /ua/... версию
    $canonical_url = $protocol . $host . $link_ua;
} else {
    // RU: канонический указывает на текущий URI
    $canonical_url = $protocol . $host . $request_uri;
}
// Убираем расширения файлов
$canonical_url = str_replace(['index.php', '.html', '.php'], '', $canonical_url);
// Убираем trailing slash, если это не главная страница
$home_ru = $protocol . $host . '/';
$home_ua = $protocol . $host . '/ua/';
if ($canonical_url !== $home_ru && $canonical_url !== $home_ua) {
    $canonical_url = rtrim($canonical_url, '/');
}

// 6. Настройки для каждого языка
$html_lang  = $is_ua ? 'uk'    : 'ru';
$og_locale  = $is_ua ? 'uk_UA' : 'ru_UA';
?>
<!DOCTYPE html>
<html lang="<?= $html_lang ?>">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
    <link rel="shortcut icon" href="/assets/images/icons8-taxi-service-64-64x64.png" type="image/x-icon">

    <meta property="og:title"       content="<?= $title ?>">
    <meta property="og:description" content="<?= $description ?>">
    <meta property="og:type"        content="website">
    <meta property="og:url"         content="<?= $canonical_url ?>">
    <meta property="og:image"       content="https://evakuator-kharkov.kh.ua/assets/images/2-1000x500.png">
    <meta property="og:image:width"  content="1000">
    <meta property="og:image:height" content="500">
    <meta property="og:locale"      content="<?= $og_locale ?>">

    <link rel="canonical" href="<?= $canonical_url ?>" />
    <meta name="description" content="<?= $description ?>">

    <link rel="alternate" hreflang="ru-UA"    href="<?= $protocol . $host . $link_ru ?>" />
    <link rel="alternate" hreflang="uk-UA"    href="<?= $protocol . $host . $link_ua ?>" />
    <link rel="alternate" hreflang="x-default" href="<?= $protocol . $host . $link_ru ?>" />

    <title><?= $title ?></title>

    <style>
        body,
        .display-1,
        .display-2,
        .display-4,
        .display-7 {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
        }
        .location_h1_block,
        .service_h1_block {
            padding-top: 200px !important;
        }
    </style>

    <link rel="stylesheet" href="/assets/web/assets/mobirise-icons/mobirise-icons.css" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="/assets/web/assets/mobirise-icons-bold/mobirise-icons-bold.css" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/dropdown/css/style.css" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="/assets/socicon/css/styles.css" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="/assets/theme/css/style.css">

    
    <link rel="stylesheet" href="/assets/mobirise/css/mbr-additional_new.css">

    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-W8H32TN');</script>
    <!-- End Google Tag Manager -->

    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/schema.php'; ?>

</head>
<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-W8H32TN"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/menu.php'; ?>

<main>