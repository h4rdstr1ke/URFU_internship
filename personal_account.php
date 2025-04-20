<?php
session_start();

if (empty($_SESSION['user_id'])) {
  header("Location: login_form.html");
  exit();
}
?>
<!DOCTYPE html>
<html lang="ru">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Личный кабинет</title>
  <link rel="stylesheet" href="vendor/normalize.css" />
  <link rel="stylesheet" href="pages/personal-account.css" />
</head>

<body>
  <header class="header">
    <img src="images/Logo.svg" />
    <nav class="header-func">
      <a href="index.html">Главная</a>
      <a href="index.html">Каталог</a>
      <a href="#">О нас</a>
      <a href="personal_account.html"><img class="header__personal_account" src="images/Personal_Account.svg"
          height="60px" width="60px" alt="personal_account" /></a>
    </nav>
  </header>
  <div class="container">
    <h1>Анкета стажера</h1>

    <div class="profile-header">
      <div class="photo-container">
        <img id="profilePhoto" src="https://via.placeholder.com/120" alt="Фото профиля" />
      </div>
      <div class="profile-info">
        <button id="changePhoto" class="btn-secondary">Сменить фото</button>
      </div>
    </div>

    <div class="form-group">
      <label for="fullName" class="required-field">ФИО</label>
      <input type="text" id="fullName" value="Иванов Иван Иванович" disabled />
      <div id="fullNameError" class="error">
        ФИО должно быть на русском языке (Фамилия Имя Отчество)
      </div>
    </div>

    <div class="form-group">
      <label for="email" class="required-field">Email</label>
      <input type="email" id="email" value="ivanov@example.com" disabled />
      <div id="emailError" class="error">Введите корректный email</div>
    </div>

    <div class="form-group">
      <label for="academicGroup" class="required-field">Академическая группа</label>
      <input type="text" id="academicGroup" value="ИКБО-01-21" disabled />
      <div id="academicGroupError" class="error">
        Введите номер академической группы
      </div>
    </div>

    <div class="form-group">
      <label for="telegram">Telegram</label>
      <input type="text" id="telegram" value="@ivanov" disabled placeholder="@username" />
      <div id="telegramError" class="error">
        Никнейм должен начинаться с @ и содержать только буквы, цифры и
        подчеркивания
      </div>
    </div>

    <div class="form-group">
      <h2>Достижения</h2>
      <div id="achievementsViewMode">
        <div id="linksContainer">
          <a href="https://github.com/ivanov" class="achievement-link" target="_blank">Достижение 1:
            https://github.com/ivanov</a>
          <a href="https://example.com/portfolio" class="achievement-link" target="_blank">Достижение 2:
            https://example.com/portfolio</a>
        </div>
      </div>
      <div id="achievementsEditMode" style="display: none">
        <div class="form-group">
          <label for="link1">Ссылка на достижение 1</label>
          <input type="text" id="link1" placeholder="https://example.com" />
          <div id="error1" class="error"></div>
        </div>
        <div class="form-group">
          <label for="link2">Ссылка на достижение 2</label>
          <input type="text" id="link2" placeholder="https://example.com" />
          <div id="error2" class="error"></div>
        </div>
        <div class="form-group">
          <label for="link3">Ссылка на достижение 3</label>
          <input type="text" id="link3" placeholder="https://example.com" />
          <div id="error3" class="error"></div>
        </div>
      </div>
    </div>

    <div class="buttons">
      <button id="editButton" class="btn-primary">Редактировать</button>
      <button id="saveButton" class="btn-primary" style="display: none">
        Сохранить
      </button>
      <button id="logoutButton" class="btn-danger">Выйти</button>
    </div>
  </div>

  <!-- Модальное окно для загрузки фото -->
  <div id="photoModal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h3>Загрузите новое фото</h3>
      <input type="file" id="photoUpload" accept="image/*" />
      <div style="margin-top: 20px">
        <button id="uploadButton" class="btn-primary">Загрузить</button>
        <button id="cancelUpload" class="btn-secondary" style="margin-left: 10px">
          Отмена
        </button>
      </div>
    </div>
  </div>
  <script src="scripts/personal_account.js"></script>
</body>

</html>