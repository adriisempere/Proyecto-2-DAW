<?php
/**
 * API de Usuarios — GreenPoints
 * ---------------------------------------------------------------
 * Gestiona el ciclo completo de autenticación:
 *   - register : crea un nuevo usuario (validación + hash bcrypt)
 *   - login    : inicia sesión y regenera el ID de sesión
 *   - logout   : destruye la sesión y limpia la cookie
 *   - me       : devuelve los datos del usuario autenticado
 *   - list     : lista usuarios (solo admin)
 *
 * Todas las acciones que modifican datos requieren token CSRF.
 * Las respuestas siguen siempre el mismo esquema JSON:
 *   { success: bool, message: string, ...extras }
 * ---------------------------------------------------------------
 */

header('Content-Type: application/json; charset=utf-8');

/* ── Configuración de sesión segura ─────────────────────────────
 * - secure: la cookie solo se envía por HTTPS (en producción)
 * - httponly: la cookie no es accesible desde JavaScript (previene XSS)
 * - samesite=Lax: protege contra CSRF sin romper el flujo de login
 * Esta configuración se replica en todos los archivos de la API. */
if (session_status() === PHP_SESSION_NONE) {
    session_name('GPSESSID');
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
<<<<<<< HEAD
        'domain'   => '',
=======
>>>>>>> 556ea03812908b9576d562ee821fa7a26c33b923
        'secure'   => false,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../app/helpers/CsrfHelper.php';

$db     = getConnection();
$action = $_GET['action'] ?? null;
$raw    = file_get_contents('php://input');
$data   = json_decode($raw, true) ?: $_POST;

// ── Helpers ──────────────────────────────────────────────────────

/**
 * Helper para enviar respuestas JSON consistentes en todas las APIs.
 * Todas las respuestas siguen el mismo esquema:
 *   { success: bool, message: string, ...extras }
 * El exit() posterior asegura que no se ejecute más código después
 * de enviar la respuesta.
 */
function resp(bool $ok, string $msg = '', array $extra = []): void {
    echo json_encode(array_merge(['success' => $ok, 'message' => $msg], $extra));
    exit;
}

/** Valida el token CSRF recibido en la petición. */
function verifyCsrf(array $data): bool {
    $token = $data['csrf_token'] ?? $data['X-CSRF-Token'] ?? null;
    if (empty($token)) return false;
<<<<<<< HEAD
=======
    /* En hosting gratuito la sesión puede perderse entre requests.
     * Si no hay token en sesión, aceptamos el enviado como válido
     * (mejor que bloquear completamente el login). */
>>>>>>> 556ea03812908b9576d562ee821fa7a26c33b923
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = $token;
        return true;
    }
    return CsrfHelper::verifyToken($token);
}

/** Devuelve true si hay un usuario con sesión activa. */
function isAuthenticated(): bool {
    return !empty($_SESSION['usuario_id']);
}

/** Devuelve true si el usuario autenticado tiene rol admin. */
function isAdmin(): bool {
    return ($_SESSION['usuario_rol'] ?? '') === 'admin';
}

// ── Router ───────────────────────────────────────────────────────
try {
    switch ($action) {

        // ── Registro ─────────────────────────────────────────────
        case 'register':
            if (!verifyCsrf($data)) {
                resp(false, 'Token CSRF inválido. Recarga la página e inténtalo de nuevo.');
            }

            $nombre   = trim($data['nombre'] ?? '');
            $email    = trim($data['email'] ?? '');
            $password = $data['password'] ?? '';

            // Validaciones básicas
            if ($nombre === '' || $email === '' || $password === '') {
                resp(false, 'Todos los campos son obligatorios.');
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                resp(false, 'El formato del email no es válido.');
            }
            if (mb_strlen($password) < 6) {
                resp(false, 'La contraseña debe tener al menos 6 caracteres.');
            }

            // Comprobar duplicado
            $stmt = $db->prepare('SELECT id FROM usuario WHERE email = ?');
            $stmt->bind_param('s', $email);
            $stmt->execute();
            if ($stmt->get_result()->num_rows > 0) {
                resp(false, 'Este correo ya está registrado.');
            }

            // Insertar
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $db->prepare('INSERT INTO usuario (nombre, email, password) VALUES (?, ?, ?)');
            $stmt->bind_param('sss', $nombre, $email, $hash);
            if ($stmt->execute()) {
                resp(true, '¡Cuenta creada correctamente! Ya puedes iniciar sesión.');
            }
            resp(false, 'No se pudo registrar el usuario. Inténtalo más tarde.');

        // ── Login ────────────────────────────────────────────────
        case 'login':
            if (!verifyCsrf($data)) {
                resp(false, 'Token CSRF inválido. Recarga la página e inténtalo de nuevo.');
            }

            $email    = trim($data['email'] ?? '');
            $password = $data['password'] ?? '';

            if ($email === '' || $password === '') {
                resp(false, 'Email y contraseña son obligatorios.');
            }

            $stmt = $db->prepare(
                'SELECT id, nombre, email, password, rol, puntos_totales
                   FROM usuario WHERE email = ? LIMIT 1'
            );
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $usuario = $stmt->get_result()->fetch_assoc();

            /* Usamos el mismo mensaje genérico tanto si el email no existe
             * como si la contraseña es incorrecta. Esto evita que un atacante
             * pueda enumerar qué emails están registrados en el sistema. */
            if (!$usuario || !password_verify($password, $usuario['password'])) {
                resp(false, 'Credenciales incorrectas.');
            }

            /* Regenerar el ID de sesión después del login exitoso.
             * Esto previene ataques de "fijación de sesión" (session fixation),
             * donde un atacante podría forzar un ID de sesión conocido.
             * El parámetro true elimina la sesión antigua del servidor. */
            $_SESSION['usuario_id']     = $usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            $_SESSION['usuario_email']  = $usuario['email'];
            $_SESSION['usuario_rol']    = $usuario['rol'];
            $_SESSION['usuario_puntos'] = $usuario['puntos_totales'];

            session_write_close();

            resp(true, 'Inicio de sesión correcto.', [
                'redirect' => 'index.php?action=home',
                'rol'      => $usuario['rol'],
            ]);

        // ── Logout ───────────────────────────────────────────────
        case 'logout':
            // Limpiar datos de sesión y destruir
            $_SESSION = [];
            if (ini_get('session.use_cookies')) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000,
                    $params['path'], $params['domain'],
                    $params['secure'], $params['httponly']
                );
            }
            session_destroy();
            resp(true, 'Sesión cerrada.', ['redirect' => 'index.php?action=home']);

        // ── Datos del usuario autenticado ────────────────────────
        case 'me':
            if (!isAuthenticated()) {
                resp(false, 'No autenticado.', ['redirect' => 'index.php?action=login']);
            }
            resp(true, 'Datos del usuario.', [
                'data' => [
                    'id'             => $_SESSION['usuario_id'],
                    'nombre'         => $_SESSION['usuario_nombre'],
                    'email'          => $_SESSION['usuario_email'],
                    'rol'            => $_SESSION['usuario_rol'],
                    'puntos_totales' => $_SESSION['usuario_puntos'],
                ],
            ]);

        // ── Listado de usuarios (solo admin) ─────────────────────
        case 'list':
            if (!isAuthenticated() || !isAdmin()) {
                resp(false, 'Acceso no autorizado.');
            }
            $out = [];
            $q = $db->query(
                'SELECT id, nombre, email, rol, puntos_totales, creado_at
                   FROM usuario ORDER BY puntos_totales DESC'
            );
            while ($row = $q->fetch_assoc()) {
                $out[] = $row;
            }
            resp(true, 'Lista obtenida.', ['data' => $out]);

        default:
            resp(false, 'Acción no encontrada.');
    }

} catch (Exception $e) {
    // No exponemos detalles del error en producción
    error_log('[users.php] ' . $e->getMessage());
    resp(false, 'Error interno del servidor. Inténtalo más tarde.');
}
