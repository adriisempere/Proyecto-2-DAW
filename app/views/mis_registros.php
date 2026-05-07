<?php
/**
 * Vista de Mis Registros — GreenPoints
 */
if (!isset($_SESSION["usuario_id"])) {
    header("Location: index.php?action=login");
    exit();
}
$pageTitle = "Mis Registros | GreenPoints";
include __DIR__ . "/partials/header.php";
?>

<style>
/* ── Hero de la página ──────────────────────────────────────── */
.registros-hero {
    background: linear-gradient(135deg, #064e3b 0%, #065f46 40%, #0d9488 100%);
    padding: 3rem 0 5rem;
    position: relative;
    overflow: hidden;
}
.registros-hero::before {
    content: '';
    position: absolute; inset: 0;
    background: radial-gradient(ellipse at 70% 50%, rgba(52,211,153,.15) 0%, transparent 55%);
    pointer-events: none;
}
.registros-hero-wave {
    position: absolute; bottom: -1px; left: 0; right: 0; line-height: 0;
}
.registros-hero-wave svg { display: block; width: 100%; }

/* ── Tarjetas de resumen estadístico ────────────────────────── */
.resumen-card {
    background: rgba(255,255,255,.92);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(255,255,255,.7);
    border-radius: 20px;
    padding: 1.5rem;
    text-align: center;
    box-shadow: 0 4px 20px rgba(6,78,59,.07);
    transition: all .3s ease;
    position: relative;
    overflow: hidden;
}
.resumen-card::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0;
    height: 3px;
    background: linear-gradient(90deg, #22c55e, #0d9488);
}
.resumen-card:hover { transform: translateY(-6px); box-shadow: 0 16px 40px rgba(6,78,59,.14); }

.resumen-icon {
    width: 52px; height: 52px;
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem;
    margin: 0 auto .85rem;
}

.resumen-value {
    font-size: 1.7rem;
    font-weight: 800;
    background: linear-gradient(135deg, #16a34a, #0d9488);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    line-height: 1.1;
    margin-bottom: .25rem;
}

/* ── Tarjetas de registro individual ──────────────────────────── */
.registro-card {
    background: rgba(255,255,255,.92);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,.7);
    border-radius: 18px;
    box-shadow: 0 2px 12px rgba(6,78,59,.06);
    transition: all .3s ease;
    overflow: hidden;
    position: relative;
}
.registro-card::before {
    content: '';
    position: absolute; left: 0; top: 0; bottom: 0;
    width: 4px;
    border-radius: 4px 0 0 4px;
}
.registro-card:hover {
    transform: translateX(4px);
    box-shadow: 0 8px 28px rgba(6,78,59,.12);
}

/* Codificación de colores por tipo de material */
.mat-plastico  { --mat-color: #3b82f6; --mat-bg: rgba(59,130,246,.1);  --mat-border: rgba(59,130,246,.25); }
.mat-papel     { --mat-color: #d97706; --mat-bg: rgba(217,119,6,.1);   --mat-border: rgba(217,119,6,.25); }
.mat-vidrio    { --mat-color: #0891b2; --mat-bg: rgba(8,145,178,.1);   --mat-border: rgba(8,145,178,.25); }
.mat-metal     { --mat-color: #6b7280; --mat-bg: rgba(107,114,128,.1); --mat-border: rgba(107,114,128,.25); }
.mat-organico  { --mat-color: #16a34a; --mat-bg: rgba(22,163,74,.1);   --mat-border: rgba(22,163,74,.25); }

.registro-card::before { background: var(--mat-color, #16a34a); }

.mat-icon-box {
    width: 48px; height: 48px;
    border-radius: 14px;
    background: var(--mat-bg, rgba(22,163,74,.1));
    border: 1px solid var(--mat-border, rgba(22,163,74,.25));
    display: flex; align-items: center; justify-content: center;
    font-size: 1.25rem;
    color: var(--mat-color, #16a34a);
    flex-shrink: 0;
    transition: all .3s ease;
}
.registro-card:hover .mat-icon-box {
    background: var(--mat-color);
    color: #fff;
    border-color: transparent;
}

.pts-badge-reg {
    display: inline-flex;
    align-items: center;
    gap: .3rem;
    padding: .3rem .75rem;
    border-radius: 50px;
    font-size: .78rem;
    font-weight: 700;
    background: linear-gradient(135deg, #22c55e, #0d9488);
    color: #fff;
    box-shadow: 0 3px 10px rgba(34,197,94,.3);
}

.btn-delete-reg {
    width: 36px; height: 36px;
    border-radius: 10px;
    background: rgba(239,68,68,.08);
    border: 1px solid rgba(239,68,68,.2);
    color: #ef4444;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    transition: all .25s ease;
    flex-shrink: 0;
    font-size: .9rem;
}
.btn-delete-reg:hover {
    background: #ef4444;
    color: #fff;
    border-color: transparent;
    box-shadow: 0 4px 12px rgba(239,68,68,.35);
    transform: scale(1.08);
}

/* ── Estado vacío ───────────────────────────────────────────── */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: rgba(255,255,255,.7);
    border-radius: 24px;
    border: 1px dashed rgba(34,197,94,.3);
}

/* ── Diseño adaptable (responsive) ──────────────────────────── */
@media (max-width: 575.98px) {
    .registros-hero { padding: 2rem 0 4rem; }
    .resumen-card   { padding: 1.1rem; }
    .resumen-value  { font-size: 1.4rem; }
}
</style>

<!-- ── Hero de la página ─────────────────────────────────────────────── -->
<section class="registros-hero">
    <div class="container" style="position:relative;z-index:2;">
        <div class="d-flex align-items-start justify-content-between flex-wrap gap-3">
            <div>
                <span style="background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.25);border-radius:50px;padding:.2rem .8rem;font-size:.75rem;font-weight:600;color:rgba(255,255,255,.88);letter-spacing:.06em;display:inline-block;margin-bottom:.75rem;">
                    <i class="bi bi-clock-history me-1"></i>MI HISTORIAL
                </span>
                <h1 class="text-white fw-800 mb-1" style="font-size:clamp(1.7rem,4vw,2.8rem);font-weight:800;">
                    Mis Registros
                </h1>
                <p style="color:rgba(255,255,255,.7);margin:0;font-size:.92rem;">
                    Historial completo de tu actividad de reciclaje
                </p>
            </div>
            <div class="d-flex align-items-center mt-2 mt-lg-1">
                <a href="index.php?action=registro_create"
                   style="background:rgba(255,255,255,.18);border:1.5px solid rgba(255,255,255,.4);color:#fff;border-radius:50px;padding:.55rem 1.2rem;font-weight:600;font-size:.9rem;text-decoration:none;display:flex;align-items:center;gap:.4rem;transition:all .25s ease;"
                   onmouseover="this.style.background='rgba(255,255,255,.28)'"
                   onmouseout="this.style.background='rgba(255,255,255,.18)'">
                    <i class="bi bi-plus-circle-fill"></i>Nuevo Registro
                </a>
            </div>
        </div>
    </div>
    <div class="registros-hero-wave">
        <svg viewBox="0 0 1440 60" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
            <path d="M0,20 C360,60 1080,0 1440,40 L1440,60 L0,60 Z" fill="#f0fdf4"/>
        </svg>
    </div>
</section>

<!-- ── Contenido principal ──────────────────────────────────────────── -->
<div class="container py-5">

    <!-- Tarjetas de resumen estadístico -->
    <div class="row g-3 mb-4" id="resumenCards">
        <div class="col-sm-4">
            <div class="resumen-card">
                <div class="resumen-icon" style="background:rgba(34,197,94,.1);">
                    <i class="bi bi-collection-fill text-success"></i>
                </div>
                <div class="resumen-value" id="resumenTotal">—</div>
                <div class="text-muted small">Registros totales</div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="resumen-card">
                <div class="resumen-icon" style="background:rgba(14,165,233,.1);">
                    <i class="bi bi-speedometer2" style="color:#0ea5e9;font-size:1.4rem;"></i>
                </div>
                <div class="resumen-value" id="resumenKg">—</div>
                <div class="text-muted small">Kg reciclados</div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="resumen-card">
                <div class="resumen-icon" style="background:rgba(245,158,11,.1);">
                    <i class="bi bi-star-fill" style="color:#f59e0b;font-size:1.3rem;"></i>
                </div>
                <div class="resumen-value" id="resumenPuntos">—</div>
                <div class="text-muted small">Puntos acumulados</div>
            </div>
        </div>
    </div>

    <!-- Alerta de retroalimentación -->
    <div id="feedbackAlert" class="alert d-none mb-3" role="alert"></div>

    <!-- Contenedor de tarjetas de registros -->
    <div id="misRegistros"></div>

</div>

<!-- ── Modal de confirmación de eliminación ──────────────────── -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0" style="border-radius:20px;overflow:hidden;box-shadow:0 24px 64px rgba(0,0,0,.18);">
            <div class="modal-header border-0 text-white"
                 style="background:linear-gradient(135deg,#dc2626,#ef4444);padding:1.5rem 1.75rem;">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-trash me-2"></i>Eliminar Registro
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 text-muted" style="background:#fff9f9;">
                <div class="text-center mb-3">
                    <div style="font-size:2.5rem;">⚠️</div>
                </div>
                ¿Seguro que quieres eliminar este registro? Se descontarán los puntos ganados
                y la acción <strong>no se puede deshacer</strong>.
            </div>
            <div class="modal-footer border-0" style="background:#fff9f9;padding:1rem 1.75rem 1.5rem;">
                <button type="button" class="btn btn-light rounded-pill px-4 fw-600"
                        data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger rounded-pill px-4 fw-600" id="confirmDeleteBtn">
                    <i class="bi bi-trash me-1"></i>Sí, eliminar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const container     = document.getElementById('misRegistros');
    const feedbackAlert = document.getElementById('feedbackAlert');
    const deleteModal   = new bootstrap.Modal(document.getElementById('deleteModal'));
    const confirmBtn    = document.getElementById('confirmDeleteBtn');

    let pendingDeleteId   = null;
    let pendingDeleteCard = null;
    const csrfToken = document.querySelector('[name="csrf_token"]')?.value ?? '';

    // Escapa HTML para prevenir inyección XSS en datos dinámicos
    function esc(str) {
        const d = document.createElement('div');
        d.textContent = str ?? '';
        return d.innerHTML;
    }

    // Mapeo de nombres e iconos por tipo de material
    const materialLabel = { plastico: 'Plástico', papel: 'Papel', vidrio: 'Vidrio', metal: 'Metal', organico: 'Orgánico' };
    const materialIcon  = { plastico: 'bi-bag-fill', papel: 'bi-file-earmark-fill', vidrio: 'bi-cup-fill', metal: 'bi-gear-fill', organico: 'bi-tree-fill' };

    function showFeedback(msg, type = 'success') {
        feedbackAlert.className = `alert alert-${type} animate__animated animate__fadeIn`;
        feedbackAlert.innerHTML = `<i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}-fill me-2"></i>${msg}`;
        setTimeout(() => feedbackAlert.classList.add('d-none'), 4000);
    }

    function formatDate(str) {
        const d = new Date(str);
        return isNaN(d) ? str : d.toLocaleDateString('es-ES', {
            day: '2-digit', month: 'short', year: 'numeric',
            hour: '2-digit', minute: '2-digit'
        });
    }

    // Calcula y actualiza los totales de registros, kg y puntos acumulados
    function updateResumen(data) {
        const totalKg  = data.reduce((s, r) => s + parseFloat(r.cantidad || 0), 0);
        const totalPts = data.reduce((s, r) => s + parseInt(r.puntos_ganados || 0), 0);
        document.getElementById('resumenTotal').textContent  = data.length;
        document.getElementById('resumenKg').textContent     = totalKg.toFixed(2) + ' kg';
        document.getElementById('resumenPuntos').textContent = totalPts + ' pts';
    }

    function renderCards(data) {
        if (data.length === 0) {
            container.innerHTML = `
                <div class="empty-state">
                    <div style="font-size:3.5rem;margin-bottom:1rem;">♻️</div>
                    <h5 class="fw-bold text-secondary mb-2">Aún no tienes registros</h5>
                    <p class="text-muted mb-3">Empieza a reciclar y acumula puntos.</p>
                    <a href="index.php?action=registro_create"
                       class="btn btn-success rounded-pill px-4 fw-semibold">
                        <i class="bi bi-plus-circle me-2"></i>Hacer mi primer registro
                    </a>
                </div>`;
            return;
        }

        container.innerHTML = data.map((r, idx) => {
            const matClass = `mat-${esc(r.tipo_material)}`;
            const icon     = materialIcon[r.tipo_material]  || 'bi-recycle';
            const label    = materialLabel[r.tipo_material] || r.tipo_material;

            return `
                <div class="registro-card ${matClass} mb-3 animate-fade-in-up" id="card-${esc(String(r.id))}"
                     style="animation-delay:${idx * 0.05}s">
                    <div class="p-4 d-flex align-items-center gap-3">
                        <div class="mat-icon-box">
                            <i class="bi ${icon}"></i>
                        </div>
                        <div class="flex-grow-1 min-w-0">
                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-1 mb-1">
                                <h6 class="fw-bold mb-0">${esc(label)}</h6>
                                <span class="pts-badge-reg">
                                    <i class="bi bi-star-fill" style="font-size:.7rem;"></i>
                                    ${esc(String(r.puntos_ganados))} pts
                                </span>
                            </div>
                            <p class="text-muted small mb-1">
                                <i class="bi bi-speedometer2 me-1"></i><strong>${esc(String(r.cantidad))} kg</strong>
                                ${r.centro_nombre
                                    ? `&nbsp;·&nbsp;<i class="bi bi-geo-alt me-1"></i>${esc(r.centro_nombre)}`
                                    : ''}
                            </p>
                            <p class="text-muted small mb-0">
                                <i class="bi bi-calendar3 me-1"></i>${esc(formatDate(r.fecha))}
                            </p>
                        </div>
                        <button class="btn-delete-reg delete-btn" data-id="${esc(String(r.id))}" title="Eliminar">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>`;
        }).join('');
    }

    let registrosData = [];

    // Esqueleto de carga mientras se obtienen los datos
    container.innerHTML = `
        <div class="d-flex flex-column gap-3">
            ${[1,2,3].map(() => `
                <div style="background:rgba(255,255,255,.85);border-radius:18px;padding:1.25rem;border:1px solid rgba(255,255,255,.6);">
                    <div class="d-flex gap-3 placeholder-glow">
                        <span class="placeholder rounded-3" style="width:48px;height:48px;flex-shrink:0;"></span>
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

    // Manejador de clic en botón de eliminar mediante delegación de eventos
    container.addEventListener('click', function (e) {
        const btn = e.target.closest('.delete-btn');
        if (!btn) return;
        pendingDeleteId   = btn.dataset.id;
        pendingDeleteCard = document.getElementById(`card-${pendingDeleteId}`);
        deleteModal.show();
    });

    // Confirmación y ejecución de eliminación vía API
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
                pendingDeleteCard?.classList.add('animate__animated','animate__fadeOutLeft');
                setTimeout(() => {
                    pendingDeleteCard?.remove();
                    registrosData = registrosData.filter(r => String(r.id) !== String(pendingDeleteId));
                    updateResumen(registrosData);
                    if (registrosData.length === 0) renderCards([]);
                    const badge = document.querySelector('.badge-points .fw-bold');
                    if (badge && json.puntos_totales !== undefined) {
                        badge.textContent = json.puntos_totales + ' pts';
                    }
                    showFeedback(`Registro eliminado. Se descontaron ${json.puntos_descontados} pts.`);
                }, 500);
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
require_once __DIR__ . "/../helpers/CsrfHelper.php";
echo '<input type="hidden" name="csrf_token" value="' .
    htmlspecialchars(CsrfHelper::generateToken()) .
    '">';
include __DIR__ . "/partials/footer.php";

?>
