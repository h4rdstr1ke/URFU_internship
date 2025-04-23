<?php
require_once '../db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Необходима авторизация']);
    exit;
}

$userId = $_SESSION['user_id'];
$admin_id = $_POST['admin_id'] ?? 0;
$application = trim($_POST['application'] ?? '');
$card_id = trim($_POST['card_id'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Проверка наличия поля
    if (!isset($_POST['application'])) {
        die('Ошибка: поле application отсутствует');
    }

    $application = trim($_POST['application']);

    // Проверка на пустое поле
    if (empty($application)) {
        die('Ошибка: поле не может быть пустым');
    }

    // Проверка минимальной длины
    if (strlen($application) < 10) {
        die('Ошибка: пожалуйста, введите более подробные сведения (минимум 10 символов)');
    }

    // Проверка максимальной длины
    if (strlen($application) > 1000) {
        die('Ошибка: слишком длинный текст (максимум 1000 символов)');
    }

    // Проверка на допустимые символы
    if (!preg_match('/^[\p{L}\p{N}\s\.,!?-]+$/u', $application)) {
        die('Ошибка: текст содержит недопустимые символы');
    }

    // Экранирование данных 
    $application = $connect->real_escape_string($application);
    $card_id = $connect->real_escape_string($card_id);

    $sql = "INSERT INTO application (application_id, application, admin_id, card_id) 
            VALUES ('$userId', '$application', '$admin_id', '$card_id')";

    if ($connect->query($sql)) {
        header("Location: ../cataloge.php");
        exit();
    } else {
        die("Ошибка SQL: " . $connect->error);
    }
}
?>