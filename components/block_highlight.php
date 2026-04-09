<?php
$h_title = $content['title'] ?? $content['h'] ?? $hl_title ?? '';
$h_text  = $content['text'] ?? $content['p'] ?? $hl_text ?? '';
$h_image = $content['image'] ?? $bg_image ?? '/assets/images/header-1800x1200.webp';
?>

<div class="band" style="background: url('<?= htmlspecialchars($h_image) ?>') center/cover no-repeat; position: relative; z-index: 1;">
  <div style="position: absolute; inset: 0; background: rgba(0,0,0,0.65); z-index: -1;"></div>
  <div class="band-inner">
    <?php if($h_title): ?>
        <div class="band-title"><?= $h_title ?></div>
    <?php endif; ?>
    <?php if($h_text): ?>
        <div class="band-sub" style="color: #fff; font-size: 16px; opacity: 0.9;"><?= $h_text ?></div>
    <?php endif; ?>
  </div>
</div>