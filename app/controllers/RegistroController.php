<?php
/**
 * RegistroController
 * Gestiona el registro de actividades de reciclaje de los usuarios
 */
class RegistroController {
    private $db;

    // Puntos otorgados por kg de material reciclado
    const PUNTOS_POR_MATERIAL = [
        'plastico' => 10,
        'papel' => 5,
        'vidrio' => 8,
        'metal' => 15,
        'organico' => 3
    ];

    public function __construct() {
        $this->db = getConnection();
    }

    /**
     * Muestra el formulario para registrar una actividad de reciclaje
     */
    public function showCreate() {
        // Verificar que el usuario esté autenticado
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?action=login');
            exit;
        }

        // Obtener centros de reciclaje para el selector
        $query = "SELECT id, nombre FROM centro_reciclaje ORDER BY nombre ASC";
        $centros = $this->db->query($query);

        // Cargar vista
        include __DIR__ . '/../views/registro_create.php';
    }

    /**
     * Guarda el registro de reciclaje y actualiza los puntos del usuario
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=registro_create');
            exit;
        }

        // Verificar autenticación
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?action=login');
            exit;
        }

        $usuario_id = $_SESSION['usuario_id'];
        $centro_id = !empty($_POST['centro_id']) ? $_POST['centro_id'] : null;
        $tipo_material = $_POST['tipo_material'] ?? '';
        $cantidad = floatval($_POST['cantidad'] ?? 0);

        // Validar material
        if (!array_key_exists($tipo_material, self::PUNTOS_POR_MATERIAL)) {
            $_SESSION['error'] = 'Tipo de material no válido';
            header('Location: index.php?action=registro_create');
            exit;
        }

        // Calcular puntos
        $puntos_ganados = intval($cantidad * self::PUNTOS_POR_MATERIAL[$tipo_material]);

        // Iniciar transacción
        $this->db->begin_transaction();

        try {
            // Insertar registro de reciclaje
            $stmt = $this->db->prepare("INSERT INTO registro_reciclaje (usuario_id, centro_id, tipo_material, cantidad, puntos_ganados) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("iisdi", $usuario_id, $centro_id, $tipo_material, $cantidad, $puntos_ganados);
            $stmt->execute();

            // Actualizar puntos del usuario
            $stmt = $this->db->prepare("UPDATE usuario SET puntos_totales = puntos_totales + ? WHERE id = ?");
            $stmt->bind_param("ii", $puntos_ganados, $usuario_id);
            $stmt->execute();

            // Confirmar transacción
            $this->db->commit();

            // Actualizar sesión
            $_SESSION['usuario_puntos'] = ($_SESSION['usuario_puntos'] ?? 0) + $puntos_ganados;
            $_SESSION['success'] = "¡Reciclaje registrado! Has ganado $puntos_ganados puntos.";
            
            header('Location: index.php?action=home');
        } catch (Exception $e) {
            // Revertir en caso de error
            $this->db->rollback();
            $_SESSION['error'] = 'Error al registrar el reciclaje';
            header('Location: index.php?action=registro_create');
        }
        exit;
    }

    /**
     * Lista los registros de reciclaje del usuario actual
     */
    public function misRegistros() {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?action=login');
            exit;
        }

        $usuario_id = $_SESSION['usuario_id'];
        
        $query = "SELECT r.*, c.nombre as centro_nombre 
                  FROM registro_reciclaje r 
                  LEFT JOIN centro_reciclaje c ON r.centro_id = c.id 
                  WHERE r.usuario_id = ? 
                  ORDER BY r.fecha DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $result = $stmt->get_result();

        // TODO: Crear vista para mostrar historial
        echo "<h1>Mis Registros de Reciclaje</h1>";
        
        while ($registro = $result->fetch_assoc()) {
            echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 10px 0;'>";
            echo "<p><strong>Fecha:</strong> {$registro['fecha']}</p>";
            echo "<p><strong>Material:</strong> {$registro['tipo_material']}</p>";
            echo "<p><strong>Cantidad:</strong> {$registro['cantidad']} kg</p>";
            echo "<p><strong>Puntos ganados:</strong> {$registro['puntos_ganados']}</p>";
            if ($registro['centro_nombre']) {
                echo "<p><strong>Centro:</strong> {$registro['centro_nombre']}</p>";
            }
            echo "</div>";
        }
    }
}
?>
