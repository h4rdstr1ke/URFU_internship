<?php
session_start();

include_once('db.php');

//echo $_SESSION['user_id'];
//echo $_GET['card_id'];

// Получаем ID карточки и проверяем его
$card_id = isset($_GET['card_id']) ? intval($_GET['card_id']) : 0;

if ($card_id <= 0) {
    die("Ошибка: Неверный ID карточки");
}
// Используем подготовленные запросы для безопасности
$sql = "SELECT title, description, admin_id FROM cataloge WHERE card_id = ?";
$stmt = $connect->prepare($sql);

if (!$stmt) {
    die("Ошибка подготовки запроса: " . $connect->error);
}
$stmt->bind_param("i", $card_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("Карточка с ID $card_id не найдена");
}
$row = $result->fetch_assoc();
$title = htmlspecialchars($row['title']);
$description = htmlspecialchars($row['description']);
$admin_id = intval($row['admin_id']);
//echo 'ADMIN' . $admin_id;
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="pages/application.css" />
    <title>Заявка</title>
</head>
<header class="header">
    <img src="images/Logo.svg" />
    <nav class="header-func">
        <a href="index.html">Главная</a>
        <a href="cataloge.php">Каталог</a>
        <a href="#">О нас</a>
        <a href="personal_account.php"><img class="header__personal_account" src="images/Personal_Account.svg"
                height="60px" width="60px" alt="personal_account" /></a>
    </nav>
</header>

<p>Название вакансии: <?= htmlspecialchars($title ?? '') ?></p>

<div class="application-info">
    <div class="application-description">
        <p><?= $description ?></p>
    </div>
    <button id="respondBtn">Откликнуться</button>
</div>

<div class="application-post" style="display: none;">
    <button id="backBtn" style="margin-bottom: 10px;">← Назад</button>
    <form method="post" action="php/application_func.php" id="applicationForm">
        <textarea type="text" name="application"
            placeholder="Напишите какие-нибудь сведения, которые могут быть для нас полезны" required></textarea>
        <div id="error" style="color: red; margin: 5px 0;"></div>
        <input type="hidden" name="admin_id" value="<?= $admin_id ?>">
        <input type="hidden" name="card_id" value="<?= $card_id ?>">
        <button type="submit">Отправить</button>
    </form>
</div>
</body>
<script src="scripts/application_func.js"></script>

</html>