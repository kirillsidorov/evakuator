<?php
// includes/theme_functions.php
// Обновлено: require_smart() ищет сначала в /components/, потом в /includes/

// 1. Пути
$root_includes = $_SERVER['DOCUMENT_ROOT'] . '/includes/';
$ua_includes   = $_SERVER['DOCUMENT_ROOT'] . '/includes/';
$components    = $_SERVER['DOCUMENT_ROOT'] . '/components/';

// 2. Функция умного подключения
//    Приоритет: components/ → includes/
//    Это позволяет постепенно переносить файлы в новый дизайн
if (!function_exists('require_smart')) {
    function require_smart($filename, $lang, $ua_path, $root_path) {
        global $db, $page, $page_type, $slug, $settings, $h1, $breadcrumbs,
               $loc, $custom_h1, $custom_p, $custom_bg, $custom_btn, $loc_map,
               $title, $description, $dist_val, $time_val, $price_val,
               $city_val, $in_city_val, $custom_btn_text, $custom_btn_link,
               $faq_title, $faq_items, $blocks, $attrs, $lang;

        $components_path = $_SERVER['DOCUMENT_ROOT'] . '/components/';

        // Приоритет 1: components/ (новый дизайн)
        if (file_exists($components_path . $filename)) {
            include $components_path . $filename;
            return;
        }

        // Приоритет 2: includes/ (старый дизайн, fallback)
        if ($lang == 'ua' && file_exists($ua_path . $filename)) {
            include $ua_path . $filename;
        } elseif (file_exists($root_path . $filename)) {
            include $root_path . $filename;
        }
    }
}

// 3. Замена плейсхолдеров ({city}, {price}, {tel1} и т.д.)
if (!function_exists('apply_placeholders')) {
    function apply_placeholders($text, $city, $in_city, $price, $dist = null, $time = null, $settings = null) {
        if (!$text) return '';

        $text = str_replace('{city}', $city, $text);
        $text = str_replace('{in_city}', $in_city, $text);
        $text = str_replace('{price}', $price, $text);
        $text = str_replace('{dist}', $dist ?? '', $text);
        $text = str_replace('{time}', $time ?? '', $text);

        if ($settings) {
            $text = str_replace('{tel1}', $settings['tel_one_view'] ?? '', $text);
            $text = str_replace('{tel2}', $settings['tel_two_view'] ?? '', $text);
            $text = str_replace('{tel1_link}', $settings['tel_one_link'] ?? '', $text);
            $text = str_replace('{tel2_link}', $settings['tel_two_link'] ?? '', $text);
            $text = str_replace('{viber_link}', $settings['viber_clean'] ?? '', $text);
            $text = str_replace('{tg_user}', $settings['telegram_user'] ?? '', $text);
            $text = str_replace('{price_km}', $settings['price_km'] ?? '', $text);
        }

        return $text;
    }
}

// 4. Рендеринг структурированных блоков (JSON → HTML)
//    ОБНОВЛЕНО: использует классы нового дизайна вместо Mobirise
if (!function_exists('render_structured_content')) {
    function render_structured_content($items) {
        global $loc, $price_val, $dist_val, $time_val, $settings, $lang;

        if (!is_array($items)) return;

        foreach ($items as $index => $item) {
            $type = $item['type'];
            $content = $item['content'];
            $name    = $loc['name'] ?? '';
            $in_city = $loc['in_city'] ?? '';

            if ($type == 'h2') {
                $h2_text = apply_placeholders($content, $name, $in_city, $price_val, $dist_val, $time_val, $settings);
                echo '<section class="sec"><div class="sec-inner">';
                echo '<div class="sec-title">' . $h2_text . '</div>';
                echo '</div></section>';
            }
            elseif ($type == 'p') {
                $p_text = apply_placeholders($content, $name, $in_city, $price_val, $dist_val, $time_val, $settings);
                echo '<section class="sec"><div class="sec-inner">';
                echo '<div class="text-block">' . $p_text . '</div>';
                echo '</div></section>';
            }
            elseif ($type == 'li') {
                echo '<section class="sec"><div class="sec-inner"><ul class="num-list">';
                if (is_array($content)) {
                    foreach ($content as $i => $li) {
                        $li_text = apply_placeholders($li, $name, $in_city, $price_val, $dist_val, $time_val, $settings);
                        echo '<li><div class="num">' . ($i + 1) . '</div><span>' . $li_text . '</span></li>';
                    }
                }
                echo '</ul></div></section>';
            }
            elseif ($type == 'h3') {
                if (empty($content) && isset($item['h'])) {
                    $content = ['h' => $item['h'], 'p' => $item['p'] ?? '', 'image' => $item['image'] ?? ''];
                }

                if (is_array($content)) {
                    $h3_text = apply_placeholders($content['h'] ?? '', $name, $in_city, $price_val, $dist_val, $time_val, $settings);
                    $h3_p    = apply_placeholders($content['p'] ?? '', $name, $in_city, $price_val, $dist_val, $time_val, $settings);
                    echo '<section class="sec"><div class="sec-inner">';
                    echo '<div class="sec-title" style="font-size:clamp(22px,5vw,30px)">' . $h3_text . '</div>';
                    if ($h3_p) {
                        echo '<div class="text-block">' . $h3_p . '</div>';
                    }
                    if (!empty($content['image'])) {
                        echo '<img src="' . $content['image'] . '" alt="' . htmlspecialchars(strip_tags($h3_text)) . '" loading="lazy" style="border-radius:12px;margin-top:20px;box-shadow:0 10px 30px rgba(0,0,0,.08)">';
                    }
                    echo '</div></section>';
                } else {
                    $h3_text = apply_placeholders($content, $name, $in_city, $price_val, $dist_val, $time_val, $settings);
                    echo '<section class="sec"><div class="sec-inner">';
                    echo '<div class="sec-title" style="font-size:clamp(22px,5vw,30px)">' . $h3_text . '</div>';
                    echo '</div></section>';
                }
            }
            elseif ($type == 'highlight') {
                $hl_text = apply_placeholders($content ?? '', $name, $in_city, $price_val, $dist_val, $time_val, $settings);
                echo '<section class="sec" style="background:#f8f8f6"><div class="sec-inner">';
                echo '<div class="text-block" style="background:#fff;border-radius:12px;padding:24px;border-left:4px solid #e9ff00">' . $hl_text . '</div>';
                echo '</div></section>';
            }
            elseif ($type == 'table') {
                $tbl_title = apply_placeholders($item['title'] ?? '', $name, $in_city, $price_val, $dist_val, $time_val, $settings);
                $columns   = $item['columns'] ?? [];
                $rows      = $content;

                echo '<section class="sec"><div class="sec-inner">';
                if ($tbl_title) {
                    echo '<div class="sec-title">' . htmlspecialchars($tbl_title) . '</div>';
                }
                echo '<div class="table-wrap"><table class="custom-table">';

                if (!empty($columns)) {
                    echo '<thead><tr>';
                    foreach ($columns as $col) {
                        echo '<th>' . apply_placeholders($col, $name, $in_city, $price_val, $dist_val, $time_val, $settings) . '</th>';
                    }
                    echo '</tr></thead>';
                }

                if (!empty($rows) && is_array($rows)) {
                    echo '<tbody>';
                    foreach ($rows as $row) {
                        echo '<tr>';
                        foreach ($row as $i => $cell) {
                            $is_last = ($i === array_key_last($row));
                            $processed = apply_placeholders($cell, $name, $in_city, $price_val, $dist_val, $time_val, $settings);
                            echo '<td>' . ($is_last ? "<strong>{$processed}</strong>" : $processed) . '</td>';
                        }
                        echo '</tr>';
                    }
                    echo '</tbody>';
                }
                echo '</table></div></div></section>';
            }
            elseif ($type == 'faq') {
                $faq_title = apply_placeholders($content['title'] ?? 'FAQ', $name, $in_city, $price_val, $dist_val, $time_val, $settings);
                $faq_items_raw = $content['items'] ?? [];
                $faq_items = [];

                foreach ($faq_items_raw as $fi) {
                    $faq_items[] = [
                        'q' => apply_placeholders($fi['q'], $name, $in_city, $price_val, $dist_val, $time_val, $settings),
                        'a' => apply_placeholders($fi['a'], $name, $in_city, $price_val, $dist_val, $time_val, $settings)
                    ];
                }

                if (!empty($faq_items)) {
                    echo '<section class="sec"><div class="sec-inner">';
                    echo '<div class="sec-title">' . htmlspecialchars($faq_title) . '</div>';
                    echo '<div class="faq">';
                    foreach ($faq_items as $fi) {
                        echo '<div class="faq-item">';
                        echo '<button class="faq-q">' . htmlspecialchars($fi['q']) . '<span class="faq-icon">+</span></button>';
                        echo '<div class="faq-a">' . $fi['a'] . '</div>';
                        echo '</div>';
                    }
                    echo '</div></div></section>';
                }
            }
        }
    }
}
?>
