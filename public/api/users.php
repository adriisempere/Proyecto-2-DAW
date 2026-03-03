<?php
header('Content-Type: application/json; charset=utf-8');
session_start();

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../app/helpers/CsrfHelper.php';

$db = getConnection();

$action = $_GET['action'] ?? null;

// Leer datos JSON o POST
$raw = file_get_contents('php://input');
$data = json_decode($raw, true) ?: $_POST;

// Función de respuesta unificada
function resp($ok, $msg = '', $extra = []) {
    echo json_encode(array_merge(['success' => $ok, 'message' => $msg], $extra));
    exit;
}

// Verificar token CSRF si viene
function verifyCsrf($token) {
    if (empty($token)) return false;
    return CsrfHelper::verifyToken($token);
}

try {
    switch ($action) {
        case 'register':
            $nombre = trim($data['nombre'] ?? '');
            $email = filter_var(trim($data['email'] ?? ''), FILTER_SANITIZE_EMAIL);
            $password = $data['password'] ?? '';
            $csrf = $data['csrf_token'] ?? ($data['X-CSRF-Token'] ?? null);

            if (!verifyCsrf($csrf)) {
                resp(false, 'Token CSRF inválido. Recarga la página e inténtalo de nuevo.');
            }

            if ($nombre === '' || $email === '' || $password === '') {
                resp(false, 'Todos los campos son obligatorios');
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                resp(false, 'Email no válido');
            }

            // Comprobar duplicado
            $stmt = $db->prepare('SELECT id FROM usuario WHERE email = ?');
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res && $res->num_rows > 0) {
                resp(false, 'Este correo ya está registrado');
            }

            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $db->prepare('INSERT INTO usuario (nombre, email, password) VALUES (?, ?, ?)');
            $stmt->bind_param('sss', $nombre, $email, $passwordHash);
            if ($stmt->execute()) {
                resp(true, 'Usuario registrado correctamente');
            } else {
                resp(false, 'Error interno al registrar usuario');
            }
            break;

        case 'login':
            $email = filter_var(trim($data['email'] ?? ''), FILTER_SANITIZE_EMAIL);
            $password = $data['password'] ?? '';
            $csrf = $data['csrf_token'] ?? ($data['X-CSRF-Token'] ?? null);

            if (!verifyCsrf($csrf)) {
                resp(false, 'Token CSRF inválido. Recarga la página e inténtalo de nuevo.');
            }

            if ($email === '' || $password === '') {
                resp(false, 'Email y contraseña son obligatorios');
            }

            $stmt = $db->prepare('SELECT id, nombre, email, password, rol, puntos_totales FROM usuario WHERE email = ?');
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows === 1) {
                $usuario = $result->fetch_assoc();
                if (password_verify($password, $usuario['password'])) {
                    session_regenerate_id(true);
                    $_SESSION['usuario_id'] = $usuario['id'];
                    $_SESSION['usuario_nombre'] = $usuario['nombre'];
                    $_SESSION['usuario_email'] = $usuario['email'];
                    $_SESSION['usuario_rol'] = $usuario['rol'];
                    $_SESSION['usuario_puntos'] = $usuario['puntos_totales'];
                    resp(true, 'Inicio de sesión correcto', ['redirect' => 'index.php?action=home']);
                }
            }
            resp(false, 'Credenciales incorrectas');
            break;

        case 'list':
            $out = [];
            $q = $db->query('SELECT id, nombre, email, puntos_totales FROM usuario ORDER BY puntos_totales DESC');
            while ($row = $q->fetch_assoc()) {
                $out[] = $row;
            }
            resp(true, 'Lista obtenida', ['data' => $out]);
            break;

        case 'logout':
            session_destroy();
            resp(true, 'Sesión cerrada', ['redirect' => 'index.php?action=home']);
            break;

        default:
            resp(false, 'Acción no encontrada');
    }
} catch (Exception $e) {
    resp(false, 'Error interno: ' . $e->getMessage());
}

?>
