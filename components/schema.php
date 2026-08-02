<?php
/**
 * Універсальна мікророзмітка JSON-LD
 * Використовує дані з глобальної змінної $page
 */

// !!! ВАЖНО: Объявляем глобальные переменные, чтобы видеть их внутри include
global $page, $settings, $loc, $lang;

// 1. Словник перекладів та даних
$labels = [
    'ru' => [
        'main_name'  => "Эвакуатор Харьков",
        'region'     => "Харьковская область",
        'locality'   => "Харьков",
        'street'     => "ул. Большая Панасовская, 14",
        'main_url'   => "https://evakuator-kharkov.kh.ua/",
        'price_from' => "от",
        'evak'       => "Эвакуатор ",
        'blog_name'  => "Полезные статьи и новости — Эвакуатор Харьков"
    ],
    'ua' => [
        'main_name'  => "Евакуатор Харків",
        'region'     => "Харківська область",
        'locality'   => "Харків",
        'street'     => "вул. Велика Панасівська, 14",
        'main_url'   => "https://evakuator-kharkov.kh.ua/ua/",
        'price_from' => "від",
        'evak'       => "Евакуатор ",
        'blog_name'  => "Корисні статті та новини — Евакуатор Харків"
    ]
];

// Определяем язык и базовые переменные
$current_lang = $lang ?? 'ru';
$t = $labels[$current_lang];
$logo = "https://evakuator-kharkov.kh.ua/assets/images/2-1000x500.webp";
$current_url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

// !!! БЕРЕМ ТИП СТРАНИЦЫ НАПРЯМУЮ ИЗ БАЗЫ !!!
// Если $page не существует (например, прямой вызов файла), ставим 'home'
$current_type = $page['type'] ?? 'home'; 

// 2. Генерація схеми

// --- ВАРИАНТ 1: СТАТЬЯ БЛОГА ---
if ($current_type == "articles") {
    $schema = [
        "@context" => "https://schema.org",
        "@type" => "BlogPosting", // Или Article
        "mainEntityOfPage" => [
            "@type" => "WebPage",
            "@id" => $current_url
        ],
        "headline" => $page['breadcrumb_title'] ?? $t['main_name'],
        "description" => $page['meta_description'] ?? "",
        "image" => !empty($page['hero_image']) ? $page['hero_image'] : $logo,
        "author" => [
            "@type" => "Organization",
             "name" => $t['main_name'],
             "url" => $t['main_url']
        ],
        "publisher" => [
            "@type" => "Organization",
            "name" => $t['main_name'],
            "logo" => [
                "@type" => "ImageObject",
                "url" => "https://evakuator-kharkov.kh.ua/assets/images/icons8-taxi-service-64-64x64.webp"
            ]
        ],
        "datePublished" => (!empty($page['date']) ? $page['date'] : date('Y-m-d')),
        "dateModified"  => (!empty($page['date']) ? $page['date'] : date('Y-m-d'))
    ];

// --- ВАРИАНТ 2: АРХИВ БЛОГА (СПИСОК) ---
} else if ($current_type == "archive") {
    $schema = [
        "@context" => "https://schema.org",
        "@type" => "CollectionPage",
        "name" => $t['blog_name'],
        "url" => $current_url,
        "description" => $page['meta_description'] ?? ""
    ];

// --- ВАРИАНТ 3: БИЗНЕС (Главная, Услуги, Цены, Контакты) ---
// Сюда же можно добавить 'home', если в базе тип главной - home
} else if (in_array($current_type, ["main", "home", "service", "prices", "contacts"])) {
    $schema = [
        "@context" => "https://schema.org",
        "@type" => "AutomotiveBusiness",
        "name" => $t['main_name'],
        "image" => $logo,
        "url" => $current_url,
        "telephone" => [$settings['tel_one_link'], $settings['tel_two_link']],
        "priceRange" => $t['price_from'] . " " . ($settings['price_car'] ?? '1000') . " грн",
        "address" => [
            "@type" => "PostalAddress",
            "streetAddress" => $t['street'],
            "addressLocality" => $t['locality'],
            "addressRegion" => $t['region'],
            "postalCode" => "61052",
            "addressCountry" => "UA"
        ],
        "aggregateRating" => [
            "@type" => "AggregateRating",
            "ratingValue" => $settings['rating_value'] ?? "4.7",
            "reviewCount" => $settings['rating_count'] ?? "57",
            "bestRating" => "5",
            "worstRating" => "1"
        ]
    ];

// --- ВАРИАНТ 4: ЛОКАЦИИ (По умолчанию для городов) ---
} else {
    // Если это город или район (locations, district)
    $location_name = $loc['name'] ?? $t['locality'];
    
    $schema = [
        "@context" => "https://schema.org",
        "@type" => "AutomotiveBusiness",
        "name" => $t['evak'] . $location_name,
        "image" => $logo,
        "url" => $current_url,
        "parentOrganization" => [
            "@type" => "AutomotiveBusiness",
            "name" => $t['main_name'],
            "url" => $t['main_url']
        ],
        "telephone" => [$settings['tel_one_link'], $settings['tel_two_link']],
        "priceRange" => $t['price_from'] . " " . ($settings['price_car'] ?? '1000') . " грн",
        "address" => [
            "@type" => "PostalAddress",
            "addressLocality" => $location_name,
            "addressRegion" => $t['region'],
            "addressCountry" => "UA"
        ],
        "aggregateRating" => [
            "@type" => "AggregateRating",
            "ratingValue" => $settings['rating']['value'] ?? "4.7",
            "reviewCount" => $settings['rating']['count'] ?? "57",
            "bestRating" => "5",
            "worstRating" => "1"
        ]
    ];
}

// Вивід
echo '<script type="application/ld+json">' . PHP_EOL;
echo json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
echo PHP_EOL . '</script>';
?>