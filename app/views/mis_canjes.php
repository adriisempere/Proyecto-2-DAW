<?php
/**
 * Vista de Mis Canjes — GreenPoints
 * ---------------------------------------------------------------
 * Muestra el historial completo de canjes del usuario autenticado
 * con los códigos generados, listos para copiar.
 * Los datos se cargan desde api/recompensas.php?action=mis_canjes
 * ---------------------------------------------------------------
 */

if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php?action=login');
    exit;
}

$pageTitle = 'Mis Canjes | GreenPoints';
include __DIR__ . '/partials/header.php';
?>

<style>
    .code-box {
        background: #f8f9fa;
        border: 2px dashed #28a745;
        border-radius: 10px;
        padding: 0.6rem 1rem;
        font-family: 'Courier New', monospace;
        font-size: 1rem;
        font-weight: 700;
        letter-spacing: 2px;
        color: #198754;
        cursor: pointer;
        transition: background 0.2s;
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: relative;
    }

    .code-box:hover { background: #e8f5e9; }

    .code-copied {
        position: absolute;
        top: -26px; right: 0;
        background: #198754;
        color: white;
        font-size: .7rem;
        padding: 2px 8px;
        border-radius: 20px;
        opacity: 0;
        transition: opacity 0.2s;
        font-family: 'Poppins', sans-serif;
        letter-spacing: 0;
        font-weight: 500;
        white-space: nowrap;
    }

    .code-box.just-copied .code-copied { opacity: 1; }
</style>

<div class="container py-5">

    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h1 class="fw-bold display-6 mb-1">
                <i class="bi bi-bag-check-fill text-success me-2"></i>Mis Canjes
            </h1>
            <p class="text-muted mb-0">Historial de tarjetas regalo canjeadas</p>
        </div>
        <a href="index.php?action=tienda" class="btn btn-success rounded-pill px-4">
            <i class="bi bi-gift me-2"></i>Ir a la Tienda
        </a>
    </div>

    <div id="canjesContainer"></div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const container = document.getElementById('canjesContainer');

    function esc(str) {
        const d = document.createElement('div');
        d.textContent = str ?? '';
        return d.innerHTML;
    }

    function formatDate(str) {
        const d = new Date(str);
        return isNaN(d) ? str : d.toLocaleDateString('es-ES', {
            day: '2-digit', month: 'short', year: 'numeric',
            hour: '2-digit', minute: '2-digit'
        });
    }

    // ── Esqueleto ─────────────────────────────────────────────────
    container.innerHTML = `<div class="placeholder-glow">
        ${[1,2,3].map(() => `
            <div class="card border-0 shadow-sm mb-3 p-4">
                <div class="d-flex gap-3">
                    <span class="placeholder rounded" style="width:48px;height:48px;flex-shrink:0"></span>
                    <div class="flex-grow-1">
                        <span class="placeholder col-3 rounded mb-2 d-block"></span>
                        <span class="placeholder col-5 rounded d-block"></span>
                    </div>
                </div>
            </div>`).join('')}
    </div>`;

    // ── Cargar canjes ─────────────────────────────────────────────
    fetch('api/recompensas.php?action=mis_canjes')
        .then(r => r.json())
        .then(json => {
            if (!json.success) throw new Error(json.message);

            if (json.data.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-bag fs-1 d-block mb-3 opacity-25"></i>
                        <p class="mb-3">Todavía no has canjeado ninguna recompensa.</p>
                        <a href="index.php?action=tienda" class="btn btn-success rounded-pill px-4">
                            <i class="bi bi-gift me-2"></i>Explorar la tienda
                        </a>
                    </div>`;
                return;
            }

            container.innerHTML = json.data.map(c => `
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start gap-3 flex-wrap">
                            <div class="bg-success bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center flex-shrink-0 p-2"
                                 style="width:52px;height:52px">
                                ${c.imagen_url
                                    ? `<img src="${esc(c.imagen_url)}" alt="${esc(c.marca)}"
                                           style="width:36px;height:36px;object-fit:contain">`
                                    : `<span class="fw-bold text-success">${esc(c.marca.charAt(0))}</span>`
                                }
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start flex-wrap gap-1 mb-2">
                                    <div>
                                        <h6 class="fw-bold mb-0">${esc(c.nombre)}</h6>
                                        <small class="text-muted">${esc(c.marca)}</small>
                                    </div>
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25">
                                        −${esc(String(c.puntos_gastados))} pts
                                    </span>
                                </div>
                                <div class="code-box position-relative"
                                     onclick="copiarCodigo(this, '${esc(c.codigo)}')">
                                    <span class="code-copied">¡Copiado!</span>
                                    <span>${esc(c.codigo)}</span>
                                    <i class="bi bi-clipboard text-muted"></i>
                                </div>
                                <small class="text-muted d-block mt-2">
                                    <i class="bi bi-calendar3 me-1"></i>${esc(formatDate(c.canjeado_at))}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');
        })
        .catch(() => {
            container.innerHTML = '<p class="text-danger"><i class="bi bi-exclamation-triangle me-2"></i>Error al cargar los canjes.</p>';
        });

    window.copiarCodigo = function (el, codigo) {
        navigator.clipboard.writeText(codigo).then(() => {
            el.classList.add('just-copied');
            setTimeout(() => el.classList.remove('just-copied'), 1800);
        });
    };
});
</script>

<?php include __DIR__ . '/partials/footer.php'; ?>
