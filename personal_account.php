<?php

session_start();

$workman_status = 0;

if (empty($_SESSION['user_id'])) {
  header("Location: registration.html");
  exit();
} elseif ($_SESSION['user_id'] == 1 && $_GET['user_id'] == 0) {
  header("Location: workman_account.php");
  exit();
} elseif ($_SESSION['user_id'] == 1 && $_GET['user_id'] != 0) {
  $workman_status = 1;
}
;

$application_id = intval($_GET['user_id']);



//echo 'application_id' . $application_id;
//echo 'ID:' . $_SESSION['user_id'];
//echo 'status' . $workman_status;
require_once('php/get_account.php');
require_once('db.php');
require_once('php/notification.php');
?>

<!DOCTYPE html>
<html lang="ru">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="pages/personal-account.css" />
  <title>Персональный аккаунт</title>
  <style>
    /*Временно */
    .header {
      margin-bottom: 50px;
    }
  </style>
</head>
<header class="header">
  <img src="images/Logo.svg" />
  <nav class="header-func">
    <a href="index.html">Главная</a>
    <a href="cataloge.php">Каталог</a>
    <a href="#">О нас</a>
    <a href="personal_account.php"><img class="header__personal_account" src="images/Personal_Account.svg" height="60px"
        width="60px" alt="personal_account" /></a>
  </nav>
</header>
<form method="post" action="php/save_account.php" id="accountForm" class="content">
  <section class="photo-achievement">
    <div class="photo-block">
      <img src="images/karnegi.svg" alt="karnegi" width="315px" />
      <button <?= $workman_status == 1 ? 'style="display: none;"' : '' ?>>Сменить фото</button>
    </div>
    <div class="achievement">
      <h2>Достижения</h2>
      <input type="text" name="achievementOne" value="<?= htmlspecialchars($aboutData['achievementOne'] ?? '') ?>"
        placeholder="Достижение 1" disabled><br>
      <span class="error" id="achievementOneError"></span><br>

      <input type="text" name="achievementTwo" value="<?= htmlspecialchars($aboutData['achievementTwo'] ?? '') ?>"
        placeholder="Достижение 2" disabled><br>
      <span class="error" id="achievementTwoError"></span><br>

      <input type="text" name="achievementThree" value="<?= htmlspecialchars($aboutData['achievementThree'] ?? '') ?>"
        placeholder="Достижение 3" disabled><br>
      <span class="error" id="achievementThreeError"></span><br>
    </div>
  </section>
  <section class="info-about">
    <div class="info-conatiner">
      <div class="info-card">
        <label>ФИО</label>
        <input type="text" name="fullName" value="<?= htmlspecialchars($name ?? '') ?>" placeholder="ФИО" disabled
          required><br>
        <span class="error" id="fullNameError"></span><br>
      </div>

      <div class="info-card">
        <label>Академическая Группа</label>
        <input type="text" name="academicGroup" value="<?= htmlspecialchars($userData['academic'] ?? '') ?>"
          placeholder="Академическая Группа" disabled required><br>
        <span class="error" id="academicGroupError"></span><br>
      </div>

      <div class="info-card">
        <label>email</label>
        <input type="text" name="email" value="<?= htmlspecialchars($userData['email'] ?? '') ?>" placeholder="email"
          disabled required><br>
        <span class="error" id="emailError"></span><br>
      </div>

      <div class="info-card">
        <label>Telegram</label>
        <input type="text" name="telegram" value="<?= htmlspecialchars($userData['telegram'] ?? '') ?>"
          placeholder="Telegram" disabled><br>
        <span class="error" id="telegramError"></span><br>
      </div>
    </div>
    <div class="about">
      <textarea type="text" name="about" placeholder="Обо мне"
        disabled><?= htmlspecialchars($aboutData['about'] ?? '') ?></textarea><br>
      <span class="error" id="aboutError"></span><br>
    </div>
    <button type="button" id="editButton" <?= $workman_status == 1 ? 'style="display: none;"' : '' ?>>Редактировать</button>
    <button type="submit" id="saveButton" style="display: none;">Сохранить</button>
  </section>
</form>

<p></p>
<a href="php/logout.php" <?= $workman_status == 1 ? 'style="display: none;"' : '' ?>>Выйти</a>

<?php
// Проверяем, есть ли уведомления
if ($workman_status == 0): ?>
  <?php if ($status == 'На рассмотрении'): ?>
    <div class="status-notice">
      На рассмотрении:
      <?php
      if (!empty($titles)) {
        echo htmlspecialchars(implode(', ', $titles));
      } else {
        echo 'нет данных';
      }
      ?>
    </div>
  <?php endif; ?>

  <div class="all-notifications">
    <?php if (empty($notifications)): ?>
      <div class="no-notifications">
        У вас нет уведомлений
      </div>
    <?php else: ?>
      <?php foreach ($notifications as $notification): ?>
        <div class="notification-item 
                <?= (strpos($notification['notification'], 'принята') !== false ? 'accepted' : '') ?>
                <?= (strpos($notification['notification'], 'отклонена') !== false ? 'rejected' : '') ?>">

          <div class="notification-header">
            <h4><?= htmlspecialchars($notification['title'] ?? 'Уведомление') ?></h4>
            <?php if (strpos($notification['notification'], 'принята') !== false): ?>
              <span class="status accepted">Принята</span>
            <?php elseif (strpos($notification['notification'], 'отклонена') !== false): ?>
              <span class="status rejected">Отклонена</span>
            <?php endif; ?>
          </div>

          <div class="notification-content">
            <?= htmlspecialchars($notification['notification']) ?>
          </div>

          <div class="notification-date">
            <?= date('d.m.Y H:i', strtotime($notification['created_at'])) ?>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
<?php endif; ?>
</div>

<?php if (isset($_SESSION['errors'])): ?>
  <div class="errors">
    <?php foreach ($_SESSION['errors'] as $error): ?>
      <p><?= htmlspecialchars($error) ?></p>
    <?php endforeach; ?>
    <?php unset($_SESSION['errors']); ?>
  </div>
<?php endif; ?>

<?php if (isset($_SESSION['success'])): ?>
  <div class="success"><?= htmlspecialchars($_SESSION['success']) ?></div>
  <?php unset($_SESSION['success']); ?>
<?php endif;
?>

</body>
<script src="scripts/personal_account.js"></script>

</html>