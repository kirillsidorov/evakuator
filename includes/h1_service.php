<?php
/**
 * Шаблон H1 для сервисных страниц (content5)
 * Поддерживает опциональный текст под заголовком
 */
$bg_style = !empty($custom_bg) ? "style='background-image: url($custom_bg);'" : "";
?>

<section class="mbr-section service_h1_block cid-s5KWs3Q0MY mbr-parallax-background" <?= $bg_style ?>>
    <div class="mbr-overlay" style="opacity: 0.4; background-color: rgb(35, 35, 35);"></div>

    <div class="container">
        <div class="media-container-row">
            <div class="title col-12 col-md-8">
                <h1 class="align-center mbr-bold mbr-white pb-3 mbr-fonts-style display-1">
                    <?= $custom_h1 ?>
                </h1>
                
                <?php if (!empty($custom_p)): ?>
                    <p class="mbr-text align-center mbr-white pb-3 mbr-fonts-style display-5">
                        <?= $custom_p ?>
                    </p>
                <?php endif; ?>

                <?php if (!empty($custom_btn_text)): ?>
                    <div class="mbr-section-btn align-center">
                        <a class="btn btn-md btn-success display-4" href="<?= $custom_btn_link ?? '#' ?>">
                            <?= $custom_btn_text ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>