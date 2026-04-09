<?php
// Подключаем базу и конфиг
include_once $_SERVER['DOCUMENT_ROOT'] . '/db.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

$page_type = 'main';

// Определяем язык (если страница вызвана по адресу /ua/...)
$lang = (strpos($_SERVER['REQUEST_URI'], '/ua') === 0) ? 'ua' : 'ru';

// Мета-теги для главной
if ($lang == 'ua') {
    $title = "ᐈ Евакуатор Харків — Замовлення в 1 клік — Цілодобові Послуги Автососа";
    $description = "🚍 Евакуатор Харків — 💰 Тариф від " . ($settings['price_car'] ?? '1000') . " грн ☎️ Телефонуйте: " . $settings['tel_one_view'] . " ⚡ Терміновий Виклик Автососа в Харкові.";
} else {
    $title = "ᐈ Эвакуатор Харьков — Заказ в 1 клик — Круглосуточные Услуги Автососа";
    $description = "🚍 Эвакуатор Харьков — 💰 Тариф от " . ($settings['price_car'] ?? '1000') . " грн ☎️ Звоните: " . $settings['tel_one_view'] . " ⚡ Срочный Вызов Автососа в Харькове.";
}

// Подключаем шапку (в ней уже есть menu.php и CSS)
include $_SERVER['DOCUMENT_ROOT'] . '/components/header.php';
?>

<section class="hero">
  <div class="hero-overlay"></div>
  <div class="hero-body">
    <div class="hero-badge"><div class="hero-badge-dot"></div><?= ($lang == 'ua') ? 'Працюємо 24/7' : 'Работаем 24/7' ?></div>
    <h1><?= ($lang == 'ua') ? 'Евакуатор<br>Харків' : 'Эвакуатор<br>Харьков' ?></h1>
    <p class="hero-sub"><?= ($lang == 'ua') ? 'Терміновий виклик протягом 20–40 хвилин' : 'Срочный вызов в течение 20–40 минут' ?></p>
    <div class="hero-price">
      <span class="hero-price-from"><?= ($lang == 'ua') ? 'від' : 'от' ?></span>
      <span class="hero-price-num"><?= $settings['price_car'] ?? '1000' ?></span>
      <span class="hero-price-unit">грн</span>
    </div>
    <a href="tel:<?= $settings['tel_one_link'] ?>" class="hero-cta">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.55 11a19.79 19.79 0 01-3.07-8.67A2 2 0 012.44 0h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 7.91a16 16 0 006.18 6.18l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
      <?= ($lang == 'ua') ? 'Викликати евакуатор' : 'Вызвать эвакуатор' ?>
    </a>
  </div>
</section>

<?php
// === МАГИЯ СКОРОСТИ ===
// Отправляем первый экран в браузер пользователя прямо сейчас, 
// не дожидаясь генерации остальной части страницы.
if (ob_get_level() > 0) { ob_flush(); }
flush();
?>

<style>
  /* Класс для отложенного рендеринга нижних блоков браузером */
  .defer-render { content-visibility: auto; contain-intrinsic-size: 600px; }
</style>

<div class="facts defer-render">
  <div class="fact">
    <div class="fact-num">20<em><?= ($lang == 'ua') ? 'хв' : 'мин' ?></em></div>
    <div class="fact-label"><?= ($lang == 'ua') ? 'Середній час подачі' : 'Среднее время подачи' ?></div>
  </div>
  <div class="fact">
    <div class="fact-num">24<em>/7</em></div>
    <div class="fact-label"><?= ($lang == 'ua') ? 'Без перерв та вихідних' : 'Без перерывов и выходных' ?></div>
  </div>
  <div class="fact">
    <div class="fact-num">10<em>+</em></div>
    <div class="fact-label"><?= ($lang == 'ua') ? 'Років на ринку' : 'Лет на рынке' ?></div>
  </div>
  <div class="fact">
    <div class="fact-num">-20<em>%</em></div>
    <div class="fact-label"><?= ($lang == 'ua') ? 'Нижче середніх цін' : 'Ниже средних цен' ?></div>
  </div>
</div>

<section class="sec defer-render">
  <div class="sec-inner">
    <div class="sec-label"><?= ($lang == 'ua') ? 'Послуги' : 'Услуги' ?></div>
    <div class="sec-title"><?= ($lang == 'ua') ? 'Замовити евакуатор: швидко та недорого' : 'Заказать автоэвакуатор: быстро и недорого' ?></div>
    <ul class="num-list">
      <li><div class="num">1</div><span><?= ($lang == 'ua') ? 'Евакуатор Харків' : 'Эвакуатор Харьков' ?> (МТС): <strong><?= $settings['tel_one_view'] ?></strong></span></li>
      <li><div class="num">2</div><span><?= ($lang == 'ua') ? 'Евакуатор Харків' : 'Эвакуатор Харьков' ?> (Київстар): <strong><?= $settings['tel_two_view'] ?></strong></span></li>
      <li><div class="num">3</div><span><?= ($lang == 'ua') ? 'Швидка подача по Харкову, області та Україні' : 'Быстрая подача по Харькову, Харьковской области и Украине' ?></span></li>
      <li><div class="num">4</div><span><?= ($lang == 'ua') ? 'Легкові авто, позашляховики, мікроавтобуси, спецтехніка' : 'Легковые авто, внедорожники, микроавтобусы, спецтехника' ?></span></li>
      <li><div class="num">5</div><span><?= ($lang == 'ua') ? 'Режим роботи 24/7 без вихідних' : 'Режим работы 24/7 без выходных и праздников' ?></span></li>
      <li><div class="num">6</div><span><?= ($lang == 'ua') ? 'Обслуговування та ремонт авто на власному СТО' : 'Обслуживание и ремонт автомобилей на собственном СТО' ?></span></li>
    </ul>
  </div>
</section>

<section class="sec defer-render" style="background:#f8f8f6">
  <div class="sec-inner">
    <div class="sec-label"><?= ($lang == 'ua') ? 'Наш сервіс' : 'Наш сервис' ?></div>
    <div class="sec-title"><?= ($lang == 'ua') ? 'Оперативна подача спецтехніки' : 'Оперативная подача спецтехники' ?></div>
    
    <div class="text-cols" style="align-items: center; gap: 40px;">
      <div class="text-block">
        <?php if ($lang == 'ua'): ?>
            <p>Автомобіль давно не розкіш, а надійний і зручний засіб пересування. Але навіть найсучасніший автомобіль іноді підводить, трапляються поломки та аварії. За законом підлості він ламається в найбільш невідповідний час. Єдиний вихід із подібної ситуації – викликати автосос.</p>
            <p><strong>Евакуатор Харків</strong> прийде на допомогу в будь-який час дня і ночі, в будні, вихідні та святкові дні. Центр автоевакуації спеціалізується на наданні допомоги харківським автолюбителям і гостям нашого міста. Ми доставимо вашого залізного коня на найближчу автостоянку, СТО або в гараж.</p>
        <?php else: ?>
            <p>Автомобиль давно не роскошь, а надёжное и удобное средство передвижения. Но даже самый надёжный и современный автомобиль иногда подводит, случаются поломки и аварии. По закону подлости он ломается в самое неподходящее время. Единственный выход из подобной ситуации – вызвать автосос.</p>
            <p><strong>Эвакуатор Харьков</strong> придёт на помощь в любое время дня и ночи, в будни, выходные и праздничные дни. Центр автоэвакуации специализируется на оказании помощи харьковским автолюбителям и гостям нашего города. Мы доставим вашего железного коня на ближайшую автостоянку, СТО или в гараж.</p>
        <?php endif; ?>
      </div>
      <div>
        <img src="/assets/images/2-650x325.webp" alt="Эвакуатор Харьков в работе" loading="lazy" style="border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.08);">
      </div>
    </div>
  </div>
</section>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/components/3_steps_block.php'; ?>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/components/why_we_block.php'; ?>

<section class="sec defer-render">
  <div class="sec-inner">
    <div class="sec-label"><?= ($lang == 'ua') ? 'Співпраця' : 'Сотрудничество' ?></div>
    <div class="sec-title"><?= ($lang == 'ua') ? 'Порядок термінового виклику' : 'Порядок срочного вызова эвакуатора' ?></div>
    <div class="text-cols">
      <div class="text-block">
        <?php if ($lang == 'ua'): ?>
            <p>Ми працюємо з приватними та корпоративними клієнтами, евакуацію аварійних авто нам довіряють організації різних форм власності, ми співпрацюємо з автосервісами та страховими компаніями. Довгострокове співробітництво зі службою евакуації дозволяє суттєво знизити ціну на послуги автососа.</p>
        <?php else: ?>
            <p>Мы работаем с частными и корпоративными клиентами, эвакуацию аварийных авто нам доверяют организации разных форм собственности, мы сотрудничаем с автосервисами и страховыми компаниями. Долгосрочное сотрудничество со службой эвакуации позволяет существенно снизить цену на услуги автососа.</p>
        <?php endif; ?>
      </div>
      <div class="text-block">
        <?php if ($lang == 'ua'): ?>
            <p>Працювати з нашою компанією дуже просто: опинившись на дорозі з несправним автомобілем, просто зателефонуйте нам і зачекайте кілька хвилин. До вас прибуде наш співробітник, який усуне несправність на місці або безпечно транспортує автомобіль туди, куди забажаєте.</p>
        <?php else: ?>
            <p>Работать с нашей компанией очень просто: оказавшись на дороге с неисправным автомобилем, просто позвоните нам и подождите несколько минут. К вам прибудет наш сотрудник, который устранит неисправность на месте или безопасно транспортирует автомобиль туда, куда пожелаете.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<section class="sec defer-render" style="background:#f8f8f6">
  <div class="sec-inner">
    <div class="sec-label"><?= ($lang == 'ua') ? 'Напрямки' : 'Направления' ?></div>
    <div class="sec-title"><?= ($lang == 'ua') ? 'Що пропонує наша служба' : 'Что предлагает наша служба' ?></div>
    <div class="services-grid">
      <a href="<?= ($lang == 'ua' ? '/ua' : '') ?>/gruzovoy-evakuator-kharkov" class="service-card"><div class="service-num">01</div><?= ($lang == 'ua') ? 'Вантажний евакуатор' : 'Грузовой эвакуатор' ?><span class="service-arrow">›</span></a>
      <a href="<?= ($lang == 'ua' ? '/ua' : '') ?>/evakuator-manipulator-kharkov" class="service-card"><div class="service-num">02</div><?= ($lang == 'ua') ? 'Евакуатор-Маніпулятор' : 'Эвакуатор-Манипулятор' ?><span class="service-arrow">›</span></a>
      <a href="<?= ($lang == 'ua' ? '/ua' : '') ?>/Perevozka-spetstekhniki-Kharkov" class="service-card"><div class="service-num">03</div><?= ($lang == 'ua') ? 'Перевезення спецтехніки' : 'Перевозка спецтехники' ?><span class="service-arrow">›</span></a>
      <a href="<?= ($lang == 'ua' ? '/ua' : '') ?>/avtosos" class="service-card"><div class="service-num">04</div>Автосос<span class="service-arrow">›</span></a>
      <a href="<?= ($lang == 'ua' ? '/ua' : '') ?>/sto-kharkov" class="service-card"><div class="service-num">05</div><?= ($lang == 'ua') ? 'Послуги СТО' : 'Услуги СТО' ?><span class="service-arrow">›</span></a>
      <a href="<?= ($lang == 'ua' ? '/ua' : '') ?>/avtovykup-kharkov" class="service-card"><div class="service-num">06</div><?= ($lang == 'ua') ? 'Автовикуп' : 'Автовыкуп' ?><span class="service-arrow">›</span></a>
    </div>
  </div>
</section>

<section class="sec defer-render">
  <div class="sec-inner">
    <div class="sec-label"><?= ($lang == 'ua') ? 'Відгуки' : 'Отзывы' ?></div>
    <div class="sec-title"><?= ($lang == 'ua') ? 'Клієнти про нас' : 'Клиенты о нас' ?></div>
    <div class="reviews-grid">
      <div class="review">
        <div class="review-header"><div class="review-avatar">ЮЛ</div><div><div class="review-name">Юлия Лазурко</div><div class="review-stars">★★★★★</div></div></div>
        <p class="review-text">Очень классно когда цены минимальные, а качество работы на высшем уровне. Приехали быстро, всё сделали аккуратно!</p>
      </div>
      <div class="review">
        <div class="review-header"><div class="review-avatar">МД</div><div><div class="review-name">Михаил Демидас</div><div class="review-stars">★★★★★</div></div></div>
        <p class="review-text">Цены ну уж очень лёгкие! Помогли перевезти авто быстро и без проблем. Рекомендую всем!</p>
      </div>
      <div class="review">
        <div class="review-header"><div class="review-avatar">АВ</div><div><div class="review-name">Nickolai Bogdan</div><div class="review-stars">★★★★★</div></div></div>
        <p class="review-text">Спасибо что быстро сработали и приехали вовремя, как и обещали. Настоящие профессионалы!</p>
      </div>
    </div>
  </div>
</section>

<?php
$faq_title = ($lang == 'ua') ? 'Часті питання' : 'Часто задаваемые вопросы';

if ($lang == 'ua') {
    $faq_items = [
        ['q' => 'Скільки коштує виклик евакуатора?', 'a' => 'Вартість по місту починається від ' . ($settings['price_car'] ?? '1000') . ' грн. Тариф фіксований і включає подачу, навантаження та розвантаження.'],
        ['q' => 'Чи потрібні документи для перевезення?', 'a' => 'Так, достатньо мати при собі посвідчення водія та техпаспорт. Якщо ви не власник — необхідна довіреність.'],
        ['q' => 'Як швидко приїде евакуатор?', 'a' => 'Наші машини чергують у різних районах. Середній час прибуття — 20–40 хвилин.']
    ];
} else {
    $faq_items = [
        ['q' => 'Сколько стоит вызов эвакуатора по Харькову?', 'a' => 'Стоимость эвакуации по городу начинается от ' . ($settings['price_car'] ?? '1000') . ' грн. Тариф фиксированный и включает подачу, погрузку и разгрузку.'],
        ['q' => 'Нужны ли документы для вызова эвакуатора?', 'a' => 'Да, достаточно удостоверения личности и техпаспорта. Если вы не владелец автомобиля — необходима доверенность.'],
        ['q' => 'Как быстро приедет эвакуатор?', 'a' => 'Наши машины дежурят в разных районах Харькова. Среднее время прибытия — 20–40 минут.']
    ];
}

echo '<div class="defer-render">';
include $_SERVER['DOCUMENT_ROOT'] . '/components/faq_block.php';
echo '</div>';
?>

<div class="defer-render">
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/components/contacts_block.php'; ?>
</div>

<div class="band defer-render">
  <div class="band-inner">
    <div class="band-title"><?= ($lang == 'ua') ? 'Потрібен евакуатор у Харкові?' : 'Нужен эвакуатор в Харькове?' ?></div>
    <div class="band-sub"><?= ($lang == 'ua') ? 'Зателефонуйте нам — приїдемо за 20–40 хвилин' : 'Позвоните нам — приедем за 20–40 минут' ?></div>
    <a href="tel:<?= $settings['tel_one_link'] ?>" class="band-cta">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.55 11a19.79 19.79 0 01-3.07-8.67A2 2 0 012.44 0h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 7.91a16 16 0 006.18 6.18l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
      <?= ($lang == 'ua') ? 'Викликати евакуатор' : 'Вызвать эвакуатор' ?>
    </a>
  </div>
</div>

<?php
// Подключаем подвал (в нем уже есть скрипты для меню)
include $_SERVER['DOCUMENT_ROOT'] . '/components/footer.php'; 
?>