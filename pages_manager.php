<?php
// pages_manager.php
session_start();
require_once 'db.php';

// Проверка входа
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: admin.php");
    exit;
}

// === ОБРАБОТКА УДАЛЕНИЯ СТРАНИЦЫ ===
if (isset($_GET['delete_slug'])) {
    $slug_to_delete = $_GET['delete_slug'];
    $pages_to_delete = $db->select("pages", "id", ["slug" => $slug_to_delete]);
    if (!empty($pages_to_delete)) {
        $db->delete("content_blocks", ["page_id" => $pages_to_delete]);
        $db->delete("pages", ["id" => $pages_to_delete]);
    }
    // Возвращаемся на ту же вкладку, с которой удаляли
    $return_tab = $_GET['tab'] ?? 'services';
    header("Location: pages_manager.php?tab=" . $return_tab);
    exit;
}

// 1. Настройка вкладок (Папок)
$tabs = [
    'home' => ['title' => 'Главная', 'icon' => 'fa-home'],
    'services' => ['title' => 'Услуги', 'icon' => 'fa-tools'],
    'locations' => ['title' => 'Локации (ГЕО)', 'icon' => 'fa-map-marker-alt'],
    'hub' => ['title' => 'Хабы / Разделы', 'icon' => 'fa-sitemap'],
    'articles' => ['title' => 'Блог (Статьи)', 'icon' => 'fa-newspaper']
];

// Получаем текущую активную вкладку из URL (по умолчанию - Услуги)
$active_tab = $_GET['tab'] ?? 'services';
// Защита: если передали несуществующий тип, сбрасываем на services
if (!array_key_exists($active_tab, $tabs)) $active_tab = 'services';

// 2. Получаем RU страницы ТОЛЬКО ДЛЯ АКТИВНОЙ ВКЛАДКИ
$ru_pages = $db->select("pages", "*", [
    "lang" => "ru",
    "type" => $active_tab, // Фильтруем по типу!
    "ORDER" => ["breadcrumb_title" => "ASC"]
]);
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Менеджер страниц</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f4f7f6;
            padding-bottom: 50px;
        }

        .table-container {
            background: white;
            padding: 25px;
            border-radius: 0 0 12px 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .btn-lang {
            width: 45px;
            font-weight: bold;
        }

        .nav-tabs .nav-link {
            font-weight: 500;
            color: #495057;
            border-radius: 8px 8px 0 0;
            padding: 12px 20px;
        }

        .nav-tabs .nav-link.active {
            font-weight: bold;
            color: #0d6efd;
            border-bottom: 3px solid #0d6efd;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container">
            <span class="navbar-brand"><i class="fas fa-cogs me-2"></i>Менеджер страниц</span>
            <div class="d-flex">
                <a href="admin" class="btn btn-outline-light btn-sm me-2">Настройки сайта</a>
                <a href="?logout" class="btn btn-danger btn-sm">Выйти</a>
            </div>
        </div>
    </nav>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-folder-open text-warning"></i> Управление сайтом</h2>

            <a href="edit_page.php?type=<?= $active_tab ?>" class="btn btn-success">
                <i class="fas fa-plus"></i> Добавить страницу в этот раздел
            </a>
        </div>

        <ul class="nav nav-tabs border-bottom-0">
            <?php foreach ($tabs as $key => $tab): ?>
                <li class="nav-item">
                    <a class="nav-link <?= $active_tab == $key ? 'active bg-white' : 'bg-light border' ?>"
                        href="?tab=<?= $key ?>">
                        <i class="fas <?= $tab['icon'] ?> me-2"></i><?= $tab['title'] ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

        <div class="table-container border border-top-0">
            <div class="row mb-3">
                <div class="col-md-4">
                    <input type="text" id="searchInput" class="form-control" placeholder="Поиск в этом разделе...">
                </div>
            </div>

            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Название (Breadcrumb)</th>
                        <th>Тип</th>
                        <th>Родитель</th>
                        <th>Slug (URL)</th>
                        <th class="text-center">Версии</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ru_pages as $ru):
                        // Ищем пару (UA версию) по одинаковому SLUG
                        $ua = $db->get("pages", ["id", "lang"], [
                            "slug" => $ru['slug'],
                            "lang" => "ua"
                        ]);
                    ?>
                        <tr>
                            <td>
                                <div class="fw-bold"><?= htmlspecialchars($ru['breadcrumb_title'] ?? $ru['h1']) ?></div>
                                <small class="text-muted"><?= htmlspecialchars($ru['h1']) ?></small>
                            </td>
                            <td><span class="badge bg-secondary badge-type"><?= $ru['type'] ?></span></td>
                            <td>
                                <?php
                                if (!empty($ru['parent_id'])) {
                                    // Ищем имя родителя
                                    $parent = $db->get("pages", "breadcrumb_title", ["id" => $ru['parent_id']]);
                                    echo '<small class="text-muted"><i class="fas fa-level-up-alt fa-rotate-90"></i> ' . ($parent ?? $ru['parent_id']) . '</small>';
                                } else {
                                    echo '<span class="text-warning small">● Корень</span>';
                                }
                                ?>
                            </td>
                            <td class="text-muted small">/<?= $ru['slug'] ?></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="edit_page.php?id=<?= $ru['id'] ?>" class="btn btn-outline-primary btn-sm btn-lang">RU</a>

                                    <?php if ($ua): ?>
                                        <a href="edit_page.php?id=<?= $ua['id'] ?>" class="btn btn-outline-warning btn-sm btn-lang">UA</a>
                                    <?php else: ?>
                                        <a href="edit_page.php?create_lang=ua&source_id=<?= $ru['id'] ?>" class="btn btn-outline-light text-dark btn-sm btn-lang" title="Создать UA версию">+UA</a>
                                    <?php endif; ?>

                                    <a href="pages_manager.php?delete_slug=<?= htmlspecialchars($ru['slug']) ?>"
                                        class="btn btn-outline-danger btn-sm"
                                        onclick="return confirm('⚠️ ВЫ УВЕРЕНЫ?\n\nБудут удалены ОБЕ версии страницы (RU и UA), а также весь текст и блоки внутри них.\nЭто действие необратимо!');"
                                        title="Удалить страницу">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');

            // Ищем все строки внутри tbody нашей таблицы
            const tableRows = document.querySelectorAll('table tbody tr');

            searchInput.addEventListener('keyup', function(e) {
                const term = e.target.value.toLowerCase();

                tableRows.forEach(function(row) {
                    // Собираем текст из первых трех колонок (Название, URL, Шаблон)
                    // Если у вас другой порядок, скрипт все равно найдет совпадения
                    const textContent = row.textContent.toLowerCase();

                    // Если текст строки содержит введенный запрос, показываем строку, иначе прячем
                    if (textContent.includes(term)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
    </script>
</body>

</html>