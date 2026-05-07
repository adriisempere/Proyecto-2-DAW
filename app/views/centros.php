<?php
/**
 * Vista de Centros de Reciclaje — GreenPoints
 * Listado y búsqueda de centros disponibles.
 * Los administradores pueden crear nuevos centros mediante un modal.
 */
$pageTitle = "Centros de Reciclaje | GreenPoints";
include __DIR__ . "/partials/header.php";
?>

<style>
/* ── Hero de la página ──────────────────────────────────────── */
.page-hero {
    background: linear-gradient(135deg, #064e3b 0%, #065f46 40%, #0d9488 100%);
    padding: 3.5rem 0 5rem;
    position: relative;
    overflow: hidden;
}
.page-hero::before {
    content: '';
    position: absolute; inset: 0;
    background:
        radial-gradient(ellipse at 20% 50%, rgba(52,211,153,.18) 0%, transparent 55%),
        radial-gradient(ellipse at 80% 20%, rgba(13,148,136,.18) 0%, transparent 55%);
    pointer-events: none;
}
.page-hero-content { position: relative; z-index: 2; }

/* Divisor de onda decorativo */
.page-hero-wave {
    position: absolute; bottom: -1px; left: 0; right: 0;
    line-height: 0;
}
.page-hero-wave svg { display: block; width: 100%; }

/* ── Barra de búsqueda con efecto vidrio ──────────────────── */
.search-glass-wrap {
    background: rgba(255,255,255,.92);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(255,255,255,.8);
    border-radius: 16px;
    padding: .5rem .6rem .5rem 1.2rem;
    display: flex;
    align-items: center;
    gap: .6rem;
    box-shadow: 0 8px 32px rgba(6,78,59,.1);
    transition: box-shadow .25s ease;
}
.search-glass-wrap:focus-within {
    box-shadow: 0 8px 32px rgba(6,78,59,.18), 0 0 0 3px rgba(5,150,105,.12);
}
.search-glass-icon { color: #059669; font-size: 1.1rem; flex-shrink: 0; }
.search-glass-input {
    flex: 1;
    background: transparent;
    border: none;
    outline: none;
    font-size: .92rem;
    font-family: 'Poppins', sans-serif;
    color: #1f2937;
    min-width: 0;
}
.search-glass-input::placeholder { color: #9ca3af; }

/* ── Tarjetas de centro ──────────────────────────────────────── */
.centro-card {
    background: rgba(255,255,255,.92);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(255,255,255,.7);
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(6,78,59,.07);
    transition: all .35s cubic-bezier(.175,.885,.32,1.275);
    position: relative;
}
.centro-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: linear-gradient(90deg, #22c55e, #0d9488);
    border-radius: 20px 20px 0 0;
}
.centro-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 50px rgba(6,78,59,.16);
    border-color: rgba(34,197,94,.25);
}

.centro-icon-box {
    width: 50px; height: 50px;
    background: linear-gradient(135deg, rgba(34,197,94,.15), rgba(13,148,136,.15));
    border: 1px solid rgba(34,197,94,.2);
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.3rem;
    color: #059669;
    flex-shrink: 0;
    transition: all .3s ease;
}
.centro-card:hover .centro-icon-box {
    background: linear-gradient(135deg, #22c55e, #0d9488);
    color: #fff;
    border-color: transparent;
    box-shadow: 0 6px 18px rgba(34,197,94,.35);
}

.material-badge {
    display: inline-flex;
    align-items: center;
    gap: .25rem;
    padding: .25rem .65rem;
    border-radius: 50px;
    font-size: .72rem;
    font-weight: 600;
    background: rgba(34,197,94,.1);
    color: #059669;
    border: 1px solid rgba(34,197,94,.22);
    transition: all .2s ease;
}
.material-badge:hover {
    background: rgba(34,197,94,.18);
}

.centro-schedule {
    display: flex;
    align-items: center;
    gap: .4rem;
    font-size: .8rem;
    color: #6b7280;
    padding: .5rem .75rem;
    background: rgba(240,253,244,.8);
    border-radius: 8px;
    border: 1px solid rgba(209,250,229,.8);
}

/* ── Estados vacío / carga (esqueleto) ────────────────────── */
.skeleton-card {
    background: rgba(255,255,255,.85);
    border-radius: 20px;
    overflow: hidden;
    padding: 1.5rem;
    border: 1px solid rgba(255,255,255,.6);
}
.skeleton-line {
    background: linear-gradient(90deg, #f0fdf4 25%, #dcfce7 50%, #f0fdf4 75%);
    background-size: 200% 100%;
    animation: shimmer 1.6s infinite;
    border-radius: 6px;
}
@keyframes shimmer { to { background-position: -200% 0; } }

/* ── Botón de administración ───────────────────────────────── */
.btn-add-centro {
    background: linear-gradient(135deg, #22c55e, #0d9488);
    border: none;
    color: #fff;
    border-radius: 50px;
    padding: .6rem 1.4rem;
    font-weight: 700;
    font-size: .9rem;
    box-shadow: 0 4px 16px rgba(34,197,94,.35);
    transition: all .3s ease;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: .4rem;
}
.btn-add-centro:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(34,197,94,.5); }

/* ── Diseño adaptable (responsive) ─────────────────────────── */
@media (max-width: 575.98px) {
    .page-hero { padding: 2.5rem 0 4rem; }
    .search-glass-wrap { border-radius: 12px; }
}
</style>

<!-- ── Hero de la página ─────────────────────────────────────────────── -->
<section class="page-hero">
    <div class="container page-hero-content">
        <div class="row align-items-center gy-4">
            <div class="col-lg-7">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span style="background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.25);border-radius:50px;padding:.25rem .85rem;font-size:.78rem;font-weight:600;color:rgba(255,255,255,.9);letter-spacing:.06em;">
                        <i class="bi bi-geo-alt me-1"></i>PUNTOS DE RECICLAJE
                    </span>
                </div>
                <h1 class="fw-800 text-white mb-2" style="font-size:clamp(1.8rem,4vw,3rem);font-weight:800;">
                    Centros de Reciclaje
                </h1>
                <p class="mb-0" style="color:rgba(255,255,255,.72);font-size:.95rem;max-width:500px;">
                    Encuentra el punto de reciclaje más cercano. Deposita tus residuos
                    y acumula puntos por cada visita.
                </p>
            </div>
            <?php if (($_SESSION["usuario_rol"] ?? "") === "admin"): ?>
            <div class="col-lg-5 text-lg-end">
                <button class="btn-add-centro" data-bs-toggle="modal" data-bs-target="#addCentroModal">
                    <i class="bi bi-plus-circle-fill"></i>Nuevo Centro
                </button>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <!-- Onda decorativa inferior -->
    <div class="page-hero-wave">
        <svg viewBox="0 0 1440 60" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
            <path d="M0,30 C240,60 480,0 720,30 C960,60 1200,0 1440,30 L1440,60 L0,60 Z" fill="#f0fdf4"/>
        </svg>
    </div>
</section>

<!-- ── Contenido principal ──────────────────────────────────────────── -->
<div class="container py-5" style="margin-top:-1px;">

    <!-- Barra de búsqueda en vivo -->
    <div class="mb-4">
        <div class="search-glass-wrap">
            <i class="bi bi-search search-glass-icon"></i>
            <input type="text"
                   class="search-glass-input"
                   id="searchInput"
                   placeholder="Buscar por nombre, dirección o tipo de residuo…"
                   autocomplete="off">
        </div>
    </div>

    <!-- Alerta de retroalimentación -->
    <div id="feedbackAlert" class="alert d-none mb-3" role="alert"></div>

    <!-- Cuadrícula de tarjetas de centros -->
    <div id="centrosList" class="row g-4"></div>

</div>

<?php if (($_SESSION["usuario_rol"] ?? "") === "admin"): ?>
<!-- ── Admin: Modal para crear centro ─────────────────────────── -->
<div class="modal fade" id="addCentroModal" tabindex="-1"
     aria-labelledby="addCentroModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0" style="border-radius:20px;overflow:hidden;box-shadow:0 24px 64px rgba(0,0,0,.2);">
            <div class="modal-header border-0 text-white"
                 style="background:linear-gradient(135deg,#065f46,#0d9488);padding:1.5rem 1.75rem;">
                <h5 class="modal-title fw-bold" id="addCentroModalLabel">
                    <i class="bi bi-geo-alt me-2"></i>Nuevo Centro de Reciclaje
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" style="background:#f0fdf4;">
                <div id="modalAlert" class="alert d-none mb-3" role="alert"></div>
                <form id="addCentroForm" novalidate>
                    <?php
                    require_once __DIR__ . "/../helpers/CsrfHelper.php";
                    echo CsrfHelper::getTokenField();
                    ?>
                    <?php foreach (
                        [
                            [
                                "nombre",
                                "Nombre del centro",
                                "Ej: Centro EcoVida",
                            ],
                            ["direccion", "Dirección", "Ej: Calle Mayor 12"],
                            [
                                "tipos_residuos",
                                "Tipos de residuos",
                                "Ej: Plástico, Papel, Vidrio",
                            ],
                            [
                                "horario",
                                "Horario de atención",
                                "Ej: L-V 09:00-18:00",
                            ],
                        ]
                        as [$name, $label, $placeholder]
                    ): ?>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small" style="color:#374151;"><?= $label ?></label>
                        <input type="text" class="form-control" name="<?= $name ?>"
                               placeholder="<?= $placeholder ?>"
                               required
                               style="border-radius:10px;border-color:#d1fae5;background:#fff;">
                        <div class="invalid-feedback">Campo obligatorio.</div>
                    </div>
                    <?php endforeach; ?>
                </form>
            </div>
            <div class="modal-footer border-0" style="background:#f0fdf4;padding:1rem 1.75rem 1.5rem;">
                <button type="button" class="btn btn-light rounded-pill px-4"
                        data-bs-dismiss="modal" style="font-weight:600;">Cancelar</button>
                <button type="button" class="btn-add-centro" id="saveCentroBtn">
                    <i class="bi bi-check-lg"></i>Guardar Centro
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
    const isAdmin       = <?= ($_SESSION["usuario_rol"] ?? "") === "admin"
        ? "true"
        : "false" ?>;

    let allCentros = [];

    // Escapa HTML para prevenir inyección XSS
    function esc(str) {
        const d = document.createElement('div');
        d.textContent = str ?? '';
        return d.innerHTML;
    }

    function showFeedback(msg, type = 'success') {
        feedbackAlert.className = `alert alert-${type} animate__animated animate__fadeIn`;
        feedbackAlert.innerHTML =
            `<i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}-fill me-2"></i>${msg}`;
        setTimeout(() => feedbackAlert.classList.add('d-none'), 4000);
    }

    function renderCentros(data) {
        if (data.length === 0) {
            list.innerHTML = `
                <div class="col-12 text-center py-5">
                    <div style="font-size:3rem;margin-bottom:1rem;">🗺️</div>
                    <p class="text-muted">No se encontraron centros.</p>
                </div>`;
            return;
        }

        list.innerHTML = data.map((c, idx) => {
            const badges = esc(c.tipos_residuos).split(',').map(t =>
                `<span class="material-badge"><i class="bi bi-recycle"></i>${t.trim()}</span>`
            ).join('');

            return `
                <div class="col-md-6 col-lg-4">
                    <div class="centro-card h-100 animate-fade-in-up" style="animation-delay:${idx * 0.06}s">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-start gap-3 mb-3">
                                <div class="centro-icon-box">
                                    <i class="bi bi-recycle"></i>
                                </div>
                                <div class="flex-grow-1 min-w-0">
                                    <h6 class="fw-bold mb-1 text-truncate" title="${esc(c.nombre)}">${esc(c.nombre)}</h6>
                                    <small class="text-muted d-flex align-items-center gap-1">
                                        <i class="bi bi-geo-alt text-success"></i>
                                        ${esc(c.direccion)}
                                    </small>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap gap-1 mb-3">${badges}</div>
                            <div class="centro-schedule">
                                <i class="bi bi-clock-fill text-success"></i>
                                <span>${esc(c.horario)}</span>
                            </div>
                        </div>
                    </div>
                </div>`;
        }).join('');
    }

    // Búsqueda en vivo filtrando por nombre, dirección o residuos
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

    // Esqueleto de carga mientras se obtienen los datos
    list.innerHTML = `
        <div class="col-12">
            <div class="row g-4">
                ${[1,2,3].map(() => `
                    <div class="col-md-6 col-lg-4">
                        <div class="skeleton-card">
                            <div class="d-flex gap-3 mb-3">
                                <div class="skeleton-line" style="width:50px;height:50px;border-radius:14px;flex-shrink:0;"></div>
                                <div class="flex-grow-1">
                                    <div class="skeleton-line mb-2" style="height:14px;width:70%;"></div>
                                    <div class="skeleton-line" style="height:11px;width:50%;"></div>
                                </div>
                            </div>
                            <div class="d-flex gap-2 mb-3">
                                <div class="skeleton-line" style="height:22px;width:70px;border-radius:50px;"></div>
                                <div class="skeleton-line" style="height:22px;width:60px;border-radius:50px;"></div>
                            </div>
                            <div class="skeleton-line" style="height:32px;border-radius:8px;"></div>
                        </div>
                    </div>`).join('')}
            </div>
        </div>`;

    // Carga asíncrona de centros desde la API
    fetch('api/centros.php?action=list')
        .then(r => r.json())
        .then(json => {
            if (!json.success) throw new Error(json.message);
            allCentros = json.data;
            renderCentros(allCentros);
        })
        .catch(() => {
            list.innerHTML = `
                <div class="col-12">
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>Error al cargar los centros.
                    </div>
                </div>`;
        });

    // Admin: creación de nuevo centro vía modal
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
                    bootstrap.Modal.getInstance(document.getElementById('addCentroModal')).hide();
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
                saveBtn.innerHTML = '<i class="bi bi-check-lg"></i>Guardar Centro';
            }
        });
    }
});
</script>

<?php include __DIR__ . "/partials/footer.php"; ?>
