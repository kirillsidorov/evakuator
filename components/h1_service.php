<?php
/**
 * Шаблон H1 для сервисных страниц (Новый быстрый дизайн)
 */
$bg_style = !empty($custom_bg) ? "style=\"background: #0a0a0a url('$custom_bg') center/cover no-repeat;\"" : "style=\"background: #0a0a0a;\"";
?>

<section class="hero" <?= $bg_style ?>>
    <div class="hero-overlay" style="background: linear-gradient(to top, rgba(0,0,0,0.92) 0%, rgba(0,0,0,0.55) 100%);"></div>
    
    <div class="hero-body" style="text-align: center; margin: 0 auto;">
        <h1><?= $custom_h1 ?></h1>
        
        <?php if (!empty($custom_p)): ?>
            <p class="hero-sub"><?= $custom_p ?></p>
        <?php endif; ?>

        <?php if (!empty($custom_btn_text)): ?>
            <a href="<?= $custom_btn_link ?? '#' ?>" class="hero-cta">
                <?= $custom_btn_text ?>
            </a>
        <?php endif; ?>
    </div>
</section>