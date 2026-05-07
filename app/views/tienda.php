<?php
/**
 * Vista de la Tienda de Recompensas — GreenPoints
 * ---------------------------------------------------------------
 * Permite al usuario canjear sus puntos por tarjetas regalo.
 * Requiere sesión activa; redirige al login si no la hay.
 *
 * Flujo:
 *   1. Carga el catálogo desde api/recompensas.php?action=list
 *   2. El usuario añade tarjetas al carrito (estado en memoria JS)
 *   3. El carrito lateral muestra los ítems, total en puntos y saldo
 *   4. Al hacer checkout se muestra un modal de confirmación
 *   5. Si se confirma, envía la petición a api/recompensas.php?action=checkout
 *   6. Modal de éxito revela los códigos generados para copiar al portapapeles
 * ---------------------------------------------------------------
 */

if (!isset($_SESSION["usuario_id"])) {
    header("Location: index.php?action=login");
    exit();
}

$pageTitle = "Tienda de Recompensas | GreenPoints";
include __DIR__ . "/partials/header.php";
?>

<style>
/* ── Carrito lateral con efecto glass ──────────────────────────── */
.cart-drawer {
    position: fixed;
    top: 0; right: 0;
    width: 380px;
    max-width: 96vw;
    height: 100vh;
    /* Fondo oscuro opaco SIN backdrop-filter para evitar problemas de stacking context */
    background: #0d2e16;
    border-left: 1px solid rgba(255,255,255,.1);
    box-shadow: -8px 0 48px rgba(0,0,0,.4);
    /* z-index: 1045 → por debajo del modal de Bootstrap (1055) para que los modales sean visibles */
    z-index: 1045;
    transform: translateX(100%);
    transition: transform 0.35s cubic-bezier(.4,0,.2,1);
    display: flex;
    flex-direction: column;
    color: #fff;
}
.cart-drawer.open { transform: translateX(0); }

.cart-overlay {
    position: fixed; inset: 0;
    background: rgba(0,0,0,.55);
    /* z-index: 1040 → por debajo del modal de Bootstrap (1055) para que el modal sea accesible */
    z-index: 1040;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s ease;
    /* SIN backdrop-filter: incluso con opacity:0 puede difuminar toda la pantalla en Safari/Chrome */
}
.cart-overlay.open { opacity: 1; pointer-events: all; }

.cart-header {
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid rgba(255,255,255,.08);
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-shrink: 0;
    background: linear-gradient(135deg, rgba(22,163,74,.2), rgba(13,148,136,.2));
}
.cart-header h5 { color: #fff; font-weight: 700; }

.cart-body {
    flex: 1;
    overflow-y: auto;
    padding: 1rem 1.5rem;
    scrollbar-width: thin;
    scrollbar-color: rgba(255,255,255,.15) transparent;
}
.cart-body::-webkit-scrollbar { width: 4px; }
.cart-body::-webkit-scrollbar-track { background: transparent; }
.cart-body::-webkit-scrollbar-thumb { background: rgba(255,255,255,.15); border-radius: 4px; }

.cart-footer {
    padding: 1.25rem 1.5rem;
    border-top: 1px solid rgba(255,255,255,.08);
    flex-shrink: 0;
    background: rgba(0,0,0,.2);
}

.cart-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.85rem 0;
    border-bottom: 1px solid rgba(255,255,255,.06);
}
.cart-item:last-child { border-bottom: none; }

.cart-item-img {
    width: 44px; height: 44px;
    object-fit: contain;
    border-radius: 10px;
    background: rgba(255,255,255,.1);
    padding: 6px;
    flex-shrink: 0;
}

/* Totales y saldo tras el canje */
.cart-total-row { color: rgba(255,255,255,.7); font-size: .88rem; }
.cart-total-row strong { color: #fff; }

/* Botón para confirmar el canje desde el carrito */
.btn-checkout {
    width: 100%;
    background: linear-gradient(135deg, #22c55e, #0d9488);
    border: none;
    border-radius: 12px;
    padding: 0.85rem;
    font-weight: 700;
    font-size: .95rem;
    color: #fff;
    cursor: pointer;
    transition: all .25s ease;
    box-shadow: 0 6px 20px rgba(34,197,94,.35);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: .5rem;
}
.btn-checkout:hover { box-shadow: 0 10px 28px rgba(34,197,94,.55); transform: translateY(-2px); }
.btn-checkout:disabled { opacity: .6; transform: none; cursor: not-allowed; }

.btn-clear-cart {
    width: 100%;
    background: transparent;
    border: 1px solid rgba(239,68,68,.35);
    border-radius: 10px;
    padding: 0.55rem;
    font-size: .84rem;
    color: rgba(239,68,68,.8);
    cursor: pointer;
    transition: all .2s;
    margin-top: 0.6rem;
}
.btn-clear-cart:hover { background: rgba(239,68,68,.15); color: #f87171; border-color: #f87171; }

/* Tarjetas de recompensa del catálogo */
.reward-card {
    border: 1.5px solid rgba(22,163,74,.15);
    border-radius: 18px;
    transition: all 0.25s cubic-bezier(.175,.885,.32,1.275);
    cursor: pointer;
    height: 100%;
    background: rgba(255,255,255,.92);
    backdrop-filter: blur(10px);
    position: relative;
    overflow: hidden;
}
.reward-card::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0;
    height: 3px;
    background: linear-gradient(90deg, #22c55e, #0d9488);
    opacity: 0;
    transition: opacity .25s;
}
.reward-card:hover {
    border-color: rgba(22,163,74,.45);
    transform: translateY(-6px);
    box-shadow: 0 16px 40px rgba(22,163,74,.18) !important;
}
.reward-card:hover::before { opacity: 1; }
.reward-card.in-cart {
    border-color: #22c55e;
    box-shadow: 0 0 0 3px rgba(34,197,94,.15);
}
.reward-card.in-cart::before { opacity: 1; }

.reward-card .brand-logo {
    height: 52px; object-fit: contain;
    filter: grayscale(20%);
    transition: filter .2s;
    display: block; margin: 0 auto;
}
.reward-card:hover .brand-logo { filter: none; }

.pts-badge {
    background: linear-gradient(135deg, #16a34a, #0d9488);
    color: white;
    border-radius: 20px;
    padding: 0.3rem 0.85rem;
    font-weight: 700;
    font-size: .82rem;
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    box-shadow: 0 3px 10px rgba(22,163,74,.3);
}
.pts-badge.insufficient {
    background: linear-gradient(135deg, #e5e7eb, #d1d5db);
    color: #6b7280;
    box-shadow: none;
}

/* Caja de código generado tras el canje exitoso */
.code-box {
    background: #f0fdf4;
    border: 2px dashed #16a34a;
    border-radius: 12px;
    padding: 0.85rem 1rem;
    font-family: 'Courier New', monospace;
    font-size: 1.1rem;
    font-weight: 700;
    letter-spacing: 2.5px;
    color: #15803d;
    text-align: center;
    cursor: pointer;
    transition: all .2s;
    position: relative;
    user-select: all;
}
.code-box:hover { background: #dcfce7; border-style: solid; }

.code-copied {
    position: absolute;
    top: -30px; left: 50%;
    transform: translateX(-50%);
    background: #16a34a;
    color: white;
    font-size: .7rem;
    padding: 3px 10px;
    border-radius: 20px;
    white-space: nowrap;
    opacity: 0;
    transition: opacity 0.2s;
    font-family: 'Poppins', sans-serif;
    letter-spacing: 0;
    font-weight: 600;
    pointer-events: none;
}
.code-box.just-copied .code-copied { opacity: 1; }

/* Botón flotante del carrito (FAB) */
.cart-fab {
    position: fixed;
    bottom: 5rem; /* Por encima del scroll-to-top */
    right: 1.5rem;
    z-index: 1048;
    width: 58px; height: 58px;
    border-radius: 16px;
    background: linear-gradient(135deg, #16a34a, #0d9488);
    color: white;
    border: none;
    box-shadow: 0 6px 24px rgba(22,163,74,.5);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    transition: all .3s cubic-bezier(.175,.885,.32,1.275);
}
.cart-fab:hover { transform: scale(1.1) translateY(-3px); box-shadow: 0 10px 32px rgba(22,163,74,.65); }

.cart-badge {
    position: absolute;
    top: -6px; right: -6px;
    background: #dc2626;
    color: white;
    border-radius: 50%;
    width: 22px; height: 22px;
    font-size: .68rem;
    font-weight: 700;
    display: none;
    align-items: center;
    justify-content: center;
    border: 2px solid #fff;
    box-shadow: 0 2px 8px rgba(220,38,38,.4);
}

/* Filtros para seleccionar por marca */
.brand-filter {
    border-radius: 50px;
    font-size: .82rem;
    font-weight: 600;
    padding: .3rem .9rem;
    border: 1.5px solid #d1fae5;
    background: transparent;
    color: #374151;
    transition: all .2s ease;
    cursor: pointer;
}
.brand-filter:hover { border-color: #16a34a; color: #16a34a; }
.brand-filter.active {
    background: linear-gradient(135deg, #16a34a, #0d9488) !important;
    color: white !important;
    border-color: transparent !important;
    box-shadow: 0 4px 12px rgba(22,163,74,.35);
}

/* Controles de cantidad (sumar/restar) */
.qty-btn {
    width: 28px; height: 28px;
    border-radius: 8px;
    border: 1px solid rgba(255,255,255,.2);
    background: rgba(255,255,255,.1);
    color: #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: .9rem;
    cursor: pointer;
    transition: all .2s;
    line-height: 1;
    padding: 0;
}
.qty-btn:hover { background: rgba(255,255,255,.22); border-color: rgba(255,255,255,.4); }
.qty-btn-cat {
    width: 30px; height: 30px;
    border-radius: 8px;
    border: 1.5px solid #d1fae5;
    background: #f0fdf4;
    color: #16a34a;
    display: flex; align-items: center; justify-content: center;
    font-size: .95rem;
    cursor: pointer;
    transition: all .2s;
    padding: 0;
}
.qty-btn-cat.plus { background: linear-gradient(135deg,#16a34a,#0d9488); color:#fff; border-color:transparent; }
.qty-btn-cat:hover { transform: scale(1.1); }

/* Estilos del modal de confirmación de canje */
.confirm-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: .6rem 0;
    border-bottom: 1px solid #e8f5e9;
    font-size: .9rem;
}
.confirm-item:last-of-type { border-bottom: none; }

@media (max-width: 575.98px) {
    .cart-drawer { width: 100%; border-radius: 0; }
    .cart-fab { bottom: 4.5rem; right: 1rem; }
}
</style>

<!-- ── Contenido principal de la tienda ─────────────────────────── -->
<div class="container py-5">

    <!-- Cabecera de la tienda con título y saldo del usuario -->
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
                <span id="saldoDisplay"><?= number_format(
                    (int) $_SESSION["usuario_puntos"],
                ) ?></span> pts disponibles
            </div>
            <small class="text-muted">Tu saldo actual</small>
        </div>
    </div>

    <!-- Alerta para mensajes de error/feedback al usuario -->
    <div id="tiendaAlert" class="alert d-none mb-4" role="alert"></div>

    <!-- Filtros para mostrar recompensas por marca -->
    <div class="d-flex flex-wrap gap-2 mb-4" id="brandFilters">
        <button class="btn btn-outline-secondary btn-sm rounded-pill brand-filter active" data-brand="all">
            Todas
        </button>
    </div>

    <!-- Cuadrícula de tarjetas de recompensa -->
    <div class="row g-4" id="catalogoGrid">
        <!-- Esqueleto de carga mientras se obtienen los datos -->
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

<!-- ── Overlay (fondo oscuro) del carrito lateral ──────────────── -->
<div class="cart-overlay" id="cartOverlay"></div>

<!-- ── Carrito lateral con estilo glass ──────────────────────────── -->
<div class="cart-drawer" id="cartDrawer">
    <div class="cart-header">
        <h5 class="mb-0 d-flex align-items-center gap-2">
            <i class="bi bi-bag-heart-fill"></i>Tu Carrito
            <span class="badge ms-1" id="cartCountBadge"
                  style="background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.2);
                         font-size:.72rem;border-radius:50px;">0 ítems</span>
        </h5>
        <button class="btn" id="closeCart"
                style="background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.18);
                       color:#fff;border-radius:10px;padding:.35rem .65rem;">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
    <div class="cart-body" id="cartBody">
        <div class="text-center py-5" id="cartEmpty">
            <div style="font-size:2.5rem;margin-bottom:.75rem;opacity:.35;">🛒</div>
            <p style="color:rgba(255,255,255,.5);font-size:.88rem;">Tu carrito está vacío</p>
        </div>
    </div>
    <div class="cart-footer" id="cartFooter" style="display:none!important">
        <div class="d-flex justify-content-between mb-1 cart-total-row">
            <span>Subtotal</span>
            <strong id="cartSubtotal">0 pts</strong>
        </div>
        <div class="d-flex justify-content-between mb-3 cart-total-row">
            <span>Saldo tras canje</span>
            <strong id="cartSaldoTras">— pts</strong>
        </div>
        <button class="btn-checkout" id="checkoutBtn">
            <i class="bi bi-lock-fill"></i>Confirmar Canje
        </button>
        <button class="btn-clear-cart" id="clearCartBtn">
            <i class="bi bi-trash me-1"></i>Vaciar carrito
        </button>
    </div>
</div>

<!-- ── Botón flotante del carrito (FAB) ─────────────────────────── -->
<button class="cart-fab" id="cartFab" title="Ver carrito">
    <i class="bi bi-bag-heart-fill"></i>
    <span class="cart-badge" id="cartBadge">0</span>
</button>

<!-- ── Modal de confirmación antes de realizar el canje ─────────── -->
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

<!-- ── Modal de éxito que muestra los códigos generados ─────────── -->
<!--    data-bs-backdrop="static" + data-bs-keyboard="false" evita
       que el usuario cierre el modal sin querer y pierda los códigos -->
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
// ── Lógica de la tienda: carga el catálogo, gestiona el carrito
//    en memoria, muestra modales de confirmación y envía el canje
//    a la API. ─────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {

    // ── Estado de la aplicación ───────────────────────────────────
    //    catalogo: array de recompensas obtenido de la API
    //    carrito:  array de objetos { recompensa, cantidad }
    //    saldo:    puntos disponibles del usuario
    let catalogo = [];
    let carrito  = [];
    let saldo    = <?= (int) $_SESSION["usuario_puntos"] ?>;

    const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
    const successModal = new bootstrap.Modal(document.getElementById('successModal'));

    // ── Escapar HTML para prevenir XSS en innerHTML ────────────────
    function esc(str) {
        const d = document.createElement('div');
        d.textContent = str ?? '';
        return d.innerHTML;
    }

    // ── Obtener el catálogo de recompensas desde la API ────────────
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

    // ── Crear botones de filtro dinámicamente según las marcas disponibles ──
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

    // Filtrar el catálogo al hacer clic en una marca
    document.getElementById('brandFilters').addEventListener('click', function (e) {
        const btn = e.target.closest('.brand-filter');
        if (!btn) return;
        document.querySelectorAll('.brand-filter').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        const brand = btn.dataset.brand;
        renderCatalogo(brand === 'all' ? catalogo : catalogo.filter(r => r.marca === brand));
    });

    // ── Renderizar las tarjetas de recompensa en la cuadrícula ─────
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

    // ── Funciones para añadir, modificar cantidad y calcular total del carrito ──
    window.addToCart = function (id) {
        const r = catalogo.find(x => x.id == id);
        if (!r) return;
        const existing = carrito.find(i => i.recompensa.id == id);
        if (existing) {
            existing.cantidad++;
        } else {
            carrito.push({ recompensa: r, cantidad: 1 });
        }
        updateCart();
        openCart();
    };

    window.changeQty = function (id, delta) {
        const idx = carrito.findIndex(i => i.recompensa.id == id);
        if (idx === -1) return;
        carrito[idx].cantidad += delta;
        if (carrito[idx].cantidad <= 0) carrito.splice(idx, 1);
        updateCart();
        // Re-renderizar catálogo para reflejar el cambio en los botones ± y "Añadir"
        const activeBrand = document.querySelector('.brand-filter.active')?.dataset.brand || 'all';
        renderCatalogo(activeBrand === 'all' ? catalogo : catalogo.filter(r => r.marca === activeBrand));
    };

    function calcTotal() {
        return carrito.reduce((s, i) => s + i.recompensa.puntos_coste * i.cantidad, 0);
    }

    function updateCart() {
        const total  = calcTotal();
        const items  = carrito.reduce((s, i) => s + i.cantidad, 0);
        const tras   = saldo - total;
        const body   = document.getElementById('cartBody');
        const footer = document.getElementById('cartFooter');
        const badge  = document.getElementById('cartBadge');
        const countBadge = document.getElementById('cartCountBadge');
        const empty  = document.getElementById('cartEmpty');

        // Badge flotante
        badge.style.display = items > 0 ? 'flex' : 'none';
        badge.textContent   = items > 9 ? '9+' : items;
        if (countBadge) countBadge.textContent = items + ' ítem' + (items !== 1 ? 's' : '');

        if (carrito.length === 0) {
            if (empty) empty.style.display = '';
            footer.style.setProperty('display', 'none', 'important');
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
                ${
                    item.recompensa.imagen_url
                        ? `<img src="${esc(item.recompensa.imagen_url)}" class="cart-item-img" alt="${esc(item.recompensa.marca)}">`
                        : `<div class="cart-item-img d-flex align-items-center justify-content-center fw-bold" style="color:#22c55e;font-size:1rem;">${esc(item.recompensa.marca.charAt(0))}</div>`
                }
                <div class="flex-grow-1 min-w-0">
                    <div class="fw-semibold small text-truncate" style="color:#fff;">${esc(item.recompensa.nombre)}</div>
                    <div style="font-size:.76rem;color:rgba(255,255,255,.5);">${esc(item.recompensa.marca)}</div>
                    <div style="font-size:.8rem;font-weight:700;color:#22c55e;">${item.recompensa.puntos_coste * item.cantidad} pts</div>
                </div>
                <div class="d-flex align-items-center gap-1 flex-shrink-0">
                    <button class="qty-btn" onclick="changeQty(${item.recompensa.id}, -1)">−</button>
                    <span style="font-weight:700;font-size:.88rem;min-width:18px;text-align:center;color:#fff;">${item.cantidad}</span>
                    <button class="qty-btn" onclick="changeQty(${item.recompensa.id}, 1)">+</button>
                </div>
            `;
            body.insertBefore(div, empty);
        });

        // Totales
        document.getElementById('cartSubtotal').textContent = total.toLocaleString('es-ES') + ' pts';
        const saldoTrasEl = document.getElementById('cartSaldoTras');
        saldoTrasEl.textContent = tras.toLocaleString('es-ES') + ' pts';
        saldoTrasEl.style.color = tras < 0 ? '#f87171' : '#22c55e';
        document.getElementById('checkoutBtn').disabled = tras < 0;
    }

    // ── Control de apertura y cierre del carrito lateral ───────────
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

    // ── Checkout: mostrar modal de confirmación con resumen del canje ────
    document.getElementById('checkoutBtn').addEventListener('click', function () {
        const total = calcTotal();
        const tras  = saldo - total;

        let resumen = '<div class="mb-3">';
        carrito.forEach(i => {
            resumen += `
                <div class="confirm-item">
                    <span class="text-muted">${esc(i.recompensa.marca)} — ${esc(i.recompensa.nombre)} <span class="badge bg-secondary">×${i.cantidad}</span></span>
                    <strong class="text-success">${(i.recompensa.puntos_coste * i.cantidad).toLocaleString('es-ES')} pts</strong>
                </div>`;
        });
        resumen += `</div>
            <div class="d-flex justify-content-between fw-bold fs-5 py-2 border-top" style="border-color:#e8f5e9!important;">
                <span>Total a descontar</span>
                <span class="text-danger">${total.toLocaleString('es-ES')} pts</span>
            </div>
            <div class="d-flex justify-content-between py-1">
                <span class="text-muted small">Tu saldo actual</span>
                <span class="small fw-semibold">${saldo.toLocaleString('es-ES')} pts</span>
            </div>
            <div class="d-flex justify-content-between py-1">
                <span class="text-muted small">Saldo resultante</span>
                <span class="small fw-bold ${tras < 0 ? 'text-danger' : 'text-success'}">${tras.toLocaleString('es-ES')} pts</span>
            </div>`;

        document.getElementById('confirmBody').innerHTML = resumen;
        closeCart();
        confirmModal.show();
    });

    // ── Enviar canje a la API (BUG FIX: esperar hidden.bs.modal) ────────
    //    Es crucial esperar a que el modal de confirmación se cierre
    //    completamente (hidden.bs.modal) antes de mostrar el modal de éxito.
    //    Sin esto, Bootstrap puede dejar el backdrop activo y el éxito no se ve.
    document.getElementById('confirmCheckoutBtn').addEventListener('click', async function () {
        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Procesando…';

        // Leer CSRF antes de que el modal cambie el DOM
        const csrfToken = document.querySelector('[name="csrf_token"]')?.value ?? '';

        const items = carrito.map(i => ({
            recompensa_id: i.recompensa.id,
            cantidad:      i.cantidad,
        }));

        // Helper para mostrar error en la página
        function mostrarError(msg) {
            const alertBox = document.getElementById('tiendaAlert');
            alertBox.className = 'alert alert-danger alert-dismissible fade show';
            alertBox.innerHTML = `
                <i class="bi bi-exclamation-triangle-fill me-2"></i>${msg}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        try {
            const res  = await fetch('api/recompensas.php?action=checkout', {
                method:  'POST',
                headers: { 'Content-Type': 'application/json' },
                body:    JSON.stringify({ csrf_token: csrfToken, items }),
            });
            const json = await res.json();

            if (json.success) {
                // Actualizar saldo en la página
                saldo = json.puntos_totales;
                document.getElementById('saldoDisplay').textContent = saldo.toLocaleString('es-ES');
                const headerBadge = document.querySelector('.badge-points .fw-bold');
                if (headerBadge) headerBadge.textContent = saldo.toLocaleString('es-ES') + ' pts';

                // Vaciar carrito y refrescar catálogo con nuevo saldo
                carrito = [];
                updateCart();
                renderCatalogo(catalogo);

                // Preparar HTML de códigos
                let codigoHtml = json.codigos.map(c => `
                    <div class="mb-3 p-3" style="background:#f0fdf4;border-radius:12px;border:1px solid #d1fae5;">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="fw-semibold text-dark">${esc(c.marca)} — ${esc(c.nombre)}</span>
                            <span class="badge ms-auto" style="background:linear-gradient(135deg,#16a34a,#0d9488);">${esc(String(c.puntos))} pts</span>
                        </div>
                        <div class="code-box position-relative" onclick="copiarCodigo(this, '${esc(c.codigo)}')">
                            <span class="code-copied">¡Copiado!</span>
                            ${esc(c.codigo)}
                            <i class="bi bi-clipboard ms-2 text-muted" style="font-size:.8rem"></i>
                        </div>
                    </div>
                `).join('');

                codigoHtml += `<div class="alert alert-info alert-sm mt-2" style="font-size:.82rem;border-radius:10px;">
                    <i class="bi bi-info-circle me-1"></i>
                    Haz clic en cada código para copiarlo. También están en
                    <a href="index.php?action=mis_canjes" class="fw-semibold">Mis Canjes</a>.
                </div>`;

                document.getElementById('successBody').innerHTML = codigoHtml;

                // ⭐ FIX PRINCIPAL: esperar a que el confirmModal termine de cerrarse
                //    antes de mostrar el successModal. Sin esto, Bootstrap
                //    puede dejar el backdrop activo y el success modal no se muestra.
                const confirmEl = document.getElementById('confirmModal');
                function onConfirmHidden() {
                    confirmEl.removeEventListener('hidden.bs.modal', onConfirmHidden);
                    successModal.show();
                }
                confirmEl.addEventListener('hidden.bs.modal', onConfirmHidden);
                confirmModal.hide();

            } else {
                // Error: cerrar confirm y mostrar alerta
                confirmModal.hide();
                mostrarError(json.message || 'No se pudo completar el canje. Inténtalo más tarde.');
            }

        } catch (err) {
            confirmModal.hide();
            mostrarError('Error de red. Comprueba tu conexión e inténtalo de nuevo.');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-lg me-1"></i>Sí, canjear';
        }
    });

    // ── Copiar código de recompensa al portapapeles ─────────────────
    window.copiarCodigo = function (el, codigo) {
        navigator.clipboard.writeText(codigo).then(() => {
            el.classList.add('just-copied');
            setTimeout(() => el.classList.remove('just-copied'), 1800);
        });
    };
});
</script>

<?php
// Token CSRF colocado fuera del formulario (el checkout se hace vía fetch JS).
// Se lee desde el DOM mediante querySelector en el script de checkout.
require_once __DIR__ . "/../helpers/CsrfHelper.php";
echo '<input type="hidden" name="csrf_token" value="' .
    htmlspecialchars(CsrfHelper::generateToken()) .
    '">';

include __DIR__ . "/partials/footer.php";
?>