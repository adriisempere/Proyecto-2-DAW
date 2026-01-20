<?php
/**
 * UsuarioController
 * Gestiona todas las operaciones relacionadas con usuarios
 */
class UsuarioController {
    private $db;

    public function __construct() {
        $this->db = getConnection();
    }

    /**
     * Muestra la vista de registro
     */
    public function showRegister() {
        include __DIR__ . '/../views/register.php';
    }

    /**
     * Procesa el registro de un nuevo usuario
     */
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=register');
            exit;
        }

        // TODO: Implementar validación completa
        $nombre = $_POST['nombre'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        // Validación básica
        if (empty($nombre) || empty($email) || empty($password)) {
            $_SESSION['error'] = 'Todos los campos son obligatorios';
            header('Location: index.php?action=register');
            exit;
        }

        // Hash de la contraseña
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Insertar usuario (TODO: agregar manejo de errores robusto)
        $stmt = $this->db->prepare("INSERT INTO usuario (nombre, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nombre, $email, $passwordHash);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Usuario registrado correctamente';
            header('Location: index.php?action=login');
        } else {
            $_SESSION['error'] = 'Error al registrar usuario';
            header('Location: index.php?action=register');
        }
        exit;
    }

    /**
     * Muestra la vista de login
     */
    public function showLogin() {
        include __DIR__ . '/../views/login.php';
    }

    /**
     * Procesa el inicio de sesión
     */
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=login');
            exit;
        }

        $email = $_POST['email'] ?? '';
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
    public function logout() {
        session_destroy();
        header('Location: index.php?action=home');
        exit;
    }

    /**
     * Muestra el perfil del usuario
     */
    public function perfil() {
        // TODO: Implementar vista de perfil
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?action=login');
            exit;
        }
        
        echo "Vista de perfil - En desarrollo";
    }
}
?>
