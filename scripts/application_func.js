document.getElementById('applicationForm').addEventListener('submit', function(e) {
    const applicationField = document.querySelector('input[name="application"]');
    const errorDiv = document.getElementById('error');
    const value = applicationField.value.trim();
    
    errorDiv.textContent = '';
    
    if (!value) {
        errorDiv.textContent = 'Поле не может быть пустым';
        e.preventDefault();
        return;
    }
    
    if (value.length < 10) {
        errorDiv.textContent = 'Пожалуйста, введите более подробные сведения (минимум 10 символов)';
        e.preventDefault();
        return;
    }
    
    if (value.length > 1000) {
        errorDiv.textContent = 'Слишком длинный текст (максимум 1000 символов)';
        e.preventDefault();
        return;
    }
});