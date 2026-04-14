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

    <div class="d-flex align-items-center justify-content-between mb-5 flex-wrap gap-3 reveal-on-scroll">
        <div>
            <h1 class="fw-bold display-5 mb-1">
                <i class="bi bi-clock-history text-success me-2"></i>Mis Registros
            </h1>
            <p class="text-muted fs-5">Tu historial personal de impacto positivo</p>
        </div>
        <a href="index.php?action=registro_create" class="btn btn-success rounded-pill px-4 py-2 fw-bold shadow-sm hover-lift"
           style="background: linear-gradient(135deg, var(--primary), var(--secondary)); border: none;">
            <i class="bi bi-plus-circle me-2"></i>Nuevo Registro
        </a>
    </div>

    <!-- Resumen rápido Premium -->
    <div class="row g-4 mb-5 reveal-on-scroll" id="resumenCards">
        <div class="col-md-4">
            <div class="premium-card p-4 text-center h-100">
                <div class="text-success fw-bold display-6 mb-1" id="resumenTotal">—</div>
                <div class="text-muted text-uppercase small fw-bold tracking-wider">Registros Totales</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="premium-card p-4 text-center h-100">
                <div class="text-success fw-bold display-6 mb-1" id="resumenKg">—</div>
                <div class="text-muted text-uppercase small fw-bold tracking-wider">Kg Reciclados</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="premium-card p-4 text-center h-100">
                <div class="text-success fw-bold display-6 mb-1" id="resumenPuntos">—</div>
                <div class="text-muted text-uppercase small fw-bold tracking-wider">Puntos Acumulados</div>
            </div>
        </div>
    </div>

    <!-- Alerta de feedback -->
    <div id="feedbackAlert" class="alert d-none mb-4" role="alert"></div>

    <!-- Contenedor de tarjetas -->
    <div id="misRegistros" class="row g-4"></div>

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
                <div class="col-12 text-center py-5 reveal-on-scroll">
                    <div class="mb-4">
                        <i class="bi bi-calendar-x opacity-25" style="font-size: 5rem; color: var(--primary);"></i>
                    </div>
                    <h3 class="fw-bold mb-3">Tu historial está vacío</h3>
                    <p class="text-muted mb-4 fs-5">Aún no has registrado ninguna actividad de reciclaje.</p>
                    <a href="index.php?action=registro_create" class="btn btn-success rounded-pill px-5 py-3 fw-bold shadow-lg"
                       style="background: linear-gradient(135deg, var(--primary), var(--secondary)); border: none;">
                        <i class="bi bi-plus-circle me-2"></i>Hacer mi primer registro
                    </a>
                </div>`;
            return;
        }

        container.innerHTML = data.map(r => `
            <div class="col-md-6 reveal-on-scroll" id="card-${esc(String(r.id))}">
                <div class="premium-card p-4 h-100 d-flex align-items-start gap-3">
                    <div class="bg-success bg-opacity-10 text-success rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:52px;height:52px;font-size:1.5rem; border: 1px solid var(--border-color);">
                        <i class="bi ${materialIcon[r.tipo_material] || 'bi-recycle'}"></i>
                    </div>
                    <div class="flex-grow-1 overflow-hidden">
                        <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                            <h6 class="fw-bold mb-0 text-truncate">${esc(materialLabel[r.tipo_material] || r.tipo_material)}</h6>
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1 small fw-bold" 
                                  style="border: 1px solid rgba(40,167,69,0.2)">
                                +${esc(String(r.puntos_ganados))} pts
                            </span>
                        </div>
                        <div class="text-muted small mb-3">
                            <div class="mb-1"><i class="bi bi-speedometer2 me-1"></i>${esc(String(r.cantidad))} kg</div>
                            ${r.centro_nombre
                                ? `<div class="text-truncate"><i class="bi bi-geo-alt me-1"></i>${esc(r.centro_nombre)}</div>`
                                : ''}
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top border-opacity-10">
                            <small class="text-muted italic"><i class="bi bi-calendar3 me-1"></i>${esc(formatDate(r.fecha))}</small>
                            <button class="btn btn-sm btn-link text-danger p-0 text-decoration-none hover-lift delete-btn"
                                    data-id="${esc(String(r.id))}" title="Eliminar registro">
                                <i class="bi bi-trash3"></i> Eliminar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');
        
        if(window.observer) {
            document.querySelectorAll('.reveal-on-scroll').forEach(el => window.observer.observe(el));
        }
    }

    // ── Cargar registros ──────────────────────────────────────────
    let registrosData = [];

    container.innerHTML = `
        ${[1,2,3,4].map(() => `
            <div class="col-md-6">
                <div class="premium-card p-4 h-100 placeholder-glow">
                    <div class="d-flex gap-3">
                        <span class="placeholder rounded-3" style="width:52px;height:52px;flex-shrink:0"></span>
                        <div class="flex-grow-1">
                            <span class="placeholder col-4 rounded mb-2 d-block"></span>
                            <span class="placeholder col-6 rounded d-block"></span>
                        </div>
                    </div>
                </div>
            </div>`).join('')}
    `;

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