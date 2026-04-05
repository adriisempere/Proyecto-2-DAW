<?php
/**
 * Vista de Tienda de Recompensas — GreenPoints
 * ---------------------------------------------------------------
 * Permite al usuario canjear sus puntos por tarjetas regalo.
 * Requiere sesión activa; redirige al login si no la hay.
 *
 * Flujo:
 *   1. Carga el catálogo desde api/recompensas.php?action=list
 *   2. El usuario añade tarjetas al carrito (estado en memoria)
 *   3. El carrito lateral muestra ítems, total en puntos y saldo
 *   4. Al hacer checkout se muestra un modal de confirmación
 *   5. Si se confirma, llama a api/recompensas.php?action=checkout
 *   6. Modal de éxito revela los códigos generados para copiar
 * ---------------------------------------------------------------
 */

if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php?action=login');
    exit;
}

$pageTitle = 'Tienda de Recompensas | GreenPoints';
include __DIR__ . '/partials/header.php';
?>

<style>
    /* ── Carrito lateral ─────────────────────────────────────── */
    .cart-drawer {
        position: fixed;
        top: 0; right: 0;
        width: 360px;
        max-width: 95vw;
        height: 100vh;
        background: white;
        box-shadow: -4px 0 30px rgba(0,0,0,0.12);
        z-index: 1050;
        transform: translateX(100%);
        transition: transform 0.3s cubic-bezier(.4,0,.2,1);
        display: flex;
        flex-direction: column;
    }
    .cart-drawer.open { transform: translateX(0); }

    .cart-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.35);
        z-index: 1049;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s;
    }
    .cart-overlay.open { opacity: 1; pointer-events: all; }

    .cart-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-shrink: 0;
    }

    .cart-body {
        flex: 1;
        overflow-y: auto;
        padding: 1rem 1.5rem;
    }

    .cart-footer {
        padding: 1.25rem 1.5rem;
        border-top: 1px solid #f0f0f0;
        flex-shrink: 0;
        background: #fafafa;
    }

    .cart-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f5f5f5;
    }

    .cart-item:last-child { border-bottom: none; }

    .cart-item-img {
        width: 44px;
        height: 44px;
        object-fit: contain;
        border-radius: 8px;
        background: #f8f8f8;
        padding: 6px;
        flex-shrink: 0;
    }

    /* ── Tarjetas del catálogo ───────────────────────────────── */
    .reward-card {
        border: 2px solid transparent;
        border-radius: 16px;
        transition: all 0.25s;
        cursor: pointer;
        height: 100%;
    }

    .reward-card:hover {
        border-color: #28a745;
        transform: translateY(-4px);
        box-shadow: 0 10px 28px rgba(40,167,69,0.15) !important;
    }

    .reward-card .brand-logo {
        height: 52px;
        object-fit: contain;
        filter: grayscale(20%);
        transition: filter 0.2s;
    }

    .reward-card:hover .brand-logo { filter: none; }

    .pts-badge {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        border-radius: 20px;
        padding: 0.3rem 0.85rem;
        font-weight: 700;
        font-size: .85rem;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }

    .pts-badge.insufficient {
        background: #dee2e6;
        color: #6c757d;
    }

    /* ── Código revelado ─────────────────────────────────────── */
    .code-box {
        background: #f8f9fa;
        border: 2px dashed #28a745;
        border-radius: 10px;
        padding: 0.85rem 1rem;
        font-family: 'Courier New', monospace;
        font-size: 1.15rem;
        font-weight: 700;
        letter-spacing: 2px;
        color: #198754;
        text-align: center;
        cursor: pointer;
        transition: background 0.2s;
        position: relative;
    }

    .code-box:hover { background: #e8f5e9; }

    .code-copied {
        position: absolute;
        top: -28px; left: 50%;
        transform: translateX(-50%);
        background: #198754;
        color: white;
        font-size: .7rem;
        padding: 2px 8px;
        border-radius: 20px;
        white-space: nowrap;
        opacity: 0;
        transition: opacity 0.2s;
        font-family: 'Poppins', sans-serif;
        letter-spacing: 0;
        font-weight: 500;
    }

    .code-box.just-copied .code-copied { opacity: 1; }

    /* ── Botón flotante del carrito ──────────────────────────── */
    .cart-fab {
        position: fixed;
        bottom: 1.5rem;
        right: 1.5rem;
        z-index: 1048;
        width: 58px;
        height: 58px;
        border-radius: 50%;
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        border: none;
        box-shadow: 0 4px 18px rgba(40,167,69,0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        transition: transform 0.2s;
    }

    .cart-fab:hover { transform: scale(1.08); }

    .cart-badge {
        position: absolute;
        top: -4px; right: -4px;
        background: #dc3545;
        color: white;
        border-radius: 50%;
        width: 22px;
        height: 22px;
        font-size: .7rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        display: none;
    }

    /* ── Filtros de marca ────────────────────────────────────── */
    .brand-filter.active {
        background: #28a745 !important;
        color: white !important;
        border-color: #28a745 !important;
    }
</style>

<!-- ── Contenido principal ──────────────────────────────────── -->
<div class="container py-5">

    <!-- Cabecera -->
    <div class="d-flex align-items-start justify-content-between flex-wrap gap-3 mb-4">
        <div>
            <h1 class="fw-bold display-6 mb-1">
                <i class="bi bi-gift-fill text-success me-2"></i>Tienda de Recompensas
            </h1>
            <p class="text-muted mb-0">Canjea tus puntos por tarjetas regalo</p>
        </div>
        <div class="text-end">
            <div class="fw-bold fs-5 text-success">
                <i class="bi bi-star-fill text-warning me-1"></i>
                <span id="saldoDisplay"><?= number_format((int)$_SESSION['usuario_puntos']) ?></span> pts disponibles
            </div>
            <small class="text-muted">Tu saldo actual</small>
        </div>
    </div>

    <!-- Alerta de feedback -->
    <div id="tiendaAlert" class="alert d-none mb-4" role="alert"></div>

    <!-- Filtros de marca -->
    <div class="d-flex flex-wrap gap-2 mb-4" id="brandFilters">
        <button class="btn btn-outline-secondary btn-sm rounded-pill brand-filter active" data-brand="all">
            Todas
        </button>
    </div>

    <!-- Cuadrícula de tarjetas -->
    <div class="row g-4" id="catalogoGrid">
        <!-- Esqueleto de carga -->
        <?php for ($i = 0; $i < 8; $i++): ?>
            <div class="col-6 col-md-4 col-lg-3 placeholder-card">
                <div class="card border-0 shadow-sm p-3 placeholder-glow" style="border-radius:16px">
                    <span class="placeholder rounded mb-3 d-block mx-auto" style="width:52px;height:52px;"></span>
                    <span class="placeholder col-8 rounded mb-2 d-block mx-auto"></span>
                    <span class="placeholder col-5 rounded d-block mx-auto"></span>
                </div>
            </div>
        <?php endfor; ?>
    </div>

</div>

<!-- ── Overlay del carrito ───────────────────────────────────── -->
<div class="cart-overlay" id="cartOverlay"></div>

<!-- ── Carrito lateral ──────────────────────────────────────── -->
<div class="cart-drawer" id="cartDrawer">
    <div class="cart-header">
        <h5 class="fw-bold mb-0"><i class="bi bi-bag-heart text-success me-2"></i>Tu Carrito</h5>
        <button class="btn btn-sm btn-outline-secondary rounded-pill" id="closeCart">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
    <div class="cart-body" id="cartBody">
        <p class="text-muted text-center py-4" id="cartEmpty">
            <i class="bi bi-bag fs-2 d-block mb-2 opacity-25"></i>
            Tu carrito está vacío
        </p>
    </div>
    <div class="cart-footer" id="cartFooter" style="display:none!important">
        <div class="d-flex justify-content-between mb-1">
            <span class="text-muted">Subtotal</span>
            <span class="fw-bold" id="cartSubtotal">0 pts</span>
        </div>
        <div class="d-flex justify-content-between mb-3">
            <span class="text-muted">Saldo tras canje</span>
            <span class="fw-semibold" id="cartSaldoTras">— pts</span>
        </div>
        <button class="btn btn-success w-100 rounded-pill py-2 fw-bold" id="checkoutBtn">
            <i class="bi bi-lock-fill me-2"></i>Confirmar Canje
        </button>
        <button class="btn btn-outline-danger w-100 rounded-pill py-2 mt-2 btn-sm" id="clearCartBtn">
            Vaciar carrito
        </button>
    </div>
</div>

<!-- ── Botón flotante del carrito ────────────────────────────── -->
<button class="cart-fab" id="cartFab" title="Ver carrito">
    <i class="bi bi-bag-heart-fill"></i>
    <span class="cart-badge" id="cartBadge">0</span>
</button>

<!-- ── Modal de confirmación ────────────────────────────────── -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-shield-check text-success me-2"></i>Confirmar Canje
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-3" id="confirmBody"></div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary rounded-pill"
                        data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success rounded-pill px-4 fw-bold"
                        id="confirmCheckoutBtn">
                    <i class="bi bi-check-lg me-1"></i>Sí, canjear
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ── Modal de éxito con códigos ───────────────────────────── -->
<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true"
     data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0"
                 style="background:linear-gradient(135deg,#28a745,#20c997);border-radius:.375rem .375rem 0 0">
                <div class="text-white py-2">
                    <h4 class="fw-bold mb-1">
                        <i class="bi bi-stars me-2"></i>¡Canje completado!
                    </h4>
                    <p class="mb-0 opacity-75 small">
                        Guarda tus códigos en un lugar seguro. No se volverán a mostrar aquí.
                    </p>
                </div>
            </div>
            <div class="modal-body p-4" id="successBody"></div>
            <div class="modal-footer border-0">
                <a href="index.php?action=mis_canjes"
                   class="btn btn-outline-success rounded-pill">
                    Ver mis canjes
                </a>
                <button type="button" class="btn btn-success rounded-pill px-4"
                        data-bs-dismiss="modal">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Estado ────────────────────────────────────────────────────
    let catalogo = [];
    let carrito  = []; // [{ recompensa, cantidad }]
    let saldo    = <?= (int)$_SESSION['usuario_puntos'] ?>;

    const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
    const successModal = new bootstrap.Modal(document.getElementById('successModal'));

    // ── Escapar HTML ──────────────────────────────────────────────
    function esc(str) {
        const d = document.createElement('div');
        d.textContent = str ?? '';
        return d.innerHTML;
    }

    // ── Cargar catálogo ───────────────────────────────────────────
    fetch('api/recompensas.php?action=list')
        .then(r => r.json())
        .then(json => {
            if (!json.success) throw new Error();
            catalogo = json.data;
            renderCatalogo(catalogo);
            renderFiltros(catalogo);
        })
        .catch(() => {
            document.getElementById('catalogoGrid').innerHTML =
                '<p class="text-danger col-12"><i class="bi bi-exclamation-triangle me-2"></i>Error al cargar el catálogo.</p>';
        });

    // ── Renderizar filtros de marca ───────────────────────────────
    function renderFiltros(data) {
        const marcas = [...new Set(data.map(r => r.marca))];
        const cont   = document.getElementById('brandFilters');
        marcas.forEach(m => {
            const btn = document.createElement('button');
            btn.className   = 'btn btn-outline-secondary btn-sm rounded-pill brand-filter';
            btn.dataset.brand = m;
            btn.textContent = m;
            cont.appendChild(btn);
        });
    }

    // Filtrar al hacer clic en marca
    document.getElementById('brandFilters').addEventListener('click', function (e) {
        const btn = e.target.closest('.brand-filter');
        if (!btn) return;
        document.querySelectorAll('.brand-filter').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        const brand = btn.dataset.brand;
        renderCatalogo(brand === 'all' ? catalogo : catalogo.filter(r => r.marca === brand));
    });

    // ── Renderizar tarjetas ───────────────────────────────────────
    function renderCatalogo(data) {
        const grid = document.getElementById('catalogoGrid');

        if (data.length === 0) {
            grid.innerHTML = '<p class="text-muted col-12 text-center py-4">No hay recompensas disponibles.</p>';
            return;
        }

        grid.innerHTML = data.map(r => {
            const canAfford  = saldo >= r.puntos_coste;
            const enCarrito  = carrito.find(i => i.recompensa.id === r.id);
            const qty        = enCarrito ? enCarrito.cantidad : 0;

            return `
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card shadow-sm reward-card p-3 text-center ${!canAfford ? 'opacity-60' : ''}">
                    <div class="mb-3 d-flex align-items-center justify-content-center" style="height:56px">
                        ${r.imagen_url
                            ? `<img src="${esc(r.imagen_url)}" alt="${esc(r.marca)}" class="brand-logo">`
                            : `<span class="fw-bold fs-5 text-success">${esc(r.marca)}</span>`
                        }
                    </div>
                    <h6 class="fw-bold mb-1">${esc(r.nombre)}</h6>
                    <p class="text-muted small mb-3" style="min-height:2.4rem">${esc(r.descripcion || '')}</p>
                    <div class="pts-badge mx-auto mb-3 ${!canAfford ? 'insufficient' : ''}">
                        <i class="bi bi-star-fill"></i> ${esc(String(r.puntos_coste))} pts
                    </div>
                    ${qty > 0 ? `
                        <div class="d-flex align-items-center justify-content-center gap-2">
                            <button class="btn btn-outline-secondary btn-sm rounded-circle"
                                    style="width:32px;height:32px;padding:0"
                                    onclick="changeQty(${r.id}, -1)">−</button>
                            <span class="fw-bold">${qty}</span>
                            <button class="btn btn-success btn-sm rounded-circle"
                                    style="width:32px;height:32px;padding:0"
                                    onclick="changeQty(${r.id}, 1)">+</button>
                        </div>
                    ` : `
                        <button class="btn btn-success btn-sm rounded-pill w-100"
                                onclick="addToCart(${r.id})"
                                ${!canAfford ? 'disabled' : ''}>
                            <i class="bi bi-plus-circle me-1"></i>Añadir
                        </button>
                    `}
                </div>
            </div>`;
        }).join('');
    }

    // ── Gestión del carrito ───────────────────────────────────────
    window.addToCart = function (id) {
        const r = catalogo.find(x => x.id === id);
        if (!r) return;
        const existing = carrito.find(i => i.recompensa.id === id);
        if (existing) {
            existing.cantidad++;
        } else {
            carrito.push({ recompensa: r, cantidad: 1 });
        }
        updateCart();
        openCart();
    };

    window.changeQty = function (id, delta) {
        const idx = carrito.findIndex(i => i.recompensa.id === id);
        if (idx === -1) return;
        carrito[idx].cantidad += delta;
        if (carrito[idx].cantidad <= 0) carrito.splice(idx, 1);
        updateCart();
        // Re-renderizar catálogo para actualizar los controles
        const activeBrand = document.querySelector('.brand-filter.active')?.dataset.brand || 'all';
        renderCatalogo(activeBrand === 'all' ? catalogo : catalogo.filter(r => r.marca === activeBrand));
    };

    function calcTotal() {
        return carrito.reduce((s, i) => s + i.recompensa.puntos_coste * i.cantidad, 0);
    }

    function updateCart() {
        const total   = calcTotal();
        const items   = carrito.reduce((s, i) => s + i.cantidad, 0);
        const tras    = saldo - total;
        const body    = document.getElementById('cartBody');
        const footer  = document.getElementById('cartFooter');
        const badge   = document.getElementById('cartBadge');
        const empty   = document.getElementById('cartEmpty');

        // Badge flotante
        if (items > 0) {
            badge.style.display = 'flex';
            badge.textContent   = items > 9 ? '9+' : items;
        } else {
            badge.style.display = 'none';
        }

        if (carrito.length === 0) {
            if (empty) empty.style.display = '';
            footer.style.setProperty('display', 'none', 'important');
            // Limpiar ítems
            body.querySelectorAll('.cart-item').forEach(el => el.remove());
            return;
        }

        if (empty) empty.style.display = 'none';
        footer.style.removeProperty('display');

        // Renderizar ítems
        body.querySelectorAll('.cart-item').forEach(el => el.remove());
        carrito.forEach(item => {
            const div = document.createElement('div');
            div.className = 'cart-item';
            div.innerHTML = `
                ${item.recompensa.imagen_url
                    ? `<img src="${esc(item.recompensa.imagen_url)}" class="cart-item-img" alt="${esc(item.recompensa.marca)}">`
                    : `<div class="cart-item-img d-flex align-items-center justify-content-center fw-bold text-success small">${esc(item.recompensa.marca.charAt(0))}</div>`
                }
                <div class="flex-grow-1 min-w-0">
                    <div class="fw-semibold small text-truncate">${esc(item.recompensa.nombre)}</div>
                    <div class="text-muted" style="font-size:.78rem">${esc(item.recompensa.marca)}</div>
                    <div class="text-success small fw-bold">${item.recompensa.puntos_coste * item.cantidad} pts</div>
                </div>
                <div class="d-flex align-items-center gap-1 flex-shrink-0">
                    <button class="btn btn-outline-secondary btn-sm rounded-circle"
                            style="width:26px;height:26px;padding:0;font-size:.75rem"
                            onclick="changeQty(${item.recompensa.id}, -1)">−</button>
                    <span class="fw-bold small" style="min-width:16px;text-align:center">${item.cantidad}</span>
                    <button class="btn btn-success btn-sm rounded-circle"
                            style="width:26px;height:26px;padding:0;font-size:.75rem"
                            onclick="changeQty(${item.recompensa.id}, 1)">+</button>
                </div>
            `;
            body.insertBefore(div, document.getElementById('cartEmpty'));
        });

        // Totales
        document.getElementById('cartSubtotal').textContent = total + ' pts';
        const saldoTrasEl = document.getElementById('cartSaldoTras');
        saldoTrasEl.textContent = tras + ' pts';
        saldoTrasEl.className   = 'fw-semibold ' + (tras < 0 ? 'text-danger' : 'text-success');
        document.getElementById('checkoutBtn').disabled = tras < 0;
    }

    // ── Abrir / cerrar carrito ────────────────────────────────────
    function openCart()  {
        document.getElementById('cartDrawer').classList.add('open');
        document.getElementById('cartOverlay').classList.add('open');
    }
    function closeCart() {
        document.getElementById('cartDrawer').classList.remove('open');
        document.getElementById('cartOverlay').classList.remove('open');
    }

    document.getElementById('cartFab').addEventListener('click', openCart);
    document.getElementById('closeCart').addEventListener('click', closeCart);
    document.getElementById('cartOverlay').addEventListener('click', closeCart);
    document.getElementById('clearCartBtn').addEventListener('click', function () {
        carrito = [];
        updateCart();
        const activeBrand = document.querySelector('.brand-filter.active')?.dataset.brand || 'all';
        renderCatalogo(activeBrand === 'all' ? catalogo : catalogo.filter(r => r.marca === activeBrand));
    });

    // ── Checkout — modal de confirmación ─────────────────────────
    document.getElementById('checkoutBtn').addEventListener('click', function () {
        const total = calcTotal();
        const tras  = saldo - total;

        let resumen = '<ul class="list-unstyled mb-3">';
        carrito.forEach(i => {
            resumen += `<li class="d-flex justify-content-between py-1 border-bottom">
                <span>${esc(i.recompensa.marca)} — ${esc(i.recompensa.nombre)} × ${i.cantidad}</span>
                <span class="fw-bold text-success">${i.recompensa.puntos_coste * i.cantidad} pts</span>
            </li>`;
        });
        resumen += `</ul>
            <div class="d-flex justify-content-between fw-bold fs-5 mt-3">
                <span>Total a descontar</span>
                <span class="text-danger">${total} pts</span>
            </div>
            <div class="d-flex justify-content-between text-muted mt-1">
                <span>Saldo resultante</span>
                <span class="fw-semibold ${tras < 0 ? 'text-danger' : 'text-success'}">${tras} pts</span>
            </div>`;

        document.getElementById('confirmBody').innerHTML = resumen;
        closeCart();
        confirmModal.show();
    });

    // ── Checkout — envío a la API ─────────────────────────────────
    document.getElementById('confirmCheckoutBtn').addEventListener('click', async function () {
        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Procesando…';

        const items = carrito.map(i => ({
            recompensa_id: i.recompensa.id,
            cantidad:      i.cantidad,
        }));

        const csrfToken = document.querySelector('[name="csrf_token"]')?.value ?? '';

        try {
            const res  = await fetch('api/recompensas.php?action=checkout', {
                method:  'POST',
                headers: { 'Content-Type': 'application/json' },
                body:    JSON.stringify({ csrf_token: csrfToken, items }),
            });
            const json = await res.json();

            confirmModal.hide();

            if (json.success) {
                // Actualizar saldo
                saldo = json.puntos_totales;
                document.getElementById('saldoDisplay').textContent =
                    saldo.toLocaleString('es-ES');
                const headerBadge = document.querySelector('.badge-points .fw-bold');
                if (headerBadge) headerBadge.textContent = saldo + ' pts';

                // Vaciar carrito
                carrito = [];
                updateCart();

                // Mostrar códigos
                let codigoHtml = json.codigos.map(c => `
                    <div class="mb-3">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="fw-semibold">${esc(c.marca)} — ${esc(c.nombre)}</span>
                            <span class="badge bg-success ms-auto">${esc(String(c.puntos))} pts</span>
                        </div>
                        <div class="code-box position-relative" onclick="copiarCodigo(this, '${esc(c.codigo)}')">
                            <span class="code-copied">¡Copiado!</span>
                            ${esc(c.codigo)}
                            <i class="bi bi-clipboard ms-2 text-muted" style="font-size:.8rem"></i>
                        </div>
                    </div>
                `).join('');

                codigoHtml += `<p class="text-muted small mt-3 mb-0">
                    <i class="bi bi-info-circle me-1"></i>
                    Haz clic en cada código para copiarlo. También los encontrarás en
                    <a href="index.php?action=mis_canjes" class="text-success">Mis Canjes</a>.
                </p>`;

                document.getElementById('successBody').innerHTML = codigoHtml;
                successModal.show();

                // Re-renderizar catálogo con nuevo saldo
                renderCatalogo(catalogo);

            } else {
                const alertBox = document.getElementById('tiendaAlert');
                alertBox.className = 'alert alert-danger animate__animated animate__fadeIn';
                alertBox.innerHTML = `<i class="bi bi-exclamation-triangle-fill me-2"></i>${json.message}`;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }

        } catch {
            confirmModal.hide();
            const alertBox = document.getElementById('tiendaAlert');
            alertBox.className = 'alert alert-danger';
            alertBox.innerHTML = '<i class="bi bi-exclamation-triangle-fill me-2"></i>Error de red. Inténtalo más tarde.';
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-lg me-1"></i>Sí, canjear';
        }
    });

    // ── Copiar código al portapapeles ─────────────────────────────
    window.copiarCodigo = function (el, codigo) {
        navigator.clipboard.writeText(codigo).then(() => {
            el.classList.add('just-copied');
            setTimeout(() => el.classList.remove('just-copied'), 1800);
        });
    };
});
</script>

<?php
require_once __DIR__ . '/../helpers/CsrfHelper.php';
echo '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(CsrfHelper::generateToken()) . '">';
include __DIR__ . '/partials/footer.php';
?>
