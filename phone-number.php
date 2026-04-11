<?php
// phone-number.php — Страница контактов/телефонов (новый дизайн)
include_once $_SERVER['DOCUMENT_ROOT'] . '/db.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

$page_type = 'contacts';
$lang = (strpos($_SERVER['REQUEST_URI'], '/ua') === 0) ? 'ua' : 'ru';
$lp = ($lang == 'ua') ? '/ua/' : '/';

if ($lang == 'ua') {
    $title = "Номер евакуатора у Харкові — телефон евакуатора 24/7";
    $description = "Телефон евакуатора у Харкові працює цілодобово. Запишіть номер автососа: " . $settings['tel_one_view'] . ", " . $settings['tel_two_view'] . ". Швидкий виклик евакуатора по Харкову.";
    $breadcrumb_title = 'Номер евакуатора';
} else {
    $title = "Номер эвакуатора в Харькове — телефон эвакуатора 24/7";
    $description = "Телефон эвакуатора в Харькове работает круглосуточно. Запишите номер автососа: " . $settings['tel_one_view'] . ", " . $settings['tel_two_view'] . ". Быстрый вызов эвакуатора по Харькову.";
    $breadcrumb_title = 'Номер эвакуатора';
}

include $_SERVER['DOCUMENT_ROOT'] . '/components/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/components/breadcrumbs.php';
?>

<!-- ============================================================
     H1
     ============================================================ -->
<section class="sec">
  <div class="sec-inner">
    <h1 class="sec-title" style="font-size:clamp(32px,8vw,48px);">
      <?= ($lang == 'ua')
          ? 'Номер евакуатора у Харкові 24/7'
          : 'Номер эвакуатора в Харькове 24/7' ?>
    </h1>
    <div class="text-block" style="max-width:700px;">
      <p><?= ($lang == 'ua')
          ? 'Збережіть номер евакуатора, щоб швидко викликати допомогу на дорозі у будь-якій ситуації.'
          : 'Сохраните номер эвакуатора, чтобы быстро вызвать помощь на дороге в любой ситуации.' ?></p>
    </div>
  </div>
</section>

<!-- ============================================================
     H2: Номера телефонов
     ============================================================ -->
<section class="sec" style="background:#f8f8f6">
  <div class="sec-inner">
    <h2 class="sec-title"><?= ($lang == 'ua') ? 'Номери телефонів евакуатора' : 'Номера телефонов эвакуатора' ?></h2>

    <div class="why-grid" style="grid-template-columns:repeat(auto-fit,minmax(240px,1fr));">

      <div class="why-card" style="background:#fff;">
        <div class="why-icon">📞</div>
        <div>
          <div class="contact-label">МТС</div>
          <a href="tel:<?= $settings['tel_one_link'] ?>" style="font-family:'Oswald',sans-serif;font-size:24px;font-weight:700;color:#111;"><?= $settings['tel_one_view'] ?></a>
        </div>
      </div>

      <div class="why-card" style="background:#fff;">
        <div class="why-icon">📞</div>
        <div>
          <div class="contact-label">Київстар</div>
          <a href="tel:<?= $settings['tel_two_link'] ?>" style="font-family:'Oswald',sans-serif;font-size:24px;font-weight:700;color:#111;"><?= $settings['tel_two_view'] ?></a>
        </div>
      </div>

      <div class="why-card" style="background:#fff;">
        <div class="why-icon">✉️</div>
        <div>
          <div class="contact-label">E-mail</div>
          <a href="mailto:<?= $settings['email'] ?>" style="font-size:15px;font-weight:600;color:#111;"><?= $settings['email'] ?></a>
        </div>
      </div>

      <div class="why-card" style="background:#fff;">
        <div class="why-icon">📍</div>
        <div>
          <div class="contact-label"><?= ($lang == 'ua') ? 'Адреса' : 'Адрес' ?></div>
          <div style="font-size:14px;font-weight:500;color:#111;"><?= ($lang == 'ua') ? $settings['address_ua'] : $settings['address_ru'] ?></div>
        </div>
      </div>

    </div>

    <div style="margin-top:20px;text-align:center;">
      <a href="tel:<?= $settings['tel_one_link'] ?>" class="hero-cta" style="display:inline-flex;width:auto;">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:20px;height:20px;flex-shrink:0"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.55 11a19.79 19.79 0 01-3.07-8.67A2 2 0 012.44 0h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 7.91a16 16 0 006.18 6.18l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
        <?= ($lang == 'ua') ? 'Зателефонувати зараз' : 'Позвонить сейчас' ?>
      </a>
    </div>
  </div>
</section>

<!-- ============================================================
     H2: Когда нужен телефон эвакуатора
     ============================================================ -->
<section class="sec">
  <div class="sec-inner">
    <h2 class="sec-title"><?= ($lang == 'ua') ? 'Коли потрібен телефон евакуатора' : 'Когда нужен телефон эвакуатора' ?></h2>
    <ul class="num-list">
      <li><div class="num">1</div><span><?= ($lang == 'ua') ? 'Автомобіль не заводиться або зламався в дорозі' : 'Автомобиль не заводится или сломался в дороге' ?></span></li>
      <li><div class="num">2</div><span><?= ($lang == 'ua') ? 'Сталася ДТП — потрібна евакуація пошкодженого авто' : 'Произошло ДТП — нужна эвакуация повреждённого авто' ?></span></li>
      <li><div class="num">3</div><span><?= ($lang == 'ua') ? 'Заблоковані колеса або несправна трансмісія' : 'Заблокированы колёса или неисправна трансмиссия' ?></span></li>
      <li><div class="num">4</div><span><?= ($lang == 'ua') ? 'Машина застрягла у бруді, снігу або кюветі' : 'Машина застряла в грязи, снегу или кювете' ?></span></li>
      <li><div class="num">5</div><span><?= ($lang == 'ua') ? 'Потрібно перевезти авто на СТО, стоянку або в гараж' : 'Нужно перевезти авто на СТО, стоянку или в гараж' ?></span></li>
      <li><div class="num">6</div><span><?= ($lang == 'ua') ? 'Перевезення нового або придбаного авто без номерів' : 'Перевозка нового или купленного авто без номеров' ?></span></li>
    </ul>
  </div>
</section>

<!-- ============================================================
     H2: Почему стоит сохранить наш номер
     ============================================================ -->
<section class="sec" style="background:#f8f8f6">
  <div class="sec-inner">
    <h2 class="sec-title"><?= ($lang == 'ua') ? 'Чому варто зберегти наш номер' : 'Почему стоит сохранить наш номер' ?></h2>
    <div class="why-grid">
      <div class="why-card">
        <div class="why-icon">🕐</div>
        <div>
          <h3 class="why-title"><?= ($lang == 'ua') ? 'Цілодобова робота' : 'Круглосуточная работа' ?></h3>
          <p class="why-text"><?= ($lang == 'ua')
              ? 'Приймаємо дзвінки 24/7 без вихідних та святкових днів.'
              : 'Принимаем звонки 24/7 без выходных и праздничных дней.' ?></p>
        </div>
      </div>
      <div class="why-card">
        <div class="why-icon">⚡</div>
        <div>
          <h3 class="why-title"><?= ($lang == 'ua') ? 'Швидка подача' : 'Быстрая подача' ?></h3>
          <p class="why-text"><?= ($lang == 'ua')
              ? 'Евакуатор приїжджає по Харкову в середньому за 20–30 хвилин.'
              : 'Эвакуатор приезжает по Харькову в среднем за 20–30 минут.' ?></p>
        </div>
      </div>
      <div class="why-card">
        <div class="why-icon">📍</div>
        <div>
          <h3 class="why-title"><?= ($lang == 'ua') ? 'Місто та область' : 'Город и область' ?></h3>
          <p class="why-text"><?= ($lang == 'ua')
              ? 'Виїжджаємо в усі райони Харкова та по Харківській області.'
              : 'Выезжаем во все районы Харькова и по Харьковской области.' ?></p>
        </div>
      </div>
      <div class="why-card">
        <div class="why-icon">🚗</div>
        <div>
          <h3 class="why-title"><?= ($lang == 'ua') ? 'Перевозимо будь-які авто' : 'Перевозим любые авто' ?></h3>
          <p class="why-text"><?= ($lang == 'ua')
              ? 'Легкові машини, кросовери, мікроавтобуси, спецтехніку.'
              : 'Легковые машины, кроссоверы, микроавтобусы, спецтехнику.' ?></p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ============================================================
     H2: Как вызвать эвакуатор по телефону
     ============================================================ -->
<section class="sec">
  <div class="sec-inner">
    <h2 class="sec-title"><?= ($lang == 'ua') ? 'Як викликати евакуатор по телефону' : 'Как вызвать эвакуатор по телефону' ?></h2>
    <div class="steps">
      <div class="step">
        <div class="step-num">1</div>
        <div>
          <h3 class="step-title"><?= ($lang == 'ua') ? 'Зателефонуйте' : 'Позвоните' ?></h3>
          <p class="step-text"><?= ($lang == 'ua')
              ? 'Наберіть <strong>' . $settings['tel_one_view'] . '</strong> або <strong>' . $settings['tel_two_view'] . '</strong>'
              : 'Наберите <strong>' . $settings['tel_one_view'] . '</strong> или <strong>' . $settings['tel_two_view'] . '</strong>' ?></p>
        </div>
      </div>
      <div class="step">
        <div class="step-num">2</div>
        <div>
          <h3 class="step-title"><?= ($lang == 'ua') ? 'Повідомте деталі' : 'Сообщите детали' ?></h3>
          <p class="step-text"><?= ($lang == 'ua')
              ? 'Де знаходиться авто, марку та причину виклику.'
              : 'Где находится авто, марку и причину вызова.' ?></p>
        </div>
      </div>
      <div class="step">
        <div class="step-num">3</div>
        <div>
          <h3 class="step-title"><?= ($lang == 'ua') ? 'Отримайте вартість' : 'Получите стоимость' ?></h3>
          <p class="step-text"><?= ($lang == 'ua')
              ? 'Диспетчер назве орієнтовну ціну та час подачі евакуатора.'
              : 'Диспетчер назовёт ориентировочную цену и время подачи эвакуатора.' ?></p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ============================================================
     H2: FAQ
     ============================================================ -->
<?php
$faq_title = ($lang == 'ua') ? 'Часті питання' : 'Часто задаваемые вопросы';

if ($lang == 'ua') {
    $faq_items = [
        ['q' => 'Який номер евакуатора у Харкові працює цілодобово?', 'a' => 'Зателефонуйте за номером <strong>' . $settings['tel_one_view'] . '</strong> або <strong>' . $settings['tel_two_view'] . '</strong> — ми працюємо 24/7.'],
        ['q' => 'Скільки чекати евакуатор після дзвінка?', 'a' => 'Середній час подачі по Харкову становить 20–30 хвилин.'],
        ['q' => 'Чи можна викликати евакуатор вночі?', 'a' => 'Так, служба працює цілодобово без вихідних та святкових днів.'],
        ['q' => 'Чи виїжджаєте ви по Харківській області?', 'a' => 'Так, ми працюємо по Харкову, області та виконуємо міжміські перевезення по Україні.'],
        ['q' => 'Скільки коштує виклик евакуатора?', 'a' => 'Від ' . ($settings['price_car'] ?? '1000') . ' грн по місту. Точну вартість диспетчер розрахує по телефону.'],
    ];
} else {
    $faq_items = [
        ['q' => 'Какой номер эвакуатора в Харькове работает круглосуточно?', 'a' => 'Позвоните по номеру <strong>' . $settings['tel_one_view'] . '</strong> или <strong>' . $settings['tel_two_view'] . '</strong> — мы работаем 24/7.'],
        ['q' => 'Сколько ждать эвакуатор после звонка?', 'a' => 'Среднее время подачи по Харькову составляет 20–30 минут.'],
        ['q' => 'Можно ли вызвать эвакуатор ночью?', 'a' => 'Да, служба работает круглосуточно без выходных и праздничных дней.'],
        ['q' => 'Выезжаете ли вы по Харьковской области?', 'a' => 'Да, мы работаем по Харькову, области и выполняем междугородние перевозки по Украине.'],
        ['q' => 'Сколько стоит вызов эвакуатора?', 'a' => 'От ' . ($settings['price_car'] ?? '1000') . ' грн по городу. Точную стоимость диспетчер рассчитает по телефону.'],
    ];
}

include $_SERVER['DOCUMENT_ROOT'] . '/components/faq_block.php';
?>

<!-- ============================================================
     CTA: Запишите наш номер
     ============================================================ -->
<div class="band">
  <div class="band-inner">
    <div class="band-title"><?= ($lang == 'ua') ? 'Запишіть наш номер евакуатора' : 'Запишите наш номер эвакуатора' ?></div>
    <div class="band-sub" style="font-size:18px;color:rgba(255,255,255,.7);margin-bottom:8px;">
      <strong><?= $settings['tel_one_view'] ?></strong> · <strong><?= $settings['tel_two_view'] ?></strong>
    </div>
    <div class="band-sub"><?= ($lang == 'ua')
        ? 'Працюємо по всьому Харкову та області · 24/7 без вихідних'
        : 'Работаем по всему Харькову и области · 24/7 без выходных' ?></div>
    <a href="tel:<?= $settings['tel_one_link'] ?>" class="band-cta">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.55 11a19.79 19.79 0 01-3.07-8.67A2 2 0 012.44 0h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 7.91a16 16 0 006.18 6.18l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
      <?= ($lang == 'ua') ? 'Зателефонувати зараз' : 'Позвонить сейчас' ?>
    </a>
  </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/components/footer.php'; ?>
