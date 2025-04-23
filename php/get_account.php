<?php
//Проверка на ошибки

error_reporting(E_ALL);
ini_set('display_errors', 1);
echo realpath(__DIR__ . '../db.php');

include_once '../db.php';

session_start();

if (empty($_SESSION['user_id'])) {
    header("Location: ../registration.html");
    exit();
}

$userId = $_SESSION['user_id'];

$result = $connect->query("SELECT * FROM personal_account WHERE ID = '$userId'");
if (!$result || $result->num_rows === 0) {
    die("Ошибка: Данные не найдены");
}

$userData = $result->fetch_assoc(); // Получаем данные пользователя
// Объединяем name, surname и patronymic в одну строку
$name = $userData['surname'] . ' ' . $userData['name'] . ' ' . $userData['patronymic'];

$result = $connect->query("SELECT * FROM personal_about WHERE ID = '$userId'");
if (!$result || $result->num_rows === 0) {
    die("Ошибка: Данные не найдены");
}
$aboutData = $result->fetch_assoc();
/*$data = array(); // Создаем пустой массив
while ($row = $result->fetch_assoc()) {
    $data[] = $row; // Добавляем каждую строку в массив
}
// Выводим весь массив
print_r($data);*/

?>