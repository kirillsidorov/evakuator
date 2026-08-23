<?php
/**
 * components/404.php
 * Страница 404. Подключается из router.php ПОСЛЕ http_response_code(404).
 *
 * Использует общий header.php / footer.php, поэтому в router.php перед
 * подключением обязательно должен быть заполнен синтетический $page
 * (см. router.php, блок "РЕНДЕР 404").
 *
 * ВАЖНО: проверь, что header.php не выводит <link rel="canonical">
 * и hreflang, когда $page['type'] === '404'. Одна строка в header.php:
 *     if (($page['type'] ?? '') !== '404') { ... вывод canonical/hreflang ... }
 */

global $settings, $lang, $db;

$is_ua = ($lang === 'ua');
$pref  = $is_ua ? '/ua/' : '/';

$tel_view = $settings['tel_one_view'] ?? '';
$tel_link = $settings['tel_one_link'] ?? '';

$t = $is_ua ? [
    'code'    => '404',
    'title'   => 'Сторінку не знайдено',
    'lead'    => 'Такої адреси на сайті немає. Можливо, сторінку перейменували або ви перейшли за застарілим посиланням.',
    'urgent'  => 'Потрібен евакуатор просто зараз? Не шукайте по сайту — телефонуйте, диспетчер приймає заявки цілодобово.',
    'cta'     => 'Зателефонувати',
    'where'   => 'Куди піти далі',
] : [
    'code'    => '404',
    'title'   => 'Страница не найдена',
    'lead'    => 'Такого адреса на сайте нет. Возможно, страницу переименовали или вы перешли по устаревшей ссылке.',
    'urgent'  => 'Нужен эвакуатор прямо сейчас? Не ищите по сайту — звоните, диспетчер принимает заявки круглосуточно.',
    'cta'     => 'Позвонить',
    'where'   => 'Куда пойти дальше',
];

// Полезные ссылки. Слаги общие для обеих версий, отличается только префикс.
$links = $is_ua ? [
    ['',                              'Головна'],
    ['price',                         'Ціни'],
    ['evakuator-po-kharkovskoy-oblasti', 'Харківська область'],
    ['gruzovoy-evakuator-kharkov',    'Вантажний евакуатор'],
    ['evakuator-manipulator-kharkov', 'Маніпулятор'],
    ['evakuator-pri-dtp',             'Евакуація після ДТП'],
    ['blog',                          'Блог'],
    ['phone-number',                  'Контакти'],
] : [
    ['',                              'Главная'],
    ['price',                         'Цены'],
    ['evakuator-po-kharkovskoy-oblasti', 'Харьковская область'],
    ['gruzovoy-evakuator-kharkov',    'Грузовой эвакуатор'],
    ['evakuator-manipulator-kharkov', 'Манипулятор'],
    ['evakuator-pri-dtp',             'Эвакуация после ДТП'],
    ['blog',                          'Блог'],
    ['phone-number',                  'Контакты'],
];

require_smart('header.php');
?>

<section class="sec">
  <div class="sec-inner">

    <div class="e404-top">
      <div class="e404-code"><?= $t['code'] ?></div>
      <div class="e404-head">
        <h1 class="sec-title" style="margin-bottom:12px"><?= htmlspecialchars($t['title']) ?></h1>
        <div class="text-block"><?= htmlspecialchars($t['lead']) ?></div>
      </div>
    </div>

  </div>
</section>

<div class="band">
  <div class="band-inner">
    <div class="band-title"><?= htmlspecialchars($t['urgent']) ?></div>
    <a href="tel:<?= htmlspecialchars($tel_link) ?>" class="band-cta">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.55 11a19.79 19.79 0 01-3.07-8.67A2 2 0 012.44 0h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 7.91a16 16 0 006.18 6.18l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
      <?= htmlspecialchars($tel_view ?: $t['cta']) ?>
    </a>
  </div>
</div>

<section class="sec">
  <div class="sec-inner">
    <h2 class="sec-title sec-title--sm"><?= htmlspecialchars($t['where']) ?></h2>
    <div class="services-grid">
      <?php foreach ($links as $i => $l): ?>
        <a class="service-card" href="<?= $pref . $l[0] ?>">
          <span class="service-num"><?= $i + 1 ?></span>
          <?= htmlspecialchars($l[1]) ?>
          <span class="service-arrow" aria-hidden="true">›</span>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php
require_smart('footer.php');
