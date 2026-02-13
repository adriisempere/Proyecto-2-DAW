<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - GreenPoints</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <!-- Custom Animations -->
    <link rel="stylesheet" href="css/custom.css?v=<?= time() ?>">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: var(--auth-bg-gradient, linear-gradient(135deg, #11998e 0%, #38ef7d 100%));
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .login-card {
            background: var(--card-bg, #ffffff);
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .login-header {
            background: var(--auth-header-gradient, linear-gradient(135deg, #28a745 0%, #20c997 100%));
            color: white;
            padding: 40px;
            text-align: center;
        }

        .login-body {
            padding: 40px;
        }

        .form-control:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }

        .btn-login {
            background: var(--auth-header-gradient);
            border: none;
            padding: 12px;
            font-weight: 600;
            transition: transform 0.3s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(40, 167, 69, 0.4);
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
            <div class="col-md-5">
                <div class="login-card animate__animated animate__fadeInDown glass-card">
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

                        <?php if (isset($_SESSION['success'])): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                <?= htmlspecialchars($_SESSION['success']); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                            <?php unset($_SESSION['success']); ?>
                        <?php endif; ?>

                        <form method="POST" action="index.php?action=login_post" novalidate>
                            <!-- Token CSRF -->
                            <?php
                            require_once __DIR__ . '/../helpers/CsrfHelper.php';
                            echo CsrfHelper::getTokenField();
                            ?>

                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">
                                    <i class="bi bi-envelope me-2"></i>Correo Electrónico
                                </label>
                                <input type="email" class="form-control form-control-lg" id="email" name="email"
                                    placeholder="tu@email.com" required autocomplete="email">
                                <div class="invalid-feedback">
                                    Por favor, introduce un correo electrónico válido.
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label fw-semibold">
                                    <i class="bi bi-lock me-2"></i>Contraseña
                                </label>
                                <input type="password" class="form-control form-control-lg" id="password"
                                    name="password" placeholder="••••••••" required autocomplete="current-password">
                                <div class="invalid-feedback">
                                    Por favor, introduce tu contraseña.
                                </div>
                            </div>

                            <div class="mb-4 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">
                                    Recordarme
                                </label>
                            </div>

                            <button type="submit" class="btn btn-success btn-login w-100 btn-lg mb-3">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión
                            </button>

                            <div class="text-center">
                                <p class="text-muted mb-2">¿No tienes cuenta?</p>
                                <a href="index.php?action=register" class="text-decoration-none fw-semibold">
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
    <script src="js/theme.js?v=<?= time() ?>"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const inputs = form.querySelectorAll('input[required]');

            inputs.forEach(input => {
                input.addEventListener('blur', function() {
                    validateInput(this);
                });

                input.addEventListener('input', function() {
                    if (this.classList.contains('is-invalid')) {
                        validateInput(this);
                    }
                });
            });

            form.addEventListener('submit', function(e) {
                let isValid = true;
                inputs.forEach(input => {
                    if (!validateInput(input)) {
                        isValid = false;
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const firstInvalid = form.querySelector('.is-invalid');
                    if (firstInvalid) {
                        firstInvalid.focus();
                    }
                }
            });

            function validateInput(input) {
                if (!input.checkValidity()) {
                    input.classList.add('is-invalid');
                    input.classList.remove('is-valid');
                    return false;
                } else {
                    input.classList.remove('is-invalid');
                    input.classList.add('is-valid');
                    return true;
                }
            }
        });
    </script>
</body>

</html>