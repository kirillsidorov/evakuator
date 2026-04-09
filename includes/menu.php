<?php
// includes/menu.php

// 1. ПРЕФИКСЫ ССЫЛОК
$link_prefix = ($lang == 'ua') ? '/ua/' : '/';

// 2. СТРУКТУРА И ПЕРЕВОДЫ
$m_lang = [
    'ru' => [
        'brand'    => 'Эвакуатор Харьков',
        
        'nav' => [
            'districts' => 'Районы',
            'services'  => 'Услуги',
            'intercity' => 'Межгород',
            'prices'    => 'Цены',
            'blog'      => 'Блог',
            'contacts'  => 'Контакты'
        ],

        'districts_items' => [
            'evakuator-aleksseyevka'    => 'Алексеевка',
            'evakuator-saltovka'        => 'Салтовка',
            'evakuator-pesochin'        => 'Песочин',
            'evakuator-kholodnaya-gora' => 'Холодная гора',
            'evakuator-novyye-doma'     => 'Новые дома',
            'evakuator-xtz'             => 'ХТЗ',
        ],

        'services_items' => [
            'evakuator-manipulator-kharkov'   => 'Эвакуатор-Манипулятор(Кран)',
            'manipulator-kharkov'    => 'Услуга Манипулятора',
            'gruzovoy-evakuator-kharkov'      => 'Грузовой эвакуатор',
            'Perevozka-spetstekhniki-Kharkov' => 'Перевозка спецтехники',
            'DIVIDER'                         => '---',
            'sto-kharkov'                     => 'Услуги СТО',
            'avtovykup-kharkov'               => 'Автовыкуп',
        ],

        'intercity_items' => [
            'evakuator-po-kharkovskoy-oblasti' => 'По Харьковской области',
            'evakuator-po-ukraine'             => 'По Украине',
            'poputnyy-evakuator'               => 'Попутный эвакуатор',
        ]
    ],
    
    'ua' => [
        'brand'    => 'Евакуатор Харків',
        
        'nav' => [
            'districts' => 'Райони',
            'services'  => 'Послуги',
            'intercity' => 'Міжмісто',
            'prices'    => 'Ціни',
            'blog'      => 'Блог',
            'contacts'  => 'Контакти'
        ],

        'districts_items' => [
            'evakuator-aleksseyevka'    => 'Олексіївка',
            'evakuator-saltovka'        => 'Салтівка',
            'evakuator-pesochin'        => 'Пісочин',
            'evakuator-kholodnaya-gora' => 'Холодна гора',
            'evakuator-novyye-doma'     => 'Нові будинки',
            'evakuator-xtz'             => 'ХТЗ',
        ],

        'services_items' => [
            'evakuator-manipulator-kharkov'   => 'Евакуатор Маніпулятор(Кран)',
            'manipulator-kharkov'    => 'Послуга Маніпулятора',
            'gruzovoy-evakuator-kharkov'      => 'Вантажний евакуатор',
            'Perevozka-spetstekhniki-Kharkov' => 'Перевезення спецтехніки',
            'DIVIDER'                         => '---',
            'sto-kharkov'                     => 'Послуги СТО',
            'avtovykup-kharkov'               => 'Автовикуп',
        ],

        'intercity_items' => [
            'evakuator-po-kharkovskoy-oblasti' => 'По Харківській області',
            'evakuator-po-ukraine'             => 'По Україні',
            'poputnyy-evakuator'               => 'Попутний евакуатор',
        ]
    ]
];

$menu = $m_lang[$lang];

// 3. ЛОГИКА
$current_path = $_SERVER['REQUEST_URI'];
$path_clean = preg_replace('#^/ua/#', '/', $current_path); 
if ($path_clean == '') $path_clean = '/';
if (substr($path_clean, 0, 1) !== '/') $path_clean = '/' . $path_clean;

$link_ru = $path_clean;
$link_ua = '/ua' . ($path_clean == '/' ? '' : $path_clean);

// Кнопка языка
if ($lang == 'ru') {
    $switch_label = 'UA'; 
    $switch_link  = $link_ua;
    $switch_style = 'border: 1px solid #ffeb3b; color: #000; background: transparent;'; 
} else {
    $switch_label = 'RU';
    $switch_link  = $link_ru;
    $switch_style = 'border: 1px solid #ccc; color: #000; background: transparent;'; 
}

?>

<section class="menu cid-qTkzRZLJNu" once="menu">
    <nav class="navbar navbar-expand beta-menu navbar-dropdown align-items-center navbar-fixed-top navbar-toggleable-sm">
        
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <div class="hamburger">
                <span></span><span></span><span></span><span></span>
            </div>
        </button>
        
        <div class="menu-logo">
            <div class="navbar-brand">
                <span class="navbar-caption-wrap">
                    <a class="navbar-caption text-black display-7" href="<?= $link_prefix ?>">
                        <?= $menu['brand'] ?>
                    </a>
                </span>
            </div>
        </div>

        <div class="mobile-phones-block" style="display: flex; align-items: center; white-space: nowrap; margin-left: auto; margin-right: auto;">
            
            <a href="tel:<?= $settings['tel_one_link'] ?>" class="text-black display-7" style="font-weight: bold; font-size: 0.9rem; display: flex; align-items: center;">
                <span class="mbri-mobile2 mbr-iconfont mbr-iconfont-btn" style="font-size: 1.1rem; color: #000000; margin-right: 3px;"></span>
                <?= $settings['tel_one_view']  ?>
            </a>

            <span style="margin: 0 8px; color: #ccc; font-size: 1.2rem; font-weight: 300;">|</span>

            <a href="tel:<?= $settings['tel_two_link'] ?>" class="text-black display-7" style="font-weight: bold; font-size: 0.9rem; display: flex; align-items: center;">
                <span class="mbri-mobile2 mbr-iconfont mbr-iconfont-btn" style="font-size: 1.1rem; color: #000000; margin-right: 3px;"></span>
                <?= $settings['tel_two_view'] ?>
            </a>

        </div>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav nav-dropdown" data-app-modern-menu="true">
                
                <li class="nav-item dropdown">
                    <a class="nav-link link dropdown-toggle text-black display-7" href="#" data-toggle="dropdown-submenu" aria-expanded="false">
                        <?= $menu['nav']['districts'] ?>
                    </a>
                    <div class="dropdown-menu">
                        <?php foreach ($menu['districts_items'] as $slug => $label): ?>
                            <a class="dropdown-item text-black display-7" href="<?= $link_prefix . $slug ?>">
                                <?= $label ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link link dropdown-toggle text-black display-7" href="#" data-toggle="dropdown-submenu" aria-expanded="false">
                        <?= $menu['nav']['services'] ?>
                    </a>
                    <div class="dropdown-menu">
                        <?php foreach ($menu['services_items'] as $slug => $label): ?>
                            <?php if ($slug === 'DIVIDER'): ?>
                                <div class="dropdown-divider"></div>
                            <?php else: ?>
                                <a class="dropdown-item text-black display-7" href="<?= $link_prefix . $slug ?>">
                                    <?= $label ?>
                                </a>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link link dropdown-toggle text-black display-7" href="#" data-toggle="dropdown-submenu" aria-expanded="false">
                        <?= $menu['nav']['intercity'] ?>
                    </a>
                    <div class="dropdown-menu">
                        <?php foreach ($menu['intercity_items'] as $slug => $label): ?>
                            <a class="dropdown-item text-black display-7" href="<?= $link_prefix . $slug ?>">
                                <?= $label ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link link text-black display-7" href="<?= $link_prefix ?>price">
                        <?= $menu['nav']['prices'] ?>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link link text-black display-7" href="<?= $link_prefix ?>news">
                        <?= $menu['nav']['blog'] ?>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link link text-black display-7" href="<?= $link_prefix ?>phone-number">
                        <?= $menu['nav']['contacts'] ?>
                    </a>
                </li>

                <li class="nav-item" style="display: flex; align-items: center; justify-content: center; margin-top: 5px; margin-bottom: 5px;">
                    <a class="btn btn-sm display-7" href="<?= $switch_link ?>" style="padding: 2px 10px; border-radius: 4px; font-weight: bold; margin: 0; <?= $switch_style ?>">
                        <span class="mbri-globe mbr-iconfont mbr-iconfont-btn" style="font-size: 14px; margin-right: 3px;"></span>
                        <?= $switch_label ?>
                    </a>
                </li>

            </ul>
        </div>
    </nav>
</section>