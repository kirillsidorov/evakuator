<?php
/**
 * Обновленный универсальный H1 блок
 */

// Если переменная есть и она НЕ пустая — берем её. Иначе — дефолт.
$current_h1 = !empty($custom_h1) ? $custom_h1 : (($lang == 'ua') ? "Евакуатор " . $loc['name'] : "Эвакуатор " . $loc['name']);

$current_p  = !empty($custom_p)  ? $custom_p  : (($lang == 'ua') ? "Терміновий виклик..." : "Срочный вызов...");

$current_btn = !empty($custom_btn) ? $custom_btn : (($lang == 'ua') ? "Викликати евакуатор" : "Вызвать эвакуатор");

$bg_style = !empty($custom_bg) ? "style='background-image: url($custom_bg);'" : "";
?>

<section class="cid-s29np8LF2n mbr-fullscreen location_h1_block mbr-parallax-background" <?= $bg_style ?>>
    <div class="mbr-overlay" style="opacity: 0.5; background-color: rgb(0, 0, 0);"></div>
    
    <div class="container align-center">
        <div class="row justify-content-md-center">
            <div class="mbr-white col-md-10">
                
                <h1 class="mbr-section-title mbr-bold pb-3 mbr-fonts-style display-1">
                    <?= $current_h1 ?>
                </h1>

                <p class="mbr-text pb-3 mbr-fonts-style display-5">
                    <?= $current_p ?>
                </p>

                <div class="mbr-section-btn">
                    <a class="btn btn-md btn-success display-5" href="tel:<?= $settings['tel_one_link'] ?>">
                        <span class="mbri-touch mbr-iconfont mbr-iconfont-btn"></span>
                        <?= $current_btn ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>