<?php
/**
 * Універсальний блок контактів
 * Працює на базі даних з config.php ($settings, $lang, $loc, $page_type)
 */

// Визначаємо типи сторінок 
// all_articles
// service
// articles
// contacts
// galery
// hub
// locations
// main
// prices

$show_address_types = ['main', 'service', 'contacts','locations'];

$is_full_address = in_array($page_type, $show_address_types);
?>
<section class="mbr-section contacts2">
    <div class="container">
        <div class="row">
            <div class="title col-12">
                <h2 class="align-center mbr-fonts-style display-2">
                    <?= ($lang == 'ua' ? "Телефон евакуатора " : "Телефон эвакуатора ") . $loc['in_city'] ?>
                </h2>
            </div>
            <div class="col-12">
                <div class="row justify-content-center">
                    
                    <div class="col-12 col-md-4">
                        <div class="b">
                            <?php if ($is_full_address): ?>
                                <h5 class="align-left mbr-fonts-style m-0 display-5">
                                    <?= ($lang == 'ua' ? "Наша адреса:" : "Наш адрес:") ?>
                                </h5>
                                <p class="mbr-text align-left mbr-fonts-style display-5">
                                    <?= $settings['address_' . $lang] ?? '' ?>
                                </p>
                            <?php else: ?>
                                <h5 class="align-left mbr-fonts-style m-0 display-5">
                                    <?= ($lang == 'ua' ? "Напишіть нам:" : "Напишите нам:") ?>
                                </h5>
                                <p class="mbr-text align-left mbr-fonts-style display-5">
                                    <a href="viber://chat?number=<?= $settings['tel_one_link'] ?>" style="color: #7360f2;">
                                        <strong>Viber</strong>
                                    </a><br>
                                    <a href="https://t.me/<?= $settings['tel_one_link'] ?>" style="color: #0088cc;">
                                        <strong>Telegram</strong>
                                    </a>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="b">
                            <h5 class="align-left mbr-fonts-style m-0 display-5">
                                <?= ($lang == 'ua' ? "Цілодобово:" : "Круглосуточно:") ?>
                            </h5>
                            <p class="mbr-text align-left mbr-fonts-style display-5">
                                <strong>
                                    <a href="tel:<?= $settings['tel_one_link'] ?>" class="text-black"><?= $settings['tel_one_view']  ?></a><br>
                                    <a href="tel:<?= $settings['tel_two_link'] ?>" class="text-black"><?= $settings['tel_two_view'] ?></a>
                                </strong>
                            </p>
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="b">
                            <h5 class="align-left mbr-fonts-style m-0 display-5">E-mail:</h5>
                            <p class="mbr-text align-left mbr-fonts-style display-5">
                                <?= $settings['email'] ?>
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

<section class="mbr-section article content1 cid-s3Yp3GvQ4E">
    <div class="container">
        <div class="media-container-row">
            <div class="mbr-text col-12 mbr-fonts-style display-5 col-md-6">
                <p>
                    <font color="#232323"><strong>
                        <?= ($lang == 'ua' ? "Потрібен евакуатор " : "Нужен эвакуатор ") . $loc['in_city'] ?>?<br>
                    </strong></font>
                    <strong><?= ($lang == 'ua' ? "Зателефонуйте нам!" : "Позвоните нам!") ?></strong>
                </p>
            </div>
        </div>
    </div>
</section>

<section class="mbr-section content8 cid-s3YpLHwk3H">
    <div class="container">
        <div class="media-container-row title">
            <div class="col-12 col-md-8">
                <div class="mbr-section-btn align-center">
                    <a class="btn btn-success display-5" href="tel:<?= $settings['tel_one_link'] ?>">
                        <span class="mbri-touch mbr-iconfont mbr-iconfont-btn"></span>
                        <?= ($lang == 'ua' ? "Викликати евакуатор" : "Вызвать эвакуатор") ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>