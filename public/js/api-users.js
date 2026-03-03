document.addEventListener('DOMContentLoaded', function () {
    function showAlert(container, message, type = 'danger') {
        const wrapper = document.createElement('div');
        wrapper.innerHTML = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        container.prepend(wrapper);
        setTimeout(() => {
            try { const bs = bootstrap.Alert.getOrCreateInstance(wrapper.querySelector('.alert')); bs.close(); } catch(e){}
        }, 5000);
    }

    // Formulario de login
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            const formData = new FormData(loginForm);
            const payload = {};
            formData.forEach((v,k) => payload[k] = v);

            const resp = await fetch('api/users.php?action=login', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });
            const json = await resp.json();
            if (json.success) {
                window.location = json.redirect || 'index.php?action=home';
            } else {
                showAlert(loginForm, json.message || 'Error al iniciar sesión');
            }
        });
    }

    // Formulario de registro
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            const formData = new FormData(registerForm);
            const payload = {};
            formData.forEach((v,k) => payload[k] = v);

            const resp = await fetch('api/users.php?action=register', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });
            const json = await resp.json();
            if (json.success) {
                showAlert(registerForm, json.message || 'Registrado correctamente', 'success');
                setTimeout(() => window.location = 'index.php?action=login', 1200);
            } else {
                showAlert(registerForm, json.message || 'Error al registrarse');
            }
        });
    }
});
