<?php
// pages_manager.php — Менеджер страниц (обновлённый)
ini_set('session.gc_maxlifetime', 86400);
session_set_cookie_params(86400);
session_start();
require_once 'db.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: admin.php");
    exit;
}

// === УДАЛЕНИЕ ===
if (isset($_GET['delete_slug'])) {
    $slug_to_delete = $_GET['delete_slug'];
    $pages_to_delete = $db->select("pages", "id", ["slug" => $slug_to_delete]);
    if (!empty($pages_to_delete)) {
        $db->delete("content_blocks", ["page_id" => $pages_to_delete]);
        $db->delete("pages", ["id" => $pages_to_delete]);
    }
    $return_tab = $_GET['tab'] ?? 'locations';
    header("Location: pages_manager.php?tab=" . $return_tab);
    exit;
}

// Вкладки
$tabs = [
    'locations' => ['title' => 'Локации (ГЕО)', 'icon' => 'fa-map-marker-alt'],
    'services'  => ['title' => 'Услуги',        'icon' => 'fa-tools'],
    'hub'       => ['title' => 'Хабы',           'icon' => 'fa-sitemap'],
    'articles'  => ['title' => 'Блог',           'icon' => 'fa-newspaper'],
    'archive'   => ['title' => 'Архивы',         'icon' => 'fa-folder'],
];

$active_tab = $_GET['tab'] ?? 'locations';
if (!array_key_exists($active_tab, $tabs)) $active_tab = 'locations';

// Считаем страницы для бейджей
$tab_counts = [];
foreach ($tabs as $key => $t) {
    $tab_counts[$key] = $db->count("pages", ["lang" => "ru", "type" => $key]);
}

// Страницы для текущей вкладки
$ru_pages = $db->select("pages", "*", [
    "lang"  => "ru",
    "type"  => $active_tab,
    "ORDER" => ["breadcrumb_title" => "ASC"]
]);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Страницы — <?= $tabs[$active_tab]['title'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f4f7f6; padding-bottom: 50px; }
        .table-container { background: #fff; padding: 20px; border-radius: 0 0 12px 12px; box-shadow: 0 4px 6px rgba(0,0,0,.05); }
        .btn-lang { width: 42px; font-weight: bold; font-size: 12px; }
        .nav-tabs .nav-link { font-weight: 500; color: #495057; border-radius: 8px 8px 0 0; padding: 10px 16px; }
        .nav-tabs .nav-link.active { font-weight: bold; color: #0d6efd; border-bottom: 3px solid #0d6efd; }
        .badge-count { font-size: 11px; padding: 2px 6px; margin-left: 6px; }
        .slug-col { max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container">
            <span class="navbar-brand"><i class="fas fa-file-alt me-2"></i>Страницы</span>
            <div class="d-flex gap-2">
                <a href="admin" class="btn btn-outline-light btn-sm"><i class="fas fa-cogs me-1"></i>Настройки</a>
                <a href="/" target="_blank" class="btn btn-outline-light btn-sm"><i class="fas fa-eye me-1"></i>Сайт</a>
                <a href="?logout" class="btn btn-danger btn-sm">Выйти</a>
            </div>
        </div>
    </nav>

    <div class="container mt-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0"><i class="fas <?= $tabs[$active_tab]['icon'] ?> text-warning me-2"></i><?= $tabs[$active_tab]['title'] ?></h4>
            <a href="edit_page.php?type=<?= $active_tab ?>" class="btn btn-success btn-sm">
                <i class="fas fa-plus me-1"></i>Добавить
            </a>
        </div>

        <ul class="nav nav-tabs border-bottom-0">
            <?php foreach ($tabs as $key => $tab): ?>
            <li class="nav-item">
                <a class="nav-link <?= $active_tab == $key ? 'active bg-white' : 'bg-light border' ?>" href="?tab=<?= $key ?>">
                    <i class="fas <?= $tab['icon'] ?> me-1"></i><?= $tab['title'] ?>
                    <?php if ($tab_counts[$key] > 0): ?>
                        <span class="badge bg-secondary badge-count"><?= $tab_counts[$key] ?></span>
                    <?php endif; ?>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>

        <div class="table-container border border-top-0">
            <div class="row mb-3">
                <div class="col-md-4">
                    <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Поиск...">
                </div>
                <div class="col-md-8 text-end text-muted small pt-2">
                    Страниц: <strong><?= count($ru_pages) ?></strong> (RU)
                </div>
            </div>

            <table class="table table-hover align-middle mb-0" style="font-size:14px;">
                <thead class="table-light">
                    <tr>
                        <th>Название</th>
                        <th>Родитель</th>
                        <th>Slug</th>
                        <th style="width:120px">Дата</th>
                        <th class="text-center" style="width:180px">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ru_pages as $ru):
                        $ua = $db->get("pages", ["id", "lang"], ["slug" => $ru['slug'], "lang" => "ua"]);
                        $preview_url = '/' . $ru['slug'];
                    ?>
                    <tr>
                        <td>
                            <div class="fw-bold"><?= htmlspecialchars($ru['breadcrumb_title'] ?? $ru['h1']) ?></div>
                            <small class="text-muted"><?= htmlspecialchars(mb_substr($ru['h1'], 0, 60)) ?></small>
                        </td>
                        <td>
                            <?php if (!empty($ru['parent_id'])):
                                $parent = $db->get("pages", "breadcrumb_title", ["id" => $ru['parent_id']]);
                                echo '<small class="text-muted"><i class="fas fa-level-up-alt fa-rotate-90 me-1"></i>' . ($parent ?? '?') . '</small>';
                            else:
                                echo '<span class="text-warning small">● Корень</span>';
                            endif; ?>
                        </td>
                        <td class="text-muted small slug-col" title="/<?= $ru['slug'] ?>">/<?= $ru['slug'] ?></td>
                        <td class="small text-nowrap">
                            <?php if (!empty($ru['date'])): $future = $ru['date'] > date('Y-m-d'); ?>
                                <span class="<?= $future ? 'text-warning fw-bold' : 'text-muted' ?>">
                                    <?= htmlspecialchars($ru['date']) ?>
                                    <?php if ($future): ?><i class="fas fa-clock ms-1" title="Запланирована — выйдет в эту дату"></i><?php endif; ?>
                                </span>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                                <a href="edit_page.php?id=<?= $ru['id'] ?>" class="btn btn-outline-primary btn-sm btn-lang" title="Редактировать RU">RU</a>
                                <?php if ($ua): ?>
                                    <a href="edit_page.php?id=<?= $ua['id'] ?>" class="btn btn-outline-warning btn-sm btn-lang" title="Редактировать UA">UA</a>
                                <?php else: ?>
                                    <a href="edit_page.php?create_lang=ua&source_id=<?= $ru['id'] ?>" class="btn btn-outline-light text-dark btn-sm btn-lang" title="Создать UA">+UA</a>
                                <?php endif; ?>
                                <a href="<?= $preview_url ?>" target="_blank" class="btn btn-outline-secondary btn-sm" title="Открыть на сайте"><i class="fas fa-eye"></i></a>
                                <a href="?delete_slug=<?= urlencode($ru['slug']) ?>&tab=<?= $active_tab ?>"
                                   class="btn btn-outline-danger btn-sm"
                                   onclick="return confirm('Удалить ОБЕ версии (RU+UA) и все блоки?')"
                                   title="Удалить"><i class="fas fa-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
    document.getElementById('searchInput').addEventListener('keyup', function(e) {
        const term = e.target.value.toLowerCase();
        document.querySelectorAll('table tbody tr').forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
        });
    });
    </script>
</body>
</html>
