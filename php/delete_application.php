<?php
session_start();
include_once('../db.php');

// Получаем и проверяем данные
$application_id = (int) $_POST['application_id'];
$number_application = (int) $_POST['number_application'];

if (isset($_POST['accept_application'])) {
    // Получаем данные конкретной заявки в application
    $sql_select = "SELECT * FROM application 
                  WHERE application_id = $application_id 
                  AND number_application = $number_application";

    $result = $connect->query($sql_select);
    if ($result && $result->num_rows > 0) {
        $app_data = $result->fetch_assoc();

        // Получаем title из cataloge
        $sql_titleSelect = "SELECT title FROM cataloge WHERE card_id = '{$app_data['card_id']}'";
        $result_titleSelect = $connect->query($sql_titleSelect);

        if ($result_titleSelect && $result_titleSelect->num_rows > 0) {
            $titleSelect = $result_titleSelect->fetch_assoc();
            $title = $titleSelect['title'];
        } else {
            $title = 'Без названия'; // Значение по умолчанию
        }

        // Архивируем заявку
        $sql_archive = "INSERT INTO application_accept 
                       (number_application, application_id, application, admin_id, card_id) 
                       VALUES (
                           '{$app_data['number_application']}', 
                           '{$app_data['application_id']}', 
                           '{$app_data['application']}', 
                           '{$_SESSION['user_id']}', 
                           '{$app_data['card_id']}'
                       )";

        if ($connect->query($sql_archive)) {
            // Отправляем уведомление пользователю
            $sql_notify = "INSERT INTO notification (user_id, notification, title) 
                          VALUES ({$app_data['application_id']}, 'Ваша заявка принята', '$title')";
            $connect->query($sql_notify);

            // Удаляем оригинальную заявку
            $sql_delete = "DELETE FROM application 
                          WHERE application_id = $application_id 
                          AND number_application = $number_application";
            $connect->query($sql_delete);

            $_SESSION['message'] = "Заявка №$number_application успешно принята";
        } else {
            $_SESSION['error'] = "Ошибка архивации: " . $connect->error;
        }
    } else {
        $_SESSION['error'] = "Заявка №$number_application не найдена";
    }
} elseif (isset($_POST['delete_application'])) {
    // Для отклонения заявки тоже title
    $sql_title = "SELECT a.card_id, c.title 
                 FROM application a
                 LEFT JOIN cataloge c ON a.card_id = c.card_id
                 WHERE a.application_id = $application_id 
                 AND a.number_application = $number_application";

    $title_result = $connect->query($sql_title);
    $title_data = $title_result->fetch_assoc();
    $title = $title_data['title'] ?? 'Без названия';

    // Отправляем уведомление
    $sql_notify = "INSERT INTO notification (user_id, notification, title) 
                  VALUES ($application_id, 'Ваша заявка отклонена', '$title')";
    $connect->query($sql_notify);

    // Удаляем заявку
    $sql_delete = "DELETE FROM application 
                  WHERE application_id = $application_id 
                  AND number_application = $number_application";
    $connect->query($sql_delete);

    $_SESSION['message'] = "Заявка №$number_application отклонена";
}

header("Location: " . $_SERVER['HTTP_REFERER']);
exit();
?>