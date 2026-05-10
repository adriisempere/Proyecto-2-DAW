<?php
header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

session_start();

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../app/helpers/CsrfHelper.php';

/**
 * Función de respuesta estandarizada
 */
function resp($ok, $msg = '', $extra = [], $code = 200) {
    http_response_code($code);
    echo json_encode(array_merge(['success' => $ok, 'message' => $msg], $extra));
    exit;
}

try {
    $db = getConnection();
    // Reporte de errores estricto para capturarlos en el catch
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    $action = $_GET['action'] ?? null;
    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true) ?: $_POST;

    switch ($action) {
        case 'list':
            $stmt = $db->prepare('SELECT id, nombre, direccion, tipos_residuos, horario FROM centro_reciclaje ORDER BY nombre ASC');
            $stmt->execute();
            $result = $stmt->get_result();
            
            $out = [];
            while ($r = $result->fetch_assoc()) {
                // Enviamos los datos puros. El Front-end debe encargarse de la seguridad al imprimir.
                $out[] = $r;
            }
            
            resp(true, 'Centros obtenidos', ['data' => $out]);
            break;

        case 'store':
            // 1. Verificación de Rol
            if (empty($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'admin') {
                resp(false, 'No autorizado para realizar esta acción', [], 403);
            }

            // 2. Verificación CSRF
            $csrf = $data['csrf_token'] ?? null;
            if (!CsrfHelper::verifyToken($csrf)) {
                resp(false, 'Token de seguridad inválido o expirado', [], 400);
            }

            // 3. Recogida y limpieza
            $nombre    = trim($data['nombre'] ?? '');
            $direccion = trim($data['direccion'] ?? '');
            $tipos     = trim($data['tipos_residuos'] ?? '');
            $horario   = trim($data['horario'] ?? '');

            // 4. Validación robusta
            if (empty($nombre) || empty($direccion)) {
                resp(false, 'El nombre y la dirección son obligatorios', [], 400);
            }

            // 5. Inserción
            $stmt = $db->prepare('INSERT INTO centro_reciclaje (nombre, direccion, tipos_residuos, horario) VALUES (?, ?, ?, ?)');
            $stmt->bind_param('ssss', $nombre, $direccion, $tipos, $horario);
            $stmt->execute();

            resp(true, 'Centro de reciclaje creado exitosamente');
            break;

        default:
            resp(false, 'Acción no permitida', [], 404);
            break;
    }

} catch (mysqli_sql_exception $e) {
    // Loguear el error real en el servidor y mostrar mensaje genérico al usuario
    error_log("DB Error: " . $e->getMessage());
    resp(false, 'Error en la base de datos', ['debug' => 'Consulte los logs'], 500);
} catch (Exception $e) {
    error_log("General Error: " . $e->getMessage());
    resp(false, 'Error interno del servidor', [], 500);
}