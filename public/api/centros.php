<?php
/**
 * API de Centros de Reciclaje — GreenPoints
 * ---------------------------------------------------------------
 * Gestiona los centros de reciclaje disponibles en la plataforma:
 *   - list   : devuelve todos los centros (público)
 *   - store  : crea un nuevo centro (solo admin)
 *   - update : actualiza un centro existente (solo admin)
 *   - delete : elimina un centro (solo admin)
 *
 * Las acciones que modifican datos requieren token CSRF y rol admin.
 * Las respuestas siguen siempre el mismo esquema JSON:
 *   { success: bool, message: string, ...extras }
 * ---------------------------------------------------------------
 */

header('Content-Type: application/json; charset=utf-8');

// ── Sesión segura (mismo patrón que el resto de APIs) ────────────
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'secure'   => isset($_SERVER['HTTPS']),
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

/** Envía respuesta JSON y termina la ejecución. */
function resp(bool $ok, string $msg = '', array $extra = []): void {
    echo json_encode(array_merge(['success' => $ok, 'message' => $msg], $extra));
    exit;
}

/** Comprueba que hay sesión activa con rol admin. */
function requireAdmin(): void {
    if (empty($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'admin') {
        resp(false, 'Acceso no autorizado.');
    }
}

/** Valida el token CSRF del array de datos recibido. */
function verifyCsrf(array $data): bool {
    $token = $data['csrf_token'] ?? null;
    if (empty($token)) return false;
    return CsrfHelper::verifyToken($token);
}

// ── Router ───────────────────────────────────────────────────────
try {
    switch ($action) {

        // ── Listado público ───────────────────────────────────────
        case 'list':
            $out = [];
            $q = $db->query(
                'SELECT id, nombre, direccion, tipos_residuos, horario
                   FROM centro_reciclaje
                  ORDER BY nombre ASC'
            );
            while ($row = $q->fetch_assoc()) {
                $out[] = $row;
            }
            resp(true, 'Centros obtenidos.', ['data' => $out]);

        // ── Crear centro (admin) ──────────────────────────────────
        case 'store':
            requireAdmin();

            if (!verifyCsrf($data)) {
                resp(false, 'Token CSRF inválido. Recarga la página e inténtalo de nuevo.');
            }

            $nombre    = trim($data['nombre'] ?? '');
            $direccion = trim($data['direccion'] ?? '');
            $tipos     = trim($data['tipos_residuos'] ?? '');
            $horario   = trim($data['horario'] ?? '');

            if ($nombre === '' || $direccion === '' || $tipos === '' || $horario === '') {
                resp(false, 'Todos los campos son obligatorios.');
            }

            $stmt = $db->prepare(
                'INSERT INTO centro_reciclaje (nombre, direccion, tipos_residuos, horario)
                 VALUES (?, ?, ?, ?)'
            );
            $stmt->bind_param('ssss', $nombre, $direccion, $tipos, $horario);
            if ($stmt->execute()) {
                resp(true, 'Centro creado correctamente.', ['id' => $db->insert_id]);
            }
            resp(false, 'No se pudo crear el centro. Inténtalo más tarde.');

        // ── Actualizar centro (admin) ─────────────────────────────
        case 'update':
            requireAdmin();

            if (!verifyCsrf($data)) {
                resp(false, 'Token CSRF inválido. Recarga la página e inténtalo de nuevo.');
            }

            $id        = intval($data['id'] ?? 0);
            $nombre    = trim($data['nombre'] ?? '');
            $direccion = trim($data['direccion'] ?? '');
            $tipos     = trim($data['tipos_residuos'] ?? '');
            $horario   = trim($data['horario'] ?? '');

            if ($id <= 0) {
                resp(false, 'ID de centro no válido.');
            }
            if ($nombre === '' || $direccion === '' || $tipos === '' || $horario === '') {
                resp(false, 'Todos los campos son obligatorios.');
            }

            $stmt = $db->prepare(
                'UPDATE centro_reciclaje
                    SET nombre = ?, direccion = ?, tipos_residuos = ?, horario = ?
                  WHERE id = ?'
            );
            $stmt->bind_param('ssssi', $nombre, $direccion, $tipos, $horario, $id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                resp(true, 'Centro actualizado correctamente.');
            }
            resp(false, 'No se encontró el centro o no hubo cambios.');

        // ── Eliminar centro (admin) ───────────────────────────────
        case 'delete':
            requireAdmin();

            if (!verifyCsrf($data)) {
                resp(false, 'Token CSRF inválido. Recarga la página e inténtalo de nuevo.');
            }

            $id = intval($data['id'] ?? 0);
            if ($id <= 0) {
                resp(false, 'ID de centro no válido.');
            }

            $stmt = $db->prepare('DELETE FROM centro_reciclaje WHERE id = ?');
            $stmt->bind_param('i', $id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                resp(true, 'Centro eliminado correctamente.');
            }
            resp(false, 'No se encontró el centro indicado.');

        default:
            resp(false, 'Acción no encontrada.');
    }

} catch (Exception $e) {
    error_log('[centros.php] ' . $e->getMessage());
    resp(false, 'Error interno del servidor. Inténtalo más tarde.');
}