<?php
include_once('../db.php');
session_start();


$status = 'Нет активных заявок'; // Статус по умолчанию
$titles = []; // Массив для названий вакансий
$notifications = []; // Массив для уведомлений


if (!isset($_SESSION['user_id'])) {
    die("Ошибка: Пользователь не авторизован");
}

$user_id = (int) $_SESSION['user_id'];

//    Получаем уведомления
$sql_notifications = "SELECT * FROM notification WHERE user_id = $user_id ORDER BY created_at DESC";
$result_notifications = $connect->query($sql_notifications);

if ($result_notifications) {
    while ($row = $result_notifications->fetch_assoc()) {
        $notifications[] = $row;
    }
} else {
    die("Ошибка при получении уведомлений: " . $connect->error);
}

//Проверяем заявки пользователя
$sql_applications = "SELECT card_id FROM application WHERE application_id = $user_id";
$result_applications = $connect->query($sql_applications);

if ($result_applications && $result_applications->num_rows > 0) {
    $status = 'На рассмотрении';

    //Для каждой заявки получаем название вакансии
    while ($application = $result_applications->fetch_assoc()) {
        $card_id = (int) $application['card_id'];
        $sql_vacancy = "SELECT title FROM cataloge WHERE card_id = $card_id";
        $result_vacancy = $connect->query($sql_vacancy);

        if ($result_vacancy && $result_vacancy->num_rows > 0) {
            $vacancy = $result_vacancy->fetch_assoc();
            $titles[] = $vacancy['title'];
        } else {
            $titles[] = "Вакансия #$card_id (не найдена)";
        }
    }
}

?>