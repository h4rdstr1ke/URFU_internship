<?php
include_once('db.php');
include_once('php/workman_func.php');

session_start();

if (empty($_SESSION['user_id'])) {
    header("Location: registration.html");
    exit();
}



//echo 'ID:' . $_SESSION['user_id'];
//echo $_SESSION['admin_id']
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Отклики на вакансии</title>
    <style>
        .response {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
        }
    </style>
    <link rel="stylesheet" href="pages/workman_account.css" />
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
    <h2>Отклики</h2>

    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="response">
                <div class="response-actions">
                    <a href="personal_account.php?user_id=<?= $row['application_id'] ?>" class="response-link">Личный
                        кабинет</a>
                    <form method="post" action="php/delete_application.php" style="display: inline;">
                        <input type="hidden" name="application_id" value="<?= $row['application_id'] ?>">
                        <input type="hidden" name="number_application" value="<?= $row['number_application'] ?>">
                        <button type="submit" name="delete_application">Удалить заявку</button>
                        <button type="submit" name="accept_application">Принять заявку</button>
                    </form>
                </div>
                <p><strong>Заявка:</strong> <?= htmlspecialchars($row['application']) ?></p>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>Нет откликов для отображения.</p>
    <?php endif; ?>

    <button>Создать вакансию</button><br>

    <form method="post" action="php/application_create.php">
        <input name="title" placeholder="Укажите название вакансии" required>
        <input name="smallDescription" placeholder="Краткое описание" required>
        <textarea name="description" placeholder="Полное описание" required></textarea>
        <button>Создать вакансию</button>
    </form>
    <a href="php/logout.php">Выйти</a>

    <h2>Архив</h2>
    <?php if ($applicationAccept->num_rows > 0): ?>
        <?php while ($arr = $applicationAccept->fetch_assoc()): ?>
            <a href="personal_account.php?user_id=<?= $arr['application_id'] ?>" class="response-link">Личный
                кабинет</a>
            <div class="response">
                <div class="response-actions">
                    <p><strong>Заявка:</strong> <?= htmlspecialchars($arr['application']) ?></p>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>Нет архива для отображения.</p>
    <?php endif; ?>

</body>

</html>