<?php if (!empty($loc_map)): ?>
 <section class="map1 cid-s3YpLIExxs">
    <div class="google-map">
        <iframe 
        src="<?= $loc_map ?>"
        width="100%" 
        height="450" 
        style="border:0;" 
        allowfullscreen="" 
        loading="lazy" 
        referrerpolicy="no-referrer-when-downgrade">
    </iframe>
    </div>
</section>
<?php endif; ?> 