<?php
// includes/theme_functions.php

// 1. Пути
$root_includes = $_SERVER['DOCUMENT_ROOT'] . '/includes/';
$ua_includes   = $_SERVER['DOCUMENT_ROOT'] . '/includes/';

// 2. Функция умного подключения (RU/UA)
if (!function_exists('require_smart')) {
    function require_smart($filename, $lang, $ua_path, $root_path) {
        global $db, $page, $page_type, $slug, $settings, $h1, $breadcrumbs, $loc, $custom_h1, $custom_p, $custom_bg, $custom_btn, $loc_map, $title, $description, $dist_val, $time_val, $price_val, $city_val, $in_city_val, $custom_btn_text, $custom_btn_link;

        if ($lang == 'ua' && file_exists($ua_path . $filename)) {
            include $ua_path . $filename;
        } elseif (file_exists($root_path . $filename)) {
            include $root_path . $filename;
        }
    }
}

// 3. Замена плейсхолдеров ({city}, {price})
if (!function_exists('apply_placeholders')) {
    function apply_placeholders($text, $city, $in_city, $price, $dist = null, $time = null, $settings = null) {
        if (!$text) return '';

        $text = str_replace('{city}', $city, $text);
        $text = str_replace('{in_city}', $in_city, $text);
        $text = str_replace('{price}', $price, $text);
        $text = str_replace('{dist}', $dist ?? '', $text);
        $text = str_replace('{time}', $time ?? '', $text);

        // Плоские ключи из базы данных
        if ($settings) {
            $text = str_replace('{tel1}', $settings['tel_one_view'] ?? '', $text);
            $text = str_replace('{tel2}', $settings['tel_two_view'] ?? '', $text);
            $text = str_replace('{tel1_link}', $settings['tel_one_link'] ?? '', $text);
            $text = str_replace('{tel2_link}', $settings['tel_two_link'] ?? '', $text);
            $text = str_replace('{viber_link}', $settings['viber_clean'] ?? '', $text);
            $text = str_replace('{tg_user}', $settings['telegram_user'] ?? '', $text);
            $text = str_replace('{price_km}', $settings['price_km'] ?? '', $text); // Добавили поддержку {price_km}
        }

        return $text;
    }
}

// 4. Рендеринг блоков (Текст, Списки, FAQ)
if (!function_exists('render_structured_content')) {
    function render_structured_content($items) {
        // ДЕЛАЕМ ПЕРЕМЕННЫЕ ВИДИМЫМИ ВНУТРИ ФУНКЦИИ (КРИТИЧЕСКИ ВАЖНО!)
        global $loc, $price_val, $dist_val, $time_val, $settings;

        if (!is_array($items)) return;
        
        foreach ($items as $index => $item) {
            $type = $item['type'];
            $content = $item['content'];

            if ($type == 'h2') {
                echo '<section class="mbr-section content4 cid-sfhfKbVqfS"><div class="container"><div class="media-container-row"><div class="title col-12 col-md-8">';
                // Для H2 $content - это просто строка
                $h2_text = apply_placeholders($content, $loc['name'] ?? '', $loc['in_city'] ?? '', $price_val, $dist_val, $time_val, $settings);
                echo '<h2 class="align-center pb-3 mbr-fonts-style display-2">' . $h2_text . '</h2>';
                echo '</div></div></div></section>';
            } 
            elseif ($type == 'p') {
                echo '<section class="mbr-section article content1 cid-sfhfC4QgR6"><div class="container"><div class="media-container-row"><div class="mbr-text col-12 mbr-fonts-style display-7 col-md-8">';
                // Для P $content - это просто строка
                echo apply_placeholders($content, $loc['name'] ?? '', $loc['in_city'] ?? '', $price_val, $dist_val, $time_val, $settings);
                echo '</div></div></div></section>';
            } 
            elseif ($type == 'li') {
                echo '<section class="mbr-section article content11 cid-s3XVz1C5G6"><div class="container"><div class="media-container-row"><div class="mbr-text counter-container col-12 col-md-8 mbr-fonts-style display-7"><ol>';
                if (is_array($content)) { 
                    foreach ($content as $li) {
                        // Обрабатываем каждый элемент списка
                        echo "<li>" . apply_placeholders($li, $loc['name'] ?? '', $loc['in_city'] ?? '', $price_val, $dist_val, $time_val, $settings) . "</li>"; 
                    }
                }
                echo '</ol></div></div></div></section>';
            }
            elseif ($type == 'h3') {
                if (empty($content) && isset($item['h'])) {
                    $content = [
                        'h'     => $item['h'],
                        'p'     => $item['p'] ?? '',
                        'image' => $item['image'] ?? '',
                    ];
                }

                if (is_array($content)) {
                    if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/includes/h3_block.php')) {
                        include $_SERVER['DOCUMENT_ROOT'] . '/includes/h3_block.php';
                    }
                } else {
                    echo '<section class="mbr-section article content1 cid-h3-std"><div class="container"><div class="media-container-row"><div class="mbr-text col-12 col-md-8 mbr-fonts-style display-5">';
                    echo "<h3><strong>" . apply_placeholders($content, $loc['name'] ?? '', $loc['in_city'] ?? '', $price_val, $dist_val, $time_val, $settings) . "</strong></h3>";
                    echo '</div></div></div></section>';
                }
            }
            elseif ($type == 'highlight') {
                if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/includes/block_highlight.php')) {
                    include $_SERVER['DOCUMENT_ROOT'] . '/includes/block_highlight.php';
                }
            }

            // ТАБЛИЦА
            elseif ($type == 'table') {
                // В таблице title и columns лежат в $item, а строки в $item['content']
                $tbl_title = apply_placeholders($item['title'] ?? '', $loc['name'] ?? '', $loc['in_city'] ?? '', $price_val, $dist_val, $time_val, $settings);
                $columns   = $item['columns'] ?? [];
                $rows      = $content; // Здесь $content это массив массивов

                echo '<section class="section-table cid-s1LSywcbcb" id="table1-5" style="padding-top: 130px;">';
                echo '<div class="container container-table">';

                if ($tbl_title) {
                    echo '<div class="media-container-row"><div class="title col-12 col-md-8">';
                    echo '<h2 class="mbr-section-title mbr-fonts-style align-center pb-3 display-2">' . htmlspecialchars($tbl_title) . "</h2>";
                    echo '</div></div>';
                }

                echo '<div class="table-wrapper"><div class="container scroll">';
                echo '<table class="table" cellspacing="0">';
                
                if (!empty($columns)) {
                    echo '<thead><tr class="table-heads">';
                    foreach ($columns as $col) {
                        echo '<th class="head-item mbr-fonts-style display-7">' . apply_placeholders($col, $loc['name'] ?? '', $loc['in_city'] ?? '', $price_val, $dist_val, $time_val, $settings) . '</th>';
                    }
                    echo '</tr></thead>';
                }
                
                if (!empty($rows) && is_array($rows)) {
                    echo '<tbody>';
                    foreach ($rows as $row) {
                        echo '<tr>';
                        foreach ($row as $i => $cell) {
                            $is_last = ($i === array_key_last($row));
                            $processed_cell = apply_placeholders($cell, $loc['name'] ?? '', $loc['in_city'] ?? '', $price_val, $dist_val, $time_val, $settings);
                            echo '<td class="body-item mbr-fonts-style display-7">';
                            echo $is_last ? "<strong>{$processed_cell}</strong>" : $processed_cell;
                            echo '</td>';
                        }
                        echo '</tr>';
                    }
                    echo '</tbody>';
                }
                echo '</table></div></div></div></section>';
            }

            // FAQ
            elseif ($type == 'faq') {
                // В FAQ title и items лежат внутри $content
                $faq_title = apply_placeholders($content['title'] ?? 'FAQ', $loc['name'] ?? '', $loc['in_city'] ?? '', $price_val, $dist_val, $time_val, $settings);
                $faq_items_raw = $content['items'] ?? [];
                $faq_items = [];
                
                foreach ($faq_items_raw as $faq_item) {
                    $faq_items[] = [
                        'q' => apply_placeholders($faq_item['q'], $loc['name'] ?? '', $loc['in_city'] ?? '', $price_val, $dist_val, $time_val, $settings),
                        'a' => apply_placeholders($faq_item['a'], $loc['name'] ?? '', $loc['in_city'] ?? '', $price_val, $dist_val, $time_val, $settings)
                    ];
                }

                $block_id = 'faq_block_' . $index;

                if (!empty($faq_items)) {
                    echo '<section class="toggle1">';
                    if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/includes/faq_block.php')) {
                        include $_SERVER['DOCUMENT_ROOT'] . '/includes/faq_block.php';
                    }
                    echo '</section>';
                }
            }
        }
    }
}
?>