<?php
// Aseguramos el inicio de sesión para que las alertas funcionen
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pageTitle = "Registrar Reciclaje | GreenPoints";
include __DIR__ . '/partials/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow-lg animate__animated animate__fadeInUp">
                <div class="card-header text-white text-center py-4" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                    <h2 class="mb-0 fw-bold"><i class="bi bi-recycle me-2"></i>Registrar Reciclaje</h2>
                    <p class="mb-0 opacity-75">Suma puntos y ayuda al planeta</p>
                </div>
                <div class="card-body p-4 p-md-5">
                    
                    <div id="alertContainer">
                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i><?= htmlspecialchars($_SESSION['error']) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                            <?php unset($_SESSION['error']); ?>
                        <?php endif; ?>
                    </div>

                    <form id="registroForm" class="needs-validation" novalidate>
                        <?php 
                        require_once __DIR__ . '/../helpers/CsrfHelper.php'; 
                        echo CsrfHelper::getTokenField(); 
                        ?>
                        
                        <div class="mb-4">
                            <label for="centro_id" class="form-label fw-bold text-secondary">Centro de Reciclaje</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-geo-alt text-success"></i></span>
                                <select class="form-select border-start-0 ps-0" name="centro_id" id="centro_id" required>
                                    <option value="" selected disabled>Selecciona un centro cercano</option>
                                    </select>
                            </div>
                            <div class="invalid-feedback">Selecciona un centro de reciclaje.</div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-secondary">Tipo de Material</label>
                            <div class="row g-2" id="materialOptions">
                                <div class="col-12 text-center py-2 text-muted small">
                                    <div class="spinner-border spinner-border-sm text-success me-2"></div> Cargando materiales...
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="cantidad" class="form-label fw-bold text-secondary">Cantidad (kg)</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-speedometer2 text-success"></i></span>
                                <input type="number" class="form-control border-start-0 ps-0" name="cantidad" id="cantidad" 
                                    step="0.1" min="0.1" placeholder="Ej: 2.5" required>
                                <span class="input-group-text bg-light border-start-0">kg</span>
                            </div>
                            <div class="invalid-feedback">Ingresa una cantidad válida mayor a 0.</div>
                        </div>

                        <div id="estimacionPuntos" class="alert alert-light border text-center d-none animate__animated animate__fadeIn">
                            <span class="text-muted small">Puntos estimados a ganar:</span>
                            <h4 class="text-success fw-bold mb-0" id="puntosValor">+0 pts</h4>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" id="submitBtn" class="btn btn-success btn-lg py-3 rounded-pill shadow">
                                <span id="btnText"><i class="bi bi-check-lg me-2"></i>Registrar Puntos</span>
                                <span id="btnLoader" class="spinner-border spinner-border-sm d-none"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="text-center mt-4 animate__animated animate__fadeInUp">
                <a href="index.php?action=mis_registros" class="text-decoration-none text-success fw-bold">
                    <i class="bi bi-arrow-left me-1"></i> Ver mi historial
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .input-group-text { background-color: #fff; }
    .form-control:focus, .form-select:focus {
        border-color: #28a745;
        box-shadow: 0 0 0 0.25rem rgba(40, 167, 69, 0.1);
    }
    
    /* Efecto para opciones de material (radio buttons como botones) */
    .material-card {
        cursor: pointer;
        transition: all 0.2s;
        border: 2px solid #f8f9fa;
    }
    .material-card:hover { border-color: #28a745; background-color: #f0fff4; }
    
    .btn-check:checked + .btn-outline-success {
        background-color: #e8f5e9;
        font-weight: bold;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('registroForm');
    const submitBtn = document.getElementById('submitBtn');
    const btnText = document.getElementById('btnText');
    const btnLoader = document.getElementById('btnLoader');
    const cantidadInput = document.getElementById('cantidad');

    // 1. Validación y Feedback de envío
    form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
            form.classList.add('was-validated');
        } else {
            // Si es válido, mostramos carga para evitar doble clic
            submitBtn.disabled = true;
            btnText.classList.add('d-none');
            btnLoader.classList.remove('d-none');
        }
    }, false);

    // 2. Lógica simple para mostrar puntos estimados (Mejora funcional)
    cantidadInput.addEventListener('input', function() {
        const val = parseFloat(this.value);
        const estimacionDiv = document.getElementById('estimacionPuntos');
        const puntosValor = document.getElementById('puntosValor');

        if (val > 0) {
            estimacionDiv.classList.remove('d-none');
            // Supongamos un promedio de 10 puntos por kg para la previsualización
            puntosValor.innerText = `+${Math.round(val * 10)} pts`;
        } else {
            estimacionDiv.classList.add('d-none');
        }
    });
});
</script>

<script src="js/api-registro.js"></script>

<?php include __DIR__ . '/partials/footer.php'; ?>