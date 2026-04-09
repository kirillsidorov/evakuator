<?php
$faq_title = $faq_title ?? 'Часто задаваемые вопросы';
$faq_items = $faq_items ?? [];
if (empty($block_id)) $block_id = 'faq_' . rand(1000, 9999);
?>

<section class="sec" style="background:#f8f8f6">
    <div class="sec-inner">
        <div class="sec-title"><?= $faq_title ?></div>
        
        <div itemscope="" itemtype="https://schema.org/FAQPage" class="faq">
            <?php foreach ($faq_items as $index => $item): ?>
                <div class="faq-item" itemscope="" itemprop="mainEntity" itemtype="https://schema.org/Question">
                    <button class="faq-q">
                        <span itemprop="name"><?= $item['q'] ?></span>
                        <span class="faq-icon">+</span>
                    </button>
                    <div class="faq-a" itemscope="" itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                        <div itemprop="text"><?= $item['a'] ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
    </div>
</section>