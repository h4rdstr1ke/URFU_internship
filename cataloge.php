<?php
session_start();
echo $_SESSION['user_id'];
$name = 'agusha';
$description = 'dasdasdasfdsfsdfasdasd'
    ?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Каталог</title>

</head>

<body>
    <a href="index.html">Главная</a>
    <div class="card">
        <h1><?= $name ?></h1>
        <p><?= $description ?></p>
    </div>
</body>

</html>