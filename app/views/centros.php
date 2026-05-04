<?php
/**
 * Vista de Centros de Reciclaje — GreenPoints
 * ---------------------------------------------------------------
 * Lista todos los centros de reciclaje disponibles.
 * Los datos se cargan dinámicamente desde:
 *   api/centros.php?action=list
 *
 * Si el usuario tiene rol admin, se muestra además un formulario
 * para crear nuevos centros (api/centros.php?action=store).
 * ---------------------------------------------------------------
 */

$pageTitle = 'Centros de Reciclaje | GreenPoints';
include __DIR__ . '/partials/header.php';
?>

<div class="container py-5">

    <!-- Cabecera -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h1 class="fw-bold display-6 mb-1">
                <i class="bi bi-geo-alt-fill text-success me-2"></i>Centros de Reciclaje
            </h1>
            <p class="text-muted mb-0">Encuentra dónde depositar tus residuos</p>
        </div>

        <?php if (($_SESSION['usuario_rol'] ?? '') === 'admin'): ?>
            <button class="btn btn-success rounded-pill px-4"
                    data-bs-toggle="modal" data-bs-target="#addCentroModal">
                <i class="bi bi-plus-circle me-2"></i>Nuevo Centro
            </button>
        <?php endif; ?>
    </div>

    <!-- Buscador rápido -->
    <div class="mb-4">
        <div class="input-group">
            <span class="input-group-text bg-white border-end-0">
                <i class="bi bi-search text-muted"></i>
            </span>
            <input type="text" class="form-control border-start-0 ps-0"
                   id="searchInput"
                   placeholder="Buscar por nombre, dirección o tipo de residuo…">
        </div>
    </div>

    <!-- Alerta de feedback (acciones de admin) -->
    <div id="feedbackAlert" class="alert d-none mb-3" role="alert"></div>

    <!-- Cuadrícula de centros -->
    <div id="centrosList" class="row g-3"></div>

</div>

<?php if (($_SESSION['usuario_rol'] ?? '') === 'admin'): ?>
<!-- Modal para crear centro (solo admin) ────────────────────── -->
<div class="modal fade" id="addCentroModal" tabindex="-1"
     aria-labelledby="addCentroModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="addCentroModalLabel">
                    <i class="bi bi-geo-alt text-success me-2"></i>Nuevo Centro de Reciclaje
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="modalAlert" class="alert d-none mb-3" role="alert"></div>
                <form id="addCentroForm" novalidate>
                    <?php
                    require_once __DIR__ . '/../helpers/CsrfHelper.php';
                    echo CsrfHelper::getTokenField();
                    ?>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nombre</label>
                        <input type="text" class="form-control" name="nombre"
                               required placeholder="Ej: Centro EcoVida">
                        <div class="invalid-feedback">Campo obligatorio.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Dirección</label>
                        <input type="text" class="form-control" name="direccion"
                               required placeholder="Ej: Calle Mayor 12">
                        <div class="invalid-feedback">Campo obligatorio.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tipos de residuos</label>
                        <input type="text" class="form-control" name="tipos_residuos"
                               required placeholder="Ej: Plástico, Papel, Vidrio">
                        <div class="invalid-feedback">Campo obligatorio.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Horario</label>
                        <input type="text" class="form-control" name="horario"
                               required placeholder="Ej: L-V 09:00-18:00">
                        <div class="invalid-feedback">Campo obligatorio.</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary rounded-pill"
                        data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success rounded-pill px-4"
                        id="saveCentroBtn">
                    <i class="bi bi-check-lg me-1"></i>Guardar
                </button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const list          = document.getElementById('centrosList');
    const searchInput   = document.getElementById('searchInput');
    const feedbackAlert = document.getElementById('feedbackAlert');
    const isAdmin       = <?= (($_SESSION['usuario_rol'] ?? '') === 'admin') ? 'true' : 'false' ?>;

    let allCentros = [];

    // ── Escapar texto para prevenir XSS al usar innerHTML ────────
    function esc(str) {
        const d = document.createElement('div');
        d.textContent = str ?? '';
        return d.innerHTML;
    }

    // ── Alerta de feedback ────────────────────────────────────────
    function showFeedback(msg, type = 'success') {
        feedbackAlert.className = `alert alert-${type} animate__animated animate__fadeIn`;
        feedbackAlert.innerHTML =
            `<i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}-fill me-2"></i>${msg}`;
        setTimeout(() => feedbackAlert.classList.add('d-none'), 4000);
    }

    // ── Renderizar centros ────────────────────────────────────────
    function renderCentros(data) {
        if (data.length === 0) {
            list.innerHTML = '<div class="col-12"><p class="text-muted text-center py-4">No se encontraron centros.</p></div>';
            return;
        }

        list.innerHTML = data.map(c => {
            const matClass = {
                plastico:'material-plastico', plástico:'material-plastico',
                papel:'material-papel', cartón:'material-papel', carton:'material-papel',
                vidrio:'material-vidrio', metal:'material-metal',
                orgánico:'material-organico', organico:'material-organico',
                electrónico:'material-electronico', electronico:'material-electronico'
            };
            const badges = esc(c.tipos_residuos).split(',').map(t => {
                const key = t.trim().toLowerCase();
                const cls = Object.keys(matClass).find(k => key.includes(k));
                return `<span class="material-tag ${cls ? matClass[cls] : ''}">${t.trim()}</span>`;
            }).join(' ');

            return `
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm hover-lift centro-card">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-start gap-3 mb-3">
                                <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                     style="width:44px;height:44px;font-size:1.2rem">
                                    <i class="bi bi-recycle"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0">${esc(c.nombre)}</h6>
                                    <small class="text-muted">${esc(c.direccion)}</small>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap gap-1 mb-2">${badges}</div>
                            <small class="text-muted">
                                <i class="bi bi-clock me-1"></i>${esc(c.horario)}
                            </small>
                        </div>
                    </div>
                </div>`;
        }).join('');
    }

    // ── Buscador en tiempo real ───────────────────────────────────
    searchInput.addEventListener('input', function () {
        const q = this.value.toLowerCase().trim();
        renderCentros(
            allCentros.filter(c =>
                c.nombre.toLowerCase().includes(q) ||
                c.direccion.toLowerCase().includes(q) ||
                c.tipos_residuos.toLowerCase().includes(q)
            )
        );
    });

    // ── Esqueleto de carga ────────────────────────────────────────
    list.innerHTML = `<div class="col-12 placeholder-glow">
        ${[1,2,3].map(() => `
            <div class="card border-0 shadow-sm mb-3 p-4">
                <span class="placeholder col-4 rounded mb-2 d-block"></span>
                <span class="placeholder col-6 rounded mb-2 d-block"></span>
                <span class="placeholder col-3 rounded d-block"></span>
            </div>`).join('')}
    </div>`;

    // ── Cargar centros ────────────────────────────────────────────
    fetch('api/centros.php?action=list')
        .then(r => r.json())
        .then(json => {
            if (!json.success) throw new Error(json.message);
            allCentros = json.data;
            renderCentros(allCentros);
        })
        .catch(() => {
            list.innerHTML = '<div class="col-12"><p class="text-danger"><i class="bi bi-exclamation-triangle me-2"></i>Error al cargar los centros.</p></div>';
        });

    // ── Crear centro (solo admin) ─────────────────────────────────
    if (isAdmin) {
        const saveBtn    = document.getElementById('saveCentroBtn');
        const form       = document.getElementById('addCentroForm');
        const modalAlert = document.getElementById('modalAlert');

        saveBtn?.addEventListener('click', async function () {
            let valid = true;
            form.querySelectorAll('input[required]').forEach(input => {
                const ok = input.value.trim() !== '';
                input.classList.toggle('is-invalid', !ok);
                if (!ok) valid = false;
            });
            if (!valid) return;

            saveBtn.disabled = true;
            saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Guardando…';

            const body = {
                csrf_token:     form.querySelector('[name="csrf_token"]').value,
                nombre:         form.querySelector('[name="nombre"]').value.trim(),
                direccion:      form.querySelector('[name="direccion"]').value.trim(),
                tipos_residuos: form.querySelector('[name="tipos_residuos"]').value.trim(),
                horario:        form.querySelector('[name="horario"]').value.trim(),
            };

            try {
                const res  = await fetch('api/centros.php?action=store', {
                    method:  'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body:    JSON.stringify(body),
                });
                const json = await res.json();

                if (json.success) {
                    bootstrap.Modal.getInstance(
                        document.getElementById('addCentroModal')
                    ).hide();
                    form.reset();
                    form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                    allCentros.push({ id: json.id, ...body });
                    renderCentros(allCentros);
                    showFeedback('Centro creado correctamente.');
                } else {
                    modalAlert.className   = 'alert alert-danger';
                    modalAlert.textContent = json.message || 'No se pudo crear el centro.';
                }
            } catch {
                modalAlert.className   = 'alert alert-danger';
                modalAlert.textContent = 'Error de red. Inténtalo más tarde.';
            } finally {
                saveBtn.disabled = false;
                saveBtn.innerHTML = '<i class="bi bi-check-lg me-1"></i>Guardar';
            }
        });
    }
});
</script>

<?php include __DIR__ . '/partials/footer.php'; ?>