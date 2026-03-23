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

$db = getConnection();
$action = $_GET['action'] ?? null;

function resp($ok, $msg = '', $extra = []) { echo json_encode(array_merge(['success'=>$ok,'message'=>$msg], $extra)); exit; }

try {
    switch ($action) {
        case 'list':
            $query = "SELECT u.id, u.nombre, u.puntos_totales, u.foto, COUNT(r.id) as total_reciclajes, IFNULL(SUM(r.cantidad),0) as kg_reciclados FROM usuario u LEFT JOIN registro_reciclaje r ON u.id = r.usuario_id WHERE u.rol = 'usuario' GROUP BY u.id ORDER BY u.puntos_totales DESC LIMIT 100";
            $res = $db->query($query);
            $out = [];
            $pos = 1;
            while ($row = $res->fetch_assoc()) { $row['posicion'] = $pos++; $out[] = $row; }
            resp(true,'Ranking obtenido',['data'=>$out]);
            break;
        default:
            resp(false,'Acción no encontrada');
    }
} catch (Exception $e) { resp(false,'Error interno'); }

?>
