<?php
include_once('db.php');
session_start();

$result = $connect->query("SELECT * FROM cataloge");
$adminID = $_SESSION['admin_id'];
echo $adminID;
echo $_SESSION['user_id']

    ?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Каталог</title>
    <style>

    </style>
</head>

<body>
    <a href="index.html">Главная</a>
    <div class="card-container">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $row['admin_id']) { ?>
                    <button onclick="window.location.href='php/cataloge_delete.php?card_id=<?= $row['card_id'] ?>'">Удалить</button>
                <?php } ?>
                <div class="card" onclick="window.location.href='application.php?card_id=<?= $row['card_id'] ?>'">
                    <h3><?= htmlspecialchars($row['title']) ?></h3>
                    <p><strong>Краткое описание:</strong> <?= htmlspecialchars($row['smallDescription']) ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="no-data">
                <p>Нет доступных позиций в каталоге</p>
            </div>
        <?php endif; ?>

    </div>
</body>

</html>