<?php
/**
 * Vista de Mis Registros — GreenPoints
 * ---------------------------------------------------------------
 * Muestra el historial de reciclaje del usuario autenticado.
 * Si no hay sesión activa, redirige al login.
 * Permite eliminar registros individuales llamando a:
 *   api/registro.php?action=delete
 * Los puntos del marcador del header se actualizan en tiempo real
 * tras cada eliminación sin recargar la página.
 * ---------------------------------------------------------------
 */

// Redirigir si no está autenticado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php?action=login');
    exit;
}

$pageTitle = 'Mis Registros | GreenPoints';
include __DIR__ . '/partials/header.php';
?>

<div class="container py-5">

    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h1 class="fw-bold display-6 mb-1">
                <i class="bi bi-clock-history text-success me-2"></i>Mis Registros
            </h1>
            <p class="text-muted mb-0">Historial de tu actividad de reciclaje</p>
        </div>
        <a href="index.php?action=registro_create" class="btn btn-success rounded-pill px-4">
            <i class="bi bi-plus-circle me-2"></i>Nuevo Registro
        </a>
    </div>

    <!-- Resumen rápido -->
    <div class="row g-3 mb-4" id="resumenCards">
        <div class="col-sm-4">
            <div class="card border-0 shadow-sm text-center p-3">
                <div class="text-success fw-bold fs-4" id="resumenTotal">—</div>
                <div class="text-muted small">Registros totales</div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card border-0 shadow-sm text-center p-3">
                <div class="text-success fw-bold fs-4" id="resumenKg">—</div>
                <div class="text-muted small">Kg reciclados</div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card border-0 shadow-sm text-center p-3">
                <div class="text-success fw-bold fs-4" id="resumenPuntos">—</div>
                <div class="text-muted small">Puntos acumulados</div>
            </div>
        </div>
    </div>

    <!-- Alerta de feedback -->
    <div id="feedbackAlert" class="alert d-none mb-3" role="alert"></div>

    <!-- Contenedor de tarjetas -->
    <div id="misRegistros"></div>

</div>

<!-- Modal de confirmación de borrado -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="bi bi-trash text-danger me-2"></i>Eliminar registro
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-muted">
                ¿Seguro que quieres eliminar este registro? Se descontarán los puntos ganados y la acción no se puede deshacer.
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger rounded-pill" id="confirmDeleteBtn">
                    <i class="bi bi-trash me-1"></i>Sí, eliminar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const container    = document.getElementById('misRegistros');
    const feedbackAlert = document.getElementById('feedbackAlert');
    const deleteModal  = new bootstrap.Modal(document.getElementById('deleteModal'));
    const confirmBtn   = document.getElementById('confirmDeleteBtn');

    let pendingDeleteId   = null;
    let pendingDeleteCard = null;
    const csrfToken = document.querySelector('[name="csrf_token"]')?.value ?? '';

    // ── Escapar HTML para prevenir XSS ───────────────────────────
    function esc(str) {
        const d = document.createElement('div');
        d.textContent = str ?? '';
        return d.innerHTML;
    }

    // ── Nombre de material legible ────────────────────────────────
    const materialLabel = {
        plastico: 'Plástico', papel: 'Papel', vidrio: 'Vidrio',
        metal: 'Metal', organico: 'Orgánico'
    };

    // ── Icono por material ────────────────────────────────────────
    const materialIcon = {
        plastico: 'bi-bag', papel: 'bi-file-earmark', vidrio: 'bi-cup',
        metal: 'bi-gear', organico: 'bi-tree'
    };

    // ── Mostrar alerta de feedback ────────────────────────────────
    function showFeedback(msg, type = 'success') {
        feedbackAlert.className = `alert alert-${type} animate__animated animate__fadeIn`;
        feedbackAlert.innerHTML = `<i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}-fill me-2"></i>${msg}`;
        setTimeout(() => feedbackAlert.classList.add('d-none'), 4000);
    }

    // ── Formatear fecha ───────────────────────────────────────────
    function formatDate(str) {
        const d = new Date(str);
        return isNaN(d) ? str : d.toLocaleDateString('es-ES', {
            day: '2-digit', month: 'short', year: 'numeric',
            hour: '2-digit', minute: '2-digit'
        });
    }

    // ── Actualizar resumen rápido ─────────────────────────────────
    function updateResumen(data) {
        const totalKg    = data.reduce((s, r) => s + parseFloat(r.cantidad || 0), 0);
        const totalPts   = data.reduce((s, r) => s + parseInt(r.puntos_ganados || 0), 0);
        document.getElementById('resumenTotal').textContent  = data.length;
        document.getElementById('resumenKg').textContent     = totalKg.toFixed(2) + ' kg';
        document.getElementById('resumenPuntos').textContent = totalPts + ' pts';
    }

    // ── Renderizar tarjetas ───────────────────────────────────────
    function renderCards(data) {
        if (data.length === 0) {
            container.innerHTML = `
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-inbox fs-1 d-block mb-3 opacity-50"></i>
                    <p class="mb-3">Aún no tienes registros de reciclaje.</p>
                    <a href="index.php?action=registro_create" class="btn btn-success rounded-pill px-4">
                        <i class="bi bi-plus-circle me-2"></i>Hacer mi primer registro
                    </a>
                </div>`;
            return;
        }

        container.innerHTML = data.map(r => `
            <div class="card border-0 shadow-sm mb-3 registro-card" id="card-${esc(String(r.id))}">
                <div class="card-body d-flex align-items-start gap-3">
                    <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:46px;height:46px;font-size:1.3rem">
                        <i class="bi ${materialIcon[r.tipo_material] || 'bi-recycle'}"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-1">
                            <h6 class="fw-bold mb-1">${esc(materialLabel[r.tipo_material] || r.tipo_material)}</h6>
                            <span class="badge bg-success">${esc(String(r.puntos_ganados))} pts</span>
                        </div>
                        <p class="text-muted small mb-1">
                            <i class="bi bi-speedometer2 me-1"></i>${esc(String(r.cantidad))} kg
                            ${r.centro_nombre
                                ? `· <i class="bi bi-geo-alt me-1"></i>${esc(r.centro_nombre)}`
                                : ''}
                        </p>
                        <p class="text-muted small mb-0">
                            <i class="bi bi-calendar3 me-1"></i>${esc(formatDate(r.fecha))}
                        </p>
                    </div>
                    <button class="btn btn-sm btn-outline-danger rounded-pill flex-shrink-0 delete-btn"
                            data-id="${esc(String(r.id))}" title="Eliminar registro">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        `).join('');
    }

    // ── Cargar registros ──────────────────────────────────────────
    let registrosData = [];

    container.innerHTML = `
        <div class="placeholder-glow">
            ${[1,2,3].map(() => `
                <div class="card border-0 shadow-sm mb-3 p-3">
                    <div class="d-flex gap-3">
                        <span class="placeholder rounded-circle" style="width:46px;height:46px;flex-shrink:0"></span>
                        <div class="flex-grow-1">
                            <span class="placeholder col-3 rounded mb-2 d-block"></span>
                            <span class="placeholder col-5 rounded d-block"></span>
                        </div>
                    </div>
                </div>`).join('')}
        </div>`;

    fetch('api/registro.php?action=list')
        .then(r => r.json())
        .then(json => {
            if (!json.success) throw new Error(json.message);
            registrosData = json.data;
            updateResumen(registrosData);
            renderCards(registrosData);
        })
        .catch(() => {
            container.innerHTML = '<p class="text-danger"><i class="bi bi-exclamation-triangle me-2"></i>Error al cargar los registros.</p>';
        });

    // ── Clic en botón eliminar ────────────────────────────────────
    container.addEventListener('click', function (e) {
        const btn = e.target.closest('.delete-btn');
        if (!btn) return;
        pendingDeleteId   = btn.dataset.id;
        pendingDeleteCard = document.getElementById(`card-${pendingDeleteId}`);
        deleteModal.show();
    });

    // ── Confirmar eliminación ─────────────────────────────────────
    confirmBtn.addEventListener('click', async function () {
        if (!pendingDeleteId) return;

        confirmBtn.disabled = true;
        confirmBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Eliminando…';

        try {
            const res  = await fetch('api/registro.php?action=delete', {
                method:  'POST',
                headers: { 'Content-Type': 'application/json' },
                body:    JSON.stringify({ csrf_token: csrfToken, id: pendingDeleteId }),
            });
            const json = await res.json();

            if (json.success) {
                deleteModal.hide();
                pendingDeleteCard?.remove();

                // Actualizar datos locales y resumen
                registrosData = registrosData.filter(r => String(r.id) !== String(pendingDeleteId));
                updateResumen(registrosData);

                // Si era el último, mostrar estado vacío
                if (registrosData.length === 0) renderCards([]);

                // Actualizar badge de puntos en el header si existe
                const badge = document.querySelector('.badge-points .fw-bold');
                if (badge && json.puntos_totales !== undefined) {
                    badge.textContent = json.puntos_totales + ' pts';
                }

                showFeedback(`Registro eliminado. Se descontaron ${json.puntos_descontados} pts.`);
            } else {
                showFeedback(json.message || 'No se pudo eliminar el registro.', 'danger');
            }
        } catch {
            showFeedback('Error de red. Inténtalo más tarde.', 'danger');
        } finally {
            confirmBtn.disabled = false;
            confirmBtn.innerHTML = '<i class="bi bi-trash me-1"></i>Sí, eliminar';
            pendingDeleteId = null;
        }
    });
});
</script>

<?php
// El token CSRF se necesita en JS para el endpoint delete
require_once __DIR__ . '/../helpers/CsrfHelper.php';
echo '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(CsrfHelper::generateToken()) . '">';
include __DIR__ . '/partials/footer.php';
?>