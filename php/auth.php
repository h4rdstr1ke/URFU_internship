<?php
include '../db.php';
session_start();



$login = trim($_POST['login']);
$pass = trim($_POST['password']);

$hashedPass = md5($pass . 'SALT123');

$result = $connect->query("SELECT * FROM register_user WHERE login = '$login' AND password = '$hashedPass'");

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $_SESSION['user_id'] = $user['ID'];
    $_SESSION['user_login'] = $user['login'];
    $_SESSION['admin_id'] = 1; // Можно сохранить ID админа
    header("Location: ../personal_account.php");
} else {
    die("Неверный логин или пароль");
}

$connect->close();
?>