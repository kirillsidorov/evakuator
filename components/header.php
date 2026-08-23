<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/config.php'; ?>
<?php
// header.php
// Обновлено (SEO-фикс):
//   1. CANONICAL И HREFLANG СТРОЯТСЯ ТОЛЬКО ИЗ ПУТИ, без query string.
//      Раньше брался $_SERVER['REQUEST_URI'] целиком, и заход по рекламе
//      на /slug?gclid=... отдавал самоканоникал вместе с gclid.
//   2. На 404 canonical/hreflang не выводятся, добавляется noindex.
//   3. $is_ua определяется регуляркой ^/ua(/|$), а не strpos(...)===0 —
//      иначе под украинскую версию попадал любой /ua-что-угодно.
//   4. include_once для config.php (раньше include — файл выполнялся дважды,
//      т.к. router.php уже делает require_once).
//   5. str_replace по '.php'/'.html' заменён на якорную регулярку —
//      раньше вырезал эти подстроки в любом месте URL.

global $page, $settings, $db;
if (!isset($page) || !is_array($page)) {
    $page = ['id' => 0, 'type' => 'physical_file', 'meta_title' => $title ?? '', 'meta_description' => $description ?? ''];
}

$is_404 = (($page['type'] ?? '') === '404');

$protocol    = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$host        = $_SERVER['HTTP_HOST'];
$request_uri = $_SERVER['REQUEST_URI'];

// >>> Только путь. Никаких gclid, utm_source, fbclid в canonical.
$path_only = parse_url($request_uri, PHP_URL_PATH);
if ($path_only === false || $path_only === null || $path_only === '') $path_only = '/';

// >>> Одна функция на весь проект (components/theme_functions.php)
$lang  = function_exists('detect_lang')
    ? detect_lang($path_only)
    : (preg_match('~^/ua(/|$)~', $path_only) ? 'ua' : 'ru');
$is_ua = ($lang === 'ua');

$clean_path = preg_replace('~^/ua(?=/|$)~', '', $path_only);
if ($clean_path === '' || $clean_path === null) $clean_path = '/';

$link_ru = $clean_path;
$link_ua = ($clean_path === '/') ? '/ua/' : '/ua' . $clean_path;

$canonical_url = $protocol . $host . ($is_ua ? $link_ua : $link_ru);

// Убираем расширение только в конце строки (раньше str_replace резал везде)
$canonical_url = preg_replace('~/index\.(php|html)$~i', '/', $canonical_url);
$canonical_url = preg_replace('~\.(php|html)$~i', '', $canonical_url);

$home_ru = $protocol . $host . '/';
$home_ua = $protocol . $host . '/ua/';
if ($canonical_url !== $home_ru && $canonical_url !== $home_ua) {
    $canonical_url = rtrim($canonical_url, '/');
}

// >>> Существует ли вторая языковая версия ЭТОЙ страницы.
// Раньше hreflang выводился всегда — и на статьях без перевода указывал
// на 404. Google в таком случае отбрасывает всю связку, теряя обе версии.
$alt_url = function_exists('alt_lang_url') ? alt_lang_url($db ?? null, $page, $lang) : null;
$url_ru  = $is_ua ? $alt_url : $link_ru;
$url_ua  = $is_ua ? $link_ua : $alt_url;

// Формируем финальные Title и Description
$final_title = !empty($title) ? $title : (!empty($page['meta_title']) ? $page['meta_title'] : 'Эвакуатор Харьков');
$final_desc  = !empty($description) ? $description : (!empty($page['meta_description']) ? $page['meta_description'] : '');

$html_lang = $is_ua ? 'uk'    : 'ru';
$og_locale = $is_ua ? 'uk_UA' : 'ru_UA';

// Класс на <body>: на главной хлебных крошек нет, на остальных страницах есть.
// Полоса крошек добавляет ~40px над hero — без этого класса CSS не может
// посчитать высоту первого экрана и CTA уезжает за нижнюю границу.
$_slug_for_body = $GLOBALS['slug'] ?? '';
$body_class = ($_slug_for_body === 'home' || $_slug_for_body === '') ? 'is-home' : 'has-crumbs';
// page-<type> — чтобы CSS мог сузить колонку у статей, не трогая остальные шаблоны
$_type_for_body = preg_replace('~[^a-z0-9_-]~i', '', (string)($page['type'] ?? ''));
if ($_type_for_body !== '') $body_class .= ' page-' . $_type_for_body;
if ($is_404) $body_class = 'is-404';
?>
<!DOCTYPE html>
<html lang="<?= $html_lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="/assets/images/icons8-taxi-service-64-64x64.webp" type="image/x-icon">

    <title><?= htmlspecialchars($final_title) ?></title>
    <meta name="description" content="<?= htmlspecialchars($final_desc) ?>">

    <?php if ($is_404): ?>
    <meta name="robots" content="noindex">
    <?php endif; ?>

    <meta property="og:title"        content="<?= htmlspecialchars($final_title) ?>">
    <meta property="og:description"  content="<?= htmlspecialchars($final_desc) ?>">
    <meta property="og:type"         content="<?= (($page['type'] ?? '') === 'articles') ? 'article' : 'website' ?>">
    <?php if (($page['type'] ?? '') === 'articles' && !empty($page['date'])): ?>
    <meta property="article:published_time" content="<?= $page['date'] ?>">
    <?php endif; ?>
    <?php if (!$is_404): ?>
    <meta property="og:url"          content="<?= htmlspecialchars($canonical_url, ENT_QUOTES, 'UTF-8') ?>">
    <?php endif; ?>
    <meta property="og:image"        content="https://evakuator-kharkov.kh.ua/assets/images/2-1000x500.webp">
    <meta property="og:image:width"  content="1000">
    <meta property="og:image:height" content="500">
    <meta property="og:locale"       content="<?= $og_locale ?>">

    <?php if (!$is_404): ?>
    <link rel="canonical" href="<?= htmlspecialchars($canonical_url, ENT_QUOTES, 'UTF-8') ?>">
    <?php if ($alt_url): ?>
    <link rel="alternate" hreflang="ru-UA"     href="<?= htmlspecialchars($protocol . $host . $url_ru, ENT_QUOTES, 'UTF-8') ?>">
    <link rel="alternate" hreflang="uk-UA"     href="<?= htmlspecialchars($protocol . $host . $url_ua, ENT_QUOTES, 'UTF-8') ?>">
    <link rel="alternate" hreflang="x-default" href="<?= htmlspecialchars($protocol . $host . $url_ru, ENT_QUOTES, 'UTF-8') ?>">
    <?php endif; ?>
    <?php endif; ?>

    <!-- =====================================================
         PRELOAD — герой фон параллельно с HTML
         ===================================================== -->
    <?php if (!$is_404): ?>
    <link rel="preload" as="image" href="/assets/images/header-mob.webp" media="(max-width: 767px)" fetchpriority="high">
    <link rel="preload" as="image" href="/assets/images/header-1800x1200.webp" media="(min-width: 768px)" fetchpriority="high">
    <?php endif; ?>

    <!-- =====================================================
         FONTS — preconnect + async load
         ===================================================== -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;600;700&family=Inter:wght@400;500&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;600;700&family=Inter:wght@400;500&display=swap" media="print" onload="this.media='all'">
    <noscript>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;600;700&family=Inter:wght@400;500&display=swap">
    </noscript>

    <!-- =====================================================
         CRITICAL CSS — inline, покрывает первый экран:
         reset + nav + mobile menu + hero + facts
    ===================================================== -->
    <style>
        /* Reset */
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        html{scroll-behavior:smooth}
        body{font-family:'Inter',sans-serif;background:#fff;color:#111;-webkit-font-smoothing:antialiased}
        a{text-decoration:none;color:inherit}
        img{display:block;width:100%;height:auto}

        /* ── NAV DESKTOP ── */
        .nav{background:#fff;border-bottom:1px solid #f0f0f0;position:sticky;top:0;z-index:100;display:none}
        .nav-inner{max-width:1200px;margin:0 auto;padding:0 24px;display:flex;align-items:center;justify-content:space-between;height:64px}
        .nav-logo{font-family:'Oswald',sans-serif;font-weight:700;font-size:22px;color:#111;letter-spacing:.5px;white-space:nowrap}
        .nav-links{display:flex;align-items:center;gap:2px}
        .nav-links a{padding:8px 12px;font-size:14px;font-weight:500;color:#444;border-radius:6px;transition:background .2s,color .2s;white-space:nowrap}
        .nav-links a:hover{background:#f5f5f5;color:#111}
        .nav-links .nav-has-sub{position:relative}
        .nav-links .nav-has-sub:hover .nav-sub{display:block}
        .nav-sub{display:none;position:absolute;top:100%;left:0;background:#fff;border:1px solid #f0f0f0;border-radius:10px;box-shadow:0 8px 24px rgba(0,0,0,.1);padding:8px;min-width:200px;z-index:200}
        .nav-sub a{display:block;padding:8px 14px;font-size:13px;color:#333;border-radius:6px;white-space:nowrap}
        .nav-sub a:hover{background:#f5f5f5;color:#111}
        .nav-sub .nav-sub-divider{height:1px;background:#f0f0f0;margin:4px 0}
        .nav-phones-desk{display:flex;align-items:center;gap:12px}
        .nav-phones-desk a{display:flex;align-items:center;gap:6px;font-size:13px;font-weight:600;color:#111}
        .phone-dot{width:7px;height:7px;background:#e9ff00;border-radius:50%;flex-shrink:0}
        .nav-cta-desk{background:#e9ff00;color:#000;font-family:'Oswald',sans-serif;font-weight:700;font-size:14px;padding:10px 20px;border-radius:8px;white-space:nowrap;transition:background .2s}
        .nav-cta-desk:hover{background:#d4e800}
        @media(min-width:768px){.nav{display:block}body{padding-bottom:0}}

        /* ── MOBILE TOP BAR ── */
        .mob-topbar{display:flex;align-items:center;justify-content:space-between;background:#fff;border-bottom:1px solid #f0f0f0;padding:0 16px;height:54px;position:sticky;top:0;z-index:100}
        .mob-logo{font-family:'Oswald',sans-serif;font-weight:700;font-size:18px;color:#111}
        .mob-burger{display:flex;flex-direction:column;justify-content:center;gap:5px;width:36px;height:36px;cursor:pointer;border:none;background:none;padding:4px}
        .mob-burger span{display:block;width:22px;height:2px;background:#111;border-radius:2px;transition:transform .3s,opacity .3s}
        @media(min-width:768px){.mob-topbar{display:none}}

        /* ── FAB CALL ── */
        /* Инверсия: тёмный круг + жёлтая иконка.
           Раньше был жёлтый круг на белом фоне — контраст ~1.2:1,
           кнопка читалась как подсветка текста, а не как элемент. */
        .fab-call{position:fixed;bottom:20px;right:20px;width:62px;height:62px;background:#111;color:#e9ff00;border:2px solid #e9ff00;border-radius:50%;display:flex;align-items:center;justify-content:center;box-shadow:0 10px 28px rgba(0,0,0,.3);z-index:150;opacity:0;pointer-events:none;transform:translateY(30px) scale(.9);transition:opacity .3s,transform .3s cubic-bezier(.25,.8,.25,1)}
        .fab-call svg{width:26px;height:26px;position:relative;z-index:1}
        .fab-call.is-visible{opacity:1;pointer-events:auto;transform:translateY(0) scale(1)}
        .fab-call:active{transform:scale(.94)}
        /* Три волны при появлении, потом покой. Бесконечная пульсация
           на мобиле быстро начинает читаться как баннер и игнорируется. */
        .fab-call::before{content:'';position:absolute;inset:-2px;border-radius:50%;border:2px solid #e9ff00;opacity:0;pointer-events:none}
        .fab-call.is-visible::before{animation:fabPulse 2s ease-out 3}
        @keyframes fabPulse{
            0%{transform:scale(1);opacity:.75}
            70%{transform:scale(1.65);opacity:0}
            100%{transform:scale(1.65);opacity:0}
        }
        @media(prefers-reduced-motion:reduce){
            .fab-call.is-visible::before{animation:none}
            .fab-call{transition:opacity .2s}
        }
        @media(min-width:992px){.fab-call{display:none!important}}
        .mob-bottom{display:none!important}

        /* ── SLIDE-UP MENU ── */
        .mob-menu-overlay{position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:140;opacity:0;pointer-events:none;transition:opacity .3s}
        .mob-menu-overlay.is-open{opacity:1;pointer-events:all}
        .mob-menu-sheet{position:fixed;bottom:0;left:0;right:0;z-index:145;background:#fff;border-radius:20px 20px 0 0;transform:translateY(100%);transition:transform .35s cubic-bezier(.32,0,.67,0);max-height:85vh;overflow-y:auto;padding-bottom:env(safe-area-inset-bottom)}
        .mob-menu-sheet.is-open{transform:translateY(0);transition:transform .35s cubic-bezier(.33,1,.68,1)}
        .mob-sheet-handle{width:40px;height:4px;background:#e0e0e0;border-radius:2px;margin:12px auto 4px}
        .mob-sheet-head{display:flex;align-items:center;justify-content:space-between;padding:8px 20px 16px}
        .mob-sheet-logo{font-family:'Oswald',sans-serif;font-weight:700;font-size:18px;color:#111}
        .mob-sheet-close{width:32px;height:32px;background:#f5f5f5;border-radius:50%;display:flex;align-items:center;justify-content:center;cursor:pointer;border:none;font-size:18px;color:#555;line-height:1}
        .mob-sheet-phones{display:flex;flex-direction:column;gap:0;margin:0 16px 16px;background:#f8f8f6;border-radius:12px;overflow:hidden}
        .mob-sheet-phones a{display:flex;align-items:center;gap:12px;padding:14px 16px;font-size:16px;font-weight:600;color:#111;border-bottom:1px solid #f0f0f0}
        .mob-sheet-phones a:last-child{border-bottom:none}
        .mob-sheet-phones .ph-badge{background:#e9ff00;border-radius:6px;padding:2px 8px;font-size:10px;font-weight:700;color:#000;margin-left:auto}
        .mob-sheet-section{padding:0 16px 8px}
        .mob-sheet-section-title{font-size:10px;font-weight:700;color:#bbb;letter-spacing:1.5px;text-transform:uppercase;padding:4px 4px 8px}
        .mob-sheet-links{display:flex;flex-direction:column;gap:2px;margin-bottom:8px}
        .mob-sheet-links a{display:flex;align-items:center;justify-content:space-between;padding:12px 16px;font-size:15px;font-weight:500;color:#111;background:#f8f8f6;border-radius:10px;margin-bottom:4px}
        .mob-sheet-links a .arr{color:#bbb;font-size:18px}
        .mob-sheet-cta{margin:8px 16px 20px;display:flex;align-items:center;justify-content:center;gap:10px;background:#e9ff00;color:#000;font-family:'Oswald',sans-serif;font-size:18px;font-weight:700;border-radius:12px;padding:18px;letter-spacing:.3px}
        .mob-sheet-cta svg{width:18px;height:18px}

        /* safe area. Было 70px под .mob-bottom, но она display:none!important —
           это была пустота в конце каждой страницы. 24px нужны, чтобы FAB
           не накрывал последнюю строку футера. */
        body{padding-bottom:24px}
        @media(min-width:768px){body{padding-bottom:0}}

        /* ── HERO ── */
        /* .mob-topbar (54px) sticky и занимает место в потоке, поэтому 95svh
           давали первый экран выше вьюпорта и CTA уезжала за край. */
        .hero{position:relative;min-height:calc(100svh - 54px);display:flex;align-items:flex-end;overflow:hidden;background:#0a0a0a url('/assets/images/header-mob.webp') center/cover no-repeat}
        /* Внутренние страницы: над hero ещё полоса хлебных крошек (~40px) */
        body.has-crumbs .hero{min-height:calc(100svh - 94px)}
        .hero-overlay{position:absolute;inset:0;background:linear-gradient(to top,rgba(0,0,0,.88) 0%,rgba(0,0,0,.45) 55%,rgba(0,0,0,.15) 100%)}
        .hero-body{position:relative;z-index:1;padding:32px 24px 28px;width:100%;max-width:680px}
        .hero-badge{display:inline-flex;align-items:center;gap:8px;background:rgba(233,255,0,.12);border:1px solid rgba(233,255,0,.35);border-radius:24px;padding:5px 14px;font-size:12px;color:#e9ff00;font-weight:600;letter-spacing:.8px;text-transform:uppercase;margin-bottom:18px}
        .hero-badge-dot{width:7px;height:7px;background:#e9ff00;border-radius:50%;flex-shrink:0}
        .hero h1{font-family:'Oswald',sans-serif;font-size:clamp(48px,12vw,84px);font-weight:700;line-height:.95;color:#fff;margin-bottom:12px;letter-spacing:-1px}
        .hero-sub{font-size:clamp(15px,4vw,18px);color:rgba(255,255,255,.7);line-height:1.45;margin-bottom:20px}
        .hero-price{display:flex;align-items:baseline;gap:8px;margin-bottom:32px}
        .hero-price-from{font-size:15px;color:rgba(255,255,255,.45)}
        .hero-price-num{font-family:'Oswald',sans-serif;font-size:40px;font-weight:700;color:#fff;line-height:1}
        .hero-price-unit{font-size:clamp(18px,4vw,24px);color:rgba(255,255,255,.5)}
        .hero-cta{display:inline-flex;align-items:center;justify-content:center;gap:10px;background:#e9ff00;color:#000;font-family:'Oswald',sans-serif;font-size:clamp(17px,4vw,20px);font-weight:700;letter-spacing:.5px;border-radius:10px;padding:18px 32px;transition:background .2s,transform .1s;width:100%}
        .hero-cta:hover{background:#d4e800;transform:translateY(-1px)}
        .hero-cta:active{transform:scale(.98)}
        .hero-cta svg{width:20px;height:20px;flex-shrink:0}
        @media(min-width:600px){.hero-cta{width:auto}}
        @media(min-width:768px){
        .hero{align-items:center;min-height:calc(100svh - 64px);background-image:url('/assets/images/header-1800x1200.webp');background-size:cover;background-position:center}
        body.has-crumbs .hero{min-height:calc(100svh - 104px)}
        .hero-body{padding:60px 48px}
        }

        /* ── FACTS ── */
        .facts{background:#fff;display:grid;grid-template-columns:1fr 1fr;border-top:3px solid #e9ff00}
        .fact{padding:20px 16px;border-right:1px solid #f0f0f0;border-bottom:1px solid #f0f0f0}
        .fact:nth-child(2n){border-right:none}
        .fact:nth-child(3),.fact:nth-child(4){border-bottom:none}
        .fact-num{font-family:'Oswald',sans-serif;font-size:clamp(28px,7vw,40px);font-weight:700;color:#111;line-height:1}
        .fact-num em{color:#e9b200;font-style:normal}
        .fact-label{font-size:12px;color:#888;margin-top:4px;line-height:1.4}
        @media(min-width:768px){
        .facts{grid-template-columns:repeat(4,1fr)}
        .fact{border-bottom:none}
        .fact:nth-child(2n){border-right:1px solid #f0f0f0}
        .fact:last-child{border-right:none}
        }
    </style>

    <!-- =====================================================
         THEME CSS — async, не блокирует первый экран
         ===================================================== -->
    <link rel="stylesheet" href="/assets/css/theme.css" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="/assets/css/theme.css"></noscript>

    <?php
    // Schema.org — на 404 микроразметка не нужна
    if (!$is_404 && file_exists($_SERVER['DOCUMENT_ROOT'] . '/components/schema.php')) {
        include_once $_SERVER['DOCUMENT_ROOT'] . '/components/schema.php';
    }
    ?>
    <!-- Google Tag Manager -->
    <script>
    (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-W8H32TN');
    </script>
    <!-- End Google Tag Manager -->
</head>
<body class="<?= $body_class ?>">

    <?php
    // Меню
    if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/components/menu.php')) {
        include $_SERVER['DOCUMENT_ROOT'] . '/components/menu.php';
    }
    ?>

<main>
