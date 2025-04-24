document.addEventListener('DOMContentLoaded', function() {
    const registerForm = document.getElementById('registerForm');
    
    if (registerForm) {
        // Добавляем валидацию при потере фокуса
        registerForm.login.addEventListener('blur', validateLoginField);
        registerForm.email.addEventListener('blur', validateEmailField);
        registerForm.password.addEventListener('blur', validatePasswordField);
        
        registerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const login = registerForm.login.value.trim();
            const email = registerForm.email.value.trim();
            const password = registerForm.password.value.trim();
            
            clearErrors();
            
            let isValid = true;
            
            if (!validateLoginField()) isValid = false;
            if (!validateEmailField()) isValid = false;
            if (!validatePasswordField()) isValid = false;
            
            if (isValid) {
                registerForm.submit();
            }
        });
    }
    
    function validateLoginField() {
        const loginInput = registerForm.login;
        const login = loginInput.value.trim();
        
        clearError(loginInput);
        
        if (login.length < 5) {
            showError(loginInput, 'Логин должен быть от 5 символов');
            return false;
        }
        
        if (!/^[a-zA-Z0-9_]+$/.test(login)) {
            showError(loginInput, 'Логин может содержать только буквы, цифры и _');
            return false;
        }
        
        return true;
    }
    
    function validateEmailField() {
        const emailInput = registerForm.email;
        const email = emailInput.value.trim();
        
        clearError(emailInput);
        
        if (!email) {
            showError(emailInput, 'Email обязателен');
            return false;
        }
        
        // Улучшенная регулярка для email
        const re = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)+$/;
        
        if (!re.test(email)) {
            showError(emailInput, 'Введите корректный email (например: user@example.com)');
            return false;
        }
        
        // Дополнительная проверка домена
        const domain = email.split('@')[1];
        if (!domain.includes('.')) {
            showError(emailInput, 'Email должен содержать домен (например: example.com)');
            return false;
        }
        
        return true;
    }
    
    function validatePasswordField() {
        const passwordInput = registerForm.password;
        const password = passwordInput.value.trim();
        
        clearError(passwordInput);
        
        if (password.length < 5) {
            showError(passwordInput, 'Пароль должен быть от 5 символов');
            return false;
        }
        
        return true;
    }
    
    function showError(input, message) {
        // Создаем или находим элемент для ошибки
        let errorElement = input.nextElementSibling;
        if (!errorElement || !errorElement.classList.contains('error-message')) {
            errorElement = document.createElement('div');
            errorElement.className = 'error-message';
            input.parentNode.insertBefore(errorElement, input.nextSibling);
        }
        
        errorElement.textContent = message;
        errorElement.style.color = '#dc3545';
        errorElement.style.fontSize = '0.8rem';
        errorElement.style.marginTop = '5px';
        
        input.style.borderColor = '#dc3545';
        input.style.boxShadow = '0 0 0 0.2rem rgba(220,53,69,.25)';
    }
    
    function clearError(input) {
        const errorElement = input.nextElementSibling;
        if (errorElement && errorElement.classList.contains('error-message')) {
            errorElement.remove();
        }
        
        input.style.borderColor = '';
        input.style.boxShadow = '';
    }
    
    function clearErrors() {
        document.querySelectorAll('.error-message').forEach(el => el.remove());
        document.querySelectorAll('#registerForm input').forEach(input => {
            input.style.borderColor = '';
            input.style.boxShadow = '';
        });
    }
});