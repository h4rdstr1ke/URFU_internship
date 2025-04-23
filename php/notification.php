<?php
include_once('../db.php');
session_start();
$status = '';
$title = '';
$titles = []; // Массив для хранения названий

// Проверка авторизации
if (!isset($_SESSION['user_id'])) {
    die("Ошибка: Пользователь не авторизован");
}

$user = $_SESSION['user_id'];

// Получаем уведомления
$sql = "SELECT * FROM notification WHERE user_id = $user ORDER BY created_at DESC";
$result = $connect->query($sql);

if (!$result) {
    die("Ошибка SQL: " . $connect->error);
}

$notifications = [];
while ($row = $result->fetch_assoc()) {
    $notifications[] = $row;
}

// Проверяем заявки на рассмотрении
$sql2 = "SELECT card_id FROM application WHERE application_id = $user";
$result2 = $connect->query($sql2);

if ($result2 && $result2->num_rows > 0) {
    $status = 'На рассмотрении';
    $row2 = $result2->fetch_assoc();
    $card_id = $row2['card_id'];

    // Получаем названия
    $sql3 = "SELECT title FROM cataloge WHERE card_id = $card_id";
    $result3 = $connect->query($sql3);

    if ($result3 && $result3->num_rows > 0) {
        while ($row3 = $result3->fetch_assoc()) {
            $titles[] = $row3['title'];
        }
        $title = $titles[0]; // Первое название как основное
    }
}
?>