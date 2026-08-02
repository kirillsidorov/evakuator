<?php
// edit_page.php — Редактор страницы (обновлённый)
// Сессия 24 часа
ini_set('session.gc_maxlifetime', 86400);
session_set_cookie_params(86400);
session_start();
require_once 'db.php';

if (!isset($_SESSION['logged_in'])) die("Доступ запрещен");

$id = $_GET['id'] ?? null;
$page = null;
$content_block_id = null;
$current_content = "";
$attrs = [];

// Список стандартных include-блоков (порядок = sort_order по умолчанию)
$available_includes = [
    'route_seo_block.php' => ['label' => '📍 SEO-блок маршрута (расстояние, время)', 'default_sort' => 7],
    '3_steps_block.php'   => ['label' => '🔢 3 шага заказа + CTA',                   'default_sort' => 10],
    'why_we_block.php'    => ['label' => '⭐ Почему выбирают нас',                     'default_sort' => 20],
    'testimonials.php'    => ['label' => '💬 Отзывы клиентов',                         'default_sort' => 30],
    'maps.php'            => ['label' => '🗺 Карта Google',                             'default_sort' => 35],
    'contacts_block.php'  => ['label' => '📞 Контакты',                                'default_sort' => 40],
    'faq_block.php'       => ['label' => '❓ FAQ (из контента)',                        'default_sort' => 45],
];

// === 1. ЗАГРУЗКА ДАННЫХ ===
$active_includes = []; // какие include-блоки уже подключены

if ($id) {
    $page = $db->get("pages", "*", ["id" => $id]);
    if (!empty($page['attributes'])) {
        $attrs = json_decode($page['attributes'], true) ?: [];
    }

    // Загружаем контентный блок (text или structured_content)
    $content_block = $db->get("content_blocks", "*", [
        "page_id" => $id,
        "block_type" => ["text", "structured_content"],
        "ORDER" => ["sort_order" => "ASC"]
    ]);
    if ($content_block) {
        $content_block_id = $content_block['id'];
        $current_content = $content_block['content'];
        if ($content_block['block_type'] == 'structured_content') {
            $json_arr = json_decode($current_content, true);
            if ($json_arr) $current_content = json_encode($json_arr, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
    }

    // Загружаем активные include-блоки
    $inc_blocks = $db->select("content_blocks", "*", [
        "page_id" => $id,
        "block_type" => "include"
    ]);
    foreach ($inc_blocks as $ib) {
        $active_includes[$ib['block_path']] = $ib['id'];
    }
}

// === 2. СОХРАНЕНИЕ ===
if (isset($_POST['save_page'])) {

    // Атрибуты (JSON)
    $new_attrs = [
        "location_type" => $_POST['location_type'] ?? 'city',
        "in_city"  => $_POST['in_city'] ?? '',
        "maps"     => $_POST['map_url'] ?? '',
        "price"    => $_POST['attr_price'] ?? '',
        "distance" => $_POST['attr_distance'] ?? '',
        "time"     => $_POST['attr_time'] ?? '',
    ];
    if (!empty($attrs)) {
        $new_attrs = array_merge($attrs, $new_attrs);
    }

    // Основные данные страницы
    $data = [
        "lang"             => $_POST['lang'],
        "slug"             => $_POST['slug'],
        "type"             => $_POST['page_type'],
        "location_type"    => $_POST['location_type'] ?? 'city',
        "h1"               => $_POST['h1'],
        "breadcrumb_title" => $_POST['breadcrumb_title'],
        "h1_type"          => $_POST['h1_type'],
        "hero_image"       => $_POST['hero_image'],
        "custom_p"         => $_POST['custom_p'],
        "custom_btn"       => $_POST['custom_btn'],
        "meta_title"       => $_POST['meta_title'],
        "meta_description" => $_POST['meta_desc'],
        "attributes"       => json_encode($new_attrs, JSON_UNESCAPED_UNICODE),
        "parent_id"        => !empty($_POST['parent_id']) ? $_POST['parent_id'] : null,
        "date"             => !empty($_POST['date']) ? $_POST['date'] : null
    ];

    if ($id) {
        $db->update("pages", $data, ["id" => $id]);
    } else {
        $db->insert("pages", $data);
        $id = $db->id();
    }

    // --- Сохранение контента ---
    $new_content = $_POST['content'] ?? '';
    $block_type = 'text';
    $trimmed = trim($new_content);
    if (strpos($trimmed, '[') === 0 && json_decode($trimmed)) {
        $block_type = 'structured_content';
    }

    if ($content_block_id) {
        if (!empty($trimmed)) {
            $db->update("content_blocks", [
                "content" => $new_content,
                "block_type" => $block_type
            ], ["id" => $content_block_id]);
        } else {
            $db->delete("content_blocks", ["id" => $content_block_id]);
        }
    } elseif (!empty($trimmed)) {
        $db->insert("content_blocks", [
            "page_id"    => $id,
            "block_type" => $block_type,
            "content"    => $new_content,
            "sort_order"  => 5
        ]);
    }

    // --- Сохранение include-блоков (чекбоксы) ---
    $posted_includes = $_POST['includes'] ?? [];

    // Текущие include-блоки в БД
    $existing = $db->select("content_blocks", ["id", "block_path"], [
        "page_id" => $id,
        "block_type" => "include"
    ]);
    $existing_map = [];
    foreach ($existing as $e) {
        $existing_map[$e['block_path']] = $e['id'];
    }

    // Добавляем новые
    foreach ($posted_includes as $inc_path) {
        if (!isset($existing_map[$inc_path])) {
            $sort = $available_includes[$inc_path]['default_sort'] ?? 50;
            $db->insert("content_blocks", [
                "page_id"    => $id,
                "block_type" => "include",
                "block_path" => $inc_path,
                "content"    => null,
                "sort_order" => $sort
            ]);
        }
    }

    // Удаляем снятые
    foreach ($existing_map as $path => $block_id) {
        if (!in_array($path, $posted_includes)) {
            $db->delete("content_blocks", ["id" => $block_id]);
        }
    }

    header("Location: edit_page.php?id=$id&saved=1");
    exit;
}

// Для списка родителей
$all_pages = $db->select("pages", ["id", "breadcrumb_title", "lang", "type"], [
    "ORDER" => ["lang" => "ASC", "breadcrumb_title" => "ASC"]
]);

// URL для предпросмотра
$preview_url = '';
if ($page) {
    $preview_url = ($page['lang'] == 'ua' ? '/ua/' : '/') . ($page['slug'] ?? '');
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактор: <?= htmlspecialchars($page['breadcrumb_title'] ?? 'Новая страница') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jodit@latest/es2021/jodit.fat.min.css" />
    <style>
        body { background: #f0f2f5; padding-bottom: 80px; }
        .editor-area { font-family: monospace; font-size: 13px; min-height: 500px; background: #fff; color: #333; border: 1px solid #ced4da; }
        .card { margin-bottom: 16px; }
        .card-header { font-weight: 600; font-size: 0.85rem; letter-spacing: 0.3px; }
        .form-label { font-weight: 500; font-size: 0.85rem; color: #495057; margin-bottom: 4px; }
        .hero-preview { height: 80px; background-size: cover; background-position: center; border-radius: 6px; margin-top: 8px; border: 1px solid #ddd; display: flex; align-items: center; justify-content: center; color: #fff; text-shadow: 0 1px 3px rgba(0,0,0,0.8); font-size: 12px; }
        .include-item { display: flex; align-items: center; gap: 8px; padding: 8px 12px; background: #f8f9fa; border-radius: 6px; margin-bottom: 4px; font-size: 14px; }
        .include-item:hover { background: #e9ecef; }
        .include-item input[type="checkbox"] { width: 18px; height: 18px; cursor: pointer; }
        .include-item label { cursor: pointer; margin: 0; flex: 1; }
        .toast-saved { position: fixed; top: 20px; right: 20px; z-index: 9999; }
    </style>
</head>
<body>

<?php if (isset($_GET['saved'])): ?>
<div class="toast-saved">
    <div class="alert alert-success alert-dismissible shadow" role="alert">
        <i class="fas fa-check-circle me-2"></i> Сохранено!
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
<?php endif; ?>

<nav class="navbar navbar-dark bg-dark mb-3 shadow-sm">
    <div class="container-fluid px-4">
        <span class="navbar-brand mb-0"><i class="fas fa-edit me-2"></i><?= $id ? 'Редактирование' : 'Новая страница' ?></span>
        <div>
            <a href="pages_manager.php" class="btn btn-outline-light btn-sm">
                <i class="fas fa-list me-1"></i>К списку
            </a>
            <?php if ($preview_url): ?>
            <a href="<?= $preview_url ?>" target="_blank" class="btn btn-primary btn-sm ms-2">
                <i class="fas fa-eye me-1"></i>Просмотр
            </a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="container-fluid px-4">
    <form method="post">
        <div class="row">

            <!-- ========== ЛЕВАЯ КОЛОНКА — Настройки ========== -->
            <div class="col-lg-4">

                <!-- 1. Основные -->
                <div class="card shadow-sm">
                    <div class="card-header bg-white text-primary"><i class="fas fa-cog me-1"></i> 1. Основные параметры</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <label class="form-label">Язык</label>
                                <select name="lang" class="form-select form-select-sm mb-2">
                                    <option value="ru" <?= ($page['lang'] ?? 'ru') == 'ru' ? 'selected' : '' ?>>🇷🇺 RU</option>
                                    <option value="ua" <?= ($page['lang'] ?? '') == 'ua' ? 'selected' : '' ?>>🇺🇦 UA</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Тип страницы</label>
                                <select name="page_type" class="form-select form-select-sm mb-2">
                                    <option value="locations" <?= ($page['type'] ?? '') == 'locations' ? 'selected' : '' ?>>📍 Локация</option>
                                    <option value="services"  <?= ($page['type'] ?? '') == 'services'  ? 'selected' : '' ?>>🛠 Услуга</option>
                                    <option value="articles"  <?= ($page['type'] ?? '') == 'articles'  ? 'selected' : '' ?>>📝 Статья</option>
                                    <option value="archive"   <?= ($page['type'] ?? '') == 'archive'   ? 'selected' : '' ?>>📚 Блог (архив)</option>
                                    <option value="hub"       <?= ($page['type'] ?? '') == 'hub'       ? 'selected' : '' ?>>🗂 Хаб</option>
                                </select>
                            </div>
                        </div>

                        <label class="form-label">URL (Slug)</label>
                        <div class="input-group input-group-sm mb-2">
                            <span class="input-group-text">/</span>
                            <input type="text" name="slug" class="form-control" value="<?= htmlspecialchars($page['slug'] ?? '') ?>">
                        </div>

                        <label class="form-label">Название в хлебных крошках</label>
                        <input type="text" name="breadcrumb_title" class="form-control form-control-sm mb-2" value="<?= htmlspecialchars($page['breadcrumb_title'] ?? '') ?>">

                        <label class="form-label">Родитель</label>
                        <select name="parent_id" class="form-select form-select-sm">
                            <option value="">— Нет (корень) —</option>
                            <?php foreach ($all_pages as $p): ?>
                                <option value="<?= $p['id'] ?>" <?= ($page['parent_id'] ?? '') == $p['id'] ? 'selected' : '' ?>>
                                    [<?= strtoupper($p['lang']) ?>] <?= htmlspecialchars($p['breadcrumb_title']) ?> (<?= $p['type'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- 2. Hero -->
                <div class="card shadow-sm">
                    <div class="card-header bg-white text-info"><i class="fas fa-image me-1"></i> 2. Hero (шапка)</div>
                    <div class="card-body">
                        <label class="form-label">Тип шапки</label>
                        <select name="h1_type" class="form-select form-select-sm mb-2">
                            <option value="standard" <?= ($page['h1_type'] ?? '') == 'standard' ? 'selected' : '' ?>>📍 Standard (H1+P+Btn+фон)</option>
                            <option value="service"  <?= ($page['h1_type'] ?? '') == 'service'  ? 'selected' : '' ?>>🛠 Service (H1+P+Btn+фон)</option>
                            <option value="simple"   <?= ($page['h1_type'] ?? '') == 'simple'   ? 'selected' : '' ?>>📝 Simple (H1, без фона)</option>
                        </select>

                        <label class="form-label">Заголовок H1</label>
                        <input type="text" name="h1" class="form-control form-control-sm fw-bold mb-2" value="<?= htmlspecialchars($page['h1'] ?? '') ?>">

                        <label class="form-label">Подзаголовок</label>
                        <textarea name="custom_p" class="form-control form-control-sm mb-2" rows="2"><?= htmlspecialchars($page['custom_p'] ?? '') ?></textarea>

                        <label class="form-label">Текст кнопки</label>
                        <input type="text" name="custom_btn" class="form-control form-control-sm mb-2" placeholder="Вызвать эвакуатор" value="<?= htmlspecialchars($page['custom_btn'] ?? '') ?>">

                        <label class="form-label">Фоновое изображение</label>
                        <input type="text" name="hero_image" class="form-control form-control-sm" value="<?= htmlspecialchars($page['hero_image'] ?? '') ?>" placeholder="/assets/images/...">
                        <?php if (!empty($page['hero_image'])): ?>
                            <div class="hero-preview" style="background-image:url('<?= $page['hero_image'] ?>')">Превью</div>
                        <?php endif; ?>

                        <label class="form-label mt-2">Дата (последнее обновление / выход статьи)</label>
                        <input type="date" name="date" class="form-control form-control-sm" value="<?= htmlspecialchars($page['date'] ?? '') ?>">
                        <small class="text-muted d-block">Для статей блога: будущая дата = статья выйдет в этот день (до него скрыта). Пусто = без даты.</small>
                    </div>
                </div>

                <!-- 3. SEO & Локация -->
                <div class="card shadow-sm">
                    <div class="card-header bg-white text-secondary"><i class="fas fa-search me-1"></i> 3. SEO & Локация</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <label class="form-label">Тип локации</label>
                                <select name="location_type" class="form-select form-select-sm mb-2">
                                    <option value="city"     <?= ($page['location_type'] ?? 'city') == 'city' ? 'selected' : '' ?>>Город</option>
                                    <option value="district" <?= ($page['location_type'] ?? '') == 'district' ? 'selected' : '' ?>>Район</option>
                                    <option value="region"   <?= ($page['location_type'] ?? '') == 'region' ? 'selected' : '' ?>>Область</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Склонение (где?)</label>
                                <input type="text" name="in_city" class="form-control form-control-sm mb-2" value="<?= htmlspecialchars($attrs['in_city'] ?? '') ?>" placeholder="в Чугуеве">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-4">
                                <label class="form-label">Дистанция, км</label>
                                <input type="number" name="attr_distance" class="form-control form-control-sm" value="<?= htmlspecialchars($attrs['distance'] ?? '') ?>" placeholder="150">
                            </div>
                            <div class="col-4">
                                <label class="form-label">Время в пути</label>
                                <input type="text" name="attr_time" class="form-control form-control-sm" value="<?= htmlspecialchars($attrs['time'] ?? '') ?>" placeholder="2.5 часа">
                            </div>
                            <div class="col-4">
                                <label class="form-label">Фикс. цена</label>
                                <input type="number" name="attr_price" class="form-control form-control-sm" value="<?= htmlspecialchars($attrs['price'] ?? '') ?>" placeholder="1200">
                            </div>
                        </div>

                        <label class="form-label mt-2">Google Maps (Embed URL)</label>
                        <input type="text" name="map_url" class="form-control form-control-sm mb-2" value="<?= htmlspecialchars($attrs['maps'] ?? '') ?>">

                        <hr class="my-2">

                        <label class="form-label">Meta Title</label>
                        <textarea name="meta_title" class="form-control form-control-sm mb-2" rows="2"><?= htmlspecialchars($page['meta_title'] ?? '') ?></textarea>

                        <label class="form-label">Meta Description</label>
                        <textarea name="meta_desc" class="form-control form-control-sm" rows="2"><?= htmlspecialchars($page['meta_description'] ?? '') ?></textarea>
                    </div>
                </div>

                <!-- 4. Стандартные блоки (чекбоксы) -->
                <div class="card shadow-sm">
                    <div class="card-header bg-white text-success"><i class="fas fa-puzzle-piece me-1"></i> 4. Стандартные блоки</div>
                    <div class="card-body p-2">
                        <?php foreach ($available_includes as $path => $info): ?>
                        <div class="include-item">
                            <input type="checkbox" name="includes[]" value="<?= $path ?>" id="inc_<?= md5($path) ?>"
                                <?= isset($active_includes[$path]) ? 'checked' : '' ?>>
                            <label for="inc_<?= md5($path) ?>"><?= $info['label'] ?></label>
                        </div>
                        <?php endforeach; ?>
                        <div class="text-muted small mt-2 px-2">
                            Отмеченные блоки автоматически подключаются к странице в заданном порядке.
                        </div>
                    </div>
                </div>

            </div>

            <!-- ========== ПРАВАЯ КОЛОНКА — Контент ========== -->
            <div class="col-lg-8">
                <div class="card shadow-sm" style="min-height:700px">
                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-file-alt me-1"></i> 5. Контент страницы</span>
                        <small class="text-white-50">HTML или JSON · Плейсхолдеры: {city} {price} {in_city} {tel1}</small>
                    </div>
                    <div class="card-body p-0">
                        <!-- Панель переключения режимов -->
                        <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom" style="background:#f8f9fa">
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-outline-secondary" id="mode-html">
                                    <i class="fas fa-paint-brush"></i> HTML (WYSIWYG)
                                </button>
                                <button type="button" class="btn btn-outline-secondary" id="mode-json">
                                    <i class="fas fa-code"></i> JSON (структура)
                                </button>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <button type="button" class="btn btn-sm btn-outline-info" id="validate-json" style="display:none">
                                    <i class="fas fa-check-circle"></i> Проверить JSON
                                </button>
                                <span id="json-status" class="small"></span>
                            </div>
                        </div>
                        <textarea name="content" class="form-control editor-area border-0 p-3"><?= htmlspecialchars($current_content) ?></textarea>
                    </div>
                    <div class="card-footer bg-light">
                        <button type="submit" name="save_page" class="btn btn-success btn-lg w-100 shadow">
                            <i class="fas fa-save me-2"></i>СОХРАНИТЬ
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jodit@latest/es2021/jodit.fat.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const area      = document.querySelector('.editor-area');
    const btnHtml   = document.getElementById('mode-html');
    const btnJson   = document.getElementById('mode-json');
    const btnCheck  = document.getElementById('validate-json');
    const status    = document.getElementById('json-status');
    let joditInstance = null;
    let currentMode = null; // 'html' | 'json'
 
    if (typeof Jodit === 'undefined') {
        console.error("Jodit не загрузился");
        return;
    }
 
    // ---- Инициализация WYSIWYG ----
    function initWysiwyg() {
        joditInstance = new Jodit(area, {
            height: 650,
            language: 'ru',
            toolbarSticky: true,
            toolbarStickyOffset: 56,
            cleanHTML: {
                fillEmptyParagraph: false,
                removeEmptyElements: false,
                replaceNBSP: false,
                allowTags: '*'
            },
            buttons: [
                'bold', 'italic', 'underline', '|',
                'h1', 'h2', 'h3', 'paragraph', '|',
                'ul', 'ol', '|',
                'link', 'image', 'table', '|',
                'align', '|',
                'source', 'fullsize', 'undo', 'redo'
            ],
            events: {
                // Если в WYSIWYG пастят что-то похожее на JSON — предлагаем переключиться
                paste: function(event) {
                    const clipboardData = event.clipboardData || window.clipboardData;
                    if (!clipboardData) return;
                    const text = clipboardData.getData('text/plain').trim();
                    if (text.startsWith('[') && looksLikeJson(text)) {
                        if (confirm('Похоже, вы вставляете JSON. Переключиться в JSON-режим? (В WYSIWYG структура потеряется)')) {
                            event.preventDefault();
                            destroyWysiwyg();
                            area.value = text;
                            setMode('json');
                            validateJson();
                        }
                    }
                }
            }
        });
    }
 
    function destroyWysiwyg() {
        if (joditInstance) {
            // Перед уничтожением — забираем текущее значение и кладём в textarea
            area.value = joditInstance.value;
            joditInstance.destruct();
            joditInstance = null;
        }
    }
 
    // ---- Инициализация JSON code-editor ----
    function initJsonEditor() {
        area.style.cssText = 'background:#1e1e1e;color:#d4d4d4;font-family:Consolas,"Courier New",monospace;font-size:14px;padding:15px;border-radius:0;min-height:650px;border:none;line-height:1.5;tab-size:2;';
        area.setAttribute('spellcheck', 'false');
 
        // Tab вставляет 2 пробела
        area.addEventListener('keydown', tabHandler);
 
        // Валидация на лету (с дебаунсом)
        area.addEventListener('input', debounce(validateJson, 400));
    }
 
    function destroyJsonEditor() {
        area.style.cssText = '';
        area.removeAttribute('spellcheck');
        area.removeEventListener('keydown', tabHandler);
        status.textContent = '';
        status.className = 'small';
    }
 
    function tabHandler(e) {
        if (e.key === 'Tab') {
            e.preventDefault();
            const s = this.selectionStart, end = this.selectionEnd;
            this.value = this.value.substring(0, s) + '  ' + this.value.substring(end);
            this.selectionStart = this.selectionEnd = s + 2;
        }
    }
 
    function debounce(fn, ms) {
        let t;
        return function() {
            clearTimeout(t);
            t = setTimeout(fn, ms);
        };
    }
 
    // ---- Проверка JSON ----
    function looksLikeJson(text) {
        try {
            const parsed = JSON.parse(text);
            return Array.isArray(parsed);
        } catch (e) {
            return false;
        }
    }
 
    function validateJson() {
        const text = area.value.trim();
        if (!text) {
            status.innerHTML = '<span class="text-muted">Пусто</span>';
            return;
        }
        try {
            const parsed = JSON.parse(text);
            if (!Array.isArray(parsed)) {
                status.innerHTML = '<span class="text-warning">⚠ Должен быть массив [...]</span>';
                return;
            }
            // Проверяем структуру: каждый элемент должен иметь type и content
            const validTypes = ['p', 'h2', 'h3', 'li', 'table', 'faq', 'highlight', 'cta'];
            let warnings = [];
            parsed.forEach((item, idx) => {
                if (!item.type) {
                    warnings.push(`Блок #${idx + 1}: нет поля "type"`);
                } else if (!validTypes.includes(item.type)) {
                    warnings.push(`Блок #${idx + 1}: тип "${item.type}" не поддерживается (можно: ${validTypes.join(', ')})`);
                }
                if (item.content === undefined && !['highlight'].includes(item.type)) {
                    warnings.push(`Блок #${idx + 1}: нет поля "content"`);
                }
            });
            if (warnings.length) {
                status.innerHTML = `<span class="text-warning">⚠ ${parsed.length} блок(ов), но есть проблемы: ${warnings.slice(0, 2).join('; ')}${warnings.length > 2 ? '…' : ''}</span>`;
            } else {
                status.innerHTML = `<span class="text-success">✓ Валидный JSON · ${parsed.length} блок(ов)</span>`;
            }
        } catch (e) {
            // Попытка вытащить номер строки из ошибки
            const match = e.message.match(/position (\d+)/);
            let lineInfo = '';
            if (match) {
                const pos = parseInt(match[1]);
                const lineNum = text.substring(0, pos).split('\n').length;
                lineInfo = ` (строка ~${lineNum})`;
            }
            status.innerHTML = `<span class="text-danger">✗ ${e.message}${lineInfo}</span>`;
        }
    }
 
    // ---- Переключение режимов ----
    function setMode(mode) {
        if (mode === currentMode) return;
 
        if (currentMode === 'html') destroyWysiwyg();
        if (currentMode === 'json') destroyJsonEditor();
 
        if (mode === 'html') {
            // Если в textarea сейчас JSON — спросим, точно ли хотим в HTML
            if (area.value.trim().startsWith('[') && looksLikeJson(area.value)) {
                if (!confirm('В поле сейчас JSON. Переключение в HTML-режим испортит структуру. Продолжить?')) {
                    return;
                }
            }
            initWysiwyg();
            btnHtml.classList.replace('btn-outline-secondary', 'btn-secondary');
            btnJson.classList.replace('btn-secondary', 'btn-outline-secondary');
            btnCheck.style.display = 'none';
        } else {
            initJsonEditor();
            btnJson.classList.replace('btn-outline-secondary', 'btn-secondary');
            btnHtml.classList.replace('btn-secondary', 'btn-outline-secondary');
            btnCheck.style.display = 'inline-block';
            validateJson();
        }
        currentMode = mode;
    }
 
    // ---- Кнопки ----
    btnHtml.addEventListener('click', () => setMode('html'));
    btnJson.addEventListener('click', () => setMode('json'));
    btnCheck.addEventListener('click', validateJson);
 
    // ---- Стартовый режим ----
    const initialValue = area.value.trim();
    if (initialValue.startsWith('[')) {
        setMode('json');
    } else if (initialValue.length === 0) {
        // Пустое поле — по умолчанию JSON (потому что мы работаем со структурой)
        // Если нужно — можно поменять на 'html'
        setMode('json');
    } else {
        setMode('html');
    }
 
    // ---- Перед сабмитом формы — синхронизируем значение из Jodit обратно в textarea ----
    document.querySelector('form').addEventListener('submit', function(e) {
        if (currentMode === 'html' && joditInstance) {
            area.value = joditInstance.value;
        }
        // Для JSON-режима textarea и так содержит актуальное значение
    });
 
    // Автоскрытие тоста
    setTimeout(() => {
        document.querySelector('.toast-saved')?.remove();
    }, 3000);
});
</script>
</body>
</html>
