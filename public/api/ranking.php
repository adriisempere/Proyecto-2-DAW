<?php
/**
 * API de Ranking — GreenPoints
 * ---------------------------------------------------------------
 * Expone el ranking público de usuarios y las estadísticas
 * de reciclaje globales de la plataforma:
 *   - list  : top 100 usuarios ordenados por puntos, con número
 *             de reciclajes realizados y kg totales reciclados
 *   - stats : totales globales de la plataforma (usuarios activos,
 *             kg reciclados, puntos repartidos y CO₂ ahorrado)
 *   - me    : posición y estadísticas del usuario autenticado
 *             dentro del ranking (requiere sesión activa)
 *
 * El endpoint list es público. stats también es público.
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

$db     = getConnection();
$action = $_GET['action'] ?? null;

// ── Factor de conversión kg reciclados → CO₂ ahorrado (kg) ──────
// Estimación media ponderada entre materiales comunes.
const KG_CO2_POR_KG_RECICLADO = 1.5;

// ── Helpers ──────────────────────────────────────────────────────

/** Envía respuesta JSON y termina la ejecución. */
function resp(bool $ok, string $msg = '', array $extra = []): void {
    echo json_encode(array_merge(['success' => $ok, 'message' => $msg], $extra));
    exit;
}

// ── Router ───────────────────────────────────────────────────────
try {
    switch ($action) {

        // ── Top 100 usuarios ─────────────────────────────────────
        case 'list':
            $res = $db->query(
                "SELECT
                    u.id,
                    u.nombre,
                    u.puntos_totales,
                    u.foto,
                    COUNT(r.id)            AS total_reciclajes,
                    IFNULL(SUM(r.cantidad), 0) AS kg_reciclados
                 FROM usuario u
                 LEFT JOIN registro_reciclaje r ON u.id = r.usuario_id
                 WHERE u.rol = 'usuario'
                 GROUP BY u.id
                 ORDER BY u.puntos_totales DESC
                 LIMIT 100"
            );

            $out = [];
            $pos = 1;
            while ($row = $res->fetch_assoc()) {
                $row['posicion'] = $pos++;
                $out[] = $row;
            }

            // Si no hay usuarios todavía, devolvemos array vacío sin error
            resp(true, 'Ranking obtenido.', [
                'data'  => $out,
                'total' => count($out),
            ]);

        // ── Estadísticas globales de la plataforma ───────────────
        case 'stats':
            $row = $db->query(
                "SELECT
                    COUNT(DISTINCT u.id)       AS usuarios_activos,
                    COUNT(r.id)                AS total_reciclajes,
                    IFNULL(SUM(r.cantidad), 0) AS kg_reciclados,
                    IFNULL(SUM(r.puntos_ganados), 0) AS puntos_repartidos
                 FROM usuario u
                 LEFT JOIN registro_reciclaje r ON u.id = r.usuario_id
                 WHERE u.rol = 'usuario'"
            )->fetch_assoc();

            $row['co2_ahorrado_kg'] = round((float) $row['kg_reciclados'] * KG_CO2_POR_KG_RECICLADO, 2);

            resp(true, 'Estadísticas obtenidas.', ['data' => $row]);

        // ── Posición del usuario autenticado ─────────────────────
        case 'me':
            if (empty($_SESSION['usuario_id'])) {
                resp(false, 'No autenticado.', ['redirect' => 'index.php?action=login']);
            }

            $uid = (int) $_SESSION['usuario_id'];

            // Calcular posición contando cuántos usuarios tienen más puntos
            $stmt = $db->prepare(
                "SELECT COUNT(*) AS posicion
                   FROM usuario
                  WHERE rol = 'usuario'
                    AND puntos_totales > (
                        SELECT puntos_totales FROM usuario WHERE id = ?
                    )"
            );
            $stmt->bind_param('i', $uid);
            $stmt->execute();
            $posicion = (int) $stmt->get_result()->fetch_assoc()['posicion'] + 1;

            // Estadísticas personales
            $stmt2 = $db->prepare(
                "SELECT
                    u.puntos_totales,
                    COUNT(r.id)                AS total_reciclajes,
                    IFNULL(SUM(r.cantidad), 0) AS kg_reciclados
                 FROM usuario u
                 LEFT JOIN registro_reciclaje r ON u.id = r.usuario_id
                 WHERE u.id = ?
                 GROUP BY u.id"
            );
            $stmt2->bind_param('i', $uid);
            $stmt2->execute();
            $stats = $stmt2->get_result()->fetch_assoc();

            resp(true, 'Posición obtenida.', [
                'data' => [
                    'posicion'         => $posicion,
                    'puntos_totales'   => (int)   ($stats['puntos_totales']  ?? 0),
                    'total_reciclajes' => (int)   ($stats['total_reciclajes'] ?? 0),
                    'kg_reciclados'    => (float) ($stats['kg_reciclados']   ?? 0),
                ],
            ]);

        default:
            resp(false, 'Acción no encontrada.');
    }

} catch (Exception $e) {
    error_log('[ranking.php] ' . $e->getMessage());
    resp(false, 'Error interno del servidor. Inténtalo más tarde.');
}