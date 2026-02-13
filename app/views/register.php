<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - GreenPoints</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <!-- Custom Animations -->
    <link rel="stylesheet" href="css/custom.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: var(--auth-bg-gradient);
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 40px 0;
        }

        .register-card {
            background: var(--card-bg);
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .register-header {
            background: var(--auth-header-gradient);
            color: white;
            padding: 40px;
            text-align: center;
        }

        .register-body {
            padding: 40px;
        }

        .form-control:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }

        .btn-register {
            background: var(--auth-header-gradient);
            border: none;
            padding: 12px;
            font-weight: 600;
            transition: transform 0.3s;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(40, 167, 69, 0.4);
        }

        .password-strength {
            height: 5px;
            border-radius: 3px;
            transition: all 0.3s;
        }

        .strength-weak {
            background: #dc3545;
            width: 33%;
        }

        .strength-medium {
            background: #ffc107;
            width: 66%;
        }

        .strength-strong {
            background: #28a745;
            width: 100%;
        }

        .invalid-feedback {
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-5px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-control {
            transition: border-color 0.2s, box-shadow 0.2s;
        }
    </style>
</head>

<body>
    <!-- Theme Toggle -->
    <button id="theme-toggle" class="btn btn-outline-light position-fixed top-0 end-0 m-4 rounded-circle" style="z-index: 1000; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(5px);">
        <i class="bi bi-moon-fill"></i>
    </button>
    
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="register-card animate__animated animate__fadeInUp glass-card">
                    <div class="register-header">
                        <i class="bi bi-leaf-fill fs-1 mb-3 animate__animated animate__pulse animate__infinite"></i>
                        <h2 class="fw-bold">Únete a GreenPoints</h2>
                        <p class="mb-0">Comienza tu viaje hacia un planeta más verde</p>
                    </div>

                    <div class="register-body">
                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <?= htmlspecialchars($_SESSION['error']); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                            <?php unset($_SESSION['error']); ?>
                        <?php endif; ?>

                        <form method="POST" action="index.php?action=register_post" id="registerForm" novalidate>
                            <!-- Token CSRF -->
                            <?php
                            require_once __DIR__ . '/../helpers/CsrfHelper.php';
                            echo CsrfHelper::getTokenField();
                            ?>

                            <div class="mb-3">
                                <label for="nombre" class="form-label fw-semibold">
                                    <i class="bi bi-person me-2"></i>Nombre Completo
                                </label>
                                <input type="text" class="form-control form-control-lg" id="nombre" name="nombre"
                                    placeholder="Tu nombre" required minlength="3" autocomplete="name"
                                    value="<?= isset($_SESSION['old_data']['nombre']) ? htmlspecialchars($_SESSION['old_data']['nombre']) : '' ?>">
                                <div class="invalid-feedback">
                                    Por favor, introduce tu nombre completo (mínimo 3 caracteres).
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">
                                    <i class="bi bi-envelope me-2"></i>Correo Electrónico
                                </label>
                                <input type="email" class="form-control form-control-lg" id="email" name="email"
                                    placeholder="tu@email.com" required autocomplete="email"
                                    value="<?= isset($_SESSION['old_data']['email']) ? htmlspecialchars($_SESSION['old_data']['email']) : '' ?>">
                                <div class="invalid-feedback">
                                    Por favor, introduce un correo electrónico válido.
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label fw-semibold">
                                    <i class="bi bi-lock me-2"></i>Contraseña
                                </label>
                                <input type="password" class="form-control form-control-lg" id="password"
                                    name="password" placeholder="••••••••" required minlength="6"
                                    autocomplete="new-password">
                                <div class="invalid-feedback">
                                    La contraseña debe tener al menos 6 caracteres.
                                </div>
                                <div class="password-strength mt-2" id="passwordStrength"></div>
                                <small class="text-muted">Mínimo 6 caracteres</small>
                            </div>

                            <div class="mb-4">
                                <label for="password_confirm" class="form-label fw-semibold">
                                    <i class="bi bi-lock-fill me-2"></i>Confirmar Contraseña
                                </label>
                                <input type="password" class="form-control form-control-lg" id="password_confirm"
                                    name="password_confirm" placeholder="••••••••" required autocomplete="new-password">
                                <div class="invalid-feedback">
                                    Las contraseñas no coinciden
                                </div>
                            </div>

                            <div class="mb-4 form-check">
                                <input type="checkbox" class="form-check-input" id="terms" required>
                                <label class="form-check-label small" for="terms">
                                    Acepto los <a href="#" class="text-decoration-none">términos y condiciones</a>
                                    y la <a href="#" class="text-decoration-none">política de privacidad</a>
                                </label>
                                <div class="invalid-feedback">
                                    Debes aceptar los términos y condiciones.
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success btn-register w-100 btn-lg mb-3">
                                <i class="bi bi-person-plus me-2"></i>Crear Cuenta
                            </button>

                            <div class="text-center">
                                <p class="text-muted mb-2">¿Ya tienes cuenta?</p>
                                <a href="index.php?action=login" class="text-decoration-none fw-semibold">
                                    Inicia sesión aquí
                                </a>
                            </div>

                            <hr class="my-4">

                            <div class="text-center">
                                <a href="index.php?action=home" class="text-muted text-decoration-none">
                                    <i class="bi bi-arrow-left me-2"></i>Volver al inicio
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Beneficios de unirse -->
                <div class="row mt-4 text-white">
                    <div class="col-md-4 text-center">
                        <i class="bi bi-trophy fs-1 mb-2"></i>
                        <p class="small">Compite en rankings</p>
                    </div>
                    <div class="col-md-4 text-center">
                        <i class="bi bi-gift fs-1 mb-2"></i>
                        <p class="small">Gana recompensas</p>
                    </div>
                    <div class="col-md-4 text-center">
                        <i class="bi bi-graph-up fs-1 mb-2"></i>
                        <p class="small">Mide tu impacto</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/theme.js"></script>
    <script>
        // Validación de contraseñas coincidentes
        const password = document.getElementById('password');
        const passwordConfirm = document.getElementById('password_confirm');
        const form = document.getElementById('registerForm');
        const strengthBar = document.getElementById('passwordStrength');

        // Verificar fuerza de contraseña
        password.addEventListener('input', function () {
            const value = this.value;
            let strength = 0;

            if (value.length >= 6) strength++;
            if (value.length >= 10) strength++;
            if (/[a-z]/.test(value) && /[A-Z]/.test(value)) strength++;
            if (/\d/.test(value)) strength++;
            if (/[@$!%*?&#]/.test(value)) strength++;

            strengthBar.className = 'password-strength mt-2';
            if (strength <= 2) {
                strengthBar.classList.add('strength-weak');
            } else if (strength <= 4) {
                strengthBar.classList.add('strength-medium');
            } else {
                strengthBar.classList.add('strength-strong');
            }
        });

        // Validar al enviar
        form.addEventListener('submit', function (e) {
            let isValid = true;
            inputs.forEach(input => {
                if (!validateInput(input)) {
                    isValid = false;
                }
            });

            if (!isValid) {
                e.preventDefault();
                e.stopPropagation();
                
                // Hacer scroll al primer error
                const firstInvalid = form.querySelector('.is-invalid');
                if (firstInvalid) {
                    firstInvalid.focus();
                }
            }
        });

        passwordConfirm.addEventListener('input', function () {
            validateInput(this);
        });

        // Validación en tiempo real (al perder el foco o 'blur')
        const inputs = form.querySelectorAll('input[required]');

        inputs.forEach(input => {
            input.addEventListener('blur', function () {
                validateInput(this);
            });

            input.addEventListener('input', function () {
                if (this.classList.contains('is-invalid')) {
                    validateInput(this);
                }
            });
        });

        function validateInput(input) {
            let isValid = true;

            if (input.id === 'password_confirm') {
                if (input.value !== password.value || input.value === '') {
                    isValid = false;
                }
            } else {
                isValid = input.checkValidity();
            }

            if (!isValid) {
                input.classList.add('is-invalid');
                input.classList.remove('is-valid');
            } else {
                input.classList.remove('is-invalid');
                input.classList.add('is-valid');
            }
            
            return isValid;
        }
    </script>
</body>

</html>