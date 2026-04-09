<?php
// Подключаем сам файл библиотеки
require_once 'Medoo.php'; 

use Medoo\Medoo;

$config = [
        'type' => 'mysql',
        'host' => 'localhost',
        'database' => 'evakua62_evakuator_db',
        'username' => 'evakua62_admin',
        'password' => '13d13d13D!',
        'charset' => 'utf8mb4'
];

try {
    $db = new Medoo($config);

} catch (Exception $e) {
    die("Ошибка подключения: " . $e->getMessage());
}