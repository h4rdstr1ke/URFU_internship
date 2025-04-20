<?php
include 'db.php';
session_start();

$login = trim($_POST['login']);
$email = trim($_POST['email']);
$pass = trim($_POST['password']);

// Валидация
if (mb_strlen($login) < 5) {
    die("Логин должен быть от 5 символов");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Некорректный email");
}

if (mb_strlen($pass) < 5) {
    die("Пароль должен быть от 5 символов");
}

// Хеширование пароля
$hashedPass = md5($pass . 'SALT123');

// Проверка существования логина
$check = $connect->query("SELECT * FROM register_user WHERE login = '$login'");
if ($check->num_rows > 0) {
    die("Этот логин уже занят");
}

// Регистрация
$sql = "INSERT INTO register_user (login, password, email) VALUES ('$login', '$hashedPass', '$email')";

if ($connect->query($sql)) {
    $_SESSION['user_id'] = $connect->insert_id; // Сохраняем ID нового пользователя
    $_SESSION['user_login'] = $login;
    header("Location: test.php");
} else {
    die("Ошибка регистрации: " . $connect->error);
}

$connect->close();
?>