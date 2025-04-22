<?php
require_once '../db.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Необходима авторизация']);
    exit;
}
$userId = $_SESSION['user_id'];

// Обработка данных формы
$fullName = trim($_POST['fullName'] ?? '');
$email = trim($_POST['email'] ?? '');
$telegram = trim($_POST['telegram'] ?? '');
$academicGroup = trim($_POST['academicGroup'] ?? '');
$about = trim($_POST['about'] ?? '');
$achievementOne = $_POST['achievementOne'] ?? '';
$achievementTwo = $_POST['achievementTwo'] ?? '';
$achievementThree = $_POST['achievementThree'] ?? '';

echo "achievementOne: $achievementOne<br>";
echo "achievementTwo: $achievementTwo<br>";
echo "achievementThree: $achievementThree<br>";
echo "about: $about<br>";
echo "userId: $userId<br>";

// Разбиваем ФИО на Имя Фамилию Отчество

$fullName = trim($fullName); // Удаляем лишние пробелы 
$parts = preg_split('/\s+/', $fullName); // Разбиваем по одному или нескольким пробелам

$surname = $parts[0] ?? '';
$name = $parts[1] ?? '';
$patronymic = $parts[2] ?? '';


// Обновляем данные в personal_account


// Валидация данных
$errors = [];

// Валидация ФИО
if (empty($fullName)) {
    $errors[] = "ФИО является обязательным полем";
} else {
    $fullName = trim($fullName);
    $parts = preg_split('/\s+/', $fullName);

    if (count($parts) < 2) {
        $errors[] = "Укажите хотя бы Фамилию и Имя";
    } else {
        $surname = $parts[0] ?? '';
        $name = $parts[1] ?? '';
        $patronymic = $parts[2] ?? '';

        // Проверка фамилии
        if (!preg_match('/^[а-яА-ЯёЁ\-]{2,50}$/u', $surname)) {
            $errors[] = "Фамилия должна содержать только русские буквы и быть от 2 до 50 символов";
        }

        // Проверка имени
        if (!preg_match('/^[а-яА-ЯёЁ\-]{2,50}$/u', $name)) {
            $errors[] = "Имя должно содержать только русские буквы и быть от 2 до 50 символов";
        }

        // Проверка отчества (если есть)
        if (!empty($patronymic) && !preg_match('/^[а-яА-ЯёЁ\-]{2,50}$/u', $patronymic)) {
            $errors[] = "Отчество должно содержать только русские буквы и быть от 2 до 50 символов";
        }
    }
}

// dалидация email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Некорректный email адрес";
}

// валидация telegram 
/*if (!empty($telegram) && !preg_match('/^@[a-zA-Z0-9_]{5,32}$/', $telegram)) {
    $errors[] = "Telegram должен быть в формате @username (5-32 символа)";
}*/
if (!empty($telegram)) {
    // Удаляем все пробелы
    $telegram = str_replace(' ', '', $telegram);

    // Проверяем общий формат
    if (!preg_match('/^@[a-zA-Z0-9_]{5,32}$/', $telegram)) {
        $errors[] = "Telegram должен быть в формате @username (5-32 латинских букв, цифр или _)";
    }
}



// валидация academicGroup
if (empty($academicGroup)) {
    $errors[] = "Учебная группа обязательна для заполнения";
} elseif (!preg_match('/^[а-яА-ЯёЁa-zA-Z0-9\-]{2,20}$/u', $academicGroup)) {
    $errors[] = "Некорректный формат учебной группы";
}

// валидация about 
if (mb_strlen($about) > 1000) {
    $errors[] = "Описание не должно превышать 1000 символов";
}

// 6. Валидация достижений 
/*foreach ([$achievementOne, $achievementTwo, $achievementThree] as $achievement) {
    if (!empty($achievement) && mb_strlen($achievement) > 255) {
        $errors[] = "Каждое достижение не должно превышать 255 символов";
        break;
    }
}*/


function validateAchievementLink($link, $allowedDomains)
{
    if (empty($link)) {
        return true; // Необязательное поле
    }

    // Проверка, что это похоже на ссылку (содержит точку или слеши)
    if (strpos($link, '.') === false && strpos($link, '/') === false) {
        return false;
    }

    try {
        $url = parse_url($link);
        if (!isset($url['host'])) {
            $url = parse_url("https://" . $link);
            if (!isset($url['host'])) {
                return false;
            }
        }

        // Проверка что путь не пустой (не просто домен)
        if (!isset($url['path']) || $url['path'] === '/') {
            return false;
        }

        $host = str_replace('www.', '', $url['host']);
        return in_array($host, $allowedDomains);
    } catch (Exception $e) {
        return false;
    }
}

// Разрешенные домены
$allowedDomains = [
    'github.com',
    'gitlab.com',
    'codewars.com',
    'leetcode.com'
];

// Валидация каждого достижения
foreach (['achievementOne', 'achievementTwo', 'achievementThree'] as $field) {
    $value = trim($_POST[$field] ?? '');
    if (!empty($value)) {
        if (strpos($value, '.') === false && strpos($value, '/') === false) {
            $errors[] = "В поле " . ucfirst(str_replace('achievement', '', $field)) .
                " должна быть ссылка (содержать домен)";
        } elseif (!validateAchievementLink($value, $allowedDomains)) {
            $errors[] = "Ссылка в поле " . ucfirst(str_replace('achievement', '', $field)) .
                " должна быть с одного из доменов: " . implode(', ', $allowedDomains);
        }
    }
}


// Если есть ошибки - выводим их
if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    $_SESSION['old_data'] = $_POST;
    header("Location: ../personal_account.php");
    exit;
}

$sql1 = "UPDATE personal_account 
        SET 
            name = '$name',
            surname = '$surname',
            email = '$email',
            academic = '$academicGroup',
            patronymic = '$patronymic',
            telegram = '$telegram'

        WHERE ID = '$userId'";

$result = $connect->query($sql1);
if (!$result) {
    die("Ошибка SQL: " . $connect->error);
}

// Обновляем данные в personal_about
$sql2 = "UPDATE personal_about
        SET 
            achievementOne = '$achievementOne',
            achievementTwo = '$achievementTwo',
            achievementThree = '$achievementThree',
            about = '$about'
        WHERE ID = '$userId'";

$result2 = $connect->query($sql2);
if (!$result2) {
    die("Ошибка SQL: " . $connect->error);
}

echo "Данные успешно обновлены!";

header("Location: ../personal_account.php")
    ?>