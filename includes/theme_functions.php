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
//    Группирует h2 + последующие p/li в одну секцию
if (!function_exists('render_structured_content')) {
    function render_structured_content($items) {
        global $loc, $price_val, $dist_val, $time_val, $settings, $lang;

        if (!is_array($items)) return;

        $name    = $loc['name'] ?? '';
        $in_city = $loc['in_city'] ?? '';
        $ph = function($text) use ($name, $in_city, $price_val, $dist_val, $time_val, $settings) {
            return apply_placeholders($text, $name, $in_city, $price_val, $dist_val, $time_val, $settings);
        };

        // Группируем: h2 начинает новую группу, p/li присоединяются к текущей
        $groups = [];
        $current = null;

        foreach ($items as $item) {
            $type = $item['type'];

            // Эти типы всегда отдельная секция
            if (in_array($type, ['table', 'faq', 'highlight'])) {
                if ($current) { $groups[] = $current; $current = null; }
                $groups[] = ['standalone' => true, 'item' => $item];
                continue;
            }

            // h2 или h3 начинает новую группу
            if ($type == 'h2' || $type == 'h3') {
                if ($current) { $groups[] = $current; }
                $current = ['heading' => $item, 'body' => []];
                continue;
            }

            // p и li присоединяются к текущей группе
            if ($type == 'p' || $type == 'li') {
                if ($current) {
                    $current['body'][] = $item;
                } else {
                    // p/li без предшествующего заголовка — отдельная группа
                    $current = ['heading' => null, 'body' => [$item]];
                }
                continue;
            }
        }
        if ($current) { $groups[] = $current; }

        // Рендерим группы
        foreach ($groups as $gi => $group) {

            // Standalone блоки (table, faq, highlight)
            if (!empty($group['standalone'])) {
                $item = $group['item'];
                $type = $item['type'];
                $content = $item['content'];

                if ($type == 'table') {
                    $tbl_title = $ph($item['title'] ?? '');
                    $columns   = $item['columns'] ?? [];
                    $rows      = $content;
                    $bg = ($gi % 2 == 0) ? '' : ' style="background:#f8f8f6"';
                    echo "<section class=\"sec\"{$bg}><div class=\"sec-inner\">";
                    if ($tbl_title) echo '<h2 class="sec-title">' . htmlspecialchars($tbl_title) . '</h2>';
                    echo '<div class="table-wrap"><table class="custom-table">';
                    if (!empty($columns)) {
                        echo '<thead><tr>';
                        foreach ($columns as $col) echo '<th>' . $ph($col) . '</th>';
                        echo '</tr></thead>';
                    }
                    if (!empty($rows) && is_array($rows)) {
                        echo '<tbody>';
                        foreach ($rows as $row) {
                            echo '<tr>';
                            foreach ($row as $i => $cell) {
                                $is_last = ($i === array_key_last($row));
                                $val = $ph($cell);
                                echo '<td>' . ($is_last ? "<strong>{$val}</strong>" : $val) . '</td>';
                            }
                            echo '</tr>';
                        }
                        echo '</tbody>';
                    }
                    echo '</table></div></div></section>';
                }
                elseif ($type == 'faq') {
                    $faq_title = $ph($content['title'] ?? 'FAQ');
                    $faq_items_raw = $content['items'] ?? [];
                    $faq_items = [];
                    foreach ($faq_items_raw as $fi) {
                        $faq_items[] = ['q' => $ph($fi['q']), 'a' => $ph($fi['a'])];
                    }
                    if (!empty($faq_items)) {
                        echo '<section class="sec" style="background:#f8f8f6"><div class="sec-inner">';
                        echo '<h2 class="sec-title">' . htmlspecialchars($faq_title) . '</h2>';
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
                elseif ($type == 'highlight') {
                    $hl_text = $ph($content ?? '');
                    echo '<section class="sec" style="background:#f8f8f6"><div class="sec-inner">';
                    echo '<div class="text-block" style="background:#fff;border-radius:12px;padding:24px;border-left:4px solid #e9ff00">' . $hl_text . '</div>';
                    echo '</div></section>';
                }
                continue;
            }

            // Группа: heading + body
            $heading = $group['heading'] ?? null;
            $body    = $group['body'] ?? [];
            $bg = ($gi % 2 == 0) ? '' : ' style="background:#f8f8f6"';

            echo "<section class=\"sec\"{$bg}><div class=\"sec-inner\">";

            // Заголовок
            if ($heading) {
                $h_type = $heading['type'];
                $h_content = $heading['content'];

                if ($h_type == 'h2') {
                    echo '<h2 class="sec-title">' . $ph($h_content) . '</h2>';
                }
                elseif ($h_type == 'h3') {
                    if (is_array($h_content)) {
                        echo '<h3 class="sec-title" style="font-size:clamp(22px,5vw,30px)">' . $ph($h_content['h'] ?? '') . '</h3>';
                        if (!empty($h_content['image'])) {
                            echo '<img src="' . $h_content['image'] . '" alt="' . htmlspecialchars(strip_tags($ph($h_content['h'] ?? ''))) . '" loading="lazy" style="border-radius:12px;margin-top:20px;box-shadow:0 10px 30px rgba(0,0,0,.08)">';
                        }
                    } else {
                        echo '<h3 class="sec-title" style="font-size:clamp(22px,5vw,30px)">' . $ph($h_content) . '</h3>';
                    }
                }
            }

            // Тело
            if (!empty($body)) {
                echo '<div class="text-block">';
                foreach ($body as $b) {
                    if ($b['type'] == 'p') {
                        echo '<p>' . $ph($b['content']) . '</p>';
                    }
                    elseif ($b['type'] == 'li' && is_array($b['content'])) {
                        echo '<ul class="num-list" style="margin-top:16px">';
                        foreach ($b['content'] as $i => $li) {
                            echo '<li><div class="num">' . ($i + 1) . '</div><span>' . $ph($li) . '</span></li>';
                        }
                        echo '</ul>';
                    }
                }
                echo '</div>';
            }

            echo '</div></section>';
        }
    }
}
?>
