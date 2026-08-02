<?php
// admin.php — Панель настроек (обновлённая)
// Сессия 24 часа
ini_set('session.gc_maxlifetime', 86400);
session_set_cookie_params(86400);
session_start();
require_once 'db.php';

// Домен для писем/ссылок восстановления (НЕ берём из HTTP_HOST — его подделывают)
if (!defined('SITE_DOMAIN')) define('SITE_DOMAIN', 'evakuator-kharkov.kh.ua');

// CSRF-токен на сессию + проверка
if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf'];
function csrf_check() {
    if (!isset($_POST['csrf']) || !hash_equals($_SESSION['csrf'] ?? '', (string)$_POST['csrf'])) {
        http_response_code(403);
        die('Ошибка безопасности (CSRF). Обновите страницу и попробуйте снова.');
    }
}

// Выход
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin.php");
    exit;
}

// Получаем настройки базы
$settings_raw = $db->select("settings", ["setting_key", "setting_value"]);
$settings = array_column($settings_raw, 'setting_value', 'setting_key');

// ==========================================
// ЛОГИКА ВОССТАНОВЛЕНИЯ ПАРОЛЯ
// ==========================================

// 1. Форма "Забыли пароль?" и отправка письма
if (isset($_GET['forgot'])) {
    $msg = '';
    if (isset($_POST['recovery_email'])) {
        csrf_check();
        $recovery_email = $settings['admin_recovery_email'] ?? '';

        if (!empty($recovery_email) && strtolower(trim($_POST['recovery_email'])) === strtolower($recovery_email)) {
            $token = bin2hex(random_bytes(32));
            $expires = time() + 3600;

            $db->update("settings", ["setting_value" => $token], ["setting_key" => "reset_token"]);
            $db->update("settings", ["setting_value" => $expires], ["setting_key" => "reset_expires"]);

            $reset_link = "https://" . SITE_DOMAIN . "/admin.php?reset=" . $token;
            $subject = "Восстановление пароля от админки " . SITE_DOMAIN;
            $message = "Здравствуйте!\n\nДля установки нового пароля перейдите по ссылке (действительна 1 час):\n\n";
            $message .= $reset_link . "\n\nЕсли это были не вы, проигнорируйте это письмо.";
            $headers = "From: noreply@" . SITE_DOMAIN;
            
            @mail($recovery_email, $subject, $message, $headers);
            $msg = '<div class="alert alert-success">Инструкции отправлены на email!</div>';
        } else {
            $msg = '<div class="alert alert-danger">Неверный Email!</div>';
        }
    }
    ?>
    <!DOCTYPE html>
    <html lang="ru"><head><meta charset="UTF-8"><title>Восстановление пароля</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"><style>body{background:#f4f7f6;height:100vh;display:flex;align-items:center;justify-content:center}.login-box{background:#fff;padding:30px;border-radius:15px;box-shadow:0 10px 25px rgba(0,0,0,.1);width:100%;max-width:400px}</style></head><body>
        <div class="login-box text-center">
            <h4 class="mb-3">Сброс пароля</h4>
            <?= $msg ?>
            <p class="text-muted small">Введите секретный email администратора</p>
            <form method="POST">
                <input type="hidden" name="csrf" value="<?= $csrf ?>">
                <input type="email" name="recovery_email" class="form-control mb-3" placeholder="Email" required autofocus>
                <button type="submit" class="btn btn-warning w-100 mb-2">Отправить ссылку</button>
                <a href="admin.php" class="text-decoration-none small">Вернуться ко входу</a>
            </form>
        </div>
    </body></html>
    <?php exit;
}

// 2. Установка нового пароля
if (isset($_GET['reset'])) {
    $token = $_GET['reset'];
    $db_token = $settings['reset_token'] ?? '';
    $db_expires = (int)($settings['reset_expires'] ?? 0);
    
    if (!empty($token) && !empty($db_token) && hash_equals((string)$db_token, (string)$token) && time() < $db_expires) {
        if (isset($_POST['new_pass'])) {
            csrf_check();
            $hashed = password_hash(trim($_POST['new_pass']), PASSWORD_DEFAULT);
            $db->update("settings", ["setting_value" => $hashed], ["setting_key" => "admin_password"]);
            $db->update("settings", ["setting_value" => ''], ["setting_key" => "reset_token"]); 
            $db->update("settings", ["setting_value" => '0'], ["setting_key" => "reset_expires"]); 
            header("Location: admin.php?msg=reset_ok");
            exit;
        }
        ?>
        <!DOCTYPE html>
        <html lang="ru"><head><meta charset="UTF-8"><title>Новый пароль</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"><style>body{background:#f4f7f6;height:100vh;display:flex;align-items:center;justify-content:center}.login-box{background:#fff;padding:30px;border-radius:15px;box-shadow:0 10px 25px rgba(0,0,0,.1);width:100%;max-width:400px}</style></head><body>
            <div class="login-box text-center">
                <h4 class="mb-3">Новый пароль</h4>
                <form method="POST">
                    <input type="hidden" name="csrf" value="<?= $csrf ?>">
                    <input type="password" name="new_pass" class="form-control mb-3" placeholder="Новый пароль" required autofocus>
                    <button type="submit" class="btn btn-success w-100">Сохранить</button>
                </form>
            </div>
        </body></html>
        <?php exit;
    } else {
        die("<h3>Ошибка</h3><p>Ссылка недействительна или устарела. <a href='admin.php'>Назад</a></p>");
    }
}

// ==========================================
// ЛОГИКА ВХОДА
// ==========================================
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    $error_msg = '';
    if (isset($_GET['msg']) && $_GET['msg'] === 'reset_ok') {
        $error_msg = '<div class="alert alert-success small">Пароль изменен! Теперь войдите.</div>';
    }

    // --- Защита от перебора пароля (лимит попыток по IP) ---
    $throttle_file = __DIR__ . '/../login_throttle.json';
    $ip           = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $now          = time();
    $max_attempts = 5;      // попыток до блокировки
    $lock_time    = 900;    // блокировка 15 минут

    $throttle = is_file($throttle_file) ? (json_decode(@file_get_contents($throttle_file), true) ?: []) : [];
    $rec = $throttle[$ip] ?? ['count' => 0, 'last' => 0, 'until' => 0];

    if ($rec['until'] > $now) {
        $wait = ceil(($rec['until'] - $now) / 60);
        $error_msg = '<div class="alert alert-danger small">Слишком много попыток. Попробуйте через ' . $wait . ' мин.</div>';
    } elseif (isset($_POST['pass'])) {
        csrf_check();
        $db_pass = $settings['admin_password'] ?? '';
        $is_valid = !empty($db_pass) && (
            (strpos($db_pass, '$2y$') === 0)
                ? password_verify($_POST['pass'], $db_pass)
                : hash_equals($db_pass, (string)$_POST['pass'])
        );

        if ($is_valid) {
            unset($throttle[$ip]);
            @file_put_contents($throttle_file, json_encode($throttle), LOCK_EX);
            session_regenerate_id(true);
            $_SESSION['logged_in'] = true;
            header("Location: admin.php");
            exit;
        } else {
            // Сбрасываем счётчик, если с последней попытки прошло больше окна
            if ($rec['last'] < $now - $lock_time) $rec['count'] = 0;
            $rec['count']++;
            $rec['last'] = $now;
            if ($rec['count'] >= $max_attempts) {
                $rec['until'] = $now + $lock_time;
                $rec['count'] = 0;
            }
            $throttle[$ip] = $rec;
            // подчищаем старые записи, чтобы файл не рос
            foreach ($throttle as $k => $r) {
                if (($r['until'] ?? 0) < $now && ($r['last'] ?? 0) < $now - $lock_time) unset($throttle[$k]);
            }
            @file_put_contents($throttle_file, json_encode($throttle), LOCK_EX);
            $error_msg = '<div class="alert alert-danger small">Неверный пароль!</div>';
        }
    }
    ?>
    <!DOCTYPE html>
    <html lang="ru"><head><meta charset="UTF-8"><title>Вход в панель</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"><style>body{background:#f4f7f6;height:100vh;display:flex;align-items:center;justify-content:center}.login-box{background:#fff;padding:30px;border-radius:15px;box-shadow:0 10px 25px rgba(0,0,0,.1);width:100%;max-width:400px}</style></head><body>
        <div class="login-box text-center">
            <h4 class="mb-3">🔒 Панель управления</h4>
            <?= $error_msg ?>
            <form method="POST">
                <input type="hidden" name="csrf" value="<?= $csrf ?>">
                <input type="password" name="pass" class="form-control mb-3" placeholder="Пароль" required autofocus>
                <button type="submit" class="btn btn-primary w-100 mb-2">Войти</button>
                <a href="?forgot" class="text-muted small text-decoration-none">Забыли пароль?</a>
            </form>
        </div>
    </body></html>
    <?php exit;
}

// ==========================================
// ОСНОВНАЯ ПАНЕЛЬ (НАСТРОЙКИ)
// ==========================================

// Сохранение настроек
if (isset($_POST['save'])) {
    csrf_check();
    $fields = ['tel_one_view','tel_one_link','tel_two_view','tel_two_link','telegram_user','viber_clean',
               'address_ru','address_ua','email',
               'price_car','price_jeep','price_spec','price_hard_load','price_km','price_feed','price_downtime',
               'rating_value','rating_count','admin_recovery_email'];
    
    foreach ($fields as $key) {
        if (isset($_POST[$key])) {
            $db->update("settings", ["setting_value" => trim($_POST[$key])], ["setting_key" => $key]);
        }
    }
    
    if (!empty(trim($_POST['new_admin_password'] ?? ''))) {
        $hashed = password_hash(trim($_POST['new_admin_password']), PASSWORD_DEFAULT);
        $db->update("settings", ["setting_value" => $hashed], ["setting_key" => "admin_password"]);
    }
    
    // Перечитываем настройки
    $settings_raw = $db->select("settings", ["setting_key", "setting_value"]);
    $settings = array_column($settings_raw, 'setting_value', 'setting_key');
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Настройки сайта</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f4f7f6; padding-bottom: 80px; }
        .card { margin-bottom: 16px; border-radius: 10px; }
        .btn-float { position: fixed; bottom: 24px; right: 24px; z-index: 100; border-radius: 12px; }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container">
            <span class="navbar-brand"><i class="fas fa-cogs me-2"></i>Настройки сайта</span>
            <div class="d-flex gap-2">
                <a href="pages_manager" class="btn btn-outline-info btn-sm"><i class="fas fa-file-alt me-1"></i>Страницы</a>
                <a href="/" target="_blank" class="btn btn-outline-light btn-sm"><i class="fas fa-eye me-1"></i>Сайт</a>
                <!-- Добавлена кнопка обновления Sitemap -->
                <a href="sitemap_gen.php" target="_blank" class="btn btn-outline-warning btn-sm"><i class="fas fa-sitemap me-1"></i>Обновить Sitemap</a>
                <a href="?logout" class="btn btn-danger btn-sm">Выйти</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <?php if (isset($_POST['save'])): ?>
            <div class="alert alert-success alert-dismissible">
                <i class="fas fa-check-circle me-2"></i>Настройки сохранены!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form method="post">
            <input type="hidden" name="csrf" value="<?= $csrf ?>">
            <div class="row">
                <div class="col-lg-6">

                    <div class="card">
                        <div class="card-header bg-primary text-white"><i class="fas fa-phone me-2"></i>Телефоны и мессенджеры</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Тел 1 (отображение)</label>
                                    <input type="text" name="tel_one_view" class="form-control" value="<?= htmlspecialchars($settings['tel_one_view'] ?? '') ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Тел 1 (ссылка tel:)</label>
                                    <input type="text" name="tel_one_link" class="form-control" value="<?= htmlspecialchars($settings['tel_one_link'] ?? '') ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Тел 2 (отображение)</label>
                                    <input type="text" name="tel_two_view" class="form-control" value="<?= htmlspecialchars($settings['tel_two_view'] ?? '') ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Тел 2 (ссылка tel:)</label>
                                    <input type="text" name="tel_two_link" class="form-control" value="<?= htmlspecialchars($settings['tel_two_link'] ?? '') ?>">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Telegram Username</label>
                                <input type="text" name="telegram_user" class="form-control" value="<?= htmlspecialchars($settings['telegram_user'] ?? '') ?>">
                            </div>
                            <div class="mb-0">
                                <label class="form-label">Viber ссылка</label>
                                <input type="text" name="viber_clean" class="form-control" value="<?= htmlspecialchars($settings['viber_clean'] ?? '') ?>">
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header bg-info text-white"><i class="fas fa-map-marker-alt me-2"></i>Адреса и Email</div>
                        <div class="card-body">
                            <div class="mb-3"><label class="form-label">Адрес (RU)</label><input type="text" name="address_ru" class="form-control" value="<?= htmlspecialchars($settings['address_ru'] ?? '') ?>"></div>
                            <div class="mb-3"><label class="form-label">Адрес (UA)</label><input type="text" name="address_ua" class="form-control" value="<?= htmlspecialchars($settings['address_ua'] ?? '') ?>"></div>
                            <div class="mb-0"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="<?= htmlspecialchars($settings['email'] ?? '') ?>"></div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header bg-success text-white"><i class="fas fa-tags me-2"></i>Тарифы</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3"><label class="form-label">Легковой авто</label><input type="number" name="price_car" class="form-control" value="<?= htmlspecialchars($settings['price_car'] ?? '') ?>"></div>
                                <div class="col-md-6 mb-3"><label class="form-label">Джип / Кроссовер</label><input type="number" name="price_jeep" class="form-control" value="<?= htmlspecialchars($settings['price_jeep'] ?? '') ?>"></div>
                                <div class="col-md-6 mb-3"><label class="form-label">Спецтехника</label><input type="number" name="price_spec" class="form-control" value="<?= htmlspecialchars($settings['price_spec'] ?? '') ?>"></div>
                                <div class="col-md-6 mb-3"><label class="form-label">Сложная погрузка</label><input type="number" name="price_hard_load" class="form-control" value="<?= htmlspecialchars($settings['price_hard_load'] ?? '') ?>"></div>
                                <div class="col-md-6 mb-3"><label class="form-label">Простой (час)</label><input type="number" name="price_downtime" class="form-control" value="<?= htmlspecialchars($settings['price_downtime'] ?? '') ?>"></div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-warning">
                        <div class="card-header bg-warning"><i class="fas fa-calculator me-2"></i>Межгород (автоматика)</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold text-primary">Цена за 1 км</label>
                                    <div class="input-group"><input type="number" name="price_km" class="form-control" value="<?= htmlspecialchars($settings['price_km'] ?? '') ?>"><span class="input-group-text">грн</span></div>
                                    <small class="text-muted">× дистанция × 2</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold text-primary">Подача за город</label>
                                    <div class="input-group"><input type="number" name="price_feed" class="form-control" value="<?= htmlspecialchars($settings['price_feed'] ?? '') ?>"><span class="input-group-text">грн</span></div>
                                    <small class="text-muted">Минимальная база</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header bg-secondary text-white"><i class="fas fa-star me-2"></i>SEO: Рейтинг</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6"><label class="form-label">Значение (4.8)</label><input type="text" name="rating_value" class="form-control" value="<?= htmlspecialchars($settings['rating_value'] ?? '') ?>"></div>
                                <div class="col-md-6"><label class="form-label">Кол-во отзывов</label><input type="number" name="rating_count" class="form-control" value="<?= htmlspecialchars($settings['rating_count'] ?? '') ?>"></div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-danger">
                        <div class="card-header bg-danger text-white"><i class="fas fa-shield-alt me-2"></i>Безопасность</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold text-danger">Новый пароль</label>
                                    <input type="text" name="new_admin_password" class="form-control border-danger" placeholder="Оставьте пустым">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-danger">Email для сброса</label>
                                    <input type="email" name="admin_recovery_email" class="form-control border-danger" value="<?= htmlspecialchars($settings['admin_recovery_email'] ?? '') ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" name="save" class="btn btn-success btn-lg shadow btn-float">
                <i class="fas fa-save me-2"></i>СОХРАНИТЬ
            </button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>