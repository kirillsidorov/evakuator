<?php if (!empty($loc_map)): ?>
<section class="sec" style="padding-top: 0;">
    <div class="sec-inner">
        <div class="map-wrap">
            <iframe 
                src="<?= $loc_map ?>"
                width="100%" 
                height="100%" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>
</section>
<?php endif; ?>