<?php
/**
 * API de Registros de Reciclaje — GreenPoints
 * ---------------------------------------------------------------
 * Gestiona el historial de reciclaje de cada usuario:
 *   - store  : registra una nueva actividad de reciclaje y suma
 *              los puntos correspondientes al usuario (transacción)
 *   - list   : devuelve el historial del usuario autenticado,
 *              con el nombre del centro si fue presencial
 *   - delete : elimina un registro propio y descuenta sus puntos
 *              (también transacción para mantener consistencia)
 *
 * Tabla de puntos por kg según material:
 *   plástico 10 · papel 5 · vidrio 8 · metal 15 · orgánico 3
 *
 * Todas las acciones requieren sesión activa.
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

// ── Puntos por kg según tipo de material ─────────────────────────
const PUNTOS_POR_MATERIAL = [
    'plastico' => 10,
    'papel'    => 5,
    'vidrio'   => 8,
    'metal'    => 15,
    'organico' => 3,
];

// ── Helpers ──────────────────────────────────────────────────────

/** Envía respuesta JSON y termina la ejecución. */
function resp(bool $ok, string $msg = '', array $extra = []): void {
    echo json_encode(array_merge(['success' => $ok, 'message' => $msg], $extra));
    exit;
}

/** Aborta con 401 si no hay sesión activa. */
function requireAuth(): void {
    if (empty($_SESSION['usuario_id'])) {
        resp(false, 'No autenticado.', ['redirect' => 'index.php?action=login']);
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

        // ── Registrar reciclaje ───────────────────────────────────
        case 'store':
            requireAuth();

            if (!verifyCsrf($data)) {
                resp(false, 'Token CSRF inválido. Recarga la página e inténtalo de nuevo.');
            }

            $usuario_id = (int) $_SESSION['usuario_id'];
            $centro_id  = !empty($data['centro_id']) ? (int) $data['centro_id'] : null;
            $tipo       = $data['tipo_material'] ?? '';
            $cantidad   = (float) ($data['cantidad'] ?? 0);

            if (!array_key_exists($tipo, PUNTOS_POR_MATERIAL)) {
                resp(false, 'Tipo de material no válido. Valores permitidos: ' . implode(', ', array_keys(PUNTOS_POR_MATERIAL)) . '.');
            }
            if ($cantidad <= 0) {
                resp(false, 'La cantidad debe ser mayor que 0.');
            }

            $puntos = (int) ($cantidad * PUNTOS_POR_MATERIAL[$tipo]);

            // Transacción: insertar registro + actualizar puntos del usuario
            $db->begin_transaction();
            try {
                $stmt = $db->prepare(
                    'INSERT INTO registro_reciclaje
                        (usuario_id, centro_id, tipo_material, cantidad, puntos_ganados)
                     VALUES (?, ?, ?, ?, ?)'
                );
                $stmt->bind_param('iisdi', $usuario_id, $centro_id, $tipo, $cantidad, $puntos);
                $stmt->execute();
                $nuevo_id = $db->insert_id;

                $stmt2 = $db->prepare(
                    'UPDATE usuario SET puntos_totales = puntos_totales + ? WHERE id = ?'
                );
                $stmt2->bind_param('ii', $puntos, $usuario_id);
                $stmt2->execute();

                $db->commit();

                // Actualizar puntos en sesión para reflejar el cambio sin recargar
                $_SESSION['usuario_puntos'] = ($_SESSION['usuario_puntos'] ?? 0) + $puntos;

                resp(true, '¡Reciclaje registrado correctamente!', [
                    'id'            => $nuevo_id,
                    'puntos_ganados' => $puntos,
                    'puntos_totales' => $_SESSION['usuario_puntos'],
                ]);

            } catch (Exception $e) {
                $db->rollback();
                error_log('[registro.php:store] ' . $e->getMessage());
                resp(false, 'No se pudo guardar el registro. Inténtalo más tarde.');
            }

        // ── Historial del usuario autenticado ─────────────────────
        case 'list':
            requireAuth();

            $uid  = (int) $_SESSION['usuario_id'];
            $stmt = $db->prepare(
                'SELECT r.id, r.tipo_material, r.cantidad, r.puntos_ganados, r.fecha,
                        c.nombre AS centro_nombre
                   FROM registro_reciclaje r
                   LEFT JOIN centro_reciclaje c ON r.centro_id = c.id
                  WHERE r.usuario_id = ?
                  ORDER BY r.fecha DESC'
            );
            $stmt->bind_param('i', $uid);
            $stmt->execute();
            $res = $stmt->get_result();

            $out = [];
            while ($row = $res->fetch_assoc()) {
                $out[] = $row;
            }
            resp(true, 'Registros obtenidos.', ['data' => $out]);

        // ── Eliminar registro propio ──────────────────────────────
        case 'delete':
            requireAuth();

            if (!verifyCsrf($data)) {
                resp(false, 'Token CSRF inválido. Recarga la página e inténtalo de nuevo.');
            }

            $registro_id = (int) ($data['id'] ?? 0);
            $usuario_id  = (int) $_SESSION['usuario_id'];

            if ($registro_id <= 0) {
                resp(false, 'ID de registro no válido.');
            }

            // Obtener el registro verificando que pertenece al usuario
            $stmt = $db->prepare(
                'SELECT id, puntos_ganados FROM registro_reciclaje
                  WHERE id = ? AND usuario_id = ?'
            );
            $stmt->bind_param('ii', $registro_id, $usuario_id);
            $stmt->execute();
            $registro = $stmt->get_result()->fetch_assoc();

            if (!$registro) {
                resp(false, 'Registro no encontrado o no tienes permiso para eliminarlo.');
            }

            $puntos_a_descontar = (int) $registro['puntos_ganados'];

            // Transacción: borrar registro + descontar puntos
            $db->begin_transaction();
            try {
                $stmt = $db->prepare('DELETE FROM registro_reciclaje WHERE id = ?');
                $stmt->bind_param('i', $registro_id);
                $stmt->execute();

                $stmt2 = $db->prepare(
                    'UPDATE usuario
                        SET puntos_totales = GREATEST(0, puntos_totales - ?)
                      WHERE id = ?'
                );
                $stmt2->bind_param('ii', $puntos_a_descontar, $usuario_id);
                $stmt2->execute();

                $db->commit();

                // Actualizar puntos en sesión
                $_SESSION['usuario_puntos'] = max(0, ($_SESSION['usuario_puntos'] ?? 0) - $puntos_a_descontar);

                resp(true, 'Registro eliminado correctamente.', [
                    'puntos_descontados' => $puntos_a_descontar,
                    'puntos_totales'     => $_SESSION['usuario_puntos'],
                ]);

            } catch (Exception $e) {
                $db->rollback();
                error_log('[registro.php:delete] ' . $e->getMessage());
                resp(false, 'No se pudo eliminar el registro. Inténtalo más tarde.');
            }

        default:
            resp(false, 'Acción no encontrada.');
    }

} catch (Exception $e) {
    error_log('[registro.php] ' . $e->getMessage());
    resp(false, 'Error interno del servidor. Inténtalo más tarde.');
}