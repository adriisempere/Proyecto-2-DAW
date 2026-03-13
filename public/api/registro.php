<?php
header('Content-Type: application/json; charset=utf-8');

// Sesión sencilla con path común (igual que en index.php)
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'path' => '/',
    ]);
    session_start();
}

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../app/helpers/CsrfHelper.php';

$db = getConnection();

$action = $_GET['action'] ?? null;
$raw = file_get_contents('php://input');
$data = json_decode($raw, true) ?: $_POST;

function resp($ok, $msg = '', $extra = []) { echo json_encode(array_merge(['success'=>$ok,'message'=>$msg], $extra)); exit; }

try {
    switch ($action) {
        case 'store':
            if (!isset($_SESSION['usuario_id'])) { resp(false, 'No autenticado', ['redirect'=>'index.php?action=login']); }

            $csrf = $data['csrf_token'] ?? null;
            if (!CsrfHelper::verifyToken($csrf)) { resp(false,'Token CSRF inválido'); }

            $usuario_id = $_SESSION['usuario_id'];
            $centro_id = !empty($data['centro_id']) ? intval($data['centro_id']) : null;
            $tipo = $data['tipo_material'] ?? '';
            $cantidad = floatval($data['cantidad'] ?? 0);

            $puntos_map = [
                'plastico'=>10,'papel'=>5,'vidrio'=>8,'metal'=>15,'organico'=>3
            ];
            if (!isset($puntos_map[$tipo]) || $cantidad <= 0) { resp(false,'Datos inválidos'); }
            $puntos = intval($cantidad * $puntos_map[$tipo]);

            $db->begin_transaction();
            try {
                $stmt = $db->prepare('INSERT INTO registro_reciclaje (usuario_id, centro_id, tipo_material, cantidad, puntos_ganados) VALUES (?, ?, ?, ?, ?)');
                $stmt->bind_param('iisdi', $usuario_id, $centro_id, $tipo, $cantidad, $puntos);
                $stmt->execute();

                $stmt2 = $db->prepare('UPDATE usuario SET puntos_totales = puntos_totales + ? WHERE id = ?');
                $stmt2->bind_param('ii', $puntos, $usuario_id);
                $stmt2->execute();

                $db->commit();
                $_SESSION['usuario_puntos'] = ($_SESSION['usuario_puntos'] ?? 0) + $puntos;
                resp(true, 'Reciclaje registrado', ['puntos_ganados'=>$puntos]);
            } catch (Exception $e) {
                $db->rollback();
                resp(false,'Error al guardar');
            }
            break;

        case 'list':
            if (!isset($_SESSION['usuario_id'])) { resp(false,'No autenticado'); }
            $uid = $_SESSION['usuario_id'];
            $stmt = $db->prepare("SELECT r.*, c.nombre as centro_nombre FROM registro_reciclaje r LEFT JOIN centro_reciclaje c ON r.centro_id = c.id WHERE r.usuario_id = ? ORDER BY r.fecha DESC");
            $stmt->bind_param('i', $uid);
            $stmt->execute();
            $res = $stmt->get_result();
            $out = [];
            while ($row = $res->fetch_assoc()) $out[] = $row;
            resp(true,'Registros obtenidos',['data'=>$out]);
            break;

        default:
            resp(false,'Acción no encontrada');
    }
} catch (Exception $e) { resp(false,'Error interno'); }

?>
