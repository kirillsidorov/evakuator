<?php
// ... ВЕСЬ ВАШ PHP КОД ДО HTML ОСТАЕТСЯ БЕЗ ИЗМЕНЕНИЙ ...
// (Оставьте логику с $breadcrumbs = []; и VАРИАНТ 1 / VАРИАНТ 2)
?>
<nav class="breadcrumbs" aria-label="breadcrumb">
    <ol class="breadcrumbs-inner" itemscope itemtype="https://schema.org/BreadcrumbList">
        <?php foreach ($breadcrumbs as $index => $crumb): ?>
            <?php 
                $is_last = ($index === count($breadcrumbs) - 1);
                $position = $index + 1;
            ?>
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" style="display:inline-flex; align-items:center; gap:8px;">
                <?php if (!$is_last): ?>
                    <a itemprop="item" href="<?= $crumb['url'] ?>"><span itemprop="name"><?= $crumb['name'] ?></span></a>
                    <span class="breadcrumbs-sep">/</span>
                <?php else: ?>
                    <span itemprop="name" style="color:#111;"><?= $crumb['name'] ?></span>
                <?php endif; ?>
                <meta itemprop="position" content="<?= $position ?>" />
            </li>
        <?php endforeach; ?>
    </ol>
</nav>