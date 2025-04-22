<?php
include 'db.php';
session_start();

$login = trim($_POST['login']);
$email = trim($_POST['email']);
$pass = trim($_POST['password']);

// Валидация
if (mb_strlen($login) < 5)
    die("Логин должен быть от 5 символов");
if (!filter_var($email, FILTER_VALIDATE_EMAIL))
    die("Некорректный email");
if (mb_strlen($pass) < 5)
    die("Пароль должен быть от 5 символов");

$hashedPass = md5($pass . 'SALT123');

// Проверка логина
$check = $connect->query("SELECT * FROM register_user WHERE login = '$login'");
if ($check->num_rows > 0)
    die("Этот логин уже занят");

// Транзакция
$connect->autocommit(false);

try {
    // 1. Добавляем пользователя
    $sql = "INSERT INTO register_user (login, password, email) VALUES ('$login', '$hashedPass', '$email')";
    if (!$connect->query($sql))
        throw new Exception("Ошибка добавления пользователя");

    $user_id = $connect->insert_id; // Получаем ID до коммита

    // 2. Добавляем в personal_account
    $sql2 = "INSERT INTO personal_account (email) VALUES ('$email')";
    if (!$connect->query($sql2))
        throw new Exception("Ошибка добавления аккаунта");
    // 3.создаем personal_about

    $sql3 = "INSERT INTO personal_about (achievementOne) VALUES ('')";
    if (!$connect->query($sql3))
        throw new Exception("Ошибка создания about");
    $connect->commit();

    // Устанавливаем сессию ПОСЛЕ коммита
    $_SESSION['user_id'] = $user_id;
    $_SESSION['user_login'] = $login;

    header("Location: personal_account.php");
    exit;

} catch (Exception $e) {
    $connect->rollback();
    die("Ошибка регистрации: " . $e->getMessage());
} finally {
    $connect->autocommit(true);
    $connect->close();
}