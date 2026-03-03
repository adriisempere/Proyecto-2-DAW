<?php
header('Content-Type: application/json; charset=utf-8');
session_start();

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../app/helpers/CsrfHelper.php';

$db = getConnection();
$action = $_GET['action'] ?? null;
$raw = file_get_contents('php://input');
$data = json_decode($raw, true) ?: $_POST;

function resp($ok, $msg = '', $extra = []) { echo json_encode(array_merge(['success'=>$ok,'message'=>$msg], $extra)); exit; }

try {
    switch ($action) {
        case 'list':
            $out = [];
            $q = $db->query('SELECT id, nombre, direccion, tipos_residuos, horario FROM centro_reciclaje ORDER BY nombre ASC');
            while ($r = $q->fetch_assoc()) $out[] = $r;
            resp(true,'Centros obtenidos',['data'=>$out]);
            break;

        case 'store':
            // Solo admin (sencillo)
            if (empty($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'admin') { resp(false,'No autorizado'); }
            $csrf = $data['csrf_token'] ?? null;
            if (!CsrfHelper::verifyToken($csrf)) { resp(false,'Token inválido'); }
            $nombre = $data['nombre'] ?? '';
            $direccion = $data['direccion'] ?? '';
            $tipos = $data['tipos_residuos'] ?? '';
            $horario = $data['horario'] ?? '';
            $stmt = $db->prepare('INSERT INTO centro_reciclaje (nombre, direccion, tipos_residuos, horario) VALUES (?, ?, ?, ?)');
            $stmt->bind_param('ssss', $nombre, $direccion, $tipos, $horario);
            if ($stmt->execute()) resp(true,'Centro creado'); else resp(false,'Error al crear centro');
            break;

        default:
            resp(false,'Acción no encontrada');
    }
} catch (Exception $e) { resp(false,'Error interno'); }

?>
