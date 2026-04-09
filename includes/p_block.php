<section class="mbr-section article content1 cid-sfh9tj5sqS">
    <div class="container">
        <div class="media-container-row">
            <div class="mbr-text col-12 mbr-fonts-style display-7 col-md-8">
                <?php if (is_array($block_content)): ?>
                    <?php foreach ($block_content as $paragraph): ?>
                        <p><?= $paragraph ?></p>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p><?= $block_content ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>