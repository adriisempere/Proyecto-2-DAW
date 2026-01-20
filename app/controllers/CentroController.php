<?php
/**
 * CentroController
 * Gestiona las operaciones relacionadas con centros de reciclaje
 */
class CentroController {
    private $db;

    public function __construct() {
        $this->db = getConnection();
    }

    /**
     * Lista todos los centros de reciclaje
     */
    public function index() {
        $query = "SELECT * FROM centro_reciclaje ORDER BY nombre ASC";
        $result = $this->db->query($query);
        
        $centros = [];
        while ($row = $result->fetch_assoc()) {
            $centros[] = $row;
        }

        // TODO: Crear vista para listar centros
        echo "<h1>Centros de Reciclaje</h1>";
        echo "<a href='index.php?action=centro_create'>Añadir nuevo centro</a><br><br>";
        
        foreach ($centros as $centro) {
            echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 10px 0;'>";
            echo "<h3>{$centro['nombre']}</h3>";
            echo "<p><strong>Dirección:</strong> {$centro['direccion']}</p>";
            echo "<p><strong>Tipos de residuos:</strong> {$centro['tipos_residuos']}</p>";
            echo "<p><strong>Horario:</strong> {$centro['horario']}</p>";
            echo "</div>";
        }
    }

    /**
     * Muestra el formulario para crear un centro
     */
    public function showCreate() {
        // TODO: Verificar que el usuario sea administrador
        
        // TODO: Crear vista con formulario
        echo "<h1>Crear Centro de Reciclaje</h1>";
        echo "<form method='POST' action='index.php?action=centro_store'>";
        echo "<input type='text' name='nombre' placeholder='Nombre' required><br>";
        echo "<textarea name='direccion' placeholder='Dirección' required></textarea><br>";
        echo "<input type='text' name='tipos_residuos' placeholder='Tipos de residuos' required><br>";
        echo "<input type='text' name='horario' placeholder='Horario' required><br>";
        echo "<button type='submit'>Crear Centro</button>";
        echo "</form>";
    }

    /**
     * Guarda un nuevo centro en la base de datos
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=centro_create');
            exit;
        }

        // TODO: Verificar que el usuario sea administrador
        // TODO: Implementar validación completa

        $nombre = $_POST['nombre'] ?? '';
        $direccion = $_POST['direccion'] ?? '';
        $tipos_residuos = $_POST['tipos_residuos'] ?? '';
        $horario = $_POST['horario'] ?? '';

        $stmt = $this->db->prepare("INSERT INTO centro_reciclaje (nombre, direccion, tipos_residuos, horario) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nombre, $direccion, $tipos_residuos, $horario);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Centro creado correctamente';
            header('Location: index.php?action=centros');
        } else {
            $_SESSION['error'] = 'Error al crear centro';
            header('Location: index.php?action=centro_create');
        }
        exit;
    }
}
?>
