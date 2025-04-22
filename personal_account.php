<?php


session_start();

if (empty($_SESSION['user_id'])) {
  header("Location: registration.html");
  exit();
}
echo 'ID:' . $_SESSION['user_id'];

require_once('db.php');
require_once('php/get_account.php');

?>

<!DOCTYPE html>
<html lang="ru">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="pages/personal-account.css" />
  <title>Персональный аккаунт</title>
</head>

<body>
  <a href="index.html">Главная</a>
  <form method="post" action="php/save_account.php" id="accountForm">
    <label>ФИО</label>
    <input type="text" name="fullName" value="<?= htmlspecialchars($name ?? '') ?>" placeholder="ФИО" disabled
      required><br>
    <span class="error" id="fullNameError"></span><br>

    <label>Академическая Группа</label>
    <input type="text" name="academicGroup" value="<?= htmlspecialchars($userData['academic'] ?? '') ?>"
      placeholder="Академическая Группа" disabled required><br>
    <span class="error" id="academicGroupError"></span><br>

    <label>email</label>
    <input type="text" name="email" value="<?= htmlspecialchars($userData['email'] ?? '') ?>" placeholder="email"
      disabled required><br>
    <span class="error" id="emailError"></span><br>

    <label>Telegram</label>
    <input type="text" name="telegram" value="<?= htmlspecialchars($userData['telegram'] ?? '') ?>"
      placeholder="Telegram" disabled><br>
    <span class="error" id="telegramError"></span><br>

    <input type="text" name="achievementOne" value="<?= htmlspecialchars($aboutData['achievementOne'] ?? '') ?>"
      placeholder="Достижение 1" disabled><br>
    <span class="error" id="achievementOneError"></span><br>

    <input type="text" name="achievementTwo" value="<?= htmlspecialchars($aboutData['achievementTwo'] ?? '') ?>"
      placeholder="Достижение 2" disabled><br>
    <span class="error" id="achievementTwoError"></span><br>

    <input type="text" name="achievementThree" value="<?= htmlspecialchars($aboutData['achievementThree'] ?? '') ?>"
      placeholder="Достижение 3" disabled><br>
    <span class="error" id="achievementThreeError"></span><br>

    <button type="submit" id="saveButton" style="display: none;">Сохранить</button>
    <textarea type="text" name="about" placeholder="Обо мне"
      disabled><?= htmlspecialchars($aboutData['about'] ?? '') ?></textarea><br>
    <span class="error" id="aboutError"></span><br>
  </form>

  <button type="submit" id="editButton">Редактировать</button>
  <a href="php/logout.php">Выйти</a>

  <?php if (isset($_SESSION['errors'])): ?>
    <div class="errors">
      <?php foreach ($_SESSION['errors'] as $error): ?>
        <p><?= htmlspecialchars($error) ?></p>
      <?php endforeach; ?>
    </div>
    <?php unset($_SESSION['errors']); ?>
  <?php endif; ?>
  <?php if (isset($_SESSION['success'])): ?>
    <div class="success"><?= htmlspecialchars($_SESSION['success']) ?></div>
    <?php unset($_SESSION['success']); ?>
  <?php endif; ?>
</body>
<script src="scripts/personal_account.js"></script>

</html>