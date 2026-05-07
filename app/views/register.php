<?php
/**
 * Vista de Registro — GreenPoints
 * Formulario de creación de cuenta con diseño de panel dividido.
 */
if (isset($_SESSION["usuario_id"])) {
    header("Location: index.php?action=home");
    exit();
}
$pageTitle = "Crear Cuenta | GreenPoints";
include __DIR__ . "/partials/header.php";
?>

<style>
/* ── Base de la página de autenticación ─────────────────────── */
body         { background: #f0fdf4; }
main         { display: flex; flex-direction: column; }
body > .gp-footer { margin-top: 0 !important; }

/* ── Contenedor principal ───────────────────────────────────── */
.auth-page-wrapper {
    flex: 1;
    display: flex;
    min-height: calc(100vh - 70px);
}

/* ── Panel decorativo izquierdo ─────────────────────────────── */
.auth-left-panel {
    width: 42%;
    background: linear-gradient(145deg, #064e3b 0%, #065f46 22%, #047857 55%, #0d9488 100%);
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 3rem 2.5rem;
}

.auth-left-panel::before {
    content: '';
    position: absolute;
    inset: -60%;
    background: conic-gradient(
        from 180deg at 50% 50%,
        rgba(52,211,153,.12) 0deg,
        rgba(16,185,129,.04) 120deg,
        rgba(52,211,153,.12) 240deg,
        rgba(16,185,129,.04) 360deg
    );
    animation: panelSpin 24s linear infinite;
    pointer-events: none;
}
@keyframes panelSpin { to { transform: rotate(360deg); } }

/* Elementos decorativos flotantes */
.auth-blob { position: absolute; border-radius: 50%; pointer-events: none; }
.auth-blob-1 {
    width: 380px; height: 380px;
    background: radial-gradient(circle, rgba(110,231,183,.22) 0%, transparent 68%);
    top: -120px; left: -120px;
    animation: drift1 16s ease-in-out infinite;
}
.auth-blob-2 {
    width: 280px; height: 280px;
    background: radial-gradient(circle, rgba(52,211,153,.18) 0%, transparent 68%);
    bottom: -100px; right: -100px;
    animation: drift2 13s ease-in-out infinite;
}
.auth-blob-3 {
    width: 180px; height: 180px;
    background: radial-gradient(circle, rgba(167,243,208,.18) 0%, transparent 68%);
    top: 40%; left: 55%;
    animation: drift3 18s ease-in-out infinite;
}

@keyframes drift1 {
    0%,100% { transform: translate(0,0) scale(1); }
    33%      { transform: translate(22px,-25px) scale(1.06); }
    66%      { transform: translate(-16px,16px) scale(.94); }
}
@keyframes drift2 {
    0%,100% { transform: translate(0,0) scale(1); }
    50%      { transform: translate(-22px,-18px) scale(1.08); }
}
@keyframes drift3 {
    0%,100% { transform: translate(0,0); }
    25%      { transform: translate(14px,-16px); }
    75%      { transform: translate(-12px,12px); }
}

.auth-left-content {
    position: relative;
    z-index: 2;
    color: #fff;
    text-align: center;
    max-width: 360px;
    width: 100%;
}

.auth-brand-icon {
    font-size: 4rem;
    display: inline-block;
    filter: drop-shadow(0 6px 16px rgba(0,0,0,.28));
    animation: iconPulse 3.5s ease-in-out infinite;
}
@keyframes iconPulse {
    0%,100% { transform: scale(1) rotate(0deg); }
    50%      { transform: scale(1.09) rotate(-5deg); }
}

.auth-left-content h1 {
    font-size: 2.1rem;
    font-weight: 800;
    letter-spacing: -.5px;
    text-shadow: 0 2px 16px rgba(0,0,0,.3);
    margin-bottom: .2rem;
}
.auth-tagline {
    font-size: .78rem;
    font-weight: 400;
    letter-spacing: 2.5px;
    opacity: .68;
    text-transform: uppercase;
    margin-bottom: 1.5rem;
}

/* Tarjetas de beneficios */
.auth-benefit-item {
    display: flex;
    align-items: center;
    gap: .9rem;
    text-align: left;
    padding: .8rem 1rem;
    background: rgba(255,255,255,.07);
    border-radius: 13px;
    border: 1px solid rgba(255,255,255,.1);
    backdrop-filter: blur(6px);
    transition: background .3s, transform .3s;
    margin-bottom: .65rem;
}
.auth-benefit-item:hover { background: rgba(255,255,255,.13); transform: translateX(4px); }
.auth-benefit-item:last-child { margin-bottom: 0; }
.benefit-icon-circle {
    width: 42px; height: 42px;
    background: rgba(255,255,255,.13);
    border-radius: 11px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
    border: 1px solid rgba(255,255,255,.16);
}
.benefit-text strong { display: block; font-size: .865rem; font-weight: 600; margin-bottom: 1px; }
.benefit-text small  { font-size: .78rem; opacity: .68; }

/* ── Panel derecho (formulario) ─────────────────────────────── */
.auth-right-panel {
    flex: 1;
    background: #f0fdf4;
    display: flex;
    align-items: flex-start;
    justify-content: center;
    padding: 2rem 1.5rem;
    overflow-y: auto;
}
.auth-right-inner { width: 100%; max-width: 500px; }

/* Encabezado de marca para móviles */
.auth-mobile-brand {
    display: none;
    align-items: center;
    gap: .6rem;
    justify-content: center;
    margin-bottom: 1.5rem;
}
.auth-mobile-brand .m-brand-icon {
    width: 36px; height: 36px;
    background: linear-gradient(135deg, #22c55e, #0d9488);
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 1rem;
}
.auth-mobile-brand .m-brand-name { font-weight: 800; font-size: 1.3rem; color: #065f46; }

/* ── Tarjeta de autenticación ───────────────────────────────── */
.auth-card {
    background: rgba(255,255,255,.92);
    backdrop-filter: blur(20px) saturate(160%);
    -webkit-backdrop-filter: blur(20px) saturate(160%);
    border: 1px solid rgba(255,255,255,.75);
    border-radius: 24px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(6,78,59,.12), 0 1px 0 rgba(255,255,255,.6) inset;
}

.auth-card-header {
    background: linear-gradient(135deg, #065f46 0%, #047857 45%, #0d9488 100%);
    padding: 2rem 2rem 1.8rem;
    text-align: center;
    position: relative;
    overflow: hidden;
}
.auth-card-header::before {
    content: '';
    position: absolute;
    top: -50%; left: -30%;
    width: 160%; height: 200%;
    background: radial-gradient(ellipse, rgba(255,255,255,.08) 0%, transparent 65%);
    pointer-events: none;
}

.auth-header-icon {
    width: 64px; height: 64px;
    background: rgba(255,255,255,.15);
    border: 2px solid rgba(255,255,255,.3);
    border-radius: 18px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.8rem;
    color: #fff;
    margin: 0 auto 1rem;
    box-shadow: 0 8px 24px rgba(0,0,0,.15);
    animation: iconPulse 3.5s ease-in-out infinite;
}
.auth-card-header h2 {
    font-size: 1.55rem;
    font-weight: 800;
    color: #fff;
    margin-bottom: .25rem;
    text-shadow: 0 2px 10px rgba(0,0,0,.15);
}
.auth-card-header p { color: rgba(255,255,255,.78); font-size: .875rem; margin: 0; }

.auth-card-body { padding: 1.75rem 2rem 2rem; }

/* ── Controles del formulario ───────────────────────────────── */
.auth-form-label {
    font-weight: 600;
    font-size: .85rem;
    color: #374151;
    margin-bottom: .4rem;
    display: flex;
    align-items: center;
    gap: .35rem;
}
.auth-form-label i { color: #059669; font-size: .9rem; }

.form-control-glass {
    background: rgba(240,253,244,.8);
    border: 1.5px solid #d1fae5;
    border-radius: 12px;
    padding: .7rem 1rem;
    font-size: .9rem;
    font-family: 'Poppins', sans-serif;
    color: #1f2937;
    transition: all .25s ease;
    width: 100%;
}
.form-control-glass::placeholder { color: #9ca3af; }
.form-control-glass:focus {
    outline: none;
    border-color: #059669;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(5,150,105,.12);
}
.form-control-glass.is-invalid { border-color: #ef4444; box-shadow: 0 0 0 3px rgba(239,68,68,.1); }
.form-control-glass.is-valid   { border-color: #22c55e; box-shadow: 0 0 0 3px rgba(34,197,94,.1); }

.input-group > .form-control-glass:not(:last-child) {
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
}
.btn-eye {
    background: rgba(240,253,244,.8);
    border: 1.5px solid #d1fae5;
    border-left: none;
    border-radius: 0 12px 12px 0;
    padding: 0 .85rem;
    color: #6b7280;
    transition: all .2s;
    cursor: pointer;
}
.btn-eye:hover { background: #dcfce7; color: #059669; }
.btn-eye:focus { outline: none; box-shadow: none; }

/* Barra de fortaleza de contraseña */
.strength-bar {
    height: 4px;
    border-radius: 3px;
    transition: width .35s ease, background-color .35s ease;
    width: 0;
    background: transparent;
}
.strength-weak   { width: 33%; background: #ef4444; }
.strength-medium { width: 66%; background: #f59e0b; }
.strength-strong { width: 100%; background: #22c55e; }

/* ── Botón de envío ─────────────────────────────────────────── */
.btn-auth {
    width: 100%;
    background: linear-gradient(135deg, #059669 0%, #0d9488 100%);
    border: none;
    border-radius: 12px;
    padding: .85rem 1.5rem;
    font-weight: 700;
    font-size: .95rem;
    color: #fff;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    transition: all .3s ease;
    box-shadow: 0 6px 20px rgba(5,150,105,.35);
    font-family: 'Poppins', sans-serif;
}
.btn-auth::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(255,255,255,.14), transparent);
    border-radius: inherit;
    opacity: 0;
    transition: opacity .3s;
}
.btn-auth:hover { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(5,150,105,.48); }
.btn-auth:hover::after { opacity: 1; }
.btn-auth:active { transform: translateY(0); }
.btn-auth:disabled { opacity: .65; transform: none; cursor: not-allowed; }

/* ── Alerta ──────────────────────────────────────────────────── */
.auth-alert {
    border-radius: 12px;
    font-size: .875rem;
    border: none;
    padding: .85rem 1rem;
}

/* ── Responsive ─────────────────────────────────────────────── */
@media (max-width: 767.98px) {
    .auth-left-panel  { display: none; }
    .auth-right-panel { padding: 1.5rem 1rem; background: linear-gradient(160deg, #f0fdf4 0%, #ecfdf5 100%); }
    .auth-card-body   { padding: 1.5rem; }
    .auth-card-header { padding: 1.75rem 1.5rem; }
    .auth-mobile-brand { display: flex; }
}
@media (min-width: 768px) and (max-width: 1023.98px) {
    .auth-left-panel { width: 38%; }
}
</style>

<div class="auth-page-wrapper">

    <!-- ── Panel decorativo izquierdo ──────────────────────────── -->
    <div class="auth-left-panel">
        <div class="auth-blob auth-blob-1"></div>
        <div class="auth-blob auth-blob-2"></div>
        <div class="auth-blob auth-blob-3"></div>

        <div class="auth-left-content animate__animated animate__fadeInLeft">
            <div class="auth-brand-icon mb-3">
                <i class="bi bi-recycle"></i>
            </div>
            <h1>GreenPoints</h1>
            <p class="auth-tagline">Recicla · Gana · Impacta</p>

            <div class="mb-4 text-start">
                <div class="auth-benefit-item stagger-item stagger-1">
                    <div class="benefit-icon-circle"><i class="bi bi-trophy-fill text-warning"></i></div>
                    <div class="benefit-text">
                        <strong>Compite en rankings</strong>
                        <small>Escala posiciones reciclando más</small>
                    </div>
                </div>
                <div class="auth-benefit-item stagger-item stagger-2">
                    <div class="benefit-icon-circle"><i class="bi bi-gift-fill" style="color:#f9a8d4;"></i></div>
                    <div class="benefit-text">
                        <strong>Gana recompensas</strong>
                        <small>Canjea puntos por descuentos reales</small>
                    </div>
                </div>
                <div class="auth-benefit-item stagger-item stagger-3">
                    <div class="benefit-icon-circle"><i class="bi bi-graph-up-arrow" style="color:#86efac;"></i></div>
                    <div class="benefit-text">
                        <strong>Mide tu impacto</strong>
                        <small>Visualiza el CO₂ que has ahorrado</small>
                    </div>
                </div>
                <div class="auth-benefit-item stagger-item stagger-4">
                    <div class="benefit-icon-circle"><i class="bi bi-people-fill" style="color:#7dd3fc;"></i></div>
                    <div class="benefit-text">
                        <strong>Comunidad activa</strong>
                        <small>+10.000 recicladores comprometidos</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ── Panel derecho (formulario) ─────────────────────── -->
    <div class="auth-right-panel">
        <div class="auth-right-inner">

            <!-- Marca para móviles -->
            <div class="auth-mobile-brand animate__animated animate__fadeInDown">
                <div class="m-brand-icon"><i class="bi bi-recycle"></i></div>
                <span class="m-brand-name">GreenPoints</span>
            </div>

            <!-- Tarjeta de autenticación -->
            <div class="auth-card animate__animated animate__fadeInUp">

                <!-- Encabezado -->
                <div class="auth-card-header">
                    <div class="auth-header-icon">
                        <i class="bi bi-person-plus-fill"></i>
                    </div>
                    <h2>Únete a GreenPoints</h2>
                    <p>Comienza tu viaje hacia un planeta más verde</p>
                </div>

                <!-- Cuerpo -->
                <div class="auth-card-body">

                    <!-- Contenedor para alertas dinámicas -->
                    <div id="registerAlert" class="auth-alert d-none mb-3" role="alert"></div>

                    <form id="registerForm" novalidate>
                        <?php
                        require_once __DIR__ . "/../helpers/CsrfHelper.php";
                        echo CsrfHelper::getTokenField();
                        ?>

                        <!-- Campo de nombre completo -->
                        <div class="mb-3">
                            <label for="nombre" class="auth-form-label">
                                <i class="bi bi-person"></i>Nombre completo
                            </label>
                            <input type="text"
                                   class="form-control-glass"
                                   id="nombre" name="nombre"
                                   placeholder="Tu nombre completo"
                                   required minlength="3"
                                   autocomplete="name"
                                   value="<?= htmlspecialchars(
                                       $_SESSION["old_data"]["nombre"] ?? "",
                                   ) ?>">
                            <div class="invalid-feedback">Introduce tu nombre completo (mínimo 3 caracteres).</div>
                        </div>

                        <!-- Campo de correo electrónico -->
                        <div class="mb-3">
                            <label for="email" class="auth-form-label">
                                <i class="bi bi-envelope"></i>Correo electrónico
                            </label>
                            <input type="email"
                                   class="form-control-glass"
                                   id="email" name="email"
                                   placeholder="tu@email.com"
                                   required autocomplete="email"
                                   value="<?= htmlspecialchars(
                                       $_SESSION["old_data"]["email"] ?? "",
                                   ) ?>">
                            <div class="invalid-feedback">Introduce un correo electrónico válido.</div>
                        </div>

                        <!-- Campo de contraseña con visibilidad alternable -->
                        <div class="mb-3">
                            <label for="password" class="auth-form-label">
                                <i class="bi bi-lock"></i>Contraseña
                            </label>
                            <div class="input-group" style="gap:0;">
                                <input type="password"
                                       class="form-control-glass"
                                       id="password" name="password"
                                       placeholder="••••••••"
                                       required minlength="6"
                                       autocomplete="new-password">
                                <button class="btn-eye" type="button" id="togglePassword" tabindex="-1">
                                    <i class="bi bi-eye" id="toggleIcon"></i>
                                </button>
                            </div>
                            <!-- Barra indicadora de fortaleza -->
                            <div class="strength-bar mt-2" id="strengthBar"></div>
                            <div class="d-flex justify-content-between mt-1">
                                <small class="text-muted" style="font-size:.74rem;">Mínimo 6 caracteres</small>
                                <small id="strengthLabel" class="text-muted" style="font-size:.74rem;"></small>
                            </div>
                            <div class="invalid-feedback">La contraseña debe tener al menos 6 caracteres.</div>
                        </div>

                        <!-- Confirmación de contraseña -->
                        <div class="mb-3">
                            <label for="password_confirm" class="auth-form-label">
                                <i class="bi bi-lock-fill"></i>Confirmar contraseña
                            </label>
                            <input type="password"
                                   class="form-control-glass"
                                   id="password_confirm" name="password_confirm"
                                   placeholder="••••••••"
                                   required autocomplete="new-password">
                            <div class="invalid-feedback">Las contraseñas no coinciden.</div>
                        </div>

                        <!-- Aceptación de términos y condiciones -->
                        <div class="mb-4 d-flex align-items-start gap-2">
                            <input type="checkbox" class="form-check-input mt-1 flex-shrink-0" id="terms" required
                                   style="border-color:#059669; accent-color:#059669;">
                            <label class="form-check-label small text-secondary" for="terms" style="font-size:.82rem;">
                                Acepto los <a href="#" class="text-success fw-semibold text-decoration-none">términos y condiciones</a>
                                y la <a href="#" class="text-success fw-semibold text-decoration-none">política de privacidad</a>
                            </label>
                        </div>

                        <!-- Botón de envío del formulario -->
                        <button type="submit" class="btn-auth mb-3" id="submitBtn">
                            <i class="bi bi-person-plus me-2"></i>Crear Cuenta
                        </button>

                        <div class="text-center">
                            <p class="text-muted mb-1" style="font-size:.85rem;">¿Ya tienes cuenta?</p>
                            <a href="index.php?action=login" class="fw-bold text-success text-decoration-none">
                                <i class="bi bi-box-arrow-in-right me-1"></i>Inicia sesión aquí
                            </a>
                        </div>

                        <hr class="my-3" style="border-color:#d1fae5;">

                        <div class="text-center">
                            <a href="index.php?action=home" class="text-muted text-decoration-none" style="font-size:.82rem;">
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
    const form        = document.getElementById('registerForm');
    const submitBtn   = document.getElementById('submitBtn');
    const alertBox    = document.getElementById('registerAlert');
    const passInput   = document.getElementById('password');
    const confirmPass = document.getElementById('password_confirm');
    const strengthBar = document.getElementById('strengthBar');
    const strengthLbl = document.getElementById('strengthLabel');
    const toggleBtn   = document.getElementById('togglePassword');
    const toggleIcon  = document.getElementById('toggleIcon');

    // Alternar visibilidad de la contraseña
    toggleBtn.addEventListener('click', function () {
        const visible = passInput.type === 'text';
        passInput.type = visible ? 'password' : 'text';
        toggleIcon.className = visible ? 'bi bi-eye' : 'bi bi-eye-slash';
    });

    // Indicador visual de fortaleza de la contraseña
    // Sistema de puntuación: evalúa longitud, mayúsculas, dígitos y símbolos
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
            strengthLbl.textContent = 'Debil';
            strengthLbl.className = 'text-danger';
            strengthLbl.style.fontSize = '.74rem';
        } else if (score <= 4) {
            strengthBar.classList.add('strength-medium');
            strengthLbl.textContent = 'Media';
            strengthLbl.className = 'text-warning';
            strengthLbl.style.fontSize = '.74rem';
        } else {
            strengthBar.classList.add('strength-strong');
            strengthLbl.textContent = 'Fuerte';
            strengthLbl.className = 'text-success';
            strengthLbl.style.fontSize = '.74rem';
        }
    });

    // Validación individual de cada campo del formulario
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

    form.querySelectorAll('input[required]').forEach(input => {
        input.addEventListener('blur',  () => validateField(input));
        input.addEventListener('input', () => {
            if (input.classList.contains('is-invalid')) validateField(input);
        });
    });

    passInput.addEventListener('input', () => {
        if (confirmPass.classList.contains('is-invalid') || confirmPass.classList.contains('is-valid')) {
            validateField(confirmPass);
        }
    });

    // Mostrar alerta de retroalimentación al usuario
    function showAlert(msg, type = 'danger') {
        alertBox.className = `alert alert-${type} auth-alert animate__animated animate__fadeIn`;
        alertBox.innerHTML = `<i class="bi bi-${type === 'danger' ? 'exclamation-triangle' : 'check-circle'}-fill me-2"></i>${msg}`;
    }

    // Envío asíncrono del formulario mediante fetch API
    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        let valid = true;
        form.querySelectorAll('input[required]').forEach(input => {
            if (!validateField(input)) valid = false;
        });
        if (!valid) return;

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Creando cuenta…';

        // Construcción del payload con token CSRF para protección contra falsificación
        const formData = {
            csrf_token: form.querySelector('[name="csrf_token"]').value,
            nombre:     form.querySelector('#nombre').value.trim(),
            email:      form.querySelector('#email').value.trim(),
            password:   passInput.value,
        };

        try {
            console.log('Enviando registro a:', 'api/users.php?action=register');
            const res  = await fetch('api/users.php?action=register', {
                method:  'POST',
                credentials: 'same-origin',
                headers: { 'Content-Type': 'application/json' },
                body:    JSON.stringify(formData),
            });
            const json = await res.json();
            if (json.success) {
                showAlert('\u00a1Cuenta creada! Redirigiendo al login\u2026', 'success');
                setTimeout(() => { window.location.href = 'index.php?action=login'; }, 1200);
            } else {
                showAlert(json.message || 'Error al crear la cuenta.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="bi bi-person-plus me-2"></i>Crear Cuenta';
            }
        } catch (err) {
            console.error('Error en fetch:', err);
            showAlert('Error de red: ' + err.message);
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="bi bi-person-plus me-2"></i>Crear Cuenta';
        }
    });
});
</script>

<?php include __DIR__ . "/partials/footer.php"; ?>
