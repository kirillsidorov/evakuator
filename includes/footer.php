</main>

<?php
// $lang уже определён в header.php как 'ru' или 'ua'
$is_ua = ($lang === 'ua');

$menu = $is_ua ? [
    'col1_title' => 'Райони Харкова',
    'col1_links' => [
        'evakuator-saltovka'              => 'Салтівка',
        'evakuator-aleksseyevka'          => 'Олексіївка',
        'evakuator-kholodnaya-gora'       => 'Холодна гора',
        'evakuator-xtz'                   => 'ХТЗ',
        'evakuator-novyye-doma'           => 'Нові будинки',
        '/'                               => 'Центр',
    ],
    'col2_title' => 'Область',
    'col2_links' => [
        'evakuator-pesochin'              => 'Пісочин',
        'evakuator-merefa'                => 'Мерефа',
        'evakuator-Chuguyev'              => 'Чугуїв',
        'evakuator-balakleya'             => 'Балаклія',
        'evakuator-Izyum'                 => 'Ізюм',
        'evakuator-Kupyansk'              => 'Куп\'янськ',
        'evakuator-Lozovaya'              => 'Лозова',
        'evakuator-po-kharkovskoy-oblasti'=> '<strong>Вся область...</strong>',
    ],
    'col3_title' => 'Послуги',
    'col3_links' => [
        'gruzovoy-evakuator-kharkov'      => 'Вантажний евакуатор',
        'evakuator-manipulator-kharkov'   => 'Маніпулятор',
        'Perevozka-spetstekhniki-Kharkov' => 'Перевезення спецтехніки',
        'avtosos'                         => 'Автосос',
        'sto-kharkov'                     => 'Послуги СТО',
        'avtovykup-kharkov'               => 'Автовикуп',
    ],
    'col4_title' => 'Клієнтам',
    'col4_links' => [
        'price'                  => 'Тарифи та Ціни',
        'phone-number'           => 'Контакти',
        'avtopark-evakuatorov'   => 'Автопарк',
        'news'                   => 'Блог',
    ],
    'google_aria'   => 'Подивитись відгуки про Евакуатор Харків в Google',
    'instagram_aria'=> 'Перейти до Instagram профілю Евакуатор Харків',
    'copyright'     => '© Copyright 2010-' . date('Y') . ' Евакуатор по Харкову та Україні - Всі права захищені.',
] : [
    'col1_title' => 'Районы Харькова',
    'col1_links' => [
        'evakuator-saltovka'              => 'Салтовка',
        'evakuator-aleksseyevka'          => 'Алексеевка',
        'evakuator-kholodnaya-gora'       => 'Холодная гора',
        'evakuator-xtz'                   => 'ХТЗ',
        'evakuator-novyye-doma'           => 'Новые дома',
        '/'                               => 'Центр',
    ],
    'col2_title' => 'Область и Украина',
    'col2_links' => [
        'evakuator-pesochin'              => 'Эвакуатор Песочин',
        'evakuator-po-kharkovskoy-oblasti'=> 'Харьковская область',
        'evakuator-po-ukraine'            => 'По Украине',
        'poputnyy-evakuator'              => 'Попутный эвакуатор',
    ],
    'col3_title' => 'Услуги',
    'col3_links' => [
        'gruzovoy-evakuator-kharkov'      => 'Грузовой эвакуатор',
        'evakuator-manipulator-kharkov'   => 'Манипулятор',
        'Perevozka-spetstekhniki-Kharkov' => 'Перевозка спецтехники',
        'avtosos'                         => 'Автосос',
        'sto-kharkov'                     => 'Услуги СТО',
        'avtovykup-kharkov'               => 'Автовыкуп',
    ],
    'col4_title' => 'Клиентам',
    'col4_links' => [
        'price'                  => 'Тарифы и Цены',
        'phone-number'           => 'Контакты',
        'avtopark-evakuatorov'   => 'Автопарк',
        'news'                   => 'Блог',
    ],
    'google_aria'   => 'Посмотреть отзывы об Эвакуатор Харьков в Google',
    'instagram_aria'=> 'Перейти в Instagram профиль Эвакуатор Харьков',
    'copyright'     => '© Copyright 2010-' . date('Y') . ' Эвакуатор по Харькову и Украине - Все права защищены.',
];
?>

<section once="footers" class="cid-s2bvsZyFEe mbr-reveal">
    <div class="container">
        <div class="media-container-row align-center mbr-white">
            <div class="row row-links">
                <ul class="foot-menu">

                    <li class="foot-menu-item mbr-fonts-style display-7">
                        <p><strong><?= $menu['col1_title'] ?></strong><br>
                        <?php foreach ($menu['col1_links'] as $href => $label): ?>
                            <a href="<?= $href ?>"><?= $label ?></a><br>
                        <?php endforeach; ?>
                        </p>
                    </li>

                    <li class="foot-menu-item mbr-fonts-style display-7">
                        <p><strong><?= $menu['col2_title'] ?></strong><br>
                        <?php foreach ($menu['col2_links'] as $href => $label): ?>
                            <a href="<?= $href ?>"><?= $label ?></a><br>
                        <?php endforeach; ?>
                        </p>
                    </li>

                    <li class="foot-menu-item mbr-fonts-style display-7">
                        <div><strong><?= $menu['col3_title'] ?></strong><br>
                        <?php foreach ($menu['col3_links'] as $href => $label): ?>
                            <a href="<?= $href ?>"><?= $label ?></a><br>
                        <?php endforeach; ?>
                        </div>
                    </li>

                    <li class="foot-menu-item mbr-fonts-style display-7">
                        <strong><?= $menu['col4_title'] ?></strong><br>
                        <?php foreach ($menu['col4_links'] as $href => $label): ?>
                            <a href="<?= $href ?>"><?= $label ?></a><br>
                        <?php endforeach; ?>
                    </li>

                </ul>
            </div>

            <div class="row social-row">
                <div class="social-list align-right pb-2">
                    <div class="soc-item">
                        <a href="https://g.page/r/CQ22OWHBZsJZEBM/" target="_blank" rel="noopener" aria-label="<?= $menu['google_aria'] ?>">
                            <span class="mbr-iconfont mbr-iconfont-social socicon-google socicon" style="color: rgb(255, 255, 255); fill: rgb(255, 255, 255);"></span>
                        </a>
                    </div>
                    <div class="soc-item">
                        <a href="https://www.instagram.com/evakuatorkharkov/" target="_blank" aria-label="<?= $menu['instagram_aria'] ?>">
                            <span class="mbr-iconfont mbr-iconfont-social socicon-instagram socicon" style="color: rgb(255, 255, 255); fill: rgb(255, 255, 255);"></span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="row row-copirayt">
                <p class="mbr-text mb-0 mbr-fonts-style mbr-white align-center display-7"><?= $menu['copyright'] ?></p>
            </div>

        </div>
    </div>
</section>

<script src="/assets/web/assets/jquery/jquery.min.js" defer></script>
<script src="/assets/popper/popper.min.js" defer></script>
<script src="/assets/bootstrap/js/bootstrap.min.js" defer></script>
<script src="/assets/smoothscroll/smooth-scroll.js" defer></script>
<script src="/assets/dropdown/js/nav-dropdown.js" defer></script>
<script src="/assets/dropdown/js/navbar-dropdown.js" defer></script>
<script src="/assets/touchswipe/jquery.touch-swipe.min.js" defer></script>
<script src="/assets/theme/js/script.js" defer></script>

</body>
</html>