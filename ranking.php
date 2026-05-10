<?php
/**
 * Ranking de Usuarios - Optimizado
 */
header('Content-Type: application/json; charset=utf-8');

// Configuración de sesión más segura
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'path' => '/',
        'httponly' => true, // Evita acceso vía JS
        'samesite' => 'Lax'
    ]);
    session_start();
}

require_once __DIR__ . '/../../config/database.php';

/**
 * Función centralizada para respuestas JSON
 */
function resp(bool $ok, string $msg = '', array $extra = []): void {
    echo json_encode(array_merge([
        'success' => $ok,
        'message' => $msg,
        'timestamp' => time()
    ], $extra));
    exit;
}

$db = getConnection();
$action = $_GET['action'] ?? 'list'; // Por defecto list

try {
    switch ($action) {
        case 'list':
            // Consulta optimizada usando Sentencias Preparadas
            $sql = "SELECT 
                        u.id, 
                        u.nombre, 
                        u.puntos_totales, 
                        u.foto, 
                        COUNT(r.id) as total_reciclajes, 
                        COALESCE(SUM(r.cantidad), 0) as kg_reciclados 
                    FROM usuario u 
                    LEFT JOIN registro_reciclaje r ON u.id = r.usuario_id 
                    WHERE u.rol = 'usuario' 
                    GROUP BY u.id 
                    ORDER BY u.puntos_totales DESC 
                    LIMIT 100";

            $stmt = $db->prepare($sql);
            
            if (!$stmt) {
                throw new Exception("Error en la preparación de la consulta: " . $db->error);
            }

            $stmt->execute();
            $result = $stmt->get_result();
            
            $ranking = [];
            $posicion = 1;

            while ($row = $result->fetch_assoc()) {
                $row['posicion'] = $posicion++;
                // Asegurar que la foto tenga un valor o un placeholder
                $row['foto'] = $row['foto'] ?? 'default_avatar.png';
                $ranking[] = $row;
            }

            resp(true, 'Ranking obtenido correctamente', ['data' => $ranking]);
            break;

        default:
            resp(false, 'Acción no permitida o no encontrada');
            break;
    }
} catch (Exception $e) {
    // Registra el error real en el servidor para el desarrollador
    error_log("Error en ranking.php: " . $e->getMessage());
    
    // Respuesta genérica para el cliente (seguridad)
    resp(false, 'Lo sentimos, ha ocurrido un error interno.');
} finally {
    if (isset($db)) {
        $db->close();
    }
}