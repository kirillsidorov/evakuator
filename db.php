<?php
// Подключаем сам файл библиотеки
require_once 'Medoo.php'; 

use Medoo\Medoo;

// Креды подключения берём из файла ВЫШЕ корня сайта (вне git и вне HTTP-доступа).
// db.php лежит в public_html, поэтому __DIR__.'/../' — всегда каталог над webroot.
$secrets_file = __DIR__ . '/../secrets.php';
if (!is_file($secrets_file)) {
    die('Не найден secrets.php над корнем сайта. Скопируйте secrets.sample.php в ../secrets.php и впишите доступы к БД.');
}
$secrets = require $secrets_file;

$config = [
        'type' => 'mysql',
        'host' => $secrets['db_host'] ?? 'localhost',
        'database' => $secrets['db_name'] ?? '',
        'username' => $secrets['db_user'] ?? '',
        'password' => $secrets['db_pass'] ?? '',
        'charset' => 'utf8mb4'
];

try {
    $db = new Medoo($config);

} catch (Exception $e) {
    die("Ошибка подключения: " . $e->getMessage());
}