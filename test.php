<?php
session_start();

if (empty($_SESSION['user_id'])) {
    header("Location: login_form.html");
    exit();
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Профиль</title>
</head>

<body>
    <h1>Привет, <?php echo htmlspecialchars($_SESSION['user_login']); ?>!</h1>
    <a href="logout.php">Выйти</a>
    <? echo $_SESSION['user_id'] ?>
</body>

</html>