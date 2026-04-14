<?php
/**
 * API de Recompensas — GreenPoints
 * ---------------------------------------------------------------
 * Gestiona el catálogo de tarjetas regalo y el proceso de canje:
 *   - list       : devuelve todas las recompensas activas (público)
 *   - checkout   : procesa el carrito completo en una transacción.
 *                  Valida puntos, descuenta el total, genera un
 *                  código ficticio por ítem y guarda cada canje.
 *                  Requiere sesión activa y token CSRF.
 *   - mis_canjes : devuelve el historial de canjes del usuario
 *                  autenticado con los códigos generados.
 *
 * Los códigos son ficticios y se generan en el servidor con
 * un formato realista (XXXX-XXXX-XXXX-XXXX en mayúsculas).
 * Las respuestas siguen el esquema: { success, message, ...extras }
 * ---------------------------------------------------------------
 */

header('Content-Type: application/json; charset=utf-8');

// ── Sesión segura ────────────────────────────────────────────────
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
$data   = json_decode($raw, true) ?: [];

// ── Helpers ──────────────────────────────────────────────────────

/** Envía respuesta JSON y termina la ejecución. */
function resp(bool $ok, string $msg = '', array $extra = []): void {
    echo json_encode(array_merge(['success' => $ok, 'message' => $msg], $extra));
    exit;
}

/** Aborta si no hay sesión activa. */
function requireAuth(): void {
    if (empty($_SESSION['usuario_id'])) {
        resp(false, 'No autenticado.', ['redirect' => 'index.php?action=login']);
    }
}

/** Valida token CSRF del array de datos recibido. */
function verifyCsrf(array $data): bool {
    $token = $data['csrf_token'] ?? null;
    if (empty($token)) return false;
    return CsrfHelper::verifyToken($token);
}

/**
 * Genera un código de canje ficticio con formato realista.
 * Ejemplo: GP-A3F2-9K1X-B7QZ
 */
function generarCodigo(): string {
    $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    $segmento = function () use ($chars): string {
        $s = '';
        for ($i = 0; $i < 4; $i++) {
            $s .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $s;
    };
    return 'GP-' . $segmento() . '-' . $segmento() . '-' . $segmento();
}

// ── Router ───────────────────────────────────────────────────────
try {
    switch ($action) {

        // ── Catálogo de recompensas activas ───────────────────────
        case 'list':
            $out = [];
            $q = $db->query(
                'SELECT id, nombre, marca, puntos_coste, descripcion, imagen_url
                   FROM recompensa
                  WHERE activa = 1
                  ORDER BY puntos_coste ASC'
            );
            while ($row = $q->fetch_assoc()) {
                $row['puntos_coste'] = (int) $row['puntos_coste'];
                $out[] = $row;
            }
            resp(true, 'Catálogo obtenido.', ['data' => $out]);

        // ── Checkout del carrito ──────────────────────────────────
        case 'checkout':
            requireAuth();

            if (!verifyCsrf($data)) {
                resp(false, 'Token CSRF inválido. Recarga la página e inténtalo de nuevo.');
            }

            $items = $data['items'] ?? [];

            if (empty($items) || !is_array($items)) {
                resp(false, 'El carrito está vacío.');
            }

            // Validar que cada item tiene recompensa_id y cantidad válidos
            foreach ($items as $item) {
                if (empty($item['recompensa_id']) || empty($item['cantidad']) ||
                    (int)$item['cantidad'] < 1) {
                    resp(false, 'Datos del carrito no válidos.');
                }
            }

            $usuario_id = (int) $_SESSION['usuario_id'];

            // Obtener puntos actuales del usuario
            $stmt = $db->prepare('SELECT puntos_totales FROM usuario WHERE id = ?');
            $stmt->bind_param('i', $usuario_id);
            $stmt->execute();
            $usuario = $stmt->get_result()->fetch_assoc();

            if (!$usuario) {
                resp(false, 'Usuario no encontrado.');
            }

            $puntos_disponibles = (int) $usuario['puntos_totales'];

            // Calcular coste total verificando cada recompensa en BD
            // (nunca confiamos en el precio enviado desde el cliente)
            $total_puntos = 0;
            $items_validados = [];

            foreach ($items as $item) {
                $rid = (int) $item['recompensa_id'];
                $qty = (int) $item['cantidad'];

                $stmt = $db->prepare(
                    'SELECT id, nombre, marca, puntos_coste
                       FROM recompensa WHERE id = ? AND activa = 1'
                );
                $stmt->bind_param('i', $rid);
                $stmt->execute();
                $recompensa = $stmt->get_result()->fetch_assoc();

                if (!$recompensa) {
                    resp(false, "Una de las recompensas ya no está disponible.");
                }

                $total_puntos += (int)$recompensa['puntos_coste'] * $qty;
                $items_validados[] = [
                    'recompensa'  => $recompensa,
                    'cantidad'    => $qty,
                ];
            }

            // Verificar saldo suficiente
            if ($puntos_disponibles < $total_puntos) {
                resp(false, 'No tienes puntos suficientes para completar este canje.', [
                    'puntos_disponibles' => $puntos_disponibles,
                    'puntos_necesarios'  => $total_puntos,
                ]);
            }

            // Transacción: descontar puntos + insertar canjes
            $db->begin_transaction();
            try {
                // Descontar puntos del usuario
                $stmt = $db->prepare(
                    'UPDATE usuario SET puntos_totales = puntos_totales - ? WHERE id = ?'
                );
                $stmt->bind_param('ii', $total_puntos, $usuario_id);
                $stmt->execute();

                // Insertar un canje por cada unidad de cada ítem
                $codigos_generados = [];
                foreach ($items_validados as $iv) {
                    for ($i = 0; $i < $iv['cantidad']; $i++) {
                        $codigo       = generarCodigo();
                        $rid          = (int) $iv['recompensa']['id'];
                        $puntos_item  = (int) $iv['recompensa']['puntos_coste'];

                        $stmt = $db->prepare(
                            'INSERT INTO canje (usuario_id, recompensa_id, puntos_gastados, codigo)
                             VALUES (?, ?, ?, ?)'
                        );
                        $stmt->bind_param('iiis', $usuario_id, $rid, $puntos_item, $codigo);
                        $stmt->execute();

                        $codigos_generados[] = [
                            'nombre'  => $iv['recompensa']['nombre'],
                            'marca'   => $iv['recompensa']['marca'],
                            'puntos'  => $puntos_item,
                            'codigo'  => $codigo,
                        ];
                    }
                }

                $db->commit();

                // Actualizar puntos en sesión
                $_SESSION['usuario_puntos'] = $puntos_disponibles - $total_puntos;

                resp(true, '¡Canje realizado con éxito!', [
                    'codigos'        => $codigos_generados,
                    'puntos_gastados' => $total_puntos,
                    'puntos_totales' => $_SESSION['usuario_puntos'],
                ]);

            } catch (Exception $e) {
                $db->rollback();
                error_log('[recompensas.php:checkout] ' . $e->getMessage());
                resp(false, 'No se pudo completar el canje. Inténtalo más tarde.');
            }

        // ── Historial de canjes del usuario ───────────────────────
        case 'mis_canjes':
            requireAuth();

            $uid  = (int) $_SESSION['usuario_id'];
            $stmt = $db->prepare(
                'SELECT c.id, c.codigo, c.puntos_gastados, c.canjeado_at,
                        r.nombre, r.marca, r.imagen_url
                   FROM canje c
                   JOIN recompensa r ON c.recompensa_id = r.id
                  WHERE c.usuario_id = ?
                  ORDER BY c.canjeado_at DESC'
            );
            $stmt->bind_param('i', $uid);
            $stmt->execute();
            $res = $stmt->get_result();

            $out = [];
            while ($row = $res->fetch_assoc()) {
                $out[] = $row;
            }
            resp(true, 'Canjes obtenidos.', ['data' => $out]);

        default:
            resp(false, 'Acción no encontrada.');
    }

} catch (Exception $e) {
    error_log('[recompensas.php] ' . $e->getMessage());
    resp(false, 'Error interno del servidor. Inténtalo más tarde.');
}
