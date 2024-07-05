document.addEventListener('DOMContentLoaded', function() {
    const priceInput = document.getElementById('price');
    if (priceInput) {
        const form = document.querySelector('.auth-form');
        const errorMessage = document.createElement('p');
        errorMessage.classList.add('error');
        errorMessage.style.display = 'none';
        form.appendChild(errorMessage);
        
        priceInput.addEventListener('input', function() {
            if (isNaN(this.value) || this.value < 0) {
                errorMessage.textContent = 'Cena musi być liczbą i nie może być ujemna.';
                errorMessage.style.display = 'block';
            } else {
                errorMessage.style.display = 'none';
            }
        });
        
        form.addEventListener('submit', function(event) {
            const password = document.getElementById('password').value;
            const passwordRepeat = document.getElementById('password_repeat').value;

            if (isNaN(priceInput.value) || priceInput.value < 0) {
                event.preventDefault();
                errorMessage.textContent = 'Cena musi być liczbą i nie może być ujemna.';
                errorMessage.style.display = 'block';
            } else if (password !== passwordRepeat) {
                event.preventDefault();
                errorMessage.textContent = 'Hasła nie są zgodne.';
                errorMessage.style.display = 'block';
            } else {
                errorMessage.style.display = 'none';
            }
        });
    }
});
