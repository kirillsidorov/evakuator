</main>

<?php
$is_ua = ($lang === 'ua');

$footer_menu = $is_ua ? [
    'col1_title' => 'Райони Харкова', 'col1_links' => ['evakuator-saltovka' => 'Салтівка', 'evakuator-aleksseyevka' => 'Олексіївка', 'evakuator-kholodnaya-gora' => 'Холодна гора', 'evakuator-xtz' => 'ХТЗ', 'evakuator-novyye-doma' => 'Нові будинки'],
    'col2_title' => 'Область', 'col2_links' => ['evakuator-pesochin' => 'Пісочин', 'evakuator-merefa' => 'Мерефа', 'evakuator-Chuguyev' => 'Чугуїв', 'evakuator-balakleya' => 'Балаклія', 'evakuator-Izyum' => 'Ізюм', 'evakuator-Kupyansk' => 'Куп\'янськ', 'evakuator-Lozovaya' => 'Лозова', 'evakuator-po-kharkovskoy-oblasti'=> 'По області'],
    'col3_title' => 'Послуги', 'col3_links' => ['gruzovoy-evakuator-kharkov' => 'Вантажний евакуатор', 'evakuator-manipulator-kharkov' => 'Маніпулятор', 'Perevozka-spetstekhniki-Kharkov' => 'Перевезення спецтехніки', 'avtosos' => 'Автосос', 'sto-kharkov' => 'Послуги СТО', 'avtovykup-kharkov' => 'Автовикуп'],
    'col4_title' => 'Клієнтам', 'col4_links' => ['price' => 'Тарифи та Ціни', 'phone-number' => 'Контакти', 'avtopark-evakuatorov' => 'Автопарк', 'news' => 'Блог'],
    'copyright' => '© Copyright 2010-' . date('Y') . ' Евакуатор по Харкову та Україні - Всі права захищені.',
    'google_aria' => 'Подивитися відгуки про Евакуатор Харків в Google',
    'instagram_aria' => 'Перейти в Instagram профіль Евакуатор Харків',
] : [
    'col1_title' => 'Районы Харькова', 'col1_links' => ['evakuator-saltovka' => 'Салтовка', 'evakuator-aleksseyevka' => 'Алексеевка', 'evakuator-kholodnaya-gora' => 'Холодная гора', 'evakuator-xtz' => 'ХТЗ', 'evakuator-novyye-doma' => 'Новые дома'],
    'col2_title' => 'Область', 'col2_links' => ['evakuator-pesochin' => 'Песочин', 'evakuator-merefa' => 'Мерефа', 'evakuator-Chuguyev' => 'Чугуев', 'evakuator-balakleya' => 'Балаклея', 'evakuator-Izyum' => 'Изюм', 'evakuator-Kupyansk' => 'Купянск', 'evakuator-Lozovaya' => 'Лозовая', 'evakuator-po-kharkovskoy-oblasti'=> 'По области'],
    'col3_title' => 'Услуги', 'col3_links' => ['gruzovoy-evakuator-kharkov' => 'Грузовой эвакуатор', 'evakuator-manipulator-kharkov' => 'Манипулятор', 'Perevozka-spetstekhniki-Kharkov' => 'Перевозка спецтехники', 'avtosos' => 'Автосос', 'sto-kharkov' => 'Услуги СТО', 'avtovykup-kharkov' => 'Автовыкуп'],
    'col4_title' => 'Клиентам', 'col4_links' => ['price' => 'Тарифы и Цены', 'phone-number' => 'Контакты', 'avtopark-evakuatorov' => 'Автопарк', 'news' => 'Блог'],
    'copyright' => '© Copyright 2010-' . date('Y') . ' Эвакуатор по Харькову и Украине - Все права защищены.',
    'google_aria' => 'Посмотреть отзывы об Эвакуатор Харьков в Google',
    'instagram_aria' => 'Перейти в Instagram профиль Эвакуатор Харьков',
];

$link_prefix = $is_ua ? '/ua/' : '/';
?>

<footer class="footer">
    <div class="footer-inner">
        <div class="footer-grid">
            <div><div class="footer-col-title"><?= $footer_menu['col1_title'] ?></div><ul class="footer-links"><?php foreach ($footer_menu['col1_links'] as $slug => $name): ?><li><a href="<?= $link_prefix . $slug ?>"><?= $name ?></a></li><?php endforeach; ?></ul></div>
            <div><div class="footer-col-title"><?= $footer_menu['col2_title'] ?></div><ul class="footer-links"><?php foreach ($footer_menu['col2_links'] as $slug => $name): ?><li><a href="<?= $link_prefix . $slug ?>"><?= $name ?></a></li><?php endforeach; ?></ul></div>
            <div><div class="footer-col-title"><?= $footer_menu['col3_title'] ?></div><ul class="footer-links"><?php foreach ($footer_menu['col3_links'] as $slug => $name): ?><li><a href="<?= $link_prefix . $slug ?>"><?= $name ?></a></li><?php endforeach; ?></ul></div>
            <div><div class="footer-col-title"><?= $footer_menu['col4_title'] ?></div><ul class="footer-links"><?php foreach ($footer_menu['col4_links'] as $slug => $name): ?><li><a href="<?= $link_prefix . $slug ?>"><?= $name ?></a></li><?php endforeach; ?></ul></div>
        </div>
        <div class="footer-bottom">
            <div><?= $footer_menu['copyright'] ?></div>
            <div class="social-links">
                <a href="https://g.page/r/CQ22OWHBZsJZEBM/" target="_blank" rel="noopener" aria-label="<?= $footer_menu['google_aria'] ?>"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg></a>
                <a href="https://www.instagram.com/evakuatorkharkov/" target="_blank" rel="noopener" aria-label="<?= $footer_menu['instagram_aria'] ?>"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg></a>
            </div>
        </div>
    </div>
</footer>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // 1. FAQ Аккордеон
    document.querySelectorAll('.faq-q').forEach(btn => {
        btn.addEventListener('click', () => {
            const ans = btn.nextElementSibling;
            const isOpen = btn.classList.contains('active');
            
            document.querySelectorAll('.faq-q').forEach(b => {
                b.classList.remove('active');
                b.nextElementSibling.style.maxHeight = null;
                b.querySelector('.faq-icon').textContent = '+';
            });
            
            if (!isOpen) {
                btn.classList.add('active');
                ans.style.maxHeight = ans.scrollHeight + 'px';
                btn.querySelector('.faq-icon').textContent = '−';
            }
        });
    });

    // 2. Мобильное меню (Шторка)
    const overlay = document.getElementById('mobOverlay');
    const sheet = document.getElementById('mobSheet');
    const closeBtn = document.getElementById('mobClose');
    const burger = document.getElementById('mobBurger');

    function openMenu() {
        if(sheet) sheet.classList.add('is-open');
        if(overlay) overlay.classList.add('is-open');
        document.body.style.overflow = 'hidden'; 
    }
    function closeMenu() {
        if(sheet) sheet.classList.remove('is-open');
        if(overlay) overlay.classList.remove('is-open');
        document.body.style.overflow = ''; 
    }

    if(burger) burger.addEventListener('click', openMenu);
    if(closeBtn) closeBtn.addEventListener('click', closeMenu);
    if(overlay) overlay.addEventListener('click', closeMenu);

    if(sheet) {
        sheet.querySelectorAll('a').forEach(a => {
            if(a.href && !a.href.startsWith('tel:')) a.addEventListener('click', closeMenu);
        });

        let startY = 0;
        sheet.addEventListener('touchstart', e => { startY = e.touches[0].clientY; }, {passive: true});
        sheet.addEventListener('touchend', e => {
            if (e.changedTouches[0].clientY - startY > 60) closeMenu();
        }, {passive: true});
    }

    // 3. Плавающая кнопка звонка (появляется при скролле)
    const fab = document.getElementById('fabCall');
    if (fab) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 400) {
                fab.classList.add('is-visible');
            } else {
                fab.classList.remove('is-visible');
            }
        }, { passive: true });
    }
});
</script>

</body>
</html>