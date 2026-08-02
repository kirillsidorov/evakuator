<?php
// avtopark-evakuatorov.php — Автопарк эвакуаторов (новый дизайн)
include_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

$page_type = "galery";

$is_ua = ($lang == 'ua');

$title = $is_ua
    ? "Автопарк евакуаторів Харків — фото спецтехніки та видів платформ"
    : "Автопарк эвакуаторов Харьков — фото спецтехники и видов платформ";
$description = $is_ua
    ? "Фото нашого автопарку евакуаторів у Харкові. У наявності: маніпулятори, сдвижні платформи. Реальні фото роботи 24/7."
    : "Фото нашего автопарка эвакуаторов в Харькове. В наличии: манипуляторы, сдвижные платформы. Реальные фото работы 24/7.";
$breadcrumb_title = $is_ua ? 'Автопарк евакуаторів' : 'Автопарк эвакуаторов';
$h1_title = $is_ua ? 'Автопарк евакуаторів у Харкові' : 'Автопарк эвакуаторов в Харькове';

$platforms = $is_ua ? [
    [
        'img' => '/assets/images/ford-focus-3-463x204.webp',
        'alt' => 'Евакуатор зі стаціонарною платформою Харків',
        'title' => 'Стаціонарна платформа',
        'text' => 'Евакуйований автомобіль заїжджає на платформу по висувних аппарелях, використовується електролебідка.'
    ],
    [
        'img' => '/assets/images/ford-focus-32-472x200.webp',
        'alt' => 'Евакуатор зі зсувною платформою',
        'title' => 'Підйомно-зсувна платформа',
        'text' => 'Для евакуації авто при аварії, поломках або неправильному паркуванні. Зсувна платформа з навантаженим на неї автомобілем плавно повертається у вихідне положення.'
    ],
    [
        'img' => '/assets/images/evacuator-676x327.webp',
        'alt' => 'Послуги маніпулятора Харків',
        'title' => 'Евакуатор Маніпулятор',
        'text' => 'Стріла крана висувається на кілька метрів, щоб забезпечити процес навантаження/розвантаження транспортного засобу на відстані від евакуатора.'
    ],
] : [
    [
        'img' => '/assets/images/ford-focus-3-463x204.webp',
        'alt' => 'Эвакуатор со стационарной платформой Харьков',
        'title' => 'Стационарная платформа',
        'text' => 'Эвакуируемый автомобиль заезжает на платформу по выдвижным аппарелям, используется электролебедка.'
    ],
    [
        'img' => '/assets/images/ford-focus-32-472x200.webp',
        'alt' => 'Эвакуатор со сдвижной платформой',
        'title' => 'Подъёмно-сдвижная платформа',
        'text' => 'Для эвакуации авто при аварии, поломках или неправильной парковке. Сдвижная платформа с погруженным на неё автомобилем плавно возвращается в исходное положение.'
    ],
    [
        'img' => '/assets/images/evacuator-676x327.webp',
        'alt' => 'Услуги манипулятора Харьков',
        'title' => 'Эвакуатор Манипулятор',
        'text' => 'Стрела крана выдвигается на несколько метров, чтобы обеспечить процесс погрузки/разгрузки транспортного средства на расстоянии от эвакуатора.'
    ],
];

$gallery = [
    ['img' => '/assets/images/4-1369x1200-800x701.webp', 'full' => '/assets/images/4-1369x1200.webp', 'alt_ru' => 'Погрузка джипа на эвакуатор Харьков', 'alt_ua' => 'Завантаження джипа на евакуатор Харків'],
    ['img' => '/assets/images/8-1411x1200-800x681.webp', 'full' => '/assets/images/8-1411x1200.webp', 'alt_ru' => 'Перевозка спецтехники эвакуатором', 'alt_ua' => 'Перевезення спецтехніки евакуатором'],
    ['img' => '/assets/images/6-1130x828-800x587.webp', 'full' => '/assets/images/6-1130x828.webp', 'alt_ru' => 'Эвакуация авто после ДТП Харьков', 'alt_ua' => 'Евакуація авто після ДТП Харків'],
    ['img' => '/assets/images/3-1328x1127-800x679.webp', 'full' => '/assets/images/3-1328x1127.webp', 'alt_ru' => 'Вызвать эвакуатор дешево фото', 'alt_ua' => 'Викликати евакуатор недорого фото'],
    ['img' => '/assets/images/5-1147x828-800x577.webp', 'full' => '/assets/images/5-1147x828.webp', 'alt_ru' => 'Транспортировка автомобиля по области', 'alt_ua' => 'Транспортування автомобіля по області'],
    ['img' => '/assets/images/1-1600x1200-800x601.webp', 'full' => '/assets/images/1-1600x1200.webp', 'alt_ru' => 'Служба эвакуации Харьков автопарк', 'alt_ua' => 'Служба евакуації Харків автопарк'],
    ['img' => '/assets/images/7-1349x828-800x491.webp', 'full' => '/assets/images/7-1349x828.webp', 'alt_ru' => 'Безопасная погрузка на платформу', 'alt_ua' => 'Безпечне навантаження на платформу'],
    ['img' => '/assets/images/10-1034x678-800x525.webp', 'full' => '/assets/images/10-1034x678.webp', 'alt_ru' => 'Эвакуатор для микроавтобусов', 'alt_ua' => 'Евакуатор для мікроавтобусів'],
    ['img' => '/assets/images/11-960x720-800x600.webp', 'full' => '/assets/images/11-960x720.webp', 'alt_ru' => 'Работа эвакуатора ночью', 'alt_ua' => 'Робота евакуатора вночі'],
    ['img' => '/assets/images/12-960x720-800x600.webp', 'full' => '/assets/images/12-960x720.webp', 'alt_ru' => 'Эвакуация из кювета', 'alt_ua' => 'Евакуація з кювету'],
    ['img' => '/assets/images/2-1000x500-800x400.webp', 'full' => '/assets/images/2-1000x500.webp', 'alt_ru' => 'Наш эвакуатор на вызове', 'alt_ua' => 'Наш евакуатор на виклику'],
];

include $_SERVER['DOCUMENT_ROOT'] . '/components/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/components/breadcrumbs.php';
?>

<section class="sec" style="padding-bottom:10px;">
    <div class="sec-inner">
        <h1 class="sec-title" style="margin-bottom:10px;font-size:clamp(32px,8vw,48px);"><?= $h1_title ?></h1>
    </div>
</section>

<section class="sec">
    <div class="sec-inner">
        <div class="fleet-grid">
            <?php foreach ($platforms as $p): ?>
            <div class="fleet-card">
                <img src="<?= htmlspecialchars($p['img']) ?>" alt="<?= htmlspecialchars($p['alt']) ?>" loading="lazy">
                <div class="fleet-card-body">
                    <div class="fleet-card-title"><?= htmlspecialchars($p['title']) ?></div>
                    <p class="fleet-card-text"><?= htmlspecialchars($p['text']) ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="sec" style="background:#f8f8f6">
    <div class="sec-inner">
        <h2 class="sec-title"><?= $is_ua ? 'Евакуатор Харків — фото роботи' : 'Эвакуатор Харьков — фото работы' ?></h2>
        <div class="fleet-gallery">
            <?php foreach ($gallery as $i => $g):
                $alt = $is_ua ? $g['alt_ua'] : $g['alt_ru'];
            ?>
            <div class="fleet-gallery-item" data-lightbox-index="<?= $i ?>">
                <img src="<?= htmlspecialchars($g['img']) ?>" alt="<?= htmlspecialchars($alt) ?>" loading="lazy">
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Лайтбокс (свой, без jQuery/Bootstrap) -->
<div class="fleet-lightbox" id="fleetLightbox">
    <button class="fleet-lightbox-close" id="fleetLightboxClose" aria-label="<?= $is_ua ? 'Закрити' : 'Закрыть' ?>">&times;</button>
    <button class="fleet-lightbox-nav fleet-lightbox-prev" id="fleetLightboxPrev" aria-label="Prev">&#8249;</button>
    <img class="fleet-lightbox-img" id="fleetLightboxImg" src="" alt="">
    <button class="fleet-lightbox-nav fleet-lightbox-next" id="fleetLightboxNext" aria-label="Next">&#8250;</button>
</div>

<section class="sec">
    <div class="sec-inner">
        <div class="video-wrap">
            <iframe src="https://www.youtube.com/embed/GMXbSS3td6k?rel=0" width="100%" height="480" frameborder="0" allowfullscreen loading="lazy" style="border-radius:12px;"></iframe>
        </div>
    </div>
</section>

<style>
.fleet-grid{display:grid;grid-template-columns:1fr;gap:20px}
@media(min-width:768px){.fleet-grid{grid-template-columns:repeat(3,1fr)}}
.fleet-card{background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 4px 16px rgba(0,0,0,.06)}
.fleet-card img{width:100%;height:180px;object-fit:cover}
.fleet-card-body{padding:20px}
.fleet-card-title{font-family:'Oswald',sans-serif;font-weight:700;font-size:18px;color:#111;margin-bottom:8px}
.fleet-card-text{font-size:14px;color:#555;line-height:1.5}

.fleet-gallery{display:grid;grid-template-columns:repeat(2,1fr);gap:12px}
@media(min-width:600px){.fleet-gallery{grid-template-columns:repeat(3,1fr)}}
@media(min-width:900px){.fleet-gallery{grid-template-columns:repeat(4,1fr)}}
.fleet-gallery-item{border-radius:10px;overflow:hidden;cursor:pointer;aspect-ratio:4/3;position:relative}
.fleet-gallery-item img{width:100%;height:100%;object-fit:cover;transition:transform .3s}
.fleet-gallery-item:hover img{transform:scale(1.06)}

.fleet-lightbox{position:fixed;inset:0;background:rgba(0,0,0,.92);z-index:300;display:none;align-items:center;justify-content:center}
.fleet-lightbox.is-open{display:flex}
.fleet-lightbox-img{max-width:90vw;max-height:85vh;border-radius:8px}
.fleet-lightbox-close{position:absolute;top:20px;right:24px;background:none;border:none;color:#fff;font-size:36px;cursor:pointer;line-height:1}
.fleet-lightbox-nav{position:absolute;top:50%;transform:translateY(-50%);background:rgba(255,255,255,.1);border:none;color:#fff;font-size:32px;width:48px;height:48px;border-radius:50%;cursor:pointer}
.fleet-lightbox-prev{left:16px}
.fleet-lightbox-next{right:16px}
.video-wrap{max-width:960px;margin:0 auto}
</style>

<script>
(function(){
    var images = <?= json_encode(array_map(function($g) use ($is_ua) { return ['full' => $g['full'], 'alt' => $is_ua ? $g['alt_ua'] : $g['alt_ru']]; }, $gallery)) ?>;
    var current = 0;
    var lightbox = document.getElementById('fleetLightbox');
    var img = document.getElementById('fleetLightboxImg');

    function open(index) {
        current = index;
        img.src = images[current].full;
        img.alt = images[current].alt;
        lightbox.classList.add('is-open');
    }
    function close() { lightbox.classList.remove('is-open'); }
    function next() { open((current + 1) % images.length); }
    function prev() { open((current - 1 + images.length) % images.length); }

    document.querySelectorAll('.fleet-gallery-item').forEach(function(el) {
        el.addEventListener('click', function() {
            open(parseInt(el.getAttribute('data-lightbox-index'), 10));
        });
    });

    document.getElementById('fleetLightboxClose').addEventListener('click', close);
    document.getElementById('fleetLightboxNext').addEventListener('click', next);
    document.getElementById('fleetLightboxPrev').addEventListener('click', prev);
    lightbox.addEventListener('click', function(e) { if (e.target === lightbox) close(); });
    document.addEventListener('keydown', function(e) {
        if (!lightbox.classList.contains('is-open')) return;
        if (e.key === 'Escape') close();
        if (e.key === 'ArrowRight') next();
        if (e.key === 'ArrowLeft') prev();
    });
})();
</script>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/components/footer.php'; ?>
