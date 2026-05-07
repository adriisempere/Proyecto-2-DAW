<?php
/**
 * Vista de Registro de Reciclaje — GreenPoints
 */
if (!isset($_SESSION["usuario_id"])) {
    header("Location: index.php?action=login");
    exit();
}
$pageTitle = "Registrar Reciclaje | GreenPoints";
include __DIR__ . "/partials/header.php";
?>

<style>
/* ── Diseño general ─────────────────────────────────────────── */
.registro-wrapper {
    min-height: calc(100vh - 70px);
    background: linear-gradient(160deg, #f0fdf4 0%, #ecfdf5 50%, #f0fdf4 100%);
    padding: 2.5rem 0;
}

/* ── Tarjeta del formulario ─────────────────────────────────── */
.registro-card {
    background: rgba(255,255,255,.92);
    backdrop-filter: blur(20px) saturate(160%);
    -webkit-backdrop-filter: blur(20px) saturate(160%);
    border: 1px solid rgba(255,255,255,.75);
    border-radius: 28px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(6,78,59,.1), 0 1px 0 rgba(255,255,255,.6) inset;
}

.registro-card-header {
    background: linear-gradient(135deg, #065f46 0%, #047857 45%, #0d9488 100%);
    padding: 2rem 2.25rem 1.75rem;
    position: relative;
    overflow: hidden;
}
.registro-card-header::before {
    content: '';
    position: absolute; inset: 0;
    background: radial-gradient(ellipse at 80% 50%, rgba(255,255,255,.08), transparent 65%);
    pointer-events: none;
}
.registro-card-header h2 {
    font-size: 1.5rem;
    font-weight: 800;
    color: #fff;
    margin-bottom: .2rem;
    position: relative;
}
.registro-card-header p {
    color: rgba(255,255,255,.72);
    font-size: .875rem;
    margin: 0;
    position: relative;
}
.registro-header-icon {
    width: 58px; height: 58px;
    background: rgba(255,255,255,.15);
    border: 2px solid rgba(255,255,255,.28);
    border-radius: 16px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.7rem; color: #fff;
    margin-bottom: 1rem;
    position: relative;
    animation: iconPulse 3s ease-in-out infinite;
}
@keyframes iconPulse {
    0%,100% { transform: scale(1); }
    50%      { transform: scale(1.07); }
}

.registro-card-body { padding: 2rem 2.25rem 2.25rem; }

/* ── Selector de material ───────────────────────────────────── */
.material-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: .6rem; }

.material-label {
    cursor: pointer;
    border: 2px solid #e5e7eb;
    border-radius: 14px;
    padding: .85rem .5rem;
    text-align: center;
    transition: all .25s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: .3rem;
    background: rgba(255,255,255,.8);
    user-select: none;
}
.material-label:hover {
    border-color: #059669;
    background: rgba(5,150,105,.04);
    transform: translateY(-2px);
}
.btn-check:checked + .material-label {
    border-color: #059669;
    background: rgba(5,150,105,.08);
    box-shadow: 0 0 0 3px rgba(5,150,105,.16);
    transform: translateY(-2px);
}

.material-icon { font-size: 1.5rem; }
.material-name { font-weight: 600; font-size: .78rem; color: #374151; }
.material-pts  { font-size: .68rem; color: #6b7280; }
.btn-check:checked + .material-label .material-pts {
    color: #059669;
    font-weight: 700;
}

/* Colores del material cuando está seleccionado */
.btn-check:checked + .material-label .material-icon { filter: drop-shadow(0 2px 6px rgba(5,150,105,.4)); }

/* ── Campos del formulario ──────────────────────────────────── */
.reg-form-label {
    font-weight: 600;
    font-size: .85rem;
    color: #374151;
    margin-bottom: .4rem;
    display: flex;
    align-items: center;
    gap: .35rem;
}
.reg-form-label i { color: #059669; }

.reg-input {
    background: rgba(240,253,244,.8);
    border: 1.5px solid #d1fae5;
    border-radius: 12px;
    padding: .7rem 1rem;
    font-size: .9rem;
    font-family: 'Poppins', sans-serif;
    color: #1f2937;
    transition: all .25s ease;
    width: 100%;
}
.reg-input:focus {
    outline: none;
    border-color: #059669;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(5,150,105,.12);
}
.reg-input.is-invalid { border-color: #ef4444; box-shadow: 0 0 0 3px rgba(239,68,68,.1); }
.reg-input.is-valid   { border-color: #22c55e; box-shadow: 0 0 0 3px rgba(34,197,94,.1); }

/* ── Vista previa de puntos ─────────────────────────────────── */
.preview-box {
    background: linear-gradient(135deg, #065f46 0%, #059669 50%, #0d9488 100%);
    border-radius: 18px;
    padding: 1.5rem;
    color: #fff;
    position: relative;
    overflow: hidden;
    transition: all .35s ease;
}
.preview-box::before {
    content: '';
    position: absolute; inset: 0;
    background: radial-gradient(ellipse at 80% 30%, rgba(255,255,255,.1), transparent 60%);
    pointer-events: none;
}
.preview-label { font-size: .78rem; opacity: .75; text-transform: uppercase; letter-spacing: .06em; margin-bottom: .3rem; }
.preview-pts   { font-size: 3rem; font-weight: 800; line-height: 1; }
.preview-sub   { font-size: .8rem; opacity: .7; margin-top: .3rem; }

/* ── Botón de envío ─────────────────────────────────────────── */
.btn-registrar {
    width: 100%;
    background: linear-gradient(135deg, #059669, #0d9488);
    border: none;
    border-radius: 14px;
    padding: 1rem 1.5rem;
    font-weight: 700;
    font-size: 1rem;
    color: #fff;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    transition: all .3s ease;
    box-shadow: 0 6px 20px rgba(5,150,105,.38);
    font-family: 'Poppins', sans-serif;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: .5rem;
}
.btn-registrar:hover { transform: translateY(-2px); box-shadow: 0 12px 30px rgba(5,150,105,.5); }
.btn-registrar:active { transform: translateY(0); }
.btn-registrar:disabled { opacity: .65; cursor: not-allowed; transform: none; }

/* ── Panel informativo lateral ──────────────────────────────── */
.info-panel {
    background: rgba(255,255,255,.7);
    border: 1px solid rgba(255,255,255,.8);
    border-radius: 20px;
    padding: 1.5rem;
    box-shadow: 0 4px 20px rgba(6,78,59,.06);
}

.mat-info-item {
    display: flex;
    align-items: center;
    gap: .75rem;
    padding: .6rem .5rem;
    border-radius: 10px;
    transition: background .2s;
    font-size: .875rem;
}
.mat-info-item:hover { background: rgba(240,253,244,.8); }
.mat-dot {
    width: 10px; height: 10px;
    border-radius: 50%;
    flex-shrink: 0;
}

/* ── Alerta ──────────────────────────────────────────────────── */
.form-alert { border-radius: 12px; font-size: .875rem; border: none; padding: .85rem 1rem; }

/* ── Diseño adaptable (responsive) ──────────────────────────── */
@media (max-width: 767.98px) {
    .material-grid  { grid-template-columns: repeat(3, 1fr); }
    .registro-card-body { padding: 1.5rem; }
    .registro-card-header { padding: 1.75rem 1.5rem; }
}
@media (max-width: 575.98px) {
    .registro-wrapper { padding: 1rem 0; }
    .material-grid  { grid-template-columns: repeat(2, 1fr); }
    .preview-pts    { font-size: 2.2rem; }
}
</style>

<div class="registro-wrapper">
    <div class="container">
        <div class="row justify-content-center gy-4">

            <!-- ── Columna principal del formulario ─────────── -->
            <div class="col-lg-7 col-xl-6">
                <div class="registro-card animate__animated animate__fadeInUp">

                    <!-- Encabezado de la tarjeta -->
                    <div class="registro-card-header">
                        <div class="registro-header-icon">
                            <i class="bi bi-recycle"></i>
                        </div>
                        <h2>Registrar Reciclaje</h2>
                        <p>Suma puntos y ayuda al planeta</p>
                    </div>

                    <!-- Cuerpo del formulario -->
                    <div class="registro-card-body">

                        <div id="formAlert" class="form-alert alert d-none mb-4" role="alert"></div>

                        <form id="registroForm" novalidate>
                            <?php
                            require_once __DIR__ . "/../helpers/CsrfHelper.php";
                            echo CsrfHelper::getTokenField();
                            ?>

                            <!-- Selector de tipo de material -->
                            <div class="mb-4">
                                <label class="reg-form-label">
                                    <i class="bi bi-tags"></i>Tipo de Material
                                </label>
                                <div class="material-grid" id="materialOptions">
                                    <!-- Generado dinámicamente por JS -->
                                </div>
                                <div id="materialError" class="text-danger small mt-2 d-none">
                                    <i class="bi bi-exclamation-circle me-1"></i>Selecciona un tipo de material.
                                </div>
                            </div>

                            <!-- Cantidad en kilogramos -->
                            <div class="mb-4">
                                <label for="cantidad" class="reg-form-label">
                                    <i class="bi bi-speedometer2"></i>Cantidad en kg
                                </label>
                                <div class="d-flex align-items-center gap-2">
                                    <input type="number"
                                           class="reg-input"
                                           id="cantidad" name="cantidad"
                                           step="0.1" min="0.1"
                                           placeholder="Ej: 2.5"
                                           required
                                           style="max-width:200px;">
                                    <span class="text-muted fw-semibold">kg</span>
                                </div>
                                <div class="invalid-feedback d-block" id="cantidadError"
                                     style="display:none!important;font-size:.8rem;color:#ef4444;margin-top:.3rem;"></div>
                            </div>

                            <!-- Selección de centro (opcional) -->
                            <div class="mb-4">
                                <label for="centro_id" class="reg-form-label">
                                    <i class="bi bi-geo-alt"></i>Centro de Reciclaje
                                    <span class="text-muted fw-normal" style="font-size:.78rem;">(opcional)</span>
                                </label>
                                <select class="reg-input" id="centro_id" name="centro_id">
                                    <option value="">Sin centro específico</option>
                                </select>
                                <p class="text-muted mt-1" style="font-size:.77rem;">
                                    <i class="bi bi-info-circle me-1"></i>Puedes registrar sin seleccionar un centro.
                                </p>
                            </div>

                            <!-- Vista previa de puntos a ganar -->
                            <div id="previewBox" class="preview-box mb-4 d-none">
                                <div class="d-flex justify-content-between align-items-center" style="position:relative;">
                                    <div>
                                        <p class="preview-label mb-1">Puntos que ganarás</p>
                                        <div class="preview-pts" id="previewPts">0</div>
                                        <p class="preview-sub mb-0">puntos GreenPoints</p>
                                    </div>
                                    <i class="bi bi-stars" style="font-size:3.5rem;opacity:.25;"></i>
                                </div>
                            </div>

                            <!-- Botón de registro -->
                            <button type="submit" class="btn-registrar" id="submitBtn">
                                <i class="bi bi-check-lg"></i>Registrar Puntos
                            </button>

                        </form>

                        <div class="text-center mt-3">
                            <a href="index.php?action=mis_registros"
                               class="text-muted text-decoration-none" style="font-size:.82rem;">
                                <i class="bi bi-clock-history me-1"></i>Ver mi historial de registros
                            </a>
                        </div>

                    </div>
                </div>
            </div>

            <!-- ── Panel informativo lateral ────────────────── -->
            <div class="col-lg-4 col-xl-3 d-none d-lg-block">
                <div class="info-panel animate__animated animate__fadeInRight animate__delay-1s" style="position:sticky;top:90px;">
                    <h6 class="fw-bold text-success mb-3">
                        <i class="bi bi-star-fill me-1"></i>Puntos por Material
                    </h6>
                    <div id="materialInfoList"></div>

                    <hr style="border-color:#d1fae5;margin:1rem 0;">

                    <div class="d-flex align-items-start gap-2">
                        <i class="bi bi-lightbulb-fill text-warning mt-1"></i>
                        <p class="text-muted mb-0" style="font-size:.8rem;">
                            Los puntos se acreditan <strong>inmediatamente</strong> en tu cuenta
                            y puedes canjearlos en la tienda de recompensas.
                        </p>
                    </div>

                    <div class="mt-3 p-3 text-center" style="background:rgba(240,253,244,.8);border-radius:12px;border:1px solid rgba(209,250,229,.8);">
                        <div style="font-size:1.75rem;margin-bottom:.4rem;">🌱</div>
                        <p class="fw-semibold text-success mb-1" style="font-size:.82rem;">¡Tus puntos cuentan!</p>
                        <p class="text-muted mb-0" style="font-size:.76rem;">Cada kg reciclado reduce tu huella de carbono</p>
                    </div>
                </div>
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

    // Definiciones de materiales: deben coincidir con PUNTOS_POR_MATERIAL en la API
    const MATERIALES = [
        { id: 'plastico', nombre: 'Plástico', icon: 'bi-bag-fill',          pts: 10, color: '#3b82f6' },
        { id: 'papel',    nombre: 'Papel',    icon: 'bi-file-earmark-fill', pts: 5,  color: '#d97706' },
        { id: 'vidrio',   nombre: 'Vidrio',   icon: 'bi-cup-fill',          pts: 8,  color: '#0891b2' },
        { id: 'metal',    nombre: 'Metal',    icon: 'bi-gear-fill',         pts: 15, color: '#6b7280' },
        { id: 'organico', nombre: 'Orgánico', icon: 'bi-tree-fill',         pts: 3,  color: '#16a34a' },
    ];

    // Renderiza los botones de selección de material con radio buttons ocultos
    const matContainer = document.getElementById('materialOptions');
    matContainer.innerHTML = MATERIALES.map(m => `
        <div>
            <input type="radio" class="btn-check" name="tipo_material"
                   id="mat_${m.id}" value="${m.id}" autocomplete="off">
            <label class="material-label w-100 h-100" for="mat_${m.id}">
                <i class="bi ${m.icon} material-icon" style="color:${m.color};"></i>
                <span class="material-name">${m.nombre}</span>
                <span class="material-pts">${m.pts} pts/kg</span>
            </label>
        </div>
    `).join('');

    // Renderiza la lista informativa lateral de puntos por material
    const infoList = document.getElementById('materialInfoList');
    if (infoList) {
        infoList.innerHTML = MATERIALES.map(m => `
            <div class="mat-info-item">
                <span class="mat-dot" style="background:${m.color};"></span>
                <span class="text-muted" style="font-size:.83rem;flex:1;">${m.nombre}</span>
                <span class="fw-bold text-success" style="font-size:.83rem;">${m.pts} pts/kg</span>
            </div>
        `).join('');
    }

    // Actualiza la vista previa de puntos según material y cantidad
    // Calcula: puntos = cantidad (kg) * valor del material seleccionado
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
    cantidadEl.addEventListener('input', updatePreview);

    // Carga los centros disponibles en el desplegable
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

    // Muestra alerta de retroalimentación
    function showAlert(msg, type = 'danger') {
        alertBox.className = `form-alert alert alert-${type} animate__animated animate__fadeIn`;
        alertBox.innerHTML = `<i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}-fill me-2"></i>${msg}`;
    }

    // Envío asíncrono del registro de reciclaje
    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        const tipo     = form.querySelector('[name="tipo_material"]:checked');
        const cantidad = parseFloat(cantidadEl.value);
        let valid      = true;

        if (!tipo) {
            materialErr.classList.remove('d-none');
            valid = false;
        } else {
            materialErr.classList.add('d-none');
        }

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
                // Actualizar el contador de puntos en el encabezado
                const badge = document.querySelector('.badge-points .fw-bold');
                if (badge && json.puntos_totales !== undefined) {
                    badge.textContent = json.puntos_totales + ' pts';
                }
                showAlert(`¡Registro exitoso! Has ganado <strong>${json.puntos_ganados} puntos</strong>. Redirigiendo…`, 'success');
                setTimeout(() => { window.location.href = 'index.php?action=mis_registros'; }, 1500);
            } else {
                showAlert(json.message || 'No se pudo registrar el reciclaje.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="bi bi-check-lg"></i>Registrar Puntos';
            }
        } catch {
            showAlert('Error de red. Comprueba tu conexión e inténtalo de nuevo.');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="bi bi-check-lg"></i>Registrar Puntos';
        }
    });
});
</script>

<?php include __DIR__ . "/partials/footer.php"; ?>
