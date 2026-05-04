<?php
/**
 * Vista de Registro de Reciclaje — GreenPoints
 * ---------------------------------------------------------------
 * Formulario para que el usuario registre una actividad de
 * reciclaje y acumule puntos. Si no hay sesión activa redirige
 * al login directamente.
 *
 * Flujo:
 *   1. Al cargar, obtiene los centros disponibles desde
 *      api/centros.php?action=list y rellena el select.
 *   2. El usuario elige material (radio buttons con puntos/kg),
 *      centro y cantidad en kg.
 *   3. Un preview en tiempo real muestra los puntos que ganará
 *      antes de enviar.
 *   4. Al enviar llama a api/registro.php?action=store.
 *   5. Si tiene éxito, actualiza el badge de puntos del header
 *      y redirige al historial.
 * ---------------------------------------------------------------
 */

// Redirigir si no está autenticado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php?action=login');
    exit;
}

$pageTitle = 'Registrar Reciclaje | GreenPoints';
include __DIR__ . '/partials/header.php';
?>


<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">

            <div class="card border-0 shadow-lg animate__animated animate__fadeInUp">

                <!-- Cabecera -->
                <div class="card-header text-white text-center py-4 border-0 registro-header">
                    <h2 class="fw-bold mb-1">
                        <i class="bi bi-recycle me-2"></i>Registrar Reciclaje
                    </h2>
                    <p class="mb-0 opacity-75">Suma puntos y ayuda al planeta</p>
                </div>

                <div class="card-body p-4 p-md-5">

                    <!-- Alerta de feedback -->
                    <div id="formAlert" class="alert d-none mb-4" role="alert"></div>

                    <form id="registroForm" novalidate>
                        <?php
                        require_once __DIR__ . '/../helpers/CsrfHelper.php';
                        echo CsrfHelper::getTokenField();
                        ?>

                        <!-- Tipo de material -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-tags me-1 text-success"></i>Tipo de Material
                            </label>
                            <div class="row g-2 material-selector" id="materialOptions">
                                <!-- Generado por JS con los puntos/kg de cada material -->
                            </div>
                            <div id="materialError" class="text-danger small mt-1 d-none">
                                Selecciona un tipo de material.
                            </div>
                        </div>

                        <!-- Cantidad -->
                        <div class="mb-4">
                            <label for="cantidad" class="form-label fw-semibold">
                                <i class="bi bi-speedometer2 me-1 text-success"></i>Cantidad (kg)
                            </label>
                            <div class="input-group input-group-lg">
                                <input type="number" class="form-control" id="cantidad"
                                       name="cantidad" step="0.1" min="0.1"
                                       placeholder="Ej: 2.5" required>
                                <span class="input-group-text">kg</span>
                            </div>
                            <div class="invalid-feedback">
                                Introduce una cantidad mayor que 0.
                            </div>
                        </div>

                        <!-- Centro de reciclaje -->
                        <div class="mb-4">
                            <label for="centro_id" class="form-label fw-semibold">
                                <i class="bi bi-geo-alt me-1 text-success"></i>Centro de Reciclaje
                                <span class="text-muted fw-normal small">(opcional)</span>
                            </label>
                            <select class="form-select form-select-lg" id="centro_id" name="centro_id">
                                <option value="">Sin centro específico</option>
                            </select>
                            <div class="form-text">
                                Puedes registrar sin seleccionar un centro.
                            </div>
                        </div>

                        <!-- Preview de puntos -->
                        <div id="previewBox" class="preview-box mb-4 d-none">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="small opacity-75 mb-1">Puntos que ganarás</div>
                                    <div class="preview-pts" id="previewPts">0</div>
                                </div>
                                <i class="bi bi-star-fill opacity-50" style="font-size:3rem"></i>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg rounded-pill py-3" id="submitBtn">
                                <i class="bi bi-check-lg me-2"></i>Registrar Puntos
                            </button>
                        </div>

                    </form>
                </div>
            </div>

            <div class="text-center mt-4 text-muted small animate__animated animate__fadeIn">
                <i class="bi bi-info-circle me-1"></i>
                Los puntos se acreditan inmediatamente en tu cuenta.
                <br>
                <a href="index.php?action=mis_registros"
                   class="text-success fw-semibold text-decoration-none mt-1 d-inline-block">
                    Ver mi historial <i class="bi bi-arrow-right"></i>
                </a>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const form       = document.getElementById('registroForm');
    const submitBtn  = document.getElementById('submitBtn');
    const alertBox   = document.getElementById('formAlert');
    const previewBox = document.getElementById('previewBox');
    const previewPts = document.getElementById('previewPts');
    const cantidadEl = document.getElementById('cantidad');
    const materialErr = document.getElementById('materialError');

    // Tabla de puntos por kg — debe coincidir con PUNTOS_POR_MATERIAL en registro.php
    const MATERIALES = [
        { id: 'plastico', nombre: 'Plástico', icon: 'bi-bag',          pts: 10 },
        { id: 'papel',    nombre: 'Papel',    icon: 'bi-file-earmark', pts: 5  },
        { id: 'vidrio',   nombre: 'Vidrio',   icon: 'bi-cup',          pts: 8  },
        { id: 'metal',    nombre: 'Metal',    icon: 'bi-gear',         pts: 15 },
        { id: 'organico', nombre: 'Orgánico', icon: 'bi-tree',         pts: 3  },
    ];

    // ── Renderizar opciones de material ──────────────────────────
    const matContainer = document.getElementById('materialOptions');
    matContainer.innerHTML = MATERIALES.map(m => `
        <div class="col-4 col-md">
            <input type="radio" class="btn-check" name="tipo_material"
                   id="mat_${m.id}" value="${m.id}" autocomplete="off">
            <label class="material-label w-100" for="mat_${m.id}">
                <i class="bi ${m.icon} material-icon"></i>
                <span class="material-name">${m.nombre}</span>
                <span class="material-pts">${m.pts} pts/kg</span>
            </label>
        </div>
    `).join('');

    // ── Calcular y mostrar preview de puntos ─────────────────────
    function updatePreview() {
        const tipo     = form.querySelector('[name="tipo_material"]:checked');
        const cantidad = parseFloat(cantidadEl.value);
        const mat      = MATERIALES.find(m => m.id === tipo?.value);

        if (mat && cantidad > 0) {
            const pts = Math.round(cantidad * mat.pts);
            previewPts.textContent = pts;
            previewBox.classList.remove('d-none');
        } else {
            previewBox.classList.add('d-none');
        }
    }

    matContainer.addEventListener('change', updatePreview);
    cantidadEl.addEventListener('input',    updatePreview);

    // ── Cargar centros en el select ───────────────────────────────
    fetch('api/centros.php?action=list')
        .then(r => r.json())
        .then(json => {
            if (!json.success) return;
            const select = document.getElementById('centro_id');
            json.data.forEach(c => {
                const opt = document.createElement('option');
                opt.value       = c.id;
                opt.textContent = c.nombre + ' — ' + c.direccion;
                select.appendChild(opt);
            });
        });

    // ── Alerta de feedback ────────────────────────────────────────
    function showAlert(msg, type = 'danger') {
        alertBox.className = `alert alert-${type} animate__animated animate__fadeIn`;
        alertBox.innerHTML = `<i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}-fill me-2"></i>${msg}`;
    }

    // ── Envío del formulario ──────────────────────────────────────
    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        const tipo     = form.querySelector('[name="tipo_material"]:checked');
        const cantidad = parseFloat(cantidadEl.value);
        let valid      = true;

        // Validar material
        if (!tipo) {
            materialErr.classList.remove('d-none');
            valid = false;
        } else {
            materialErr.classList.add('d-none');
        }

        // Validar cantidad
        if (!cantidadEl.checkValidity() || cantidad <= 0) {
            cantidadEl.classList.add('is-invalid');
            valid = false;
        } else {
            cantidadEl.classList.remove('is-invalid');
            cantidadEl.classList.add('is-valid');
        }

        if (!valid) return;

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Registrando…';

        const body = {
            csrf_token:    form.querySelector('[name="csrf_token"]').value,
            tipo_material: tipo.value,
            cantidad:      cantidad,
            centro_id:     document.getElementById('centro_id').value || null,
        };

        try {
            const res  = await fetch('api/registro.php?action=store', {
                method:  'POST',
                headers: { 'Content-Type': 'application/json' },
                body:    JSON.stringify(body),
            });
            const json = await res.json();

            if (json.success) {
                // Actualizar badge de puntos en el header si existe
                const badge = document.querySelector('.badge-points .fw-bold');
                if (badge && json.puntos_totales !== undefined) {
                    badge.textContent = json.puntos_totales + ' pts';
                }

                showAlert(
                    `¡Registro exitoso! Has ganado <strong>${json.puntos_ganados} puntos</strong>. Redirigiendo…`,
                    'success'
                );
                setTimeout(() => { window.location.href = 'index.php?action=mis_registros'; }, 1500);
            } else {
                showAlert(json.message || 'No se pudo registrar el reciclaje.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="bi bi-check-lg me-2"></i>Registrar Puntos';
            }
        } catch {
            showAlert('Error de red. Comprueba tu conexión e inténtalo de nuevo.');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="bi bi-check-lg me-2"></i>Registrar Puntos';
        }
    });
});
</script>

<?php include __DIR__ . '/partials/footer.php'; ?>