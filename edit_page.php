<?php
// edit_page.php
session_start();
require_once 'db.php';

if (!isset($_SESSION['logged_in'])) die("Доступ запрещен");

$id = $_GET['id'] ?? null;
$page = null;
$content_block_id = null;
$current_content = "";
$attrs = [];

// === 1. ЗАГРУЗКА ДАННЫХ ===
if ($id) {
    $page = $db->get("pages", "*", ["id" => $id]);

    if (!empty($page['attributes'])) {
        $attrs = json_decode($page['attributes'], true);
    }

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
}

// === 2. СОХРАНЕНИЕ ===
if (isset($_POST['save_page'])) {

    // Атрибуты (JSON)
    $new_attrs = [
        "location_type" => "city",
        "in_city" => $_POST['in_city'],
        "maps" => $_POST['map_url'],
        'price'         => $_POST['attr_price'] ?? '',    // Новое
        'distance'      => $_POST['attr_distance'] ?? '', // Новое
        'time'          => $_POST['attr_time'] ?? ''      // Новое
    ];
    if (!empty($attrs)) {
        $new_attrs = array_merge($attrs, $new_attrs);
    }

    // Основные данные
    $data = [
        "lang" => $_POST['lang'],
        "slug" => $_POST['slug'],
        "type" => $_POST['page_type'], // locations, services, article
        "h1" => $_POST['h1'],
        "breadcrumb_title" => $_POST['breadcrumb_title'],

        // --- HERO SECTION ---
        "h1_type" => $_POST['h1_type'],     // Тип шапки
        "hero_image" => $_POST['hero_image'], // Фон
        "custom_p" => $_POST['custom_p'],     // Подзаголовок
        "custom_btn" => $_POST['custom_btn'], // Текст кнопки
        // --------------------

        "meta_title" => $_POST['meta_title'],
        "meta_description" => $_POST['meta_desc'],
        "attributes" => json_encode($new_attrs, JSON_UNESCAPED_UNICODE),
        "parent_id" => !empty($_POST['parent_id']) ? $_POST['parent_id'] : null
    ];

    if ($id) {
        $db->update("pages", $data, ["id" => $id]);
    } else {
        $db->insert("pages", $data);
        $id = $db->id();
    }

    // Сохранение контента
    $new_content = $_POST['content'];
    $block_type = 'text';
    $trimmed = trim($new_content);
    if (strpos($trimmed, '[') === 0 && json_decode($trimmed)) {
        $block_type = 'structured_content';
    }

    if ($content_block_id) {
        $db->update("content_blocks", [
            "content" => $new_content,
            "block_type" => $block_type
        ], ["id" => $content_block_id]);
    } elseif (!empty($new_content)) {
        $db->insert("content_blocks", [
            "page_id" => $id,
            "block_type" => $block_type,
            "content" => $new_content,
            "sort_order" => 5
        ]);
    }

    // Редирект обратно на эту же страницу, чтобы видеть изменения
    header("Location: edit_page.php?id=$id");
    exit;
}

$all_pages = $db->select("pages", ["id", "breadcrumb_title", "lang"], ["ORDER" => ["lang" => "ASC", "breadcrumb_title" => "ASC"]]);
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Редактор: <?= htmlspecialchars($page['breadcrumb_title'] ?? 'New') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: #f0f2f5;
            padding-bottom: 80px;
        }

        .editor-area {
            font-family: monospace;
            font-size: 13px;
            min-height: 500px;
            background: #fff;
            color: #333;
            border: 1px solid #ced4da;
        }

        .card-header {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
        }

        .form-label {
            font-weight: 500;
            font-size: 0.9rem;
            color: #495057;
        }

        .hero-preview {
            height: 100px;
            background-size: cover;
            background-position: center;
            border-radius: 4px;
            margin-top: 10px;
            border: 1px solid #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.8);
        }
    </style>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jodit@latest/es2021/jodit.fat.min.css" />

</head>

<body>

    <nav class="navbar navbar-dark bg-dark mb-4 shadow-sm">
        <div class="container-fluid px-4">
            <span class="navbar-brand mb-0 h1"><i class="fas fa-edit"></i> <?= $id ? 'Редактирование' : 'Новая страница' ?></span>
            <div>
                <a href="pages_manager.php" class="btn btn-outline-light btn-sm">К списку</a>
                <a href="/<?= $page['lang'] == 'ua' ? 'ua/' : '' ?><?= $page['slug'] ?>" target="_blank" class="btn btn-primary btn-sm ms-2"><i class="fas fa-eye"></i> Просмотр</a>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-4">
        <form method="post">
            <div class="row">

                <div class="col-lg-4">

                    <div class="card mb-4 shadow-sm">
                        <div class="card-header bg-white text-primary">1. Основные параметры</div>
                        <div class="card-body bg-light">
                            <div class="row">
                                <div class="col-6">
                                    <label class="form-label">Язык</label>
                                    <select name="lang" class="form-select form-select-sm mb-2">
                                        <option value="ru" <?= ($page['lang'] ?? '') == 'ru' ? 'selected' : '' ?>>🇷🇺 RU</option>
                                        <option value="ua" <?= ($page['lang'] ?? '') == 'ua' ? 'selected' : '' ?>>🇺🇦 UA</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Тип страницы</label>
                                    <select name="page_type" class="form-select form-select-sm mb-2">
                                        <option value="locations" <?= ($page['type'] ?? '') == 'locations' ? 'selected' : '' ?>>Локация (City)</option>
                                        <option value="services" <?= ($page['type'] ?? '') == 'services' ? 'selected' : '' ?>>Услуга (Service)</option>
                                        <option value="articles" <?= ($page['type'] ?? '') == 'articles' ? 'selected' : '' ?>>Статья (Articles)</option>
                                        <option value="articles" <?= ($page['type'] ?? '') == 'archive' ? 'selected' : '' ?>>Блог (Archive)</option>
                                        <option value="articles" <?= ($page['type'] ?? '') == 'hub' ? 'selected' : '' ?>>Региональная (Hub)</option>
                                    </select>
                                </div>
                            </div>

                            <label class="form-label">URL (Slug)</label>
                            <div class="input-group input-group-sm mb-2">
                                <span class="input-group-text">/</span>
                                <input type="text" name="slug" class="form-control" value="<?= htmlspecialchars($page['slug'] ?? '') ?>">
                            </div>

                            <label class="form-label">Название в "Крошках"</label>
                            <input type="text" name="breadcrumb_title" class="form-control form-control-sm mb-2" value="<?= htmlspecialchars($page['breadcrumb_title'] ?? '') ?>">

                            <label class="form-label">Родитель</label>
                            <select name="parent_id" class="form-select form-select-sm">
                                <option value="0">-- Нет (Главная) --</option>
                                <?php foreach ($all_pages as $p): ?>
                                    <option value="<?= $p['id'] ?>" <?= ($page['parent_id'] ?? '') == $p['id'] ? 'selected' : '' ?>>
                                        [<?= strtoupper($p['lang']) ?>] <?= htmlspecialchars($p['breadcrumb_title']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="card mb-4 shadow-sm border-info">
                        <div class="card-header bg-info text-white"><i class="fas fa-image"></i> 2. Настройки Hero (Шапка)</div>
                        <div class="card-body">

                            <label class="form-label">Тип Шапки</label>
                            <select name="h1_type" class="form-select mb-3">
                                <option value="standard" <?= ($page['h1_type'] ?? '') == 'standard' ? 'selected' : '' ?>>📍 Standard (Локация: H1+P+Btn+BG)</option>
                                <option value="service" <?= ($page['h1_type'] ?? '') == 'service' ? 'selected' : '' ?>>🛠 Service (Услуга: H1+P+Btn+BG)</option>
                                <option value="simple" <?= ($page['h1_type'] ?? '') == 'simple' ? 'selected' : '' ?>>📝 Simple (Блог: H1, без фона)</option>
                            </select>

                            <label class="form-label">Заголовок H1</label>
                            <input type="text" name="h1" class="form-control fw-bold mb-2" value="<?= htmlspecialchars($page['h1'] ?? '') ?>">

                            <label class="form-label">Подзаголовок (P)</label>
                            <textarea name="custom_p" class="form-control mb-2" rows="2" placeholder="Например: Подача за 20 минут..."><?= htmlspecialchars($page['custom_p'] ?? '') ?></textarea>

                            <label class="form-label">Текст на кнопке</label>
                            <input type="text" name="custom_btn" class="form-control mb-2" placeholder="По умолчанию: Вызвать эвакуатор" value="<?= htmlspecialchars($page['custom_btn'] ?? '') ?>">

                            <label class="form-label">Фоновое изображение (URL)</label>
                            <div class="input-group">
                                <input type="text" name="hero_image" class="form-control" id="heroImgInput" value="<?= htmlspecialchars($page['hero_image'] ?? '') ?>" placeholder="/assets/images/...">
                            </div>
                            <?php if (!empty($page['hero_image'])): ?>
                                <div class="hero-preview" style="background-image: url('<?= $page['hero_image'] ?>');">Предпросмотр</div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="card mb-4 shadow-sm">
                        <div class="card-header bg-white text-secondary">3. Локальные данные & SEO</div>
                        <div class="card-body">
                            <label class="form-label">Склонение (Где?)</label>
                            <input type="text" name="in_city" class="form-control form-control-sm mb-2" value="<?= htmlspecialchars($attrs['in_city'] ?? '') ?>" placeholder="в Чугуеве">

                            <label class="form-label">Google Maps (Embed URL)</label>
                            <input type="text" name="map_url" class="form-control form-control-sm mb-2" value="<?= htmlspecialchars($attrs['maps'] ?? '') ?>">

                            <hr>
                            <label class="form-label">Meta Title</label>
                            <textarea name="meta_title" class="form-control form-control-sm mb-2" rows="2"><?= htmlspecialchars($page['meta_title'] ?? '') ?></textarea>

                            <label class="form-label">Meta Description</label>
                            <textarea name="meta_desc" class="form-control form-control-sm" rows="3"><?= htmlspecialchars($page['meta_description'] ?? '') ?></textarea>
                        </div>
                    </div>
                    <div class="card mb-4 shadow-sm">
                        <div class="card-header bg-white text-secondary">Атрибуты</div>
                        <div class="card-body">
                            <label class="form-label">Фикс. Цена (для районов)</label>
                            <input type="number" name="attr_price" class="form-control form-control-sm"
                            value="<?= htmlspecialchars($attrs['price'] ?? '') ?>" placeholder="Напр: 1200">
                        
                            <label class="form-label">Дистанция (км) — для расчета</label>
                            <input type="number" name="attr_distance" class="form-control form-control-sm"
                            value="<?= htmlspecialchars($attrs['distance'] ?? '') ?>" placeholder="Напр: 150">
                    
                            <label class="form-label">Время в пути (текст)</label>
                            <input type="text" name="attr_time" class="form-control form-control-sm"
                                value="<?= htmlspecialchars($attrs['time'] ?? '') ?>" placeholder="Напр: 2.5 часа">
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                            <span>4. Контент страницы (Блоки)</span>
                            <small class="text-white-50">HTML или JSON</small>
                        </div>
                        <div class="card-body p-0">
                            <textarea name="content" class="form-control editor-area border-0 p-3 h-100"><?= htmlspecialchars($current_content) ?></textarea>
                        </div>
                        <div class="card-footer bg-light">
                            <button type="submit" name="save_page" class="btn btn-success btn-lg w-100 shadow">
                                <i class="fas fa-save"></i> СОХРАНИТЬ ИЗМЕНЕНИЯ
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
            const contentArea = document.querySelector('.editor-area');
            const contentVal = contentArea.value.trim();

            // Проверяем, загрузилась ли библиотека
            if (typeof Jodit === 'undefined') {
                console.error("ОШИБКА: Jodit снова не загрузился! Проверьте блокировщики рекламы.");
                return;
            }

            // Если контент не начинается с '[', считаем, что это HTML (Статьи)
            if (!contentVal.startsWith('[')) {
                const editor = new Jodit('.editor-area', {
                    height: 600,
                    language: 'ru',
                    // Отключаем лишнюю "очистку" кода, чтобы спасти классы Mobirise
                    cleanHTML: {
                        fillEmptyParagraph: false,
                        removeEmptyElements: false,
                        replaceNBSP: false,
                        allowTags: '*'
                    }
                });
            } else {
                // Если это JSON (Сервисные страницы)
                contentArea.style.backgroundColor = '#1e1e1e';
                contentArea.style.color = '#569cd6';
                contentArea.style.fontFamily = 'Consolas, "Courier New", monospace';
                contentArea.style.fontSize = '14px';
                contentArea.style.padding = '15px';
                contentArea.style.borderRadius = '5px';

                // Поддержка клавиши Tab
                contentArea.addEventListener('keydown', function(e) {
                    if (e.key === 'Tab') {
                        e.preventDefault();
                        const start = this.selectionStart;
                        const end = this.selectionEnd;
                        this.value = this.value.substring(0, start) + "  " + this.value.substring(end);
                        this.selectionStart = this.selectionEnd = start + 2;
                    }
                });
            }
        });
    </script>
</body>

</html>