<?php
// includes/faq_block.php

// Если переменные не переданы, ставим заглушки (на всякий случай)
$faq_title = $faq_title ?? 'Часто задаваемые вопросы';
$faq_items = $faq_items ?? [];
if (empty($block_id)) $block_id = 'faq_' . rand(1000, 9999);
?>

<style>
    .faq-card {
        border: none;
        border-bottom: 1px solid #e0e0e0;
        background: #fff;
        margin-bottom: 0 !important;
        border-radius: 0 !important;
    }
    .faq-header {
        padding: 0;
        background: #fff;
        border: none;
    }
    .faq-btn {
        display: block;
        width: 100%;
        padding: 20px 0;
        text-align: left;
        color: #232323;
        font-weight: 600;
        font-size: 1.1rem;
        text-decoration: none !important;
        position: relative;
        transition: all 0.3s ease;
    }
    .faq-btn:hover {
        color: #d90429;
    }
    .faq-btn::after {
        content: '+';
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 1.5rem;
        font-weight: 400;
        color: #777;
    }
    .faq-btn[aria-expanded="true"]::after {
        content: '−';
        color: #d90429;
    }
    .faq-btn[aria-expanded="true"] {
        color: #d90429;
    }
    .faq-body {
        border-top: none;
        padding-bottom: 25px;
        color: #555;
        line-height: 1.6;
    }
</style>

<div class="container">
    <div class="media-container-row">
        <div class="col-12 col-md-10 offset-md-1">
            <div class="section-head text-center space30">
                
                <div itemscope="" itemtype="https://schema.org/FAQPage" class="faq"> 
                   
                   <h2 class="mbr-section-title pb-5 align-center mbr-fonts-style display-2">
                       <?= $faq_title ?>
                   </h2>
                
                    <div class="clearfix"></div>
                    <div id="bootstrap-toggle" class="toggle-panel accordionStyles tab-content">
                        
                        <?php foreach ($faq_items as $index => $item): 
                            // Генерируем ID для связки заголовка и контента
                            $collapse_id = "collapse" . $index . "_" . $block_id;
                            $heading_id = "heading" . $index . "_" . $block_id;
                        ?>
                        
                        <div class="card faq-card" itemscope="" itemprop="mainEntity" itemtype="https://schema.org/Question">
                            <div class="card-header faq-header" role="tab" id="<?= $heading_id ?>">
                                <a role="button" class="collapsed faq-btn display-7" data-toggle="collapse" href="#<?= $collapse_id ?>" aria-expanded="false" aria-controls="<?= $collapse_id ?>">
                                    <span itemprop="name"><?= $item['q'] ?></span>
                                </a>
                            </div>
                            <div id="<?= $collapse_id ?>" class="panel-collapse noScroll collapse" role="tabpanel" aria-labelledby="<?= $heading_id ?>">
                                <div class="panel-body faq-body">
                                    <div class="mbr-fonts-style display-7">
                                        <div itemscope="" itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                                            <div itemprop="text">
                                                <?= $item['a'] ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>

                    </div>
                </div> </div>
        </div>
    </div>
</div>