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
                <p>Аварії та поломки автомобілів стали, на жаль, звичним явищем на дорогах Харкова та області у зв'язку з помітним збільшенням кількості транспортних засобів. Якщо Ваш автомобіль заглох посеред дороги, а до найближчого СТО ще десятки кілометрів, Ви можете викликати евакуатор Харківська область у нашій службі евакуації. Забезпечимо швидке і недороге транспортування пошкоджених автомобілів у будь-якому районі. Наші евакуатори несуть цілодобове чергування на найбільш напружених ділянках дорожніх магістралей, тому Автосос Харківська область зазвичай приїжджає на місце ДТП протягом 20-45 хвилин. Евакуюємо різні види автомобілів і спецтехніки: легкові, позашляховики, пасажирські мікроавтобуси, катки, міні-екскаватори.</p>
            </div>
        </div>
    </div>
</section>

<section class="mbr-section content4 cid-sfhfKbVqfS" id="e1">
    <div class="container">
        <div class="media-container-row">
            <div class="title col-12 col-md-8">
                <h2 class="align-center pb-3 mbr-fonts-style display-2">Послуги евакуатора в Харківській області</h2>
            </div>
        </div>
    </div>
</section>

<section class="mbr-section article content1 cid-sfhfCOMQKf" id="e0">
    <div class="container">
        <div class="media-container-row">
            <div class="mbr-text col-12 mbr-fonts-style display-7 col-md-8">
                <p>Через цей регіон України проходить кілька автомагістралей республіканського значення, що з'єднують Харків з Києвом, Полтавою, Дніпром, Одесою, Донецькою та Луганською областями. Інтенсивний потік автотранспорту спостерігається і у напрямку з кордоном РФ. Незалежно від Вашого поточного місцезнаходження, Ви завжди можете розраховувати, що <a href="/ua/">евакуатор Харків</a> допоможе Вам у складній ситуації. Переваги звернення в нашу службу:</p>
            </div>
        </div>
    </div>
</section>

<section class="mbr-section article content11 cid-s1LYxqocoP" id="dp">
    <div class="container">
        <div class="media-container-row">
            <div class="mbr-text counter-container col-12 col-md-8 mbr-fonts-style display-7">
                <ol>
                    <li>великий автопарк — евакуатори з підйомно-зсувними та стаціонарними платформами, обладнані електричними лебідками;</li>
                    <li>робота в цілодобовому режимі, приймаємо виклики в будь-який час доби;</li>
                    <li>оперативний виїзд найближчої до Вас платформи для якнайшвидшої допомоги;</li>
                    <li>фахівці служби Автосос Харків готові надати і технічні послуги — прикурити стартер, завести авто зі штовхача, також у нас є власне СТО;</li>
                    <li>досвідчений персонал — водії виберуть найбільш короткі та безпечні маршрути для евакуації Вашого авто.</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="mbr-section article content1 cid-sfhfAPrXNe" id="dy">
    <div class="container">
        <div class="media-container-row">
            <div class="mbr-text col-12 mbr-fonts-style display-7 col-md-8">
                <p>Звертатися за допомогою служби евакуації має сенс у різних ситуаціях. Найчастіше наші диспетчери приймають заявки в разі дорожньо-транспортних пригод та виникнення технічних несправностей. Досить затребувані також послуги евакуатор Харківська область для перевезення нових автомобілів з салону, пригнаних з-за кордону авто. У сезон розпалу сільськогосподарських робіт ми забезпечуємо транспортування різної техніки: трактори, міні-трактори, навісне обладнання (плуги, дискові борони). Співпрацюємо також з будівельними організаціями на вигідних умовах — доставка спецтехніки до місця проведення дорожньо-будівельних робіт. Зверніть увагу на послугу попутний евакуатор по Україні: перевозимо авто і вантажі в інші області.</p>
            </div>
        </div>
    </div>
</section>

<section class="mbr-section article content1 cid-sfhzc9OYVz" id="el">
    <div class="container">
        <div class="media-container-row">
            <div class="mbr-text col-12 mbr-fonts-style display-5 col-md-6">
                <font color="#232323"><strong>Розрахуйте вартість замовлення <br>прямо зараз!</strong></font>
            </div>
        </div>
    </div>
</section>

<section class="mbr-section content8 cid-sfhzbgBV1Z" id="ek">
    <div class="container">
        <div class="media-container-row title">
            <div class="col-12 col-md-8">
                <div class="mbr-section-btn align-center"><a class="btn btn-success display-5" href="tel:<?= $settings['tel_one_link'] ?>">
                        <span class="mbri-touch mbr-iconfont mbr-iconfont-btn"></span>Викликати евакуатор</a></div>
            </div>
        </div>
    </div>
</section>

<section class="mbr-section content4 cid-sfhfZpkJE0" id="e2">
    <div class="container">
        <div class="media-container-row">
            <div class="title col-12 col-md-8">
                <h2 class="align-center pb-3 mbr-fonts-style display-2">Евакуатор по Харкову та області: як ми працюємо</h2>
            </div>
        </div>
    </div>
</section>

<section class="mbr-section article content1 cid-sfhg36UGP9" id="e3">
    <div class="container">
        <div class="media-container-row">
            <div class="mbr-text col-12 mbr-fonts-style display-7 col-md-8">
                <p>Завдяки продуманим логістичним схемам та великому автопарку нам вдалося максимально скоротити час очікування евакуаторів клієнтами. Щоб машина оперативно виїхала на Ваше замовлення, при спілкуванні з диспетчером повідомте наступні дані:</p>
            </div>
        </div>
    </div>
</section>

<section class="mbr-section article content11 cid-s1LYxqocoP" id="dq">
    <div class="container">
        <div class="media-container-row">
            <div class="mbr-text counter-container col-12 col-md-8 mbr-fonts-style display-7">
                <ol>
                    <li>Що потрібно перевезти — компактний хетчбек, джип, квадроцикл, міні-трактор та ін.;</li>
                    <li>Причина виклику — аварія, поломка, перевезення нового авто з салону, фізична неможливість продовжувати керування транспортним засобом;</li>
                    <li>Де Ви перебуваєте в даний час — район Харківської області, найближчий населений пункт, кілометр; маршрут поїздки.&nbsp;</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="mbr-section article content1 cid-sfhfzNmkE6" id="dx">
    <div class="container">
        <div class="media-container-row">
            <div class="mbr-text col-12 mbr-fonts-style display-7 col-md-8">
                <p>Після обробки отриманої інформації диспетчер відразу ж повідомить Вам ціну виклику Автосос Харківська область, яка залежить від декількох факторів: дальність поїздки, складність навантаження, модель автомобіля. Вартість наших послуг цілком доступна водіям і нижча, ніж у конкурентів.</p>
                <p>Навантаження здійснюється різними способами залежно від ситуації, що виникла. Наприклад, якщо автомобіль може пересуватися своїм ходом, ми висилаємо евакуатор зі стаціонарною платформою. При заблокованих колесах, заїзді в кювет або складних ДТП доцільніше вислати підйомно-зсувну платформу, яка опускається до рівня землі, а авто затягують за допомогою підкатних візків з гідравлічними домкратами та електричної лебідки.</p>
                <p>Викликайте евакуатор по Харківській області в будь-який час доби. Ви зможете супроводжувати своє авто в кабіні оператора або просто повідомити йому кінцевий пункт маршрута. Гарантуємо безпечну евакуацію Вашого автомобіля.</p>
                <p><br></p>
            </div>
        </div>
    </div>
</section>

<section class="mbr-section content4 cid-s446tIP2JP" id="8x">
    <div class="container">
        <div class="media-container-row">
            <div class="title col-12 col-md-8">
                <h2 class="align-center pb-3 mbr-fonts-style display-2">Наші евакуатори в Харківській області&nbsp;</h2>
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
                // Фильтруем по ID родителя (который зависит от языка) и location_type='city'
                $cities = $db->select('pages', '*', [
                    'parent_id' => $page['id'],
                    'lang' => $lang,
                    'location_type' => 'city', // <-- Фильтр: только города
                    'ORDER' => ['breadcrumb_title' => 'ASC']
                ]);

                // Переводы для таблицы
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