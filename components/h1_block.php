<?php
/**
 * Обновленный универсальный H1 блок (Новый быстрый дизайн)
 */

$current_h1  = !empty($custom_h1)  ? $custom_h1  : (($lang == 'ua') ? "Евакуатор " . ($loc['name'] ?? '') : "Эвакуатор " . ($loc['name'] ?? ''));
$current_p   = !empty($custom_p)   ? $custom_p   : (($lang == 'ua') ? "Терміновий виклик..." : "Срочный вызов...");
$current_btn = !empty($custom_btn) ? $custom_btn : (($lang == 'ua') ? "Викликати евакуатор" : "Вызвать эвакуатор");
$badge_text  = ($lang == 'ua')     ? "Працюємо 24/7" : "Работаем 24/7";

// Динамический фон
$bg_style = !empty($custom_bg) ? "style=\"background: #0a0a0a url('$custom_bg') center/cover no-repeat;\"" : "style=\"background: #0a0a0a;\"";
?>

<section class="hero" <?= $bg_style ?>>
  <div class="hero-overlay"></div>
  <div class="hero-body">
    <div class="hero-badge"><div class="hero-badge-dot"></div><?= $badge_text ?></div>
    
    <h1><?= $current_h1 ?></h1>
    
    <p class="hero-sub"><?= $current_p ?></p>
    
    <a href="tel:<?= $settings['tel_one_link'] ?? '+380508517555' ?>" class="hero-cta">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.55 11a19.79 19.79 0 01-3.07-8.67A2 2 0 012.44 0h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 7.91a16 16 0 006.18 6.18l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
      <?= $current_btn ?>
    </a>
  </div>
</section>