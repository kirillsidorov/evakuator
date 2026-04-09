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

// Формируем финальные Title и Description
$final_title = !empty($title) ? $title : (!empty($page['meta_title']) ? $page['meta_title'] : 'Эвакуатор Харьков');
$final_desc  = !empty($description) ? $description : (!empty($page['meta_description']) ? $page['meta_description'] : '');
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($final_title) ?></title>
    <meta name="description" content="<?= htmlspecialchars($final_desc) ?>">
    <link rel="canonical" href="<?= $canonical_url ?>">

    <link rel="alternate" hreflang="ru-UA" href="<?= $protocol . $host . $link_ru ?>" />
    <link rel="alternate" hreflang="uk-UA" href="<?= $protocol . $host . $link_ua ?>" />
    <link rel="shortcut icon" href="/assets/images/icons8-taxi-service-64-64x64.png" type="image/x-icon">

    <link rel="preload" as="image" href="/assets/images/header-mob.webp" media="(max-width: 767px)" fetchpriority="high">

    <link rel="preload" as="image" href="/assets/images/header-1800x1200.webp" media="(min-width: 768px)" fetchpriority="high">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;600;700&family=Inter:wght@400;500&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;600;700&family=Inter:wght@400;500&display=swap" media="print" onload="this.media='all'">
    <noscript>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;600;700&family=Inter:wght@400;500&display=swap">
    </noscript>

    <style>
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
    html{scroll-behavior:smooth}
    body{font-family:'Inter',sans-serif;background:#fff;color:#111;-webkit-font-smoothing:antialiased}
    a{text-decoration:none;color:inherit}
    img{display:block;width:100%;height:auto}

    /* ── PREVIEW BANNER ── */
    .preview-bar{background:#111;color:#e9ff00;text-align:center;font-size:12px;font-weight:600;letter-spacing:1px;padding:8px 16px;position:sticky;top:0;z-index:200;text-transform:uppercase}

    /* ── NAV DESKTOP ── */
    .nav{background:#fff;border-bottom:1px solid #f0f0f0;position:sticky;top:0px;z-index:100;display:none}
    .nav-inner{max-width:1200px;margin:0 auto;padding:0 24px;display:flex;align-items:center;justify-content:space-between;height:64px}
    .nav-logo{font-family:'Oswald',sans-serif;font-weight:700;font-size:22px;color:#111;letter-spacing:0.5px;white-space:nowrap}
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
    @media(min-width:768px){
    .nav{display:block}
    body{padding-bottom:0}
    }

    /* ── MOBILE TOP BAR ── */
    .mob-topbar{display:flex;align-items:center;justify-content:space-between;background:#fff;border-bottom:1px solid #f0f0f0;padding:0 16px;height:54px;position:sticky;top:0px;z-index:100}
    .mob-logo{font-family:'Oswald',sans-serif;font-weight:700;font-size:18px;color:#111}
    .mob-burger{display:flex;flex-direction:column;justify-content:center;gap:5px;width:36px;height:36px;cursor:pointer;border:none;background:none;padding:4px}
    .mob-burger span{display:block;width:22px;height:2px;background:#111;border-radius:2px;transition:transform .3s,opacity .3s}
    .mob-burger.is-open span:nth-child(1){transform:translateY(7px) rotate(45deg)}
    .mob-burger.is-open span:nth-child(2){opacity:0}
    .mob-burger.is-open span:nth-child(3){transform:translateY(-7px) rotate(-45deg)}
    @media(min-width:768px){.mob-topbar{display:none}}

    /* ── ПЛАВАЮЩАЯ КНОПКА ЗВОНКА (FAB) ── */
    .fab-call {position: fixed;bottom: 24px;
        right: 24px;width: 64px;
        height: 64px;background: #e9ff00; /* Ваш фирменный цвет */
        color: #111;border-radius: 50%;
        display: flex;align-items: center;
        justify-content: center;box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        z-index: 150;        
        /* Скрыта по умолчанию */
        opacity: 0;pointer-events: none;transform: translateY(30px) scale(0.9);
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    }

    .fab-call svg {width: 28px;height: 28px;}
    /* Класс для отображения кнопки */
    .fab-call.is-visible {opacity: 1;pointer-events: auto;transform: translateY(0) scale(1);}
    /* Скрываем ее на десктопах, так как там есть меню с телефоном */
    @media (min-width: 992px) {.fab-call { display: none !important; }
}
    /* Убираем старую панель (на случай если где-то остался старый класс) */
    .mob-bottom { display: none !important; }
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
    .mob-sheet-links .sub-links{padding:0 0 4px 16px;display:flex;flex-direction:column;gap:2px}
    .mob-sheet-links .sub-links a{background:#f0f0ee;font-size:14px;font-weight:400;color:#555;padding:10px 14px;border-radius:8px}
    .mob-sheet-cta{margin:8px 16px 20px;display:flex;align-items:center;justify-content:center;gap:10px;background:#e9ff00;color:#000;font-family:'Oswald',sans-serif;font-size:18px;font-weight:700;border-radius:12px;padding:18px;letter-spacing:.3px}
    .mob-sheet-cta svg{width:18px;height:18px}

    /* safe area padding for bottom bar */
    body{padding-bottom:70px}
    @media(min-width:768px){body{padding-bottom:0}}

    /* ── HERO ── */
    .hero{position:relative;min-height:95svh;display:flex;align-items:flex-end;overflow:hidden;background:#0a0a0a url('https://evakuator-kharkov.kh.ua/assets/images/header-mob.webp') center/cover no-repeat}
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
        .hero{align-items:center}
        .hero-body{padding:60px 48px}
        .hero {
            background-image: url('/assets/images/header-1800x1200.webp');
            background-size: cover;
            background-position: center;
            align-items: center;
        }   
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

    /* ── SECTION ── */
    .sec{padding:40px 24px}
    .sec+.sec{border-top:8px solid #f5f5f3}
    .sec-inner{max-width:1100px;margin:0 auto}
    .sec-label{font-size:11px;font-weight:700;color:#c9a000;letter-spacing:2px;text-transform:uppercase;margin-bottom:8px}
    .sec-title{font-family:'Oswald',sans-serif;font-size:clamp(26px,6vw,40px);font-weight:700;color:#111;line-height:1.05;margin-bottom:24px}
    @media(min-width:768px){.sec{padding:56px 40px}}
    @media(min-width:1200px){.sec{padding:64px 40px}}

    /* ── LIST ── */
    .num-list{list-style:none;display:flex;flex-direction:column;gap:14px}
    .num-list li{display:flex;align-items:flex-start;gap:14px;font-size:15px;color:#444;line-height:1.55}
    .num{flex-shrink:0;width:30px;height:30px;background:#e9ff00;border-radius:7px;display:flex;align-items:center;justify-content:center;font-family:'Oswald',sans-serif;font-weight:700;font-size:14px;color:#000}
    @media(min-width:768px){
    .num-list{display:grid;grid-template-columns:1fr 1fr;gap:16px}
    }

    /* ── CTA BAND ── */
    .band{background:#111;padding:40px 24px;text-align:center}
    .band-inner{max-width:600px;margin:0 auto}
    .band-title{font-family:'Oswald',sans-serif;font-size:clamp(22px,5vw,32px);font-weight:700;color:#fff;margin-bottom:8px}
    .band-sub{font-size:14px;color:rgba(255,255,255,.45);margin-bottom:24px}
    .band-cta{display:inline-flex;align-items:center;justify-content:center;gap:10px;background:#e9ff00;color:#000;font-family:'Oswald',sans-serif;font-size:18px;font-weight:700;border-radius:10px;padding:17px 36px;transition:background .2s}
    .band-cta:hover{background:#d4e800}
    .band-cta svg{width:18px;height:18px;flex-shrink:0}

    /* ── STEPS ── */
    .steps{display:flex;flex-direction:column;gap:0}
    .step{display:flex;gap:18px;padding:20px 0;border-bottom:1px solid #f0f0f0}
    .step:last-child{border-bottom:none}
    .step-num{flex-shrink:0;width:44px;height:44px;border:2.5px solid #e9ff00;border-radius:50%;display:flex;align-items:center;justify-content:center;font-family:'Oswald',sans-serif;font-weight:700;font-size:20px;color:#111}
    .step-title{font-family:'Oswald',sans-serif;font-size:18px;font-weight:600;color:#111;margin-bottom:4px}
    .step-text{font-size:14px;color:#666;line-height:1.55}
    @media(min-width:768px){
    .steps{display:grid;grid-template-columns:repeat(3,1fr);gap:0}
    .step{flex-direction:column;border-bottom:none;border-right:1px solid #f0f0f0;padding:24px 28px}
    .step:last-child{border-right:none}
    }

    /* ── WHY US ── */
    .why-grid{display:flex;flex-direction:column;gap:12px}
    .why-card{background:#f8f8f6;border-radius:12px;padding:18px;display:flex;gap:16px;align-items:flex-start}
    .why-icon{flex-shrink:0;width:44px;height:44px;background:#e9ff00;border-radius:9px;display:flex;align-items:center;justify-content:center;font-family:'Oswald',sans-serif;font-weight:700;font-size:16px;color:#000}
    .why-title{font-family:'Oswald',sans-serif;font-size:17px;font-weight:600;color:#111;margin-bottom:4px}
    .why-text{font-size:13px;color:#666;line-height:1.5}
    @media(min-width:600px){
    .why-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px}
    }
    @media(min-width:992px){
    .why-grid{grid-template-columns:repeat(4,1fr)}
    }

    /* ── REVIEWS ── */
    .reviews-grid{display:flex;flex-direction:column;gap:14px}
    .review{background:#f8f8f6;border-radius:12px;padding:18px}
    .review-header{display:flex;align-items:center;gap:12px;margin-bottom:12px}
    .review-avatar{width:42px;height:42px;border-radius:50%;background:#e9ff00;display:flex;align-items:center;justify-content:center;font-family:'Oswald',sans-serif;font-weight:700;font-size:15px;color:#000;flex-shrink:0}
    .review-name{font-weight:600;font-size:14px;color:#111}
    .review-stars{color:#d4a000;font-size:13px;margin-top:2px;letter-spacing:1px}
    .review-text{font-size:14px;color:#555;line-height:1.6}
    @media(min-width:768px){
    .reviews-grid{display:grid;grid-template-columns:repeat(3,1fr)}
    }

    /* ── TEXT SECTION ── */
    .text-block{font-size:15px;color:#555;line-height:1.75}
    .text-block p+p{margin-top:14px}
    .text-cols{display:flex;flex-direction:column;gap:0}
    @media(min-width:768px){
    .text-cols{display:grid;grid-template-columns:1fr 1fr;gap:40px}
    }

    /* ── SERVICES ── */
    .services-grid{display:flex;flex-direction:column;gap:10px}
    .service-card{background:#f8f8f6;border-radius:10px;padding:16px 18px;display:flex;align-items:center;gap:14px;font-size:14px;font-weight:500;color:#111;transition:background .2s}
    .service-card:hover{background:#f0f0ec}
    .service-num{flex-shrink:0;width:32px;height:32px;background:#e9ff00;border-radius:6px;display:flex;align-items:center;justify-content:center;font-family:'Oswald',sans-serif;font-weight:700;font-size:13px;color:#000}
    .service-arrow{margin-left:auto;color:#bbb;font-size:18px}
    @media(min-width:600px){
    .services-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px}
    }
    @media(min-width:992px){
    .services-grid{grid-template-columns:repeat(3,1fr)}
    }

    /* ── CONTACTS ── */
    .contacts-list{display:flex;flex-direction:column;gap:16px}
    .contact-row{display:flex;align-items:flex-start;gap:14px}
    .contact-icon{width:42px;height:42px;background:#f0f0f0;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
    .contact-icon svg{width:20px;height:20px}
    .contact-label{font-size:11px;color:#999;margin-bottom:3px;text-transform:uppercase;letter-spacing:.5px}
    .contact-val{font-size:15px;font-weight:500;color:#111}
    .contact-val a{color:#111}

    /* ── FAQ ── */
    .faq{display:flex;flex-direction:column;gap:0}
    .faq-item{border-bottom:1px solid #f0f0f0}
    .faq-q{width:100%;background:none;border:none;padding:18px 0;text-align:left;font-family:'Inter',sans-serif;font-size:15px;font-weight:500;color:#111;cursor:pointer;display:flex;justify-content:space-between;align-items:center;gap:12px}
    .faq-q:hover{color:#000}
    .faq-icon{flex-shrink:0;width:24px;height:24px;background:#f0f0f0;border-radius:50%;display:flex;align-items:center;justify-content:center;transition:transform .25s,background .2s;font-size:16px;line-height:1;color:#555}
    .faq-item.open .faq-icon{transform:rotate(45deg);background:#e9ff00;color:#000}
    .faq-a{font-size:14px;color:#666;line-height:1.65;max-height:0;overflow:hidden;transition:max-height .3s ease,padding .3s}
    .faq-item.open .faq-a{max-height:400px;padding-bottom:16px}

    /* ── MAP ── */
    .map-wrap{border-radius:12px;overflow:hidden;height:280px}
    .map-wrap iframe{width:100%;height:100%;border:0}
    @media(min-width:768px){.map-wrap{height:380px}}

    /* ── FOOTER ── */
    .footer{background:#111;padding:40px 24px 32px}
    .footer-inner{max-width:1100px;margin:0 auto}
    .footer-grid{display:grid;grid-template-columns:1fr 1fr;gap:28px;margin-bottom:32px}
    .footer-col-title{font-family:'Oswald',sans-serif;font-size:12px;font-weight:700;color:#e9ff00;letter-spacing:1.5px;text-transform:uppercase;margin-bottom:12px}
    .footer-links{list-style:none;display:flex;flex-direction:column;gap:7px}
    .footer-links a{font-size:13px;color:rgba(255,255,255,.5);transition:color .2s}
    .footer-links a:hover{color:rgba(255,255,255,.85)}
    .footer-phones{display:flex;flex-direction:column;gap:8px;margin-bottom:16px}
    .footer-phones a{display:flex;align-items:center;gap:8px;font-size:15px;font-weight:600;color:#fff}
    .footer-phones a span{font-size:12px;color:rgba(255,255,255,.4);font-weight:400}
    .footer-social{display:flex;gap:10px;margin-top:16px}
    .footer-soc{width:36px;height:36px;background:rgba(255,255,255,.08);border-radius:8px;display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,.6);transition:background .2s}
    .footer-soc:hover{background:rgba(255,255,255,.15)}
    .footer-copy{border-top:1px solid rgba(255,255,255,.08);padding-top:20px;font-size:12px;color:rgba(255,255,255,.25);text-align:center}
    @media(min-width:600px){.footer-grid{grid-template-columns:repeat(3,1fr)}}
    @media(min-width:992px){.footer-grid{grid-template-columns:repeat(4,1fr)}}

    /* ── TABLES & BLOG (Дополнение к базовому CSS) ── */
    .table-wrap { overflow-x: auto; margin-bottom: 24px; border-radius: 10px; border: 1px solid #f0f0f0; }
    .custom-table { width: 100%; min-width: 600px; border-collapse: collapse; text-align: left; font-size: 14px; }
    .custom-table th { background: #f8f8f6; font-family: 'Oswald', sans-serif; font-weight: 600; padding: 14px 16px; border-bottom: 2px solid #e0e0e0; color: #111; text-transform: uppercase; letter-spacing: 0.5px; font-size: 13px; }
    .custom-table td { padding: 14px 16px; border-bottom: 1px solid #f0f0f0; color: #444; }
    .custom-table tr:last-child td { border-bottom: none; }
    .custom-table tr:hover td { background: #fafafa; }
    .custom-table a { color: #111; font-weight: 600; text-decoration: underline; text-decoration-color: #e9ff00; text-decoration-thickness: 2px; }
    .custom-table a:hover { color: #888; }

    .blog-grid { display: grid; gap: 20px; grid-template-columns: 1fr; }
    @media(min-width: 600px) { .blog-grid { grid-template-columns: repeat(2, 1fr); } }
    @media(min-width: 992px) { .blog-grid { grid-template-columns: repeat(3, 1fr); } }
    .blog-card { display: flex; flex-direction: column; background: #fff; border: 1px solid #f0f0f0; border-radius: 12px; overflow: hidden; transition: transform 0.2s; }
    .blog-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,.05); }
    .blog-card img { width: 100%; height: 200px; object-fit: cover; }
    .blog-body { padding: 20px; display: flex; flex-direction: column; flex-grow: 1; }
    .blog-title { font-family: 'Oswald', sans-serif; font-size: 18px; font-weight: 700; color: #111; margin-bottom: 10px; line-height: 1.2; }
    .blog-desc { font-size: 14px; color: #666; line-height: 1.5; margin-bottom: 20px; flex-grow: 1; }
    .blog-btn { display: inline-flex; align-items: center; justify-content: center; background: #f8f8f6; color: #111; font-weight: 600; font-size: 13px; padding: 10px 16px; border-radius: 8px; transition: background 0.2s; align-self: flex-start; }
    .blog-btn:hover { background: #e9ff00; }
    /* ── BREADCRUMBS ── */
    .breadcrumbs { padding: 16px 24px; font-size: 13px; color: #888; background: #fff; border-bottom: 1px solid #f0f0f0; overflow-x: auto; white-space: nowrap; }
    .breadcrumbs-inner { max-width: 1100px; margin: 0 auto; display: flex; align-items: center; gap: 8px; list-style: none; }
    .breadcrumbs a { color: #111; font-weight: 500; transition: color 0.2s; }
    .breadcrumbs a:hover { color: #888; }
    .breadcrumbs-sep { color: #ccc; font-size: 11px; }
    /* ── LANGUAGE SWITCHER ── */
    .lang-switch { display: inline-flex; align-items: center; background: #f0f0ee; color: #111; font-weight: 700; font-size: 13px; padding: 6px 10px; border-radius: 6px; transition: background 0.2s; margin-left: 8px; }
    .lang-switch:hover { background: #e9ff00; }
    .mob-lang-switch { font-weight: 700; font-size: 14px; color: #111; padding: 4px 8px; border: 1px solid #f0f0f0; border-radius: 6px; background: #fafafa; }
    /* ── FOOTER ── */
    .footer { background: #111; color: #fff; padding: 48px 24px 24px; font-size: 14px; margin-top: auto; }
    .footer-inner { max-width: 1100px; margin: 0 auto; }
    .footer-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 32px; margin-bottom: 40px; }
    .footer-col-title { font-family: 'Oswald', sans-serif; font-size: 18px; font-weight: 600; margin-bottom: 16px; color: #e9ff00; letter-spacing: 0.5px; text-transform: uppercase; }
    .footer-links { list-style: none; padding: 0; margin: 0; }
    .footer-links li { margin-bottom: 12px; }
    .footer-links a { color: #aaa; transition: color 0.2s; }
    .footer-links a:hover { color: #fff; text-decoration: underline; text-decoration-color: #e9ff00; }
    .footer-bottom { border-top: 1px solid #333; padding-top: 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; color: #777; font-size: 13px; }
    .social-links { display: flex; gap: 12px; }
    .social-links a { display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 50%; background: #222; color: #fff; transition: background 0.2s; }
    .social-links a:hover { background: #e9ff00; color: #111; }
    /* ── FAQ JS Стили ── */
    .faq-a { max-height: 0; overflow: hidden; transition: max-height 0.3s ease-out; }
    /* ── ВЫРАВНИВАНИЕ ПЕРВОГО ЭКРАНА НА ДЕСКТОПЕ ── */
@media (min-width: 992px) {
    /* 1. Ставим текст Hero строго вровень с логотипом */
    .hero-body {
        max-width: 1100px;
        margin: 0 auto;
        padding: 0 24px;
        width: 100%;
    }

    /* 2. Собираем блок УТП (факты) по центру и делаем его красивым */
    .facts {
        max-width: 1100px;
        margin: 0 auto;
        border-radius: 12px; /* Закругляем углы для премиального вида */
        overflow: hidden; /* Чтобы углы не торчали */
        margin-top: 10px; /* Отступ сверху */
    }
    
    /* Убираем нижнюю полоску у фактов на компьютере */
    .fact { border-bottom: none; }
    
    /* Убираем боковую полоску у последнего факта */
    .fact:last-child { border-right: none; }
}
    </style>


    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-W8H32TN');</script>
    
    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/components/schema.php'; ?>
</head>
<body>
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-W8H32TN" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/components/menu.php'; ?>