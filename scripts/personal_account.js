const fullName = document.getElementById('fullName');
const email = document.getElementById('email');
const academicGroup = document.getElementById('academicGroup');
const telegram = document.getElementById('telegram');
const editButton = document.getElementById('editButton');
const saveButton = document.getElementById('saveButton');
const changePhotoButton = document.getElementById('changePhoto');
const profilePhoto = document.getElementById('profilePhoto');
const photoModal = document.getElementById('photoModal');
const closeModal = document.querySelector('.close');
const photoUpload = document.getElementById('photoUpload');
const uploadButton = document.getElementById('uploadButton');
const cancelUpload = document.getElementById('cancelUpload');
const achievementsViewMode = document.getElementById('achievementsViewMode');
const achievementsEditMode = document.getElementById('achievementsEditMode');
const linksContainer = document.getElementById('linksContainer');
const link1 = document.getElementById('link1');
const link2 = document.getElementById('link2');
const link3 = document.getElementById('link3');

// Настройки валидации
const ALLOWED_DOMAINS = ['github.com', 'linkedin.com', 'example.com', 'portfolio.ru'];

// Инициализация данных
let profileData = {
    fullName: "Иванов Иван Иванович",
    email: "ivanov@example.com",
    academicGroup: "ИКБО-01-21",
    telegram: "@ivanov",
    achievements: [
        "https://github.com/ivanov",
        "https://example.com/portfolio"
    ]
};

// Функция отображения данных в режиме просмотра
function displayProfileData() {
    fullName.value = profileData.fullName;
    email.value = profileData.email;
    academicGroup.value = profileData.academicGroup;
    telegram.value = profileData.telegram;
    displayLinks();
}

// Функция отображения ссылок
function displayLinks() {
    linksContainer.innerHTML = '';
    profileData.achievements.forEach((link, index) => {
        if (link) {
            const linkElement = document.createElement('a');
            linkElement.href = link;
            linkElement.textContent = `Достижение ${index + 1}: ${link}`;
            linkElement.className = 'achievement-link';
            linkElement.target = '_blank';
            linksContainer.appendChild(linkElement);
        }
    });
}

// Функция заполнения полей в режиме редактирования
function fillEditFields() {
    link1.value = profileData.achievements[0] || '';
    link2.value = profileData.achievements[1] || '';
    link3.value = profileData.achievements[2] || '';
}

// Валидация ФИО (русские буквы, пробелы)
function validateFullName(name) {
    const errorElement = document.getElementById('fullNameError');
    const regex = /^[А-ЯЁ][а-яё]+\s[А-ЯЁ][а-яё]+\s[А-ЯЁ][а-яё]+$/;
    
    if (!name.trim()) {
        showError(errorElement, fullName, "ФИО обязательно для заполнения");
        return false;
    }
    
    if (!regex.test(name)) {
        showError(errorElement, fullName, "Введите ФИО на русском в формате 'Фамилия Имя Отчество'");
        return false;
    }
    
    hideError(errorElement, fullName);
    return true;
}

// Валидация email
function validateEmail(emailValue) {
    const errorElement = document.getElementById('emailError');
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
    if (!emailValue.trim()) {
        showError(errorElement, email, "Email обязателен для заполнения");
        return false;
    }
    
    if (!regex.test(emailValue)) {
        showError(errorElement, email, "Введите корректный email");
        return false;
    }
    
    hideError(errorElement, email);
    return true;
}

// Валидация академической группы
function validateAcademicGroup(group) {
    const errorElement = document.getElementById('academicGroupError');
    
    if (!group.trim()) {
        showError(errorElement, academicGroup, "Академическая группа обязательна для заполнения");
        return false;
    }
    
    hideError(errorElement, academicGroup);
    return true;
}

// Валидация Telegram
function validateTelegram(username) {
    const errorElement = document.getElementById('telegramError');
    
    if (username.trim() && !username.startsWith('@')) {
        showError(errorElement, telegram, "Никнейм должен начинаться с @");
        return false;
    }
    
    if (username.trim() && !/^@[a-zA-Z0-9_]+$/.test(username)) {
        showError(errorElement, telegram, "Никнейм может содержать только буквы, цифры и подчеркивания");
        return false;
    }
    
    hideError(errorElement, telegram);
    return true;
}

// Валидация ссылок
function validateLink(url, index) {
    const errorElement = document.getElementById(`error${index}`);
    const inputElement = document.getElementById(`link${index}`);
    
    // Пустые поля разрешены
    if (!url.trim()) {
        hideError(errorElement, inputElement);
        return true;
    }

    try {
        const urlObj = new URL(url);
        
        // Проверка протокола
        if (!['http:', 'https:'].includes(urlObj.protocol)) {
            showError(errorElement, inputElement, 'Используйте http:// или https://');
            return false;
        }
        
        // Проверка домена
        const domain = urlObj.hostname.replace('www.', '');
        if (ALLOWED_DOMAINS.length > 0 && !ALLOWED_DOMAINS.includes(domain)) {
            showError(errorElement, inputElement, `Разрешены только: ${ALLOWED_DOMAINS.join(', ')}`);
            return false;
        }
        
        hideError(errorElement, inputElement);
        return true;
        
    } catch (e) {
        showError(errorElement, inputElement, 'Некорректный URL (пример: https://example.com)');
        return false;
    }
}

// Показать ошибку
function showError(errorElement, inputElement, message) {
    errorElement.textContent = message;
    errorElement.classList.add('show-error');
    inputElement.classList.add('invalid');
    inputElement.classList.remove('valid');
}

// Скрыть ошибку
function hideError(errorElement, inputElement) {
    errorElement.classList.remove('show-error');
    inputElement.classList.remove('invalid');
    inputElement.classList.add('valid');
}

// Проверка всех полей перед сохранением
function validateAll() {
    const isFullNameValid = validateFullName(fullName.value);
    const isEmailValid = validateEmail(email.value);
    const isAcademicGroupValid = validateAcademicGroup(academicGroup.value);
    const isTelegramValid = validateTelegram(telegram.value);
    
    let areLinksValid = true;
    [link1, link2, link3].forEach((input, index) => {
        const isValid = validateLink(input.value, index + 1);
        areLinksValid = areLinksValid && (isValid || input.value.trim() === '');
    });
    
    return isFullNameValid && isEmailValid && isAcademicGroupValid && isTelegramValid && areLinksValid;
}

// Обработчики событий
editButton.addEventListener('click', () => {
    fullName.disabled = false;
    email.disabled = false;
    academicGroup.disabled = false;
    telegram.disabled = false;
    achievementsViewMode.style.display = 'none';
    achievementsEditMode.style.display = 'block';
    fillEditFields();
    editButton.style.display = 'none';
    saveButton.style.display = 'inline-block';
});

saveButton.addEventListener('click', () => {
    if (!validateAll()) {
        alert('Пожалуйста, исправьте ошибки перед сохранением');
        return;
    }

    // Сохранение данных
    profileData = {
        fullName: fullName.value,
        email: email.value,
        academicGroup: academicGroup.value,
        telegram: telegram.value,
        achievements: [link1.value, link2.value, link3.value].filter(link => link.trim())
    };

    // Переключение в режим просмотра
    fullName.disabled = true;
    email.disabled = true;
    academicGroup.disabled = true;
    telegram.disabled = true;
    achievementsViewMode.style.display = 'block';
    achievementsEditMode.style.display = 'none';
    saveButton.style.display = 'none';
    editButton.style.display = 'inline-block';
    
    // Обновление отображения
    displayProfileData();
    
    console.log('Данные сохранены:', profileData);
});

// Смена фото
changePhotoButton.addEventListener('click', () => {
    photoModal.style.display = 'block';
});

closeModal.addEventListener('click', () => {
    photoModal.style.display = 'none';
});

cancelUpload.addEventListener('click', () => {
    photoModal.style.display = 'none';
});

uploadButton.addEventListener('click', () => {
    const file = photoUpload.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            profilePhoto.src = e.target.result;
            photoModal.style.display = 'none';
            console.log('Фото обновлено');
        };
        reader.readAsDataURL(file);
    }
});

// Выход
document.getElementById('logoutButton').addEventListener('click', () => {
    if (confirm('Вы уверены, что хотите выйти?')) {
        alert('Вы вышли из системы');
        // Здесь перенаправление на страницу входа
    }
});

// Инициализация при загрузке
displayProfileData();