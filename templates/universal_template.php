<?php
// templates/universal_template.php

// 1. ПУТИ И ФУНКЦИИ
$root_includes = $_SERVER['DOCUMENT_ROOT'] . '/includes/';
$ua_includes   = $_SERVER['DOCUMENT_ROOT'] . '/includes/';

// Функция умного подключения
function require_smart($filename, $lang, $ua_path, $root_path) {
    // Делаем переменные глобальными, чтобы они были видны внутри блоков
    global $db, $page, $slug, $settings, $h1, $breadcrumbs, $loc, $custom_h1, $custom_p, $custom_bg, $custom_btn, $loc_map, $title, $description, $dist_val, $time_val, $price_val, $city_val, $in_city_val;

    if ($lang == 'ua' && file_exists($ua_path . $filename)) {
        include $ua_path . $filename;
    } elseif (file_exists($root_path . $filename)) {
        include $root_path . $filename;
    }
}

// Функция рендеринга JSON-контента
function render_structured_content($items) {
    if (!is_array($items)) return;
    foreach ($items as $item) {
        $type = $item['type'];
        $text = $item['content'];
        if ($type == 'h2') {
            echo '<section class="mbr-section content4 cid-sfh9LySfAs"><div class="container"><div class="media-container-row"><div class="title col-12 col-md-8">';
            echo "<h2 class='align-center pb-3 mbr-fonts-style display-2'>{$text}</h2>";
            echo '</div></div></div></section>';
        } elseif ($type == 'p') {
            echo '<section class="mbr-section article content1 cid-sfh9tj5sqS"><div class="container"><div class="media-container-row"><div class="mbr-text col-12 mbr-fonts-style display-7 col-md-8">';
            if (is_array($text)) { foreach ($text as $p) echo "<p>{$p}</p>"; } else { echo "<p>{$text}</p>"; }
            echo '</div></div></div></section>';
        } elseif ($type == 'li') {
            echo '<section class="mbr-section article content11 cid-s3XVz1C5G6"><div class="container"><div class="media-container-row"><div class="mbr-text counter-container col-12 col-md-8 mbr-fonts-style display-7"><ol>';
            if (is_array($text)) { foreach ($text as $li) echo "<li>{$li}</li>"; }
            echo '</ol></div></div></div></section>';
        }
    }
}

// ==========================================
// 2. ПОДГОТОВКА ДАННЫХ
// ==========================================

$attrs = [];
if (!empty($page['attributes'])) {
    $attrs = json_decode($page['attributes'], true);
}

// Получаем данные
$price_val = $attrs['price'] ?? $settings['price_car'] ?? '1200'; // Если у города своя цена, берем её
$city_val = $page['breadcrumb_title'] ?? $loc['name'] ?? '';
$in_city_val = $attrs['in_city'] ?? $loc['in_city'] ?? (($lang == 'ua') ? "у " . $city_val : "в " . $city_val);
$dist_val = $attrs['distance'] ?? null; 
$time_val = $attrs['time'] ?? null;     

// --- ВАЖНО: СИНХРОНИЗАЦИЯ С SCHEMA.PHP ---
// Обновляем глобальные массивы, чтобы schema.php в хедере увидела новые данные
$loc['name'] = $city_val; // Теперь schema.php выведет "Евакуатор Борова", а не "Харьков"
$settings['price_car'] = $price_val; // И цену подставит актуальную для города
// -----------------------------------------

// Замены плейсхолдеров
function apply_placeholders($text, $city, $in_city, $price, $dist = null, $time = null) {
    if (empty($text)) return $text;
    $text = str_replace('{city}', $city, $text);
    $text = str_replace('{in_city}', $in_city, $text);
    $text = str_replace('{price}', $price, $text);
    if ($dist) $text = str_replace('{distance}', $dist, $text);
    if ($time) $text = str_replace('{time}', $time, $text);
    return $text;
}

if (!empty($page)) {
    if (!empty($page['meta_title'])) {
        $page['meta_title'] = apply_placeholders($page['meta_title'], $city_val, $in_city_val, $price_val, $dist_val, $time_val);
        $title = $page['meta_title'];
    }
    if (!empty($page['meta_description'])) {
        $page['meta_description'] = apply_placeholders($page['meta_description'], $city_val, $in_city_val, $price_val, $dist_val, $time_val);
        $description = $page['meta_description'];
    }
    
    // H1 и Hero
    if (!empty($custom_h1)) {
        $custom_h1 = apply_placeholders($custom_h1, $city_val, $in_city_val, $price_val, $dist_val, $time_val);
    } elseif (!empty($page['h1'])) {
        $custom_h1 = apply_placeholders($page['h1'], $city_val, $in_city_val, $price_val, $dist_val, $time_val);
    }
    
    if (!empty($custom_p)) {
        $raw_p = $custom_p;
    } elseif (!empty($page['custom_p'])) {
        $raw_p = $page['custom_p'];
    } else {
        $raw_p = '';
    }

    if (!empty($raw_p)) {
        if ($time_val) {
             $raw_p = str_replace(['в течение 20-40 минут', 'протягом 20-40 хвилин'], ($lang == 'ua' ? "протягом {$time_val}" : "в течение {$time_val}"), $raw_p);
        }
        $custom_p = apply_placeholders($raw_p, $city_val, $in_city_val, $price_val, $dist_val, $time_val);
    }
    
    if (empty($custom_bg) && !empty($page['hero_image'])) $custom_bg = $page['hero_image'];
    if (empty($custom_btn) && !empty($page['custom_btn'])) $custom_btn = $page['custom_btn'];
}


// ==========================================
// 3. СБОРКА СТРАНИЦЫ
// ==========================================

// 1. Хедер (внутри подключится schema.php с уже обновленными $loc и $settings)
require_smart('header.php', $lang, $ua_includes, $root_includes);

if ($slug !== 'home' && $slug !== '') {
    if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/breadcrumbs.php')) {
        include $_SERVER['DOCUMENT_ROOT'] . '/breadcrumbs.php';
    } else {
        require_smart('breadcrumbs.php', $lang, $ua_includes, $root_includes);
    }
}

// Hero
$hero_type = $page['h1_type'] ?? 'standard';
$hero_file = ($hero_type === 'service') ? 'h1_service.php' : (($hero_type === 'simple') ? 'h1_simple.php' : 'h1_block.php');
require_smart($hero_file, $lang, $ua_includes, $root_includes);

// 2. Блоки из базы (включая наш новый route_seo_block.php)
if (!empty($blocks)) {
    foreach ($blocks as $block) {
        
        if ($block['block_type'] == 'include') {
            if ($block['block_path'] == 'maps.php' && !empty($attrs['maps'])) {
                 $loc_map = $attrs['maps'];
            }
            require_smart($block['block_path'], $lang, $ua_includes, $root_includes);
        }
        
        elseif ($block['block_type'] == 'text') {
             echo "<div class='container'><div class='row'><div class='col-12'>";
             echo $block['content'];
             echo "</div></div></div>";
        }
        
        elseif ($block['block_type'] == 'structured_content') {
             $items_array = json_decode($block['content'], true);
             render_structured_content($items_array);
        }
    }
}

// 3. Футер
require_smart('footer.php', $lang, $ua_includes, $root_includes);
?>