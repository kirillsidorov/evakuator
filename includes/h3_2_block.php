<?php
// includes/h3_block.php

$h_title = $content['h'] ?? '';
$h_text  = $content['p'] ?? '';
$h_image = $content['image'] ?? '';
?>

<section class="header6 cid-s2bgQ9xHwY mbr-fullscreen mbr-parallax-background"
        <?php if (!empty($h_image)): ?>
    style="background-image: url('<?= htmlspecialchars($h_image) ?>'); background-size: cover; background-position: center;"
    <?php endif; ?>>

    <div class="mbr-overlay" style="opacity: 0.5; background-color: rgb(255, 255, 255);"></div>

    <div class="container">
        <div class="row justify-content-md-center">
            <div class="mbr-white col-md-10">
                <h3 class="mbr-section-title align-center mbr-bold pb-3 mbr-fonts-style display-2">
                    <?= $h_title ?>
                </h3>
                <p class="mbr-text align-center pb-3 mbr-fonts-style display-5">
                    <?= $h_text ?>
                </p>
            </div>
        </div>
    </div>
</section>