<?php
session_start();

if (empty($_SESSION['user_login'])) {
    header("Location: ../auth.php");
    exit();
}


// Можно добавить дополнительные проверки, например:
// - время последней активности
// - IP-адрес пользователя
?>