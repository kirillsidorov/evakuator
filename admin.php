<?php
session_start();
require_once 'db.php';

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
        $recovery_email = $settings['admin_recovery_email'] ?? '';
        
        // Проверяем, совпадает ли введенный email с секретным из базы
        if (!empty($recovery_email) && strtolower(trim($_POST['recovery_email'])) === strtolower($recovery_email)) {
            // Генерируем надежный токен
            $token = bin2hex(random_bytes(32));
            $expires = time() + 3600; // Токен живет 1 час
            
            // Сохраняем в БД
            $db->update("settings", ["setting_value" => $token], ["setting_key" => "reset_token"]);
            $db->update("settings", ["setting_value" => $expires], ["setting_key" => "reset_expires"]);
            
            // Отправляем письмо (Используем стандартную функцию PHP mail)
            $reset_link = "https://" . $_SERVER['HTTP_HOST'] . "/admin.php?reset=" . $token;
            $subject = "Восстановление пароля от админки " . $_SERVER['HTTP_HOST'];
            $message = "Здравствуйте!\n\nКто-то (скорее всего вы) запросил сброс пароля от панели управления.\n";
            $message .= "Для установки нового пароля перейдите по ссылке ниже. Ссылка действительна 1 час:\n\n";
            $message .= $reset_link . "\n\nЕсли это были не вы, просто проигнорируйте это письмо.";
            
            $headers = "From: noreply@" . $_SERVER['HTTP_HOST'];
            
            @mail($recovery_email, $subject, $message, $headers);
            
            $msg = '<div class="alert alert-success">Инструкции отправлены на email!</div>';
        } else {
            $msg = '<div class="alert alert-danger">Неверный Email!</div>';
        }
    }
    
    // Вывод формы восстановления
    ?>
    <!DOCTYPE html>
    <html lang="ru"><head><meta charset="UTF-8"><title>Восстановление пароля</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"><style>body { background: #f4f7f6; height: 100vh; display: flex; align-items: center; justify-content: center; } .login-box { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }</style></head><body>
        <div class="login-box text-center">
            <h4 class="mb-3">Сброс пароля</h4>
            <?= $msg ?>
            <p class="text-muted small">Введите ваш секретный email администратора</p>
            <form method="POST">
                <input type="email" name="recovery_email" class="form-control mb-3" placeholder="Ваш Email" required autofocus>
                <button type="submit" class="btn btn-warning w-100 mb-2">Отправить ссылку</button>
                <a href="admin.php" class="text-decoration-none small">Вернуться ко входу</a>
            </form>
        </div>
    </body></html>
    <?php
    exit;
}

// 2. Установка нового пароля по ссылке из письма
if (isset($_GET['reset'])) {
    $token = $_GET['reset'];
    $db_token = $settings['reset_token'] ?? '';
    $db_expires = (int)($settings['reset_expires'] ?? 0);
    
    // Проверяем валидность токена
    if (!empty($token) && $token === $db_token && time() < $db_expires) {
        if (isset($_POST['new_pass'])) {
            $hashed = password_hash(trim($_POST['new_pass']), PASSWORD_DEFAULT);
            $db->update("settings", ["setting_value" => $hashed], ["setting_key" => "admin_password"]);
            // Уничтожаем токен в целях безопасности
            $db->update("settings", ["setting_value" => ''], ["setting_key" => "reset_token"]); 
            $db->update("settings", ["setting_value" => '0'], ["setting_key" => "reset_expires"]); 
            
            header("Location: admin.php?msg=reset_ok");
            exit;
        }
        ?>
        <!DOCTYPE html>
        <html lang="ru"><head><meta charset="UTF-8"><title>Новый пароль</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"><style>body { background: #f4f7f6; height: 100vh; display: flex; align-items: center; justify-content: center; } .login-box { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }</style></head><body>
            <div class="login-box text-center">
                <h4 class="mb-3">Новый пароль</h4>
                <form method="POST">
                    <input type="text" name="new_pass" class="form-control mb-3" placeholder="Придумайте новый пароль" required autofocus>
                    <button type="submit" class="btn btn-success w-100">Сохранить и войти</button>
                </form>
            </div>
        </body></html>
        <?php
        exit;
    } else {
        die("<h3>Ошибка!</h3><p>Ссылка недействительна или устарела (прошло более 1 часа). <br><a href='admin.php'>Вернуться на главную</a></p>");
    }
}

// ==========================================
// ЛОГИКА ВХОДА (ОБЫЧНАЯ)
// ==========================================
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    $error_msg = '';
    
    // Показываем сообщение, если только что успешно сбросили пароль
    if (isset($_GET['msg']) && $_GET['msg'] === 'reset_ok') {
        $error_msg = '<div class="alert alert-success small">Пароль изменен! Теперь войдите.</div>';
    }

    if (isset($_POST['pass'])) {
        $db_pass = $settings['admin_password'] ?? '12345';
        $is_valid = false;

        if (strpos($db_pass, '$2y$') === 0) {
            $is_valid = password_verify($_POST['pass'], $db_pass);
        } else {
            $is_valid = ($_POST['pass'] === $db_pass);
        }

        if ($is_valid) {
            $_SESSION['logged_in'] = true;
            header("Location: admin.php");
            exit;
        } else {
            $error_msg = '<div class="alert alert-danger small">Неверный пароль!</div>';
        }
    }
    ?>
    <!DOCTYPE html>
    <html lang="ru"><head><meta charset="UTF-8"><title>Вход в панель</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"><style>body { background: #f4f7f6; height: 100vh; display: flex; align-items: center; justify-content: center; } .login-box { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }</style></head><body>
        <div class="login-box text-center">
            <h4 class="mb-4">Авторизация</h4>
            <?= $error_msg ?>
            <form method="POST">
                <input type="password" name="pass" class="form-control mb-3" placeholder="Пароль" required autofocus>
                <button type="submit" class="btn btn-primary w-100 mb-2">Войти</button>
                <a href="admin.php?forgot=1" class="text-decoration-none text-muted small">Забыли пароль?</a>
            </form>
        </div>
    </body></html>
    <?php
    exit;
}

// --- СОХРАНЕНИЕ ВСЕХ НАСТРОЕК В АДМИНКЕ ---
if (isset($_POST['save'])) {
    foreach ($_POST as $key => $value) {
        if ($key === 'save') continue;

        if ($key === 'new_admin_password') {
            if (!empty($value)) { 
                $hashed_password = password_hash(trim($value), PASSWORD_DEFAULT);
                $db->update("settings", ["setting_value" => $hashed_password], ["setting_key" => "admin_password"]);
            }
            continue;
        }

        $db->update("settings", ["setting_value" => trim($value)], ["setting_key" => $key]);
    }
    header("Location: admin.php?success=1");
    exit;
}

// ПОЛУЧЕНИЕ ДАННЫХ ДЛЯ ФОРМЫ
$settings_raw = $db->select("settings", ["setting_key", "setting_value"]);
$settings = array_column($settings_raw, 'setting_value', 'setting_key');
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Настройки сайта | Админ-панель</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            padding-bottom: 80px;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        .card-header {
            border-radius: 12px 12px 0 0 !important;
            font-weight: bold;
        }

        .btn-float {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
            padding: 15px 30px;
            border-radius: 50px;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container">
            <span class="navbar-brand"><i class="fas fa-cogs me-2"></i> Настройки сайта</span>
            <div class="d-flex">
                <a href="pages_manager.php" class="btn btn-outline-light btn-sm me-2">Менеджер страниц</a>
                <a href="?logout" class="btn btn-danger btn-sm">Выйти</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i> Настройки успешно сохранены!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="row">

                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <i class="fas fa-address-book me-2"></i> Контакты и мессенджеры
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($settings['email'] ?? '') ?>">
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Тел 1 (Текст)</label>
                                    <input type="text" name="tel_one_view" class="form-control" value="<?= htmlspecialchars($settings['tel_one_view'] ?? '') ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Тел 1 (Ссылка tel:)</label>
                                    <input type="text" name="tel_one_link" class="form-control" value="<?= htmlspecialchars($settings['tel_one_link'] ?? '') ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Тел 2 (Текст)</label>
                                    <input type="text" name="tel_two_view" class="form-control" value="<?= htmlspecialchars($settings['tel_two_view'] ?? '') ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Тел 2 (Ссылка tel:)</label>
                                    <input type="text" name="tel_two_link" class="form-control" value="<?= htmlspecialchars($settings['tel_two_link'] ?? '') ?>">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Telegram Username</label>
                                <input type="text" name="telegram_user" class="form-control" value="<?= htmlspecialchars($settings['telegram_user'] ?? '') ?>">
                            </div>
                            <div class="mb-0">
                                <label class="form-label">Viber Ссылка</label>
                                <input type="text" name="viber_clean" class="form-control" value="<?= htmlspecialchars($settings['viber_clean'] ?? '') ?>">
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <i class="fas fa-map-marker-alt me-2"></i> Адреса (RU / UA)
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Адрес (RU)</label>
                                <input type="text" name="address_ru" class="form-control" value="<?= htmlspecialchars($settings['address_ru'] ?? '') ?>">
                            </div>
                            <div class="mb-0">
                                <label class="form-label">Адрес (UA)</label>
                                <input type="text" name="address_ua" class="form-control" value="<?= htmlspecialchars($settings['address_ua'] ?? '') ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <i class="fas fa-tags me-2"></i> Тарифы (Базовые)
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Легковой авто</label>
                                    <input type="number" name="price_car" class="form-control" value="<?= htmlspecialchars($settings['price_car'] ?? '') ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Джип / Кроссовер</label>
                                    <input type="number" name="price_jeep" class="form-control" value="<?= htmlspecialchars($settings['price_jeep'] ?? '') ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Спецтехника</label>
                                    <input type="number" name="price_spec" class="form-control" value="<?= htmlspecialchars($settings['price_spec'] ?? '') ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Сложная погрузка</label>
                                    <input type="number" name="price_hard_load" class="form-control" value="<?= htmlspecialchars($settings['price_hard_load'] ?? '') ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-warning">
                        <div class="card-header bg-warning">
                            <i class="fas fa-calculator me-2"></i> Расчет межгорода (Автоматика)
                        </div>
                        <div class="card-body bg-light-subtle">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-primary fw-bold">Цена за 1 км</label>
                                    <div class="input-group">
                                        <input type="number" name="price_km" class="form-control border-primary" value="<?= htmlspecialchars($settings['price_km'] ?? '') ?>">
                                        <span class="input-group-text">грн</span>
                                    </div>
                                    <small class="text-muted">Умножается на дистанцию х2</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-primary fw-bold">Подача за город</label>
                                    <div class="input-group">
                                        <input type="number" name="price_feed" class="form-control border-primary" value="<?= htmlspecialchars($settings['price_feed'] ?? '') ?>">
                                        <span class="input-group-text">грн</span>
                                    </div>
                                    <small class="text-muted">Минималка (база)</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header bg-secondary text-white">
                            <i class="fas fa-star me-2"></i> SEO: Рейтинг и отзывы
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Значение (напр. 4.8)</label>
                                    <input type="text" name="rating_value" class="form-control" value="<?= htmlspecialchars($settings['rating_value'] ?? '') ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Кол-во отзывов</label>
                                    <input type="number" name="rating_count" class="form-control" value="<?= htmlspecialchars($settings['rating_count'] ?? '') ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-danger mt-3 mb-5">
                        <div class="card-header bg-danger text-white">
                            <i class="fas fa-shield-alt me-2"></i> Безопасность и Доступ
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label class="form-label fw-bold text-danger">Сменить пароль от админки</label>
                                    <input type="text" name="new_admin_password" class="form-control border-danger" placeholder="Введите новый пароль">
                                    <small class="text-muted">Оставьте пустым, если не хотите менять.</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-danger">Секретный Email для сброса</label>
                                    <input type="email" name="admin_recovery_email" class="form-control border-danger" value="<?= htmlspecialchars($settings['admin_recovery_email'] ?? '') ?>" placeholder="admin@example.com">
                                    <small class="text-muted">На эту почту придет ссылка, если вы нажмете "Забыли пароль".</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <button type="submit" name="save" class="btn btn-success btn-lg shadow btn-float">
                <i class="fas fa-save me-2"></i> СОХРАНИТЬ ВСЁ
            </button>

        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>