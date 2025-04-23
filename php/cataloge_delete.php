<?php

session_start();
include_once('../db.php');

$card_id = isset($_GET['card_id']) ? intval($_GET['card_id']) : 0;

if ($card_id <= 0) {
    die("Ошибка: Неверный ID карточки");
}

$sql_delete = "DELETE FROM cataloge WHERE card_id = $card_id";
$connect->query($sql_delete);

header("Location '../cataloge.php'")

    ?>