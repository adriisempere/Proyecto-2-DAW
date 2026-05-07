<?php
// Aseguramos el inicio de sesión para errores y persistencia de datos
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$old = $_SESSION['old_data'] ?? [];
unset($_SESSION['old_data']); // Limpiamos después de leer
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - GreenPoints</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 40px 0;
        }
        .register-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }
        .register-header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }
        .register-body { padding: 40px; }
        .form-control:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }
        /* Fuerza de contraseña */
        .password-strength-container { height: 5px; background: #eee; border-radius: 3px; overflow: hidden; }
        .password-strength-bar { height: 100%; width: 0; transition: all 0.3s ease; }
        .bg-weak { background-color: #dc3545; width: 33%; }
        .bg-medium { background-color: #ffc107; width: 66%; }
        .bg-strong { background-color: #28a745; width: 100%; }
        
        .input-group-text { cursor: pointer; background: white; }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="register-card animate__animated animate__fadeInUp">
                    <div class="register-header">
                        <i class="bi bi-leaf-fill fs-1 mb-3 animate__animated animate__pulse animate__infinite"></i>
                        <h2 class="fw-bold">Únete a GreenPoints</h2>
                        <p class="mb-0">Comienza tu viaje hacia un planeta más verde</p>
                    </div>

                    <div class="register-body">
                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <?= htmlspecialchars($_SESSION['error']); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                            <?php unset($_SESSION['error']); ?>
                        <?php endif; ?>

                        <form method="POST" id="registerForm" novalidate>
                            <?php
                            require_once __DIR__ . '/../helpers/CsrfHelper.php';
                            echo CsrfHelper::getTokenField();
                            ?>

                            <div class="mb-3">
                                <label for="nombre" class="form-label fw-semibold">Nombre Completo</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" 
                                    value="<?= htmlspecialchars($old['nombre'] ?? '') ?>" required minlength="3">
                                <div class="invalid-feedback">Mínimo 3 caracteres.</div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">Correo Electrónico</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                    value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>
                                <div class="invalid-feedback">Introduce un correo válido.</div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label fw-semibold">Contraseña</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" required minlength="6">
                                    <span class="input-group-text toggle-pass"><i class="bi bi-eye"></i></span>
                                </div>
                                <div class="password-strength-container mt-2">
                                    <div id="strengthBar" class="password-strength-bar"></div>
                                </div>
                                <small id="strengthText" class="text-muted" style="font-size: 0.75rem;">Mínimo 6 caracteres</small>
                            </div>

                            <div class="mb-4">
                                <label for="password_confirm" class="form-label fw-semibold">Confirmar Contraseña</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
                                    <span class="input-group-text toggle-pass"><i class="bi bi-eye"></i></span>
                                </div>
                                <div class="invalid-feedback">Las contraseñas no coinciden.</div>
                            </div>

                            <div class="mb-4 form-check">
                                <input type="checkbox" class="form-check-input" id="terms" required>
                                <label class="form-check-label small" for="terms">Acepto los términos y condiciones</label>
                            </div>

                            <button type="submit" class="btn btn-success w-100 btn-lg shadow-sm">
                                <i class="bi bi-person-plus me-2"></i>Crear Cuenta
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('registerForm');
            const password = document.getElementById('password');
            const confirm = document.getElementById('password_confirm');
            const strengthBar = document.getElementById('strengthBar');
            const strengthText = document.getElementById('strengthText');
            const inputs = form.querySelectorAll('input[required]');

            // 1. Mostrar/Ocultar contraseñas
            document.querySelectorAll('.toggle-pass').forEach(btn => {
                btn.addEventListener('click', function() {
                    const input = this.parentElement.querySelector('input');
                    const icon = this.querySelector('i');
                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.replace('bi-eye', 'bi-eye-slash');
                    } else {
                        input.type = 'password';
                        icon.classList.replace('bi-eye-slash', 'bi-eye');
                    }
                });
            });

            // 2. Lógica de fuerza de contraseña
            password.addEventListener('input', function() {
                let val = this.value;
                let s = 0;
                if (val.length >= 6) s++;
                if (/[A-Z]/.test(val) && /[0-9]/.test(val)) s++;
                if (/[^A-Za-z0-9]/.test(val)) s++;

                strengthBar.className = 'password-strength-bar';
                if (s === 1) { strengthBar.classList.add('bg-weak'); strengthText.innerText = "Seguridad: Débil"; }
                else if (s === 2) { strengthBar.classList.add('bg-medium'); strengthText.innerText = "Seguridad: Media"; }
                else if (s === 3) { strengthBar.classList.add('bg-strong'); strengthText.innerText = "Seguridad: Fuerte"; }
                else { strengthBar.style.width = "0"; strengthText.innerText = "Mínimo 6 caracteres"; }
            });

            // 3. Validación al enviar
            form.addEventListener('submit', function(e) {
                let isvalid = true;
                
                inputs.forEach(input => {
                    if (!validate(input)) isvalid = false;
                });

                if (password.value !== confirm.value) {
                    confirm.classList.add('is-invalid');
                    isvalid = false;
                }

                if (!isvalid) {
                    e.preventDefault();
                    e.stopPropagation();
                }
            });

            function validate(input) {
                if (!input.checkValidity()) {
                    input.classList.add('is-invalid');
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