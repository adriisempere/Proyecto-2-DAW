<?php
/**
 * RankingController
 * Gestiona el sistema de ranking y clasificaciones de usuarios
 */
class RankingController {
    private $db;

    public function __construct() {
        $this->db = getConnection();
    }

    /**
     * Muestra el ranking global de usuarios
     */
    public function index() {
        // Obtener ranking de usuarios por puntos totales
        $query = "SELECT 
                    u.id,
                    u.nombre,
                    u.puntos_totales,
                    u.foto,
                    COUNT(r.id) as total_reciclajes,
                    SUM(r.cantidad) as kg_reciclados
                  FROM usuario u
                  LEFT JOIN registro_reciclaje r ON u.id = r.usuario_id
                  WHERE u.rol = 'usuario'
                  GROUP BY u.id
                  ORDER BY u.puntos_totales DESC
                  LIMIT 100";
        
        $result = $this->db->query($query);
        
        $ranking = [];
        $posicion = 1;
        while ($row = $result->fetch_assoc()) {
            $row['posicion'] = $posicion++;
            $ranking[] = $row;
        }

        // TODO: Crear vista profesional con tabla/cards
        echo "<!DOCTYPE html>";
        echo "<html lang='es'>";
        echo "<head>";
        echo "<meta charset='UTF-8'>";
        echo "<title>Ranking - GreenPoints</title>";
        echo "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css' rel='stylesheet'>";
        echo "<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css'>";
        echo "</head>";
        echo "<body>";
        
        echo "<div class='container mt-5'>";
        echo "<h1 class='text-center mb-4'><i class='bi bi-trophy-fill text-warning'></i> Ranking GreenPoints</h1>";
        echo "<a href='index.php?action=home' class='btn btn-secondary mb-3'>← Volver</a>";
        
        echo "<div class='table-responsive'>";
        echo "<table class='table table-striped table-hover'>";
        echo "<thead class='table-success'>";
        echo "<tr>";
        echo "<th>Posición</th>";
        echo "<th>Usuario</th>";
        echo "<th>Puntos</th>";
        echo "<th>Reciclajes</th>";
        echo "<th>Kg Reciclados</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        
        foreach ($ranking as $usuario) {
            $medalla = '';
            if ($usuario['posicion'] == 1) $medalla = "🥇";
            if ($usuario['posicion'] == 2) $medalla = "🥈";
            if ($usuario['posicion'] == 3) $medalla = "🥉";
            
            echo "<tr>";
            echo "<td><strong>{$medalla} {$usuario['posicion']}</strong></td>";
            echo "<td>{$usuario['nombre']}</td>";
            echo "<td><span class='badge bg-success'>{$usuario['puntos_totales']} pts</span></td>";
            echo "<td>{$usuario['total_reciclajes']}</td>";
            echo "<td>" . number_format($usuario['kg_reciclados'], 2) . " kg</td>";
            echo "</tr>";
        }
        
        echo "</tbody>";
        echo "</table>";
        echo "</div>";
        echo "</div>";
        
        echo "</body>";
        echo "</html>";
    }

    /**
     * Crea un snapshot del ranking en una fecha específica
     */
    public function crearSnapshot($descripcion = 'Ranking mensual') {
        // TODO: Implementar lógica para guardar ranking histórico
        // Esto permitiría comparar rankings de diferentes períodos
        
        $fecha = date('Y-m-d');
        
        // Crear registro de ranking
        $stmt = $this->db->prepare("INSERT INTO ranking (fecha, descripcion) VALUES (?, ?)");
        $stmt->bind_param("ss", $fecha, $descripcion);
        $stmt->execute();
        $ranking_id = $this->db->insert_id;
        
        // Obtener top usuarios
        $query = "SELECT id, puntos_totales FROM usuario WHERE rol = 'usuario' ORDER BY puntos_totales DESC LIMIT 100";
        $result = $this->db->query($query);
        
        $posicion = 1;
        while ($usuario = $result->fetch_assoc()) {
            $stmt = $this->db->prepare("INSERT INTO detalle_ranking (ranking_id, usuario_id, posicion, puntos) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiii", $ranking_id, $usuario['id'], $posicion, $usuario['puntos_totales']);
            $stmt->execute();
            $posicion++;
        }
        
        return $ranking_id;
    }
}
?>
