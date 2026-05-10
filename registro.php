<?php
header('Content-Type: application/json; charset=utf-8');

// Configuración de sesión robusta
if (session_status() === PHP_SESSION_NONE) {
    if (!session_start(['cookie_path' => '/'])) {
        echo json_encode(['success' => false, 'message' => 'Error al iniciar sesión']);
        exit;
    }
}

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../app/helpers/CsrfHelper.php';

$db = getConnection();
// Asegurar que la conexión use UTF-8
$db->set_charset("utf8mb4");

$action = $_GET['action'] ?? null;
$raw = file_get_contents('php://input');
$data = json_decode($raw, true) ?: $_POST;

/**
 * Función de respuesta estandarizada
 */
function resp($ok, $msg = '', $extra = []) { 
    echo json_encode(array_merge(['success' => $ok, 'message' => $msg], $extra)); 
    exit; 
}

try {
    switch ($action) {
        case 'store':
            // 1. Verificación de Autenticación
            if (!isset($_SESSION['usuario_id'])) { 
                resp(false, 'No autenticado', ['redirect' => 'index.php?action=login']); 
            }

            // 2. Verificación de Seguridad CSRF
            $csrf = $data['csrf_token'] ?? null;
            if (!CsrfHelper::verifyToken($csrf)) { 
                resp(false, 'Token de seguridad inválido o expirado'); 
            }

            // 3. Recolección y Limpieza de datos
            $usuario_id = (int)$_SESSION['usuario_id'];
            $centro_id = !empty($data['centro_id']) ? (int)$data['centro_id'] : null;
            $tipo = trim($data['tipo_material'] ?? '');
            $cantidad = filter_var($data['cantidad'] ?? 0, FILTER_VALIDATE_FLOAT);

            $puntos_map = [
                'plastico' => 10, 'papel' => 5, 'vidrio' => 8, 'metal' => 15, 'organico' => 3
            ];

            if (!$cantidad || $cantidad <= 0 || !isset($puntos_map[$tipo])) { 
                resp(false, 'Datos de material o cantidad inválidos'); 
            }

            // 4. Cálculo de puntos
            $puntos = (int)($cantidad * $puntos_map[$tipo]);

            // 5. Operación en Base de Datos (Transaccional)
            $db->begin_transaction();
            try {
                // Opcional: Validar que el centro existe si no es null
                if ($centro_id !== null) {
                    $checkCentro = $db->prepare("SELECT id FROM centro_reciclaje WHERE id = ?");
                    $checkCentro->bind_param('i', $centro_id);
                    $checkCentro->execute();
                    if ($checkCentro->get_result()->num_rows === 0) {
                        throw new Exception("El centro de reciclaje no existe");
                    }
                }

                // Insertar registro
                $stmt = $db->prepare('INSERT INTO registro_reciclaje (usuario_id, centro_id, tipo_material, cantidad, puntos_ganados) VALUES (?, ?, ?, ?, ?)');
                $stmt->bind_param('iisdi', $usuario_id, $centro_id, $tipo, $cantidad, $puntos);
                $stmt->execute();

                // Actualizar puntos del usuario
                $stmt2 = $db->prepare('UPDATE usuario SET puntos_totales = puntos_totales + ? WHERE id = ?');
                $stmt2->bind_param('ii', $puntos, $usuario_id);
                $stmt2->execute();

                $db->commit();

                // Sincronizar sesión con el nuevo valor real de la BD
                $stmt3 = $db->prepare('SELECT puntos_totales FROM usuario WHERE id = ?');
                $stmt3->bind_param('i', $usuario_id);
                $stmt3->execute();
                $nuevoTotal = $stmt3->get_result()->fetch_assoc()['puntos_totales'];
                $_SESSION['usuario_puntos'] = $nuevoTotal;

                resp(true, '¡Reciclaje registrado con éxito!', [
                    'puntos_ganados' => $puntos,
                    'puntos_totales' => $nuevoTotal
                ]);

            } catch (Exception $e) {
                $db->rollback();
                resp(false, 'Error al procesar el registro: ' . $e->getMessage());
            }
            break;

        case 'list':
            if (!isset($_SESSION['usuario_id'])) { 
                resp(false, 'No autenticado'); 
            }
            
            $uid = (int)$_SESSION['usuario_id'];
            $query = "SELECT r.*, c.nombre as centro_nombre 
                      FROM registro_reciclaje r 
                      LEFT JOIN centro_reciclaje c ON r.centro_id = c.id 
                      WHERE r.usuario_id = ? 
                      ORDER BY r.fecha DESC";
            
            $stmt = $db->prepare($query);
            $stmt->bind_param('i', $uid);
            $stmt->execute();
            $res = $stmt->get_result();
            
            $out = [];
            while ($row = $res->fetch_assoc()) {
                $out[] = $row;
            }
            resp(true, 'Registros obtenidos', ['data' => $out]);
            break;

        default:
            resp(false, 'Acción no permitida');
    }
} catch (Exception $e) { 
    // Registrar error en el log del servidor y no mostrar detalles sensibles al cliente
    error_log($e->getMessage());
    resp(false, 'Ocurrió un error interno en el servidor'); 
}
?>