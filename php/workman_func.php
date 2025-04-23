<?php
include_once('../db.php');

session_start();

$admin_id = $_SESSION['user_id'];

$result = $connect->query("SELECT * FROM application WHERE admin_id = '$admin_id'");
if (!$result || $result->num_rows === 0) {

}

$applicationAccept = $connect->query("SELECT * FROM application_accept WHERE admin_id = '$admin_id'");
if (!$result || $result->num_rows === 0) {
}

//$userData = $result->fetch_assoc(); // Получаем данные пользователя





?>