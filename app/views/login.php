<?php
/**
 * Vista de Inicio de Sesión — GreenPoints
 * ---------------------------------------------------------------
 * Formulario de inicio de sesión. Si el usuario ya tiene sesión
 * activa se le redirige automáticamente al inicio.
 * La autenticación se delega a la API: public/api/users.php?action=login
 * El token CSRF se genera aquí y se verifica en la API.
 * ---------------------------------------------------------------
 */

// Redirigir al inicio si el usuario ya tiene sesión activa
if (isset($_SESSION["usuario_id"])) {
    header("Location: index.php?action=home");
    exit();
}

$pageTitle = "Iniciar Sesión | GreenPoints";
include __DIR__ . "/partials/header.php";
?>

<style>
/* ── Estilos de la página de autenticación ────────────────────────── */
body         { background: #f0fdf4; }
main         { display: flex; flex-direction: column; }
body > footer { margin-top: 0 !important; }

/* ══════════════════════════════════════════════════════════════
   CONTENEDOR PRINCIPAL — fila flex de altura completa
══════════════════════════════════════════════════════════════ */
.auth-page-wrapper {
    flex: 1;
    display: flex;
    min-height: 0;
}

/* ══════════════════════════════════════════════════════════════
   PANEL LATERAL IZQUIERDO — Decorativo animado
══════════════════════════════════════════════════════════════ */
.auth-left-panel {
    width: 44%;
    background: linear-gradient(145deg, #064e3b 0%, #065f46 22%, #047857 55%, #0d9488 100%);
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 3rem 2.5rem;
}

/* Brillo cónico giratorio de fondo */
.auth-left-panel::before {
    content: '';
    position: absolute;
    inset: -60%;
    background: conic-gradient(
        from 180deg at 50% 50%,
        rgba(52,211,153,.14)   0deg,
        rgba(16,185,129,.05)  120deg,
        rgba(52,211,153,.14)  240deg,
        rgba(16,185,129,.05)  360deg
    );
    animation: panelSpin 22s linear infinite;
    pointer-events: none;
}

@keyframes panelSpin { to { transform: rotate(360deg); } }

/* Manchas radiales flotantes animadas para dar profundidad */
.auth-blob {
    position: absolute;
    border-radius: 50%;
    pointer-events: none;
}
.auth-blob-1 {
    width: 340px; height: 340px;
    background: radial-gradient(circle, rgba(110,231,183,.28) 0%, transparent 68%);
    top: -110px; left: -110px;
    animation: drift1 15s ease-in-out infinite;
}
.auth-blob-2 {
    width: 260px; height: 260px;
    background: radial-gradient(circle, rgba(52,211,153,.22) 0%, transparent 68%);
    bottom: -90px; right: -90px;
    animation: drift2 12s ease-in-out infinite;
}
.auth-blob-3 {
    width: 190px; height: 190px;
    background: radial-gradient(circle, rgba(167,243,208,.2) 0%, transparent 68%);
    top: 38%; left: 54%;
    animation: drift3 17s ease-in-out infinite;
}
.auth-blob-4 {
    width: 130px; height: 130px;
    background: radial-gradient(circle, rgba(209,250,229,.26) 0%, transparent 68%);
    top: 16%; right: 8%;
    animation: drift2 10s ease-in-out infinite reverse;
}
.auth-blob-5 {
    width: 90px; height: 90px;
    background: radial-gradient(circle, rgba(167,243,208,.32) 0%, transparent 68%);
    bottom: 22%; left: 6%;
    animation: drift1 13s ease-in-out infinite 2.5s;
}

@keyframes drift1 {
    0%,100% { transform: translate(0,0) scale(1); }
    33%      { transform: translate(24px,-28px) scale(1.07); }
    66%      { transform: translate(-18px,18px) scale(.93); }
}
@keyframes drift2 {
    0%,100% { transform: translate(0,0) scale(1) rotate(0deg); }
    50%      { transform: translate(-26px,-22px) scale(1.1) rotate(14deg); }
}
@keyframes drift3 {
    0%,100% { transform: translate(0,0); }
    25%      { transform: translate(16px,-18px); }
    75%      { transform: translate(-13px,14px); }
}

/* Contenido del panel izquierdo */
.auth-left-content {
    position: relative;
    z-index: 2;
    color: #fff;
    text-align: center;
    max-width: 390px;
    width: 100%;
}

.auth-brand-icon {
    font-size: 4.2rem;
    display: inline-block;
    filter: drop-shadow(0 6px 18px rgba(0,0,0,.28));
    animation: iconPulse 3.2s ease-in-out infinite;
}
@keyframes iconPulse {
    0%,100% { transform: scale(1) rotate(0deg); }
    50%      { transform: scale(1.09) rotate(-4deg); }
}

.auth-left-content h1 {
    font-size: 2.25rem;
    font-weight: 800;
    letter-spacing: -.5px;
    text-shadow: 0 2px 18px rgba(0,0,0,.3);
    margin-bottom: .3rem;
}

.auth-tagline {
    font-size: .82rem;
    font-weight: 400;
    letter-spacing: 2.5px;
    opacity: .72;
    text-transform: uppercase;
    margin-bottom: 1.6rem;
}

.auth-left-desc {
    font-size: .875rem;
    opacity: .78;
    line-height: 1.78;
    margin-bottom: 1.75rem;
}

/* Tarjetas de beneficios en el panel izquierdo */
.auth-benefit-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    text-align: left;
    padding: .9rem 1.15rem;
    background: rgba(255,255,255,.07);
    border-radius: 14px;
    border: 1px solid rgba(255,255,255,.1);
    backdrop-filter: blur(6px);
    transition: background .3s, transform .3s;
    margin-bottom: .75rem;
}
.auth-benefit-item:hover {
    background: rgba(255,255,255,.13);
    transform: translateX(5px);
}
.auth-benefit-item:last-child { margin-bottom: 0; }

.benefit-icon-circle {
    width: 44px; height: 44px;
    background: rgba(255,255,255,.13);
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.3rem;
    flex-shrink: 0;
    border: 1px solid rgba(255,255,255,.16);
}

.benefit-text strong {
    display: block;
    font-size: .875rem;
    font-weight: 600;
    margin-bottom: 1px;
}
.benefit-text small {
    font-size: .775rem;
    opacity: .7;
    line-height: 1.35;
}

/* ══════════════════════════════════════════════════════════════
   PANEL DERECHO — Formulario de inicio de sesión
══════════════════════════════════════════════════════════════ */
.auth-right-panel {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2.5rem 1.5rem;
    background: linear-gradient(158deg, #f0fdf4 0%, #f8fafc 55%, #ecfdf5 100%);
    overflow-y: auto;
}

.auth-right-inner {
    width: 100%;
    max-width: 435px;
}

/* Marca móvil — oculta en pantallas md+ (visible solo en móvil) */
.auth-mobile-brand {
    display: none;
    text-align: center;
    margin-bottom: 1.6rem;
}
.auth-mobile-brand .m-brand-name {
    font-size: 1.5rem;
    font-weight: 800;
    color: #059669;
    letter-spacing: -.4px;
    vertical-align: middle;
}

/* ══════════════════════════════════════════════════════════════
   TARJETA DE VIDRIO (Glass Card)
══════════════════════════════════════════════════════════════ */
.auth-card {
    background: rgba(255,255,255,.83);
    backdrop-filter: blur(26px) saturate(180%);
    -webkit-backdrop-filter: blur(26px) saturate(180%);
    border-radius: 24px;
    border: 1px solid rgba(255,255,255,.92);
    box-shadow:
        0 26px 68px rgba(0,0,0,.1),
        0 6px 20px rgba(0,0,0,.06),
        inset 0 1px 0 rgba(255,255,255,.85);
    overflow: hidden;
}

/* Cabecera con degradado de la tarjeta */
.auth-card-header {
    background: linear-gradient(135deg, #047857 0%, #059669 38%, #10b981 72%, #34d399 100%);
    padding: 2rem 2.25rem 1.85rem;
    text-align: center;
    position: relative;
    overflow: hidden;
}
.auth-card-header::before {
    content: '';
    position: absolute;
    width: 320px; height: 320px;
    border-radius: 50%;
    background: rgba(255,255,255,.1);
    top: -130px; right: -90px;
    pointer-events: none;
}
.auth-card-header::after {
    content: '';
    position: absolute;
    width: 220px; height: 220px;
    border-radius: 50%;
    background: rgba(255,255,255,.065);
    bottom: -90px; left: -45px;
    pointer-events: none;
}

.auth-header-icon {
    position: relative; z-index: 1;
    width: 68px; height: 68px;
    background: rgba(255,255,255,.18);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto .95rem;
    font-size: 1.9rem;
    color: #fff;
    border: 2px solid rgba(255,255,255,.3);
    box-shadow: 0 4px 22px rgba(0,0,0,.15);
}

.auth-card-header h2 {
    position: relative; z-index: 1;
    color: #fff;
    font-weight: 800;
    font-size: 1.45rem;
    letter-spacing: -.3px;
    margin-bottom: .28rem;
}
.auth-card-header p {
    position: relative; z-index: 1;
    color: rgba(255,255,255,.82);
    font-size: .855rem;
    margin: 0;
}

/* Cuerpo de la tarjeta */
.auth-card-body { padding: 2rem 2.25rem 2.25rem; }

/* ── Etiquetas del formulario ──────────────────────────────────── */
.auth-form-label {
    font-size: .82rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: .42rem;
    display: block;
}
.auth-form-label i { color: #059669; margin-right: .3rem; }

/* ── Campos de entrada con estilo glass ─────────────────────────── */
.form-control-glass {
    background: rgba(240,253,244,.65) !important;
    border: 1.5px solid rgba(16,185,129,.22) !important;
    border-radius: 12px !important;
    padding: .72rem 1rem !important;
    font-size: .9rem !important;
    color: #111827 !important;
    transition: all .25s ease !important;
    box-shadow: inset 0 1px 3px rgba(0,0,0,.04) !important;
}
.form-control-glass::placeholder { color: #9ca3af !important; }
.form-control-glass:focus {
    background: rgba(255,255,255,.96) !important;
    border-color: #10b981 !important;
    box-shadow:
        0 0 0 3.5px rgba(16,185,129,.16),
        0 2px 10px rgba(16,185,129,.12) !important;
}
.form-control-glass.is-invalid {
    border-color: #f87171 !important;
    box-shadow: 0 0 0 3px rgba(248,113,113,.14) !important;
    background: rgba(254,242,242,.7) !important;
}
.form-control-glass.is-valid {
    border-color: #34d399 !important;
    box-shadow: 0 0 0 3px rgba(52,211,153,.14) !important;
}

/* Grupo de entrada — campo de contraseña con botón mostrar/ocultar */
.input-group > .form-control-glass:not(:last-child) {
    border-right: none !important;
    border-radius: 12px 0 0 12px !important;
}
.btn-eye {
    border: 1.5px solid rgba(16,185,129,.22) !important;
    border-left: none !important;
    border-radius: 0 12px 12px 0 !important;
    background: rgba(240,253,244,.65) !important;
    color: #6b7280 !important;
    padding: 0 .95rem !important;
    transition: all .2s ease !important;
    box-shadow: none !important;
}
.btn-eye:hover {
    background: rgba(209,250,229,.75) !important;
    color: #059669 !important;
}
.btn-eye:focus { box-shadow: none !important; outline: none; }

/* ── Botón de envío ─────────────────────────────────────────────── */
.btn-auth {
    background: linear-gradient(135deg, #047857 0%, #059669 38%, #10b981 100%);
    border: none;
    border-radius: 12px;
    padding: .82rem 1.5rem;
    font-weight: 700;
    font-size: .92rem;
    color: #fff;
    letter-spacing: .3px;
    transition: all .25s ease;
    box-shadow: 0 4px 18px rgba(5,150,105,.38);
    position: relative;
    overflow: hidden;
}
.btn-auth::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(255,255,255,.18) 0%, transparent 55%);
    opacity: 0;
    transition: opacity .25s;
    border-radius: inherit;
}
.btn-auth:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(5,150,105,.46);
    color: #fff;
}
.btn-auth:hover::after { opacity: 1; }
.btn-auth:active { transform: translateY(0); box-shadow: 0 3px 10px rgba(5,150,105,.3); }
.btn-auth:disabled { opacity: .68; transform: none; box-shadow: none; cursor: not-allowed; }

/* ── Divisor "o" entre opciones ──────────────────────────────────── */
.auth-divider {
    display: flex;
    align-items: center;
    gap: .875rem;
    margin: 1.35rem 0;
    font-size: .78rem;
    color: #9ca3af;
    font-weight: 500;
}
.auth-divider::before, .auth-divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: linear-gradient(to right, transparent, #bbf7d0, transparent);
}

/* ── Alerta dentro de la tarjeta ────────────────────────────────── */
.auth-alert {
    border-radius: 12px;
    border: none;
    font-size: .865rem;
    padding: .85rem 1rem;
    margin-bottom: 1.2rem;
}

/* ── Retroalimentación de validación ────────────────────────────── */
.invalid-feedback {
    animation: feedbackIn .2s ease;
    font-size: .775rem;
}
@keyframes feedbackIn {
    from { opacity: 0; transform: translateY(-4px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ══════════════════════════════════════════════════════════════
   PUNTOS DE RUPTURA RESPONSIVE
══════════════════════════════════════════════════════════════ */
@media (max-width: 767.98px) {
    .auth-left-panel   { display: none; }
    .auth-right-panel  { padding: 1.5rem 1rem; }
    .auth-card-body    { padding: 1.5rem 1.25rem 1.75rem; }
    .auth-card-header  { padding: 1.7rem 1.25rem 1.5rem; }
    .auth-mobile-brand { display: block; }
}
@media (min-width: 768px) and (max-width: 1023.98px) {
    .auth-left-panel { width: 40%; }
}
</style>

<div class="auth-page-wrapper">

    <!-- ═══════════════════════════════════════════════════════════
         IZQUIERDA — PANEL DECORATIVO ANIMADO
    ═══════════════════════════════════════════════════════════ -->
    <div class="auth-left-panel">

        <!-- Manchas animadas flotantes de fondo -->
        <div class="auth-blob auth-blob-1"></div>
        <div class="auth-blob auth-blob-2"></div>
        <div class="auth-blob auth-blob-3"></div>
        <div class="auth-blob auth-blob-4"></div>
        <div class="auth-blob auth-blob-5"></div>

        <div class="auth-left-content animate__animated animate__fadeIn">

            <div class="mb-2">
                <i class="bi bi-recycle auth-brand-icon"></i>
            </div>

            <h1>GreenPoints</h1>
            <p class="auth-tagline">Recicla &middot; Gana &middot; Impacta</p>

            <p class="auth-left-desc">
                Miles de personas ya están marcando la diferencia.
                Únete a la comunidad que convierte cada residuo
                en una oportunidad para el planeta.
            </p>

            <div>
                <div class="auth-benefit-item animate__animated animate__fadeInLeft"
                     style="animation-delay:.15s; animation-fill-mode:both;">
                    <div class="benefit-icon-circle">
                        <i class="bi bi-recycle"></i>
                    </div>
                    <div class="benefit-text">
                        <strong>Registra tus reciclajes</strong>
                        <small>Historial completo de tu impacto medioambiental</small>
                    </div>
                </div>

                <div class="auth-benefit-item animate__animated animate__fadeInLeft"
                     style="animation-delay:.3s; animation-fill-mode:both;">
                    <div class="benefit-icon-circle">
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <div class="benefit-text">
                        <strong>Acumula GreenPoints</strong>
                        <small>Puntos reales por cada kilo de residuo reciclado</small>
                    </div>
                </div>

                <div class="auth-benefit-item animate__animated animate__fadeInLeft"
                     style="animation-delay:.45s; animation-fill-mode:both;">
                    <div class="benefit-icon-circle">
                        <i class="bi bi-gift-fill"></i>
                    </div>
                    <div class="benefit-text">
                        <strong>Canjea recompensas</strong>
                        <small>Descuentos y experiencias sostenibles exclusivas</small>
                    </div>
                </div>
            </div>

        </div>
    </div><!-- /.auth-left-panel -->

    <!-- ═══════════════════════════════════════════════════════════
         DERECHA — PANEL DEL FORMULARIO
    ═══════════════════════════════════════════════════════════ -->
    <div class="auth-right-panel">
        <div class="auth-right-inner animate__animated animate__fadeInUp">

            <!-- Cabecera de marca visible solo en móvil -->
            <div class="auth-mobile-brand">
                <i class="bi bi-recycle text-success fs-2 me-2 align-middle"></i>
                <span class="m-brand-name">GreenPoints</span>
            </div>

            <!-- Tarjeta de vidrio (glass card) -->
            <div class="auth-card">

                <!-- Cabecera con degradado de la tarjeta -->
                <div class="auth-card-header">
                    <div class="auth-header-icon">
                        <i class="bi bi-box-arrow-in-right"></i>
                    </div>
                    <h2>Iniciar Sesión</h2>
                    <p>Accede a tu cuenta para continuar</p>
                </div>

                <!-- Cuerpo del formulario -->
                <div class="auth-card-body">

                    <!-- Mensajes flash de sesión PHP (error/success) -->
                    <?php if (!empty($_SESSION["error"])): ?>
                        <div class="alert auth-alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <?= htmlspecialchars($_SESSION["error"]) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php unset($_SESSION["error"]); ?>
                    <?php endif; ?>

                    <?php if (!empty($_SESSION["success"])): ?>
                        <div class="alert auth-alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            <?= htmlspecialchars($_SESSION["success"]) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php unset($_SESSION["success"]); ?>
                    <?php endif; ?>

                    <!-- Alerta dinámica generada por JavaScript -->
                    <div id="loginAlert" class="alert auth-alert d-none" role="alert"></div>

                    <form id="loginForm" novalidate>

                        <!-- Token CSRF para protección contra falsificación de peticiones -->
                        <?php
                        require_once __DIR__ . "/../helpers/CsrfHelper.php";
                        echo CsrfHelper::getTokenField();
                        ?>

                        <!-- Campo de correo electrónico -->
                        <div class="mb-3">
                            <label for="email" class="auth-form-label">
                                <i class="bi bi-envelope-fill"></i>Correo electrónico
                            </label>
                            <input type="email"
                                   class="form-control form-control-lg form-control-glass"
                                   id="email" name="email"
                                   placeholder="tu@email.com"
                                   required autocomplete="email">
                            <div class="invalid-feedback">
                                Introduce un correo electrónico válido.
                            </div>
                        </div>

                        <!-- Campo de contraseña con botón mostrar/ocultar -->
                        <div class="mb-4">
                            <label for="password" class="auth-form-label">
                                <i class="bi bi-lock-fill"></i>Contraseña
                            </label>
                            <div class="input-group">
                                <input type="password"
                                       class="form-control form-control-lg form-control-glass"
                                       id="password" name="password"
                                       placeholder="••••••••"
                                       required autocomplete="current-password">
                                <button class="btn btn-outline-secondary btn-eye"
                                        type="button"
                                        id="togglePassword"
                                        tabindex="-1"
                                        title="Mostrar/ocultar contraseña">
                                    <i class="bi bi-eye" id="toggleIcon"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback d-block"
                                 id="passwordError"
                                 style="display:none!important"></div>
                        </div>

                        <!-- Botón de envío del formulario -->
                        <button type="submit"
                                class="btn btn-auth w-100 mb-1"
                                id="submitBtn">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión
                        </button>

                        <div class="auth-divider">o</div>

                        <div class="text-center">
                            <p class="text-muted mb-1" style="font-size:.82rem;">¿No tienes cuenta?</p>
                            <a href="index.php?action=register"
                               class="fw-bold text-success text-decoration-none"
                               style="font-size:.88rem;">
                                Regístrate gratis
                                <i class="bi bi-arrow-right ms-1 small"></i>
                            </a>
                        </div>

                        <hr class="my-3" style="opacity:.1;">

                        <div class="text-center">
                            <a href="index.php?action=home"
                               class="text-muted text-decoration-none"
                               style="font-size:.8rem;">
                                <i class="bi bi-arrow-left me-1"></i>Volver al inicio
                            </a>
                        </div>

                    </form>
                </div><!-- /.auth-card-body -->
            </div><!-- /.auth-card -->

        </div><!-- /.auth-right-inner -->
    </div><!-- /.auth-right-panel -->

</div><!-- /.auth-page-wrapper -->

<script>
// ── Lógica de inicio de sesión: valida campos en cliente, envía
//    credenciales via fetch a la API, maneja la respuesta JSON y
//    redirige al home si es exitoso. ──────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
    const form       = document.getElementById('loginForm');
    const submitBtn  = document.getElementById('submitBtn');
    const alertBox   = document.getElementById('loginAlert');
    const toggleBtn  = document.getElementById('togglePassword');
    const toggleIcon = document.getElementById('toggleIcon');
    const passInput  = document.getElementById('password');

    // ── Alternar visibilidad de la contraseña ──────────────────────
    toggleBtn.addEventListener('click', function () {
        const visible = passInput.type === 'text';
        passInput.type = visible ? 'password' : 'text';
        toggleIcon.className = visible ? 'bi bi-eye' : 'bi bi-eye-slash';
    });

    // ── Validar campos en tiempo real al perder el foco o al escribir ──
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

    // ── Mostrar alerta con mensaje y tipo de estilo (danger/success) ──
    function showAlert(msg, type = 'danger') {
        alertBox.className = `alert auth-alert alert-${type} animate__animated animate__fadeIn`;
        alertBox.innerHTML = `<i class="bi bi-${type === 'danger' ? 'exclamation-triangle' : 'check-circle'}-fill me-2"></i>${msg}`;
    }

    // ── Enviar credenciales mediante fetch a la API ─────────────────
    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        console.log('Formulario enviado');

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
            console.log('Enviando a:', 'api/users.php?action=login');
            const res  = await fetch('api/users.php?action=login', {
                method:  'POST',
                credentials: 'same-origin',
                headers: { 'Content-Type': 'application/json' },
                body:    JSON.stringify(formData),
            });
            const text = await res.text();
            console.log('Respuesta:', res.status, text);
            const json = JSON.parse(text);

            if (json.success) {
                showAlert('¡Inicio de sesión correcto! Redirigiendo…', 'success');
                setTimeout(() => { window.location.href = json.redirect || 'index.php?action=home'; }, 800);
            } else {
                showAlert(json.message || 'Error al iniciar sesión.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión';
            }
        } catch (err) {
            console.error('Error en fetch:', err);
            showAlert('Error de red: ' + err.message);
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión';
        }
    });
});
</script>

<?php include __DIR__ . "/partials/footer.php"; ?>
