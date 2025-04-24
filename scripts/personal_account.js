
document.addEventListener('DOMContentLoaded', function() {
    // Эементы управления
    const editButton = document.getElementById('editButton');
    const saveButton = document.getElementById('saveButton');
    const form = document.getElementById('accountForm');
    
    // разрешенные домены для достижений
    const ALLOWED_DOMAINS = [
        'github.com',
        'gitlab.com',
        'codewars.com',
        'leetcode.com'
    ];

    // Включение режима редактирования
    if (editButton && form) {
        editButton.addEventListener('click', enableEditing);
    }
    
    // Валидация при отправке формы
    if (form) {
        form.addEventListener('submit', validateForm);
    }
    
    // Функция включения редактирования
    function enableEditing() {
        const inputs = form.querySelectorAll('input, textarea');
        inputs.forEach(input => {
            input.disabled = false;
        });
        
        saveButton.style.display = 'inline-block';
        editButton.style.display = 'none';
    }
    
    // Функция проверки ссылки на достижение
    function isValidAchievementLink(url) {
        if (!url) return true; // Пустые значения разрешены
        
        // Проверка, что это похоже на ссылку (содержит точку или слеши)
        if (!/[./]/.test(url)) {
            return false;
        }
        
        try {
            // Добавляем протокол если отсутствует
            const fullUrl = url.startsWith('http') ? url : `https://${url}`;
            const parsedUrl = new URL(fullUrl);
            
            // Должен быть хотя бы один символ после домена
            if (!parsedUrl.pathname || parsedUrl.pathname === '/') {
                return false;
            }
            
            const hostname = parsedUrl.hostname.replace('www.', '');
            return ALLOWED_DOMAINS.includes(hostname);
        } catch {
            return false;
        }
    }

    // Основная функция валидации
    function validateForm(e) {
        e.preventDefault();
        clearErrors();
        
        // Получаем значения полей
        const formData = {
            fullName: form.fullName.value.trim(),
            academicGroup: form.academicGroup.value.trim(),
            email: form.email.value.trim(),
            telegram: form.telegram.value.trim(),
            about: form.about.value.trim(),
            achievementOne: form.achievementOne.value.trim(),
            achievementTwo: form.achievementTwo.value.trim(),
            achievementThree: form.achievementThree.value.trim()
        };
        
        let isValid = true;
        
        // Валидация ФИО
        if (!formData.fullName) {
            showError('fullNameError', 'ФИО обязательно для заполнения');
            isValid = false;
        } else if (!/^[а-яА-ЯёЁ\s\-]{3,}$/u.test(formData.fullName)) {
            showError('fullNameError', 'Должно содержать только русские буквы, пробелы и дефисы');
            isValid = false;
        }
        
        // Валидация академической группы
        if (!formData.academicGroup) {
            showError('academicGroupError', 'Укажите академическую группу');
            isValid = false;
        } else if (!/^[а-яА-ЯёЁa-zA-Z0-9\-]{2,20}$/u.test(formData.academicGroup)) {
            showError('academicGroupError', 'Некорректный формат группы');
            isValid = false;
        }
        
        // Валидация email
        if (!formData.email) {
            showError('emailError', 'Email обязателен');
            isValid = false;
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(formData.email)) {
            showError('emailError', 'Введите корректный email');
            isValid = false;
        }
        
        // Валидация Telegram
        if (formData.telegram) {
            const cleanTelegram = formData.telegram.replace(/\s/g, '');
            
            if (!cleanTelegram.startsWith('@')) {
                showError('telegramError', 'Должен начинаться с @');
                isValid = false;
            } else if (!/^@[a-zA-Z0-9_]{5,32}$/.test(cleanTelegram)) {
                showError('telegramError', 'Только латинские буквы (a-z), цифры и _ (5-32 символа)');
                isValid = false;
            }
            else if (/[а-яА-ЯёЁ]/.test(cleanTelegram)) {
                showError('telegramError', 'Запрещены русские буквы');
                isValid = false;
            }
        }
        
        // Валидация "О себе"
        if (formData.about.length > 1000) {
            showError('aboutError', 'Максимум 1000 символов');
            isValid = false;
        }
        
        // Валидация достижений
        ['One', 'Two', 'Three'].forEach(num => {
            const achievement = form[`achievement${num}`].value.trim();
            if (achievement) {
                if (!/[./]/.test(achievement)) {
                    showError(`achievement${num}Error`, "Это должна быть ссылка (содержать домен)");
                    isValid = false;
                } else if (!isValidAchievementLink(achievement)) {
                    showError(`achievement${num}Error`, `Разрешены только ссылки с: ${ALLOWED_DOMAINS.join(', ')}`);
                    isValid = false;
                }
            }
        });
        
        // Если все валидно, отправляем форму
        if (isValid) {
            form.submit();
        }
    }
    
    // Показать сообщение об ошибке
    function showError(elementId, message) {
        const errorElement = document.getElementById(elementId);
        if (errorElement) {
            errorElement.textContent = message;
        }
    }
    
    // Очистить все сообщения об ошибках
    function clearErrors() {
        document.querySelectorAll('.error').forEach(el => {
            el.textContent = '';
        });
    }
} );