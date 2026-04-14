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
<style>
    .code-box {
        background: hsla(var(--h-primary), var(--s-primary), var(--l-primary), 0.05);
        border: 2px dashed var(--primary);
        border-radius: 16px;
        padding: 0.8rem 1.2rem;
        font-family: 'JetBrains Mono', 'Courier New', monospace;
        font-size: 1.1rem;
        font-weight: 800;
        letter-spacing: 2px;
        color: var(--primary);
        cursor: pointer;
        transition: var(--transition);
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: relative;
    }

    .code-box:hover {
        background: hsla(var(--h-primary), var(--s-primary), var(--l-primary), 0.1);
        transform: scale(1.02);
    }

    .code-copied {
        position: absolute;
        top: -30px; right: 0;
        background: var(--primary);
        color: white;
        font-size: .75rem;
        padding: 4px 12px;
        border-radius: 50px;
        opacity: 0;
        transition: var(--transition);
        font-family: 'Poppins', sans-serif;
        letter-spacing: 0;
        font-weight: 600;
        box-shadow: var(--shadow-sm);
    }

    .code-box.just-copied .code-copied { opacity: 1; transform: translateY(-5px); }
</style>

<div class="container py-5">

    <div class="d-flex align-items-center justify-content-between mb-5 flex-wrap gap-3 reveal-on-scroll">
        <div>
            <h1 class="fw-bold display-5 mb-1">
                <i class="bi bi-bag-check-fill text-success me-2"></i>Mis Canjes
            </h1>
            <p class="text-muted fs-5">Tus recompensas listas para ser utilizadas</p>
        </div>
        <a href="index.php?action=tienda" class="btn btn-success rounded-pill px-4 py-2 fw-bold shadow-sm hover-lift"
           style="background: linear-gradient(135deg, var(--primary), var(--secondary)); border: none;">
            <i class="bi bi-gift me-2"></i>Ir a la Tienda
        </a>
    </div>

    <div id="canjesContainer" class="row g-4"></div>

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
            day: '2-digit', month: 'long', year: 'numeric'
        });
    }

    // ── Esqueleto ─────────────────────────────────────────────────
    container.innerHTML = `
        ${[1,2,3].map(() => `
            <div class="col-md-6 col-lg-4">
                <div class="premium-card p-4 h-100 placeholder-glow">
                    <div class="d-flex gap-3 mb-3">
                        <span class="placeholder rounded-circle" style="width:52px;height:52px;flex-shrink:0"></span>
                        <div class="flex-grow-1">
                            <span class="placeholder col-6 rounded mb-2 d-block"></span>
                            <span class="placeholder col-4 rounded d-block"></span>
                        </div>
                    </div>
                    <span class="placeholder col-12 rounded" style="height:50px"></span>
                </div>
            </div>`).join('')}
    `;

    // ── Cargar canjes ─────────────────────────────────────────────
    fetch('api/recompensas.php?action=mis_canjes')
        .then(r => r.json())
        .then(json => {
            if (!json.success) throw new Error(json.message);

            if (json.data.length === 0) {
                container.innerHTML = `
                    <div class="col-12 text-center py-5 reveal-on-scroll">
                        <div class="mb-4">
                            <i class="bi bi-bag-x opacity-25" style="font-size: 5rem; color: var(--primary);"></i>
                        </div>
                        <h3 class="fw-bold mb-3">Aún no tienes canjes</h3>
                        <p class="text-muted mb-4 fs-5">Canjea tus puntos por tarjetas regalo de marcas increíbles.</p>
                        <a href="index.php?action=tienda" class="btn btn-success rounded-pill px-5 py-3 fw-bold shadow-lg"
                           style="background: linear-gradient(135deg, var(--primary), var(--secondary)); border: none;">
                            <i class="bi bi-gift me-2"></i>Explorar Recompensas
                        </a>
                    </div>`;
                return;
            }

            container.innerHTML = json.data.map(c => `
                <div class="col-md-6 col-lg-4 reveal-on-scroll">
                    <div class="premium-card p-4 h-100 d-flex flex-column">
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                 style="width:56px;height:56px; border: 1px solid var(--border-color);">
                                ${c.imagen_url
                                    ? `<img src="${esc(c.imagen_url)}" alt="${esc(c.marca)}"
                                           style="width:32px;height:32px;object-fit:contain">`
                                    : `<span class="fw-bold fs-4 text-success">${esc(c.marca.charAt(0))}</span>`
                                }
                            </div>
                            <div class="overflow-hidden">
                                <h6 class="fw-bold mb-0 text-truncate">${esc(c.nombre)}</h6>
                                <small class="text-muted">${esc(c.marca)}</small>
                            </div>
                        </div>

                        <div class="flex-grow-1 mb-4">
                            <div class="code-box" onclick="copiarCodigo(this, '${esc(c.codigo)}')">
                                <span class="code-copied">¡Copiado!</span>
                                <span>${esc(c.codigo)}</span>
                                <i class="bi bi-clipboard"></i>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-auto pt-3 border-top border-opacity-10">
                            <small class="text-muted">
                                <i class="bi bi-calendar3 me-1"></i>${esc(formatDate(c.canjeado_at))}
                            </small>
                            <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2 fw-bold" 
                                  style="border: 1px solid rgba(220,53,69,0.2)">
                                −${esc(String(c.puntos_gastados))} pts
                            </span>
                        </div>
                    </div>
                </div>
            `).join('');
            
            // Reinicializar animaciones de scroll para los nuevos elementos
            if(window.observer) {
                document.querySelectorAll('.reveal-on-scroll').forEach(el => window.observer.observe(el));
            }
        })
        .catch(() => {
            container.innerHTML = '<div class="col-12"><p class="text-danger text-center"><i class="bi bi-exclamation-triangle-fill me-2"></i>Error al cargar los canjes.</p></div>';
        });

    window.copiarCodigo = function (el, codigo) {
        navigator.clipboard.writeText(codigo).then(() => {
            el.classList.add('just-copied');
            setTimeout(() => el.classList.remove('just-copied'), 1800);
        });
    };
});
</script>

    window.copiarCodigo = function (el, codigo) {
        navigator.clipboard.writeText(codigo).then(() => {
            el.classList.add('just-copied');
            setTimeout(() => el.classList.remove('just-copied'), 1800);
        });
    };
});
</script>

<?php include __DIR__ . '/partials/footer.php'; ?>
