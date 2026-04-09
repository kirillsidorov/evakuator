<?php
// includes/block_highlight.php
// Блок с параллаксом внутри контента

// Получаем данные из переменной, которую передаст функция
// Ожидаем $content['title'], $content['text'], $content['image']

$bg_image = !empty($content['image']) ? $content['image'] : '/assets/images/header-1800x1200.webp'; // Фон по умолчанию
$hl_title = !empty($content['title']) ? $content['title'] : '';
$hl_text  = !empty($content['text']) ? $content['text'] : '';
?>

<section class="header6 cid-s29npeJPcs mbr-fullscreen mbr-parallax-background" style="background-image: url('<?= $bg_image ?>'); padding-top: 6rem; padding-bottom: 6rem;">
    <div class="mbr-overlay" style="opacity: 0.5; background-color: rgb(0, 0, 0);"></div>

    <div class="container">
        <div class="row justify-content-md-center">
            <div class="mbr-white col-md-10">
                <?php if($hl_title): ?>
                <h3 class="mbr-section-title align-center mbr-bold pb-3 mbr-fonts-style display-2">
                    <?= $hl_title ?>
                </h3>
                <?php endif; ?>
                
                <?php if($hl_text): ?>
                <p class="mbr-text align-center pb-3 mbr-fonts-style display-5">
                    <?= $hl_text ?>
                </p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>