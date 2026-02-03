document.addEventListener('DOMContentLoaded', function() {
    
    // --- Utils ---
    const validators = {
        email: (value) => {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
        },
        password: (value) => {
            // Min 6 chars
            return value.length >= 6;
        },
        name: (value) => {
            return value.trim().length >= 3;
        }
    };

    const showError = (input, message) => {
        const formGroup = input.closest('.mb-3, .mb-4');
        input.classList.remove('is-valid');
        input.classList.add('is-invalid');
        
        let feedback = formGroup.querySelector('.invalid-feedback');
        if (!feedback) {
            feedback = document.createElement('div');
            feedback.className = 'invalid-feedback';
            formGroup.appendChild(feedback);
        }
        feedback.textContent = message;
    };

    const showSuccess = (input) => {
        input.classList.remove('is-invalid');
        input.classList.add('is-valid');
    };

    // --- Login Form Validation ---
    const loginForm = document.getElementById('loginForm'); // Need to add this ID to login.php
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Validate Email
            const emailInput = document.getElementById('email');
            if (!validators.email(emailInput.value)) {
                showError(emailInput, 'Por favor, introduce un correo electrónico válido.');
                isValid = false;
            } else {
                showSuccess(emailInput);
            }

            // Validate Password
            const passwordInput = document.getElementById('password');
            if (passwordInput.value.length === 0) {
                showError(passwordInput, 'La contraseña es obligatoria.');
                isValid = false;
            } else {
                showSuccess(passwordInput);
            }

            if (!isValid) {
                e.preventDefault();
            }
        });

        // Real-time validation for login
        const loginInputs = loginForm.querySelectorAll('input');
        loginInputs.forEach(input => {
            input.addEventListener('input', function() {
                if (this.classList.contains('is-invalid')) {
                    this.classList.remove('is-invalid');
                }
            });
        });
    }

    // --- Register Form Validation ---
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            let isValid = true;

            // Validate Name
            const nameInput = document.getElementById('nombre');
            if (nameInput && !validators.name(nameInput.value)) {
                showError(nameInput, 'El nombre debe tener al menos 3 caracteres.');
                isValid = false;
            } else if (nameInput) {
                showSuccess(nameInput);
            }

            // Validate Email
            const emailInput = document.getElementById('email');
            if (!validators.email(emailInput.value)) {
                showError(emailInput, 'Por favor, introduce un correo electrónico válido.');
                isValid = false;
            } else {
                showSuccess(emailInput);
            }

            // Validate Password
            const passwordInput = document.getElementById('password');
            const passwordConfirmInput = document.getElementById('password_confirm');
            
            if (!validators.password(passwordInput.value)) {
                showError(passwordInput, 'La contraseña debe tener al menos 6 caracteres.');
                isValid = false;
            } else {
                showSuccess(passwordInput);
            }

            // Validate Confirm Password
            if (passwordInput.value !== passwordConfirmInput.value) {
                showError(passwordConfirmInput, 'Las contraseñas no coinciden.');
                isValid = false;
            } else if (passwordConfirmInput.value.length > 0) {
                showSuccess(passwordConfirmInput);
            }

            // Validate Terms
            const termsInput = document.getElementById('terms');
            if (!termsInput.checked) {
                termsInput.classList.add('is-invalid');
                isValid = false;
            } else {
                termsInput.classList.remove('is-invalid');
            }

            if (!isValid) {
                e.preventDefault();
            }
        });
    }
});
