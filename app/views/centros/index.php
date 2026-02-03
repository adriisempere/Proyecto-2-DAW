<?php
$pageTitle = "Centros de Reciclaje | GreenPoints";
include __DIR__ . '/../partials/header.php';
?>

<div class="container py-5">
    <div class="text-center mb-5 animate__animated animate__fadeInDown">
        <h6 class="text-success fw-bold text-uppercase" style="letter-spacing: 2px;">Encuentra tu punto más cercano</h6>
        <h1 class="display-4 fw-bold text-gradient">Centros de Reciclaje</h1>
        <p class="lead text-muted mt-3">Colabora con el planeta llevando tus residuos a nuestros puntos autorizados</p>
    </div>

    <div class="d-flex justify-content-end mb-4 animate__animated animate__fadeInRight">
        <?php if (isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin'): ?>
            <a href="index.php?action=centro_create" class="btn btn-success btn-pulse rounded-pill px-4">
                <i class="bi bi-plus-lg me-2"></i>Añadir Nuevo Centro
            </a>
        <?php endif; ?>
    </div>

    <div class="row g-4">
        <?php foreach ($centros as $index => $centro): ?>
            <?php 
                // Determine icon based on waste types (simple logic)
                $icon = 'bi-recycle';
                $tipos = strtolower($centro['tipos_residuos']);
                if (strpos($tipos, 'vidrio') !== false) $icon = 'bi-box-seam';
                if (strpos($tipos, 'plastico') !== false || strpos($tipos, 'plástico') !== false) $icon = 'bi-cup-straw';
                if (strpos($tipos, 'papel') !== false || strpos($tipos, 'carton') !== false) $icon = 'bi-file-text';
                
                // Stagger delay calculation
                $delayClass = ($index % 3 === 0) ? 'delay-100' : (($index % 3 === 1) ? 'delay-200' : 'delay-300');
            ?>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 centro-card shadow-sm hover-lift glass-card animate__animated animate__fadeInUp <?= $delayClass ?>">
                    <div class="card-body p-4 text-center">
                        <div class="centro-icon mx-auto">
                            <i class="bi <?= $icon ?>"></i>
                        </div>
                        <h4 class="card-title fw-bold mb-3"><?= htmlspecialchars($centro['nombre']) ?></h4>
                        
                        <div class="mb-3 text-start">
                             <p class="mb-2"><i class="bi bi-geo-alt-fill text-danger me-2"></i><?= htmlspecialchars($centro['direccion']) ?></p>
                             <p class="mb-2"><i class="bi bi-clock-fill text-primary me-2"></i><?= htmlspecialchars($centro['horario']) ?></p>
                        </div>
                        
                        <div class="centro-badges text-start">
                            <span class="d-block small text-muted mb-2 text-uppercase fw-bold">Tipos de Residuos:</span>
                            <?php 
                                $residuos = explode(',', $centro['tipos_residuos']);
                                foreach($residuos as $residuo): 
                            ?>
                                <span class="badge bg-success bg-opacity-75 rounded-pill me-1 mb-1"><?= trim(htmlspecialchars($residuo)) ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 p-4 pt-0">
                        <a href="https://maps.google.com/?q=<?= urlencode($centro['direccion']) ?>" target="_blank" class="btn btn-outline-success w-100 rounded-pill">
                            <i class="bi bi-map-fill me-2"></i>Ver en Mapa
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <?php if (empty($centros)): ?>
        <div class="text-center py-5 animate__animated animate__fadeIn">
            <div class="display-1 text-muted opacity-25 mb-3">
                <i class="bi bi-geo-alt"></i>
            </div>
            <h3>No hay centros disponibles</h3>
            <p class="text-muted">Vuelve a intentarlo más tarde o contacta con soporte.</p>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
