<?php include $_SERVER['DOCUMENT_ROOT'] . '/config.php'; ?>
<?php
global $page, $settings;
if (!isset($page) || !is_array($page)) {
    $page = ['id' => 0, 'type' => 'physical_file', 'meta_title' => $title ?? '', 'meta_description' => $description ?? ''];
}

$protocol    = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$host        = $_SERVER['HTTP_HOST'];
$request_uri = $_SERVER['REQUEST_URI'];
$is_ua       = (strpos($request_uri, '/ua') === 0);
$lang        = $is_ua ? 'ua' : 'ru';
$clean_path  = preg_replace('#^/ua#', '', $request_uri) ?: '/';
$link_ru     = $clean_path;
$link_ua     = str_replace('/ua//', '/ua/', '/ua' . $clean_path);

if ($is_ua) {
    $canonical_url = $protocol . $host . $link_ua;
} else {
    $canonical_url = $protocol . $host . $request_uri;
}
$canonical_url = str_replace(['index.php', '.html', '.php'], '', $canonical_url);
$home_ru = $protocol . $host . '/';
$home_ua = $protocol . $host . '/ua/';
if ($canonical_url !== $home_ru && $canonical_url !== $home_ua) {
    $canonical_url = rtrim($canonical_url, '/');
}

$html_lang = $is_ua ? 'uk'    : 'ru';
$og_locale = $is_ua ? 'uk_UA' : 'ru_UA';
?>
<!DOCTYPE html>
<html lang="<?= $html_lang ?>">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
    <link rel="shortcut icon" href="/assets/images/icons8-taxi-service-64-64x64.png" type="image/x-icon">

    <meta property="og:title"        content="<?= $title ?>">
    <meta property="og:description"  content="<?= $description ?>">
    <meta property="og:type"         content="website">
    <meta property="og:url"          content="<?= $canonical_url ?>">
    <meta property="og:image"        content="https://evakuator-kharkov.kh.ua/assets/images/2-1000x500.png">
    <meta property="og:image:width"  content="1000">
    <meta property="og:image:height" content="500">
    <meta property="og:locale"       content="<?= $og_locale ?>">

    <link rel="canonical" href="<?= $canonical_url ?>" />
    <meta name="description" content="<?= $description ?>">

    <link rel="alternate" hreflang="ru-UA"     href="<?= $protocol . $host . $link_ru ?>" />
    <link rel="alternate" hreflang="uk-UA"     href="<?= $protocol . $host . $link_ua ?>" />
    <link rel="alternate" hreflang="x-default" href="<?= $protocol . $host . $link_ru ?>" />

    <title><?= $title ?></title>

    <!-- =====================================================
         CRITICAL CSS — инлайн, грузится мгновенно.
         Покрывает навбар + hero (первый экран).
         ===================================================== -->
    <style>
        /* Reset / Base */
        *,*::before,*::after{box-sizing:border-box}
        html,body{height:auto;min-height:100vh;margin:0;padding:0}
        body{font-style:normal;line-height:1.5;color:#232323;position:relative}
        body,.display-1,.display-2,.display-4,.display-7{font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif}
        a{font-style:normal;font-weight:400;cursor:pointer;text-decoration:none}
        a:hover{text-decoration:none}
        b,strong{font-weight:bold}
        h1,h2,h3,h4,h5,h6,.display-1,.display-2,.display-3,.display-4{line-height:1;word-break:break-word;word-wrap:break-word}
        .mbr-section-title{font-style:normal;line-height:1.2}
        .mbr-text{font-style:normal;line-height:1.6}
        .mbr-bold{font-weight:700}
        .mbr-white{color:#fff}
        /* Типографика */
        .display-1{font-size:3.5rem}
        .display-2{font-size:3rem}
        .display-5{font-size:1.6rem}
        .display-7{font-size:1rem}
        @media(max-width:768px){
            .display-1{font-size:calc(1.875rem + (3.5 - 1.875)*((100vw - 20rem)/(48 - 20)));line-height:calc(1.4*(1.875rem + (3.5 - 1.875)*((100vw - 20rem)/(48 - 20))))}
            .display-2{font-size:calc(1.7rem + (3 - 1.7)*((100vw - 20rem)/(48 - 20)));line-height:calc(1.4*(1.7rem + (3 - 1.7)*((100vw - 20rem)/(48 - 20))))}
            .display-5{font-size:calc(1.21rem + (1.6 - 1.21)*((100vw - 20rem)/(48 - 20)));line-height:calc(1.4*(1.21rem + (1.6 - 1.21)*((100vw - 20rem)/(48 - 20))))}
        }
        /* Выравнивание */
        .align-left{text-align:left}
        .align-center{text-align:center}
        .align-right{text-align:right}
        @media(max-width:767px){.align-left,.align-center,.align-right,.mbr-section-btn,.mbr-section-title{text-align:center}}
        /* Контейнер */
        .container{width:100%;padding-right:15px;padding-left:15px;margin-right:auto;margin-left:auto}
        @media(min-width:576px){.container{max-width:540px}}
        @media(min-width:768px){.container{max-width:720px}}
        @media(min-width:992px){.container{max-width:960px}}
        @media(min-width:1200px){.container{max-width:1140px}}
        .row{display:flex;flex-wrap:wrap;margin-right:-15px;margin-left:-15px}
        .col-md-10{position:relative;width:100%;padding-right:15px;padding-left:15px}
        @media(min-width:768px){.col-md-10{flex:0 0 83.333333%;max-width:83.333333%}}
        .justify-content-md-center{justify-content:center}
        /* Navbar */
        .navbar{display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;padding:.5rem 1rem}
        .navbar-toggler{align-self:flex-start;padding:.25rem .75rem;font-size:1.25rem;line-height:1;background:transparent;border:1px solid transparent;border-radius:.25rem;cursor:pointer}
        .navbar-toggler-right{position:absolute;right:1rem}
        .navbar-collapse{flex-basis:100%;flex-grow:1;align-items:center}
        .navbar-dropdown{left:0;padding:0;position:fixed;right:0;top:0;transition:all .45s ease;z-index:1030;background:#fff}
        .navbar-dropdown .navbar-caption{font-weight:700;white-space:normal;vertical-align:-4px;line-height:3.125rem!important}
        .navbar-dropdown .navbar-caption,.navbar-dropdown .navbar-caption:hover{color:inherit;text-decoration:none}
        /* Навбар сайта */
        .cid-qTkzRZLJNu{background-color:#fff!important;min-height:60px}
        .cid-qTkzRZLJNu .navbar{background-color:#fff!important;padding-top:0;padding-bottom:0}
        .cid-qTkzRZLJNu .navbar-brand .navbar-caption{color:#000;font-weight:700}
        .cid-qTkzRZLJNu .mobile-phones-block{display:flex;align-items:center;white-space:nowrap;margin-left:auto;margin-right:auto}
        /* Hamburger */
        .hamburger{display:flex;flex-direction:column;justify-content:space-around;width:24px;height:20px;cursor:pointer}
        .hamburger span{display:block;height:2px;background:#000;border-radius:2px}
        /* Hero */
        section{background-position:50% 50%;background-repeat:no-repeat;background-size:cover}
        section,.container,.container-fluid{position:relative;word-wrap:break-word}
        .mbr-fullscreen{display:flex;align-items:center;min-height:100vh;padding-top:3rem;padding-bottom:3rem}
        .mbr-fullscreen .mbr-overlay{min-height:100vh}
        .mbr-overlay{background-color:#000;bottom:0;left:0;opacity:.5;position:absolute;right:0;top:0;z-index:0;pointer-events:none}
        .mbr-parallax-background{background-attachment:fixed}
        @media(max-width:767px){.mbr-parallax-background{background-attachment:scroll}}
        .cid-s29np8LF2n{padding-top:3rem;padding-bottom:3rem;min-height:100vh}
        .cid-s29np8LF2n .container{position:relative;z-index:1}
        .location_h1_block,.service_h1_block{padding-top:200px!important}
        /* Кнопка */
        .btn{font-weight:500;border-width:2px;letter-spacing:1px;margin:.4rem .8rem;white-space:normal;transition:all .3s ease-in-out;display:inline-flex;align-items:center;justify-content:center;word-break:break-word;border-radius:3px;cursor:pointer;text-decoration:none}
        .btn-md{padding:1rem 3rem;border-radius:3px;margin:.4rem .8rem!important}
        .btn-success,.btn-success:active{background-color:#e9ff00!important;border-color:#e9ff00!important;color:#000!important}
        .btn-success:hover,.btn-success:focus{background-color:#a3b300!important;border-color:#a3b300!important;color:#000!important}
        .mbr-section-btn{margin-left:-.25rem;margin-right:-.25rem;font-size:0}
        .mbr-iconfont-btn{margin-right:.5rem}
        /* text-black используется в навбаре */
        .text-black{color:#000!important}
    </style>

    <!-- =====================================================
         Preconnect — браузер заранее открывает соединения
         ===================================================== -->
    <link rel="preconnect" href="https://www.googletagmanager.com">

    <!-- =====================================================
         Preload — герой фон грузится параллельно с HTML
         ===================================================== -->
    <link rel="preload" as="image" href="/assets/images/2-1920x1280.jpg">

    <!-- =====================================================
         Все внешние CSS — асинхронно, не блокируют рендер
         ===================================================== -->
    <link rel="stylesheet" href="/assets/web/assets/mobirise-icons/mobirise-icons.css"          media="print" onload="this.media='all'">
    <link rel="stylesheet" href="/assets/web/assets/mobirise-icons-bold/mobirise-icons-bold.css" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css"                        media="print" onload="this.media='all'">
    <link rel="stylesheet" href="/assets/dropdown/css/style.css"                                 media="print" onload="this.media='all'">
    <link rel="stylesheet" href="/assets/socicon/css/styles.css"                                 media="print" onload="this.media='all'">
    <link rel="stylesheet" href="/assets/theme/css/style.css"                                    media="print" onload="this.media='all'">
    <link rel="stylesheet" href="/assets/mobirise/css/mbr-additional_new.css"                    media="print" onload="this.media='all'">

    <!-- Fallback для браузеров без JS -->
    <noscript>
        <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="/assets/theme/css/style.css">
        <link rel="stylesheet" href="/assets/mobirise/css/mbr-additional_new.css">
    </noscript>

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