<?php
include_once('../db.php');

session_start();

$result = $connect->query("SELECT * FROM cataloge");
if (!$result || $result->num_rows === 0) {
    die("Ошибка: Данные не найдены");
}

header("Location: ../cataloge.php");
?>