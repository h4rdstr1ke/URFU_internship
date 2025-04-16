<?php

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "register_user";

$connect = mysqli_connect($servername, $username, $password, $dbname);

if (!$connect) {
    die("Connection Failed" . mysqli_connect_error());
} else {
} ?>