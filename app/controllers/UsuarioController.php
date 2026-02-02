<?php
/**
 * UsuarioController
 * Gestiona todas las operaciones relacionadas con usuarios
 */
class UsuarioController
{
    private $db;

    public function __construct()
    {
        $this->db = getConnection();
        // Asegurar que la sesión esté iniciada para CSRF
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        require_once __DIR__ . '/../helpers/CsrfHelper.php';
    }

    /**
     * Muestra la vista de registro
     */
    public function showRegister()
    {
        include __DIR__ . '/../views/register.php';
    }

    /**
     * Procesa el registro de un nuevo usuario
     */
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=register');
            exit;
        }

        // 1. Verificación CSRF
        if (!isset($_POST['csrf_token']) || !CsrfHelper::verifyToken($_POST['csrf_token'])) {
            $_SESSION['error'] = 'Error de seguridad: Token inválido. Por favor intenta de nuevo.';
            header('Location: index.php?action=register');
            exit;
        }

        // 2. Sanitización de entradas
        $nombre = trim(htmlspecialchars($_POST['nombre'] ?? ''));
        $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        // 3. Validación de campos obligatorios
        if (empty($nombre) || empty($email) || empty($password)) {
            $_SESSION['error'] = 'Todos los campos son obligatorios';
            // Guardar datos previos para no obligar a reescribir todo (menos password)
            $_SESSION['old_data'] = ['nombre' => $nombre, 'email' => $email];
            header('Location: index.php?action=register');
            exit;
        }

        // 4. Validación avanzada de Email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'El formato del correo electrónico no es válido';
            $_SESSION['old_data'] = ['nombre' => $nombre, 'email' => $email];
            header('Location: index.php?action=register');
            exit;
        }

        // 5. Validación de contraseña
        // Mínimo 8 caracteres, al menos una mayúscula, una minúscula y un número
        if (strlen($password) < 8) {
            $_SESSION['error'] = 'La contraseña debe tener al menos 8 caracteres';
            $_SESSION['old_data'] = ['nombre' => $nombre, 'email' => $email];
            header('Location: index.php?action=register');
            exit;
        }

        if (!preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password)) {
            $_SESSION['error'] = 'La contraseña debe contener mayúsculas, minúsculas y números';
            $_SESSION['old_data'] = ['nombre' => $nombre, 'email' => $email];
            header('Location: index.php?action=register');
            exit;
        }

        if ($password !== $passwordConfirm) {
            $_SESSION['error'] = 'Las contraseñas no coinciden';
            $_SESSION['old_data'] = ['nombre' => $nombre, 'email' => $email];
            header('Location: index.php?action=register');
            exit;
        }

        // 6. Verificar duplicados (Email único)
        $stmt = $this->db->prepare("SELECT id FROM usuario WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $_SESSION['error'] = 'Este correo electrónico ya está registrado';
            $_SESSION['old_data'] = ['nombre' => $nombre]; // No devolvemos el email duplicado
            header('Location: index.php?action=register');
            exit;
        }

        // Hash de la contraseña
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Insertar usuario
        $stmt = $this->db->prepare("INSERT INTO usuario (nombre, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nombre, $email, $passwordHash);

        if ($stmt->execute()) {
            $_SESSION['success'] = 'Usuario registrado correctamente. Por favor inicia sesión.';
            // Limpiar datos antiguos si existen
            unset($_SESSION['old_data']);
            header('Location: index.php?action=login');
        } else {
            $_SESSION['error'] = 'Error interno al registrar usuario. Intente más tarde.';
            header('Location: index.php?action=register');
        }
        exit;
    }

    /**
     * Muestra la vista de login
     */
    public function showLogin()
    {
        include __DIR__ . '/../views/login.php';
    }

    /**
     * Procesa el inicio de sesión
     */
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=login');
            exit;
        }

        // Verificación CSRF
        if (!isset($_POST['csrf_token']) || !CsrfHelper::verifyToken($_POST['csrf_token'])) {
            $_SESSION['error'] = 'Sesión expirada o inválida. Recargue la página.';
            header('Location: index.php?action=login');
            exit;
        }

        $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';

        // Validación básica
        if (empty($email) || empty($password)) {
            $_SESSION['error'] = 'Email y contraseña son obligatorios';
            header('Location: index.php?action=login');
            exit;
        }

        // Buscar usuario
        $stmt = $this->db->prepare("SELECT id, nombre, email, password, rol, puntos_totales FROM usuario WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $usuario = $result->fetch_assoc();

            // Verificar contraseña
            if (password_verify($password, $usuario['password'])) {
                // Regenerar ID de sesión para prevenir Session Fixation
                session_regenerate_id(true);

                // Iniciar sesión
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nombre'] = $usuario['nombre'];
                $_SESSION['usuario_email'] = $usuario['email'];
                $_SESSION['usuario_rol'] = $usuario['rol'];
                $_SESSION['usuario_puntos'] = $usuario['puntos_totales'];

                $_SESSION['success'] = '¡Bienvenido, ' . $usuario['nombre'] . '!';
                header('Location: index.php?action=home');
                exit;
            }
        }

        $_SESSION['error'] = 'Credenciales incorrectas';
        header('Location: index.php?action=login');
        exit;
    }

    /**
     * Cierra la sesión del usuario
     */
    public function logout()
    {
        session_destroy();
        header('Location: index.php?action=home');
        exit;
    }

    /**
     * Muestra el perfil del usuario
     */
    public function perfil()
    {
        // TODO: Implementar vista de perfil
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?action=login');
            exit;
        }

        echo "Vista de perfil - En desarrollo";
    }
}
?>