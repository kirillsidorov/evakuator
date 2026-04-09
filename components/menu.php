<?php
// includes/menu.php

$link_prefix = ($lang == 'ua') ? '/ua/' : '/';

$m_lang = [
    'ru' => [
        'brand'    => 'Эвакуатор Харьков',
        'nav' => ['districts' => 'Районы ▾', 'services'  => 'Услуги ▾', 'intercity' => 'Межгород ▾', 'prices'    => 'Цены', 'blog'      => 'Блог', 'contacts'  => 'Контакты', 'call'      => 'Вызвать эвакуатор', 'menu'      => 'Меню'],
        'districts_items' => ['evakuator-aleksseyevka' => 'Алексеевка', 'evakuator-saltovka' => 'Салтовка', 'evakuator-pesochin' => 'Песочин', 'evakuator-kholodnaya-gora' => 'Холодная гора', 'evakuator-novyye-doma' => 'Новые дома', 'evakuator-xtz' => 'ХТЗ'],
        'services_items' => ['evakuator-manipulator-kharkov' => 'Манипулятор', 'gruzovoy-evakuator-kharkov' => 'Грузовой эвакуатор', 'Perevozka-spetstekhniki-Kharkov' => 'Спецтехника', 'DIVIDER' => '---', 'sto-kharkov' => 'Услуги СТО', 'avtovykup-kharkov' => 'Автовыкуп'],
        'intercity_items' => ['evakuator-po-kharkovskoy-oblasti' => 'Харьковская область', 'evakuator-po-ukraine' => 'По Украине', 'poputnyy-evakuator' => 'Попутный']
    ],
    'ua' => [
        'brand'    => 'Евакуатор Харків',
        'nav' => ['districts' => 'Райони ▾', 'services'  => 'Послуги ▾', 'intercity' => 'Міжмісто ▾', 'prices'    => 'Ціни', 'blog'      => 'Блог', 'contacts'  => 'Контакти', 'call'      => 'Викликати евакуатор', 'menu'      => 'Меню'],
        'districts_items' => ['evakuator-aleksseyevka' => 'Олексіївка', 'evakuator-saltovka' => 'Салтівка', 'evakuator-pesochin' => 'Пісочин', 'evakuator-kholodnaya-gora' => 'Холодна гора', 'evakuator-novyye-doma' => 'Нові будинки', 'evakuator-xtz' => 'ХТЗ'],
        'services_items' => ['evakuator-manipulator-kharkov' => 'Маніпулятор', 'gruzovoy-evakuator-kharkov' => 'Вантажний евакуатор', 'Perevozka-spetstekhniki-Kharkov' => 'Спецтехніка', 'DIVIDER' => '---', 'sto-kharkov' => 'Послуги СТО', 'avtovykup-kharkov' => 'Автовикуп'],
        'intercity_items' => ['evakuator-po-kharkovskoy-oblasti' => 'Харківська область', 'evakuator-po-ukraine' => 'По Україні', 'poputnyy-evakuator' => 'Попутний']
    ]
];

$menu = $m_lang[$lang];

$current_path = $_SERVER['REQUEST_URI'];
$path_clean = preg_replace('#^/ua/#', '/', $current_path); 
if ($path_clean == '') $path_clean = '/';
if (substr($path_clean, 0, 1) !== '/') $path_clean = '/' . $path_clean;

$link_ru = $path_clean;
$link_ua = '/ua' . ($path_clean == '/' ? '' : $path_clean);

$switch_label = ($lang == 'ru') ? 'UA' : 'RU';
$switch_link  = ($lang == 'ru') ? $link_ua : $link_ru;
?>

<nav class="nav">
  <div class="nav-inner">
    <a href="<?= $link_prefix ?>" class="nav-logo"><?= $menu['brand'] ?></a>
    <div class="nav-links">
      <div class="nav-has-sub"><a href="#"><?= $menu['nav']['districts'] ?></a><div class="nav-sub"><?php foreach ($menu['districts_items'] as $slug => $label): ?><a href="<?= $link_prefix . $slug ?>"><?= $label ?></a><?php endforeach; ?></div></div>
      <div class="nav-has-sub"><a href="#"><?= $menu['nav']['services'] ?></a><div class="nav-sub"><?php foreach ($menu['services_items'] as $slug => $label): ?><?php if ($slug === 'DIVIDER'): ?><div class="nav-sub-divider"></div><?php else: ?><a href="<?= $link_prefix . $slug ?>"><?= $label ?></a><?php endif; ?><?php endforeach; ?></div></div>
      <div class="nav-has-sub"><a href="#"><?= $menu['nav']['intercity'] ?></a><div class="nav-sub"><?php foreach ($menu['intercity_items'] as $slug => $label): ?><a href="<?= $link_prefix . $slug ?>"><?= $label ?></a><?php endforeach; ?></div></div>
      <a href="<?= $link_prefix ?>price"><?= $menu['nav']['prices'] ?></a><a href="<?= $link_prefix ?>news"><?= $menu['nav']['blog'] ?></a><a href="<?= $link_prefix ?>phone-number"><?= $menu['nav']['contacts'] ?></a>
      <a href="<?= $switch_link ?>" class="lang-switch"><?= $switch_label ?></a>
    </div>
    <div class="nav-phones-desk">
      <a href="tel:<?= $settings['tel_one_link'] ?>"><div class="phone-dot"></div><?= $settings['tel_one_view'] ?></a>
      <a href="tel:<?= $settings['tel_one_link'] ?>" class="nav-cta-desk"><?= $menu['nav']['call'] ?></a>
    </div>
  </div>
</nav>

<div class="mob-topbar">
  <a href="<?= $link_prefix ?>" class="mob-logo"><?= $menu['brand'] ?></a>
  <div style="display: flex; align-items: center; gap: 12px;">
      <a href="<?= $switch_link ?>" class="mob-lang-switch"><?= $switch_label ?></a>
      <button class="mob-burger" id="mobBurger" aria-label="<?= $menu['nav']['menu'] ?>">
        <span></span><span></span><span></span>
      </button>
  </div>
</div>

<div class="mob-menu-overlay" id="mobOverlay"></div>
<div class="mob-menu-sheet" id="mobSheet">
  <div class="mob-sheet-handle"></div>
  <div class="mob-sheet-head">
    <div class="mob-sheet-logo"><?= $menu['brand'] ?></div>
    <button class="mob-sheet-close" id="mobClose">✕</button>
  </div>

  <div class="mob-sheet-phones">
    <a href="tel:<?= $settings['tel_one_link'] ?>"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#111" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.55 11a19.79 19.79 0 01-3.07-8.67A2 2 0 012.44 0h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 7.91a16 16 0 006.18 6.18l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg><?= $settings['tel_one_view'] ?> <span class="ph-badge">МТС</span></a>
    <a href="tel:<?= $settings['tel_two_link'] ?>"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#111" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.55 11a19.79 19.79 0 01-3.07-8.67A2 2 0 012.44 0h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 7.91a16 16 0 006.18 6.18l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg><?= $settings['tel_two_view'] ?> <span class="ph-badge">Киевстар</span></a>
  </div>

  <div class="mob-sheet-section"><div class="mob-sheet-section-title"><?= str_replace(' ▾', '', $menu['nav']['districts']) ?></div><div class="mob-sheet-links"><?php foreach ($menu['districts_items'] as $slug => $label): ?><a href="<?= $link_prefix . $slug ?>"><?= $label ?> <span class="arr">›</span></a><?php endforeach; ?></div></div>
  <div class="mob-sheet-section"><div class="mob-sheet-section-title"><?= str_replace(' ▾', '', $menu['nav']['services']) ?></div><div class="mob-sheet-links"><?php foreach ($menu['services_items'] as $slug => $label): ?><?php if ($slug !== 'DIVIDER'): ?><a href="<?= $link_prefix . $slug ?>"><?= $label ?> <span class="arr">›</span></a><?php endif; ?><?php endforeach; ?></div></div>
  <div class="mob-sheet-section"><div class="mob-sheet-section-title"><?= str_replace(' ▾', '', $menu['nav']['intercity']) ?></div><div class="mob-sheet-links"><?php foreach ($menu['intercity_items'] as $slug => $label): ?><a href="<?= $link_prefix . $slug ?>"><?= $label ?> <span class="arr">›</span></a><?php endforeach; ?></div></div>
  <div class="mob-sheet-section"><div class="mob-sheet-section-title"><?= ($lang == 'ua') ? 'Клієнтам' : 'Клиентам' ?></div><div class="mob-sheet-links"><a href="<?= $link_prefix ?>price"><?= $menu['nav']['prices'] ?> <span class="arr">›</span></a><a href="<?= $link_prefix ?>news"><?= $menu['nav']['blog'] ?> <span class="arr">›</span></a><a href="<?= $link_prefix ?>phone-number"><?= $menu['nav']['contacts'] ?> <span class="arr">›</span></a></div></div>

  <a href="tel:<?= $settings['tel_one_link'] ?>" class="mob-sheet-cta">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.55 11a19.79 19.79 0 01-3.07-8.67A2 2 0 012.44 0h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 7.91a16 16 0 006.18 6.18l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
    <?= $menu['nav']['call'] ?>
  </a>
</div>

<a href="tel:<?= $settings['tel_one_link'] ?>" class="fab-call" id="fabCall" aria-label="<?= ($lang == 'ua') ? 'Зателефонувати' : 'Позвонить' ?>">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
      <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.55 11a19.79 19.79 0 01-3.07-8.67A2 2 0 012.44 0h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 7.91a16 16 0 006.18 6.18l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/>
  </svg>
</a>