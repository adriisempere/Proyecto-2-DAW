document.addEventListener('DOMContentLoaded', function () {
    
    /**
     * Muestra alertas visuales usando Bootstrap
     */
    function showAlert(container, message, type = 'danger') {
        // Opcional: Eliminar alertas previas en el mismo contenedor
        const existingAlert = container.querySelector('.alert-wrapper');
        if (existingAlert) existingAlert.remove();

        const wrapper = document.createElement('div');
        wrapper.className = 'alert-wrapper'; // Clase para identificarlo
        wrapper.innerHTML = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        container.prepend(wrapper);
        
        setTimeout(() => {
            try { 
                const el = wrapper.querySelector('.alert');
                if(el) {
                    const bs = bootstrap.Alert.getOrCreateInstance(el); 
                    bs.close(); 
                }
            } catch(e){
                wrapper.remove();
            }
        }, 5000);
    }

    /**
     * Función genérica para manejar los envíos de formularios
     */
    async function handleFormSubmit(form, endpoint) {
        const submitBtn = form.querySelector('[type="submit"]');
        const originalBtnText = submitBtn.innerHTML;

        try {
            // 1. Bloquear UI
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Cargando...';

            // 2. Preparar datos
            const formData = new FormData(form);
            const payload = Object.fromEntries(formData.entries());

            // 3. Petición al servidor
            const resp = await fetch(endpoint, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload),
                credentials: 'same-origin'
            });

            if (!resp.ok) throw new Error('Error en la respuesta del servidor');

            const json = await resp.json();

            // 4. Manejar respuesta
            if (json.success) {
                if (endpoint.includes('register')) {
                    showAlert(form, json.message || 'Registrado correctamente', 'success');
                    setTimeout(() => window.location = 'index.php?action=login', 1500);
                } else {
                    window.location = json.redirect || 'index.php?action=home';
                }
            } else {
                showAlert(form, json.message || 'Error en la operación');
            }

        } catch (error) {
            console.error("Error API:", error);
            showAlert(form, 'No se pudo conectar con el servidor. Inténtalo de nuevo.');
        } finally {
            // 5. Restaurar UI
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
        }
    }

    // Inicialización de Login
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', (e) => {
            e.preventDefault();
            handleFormSubmit(loginForm, 'api/users.php?action=login');
        });
    }

    // Inicialización de Registro
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', (e) => {
            e.preventDefault();
            handleFormSubmit(registerForm, 'api/users.php?action=register');
        });
    }
});