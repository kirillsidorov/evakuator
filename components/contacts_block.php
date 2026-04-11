<?php
$show_address_types = ['main', 'service', 'contacts','locations'];
$is_full_address = in_array($page_type, $show_address_types);
?>
<section class="sec">
    <div class="sec-inner">
        <h2 class="sec-title">
            <?= ($lang == 'ua' ? "Телефон евакуатора " : "Телефон эвакуатора ") . ($loc['in_city'] ?? '') ?>
        </h2>
        
        <div class="contacts-list">
            <div class="contact-row">
                <div class="contact-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#111" stroke-width="2"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                </div>
                <div>
                    <?php if ($is_full_address): ?>
                        <div class="contact-label"><?= ($lang == 'ua' ? "Наша адреса:" : "Наш адрес:") ?></div>
                        <div class="contact-val"><?= $settings['address_' . $lang] ?? '' ?></div>
                    <?php else: ?>
                        <div class="contact-label"><?= ($lang == 'ua' ? "Напишіть нам:" : "Напишите нам:") ?></div>
                        <div class="contact-val">
                            <a href="viber://chat?number=<?= $settings['tel_one_link'] ?>" style="color: #7360f2;">Viber</a> / 
                            <a href="https://t.me/<?= $settings['telegram_user'] ?? '' ?>" style="color: #0088cc;">Telegram</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="contact-row">
                <div class="contact-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#111" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.55 11a19.79 19.79 0 01-3.07-8.67A2 2 0 012.44 0h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 7.91a16 16 0 006.18 6.18l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
                </div>
                <div>
                    <div class="contact-label"><?= ($lang == 'ua' ? "Цілодобово:" : "Круглосуточно:") ?></div>
                    <div class="contact-val">
                        <a href="tel:<?= $settings['tel_one_link'] ?>"><?= $settings['tel_one_view'] ?></a> / 
                        <a href="tel:<?= $settings['tel_two_link'] ?>"><?= $settings['tel_two_view'] ?></a>
                    </div>
                </div>
            </div>
            
            <div class="contact-row">
                <div class="contact-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#111" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M2 7l10 7 10-7"/></svg>
                </div>
                <div>
                    <div class="contact-label">E-mail:</div>
                    <div class="contact-val"><a href="mailto:<?= $settings['email'] ?>"><?= $settings['email'] ?></a></div>
                </div>
            </div>
        </div>
    </div>
</section>