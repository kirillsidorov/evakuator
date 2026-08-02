<?php
// components/theme_functions.php
// require_smart() подключает партиал из /components/
// Обновлено 2: render_structured_content группирует p/h2/h3/li в одну <section>,
// чтобы убрать визуальные "полоски" между блоками. Разрыв секции — только
// перед/после highlight, cta, table, faq (у них свой контрастный фон).

// Функция подключения партиала из /components/
if (!function_exists('require_smart')) {
    function require_smart($filename) {
        global $db, $page, $page_type, $slug, $settings, $h1, $breadcrumbs,
               $loc, $custom_h1, $custom_p, $custom_bg, $custom_btn, $loc_map,
               $title, $description, $dist_val, $time_val, $price_val,
               $city_val, $in_city_val, $custom_btn_text, $custom_btn_link,
               $faq_title, $faq_items, $blocks, $attrs, $lang;

        $components_path = $_SERVER['DOCUMENT_ROOT'] . '/components/';

        if (file_exists($components_path . $filename)) {
            include $components_path . $filename;
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
//    p / h2 / h3 / li идут ПОДРЯД внутри одной открытой <section>,
//    чтобы не давать визуальных "швов" между соседними блоками.
//    highlight / cta / table / faq всегда закрывают текущую секцию
//    и рисуют свой отдельный блок (у них контрастный фон/полоса).
if (!function_exists('render_structured_content')) {
    function render_structured_content($items) {
        global $loc, $price_val, $dist_val, $time_val, $settings, $lang;

        if (!is_array($items)) return;

        $section_open = false;

        foreach ($items as $index => $item) {
            $type = $item['type'] ?? '';
            $content = $item['content'] ?? null;
            $name    = $loc['name'] ?? '';
            $in_city = $loc['in_city'] ?? '';

            $is_flow_type = in_array($type, ['h2', 'p', 'li', 'h3']);

            // Открываем общую секцию перед "обычным" блоком, если она ещё не открыта
            if ($is_flow_type && !$section_open) {
                echo '<section class="sec"><div class="sec-inner">';
                $section_open = true;
            }
            // Закрываем общую секцию перед "особым" блоком, если она была открыта
            if (!$is_flow_type && $section_open) {
                echo '</div></section>';
                $section_open = false;
            }

            if ($type == 'h2') {
                $h2_text = apply_placeholders($content, $name, $in_city, $price_val, $dist_val, $time_val, $settings);
                echo '<div class="sec-title">' . $h2_text . '</div>';
            }
            elseif ($type == 'p') {
                // content может быть строкой ИЛИ массивом строк (несколько абзацев в одном блоке)
                echo '<div class="text-block">';
                if (is_array($content)) {
                    foreach ($content as $para) {
                        $p_text = apply_placeholders($para, $name, $in_city, $price_val, $dist_val, $time_val, $settings);
                        echo '<p>' . $p_text . '</p>';
                    }
                } else {
                    $p_text = apply_placeholders($content, $name, $in_city, $price_val, $dist_val, $time_val, $settings);
                    echo $p_text;
                }
                echo '</div>';
            }
            elseif ($type == 'li') {
                echo '<ul class="num-list">';
                // Подстраховка: если случайно прилетит строка — оборачиваем в массив
                $items_list = is_array($content) ? $content : [$content];
                foreach ($items_list as $i => $li) {
                    $li_text = apply_placeholders($li, $name, $in_city, $price_val, $dist_val, $time_val, $settings);
                    echo '<li><div class="num">' . ($i + 1) . '</div><span>' . $li_text . '</span></li>';
                }
                echo '</ul>';
            }
            elseif ($type == 'h3') {
                if (empty($content) && isset($item['h'])) {
                    $content = ['h' => $item['h'], 'p' => $item['p'] ?? '', 'image' => $item['image'] ?? ''];
                }

                if (is_array($content)) {
                    $h3_text = apply_placeholders($content['h'] ?? '', $name, $in_city, $price_val, $dist_val, $time_val, $settings);
                    $h3_p    = apply_placeholders($content['p'] ?? '', $name, $in_city, $price_val, $dist_val, $time_val, $settings);
                    echo '<div class="sec-title" style="font-size:clamp(22px,5vw,30px)">' . $h3_text . '</div>';
                    if ($h3_p) {
                        echo '<div class="text-block">' . $h3_p . '</div>';
                    }
                    if (!empty($content['image'])) {
                        echo '<img src="' . $content['image'] . '" alt="' . htmlspecialchars(strip_tags($h3_text)) . '" loading="lazy" style="border-radius:12px;margin-top:20px;box-shadow:0 10px 30px rgba(0,0,0,.08)">';
                    }
                } else {
                    $h3_text = apply_placeholders($content, $name, $in_city, $price_val, $dist_val, $time_val, $settings);
                    echo '<div class="sec-title" style="font-size:clamp(22px,5vw,30px)">' . $h3_text . '</div>';
                }
            }
            elseif ($type == 'highlight') {
                $hl_text = apply_placeholders($content ?? '', $name, $in_city, $price_val, $dist_val, $time_val, $settings);
                echo '<section class="sec" style="background:#f8f8f6"><div class="sec-inner">';
                echo '<div class="text-block" style="background:#fff;border-radius:12px;padding:24px;border-left:4px solid #e9ff00">' . $hl_text . '</div>';
                echo '</div></section>';
            }
            elseif ($type == 'cta') {
                $default_text = ($lang == 'ua')
                    ? 'Прорахуйте вартість замовлення прямо зараз!'
                    : 'Просчитайте стоимость заказа прямо сейчас!';

                $default_btn = ($lang == 'ua')
                    ? 'Викликати евакуатор ' . $in_city
                    : 'Вызвать эвакуатор ' . $in_city;

                $cta_text_raw = is_array($content) ? ($content['text'] ?? $default_text) : $default_text;
                $cta_btn_raw  = is_array($content) ? ($content['btn']  ?? $default_btn)  : $default_btn;

                $cta_text = apply_placeholders($cta_text_raw, $name, $in_city, $price_val, $dist_val, $time_val, $settings);
                $cta_btn  = apply_placeholders($cta_btn_raw,  $name, $in_city, $price_val, $dist_val, $time_val, $settings);

                $tel = $settings['tel_one_link'] ?? '';

                echo '<div class="band">';
                echo '<div class="band-inner">';
                echo '<div class="band-title">' . $cta_text . '</div>';
                echo '<a href="tel:' . htmlspecialchars($tel) . '" class="band-cta">';
                echo '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.55 11a19.79 19.79 0 01-3.07-8.67A2 2 0 012.44 0h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 7.91a16 16 0 006.18 6.18l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>';
                echo $cta_btn;
                echo '</a>';
                echo '</div>';
                echo '</div>';
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

        // Закрываем секцию, если она осталась открытой после последнего блока
        if ($section_open) {
            echo '</div></section>';
        }
    }
}
?>
