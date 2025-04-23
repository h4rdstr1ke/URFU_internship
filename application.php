<?php
session_start();

include_once('db.php');

echo $_SESSION['user_id'];
echo $_GET['card_id'];

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
echo 'ADMIN' . $admin_id;
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Регистрация</title>
</head>

<>
    <a href="index.html">Главная</a>
    <div>
        <p><?= $description ?></p>
    </div>
    <button>откликнуться</button>


    <form method="post" action="php/application_func.php" id="applicationForm">
        <textarea type="text" name="application"
            placeholder="Напишите какие-нибудь сведения, которые могут быть для нас полезны" required></textarea>
        <div id="error" style="color: red; margin: 5px 0;"></div>
        <input type="hidden" name="admin_id" value="<?= $admin_id ?>">
        <input type="hidden" name="card_id" value="<?= $card_id ?>">
        <button type="submit">Отправить</button>
    </form>

    <p><?= htmlspecialchars($title ?? '') ?></p>
    </body>
    <script src="scripts/application_func.js"></script>

</html>