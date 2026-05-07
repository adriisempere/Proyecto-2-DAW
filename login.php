<?php
// Iniciamos sesión para que las alertas funcionen
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Capturamos el email si hubo un intento previo para no obligar al usuario a reescribirlo
$old_email = $_POST['email'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - GreenPoints</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="css/custom.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }
        .login-header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }
        .login-body { padding: 40px; }
        .form-control:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }
        .btn-login {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            padding: 12px;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(40, 167, 69, 0.4);
        }
        /* Estilo para el toggle de contraseña */
        .password-container { position: relative; }
        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            z-index: 10;
            color: #6c757d;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="login-card animate__animated animate__fadeInDown">
                    <div class="login-header">
                        <i class="bi bi-leaf-fill fs-1 mb-3 animate__animated animate__pulse animate__infinite"></i>
                        <h2 class="fw-bold">GreenPoints</h2>
                        <p class="mb-0">Inicia sesión para continuar</p>
                    </div>

                    <div class="login-body">
                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <?= htmlspecialchars($_SESSION['error']); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                            <?php unset($_SESSION['error']); ?>
                        <?php endif; ?>

                        <form method="POST" id="loginForm" novalidate>
                            <?php
                            require_once __DIR__ . '/../helpers/CsrfHelper.php';
                            echo CsrfHelper::getTokenField();
                            ?>

                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">
                                    <i class="bi bi-envelope me-2"></i>Correo Electrónico
                                </label>
                                <input type="email" class="form-control form-control-lg" id="email" name="email"
                                    value="<?= htmlspecialchars($old_email); ?>" 
                                    placeholder="tu@email.com" required autocomplete="email">
                                <div class="invalid-feedback">Introduce un correo válido.</div>
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label fw-semibold">
                                    <i class="bi bi-lock me-2"></i>Contraseña
                                </label>
                                <div class="password-container">
                                    <input type="password" class="form-control form-control-lg" id="password"
                                        name="password" placeholder="••••••••" required autocomplete="current-password">
                                    <i class="bi bi-eye password-toggle" id="togglePassword"></i>
                                    <div class="invalid-feedback">Introduce tu contraseña.</div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mb-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                    <label class="form-check-label" for="remember">Recordarme</label>
                                </div>
                                <a href="index.php?action=forgot_password" class="small text-decoration-none text-success">¿Olvidaste tu contraseña?</a>
                            </div>

                            <button type="submit" id="submitBtn" class="btn btn-success btn-login w-100 btn-lg mb-3">
                                <span id="btnText"><i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión</span>
                                <span id="btnLoader" class="spinner-border spinner-border-sm d-none" role="status"></span>
                            </button>

                            <div class="text-center">
                                <p class="text-muted mb-2">¿No tienes cuenta?</p>
                                <a href="index.php?action=register" class="text-decoration-none fw-semibold text-success">
                                    Regístrate aquí
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
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('loginForm');
            const passwordInput = document.getElementById('password');
            const togglePassword = document.getElementById('togglePassword');
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const btnLoader = document.getElementById('btnLoader');

            // 1. Mostrar/Ocultar contraseña
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.classList.toggle('bi-eye');
                this.classList.toggle('bi-eye-slash');
            });

            // 2. Validación y Spinner de carga
            form.addEventListener('submit', function(e) {
                if (!form.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                } else {
                    // Deshabilitar botón para evitar doble envío
                    submitBtn.disabled = true;
                    btnText.classList.add('d-none');
                    btnLoader.classList.remove('d-none');
                }
                form.classList.add('was-validated');
            }, false);

            // 3. Validación en tiempo real (limpiar error al escribir)
            const inputs = form.querySelectorAll('input');
            inputs.forEach(input => {
                input.addEventListener('input', () => {
                    if (input.checkValidity()) {
                        input.classList.remove('is-invalid');
                        input.classList.add('is-valid');
                    }
                });
            });
        });
    </script>
</body>
</html>