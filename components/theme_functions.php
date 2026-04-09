if (!function_exists('render_structured_content')) {
    function render_structured_content($items) {
        global $loc, $price_val, $dist_val, $time_val, $settings;

        if (!is_array($items)) return;
        
        // Открываем общую секцию для текста, если есть текстовые блоки
        $has_text = false;
        
        foreach ($items as $index => $item) {
            $type = $item['type'];
            $content = $item['content'];

            // ЗАГОЛОВКИ H2
            if ($type == 'h2') {
                $h2_text = apply_placeholders($content, $loc['name'] ?? '', $loc['in_city'] ?? '', $price_val, $dist_val, $time_val, $settings);
                echo '<section class="sec" style="background:#f8f8f6;"><div class="sec-inner">';
                echo '<div class="sec-title">' . htmlspecialchars($h2_text) . '</div>';
                echo '</div></section>';
            } 
            // АБЗАЦЫ ТЕКСТА
            elseif ($type == 'p') {
                echo '<section class="sec"><div class="sec-inner"><div class="text-cols"><div class="text-block"><p>';
                echo apply_placeholders($content, $loc['name'] ?? '', $loc['in_city'] ?? '', $price_val, $dist_val, $time_val, $settings);
                echo '</p></div></div></div></section>';
            } 
            // СПИСКИ (Переделано под новые цифры)
            elseif ($type == 'li') {
                echo '<section class="sec"><div class="sec-inner"><ul class="num-list">';
                if (is_array($content)) { 
                    $counter = 1;
                    foreach ($content as $li) {
                        $text = apply_placeholders($li, $loc['name'] ?? '', $loc['in_city'] ?? '', $price_val, $dist_val, $time_val, $settings);
                        echo '<li><div class="num">' . $counter . '</div><span>' . $text . '</span></li>';
                        $counter++;
                    }
                }
                echo '</ul></div></section>';
            }
            // FAQ БЛОК
            elseif ($type == 'faq') {
                $faq_title = apply_placeholders($content['title'] ?? 'FAQ', $loc['name'] ?? '', $loc['in_city'] ?? '', $price_val, $dist_val, $time_val, $settings);
                $faq_items_raw = $content['items'] ?? [];
                
                echo '<section class="sec" style="background:#f8f8f6"><div class="sec-inner">';
                echo '<div class="sec-title">' . htmlspecialchars($faq_title) . '</div>';
                echo '<div class="faq">';
                
                foreach ($faq_items_raw as $faq_item) {
                    $q = apply_placeholders($faq_item['q'], $loc['name'] ?? '', $loc['in_city'] ?? '', $price_val, $dist_val, $time_val, $settings);
                    $a = apply_placeholders($faq_item['a'], $loc['name'] ?? '', $loc['in_city'] ?? '', $price_val, $dist_val, $time_val, $settings);
                    
                    echo '<div class="faq-item">';
                    echo '<button class="faq-q">' . htmlspecialchars($q) . '<span class="faq-icon">+</span></button>';
                    echo '<div class="faq-a">' . $a . '</div>';
                    echo '</div>';
                }
                echo '</div></div></section>';
            }
            // Таблицы и другие блоки нужно будет тоже подогнать под простую верстку, 
            // убрав классы container-table, cid-s1LSywcbcb и т.д.
        }
    }
}