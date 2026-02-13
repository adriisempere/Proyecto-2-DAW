<?php
$pageTitle = "Registrar Reciclaje | GreenPoints";
include __DIR__ . '/partials/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow-lg animate__animated animate__fadeInUp">
                <div class="card-header bg-success text-white text-center py-4" style="background: var(--auth-header-gradient);">
                    <h2 class="mb-0 fw-bold"><i class="bi bi-recycle me-2"></i>Registrar Reciclaje</h2>
                    <p class="mb-0 opacity-75">Suma puntos y ayuda al planeta</p>
                </div>
                <div class="card-body p-4 p-md-5">
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i><?= $_SESSION['error'] ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>

                    <form method="POST" action="index.php?action=registro_store" class="needs-validation" novalidate>
                        
                        <!-- Centro de Reciclaje -->
                        <div class="mb-4">
                            <label for="centro_id" class="form-label fw-bold text-secondary">Centro de Reciclaje</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-geo-alt text-success"></i></span>
                                <select class="form-select border-start-0 ps-0" name="centro_id" id="centro_id" required>
                                    <option value="" selected disabled>Selecciona un centro cercano</option>
                                    <?php if ($centros && $centros->num_rows > 0): ?>
                                        <?php while ($centro = $centros->fetch_assoc()): ?>
                                            <option value="<?= $centro['id'] ?>"><?= htmlspecialchars($centro['nombre']) ?></option>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <option value="" disabled>No hay centros disponibles</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="form-text">Elige el punto donde depositaste tus residuos.</div>
                        </div>

                        <!-- Tipo de Material -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-secondary">Tipo de Material</label>
                            <div class="row g-2">
                                <?php 
                                $icons = [
                                    'plastico' => 'bi-box-seam',
                                    'papel' => 'bi-file-earmark-text',
                                    'vidrio' => 'bi-lightbulb',
                                    'metal' => 'bi-nut',
                                    'organico' => 'bi-flower1'
                                ];
                                foreach (RegistroController::PUNTOS_POR_MATERIAL as $material => $puntos): 
                                    $icon = $icons[$material] ?? 'bi-recycle';
                                ?>
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="tipo_material" id="material_<?= $material ?>" value="<?= $material ?>" required>
                                    <label class="btn btn-outline-success w-100 h-100 d-flex flex-column align-items-center justify-content-center py-3 position-relative" for="material_<?= $material ?>">
                                        <i class="bi <?= $icon ?> fs-2 mb-2"></i>
                                        <span class="text-capitalize fw-bold"><?= $material ?></span>
                                        <span class="badge bg-success bg-opacity-25 text-success rounded-pill mt-1"><?= $puntos ?> pts/kg</span>
                                    </label>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Cantidad -->
                        <div class="mb-4">
                            <label for="cantidad" class="form-label fw-bold text-secondary">Cantidad (kg)</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-speedometer2 text-success"></i></span>
                                <input type="number" class="form-control border-start-0 ps-0" name="cantidad" id="cantidad" step="0.1" min="0.1" placeholder="Ej: 2.5" required>
                                <span class="input-group-text bg-light border-start-0">kg</span>
                            </div>
                        </div>

                        <!-- Submit Button -->
                         <div class="d-grid mt-5">
                            <button type="submit" class="btn btn-success btn-lg py-3 rounded-pill shadow hover-lift btn-pulse">
                                <i class="bi bi-check-lg me-2"></i>Registrar Puntos
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="text-center mt-4 animate__animated animate__fadeInUp delay-200">
                <p class="text-muted small">
                    <i class="bi bi-info-circle me-1"></i>
                    Los puntos se acreditarán en tu cuenta inmediatamente.
                </p>
                <a href="index.php?action=mis_registros" class="text-decoration-none text-success fw-bold">
                    Ver mi historial <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .input-group-text {
        background-color: var(--input-bg);
        color: var(--text-main);
        border-color: var(--input-border);
    }
    .form-control:focus, .form-select:focus {
        border-color: #28a745;
        box-shadow: none;
        border-left: 1px solid #28a745 !important;
    }
    
    /* Custom radio buttons style */
    .btn-check:checked + .btn-outline-success {
        background-color: #e8f5e9;
        color: #198754;
        border-color: #198754;
        box-shadow: 0 0 0 0.25rem rgba(40, 167, 69, 0.25);
    }
    .btn-check:checked + .btn-outline-success i {
         transform: scale(1.1);
         transition: transform 0.2s;
    }
    
    [data-theme="dark"] .btn-check:checked + .btn-outline-success {
        background-color: rgba(40, 167, 69, 0.2);
        color: var(--primary-color);
        border-color: var(--primary-color);
    }
</style>

<script>
    // Validar formulario de Bootstrap
    (function () {
        'use strict'
        var forms = document.querySelectorAll('.needs-validation')
        Array.prototype.slice.call(forms)
            .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
    })()
</script>

<?php include __DIR__ . '/partials/footer.php'; ?>
