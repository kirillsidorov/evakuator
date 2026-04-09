<?php
// breadcrumbs.php

// Настройки главной
$home_url = ($lang == 'ua') ? '/ua/' : '/';
$home_name = ($lang == 'ua') ? 'Евакуатор' : 'Эвакуатор'; 

$breadcrumbs = [];

// =========================================================
// ВАРИАНТ 1: РАБОТАЕМ ЧЕРЕЗ БАЗУ (ROUTER)
// =========================================================
if (isset($page) && !empty($page['id'])) {
    global $db; 
    
    // 1. Текущая страница
    $current_name = !empty($page['breadcrumb_title']) ? $page['breadcrumb_title'] : strip_tags($page['h1']);
    $breadcrumbs[] = ['name' => $current_name, 'url' => ''];

    // 2. Родители
    $parent_id = $page['parent_id'] ?? 0;
    $depth = 0;
    while ($parent_id && $depth < 5) {
        $parent = $db->get('pages', '*', ['id' => $parent_id]);
        if ($parent) {
            $p_slug = $parent['slug'];
            $p_lang = $parent['lang'];
            $p_url = ($p_lang == 'ua') ? "/ua/$p_slug" : "/$p_slug";
            $p_name = !empty($parent['breadcrumb_title']) ? $parent['breadcrumb_title'] : strip_tags($parent['h1']);

            $breadcrumbs[] = ['name' => $p_name, 'url' => $p_url];
            $parent_id = $parent['parent_id'];
            $depth++;
        } else { break; }
    }
    
    // 3. Главная
    $breadcrumbs[] = ['name' => $home_name, 'url' => $home_url];
    $breadcrumbs = array_reverse($breadcrumbs);
} 

// =========================================================
// ВАРИАНТ 2: РАБОТАЮТ СТАРЫЕ ФАЙЛЫ (LEGACY)
// =========================================================
else {
    // 1. Главная
    $breadcrumbs[] = ['name' => $home_name, 'url' => $home_url];

    // ОПРЕДЕЛЯЕМ ТИП: РАЙОН ИЛИ ГОРОД?
    // Проверяем переменную $location_type (которую ты добавишь в файл)
    // Или проверяем старый массив $loc (если он там есть)
    $is_district = false;
    
    if (isset($location_type) && $location_type == 'district') {
        $is_district = true;
    } elseif (isset($loc) && isset($loc['type']) && $loc['type'] == 'district') {
        $is_district = true;
    }

    // 2. Промежуточное звено
    // Если это локация, но НЕ сама страница области
    $is_location_page = (isset($page_type) && $page_type == 'locations');
    $is_oblast_url = (strpos($_SERVER['REQUEST_URI'], 'evakuator-po-kharkovskoy-oblasti') !== false);

    if ($is_location_page && !$is_oblast_url) {
        
        if ($is_district) {
            // ДЛЯ РАЙОНОВ: Родитель - Харьков (Главная)
            // По сути, мы просто НЕ добавляем ссылку на Область.
            // Цепочка будет: Главная > Салтовка
        } else {
            // ДЛЯ ГОРОДОВ: Родитель - Область
            $oblast_name = ($lang == 'ua') ? 'Харківська область' : 'Харьковская область';
            $oblast_url  = ($lang == 'ua') ? '/ua/evakuator-po-kharkovskoy-oblasti' : '/evakuator-po-kharkovskoy-oblasti';
            
            $breadcrumbs[] = ['name' => $oblast_name, 'url' => $oblast_url];
        }
    }

    // 3. Текущая страница
    $last_name = '';
    if (isset($breadcrumb_title)) { $last_name = $breadcrumb_title; }
    elseif (isset($loc['name'])) { $last_name = $loc['name']; }
    elseif (isset($custom_h1)) { $last_name = strip_tags($custom_h1); }
    elseif (isset($title)) { $last_name = $title; }
    else { $last_name = ($lang == 'ua') ? 'Сторінка' : 'Страница'; }

    // Обрезка длинных
    if (mb_strlen($last_name) > 50) $last_name = mb_substr($last_name, 0, 50) . '...';
    
    $breadcrumbs[] = ['name' => $last_name, 'url' => ''];
}
?>

<section class="breadcrumbs-section">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumbs-list" itemscope itemtype="https://schema.org/BreadcrumbList">
                
                <?php foreach ($breadcrumbs as $index => $crumb): ?>
                    <?php 
                        // Логика определения последнего элемента
                        $is_last = ($index === count($breadcrumbs) - 1);
                        $position = $index + 1;
                    ?>

                    <li class="breadcrumbs-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                        
                        <?php if (!$is_last): ?>
                            <a class="breadcrumbs-link" itemprop="item" href="<?= $crumb['url'] ?>">
                                <span itemprop="name"><?= $crumb['name'] ?></span>
                            </a>
                            
                            <span class="breadcrumbs-separator">/</span>
                        <?php else: ?>
                            <span class="breadcrumbs-active" itemprop="name"><?= $crumb['name'] ?></span>
                        <?php endif; ?>

                        <meta itemprop="position" content="<?= $position ?>" />
                    </li>
                
                <?php endforeach; ?>

            </ol>
        </nav>
    </div>
</section>