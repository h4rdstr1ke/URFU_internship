<?php
include_once('../db.php');

session_start();

$admin_id = $_SESSION['user_id'];

$title = trim($_POST['title'] ?? '');
$smallDescription = trim($_POST['smallDescription'] ?? '');
$description = trim($_POST['description'] ?? '');

if (empty($title) || empty($smallDescription) || empty($description)) {
    $_SESSION['error_message'] = "Все поля должны быть заполнены!";
    header("Location: ../workman_account.php");
    exit();
}

$sql = "INSERT INTO cataloge (title, smallDescription, description, admin_id) VALUES ('$title', '$smallDescription', '$description', '$admin_id')";
$result = $connect->query($sql);
if (!$result) {
    die("Ошибка SQL: " . $connect->error);
}
header("Location: ../workman_account.php");
exit();
?>