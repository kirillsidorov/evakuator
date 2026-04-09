<?php
// Подключаем базу и конфиг
include_once $_SERVER['DOCUMENT_ROOT'] . '/db.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

// --- ОПРЕДЕЛЕНИЕ СРЕДЫ (Язык и Страница) ---
$current_slug = 'evakuator-po-kharkovskoy-oblasti';

// Проверяем URL на наличие '/ua/' для определения языка
if (strpos($_SERVER['REQUEST_URI'], '/ua/') !== false || strpos($_SERVER['REQUEST_URI'], '/ua') === 0) {
    $lang = 'ua';
} else {
    $lang = 'ru';
}

// Получаем ID текущей страницы из базы (ID 3 для RU, ID 4 для UA)
$page = $db->get('pages', '*', [
    'slug' => $current_slug,
    'lang' => $lang
]);

// Заглушка, если вдруг страницы нет
if (!$page) $page = ['id' => 0];

$page_type = 'hub';

// --- МЕТА-ТЕГИ (Можно тоже брать из базы, но пока оставляем твою логику) ---
if ($lang == 'ua') {
    $title = "ᐈ Евакуатор 《по Харківській області》— " . $settings['tel_one_view']  . "; " . $settings['tel_two_view'];
    $description = "від " . $settings['price_car'] . " грн ⭐【Евакуатор по Харківській області】⏩  Телефон для замовлення евакуатора: ☎️ " . $settings['tel_one_view'];
    $breadcrumb_title = 'Харьківська область';
    $custom_h1 = "Недорогий евакуатор в Харківській області цілодобово";
    $custom_p  = "Терміновий виклик евакуатора протягом 20-40 хвилин&nbsp;<br>від " . $settings['price_car'] . " грн";
    $custom_btn = "Викликати евакуатор";
    include $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php';
} else {
    $title = "ᐈ Эвакуатор 《по Харьковской области》— " . $settings['tel_one_view'];
    $description = "от " . $settings['price_car'] . " грн ⭐【Эвакуатор по Харьковской области】⏩ Телефон: ☎️ " . $settings['tel_one_view'];
    $breadcrumb_title = 'Харьковская область';
    $custom_h1 = "Недорогой эвакуатор в Харьковской области круглосуточно";
    $custom_p  = "Срочный вызов эвакуатора в течение 20-40 минут&nbsp;<br>от " . $settings['price_car'] . " грн";
    $custom_btn = "Вызвать эвакуатор";
    include $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php';
}

include $_SERVER['DOCUMENT_ROOT'] . '/includes/breadcrumbs.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/h1_block.php';
?>

<section class="mbr-section article content1 cid-sfhfC4QgR6" id="dz">
    <div class="container">
        <div class="media-container-row">
            <div class="mbr-text col-12 mbr-fonts-style display-7 col-md-8">
                <p>Аварии и поломки автомобилей стали, к сожалению, привычным явлением на дорогах Харькова и области в связи с заметным увеличением числа транспортных средств. Если Ваш автомобиль заглох посреди дороги, а до ближайшей СТО еще десятки километров, Вы можете вызвать эвакуатор Харьковская область в нашей службе эвакуации. Обеспечим быструю и недорогую транспортировку поврежденных автомобилей в любом районе. Наши эвакуаторы несут круглосуточное дежурство на наиболее напряженных участках дорожных магистралей, поэтому Автосос Харьковская область обычно приезжает на место ДТП в течение 20-45 минут. Эвакуируем различные виды автомобилей и спецтехники: легковые, внедорожники, пассажирские микроавтобусы, катки, мини-экскаваторы.</p>
            </div>
        </div>
    </div>
</section>

<section class="mbr-section content4 cid-sfhfKbVqfS" id="e1">
    <div class="container">
        <div class="media-container-row">
            <div class="title col-12 col-md-8">
                <h2 class="align-center pb-3 mbr-fonts-style display-2">Услуги эвакуатора в Харьковской области</h2>
            </div>
        </div>
    </div>
</section>

<section class="mbr-section article content1 cid-sfhfCOMQKf" id="e0">
    <div class="container">
        <div class="media-container-row">
            <div class="mbr-text col-12 mbr-fonts-style display-7 col-md-8">
                <p>Через данный регион Украины проходит несколько автомагистралей республиканского значения, соединяющих Харьков с Киевом, Полтавой, Днепром, Одессой, Донецкой и Луганской областями. Интенсивный поток автотранспорта наблюдается и по направлению с границей РФ. Вне зависимости от Вашего текущего местоположения, Вы всегда можете рассчитывать, что <a href="/">эвакуатор Харьков</a> поможет Вам в сложной ситуации. Преимущества обращения в нашу службу:</p>
            </div>
        </div>
    </div>
</section>

<section class="mbr-section article content11 cid-s1LYxqocoP" id="dp">
    <div class="container">
        <div class="media-container-row">
            <div class="mbr-text counter-container col-12 col-md-8 mbr-fonts-style display-7">
                <ol>
                    <li>обширный автопарк — эвакуаторы с подъемно-сдвижными и стационарными платформами, оборудованные электрическими лебедками;
                    </li>
                    <li>работа в круглосуточном режиме, принимаем вызовы в любое время суток;
                    </li>
                    <li>оперативный выезд ближайшей к Вам платформы для скорейшей помощи;
                    </li>
                    <li>специалисты службы Автосос Харьков готовы предоставить и технические услуги — прикурить стартер, завести авто с толкача, также у нас имеется собственное СТО;
                    </li>
                    <li>опытный персонал — водители выберут наиболее короткие и безопасные маршруты для эвакуации Вашего авто.
                    </li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="mbr-section article content1 cid-sfhfAPrXNe" id="dy">
    <div class="container">
        <div class="media-container-row">
            <div class="mbr-text col-12 mbr-fonts-style display-7 col-md-8">
                <p>Обращаться за помощью службы эвакуации имеет смысл в различных ситуациях. Чаще всего наши диспетчеры принимают заявки в случае дорожно-транспортных происшествий и возникновения технических неисправностей. Достаточно востребованы также услуги эвакуатор Харьковская область для перевозки новых автомобилей из салона, пригнанных из-за рубежа авто. В сезон разгара сельскохозяйственных работ мы обеспечиваем транспортировку различной техники: тракторы, мини-тракторы, навесное оборудование (плуги, дисковые бороны). Сотрудничаем также со строительными организациями на выгодных условиях — доставка спецтехники к месту проведения дорожно-строительных работ. Обратите внимание на услугу попутный эвакуатор по Украине: перевозим авто и грузы в другие области.</p>
            </div>
        </div>
    </div>
</section>

<section class="mbr-section article content1 cid-sfhzc9OYVz" id="el">
    <div class="container">
        <div class="media-container-row">
            <div class="mbr-text col-12 mbr-fonts-style display-5 col-md-6">
                <font color="#232323"><strong>Просчитайте стоимость заказа <br>прямо сейчас!</strong></font>
            </div>
        </div>
    </div>
</section>

<section class="mbr-section content8 cid-sfhzbgBV1Z" id="ek">
    <div class="container">
        <div class="media-container-row title">
            <div class="col-12 col-md-8">
                <div class="mbr-section-btn align-center"><a class="btn btn-success display-5" href="tel:<?= $settings['tel_one_link'] ?>"><span class="mbri-touch mbr-iconfont mbr-iconfont-btn"></span>Вызвать эвакуатор</a></div>
            </div>
        </div>
    </div>
</section>

<section class="mbr-section content4 cid-sfhfZpkJE0" id="e2">
    <div class="container">
        <div class="media-container-row">
            <div class="title col-12 col-md-8">
                <h2 class="align-center pb-3 mbr-fonts-style display-2">Эвакуатор по Харькову и области: как мы работаем</h2>


            </div>
        </div>
    </div>
</section>

<section class="mbr-section article content1 cid-sfhg36UGP9" id="e3">



    <div class="container">
        <div class="media-container-row">
            <div class="mbr-text col-12 mbr-fonts-style display-7 col-md-8">
                <p>Благодаря продуманным логистическим схемам и обширному автопарку нам удалось максимально сократить время ожидания эвакуаторов клиентами. Чтобы машина оперативно выехала по Вашему заказу, при общении с диспетчером сообщите следующие данные:</p>
            </div>
        </div>
    </div>
</section>

<section class="mbr-section article content11 cid-s1LYxqocoP" id="dq">
    <div class="container">
        <div class="media-container-row">
            <div class="mbr-text counter-container col-12 col-md-8 mbr-fonts-style display-7">
                <ol>
                    <li>Что требуется перевезти — компактный хэтчбек, джип, квадроцикл, мини-трактор и пр.;
                    </li>
                    <li>Причина вызова — авария, поломка, перевозка нового авто из салона, физическая невозможность продолжать управление транспортным средством;
                    </li>
                    <li>Где Вы находитесь в данное время — район Харьковской области, ближайший населенный пункт, километр;
                        маршрут поездки.&nbsp;</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="mbr-section article content1 cid-sfhfzNmkE6" id="dx">
    <div class="container">
        <div class="media-container-row">
            <div class="mbr-text col-12 mbr-fonts-style display-7 col-md-8">
                <p>После обработки полученной информации диспетчер сразу же сообщит Вам цену вызова Автосос Харьковская область, которая зависит от нескольких факторов: дальность поездки, сложность погрузки, модель автомобиля. Стоимость наших услуг вполне доступна водителям и ниже, чем у конкурентов.
                </p>
                <p>Погрузка осуществляется различными способами в зависимости от возникшей ситуации. Например, если автомобиль может передвигаться своим ходом, мы высылаем эвакуатор со стационарной платформой. При заблокированных колесах, заезде в кювет или сложных ДТП целесообразнее выслать подъемно-сдвижную платформу, которая опускается до уровня земли, а авто затягивают при помощи подкатных тележек с гидравлическими домкратами и электрической лебедки.
                </p>
                <p>Вызывайте эвакуатор по Харьковской области в любое время суток. Вы сможете сопровождать свое авто в кабине оператора или просто сообщить ему конечный пункт маршрута. Гарантируем безопасную эвакуацию Вашего автомобиля.
                </p>
                <p><br></p>
            </div>
        </div>
    </div>
</section>

<section class="mbr-section content4 cid-s446tIP2JP" id="8x">
    <div class="container">
        <div class="media-container-row">
            <div class="title col-12 col-md-8">
                <h2 class="align-center pb-3 mbr-fonts-style display-2">Наши эвакуаторы в Харьковской области&nbsp;</h2>
            </div>
        </div>
    </div>
</section>

<section class="features4 cid-s446n6y8yN mbr-parallax-background" id="features4-8w">
    <div class="mbr-overlay" style="opacity: 0.8; background-color: rgb(206, 191, 175);"></div>

    <div class="container">
        <div class="media-container-row">
            <div class="col-12">

                <?php
                // Базовая цена
                $base_price_val = $settings['price_car'] ?? '1000';
                $base_price_txt = ($lang == 'ua') ? 'від ' . $base_price_val . ' грн' : 'от ' . $base_price_val . ' грн';

                // 1. ЗАПРОС К БАЗЕ
                // Мы сразу фильтруем по 'location_type' => 'city'
                $cities = $db->select('pages', '*', [
                    'parent_id' => $page['id'],
                    'lang' => $lang,
                    'location_type' => 'city', // <--- ВОТ ГЛАВНЫЙ ФИЛЬТР
                    'ORDER' => ['breadcrumb_title' => 'ASC']
                ]);

                // Переводы
                $th_city  = ($lang == 'ua') ? 'Населений пункт' : 'Населенный пункт';
                $th_dist  = ($lang == 'ua') ? 'До Харкова' : 'До Харькова';
                $th_time  = ($lang == 'ua') ? 'Час подачі ~' : 'Время подачи ~';
                $th_price = ($lang == 'ua') ? 'Ціна' : 'Цена';
                $btn_text = ($lang == 'ua') ? 'Замовити' : 'Заказать';
                ?>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover bg-white" style="background: #fff; border-radius: 8px; overflow: hidden;">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col" class="align-middle"><?= $th_city ?></th>
                                <th scope="col" class="align-middle text-center"><?= $th_dist ?></th>
                                <th scope="col" class="align-middle text-center d-none d-sm-table-cell"><?= $th_time ?></th>
                                <th scope="col" class="align-middle text-center"><?= $th_price ?></th>
                                <th scope="col" class="align-middle text-center"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($cities)): ?>
                                <?php
                                // Достаем тарифы для калькулятора перед циклом
                                $price_per_km = (int)($settings['price_km'] ?? 30);
                                $base_min     = (int)($settings['price_feed'] ?? 1000);
                                ?>

                                <?php foreach ($cities as $city): ?>
                                    <?php
                                    $attrs = json_decode($city['attributes'], true) ?? [];

                                    $distance = !empty($attrs['distance']) ? (float)$attrs['distance'] : null;
                                    $time     = !empty($attrs['time']) ? $attrs['time'] : '-';
                                    $link     = ($lang == 'ua' ? '/ua/' : '/') . $city['slug'];

                                    // --- ДИНАМИЧЕСКИЙ РАСЧЕТ ЦЕНЫ ---
                                    if ($distance) {
                                        // Считаем: (КМ * Тариф * 2) + Подача
                                        $calculated = ($distance * $price_per_km * 2) + $base_min;
                                        $final_price = round($calculated, -2);
                                    } else {
                                        // Если дистанции нет, берем фикс. цену или базовую 1000
                                        $final_price = !empty($attrs['price']) ? $attrs['price'] : ($settings['price_car'] ?? 1000);
                                    }

                                    // Формируем красивый вывод (добавляем "от/від")
                                    $price_text = (($lang == 'ua') ? 'від ' : 'от ') . $final_price . ' грн';
                                    $dist_text  = $distance ? $distance . ' км' : '-';
                                    ?>
                                    <tr>
                                        <td class="align-middle">
                                            <a href="<?= $link ?>" class="text-black font-weight-bold" style="font-size: 1.1rem;">
                                                <?= $city['breadcrumb_title'] ?>
                                            </a>
                                        </td>
                                        <td class="align-middle text-center"><?= $dist_text ?></td>
                                        <td class="align-middle text-center d-none d-sm-table-cell"><?= $time ?></td>
                                        <td class="align-middle text-center font-weight-bold text-black" style="white-space: nowrap;">
                                            <?= $price_text ?>
                                        </td>
                                        <td class="align-middle text-center">
                                            <a href="tel:<?= $settings['tel_one_link'] ?>" class="btn btn-sm btn-success display-4 m-0 p-2">
                                                <?= $btn_text ?>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center p-3">
                                        <?= ($lang == 'ua') ? 'Список міст оновлюється...' : 'Список городов обновляется...' ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</section>

<?php
include $_SERVER['DOCUMENT_ROOT'] . '/includes/3_steps_block.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/why_we_block.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/contacts_block.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php';
?>