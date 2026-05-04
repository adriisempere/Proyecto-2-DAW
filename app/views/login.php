<?php
/**
 * Vista de Login — GreenPoints
 * ---------------------------------------------------------------
 * Formulario de inicio de sesión. Si el usuario ya tiene sesión
 * activa se le redirige directamente al inicio.
 * La autenticación se delega a la API: public/api/users.php?action=login
 * El token CSRF se genera aquí y se verifica en la API.
 * ---------------------------------------------------------------
 */

// Redirigir si ya está autenticado
if (isset($_SESSION['usuario_id'])) {
    header('Location: index.php?action=home');
    exit;
}

$pageTitle = 'Iniciar Sesión | GreenPoints';
include __DIR__ . '/partials/header.php';
?>


<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">

            <div class="login-card animate__animated animate__fadeInDown">

                <!-- Cabecera -->
                <div class="login-header">
                    <i class="bi bi-leaf-fill fs-1 mb-3 d-block animate__animated animate__pulse animate__infinite"></i>
                    <h2 class="fw-bold mb-1">GreenPoints</h2>
                    <p class="mb-0 opacity-75">Inicia sesión para continuar</p>
                </div>

                <!-- Cuerpo -->
                <div class="login-body">

                    <!-- Alerta de error/éxito desde sesión PHP -->
                    <?php if (!empty($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <?= htmlspecialchars($_SESSION['error']) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>

                    <?php if (!empty($_SESSION['success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            <?= htmlspecialchars($_SESSION['success']) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php unset($_SESSION['success']); ?>
                    <?php endif; ?>

                    <!-- Alerta dinámica para errores de la API -->
                    <div id="loginAlert" class="alert d-none" role="alert"></div>

                    <form id="loginForm" novalidate>
                        <!-- Token CSRF -->
                        <?php
                        require_once __DIR__ . '/../helpers/CsrfHelper.php';
                        echo CsrfHelper::getTokenField();
                        ?>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">
                                <i class="bi bi-envelope me-1"></i>Correo electrónico
                            </label>
                            <input type="email" class="form-control form-control-lg"
                                   id="email" name="email"
                                   placeholder="tu@email.com"
                                   required autocomplete="email">
                            <div class="invalid-feedback">
                                Introduce un correo electrónico válido.
                            </div>
                        </div>

                        <!-- Contraseña -->
                        <div class="mb-4">
                            <label for="password" class="form-label fw-semibold">
                                <i class="bi bi-lock me-1"></i>Contraseña
                            </label>
                            <div class="input-group">
                                <input type="password" class="form-control form-control-lg"
                                       id="password" name="password"
                                       placeholder="••••••••"
                                       required autocomplete="current-password">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword" tabindex="-1" title="Mostrar/ocultar contraseña">
                                    <i class="bi bi-eye" id="toggleIcon"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback d-block" id="passwordError" style="display:none!important"></div>
                        </div>

                        <!-- Submit -->
                        <button type="submit" class="btn btn-login w-100 btn-lg mb-3" id="submitBtn">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión
                        </button>

                        <div class="text-center">
                            <p class="text-muted mb-1 small">¿No tienes cuenta?</p>
                            <a href="index.php?action=register" class="fw-semibold text-success text-decoration-none">
                                Regístrate aquí
                            </a>
                        </div>

                        <hr class="my-4">

                        <div class="text-center">
                            <a href="index.php?action=home" class="text-muted text-decoration-none small">
                                <i class="bi bi-arrow-left me-1"></i>Volver al inicio
                            </a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form       = document.getElementById('loginForm');
    const submitBtn  = document.getElementById('submitBtn');
    const alertBox   = document.getElementById('loginAlert');
    const toggleBtn  = document.getElementById('togglePassword');
    const toggleIcon = document.getElementById('toggleIcon');
    const passInput  = document.getElementById('password');

    // ── Mostrar / ocultar contraseña ─────────────────────────────
    toggleBtn.addEventListener('click', function () {
        const visible = passInput.type === 'text';
        passInput.type = visible ? 'password' : 'text';
        toggleIcon.className = visible ? 'bi bi-eye' : 'bi bi-eye-slash';
    });

    // ── Validación en tiempo real ─────────────────────────────────
    form.querySelectorAll('input[required]').forEach(input => {
        input.addEventListener('blur',  () => validateField(input));
        input.addEventListener('input', () => {
            if (input.classList.contains('is-invalid')) validateField(input);
        });
    });

    function validateField(input) {
        const valid = input.checkValidity();
        input.classList.toggle('is-invalid', !valid);
        input.classList.toggle('is-valid',    valid);
        return valid;
    }

    // ── Mostrar alerta dinámica ───────────────────────────────────
    function showAlert(msg, type = 'danger') {
        alertBox.className = `alert alert-${type} animate__animated animate__fadeIn`;
        alertBox.innerHTML = `<i class="bi bi-${type === 'danger' ? 'exclamation-triangle' : 'check-circle'}-fill me-2"></i>${msg}`;
    }

    // ── Envío del formulario vía fetch ────────────────────────────
    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        // Validar todos los campos antes de enviar
        let valid = true;
        form.querySelectorAll('input[required]').forEach(input => {
            if (!validateField(input)) valid = false;
        });
        if (!valid) return;

        // Estado de carga
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Iniciando sesión…';

        const formData = {
            csrf_token: form.querySelector('[name="csrf_token"]').value,
            email:      form.querySelector('#email').value.trim(),
            password:   form.querySelector('#password').value,
        };

        try {
            const res  = await fetch('api/users.php?action=login', {
                method:  'POST',
                headers: { 'Content-Type': 'application/json' },
                body:    JSON.stringify(formData),
            });
            const json = await res.json();

            if (json.success) {
                showAlert('¡Inicio de sesión correcto! Redirigiendo…', 'success');
                setTimeout(() => { window.location.href = json.redirect || 'index.php?action=home'; }, 800);
            } else {
                showAlert(json.message || 'Error al iniciar sesión.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión';
            }
        } catch {
            showAlert('Error de red. Comprueba tu conexión e inténtalo de nuevo.');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión';
        }
    });
});
</script>

<?php include __DIR__ . '/partials/footer.php'; ?>