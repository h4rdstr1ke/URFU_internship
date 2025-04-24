<?php
// Включение отчета об ошибках
/*error_reporting(E_ALL);
ini_set('display_errors', 1);*/

// Проверка пути к файлу БД (для отладки)
/*echo realpath(__DIR__ . '/../db.php');*/

include_once __DIR__ . '/../db.php';

session_start();

// Проверка авторизации пользователя
if (empty($_SESSION['user_id'])) {
    header("Location: ../registration.html");
    exit();
}

$userId = (int) $_SESSION['user_id'];
$application_id = isset($_GET['user_id']) ? (int) $_GET['user_id'] : 0;

// Определяем, чей профиль загружать: текущего пользователя или другого (по application_id)
$targetUserId = ($application_id > 0) ? $application_id : $userId;

// Защита от SQL-инъекций с использованием подготовленных запросов
$stmt = $connect->prepare("SELECT * FROM personal_account WHERE ID = ?");
$stmt->bind_param("i", $targetUserId);
$stmt->execute();
$result = $stmt->get_result();

if (!$result || $result->num_rows === 0) {
    die("Ошибка: Данные пользователя не найдены");
}

$userData = $result->fetch_assoc();
$name = trim($userData['surname'] . ' ' . $userData['name'] . ' ' . $userData['patronymic']);

// Получаем данные из personal_about
$stmt = $connect->prepare("SELECT * FROM personal_about WHERE ID = ?");
$stmt->bind_param("i", $targetUserId);
$stmt->execute();
$aboutResult = $stmt->get_result();

if (!$aboutResult || $aboutResult->num_rows === 0) {
    die("Ошибка: Дополнительные данные не найдены");
}

$aboutData = $aboutResult->fetch_assoc();