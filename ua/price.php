<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

$page_type = 'prices';

$title = "ᐈ Евакуатор 《Харків》 вартість, ціна на виклик евакуатора, вартість евакуації автомобіля, автосос Харків ціна";
$description = "【Евакуатор Дешево】 ⏩ Скільки коштує виклик евакуатора у Харкові? ⭐ Ціна на виклик послуг евакуатора Харків ☎️ " . $settings['tel_one_view']  . ", " . $settings['tel_two_view'];

include $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'; //ua
include $_SERVER['DOCUMENT_ROOT'] . '/includes/breadcrumbs.php';
?>

<section class="section-table pb-5 cid-s1LSywcbcb" style="padding-top: 130px;">
    <div class="container">
        <h1 class="mbr-section-title mbr-fonts-style align-center pb-3 display-2">Ціна евакуатора в Харкові</h1>
        <h2 class="mbr-section-subtitle mbr-fonts-style align-center pb-5 mbr-light display-5">
            Точну вартість послуг евакуатора назве диспетчер.<br>
            <span style="font-size: 1.1rem;">Для цього повідомте: марку авто, характер поломки, звідки забрати та куди доставити.</span>
        </h2>
        
        <div class="table-responsive">
            <table class="table table-striped table-bordered mbr-fonts-style display-7">
                <thead class="thead-dark" style="background-color: #333; color: #fff;">
                    <tr>
                        <th>Послуги автоевакуатора</th>
                        <th>Вартість</th>
                    </tr>
                </thead>
                <tbody>              
                    <tr> 
                        <td>Евакуація легкового автомобіля по місту</td>
                        <td><strong>від <?= $settings['price_car'] ?> грн</strong></td>
                    </tr>
                    <tr>           
                        <td>Евакуація позашляховика (Джип) / Мікроавтобуса</td>
                        <td><strong>від <?= $settings['price_jeep'] ?> грн</strong></td>
                    </tr>
                    <tr>    
                        <td>Перевезення спецтехніки (навантажувач, каток, трактор)</td>
                        <td><strong>від <?= $settings['price_spec'] ?> грн</strong></td>
                    </tr>
                    <tr>        
                        <td>Негабаритний вантаж (довгомір)</td>
                        <td><strong>Оговорюється при замовленні</strong></td>
                    </tr>
                    <tr>
                        <td>Складність завантаження (заблоковані/відірвані колеса, кювет)</td>
                        <td><strong>від <?= $settings['price_spec'] ?> грн</strong></td>
                    </tr>
                    <tr>
                        <td>Простій евакуатора з вини замовника</td>
                        <td><strong><?= $settings['price_downtime'] ?> грн/год</strong></td>
                    </tr>
                    <tr>
                        <td><strong>Евакуатор ціна за км</strong> (Виїзд за межі м. Харкова)</td>
                        <td><strong>від <?= $settings['price_km'] ?> грн/км + подача від <?= $settings['price_feed'] ?> грн</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<section class="mbr-section article content1 pt-4" id="cta-text">
    <div class="container">
        <div class="media-container-row">
            <div class="mbr-text col-12 mbr-fonts-style display-5 col-md-6 text-center">
                <font color="#232323"><strong>Бажаєте дізнатися точну вартість евакуації? <br>Зателефонуйте прямо зараз!</strong></font>
            </div>
        </div>
    </div>
</section>

<section class="mbr-section content8 pb-5" id="cta-btn">
    <div class="container">
        <div class="media-container-row title">
            <div class="col-12 col-md-8">
                <div class="mbr-section-btn align-center">
                    <a class="btn btn-success display-5" href="tel:<?= $settings['tel_one_link'] ?>">
                        <span class="mbri-touch mbr-iconfont mbr-iconfont-btn"></span>Викликати евакуатор
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="mbr-section content4 pt-5 cid-s1MA5wezxC" id="factors-title">
    <div class="container">
        <div class="media-container-row">
            <div class="title col-12 col-md-8">
                <h2 class="align-center pb-3 mbr-fonts-style display-2">Від чого залежить вартість виклику евакуатора?</h2>
            </div>
        </div>
    </div>
</section>

<section class="mbr-section article content11 pb-4" id="factors-list">
    <div class="container">
        <div class="media-container-row">
            <div class="mbr-text counter-container col-12 col-md-8 mbr-fonts-style display-7">
                <ul>
                    <li><strong>Тип та габарити транспорту:</strong> Евакуація легковика коштує дешевше, ніж перевезення джипа або спецтехніки.</li>
                    <li><strong>Складність навантаження:</strong> Наявність заблокованих коліс, несправного кермового управління або необхідність витягувати авто з кювету збільшує ціну.</li>
                    <li><strong>Відстань (ціна за км):</strong> При виїзді за межі Харкова (в область або по Україні) вартість розраховується за кожен кілометр пробігу.</li>
                    <li><strong>Додаткові послуги:</strong> Зміна маршруту під час евакуації або простій з вини клієнта.</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="mbr-section article content1 pb-5" id="seo-text-bottom">
    <div class="container">
        <div class="media-container-row">
            <div class="mbr-text col-12 mbr-fonts-style display-7 col-md-8">
                <p>Непередбачені ситуації на дорозі трапляються раптово: поломка двигуна, ДТП або складні погодні умови. Якщо ваше авто не може продовжувати рух самостійно, єдине правильне рішення — замовити надійний <strong>автоевакуатор</strong>.</p>
                <p>Служба "Евакуатор Харків" пропонує прозорі та доступні тарифи. Ми не приховуємо платежів: дізнатися, <strong>скільки коштує виклик евакуатора</strong>, ви можете одразу під час дзвінка. Наші диспетчери швидко розрахують маршрут, а сучасна техніка прибуде на місце протягом 20-30 хвилин. Ми гарантуємо безпечне перевезення вашого транспорту цілодобово, без вихідних та святкових днів.</p>
            </div>
        </div>
    </div>
</section>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>