<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "URFU_internship";
$port = 3306;

$connect = new mysqli($servername, $username, $password, $dbname, $port);

// Проверяем соединение
if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
} else {
    echo "Успешное подключение к базе данных!";
}
// Дополнительная проверка
$result = $connect->query("SELECT 1");
if ($result) {
    echo " База данных отвечает на запросы.";
} else {
    echo " Ошибка при выполнении тестового запроса: " . $connect->error;
}

// Создаем папку для загрузок
/*if (!file_exists('uploads')) {
    mkdir('uploads', 0777, true);
}*/

?>