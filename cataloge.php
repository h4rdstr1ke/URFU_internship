<?php
include_once('db.php');
session_start();

$result = $connect->query("SELECT * FROM cataloge");
$adminID = $_SESSION['admin_id'];
//echo $adminID;
//echo $_SESSION['user_id']

?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Каталог</title>
    <link rel="stylesheet" href="pages/cataloge.css" />
</head>

<body>

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

    <div class="cataloge-search">
        <span>фильтр </span><input type="text" id="searchInput" placeholder="Поиск..." />
        <button onclick="searchPage()">Найти</button>
    </div>

    <div class="card-container">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="card" onclick="window.location.href='application.php?card_id=<?= $row['card_id'] ?>'">
                    <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $row['admin_id']) { ?>
                        <button
                            onclick="event.stopPropagation(); window.location.href='php/cataloge_delete.php?card_id=<?= $row['card_id'] ?>'">Удалить</button>
                    <?php } ?>
                    <h3><?= htmlspecialchars($row['title']) ?></h3>
                    <p><strong>Краткое описание:</strong></p>
                    <p><?= htmlspecialchars($row['smallDescription']) ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="no-data">
                <p>Нет доступных позиций в каталоге</p>
            </div>
        <?php endif; ?>
    </div>
</body>
<script src="scripts/cataloge.js"></script>

</html>