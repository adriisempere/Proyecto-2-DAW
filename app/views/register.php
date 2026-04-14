<?php
/**
 * Vista de Registro — GreenPoints
 * ---------------------------------------------------------------
 * Formulario de creación de cuenta. Si el usuario ya tiene sesión
 * activa se le redirige directamente al inicio.
 * El registro se delega a la API: public/api/users.php?action=register
 * Incluye validación en cliente (feedback inmediato) y en servidor
 * (fuente de verdad). El token CSRF se genera aquí y se verifica
 * en la API.
 * ---------------------------------------------------------------
 */

// Redirigir si ya está autenticado
if (isset($_SESSION['usuario_id'])) {
    header('Location: index.php?action=home');
    exit;
}

$pageTitle = 'Crear Cuenta | GreenPoints';
include __DIR__ . '/partials/header.php';
?>

<style>
    body {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        min-height: 100vh;
        padding: 3rem 0;
    }

    .register-card {
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25);
        border: 1px solid rgba(255,255,255,0.1);
    }

    .register-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: white;
        padding: 3rem 2rem;
        text-align: center;
    }

    .register-body {
        padding: 3rem 2.5rem;
        background: var(--bg-card);
    }

    .btn-register {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        border: none;
        padding: 14px;
        font-weight: 600;
        color: white;
        border-radius: 12px;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .btn-register:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
        color: white;
    }

    .strength-bar {
        height: 6px;
        border-radius: 10px;
        width: 0;
        transition: var(--transition);
        background: #eee;
    }
</style>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="register-card animate__animated animate__fadeInUp">

                <!-- Cabecera -->
                <div class="register-header">
                    <i class="bi bi-leaf-fill fs-1 mb-3 d-block animate__animated animate__pulse animate__infinite"></i>
                    <h2 class="fw-bold mb-1">Únete a GreenPoints</h2>
                    <p class="mb-0 opacity-75">Comienza tu viaje hacia un planeta más verde</p>
                </div>

                <!-- Cuerpo -->
                <div class="register-body">

                    <!-- Alerta dinámica -->
                    <div id="registerAlert" class="alert d-none" role="alert"></div>

                    <form id="registerForm" novalidate>
                        <?php
                        require_once __DIR__ . '/../helpers/CsrfHelper.php';
                        echo CsrfHelper::getTokenField();
                        ?>

                        <!-- Nombre -->
                        <div class="mb-3">
                            <label for="nombre" class="form-label fw-semibold">
                                <i class="bi bi-person me-1"></i>Nombre completo
                            </label>
                            <input type="text" class="form-control form-control-lg"
                                   id="nombre" name="nombre"
                                   placeholder="Tu nombre"
                                   required minlength="3"
                                   autocomplete="name"
                                   value="<?= htmlspecialchars($_SESSION['old_data']['nombre'] ?? '') ?>">
                            <div class="invalid-feedback">
                                Introduce tu nombre completo (mínimo 3 caracteres).
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">
                                <i class="bi bi-envelope me-1"></i>Correo electrónico
                            </label>
                            <input type="email" class="form-control form-control-lg"
                                   id="email" name="email"
                                   placeholder="tu@email.com"
                                   required autocomplete="email"
                                   value="<?= htmlspecialchars($_SESSION['old_data']['email'] ?? '') ?>">
                            <div class="invalid-feedback">
                                Introduce un correo electrónico válido.
                            </div>
                        </div>

                        <!-- Contraseña -->
                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">
                                <i class="bi bi-lock me-1"></i>Contraseña
                            </label>
                            <div class="input-group">
                                <input type="password" class="form-control form-control-lg"
                                       id="password" name="password"
                                       placeholder="••••••••"
                                       required minlength="6"
                                       autocomplete="new-password">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword" tabindex="-1" title="Mostrar/ocultar contraseña">
                                    <i class="bi bi-eye" id="toggleIcon"></i>
                                </button>
                            </div>
                            <div class="strength-bar mt-2" id="strengthBar"></div>
                            <div class="d-flex justify-content-between mt-1">
                                <small class="text-muted">Mínimo 6 caracteres</small>
                                <small id="strengthLabel" class="text-muted"></small>
                            </div>
                            <div class="invalid-feedback">
                                La contraseña debe tener al menos 6 caracteres.
                            </div>
                        </div>

                        <!-- Confirmar contraseña -->
                        <div class="mb-4">
                            <label for="password_confirm" class="form-label fw-semibold">
                                <i class="bi bi-lock-fill me-1"></i>Confirmar contraseña
                            </label>
                            <input type="password" class="form-control form-control-lg"
                                   id="password_confirm" name="password_confirm"
                                   placeholder="••••••••"
                                   required autocomplete="new-password">
                            <div class="invalid-feedback">
                                Las contraseñas no coinciden.
                            </div>
                        </div>

                        <!-- Términos -->
                        <div class="mb-4 form-check">
                            <input type="checkbox" class="form-check-input" id="terms" required>
                            <label class="form-check-label small" for="terms">
                                Acepto los <a href="#" class="text-success text-decoration-none">términos y condiciones</a>
                                y la <a href="#" class="text-success text-decoration-none">política de privacidad</a>
                            </label>
                            <div class="invalid-feedback">
                                Debes aceptar los términos y condiciones.
                            </div>
                        </div>

                        <!-- Submit -->
                        <button type="submit" class="btn btn-register w-100 btn-lg mb-3" id="submitBtn">
                            <i class="bi bi-person-plus me-2"></i>Crear Cuenta
                        </button>

                        <div class="text-center">
                            <p class="text-muted mb-1 small">¿Ya tienes cuenta?</p>
                            <a href="index.php?action=login" class="fw-semibold text-success text-decoration-none">
                                Inicia sesión aquí
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

            <!-- Beneficios -->
            <div class="row mt-4 text-white text-center">
                <div class="col-4">
                    <i class="bi bi-trophy benefit-icon d-block mb-1"></i>
                    <p class="small mb-0">Compite en rankings</p>
                </div>
                <div class="col-4">
                    <i class="bi bi-gift benefit-icon d-block mb-1"></i>
                    <p class="small mb-0">Gana recompensas</p>
                </div>
                <div class="col-4">
                    <i class="bi bi-graph-up benefit-icon d-block mb-1"></i>
                    <p class="small mb-0">Mide tu impacto</p>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form        = document.getElementById('registerForm');
    const submitBtn   = document.getElementById('submitBtn');
    const alertBox    = document.getElementById('registerAlert');
    const passInput   = document.getElementById('password');
    const confirmPass = document.getElementById('password_confirm');
    const strengthBar = document.getElementById('strengthBar');
    const strengthLbl = document.getElementById('strengthLabel');
    const toggleBtn   = document.getElementById('togglePassword');
    const toggleIcon  = document.getElementById('toggleIcon');

    // ── Mostrar / ocultar contraseña ─────────────────────────────
    toggleBtn.addEventListener('click', function () {
        const visible = passInput.type === 'text';
        passInput.type = visible ? 'password' : 'text';
        toggleIcon.className = visible ? 'bi bi-eye' : 'bi bi-eye-slash';
    });

    // ── Indicador de fuerza de contraseña ────────────────────────
    passInput.addEventListener('input', function () {
        const val = this.value;
        let score = 0;
        if (val.length >= 6)  score++;
        if (val.length >= 10) score++;
        if (/[a-z]/.test(val) && /[A-Z]/.test(val)) score++;
        if (/\d/.test(val))   score++;
        if (/[@$!%*?&#]/.test(val)) score++;

        strengthBar.className = 'strength-bar mt-2';
        if (val.length === 0) {
            strengthLbl.textContent = '';
        } else if (score <= 2) {
            strengthBar.classList.add('strength-weak');
            strengthLbl.textContent = 'Débil';
            strengthLbl.className = 'text-danger small';
        } else if (score <= 4) {
            strengthBar.classList.add('strength-medium');
            strengthLbl.textContent = 'Media';
            strengthLbl.className = 'text-warning small';
        } else {
            strengthBar.classList.add('strength-strong');
            strengthLbl.textContent = 'Fuerte';
            strengthLbl.className = 'text-success small';
        }
    });

    // ── Validación de campo individual ───────────────────────────
    function validateField(input) {
        let valid = true;

        if (input.id === 'password_confirm') {
            valid = input.value !== '' && input.value === passInput.value;
        } else {
            valid = input.checkValidity();
        }

        input.classList.toggle('is-invalid', !valid);
        input.classList.toggle('is-valid',    valid);
        return valid;
    }

    // Validación en tiempo real al perder foco o mientras se escribe si ya hay error
    form.querySelectorAll('input[required]').forEach(input => {
        input.addEventListener('blur',  () => validateField(input));
        input.addEventListener('input', () => {
            if (input.classList.contains('is-invalid')) validateField(input);
        });
    });

    // Revalidar confirmación al cambiar la contraseña principal
    passInput.addEventListener('input', () => {
        if (confirmPass.classList.contains('is-invalid') || confirmPass.classList.contains('is-valid')) {
            validateField(confirmPass);
        }
    });

    // ── Mostrar alerta dinámica ───────────────────────────────────
    function showAlert(msg, type = 'danger') {
        alertBox.className = `alert alert-${type} animate__animated animate__fadeIn`;
        alertBox.innerHTML = `<i class="bi bi-${type === 'danger' ? 'exclamation-triangle' : 'check-circle'}-fill me-2"></i>${msg}`;
    }

    // ── Envío del formulario vía fetch ────────────────────────────
    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        let valid = true;
        form.querySelectorAll('input[required]').forEach(input => {
            if (!validateField(input)) valid = false;
        });
        if (!valid) return;

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Creando cuenta…';

        const formData = {
            csrf_token: form.querySelector('[name="csrf_token"]').value,
            nombre:     form.querySelector('#nombre').value.trim(),
            email:      form.querySelector('#email').value.trim(),
            password:   passInput.value,
        };

        try {
            const res  = await fetch('api/users.php?action=register', {
                method:  'POST',
                headers: { 'Content-Type': 'application/json' },
                body:    JSON.stringify(formData),
            });
            const json = await res.json();

            if (json.success) {
                showAlert('¡Cuenta creada! Redirigiendo al login…', 'success');
                setTimeout(() => { window.location.href = 'index.php?action=login'; }, 1200);
            } else {
                showAlert(json.message || 'Error al crear la cuenta.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="bi bi-person-plus me-2"></i>Crear Cuenta';
            }
        } catch {
            showAlert('Error de red. Comprueba tu conexión e inténtalo de nuevo.');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="bi bi-person-plus me-2"></i>Crear Cuenta';
        }
    });
});
</script>

<?php include __DIR__ . '/partials/footer.php'; ?>