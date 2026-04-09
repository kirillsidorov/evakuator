<?php

if (!empty($page)) {
    if (!empty($page['meta_title'])) $title = apply_placeholders($page['meta_title'], $city_val, $in_city_val, $price_val, $dist_val, $time_val);
    if (!empty($page['meta_description'])) $description = apply_placeholders($page['meta_description'], $city_val, $in_city_val, $price_val, $dist_val, $time_val);
    
    // H1 и Hero
    if (!empty($custom_h1)) {
        $custom_h1 = apply_placeholders($custom_h1, $city_val, $in_city_val, $price_val, $dist_val, $time_val);
    } elseif (!empty($page['h1'])) {
        $custom_h1 = apply_placeholders($page['h1'], $city_val, $in_city_val, $price_val, $dist_val, $time_val);
    }
    
    // Hero P
    if (!empty($custom_p)) $raw_p = $custom_p;
    elseif (!empty($page['custom_p'])) $raw_p = $page['custom_p'];
    else $raw_p = '';

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

require_smart('header.php', $lang, $ua_includes, $root_includes);

if ($slug !== 'home' && $slug !== '') {
    if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/breadcrumbs.php')) {
        include $_SERVER['DOCUMENT_ROOT'] . '/breadcrumbs.php';
    } else {
        require_smart('breadcrumbs.php', $lang, $ua_includes, $root_includes);
    }
}

$hero_type = $page['h1_type'] ?? 'standard';
$hero_file = ($hero_type === 'service') ? 'h1_service.php' : (($hero_type === 'simple') ? 'h1_simple.php' : 'h1_block.php');
require_smart($hero_file, $lang, $ua_includes, $root_includes);

// ВЫВОД БЛОКОВ
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

require_smart('footer.php', $lang, $ua_includes, $root_includes);
?>