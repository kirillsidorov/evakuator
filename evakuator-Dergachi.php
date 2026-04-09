<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

$page_type = 'locations';

$title = "ᐈ Эвакуатор 《Дергачи》 — " . $settings['tel_one_view']  . "; " . $settings['tel_two_view'];
$description = "от " . $settings['price_car'] . " грн ⭐【Эвакуатор Дергачи】⏩ Телефон для заказа эвакуатора: ☎️ " . $settings['tel_one_view']  . ", " . $settings['tel_two_view'];

$custom_h1 = "ЭВАКУАТОР Дергачи";
$custom_p  = "Срочный вызов эвакуатора в течение 20-40 минут&nbsp;<br>от " . $settings['price_car'] . " грн";

//$custom_btn = ($lang == 'ua') ? "Записатися на діагностику" : "Записаться на диагностику";
$custom_bg  = "assets/images/header-1800x1200.webp"; // Путь к вашей картинке для СТО

include $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/breadcrumbs.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/h1_block.php';

include $_SERVER['DOCUMENT_ROOT'] . '/includes/3_steps_block.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/why_we_block.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/testimonials.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/contacts_block.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/maps.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php';
?>