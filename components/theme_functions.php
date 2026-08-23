<?php
// components/theme_functions.php
// Обновлено 3 (SEO-фикс):
//   - type h2  -> реальный <h2 class="sec-title">      (было <div class="sec-title">)
//   - type h3  -> реальный <h3 class="sec-title sec-title--sm">
//   - заголовки table и faq -> <h2 class="sec-title">
//   - инлайн font-size у h3 убран, вынесен в класс .sec-title--sm
// Логика группировки секций не менялась.

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
            // Подача за город и базовый тариф — нужны в мета-описаниях,
            // чтобы показывать структуру цены, а не итог маршрута.
            $text = str_replace('{price_feed}', $settings['price_feed'] ?? '', $text);
            $text = str_replace('{price_car}',  $settings['price_car'] ?? '', $text);
        }

        return $text;
    }
}

// 3.1 ЕДИНОЕ ОПРЕДЕЛЕНИЕ ЯЗЫКА
//     Раньше логика жила в трёх местах — config.php, router.php, header.php —
//     и в двух из них была написана как strpos($uri, '/ua') === 0, без слэша.
//     Из-за этого страница вроде /uaz-evakuator считалась украинской.
if (!function_exists('detect_lang')) {
    function detect_lang($uri) {
        $path = parse_url((string)$uri, PHP_URL_PATH);
        if ($path === false || $path === null || $path === '') $path = '/';
        return preg_match('~^/ua(/|$)~', $path) ? 'ua' : 'ru';
    }
}

// 3.2 URL АЛЬТЕРНАТИВНОЙ ЯЗЫКОВОЙ ВЕРСИИ (или null, если её нет)
//     Кнопка UA/RU в menu.php и hreflang в header.php раньше строили
//     '/ua/' . slug вслепую, не проверяя, заведена ли вторая версия.
//     Кнопка вела в 404, а hreflang на 404 заставляет Google отбросить
//     всю языковую связку целиком — выпадают обе версии.
if (!function_exists('alt_lang_url')) {
    function alt_lang_url($db, $page, $lang) {
        if (!is_array($page)) return null;
        if (($page['type'] ?? '') === '404') return null;

        $slug = (string)($page['slug'] ?? '');
        if ($slug === '') return null;

        $alt = ($lang === 'ua') ? 'ru' : 'ua';

        if (!is_object($db) || !method_exists($db, 'has')) return null;
        if (!$db->has('pages', ['slug' => $slug, 'lang' => $alt])) return null;

        $prefix = ($alt === 'ua') ? '/ua/' : '/';
        if ($slug === 'home') return ($alt === 'ua') ? '/ua/' : '/';

        return $prefix . $slug;
    }
}

// 3.5 ЕДИНЫЙ РАСЧЁТ ЦЕНЫ
//     Раньше логика жила в двух местах — в router.php и в hub_template.php —
//     и порядок приоритетов там отличался. Из-за этого цена в таблице
//     хаба могла не совпадать с ценой на самой странице направления.
//     Теперь оба места зовут эту функцию.
if (!function_exists('calc_page_price')) {
    function calc_page_price($attrs, $location_type, $settings) {
        $per_km = (int)($settings['price_km'] ?? 30);
        $base   = (int)($settings['price_feed'] ?? 1000);
        $factor = (float)($settings['price_return_factor'] ?? 2);

        // 1. Ручная цена всегда выигрывает
        if (!empty($attrs['price'])) {
            return $attrs['price'];
        }
        // 2. Район города — фиксированный тариф
        if ($location_type === 'district') {
            return $settings['price_car'] ?? 1000;
        }
        // 3. Межгород — расстояние * тариф * коэффициент обратной дороги + подача
        if (!empty($attrs['distance'])) {
            return round(((float)$attrs['distance'] * $per_km * $factor) + $base, -2);
        }
        // 4. Фолбэк
        return $settings['price_car'] ?? 1000;
    }
}

// 4. Рендеринг структурированных блоков (JSON → HTML)
//    p / h2 / h3 / li идут ПОДРЯД внутри одной открытой <section>.
//    highlight / cta / table / faq всегда закрывают текущую секцию.
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
                // >>> SEO: реальный заголовок вместо div
                echo '<h2 class="sec-title">' . $h2_text . '</h2>';
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
                    // >>> SEO: реальный заголовок, размер через класс, не инлайном
                    echo '<h3 class="sec-title sec-title--sm">' . $h3_text . '</h3>';
                    if ($h3_p) {
                        echo '<div class="text-block">' . $h3_p . '</div>';
                    }
                    if (!empty($content['image'])) {
                        echo '<img src="' . $content['image'] . '" alt="' . htmlspecialchars(strip_tags($h3_text)) . '" loading="lazy" style="border-radius:12px;margin-top:20px;box-shadow:0 10px 30px rgba(0,0,0,.08)">';
                    }
                } else {
                    $h3_text = apply_placeholders($content, $name, $in_city, $price_val, $dist_val, $time_val, $settings);
                    echo '<h3 class="sec-title sec-title--sm">' . $h3_text . '</h3>';
                }
            }
            elseif ($type == 'highlight') {
                $hl_text = apply_placeholders($content ?? '', $name, $in_city, $price_val, $dist_val, $time_val, $settings);
                echo '<section class="sec" style="background:#f8f8f6"><div class="sec-inner">';
                echo '<div class="text-block" style="background:#fff;border-radius:12px;padding:24px;border-left:4px solid #e9ff00">' . $hl_text . '</div>';
                echo '</div></section>';
            }
            elseif ($type == 'notice') {
                $n_title = '';
                $n_text  = '';

                if (is_array($content)) {
                    $n_title = trim((string)($content['title'] ?? ''));
                    $n_text  = trim((string)($content['text']  ?? ''));
                } else {
                    $n_text  = trim((string)$content);
                }

                if ($n_text !== '') {
                    $n_title = apply_placeholders($n_title, $name, $in_city, $price_val, $dist_val, $time_val, $settings);
                    $n_text  = apply_placeholders($n_text,  $name, $in_city, $price_val, $dist_val, $time_val, $settings);

                    echo '<section class="sec"><div class="sec-inner">';
                    echo '<aside class="notice notice--warn" role="note">';
                    if ($n_title !== '') {
                        echo '<p class="notice__title">' . htmlspecialchars($n_title) . '</p>';
                    }
                    echo '<p class="notice__text">' . $n_text . '</p>';
                    echo '</aside>';
                    echo '</div></section>';
                }
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
                echo '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.55 11a19.79 19.79 0 01-3.07-8.67A2 2 0 012.44 0h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 7.91a16 16 0 006.18 6.18l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>';
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
                    // >>> SEO: заголовок таблицы тоже реальный h2
                    echo '<h2 class="sec-title">' . htmlspecialchars($tbl_title) . '</h2>';
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
                    // >>> SEO: заголовок FAQ тоже реальный h2
                    echo '<h2 class="sec-title">' . htmlspecialchars($faq_title) . '</h2>';
                    echo '<div class="faq">';
                    foreach ($faq_items as $fi) {
                        echo '<div class="faq-item">';
                        echo '<button class="faq-q" type="button" aria-expanded="false">' . htmlspecialchars($fi['q']) . '<span class="faq-icon" aria-hidden="true">+</span></button>';
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
