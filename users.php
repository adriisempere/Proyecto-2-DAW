<?php
header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff'); // Seguridad: evita que el navegador adivine el tipo de contenido

session_start();

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../app/helpers/CsrfHelper.php';

$db = getConnection();
// Activar excepciones en MySQLi para capturarlas en el bloque catch
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$action = $_GET['action'] ?? null;
$raw = file_get_contents('php://input');
$data = json_decode($raw, true) ?: $_POST;

/**
 * Función de respuesta estandarizada
 */
function resp($ok, $msg = '', $extra = [], $code = 200) {
    http_response_code($code);
    echo json_encode(array_merge(['success' => $ok, 'message' => $msg], $extra));
    exit;
}

try {
    switch ($action) {
        case 'list':
            $out = [];
            $stmt = $db->prepare('SELECT id, nombre, direccion, tipos_residuos, horario FROM centro_reciclaje ORDER BY nombre ASC');
            $stmt->execute();
            $result = $stmt->get_result();
            
            while ($r = $result->fetch_assoc()) {
                // Sanitización básica para evitar XSS al mostrar en HTML
                $out[] = array_map('htmlspecialchars', $r);
            }
            
            resp(true, 'Centros obtenidos', ['data' => $out]);
            break;

        case 'store':
            // 1. Verificación de Rol (403 Forbidden si no es admin)
            if (empty($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'admin') {
                resp(false, 'No autorizado', [], 403);
            }

            // 2. Verificación CSRF
            $csrf = $data['csrf_token'] ?? null;
            if (!CsrfHelper::verifyToken($csrf)) {
                resp(false, 'Token de seguridad inválido o expirado', [], 400);
            }

            // 3. Recogida y limpieza de datos
            $nombre = trim($data['nombre'] ?? '');
            $direccion = trim($data['direccion'] ?? '');
            $tipos = trim($data['tipos_residuos'] ?? '');
            $horario = trim($data['horario'] ?? '');

            // 4. Validación simple
            if (empty($nombre) || empty($direccion)) {
                resp(false, 'El nombre y la dirección son campos obligatorios', [], 400);
            }

            // 5. Inserción
            $stmt = $db->prepare('INSERT INTO centro_reciclaje (nombre, direccion, tipos_residuos, horario) VALUES (?, ?, ?, ?)');
            $stmt->bind_param('ssss', $nombre, $direccion, $tipos, $horario);
            
            if ($stmt->execute()) {
                resp(true, 'Centro creado exitosamente');
            } else {
                resp(false, 'No se pudo guardar el centro');
            }
            break;

        default:
            resp(false, 'Acción no permitida o no encontrada', [], 404);
            break;
    }
} catch (mysqli_sql_exception $e) {
    // Error específico de base de datos
    resp(false, 'Error en la base de datos: ' . $e->getMessage(), [], 500);
} catch (Exception $e) {
    // Error general
    resp(false, 'Error interno del servidor', ['debug' => $e->getMessage()], 500);
}