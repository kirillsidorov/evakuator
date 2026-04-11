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

// Формируем финальные Title и Description
$final_title = !empty($title) ? $title : (!empty($page['meta_title']) ? $page['meta_title'] : 'Эвакуатор Харьков');
$final_desc  = !empty($description) ? $description : (!empty($page['meta_description']) ? $page['meta_description'] : '');

$html_lang = $is_ua ? 'uk'    : 'ru';
$og_locale = $is_ua ? 'uk_UA' : 'ru_UA';
?>
<!DOCTYPE html>
<html lang="<?= $html_lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="/assets/images/icons8-taxi-service-64-64x64.png" type="image/x-icon">

    <title><?= htmlspecialchars($final_title) ?></title>
    <meta name="description" content="<?= htmlspecialchars($final_desc) ?>">

    <meta property="og:title"        content="<?= htmlspecialchars($final_title) ?>">
    <meta property="og:description"  content="<?= htmlspecialchars($final_desc) ?>">
    <meta property="og:type"         content="website">
    <meta property="og:url"          content="<?= $canonical_url ?>">
    <meta property="og:image"        content="https://evakuator-kharkov.kh.ua/assets/images/2-1000x500.png">
    <meta property="og:image:width"  content="1000">
    <meta property="og:image:height" content="500">
    <meta property="og:locale"       content="<?= $og_locale ?>">

    <link rel="canonical" href="<?= $canonical_url ?>">
    <link rel="alternate" hreflang="ru-UA"     href="<?= $protocol . $host . $link_ru ?>">
    <link rel="alternate" hreflang="uk-UA"     href="<?= $protocol . $host . $link_ua ?>">
    <link rel="alternate" hreflang="x-default" href="<?= $protocol . $host . $link_ru ?>">

    <!-- =====================================================
         PRELOAD — герой фон параллельно с HTML
         ===================================================== -->
    <link rel="preload" as="image" href="/assets/images/header-mob.webp" media="(max-width: 767px)" fetchpriority="high">
    <link rel="preload" as="image" href="/assets/images/header-1800x1200.webp" media="(min-width: 768px)" fetchpriority="high">

    <!-- =====================================================
         FONTS — preconnect + async load
         ===================================================== -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;600;700&family=Inter:wght@400;500&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;600;700&family=Inter:wght@400;500&display=swap" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;600;700&family=Inter:wght@400;500&display=swap"></noscript>

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
    .fab-call{position:fixed;bottom:24px;right:24px;width:64px;height:64px;background:#e9ff00;color:#111;border-radius:50%;display:flex;align-items:center;justify-content:center;box-shadow:0 8px 24px rgba(0,0,0,.15);z-index:150;opacity:0;pointer-events:none;transform:translateY(30px) scale(.9);transition:all .3s cubic-bezier(.25,.8,.25,1)}
    .fab-call svg{width:28px;height:28px}
    .fab-call.is-visible{opacity:1;pointer-events:auto;transform:translateY(0) scale(1)}
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

    /* safe area */
    body{padding-bottom:70px}
    @media(min-width:768px){body{padding-bottom:0}}

    /* ── HERO ── */
    .hero{position:relative;min-height:95svh;display:flex;align-items:flex-end;overflow:hidden;background:#0a0a0a url('/assets/images/header-mob.webp') center/cover no-repeat}
    .hero-overlay{position:absolute;inset:0;background:linear-gradient(to top,rgba(0,0,0,.88) 0%,rgba(0,0,0,.45) 55%,rgba(0,0,0,.15) 100%)}
    .hero-body{position:relative;z-index:1;padding:40px 24px 48px;width:100%;max-width:680px}
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
    .hero{align-items:center;background-image:url('/assets/images/header-1800x1200.webp');background-size:cover;background-position:center}
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

    <!-- =====================================================
         GTM
         ===================================================== -->
    <link rel="preconnect" href="https://www.googletagmanager.com">
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-W8H32TN');</script>

    <?php
    // Schema.org — ищем сначала в components, потом в includes
    if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/components/schema.php')) {
        include_once $_SERVER['DOCUMENT_ROOT'] . '/components/schema.php';
    } elseif (file_exists($_SERVER['DOCUMENT_ROOT'] . '/includes/schema.php')) {
        include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/schema.php';
    }
    ?>
</head>
<body>
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-W8H32TN" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

    <?php
    // Меню — ищем сначала в components, потом в includes
    if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/components/menu.php')) {
        include $_SERVER['DOCUMENT_ROOT'] . '/components/menu.php';
    } elseif (file_exists($_SERVER['DOCUMENT_ROOT'] . '/includes/menu.php')) {
        include $_SERVER['DOCUMENT_ROOT'] . '/includes/menu.php';
    }
    ?>

<main>
