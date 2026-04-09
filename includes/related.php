<?php
// includes/related.php

// 1. Запрос к базе данных
// Выбираем 3 случайные статьи того же языка, исключая текущую страницу
$related_items = $db->select('pages', '*', [
    'type' => 'articles',       // Только статьи
    'lang' => $lang,            // Тот же язык
    'id[!]' => $page['id'],     // Исключаем текущую страницу (чтобы не ссылаться на саму себя)
    'ORDER' => $db->raw('RAND()'), // Случайный порядок
    'LIMIT' => 3                // Только 3 штуки
]);

// Заголовки блока
$related_title = ($lang == 'ua') ? 'Читайте також:' : 'Читайте также:';
?>

<?php if (!empty($related_items)): ?>
<section class="mbr-section article content1 cid-related-posts" id="related" style="background-color: #f9f9f9; padding-top: 40px; padding-bottom: 40px; border-top: 1px solid #eee;">
    <div class="container">
        <div class="media-container-row">
            <div class="mbr-text col-12 mbr-fonts-style display-7 col-md-10">
                <h3 class="pb-3 mbr-fonts-style display-5" style="color: #232323;"><?= $related_title ?></h3>
                <div class="row">
                    <?php foreach ($related_items as $post): 
                        $post_link = ($lang == 'ua' ? '/ua/' : '/') . $post['slug'];
                    ?>
                        <div class="col-12 col-md-4 mb-3">
                            <div class="related-card" style="background: #fff; padding: 15px; height: 100%; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                                <?php if(!empty($post['hero_image'])): ?>
                                    <div style="height: 150px; overflow: hidden; margin-bottom: 10px;">
                                        <img src="<?= $post['hero_image'] ?>" style="width: 100%; height: 100%; object-fit: cover;" alt="<?= $post['breadcrumb_title'] ?>">
                                    </div>
                                <?php endif; ?>
                                
                                <h5 style="font-size: 1rem; line-height: 1.4; margin-bottom: 10px;">
                                    <a href="<?= $post_link ?>" style="color: #232323; font-weight: bold;">
                                        <?= $post['breadcrumb_title'] ?>
                                    </a>
                                </h5>
                                <a href="<?= $post_link ?>" style="font-size: 0.9rem; color: #d90429; text-decoration: underline;">
                                    <?= ($lang=='ua'?'Читати':'Читать') ?> →
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>