</main>

<!-- ── Pie de página ───────────────────────────────────────── -->
<footer class="gp-footer mt-auto">

    <!-- Línea decorativa superior con gradiente -->
    <div class="gp-footer-topline"></div>

    <div class="container py-5">
        <div class="row g-5">

            <!-- Columna 1: Marca y estadísticas -->
            <div class="col-lg-4 col-md-6">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="gp-footer-logo-icon">
                        <i class="bi bi-recycle"></i>
                    </div>
                    <span class="fw-800 fs-5 text-white" style="font-weight:800;">GreenPoints</span>
                </div>
                <p class="gp-footer-text mb-4">
                    Liderando la revolución verde a través de la tecnología y el compromiso ciudadano.
                    Cada acción cuenta para un planeta más sostenible.
                </p>

                <!-- Estadísticas rápidas -->
                <div class="row g-2 mb-4">
                    <div class="col-6">
                        <div class="gp-footer-stat-box">
                            <span class="gp-footer-stat-value">+10k</span>
                            <span class="gp-footer-stat-label">Usuarios</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="gp-footer-stat-box">
                            <span class="gp-footer-stat-value">50 T</span>
                            <span class="gp-footer-stat-label">Reciclado</span>
                        </div>
                    </div>
                </div>

                <!-- Iconos de redes sociales -->
                <div class="d-flex gap-2 flex-wrap">
                    <a href="https://www.facebook.com/?locale=es_ES" class="gp-social-icon" title="Facebook" target="_blank" rel="noopener">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <a href="https://www.instagram.com/" class="gp-social-icon" title="Instagram" target="_blank" rel="noopener">
                        <i class="bi bi-instagram"></i>
                    </a>
                    <a href="https://x.com/" class="gp-social-icon" title="Twitter/X" target="_blank" rel="noopener">
                        <i class="bi bi-twitter-x"></i>
                    </a>
                    <a href="https://www.linkedin.com/" class="gp-social-icon" title="LinkedIn" target="_blank" rel="noopener">
                        <i class="bi bi-linkedin"></i>
                    </a>
                    <a href="https://www.youtube.com/" class="gp-social-icon" title="YouTube" target="_blank" rel="noopener">
                        <i class="bi bi-youtube"></i>
                    </a>
                </div>
            </div>

            <!-- Columna 2: Enlaces de la aplicación -->
            <div class="col-lg-2 col-md-3 col-6">
                <h6 class="gp-footer-heading">Aplicación</h6>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="index.php?action=home"            class="gp-footer-link"><i class="bi bi-chevron-right"></i>Inicio</a></li>
                    <li class="mb-2"><a href="index.php?action=ranking"         class="gp-footer-link"><i class="bi bi-chevron-right"></i>Ranking</a></li>
                    <li class="mb-2"><a href="index.php?action=centros"         class="gp-footer-link"><i class="bi bi-chevron-right"></i>Centros</a></li>
                    <li class="mb-2"><a href="index.php?action=tienda"          class="gp-footer-link"><i class="bi bi-chevron-right"></i>Recompensas</a></li>
                    <li class="mb-2"><a href="index.php?action=registro_create" class="gp-footer-link"><i class="bi bi-chevron-right"></i>Registrar</a></li>
                </ul>
            </div>

            <!-- Columna 3: Soporte -->
            <div class="col-lg-2 col-md-3 col-6">
                <h6 class="gp-footer-heading">Soporte</h6>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="index.php?action=ayuda" class="gp-footer-link"><i class="bi bi-chevron-right"></i>Ayuda</a></li>
                    <li class="mb-2"><a href="index.php?action=faq" class="gp-footer-link"><i class="bi bi-chevron-right"></i>FAQ</a></li>
                    <li class="mb-2"><a href="index.php?action=privacidad" class="gp-footer-link"><i class="bi bi-chevron-right"></i>Privacidad</a></li>
                    <li class="mb-2"><a href="index.php?action=terminos" class="gp-footer-link"><i class="bi bi-chevron-right"></i>Términos</a></li>
                    <li class="mb-2"><a href="index.php?action=contacto" class="gp-footer-link"><i class="bi bi-chevron-right"></i>Contacto</a></li>
                </ul>
            </div>

            <!-- Columna 4: Boletín informativo -->
            <div class="col-lg-4 col-md-6">
                <h6 class="gp-footer-heading">Newsletter</h6>
                <p class="gp-footer-text mb-3">
                    Suscríbete para recibir noticias sobre sostenibilidad y novedades de GreenPoints.
                </p>

                <form class="mb-4" onsubmit="event.preventDefault(); this.querySelector('button').innerHTML='<i class=\'bi bi-check-lg\'></i> ¡Suscrito!'; this.querySelector('button').disabled=true;">
                    <div class="gp-newsletter-group">
                        <input type="email" class="gp-newsletter-input" placeholder="tu@email.com" required>
                        <button type="submit" class="gp-newsletter-btn">
                            <i class="bi bi-send-fill"></i>
                        </button>
                    </div>
                </form>

                <!-- Información de contacto -->
                <div class="d-flex flex-column gap-2">
                    <div class="gp-contact-item">
                        <i class="bi bi-envelope-fill"></i>
                        <span>info@greenpoints.com</span>
                    </div>
                    <div class="gp-contact-item">
                        <i class="bi bi-telephone-fill"></i>
                        <span>+34 900 123 456</span>
                    </div>
                    <div class="gp-contact-item">
                        <i class="bi bi-geo-alt-fill"></i>
                        <span>Madrid, España</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- Divisor -->
        <div class="gp-footer-divider my-4"></div>

        <!-- Barra inferior -->
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start mb-2 mb-md-0">
                <p class="gp-footer-copy mb-0">
                    &copy; <?= date(
                        "Y",
                    ) ?> GreenPoints. Todos los derechos reservados.
                </p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <p class="gp-footer-copy mb-0">
                    Hecho con <i class="bi bi-heart-fill text-danger"></i> por el planeta
                    <i class="bi bi-globe text-success"></i>
                </p>
            </div>
        </div>
    </div>

    <!-- Botón flotante para volver arriba -->
    <button id="scrollTopBtn" class="gp-scroll-top" onclick="window.scrollTo({top:0,behavior:'smooth'})" aria-label="Volver arriba">
        <i class="bi bi-arrow-up-short"></i>
    </button>

</footer>

<style>
/* ── Estilos del pie de página ────────────────────────────── */
.gp-footer {
    background: linear-gradient(165deg,
        #020c05 0%,
        #041208 40%,
        #051a0c 70%,
        #020c05 100%);
    position: relative;
    overflow: hidden;
}
.gp-footer::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background:
        radial-gradient(ellipse at 20% 30%, rgba(22,163,74,0.07) 0%, transparent 55%),
        radial-gradient(ellipse at 80% 70%, rgba(13,148,136,0.07) 0%, transparent 55%);
    pointer-events: none;
}

.gp-footer-topline {
    height: 3px;
    background: linear-gradient(90deg,
        transparent 0%,
        #16a34a 20%,
        #0d9488 50%,
        #22c55e 80%,
        transparent 100%);
}

.gp-footer-logo-icon {
    width: 38px; height: 38px;
    background: linear-gradient(135deg, #22c55e, #0d9488);
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; color: #fff;
    box-shadow: 0 4px 14px rgba(34,197,94,0.35);
    flex-shrink: 0;
}

.gp-footer-text { color: rgba(255,255,255,0.58); font-size: 0.88rem; line-height: 1.65; }

.gp-footer-stat-box {
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 10px;
    padding: 0.65rem 0.75rem;
    text-align: center;
    display: flex; flex-direction: column; gap: 0.1rem;
}
.gp-footer-stat-value { font-weight: 700; font-size: 1rem; color: #22c55e; }
.gp-footer-stat-label { font-size: 0.72rem; color: rgba(255,255,255,0.45); }

.gp-social-icon {
    width: 38px; height: 38px;
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    color: rgba(255,255,255,0.65);
    font-size: 1rem;
    text-decoration: none;
    transition: all 0.3s cubic-bezier(.175,.885,.32,1.275);
}
.gp-social-icon:hover {
    background: linear-gradient(135deg, #22c55e, #0d9488);
    border-color: transparent;
    color: #fff;
    transform: translateY(-4px) scale(1.1);
    box-shadow: 0 8px 20px rgba(34,197,94,0.4);
}

.gp-footer-heading {
    font-size: 0.78rem;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: rgba(255,255,255,0.85);
    margin-bottom: 1.1rem;
}
.gp-footer-link {
    display: flex;
    align-items: center;
    gap: 0.3rem;
    color: rgba(255,255,255,0.52);
    text-decoration: none;
    font-size: 0.875rem;
    transition: all 0.25s ease;
}
.gp-footer-link:hover {
    color: #22c55e;
    gap: 0.55rem;
}
.gp-footer-link i { font-size: 0.65rem; }

.gp-newsletter-group {
    display: flex;
    gap: 0;
    background: rgba(255,255,255,0.06);
    border: 1px solid rgba(255,255,255,0.12);
    border-radius: 50px;
    overflow: hidden;
    padding: 3px 3px 3px 14px;
    transition: border-color 0.25s ease;
}
.gp-newsletter-group:focus-within { border-color: rgba(34,197,94,0.5); }
.gp-newsletter-input {
    flex: 1;
    background: transparent;
    border: none;
    outline: none;
    color: #fff;
    font-size: 0.875rem;
    font-family: 'Poppins', sans-serif;
    min-width: 0;
}
.gp-newsletter-input::placeholder { color: rgba(255,255,255,0.35); }
.gp-newsletter-btn {
    background: linear-gradient(135deg, #22c55e, #0d9488);
    border: none;
    border-radius: 50px;
    width: 38px; height: 38px;
    color: #fff;
    font-size: 0.95rem;
    cursor: pointer;
    flex-shrink: 0;
    transition: all 0.25s ease;
    display: flex; align-items: center; justify-content: center;
}
.gp-newsletter-btn:hover { box-shadow: 0 4px 14px rgba(34,197,94,0.5); }

.gp-contact-item {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    font-size: 0.845rem;
    color: rgba(255,255,255,0.52);
}
.gp-contact-item i { color: #22c55e; flex-shrink: 0; }

.gp-footer-divider {
    height: 1px;
    background: rgba(255,255,255,0.07);
}

.gp-footer-copy {
    font-size: 0.8rem;
    color: rgba(255,255,255,0.35);
}

/* Botón de volver arriba */
.gp-scroll-top {
    position: fixed;
    bottom: 1.5rem;
    right: 1.5rem;
    width: 46px; height: 46px;
    background: linear-gradient(135deg, #22c55e, #0d9488);
    border: none;
    border-radius: 12px;
    color: #fff;
    font-size: 1.5rem;
    display: none;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 999;
    box-shadow: 0 6px 20px rgba(34,197,94,0.45);
    transition: all 0.3s cubic-bezier(.175,.885,.32,1.275);
}
.gp-scroll-top:hover {
    transform: translateY(-4px) scale(1.08);
    box-shadow: 0 10px 28px rgba(34,197,94,0.6);
}
.gp-scroll-top.visible { display: flex; }

@media (max-width: 575.98px) {
    .gp-footer .row.g-5 { --bs-gutter-y: 2rem; }
    .gp-scroll-top { bottom: 1rem; right: 1rem; width: 40px; height: 40px; font-size: 1.3rem; }
}
</style>

<!-- JavaScript de Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
/* ── Navbar: scroll, enlaces activos, dropdown móvil ─────────── */
(function () {
    const nav       = document.getElementById('mainNavbar');
    const scrollBtn = document.getElementById('scrollTopBtn');
    let ticking     = false;

    /* Efecto de desplazamiento — clase .scrolled
       Usamos requestAnimationFrame para limitar las ejecuciones y
       evitar reflows innecesarios durante el scroll (optimización de rendimiento) */
    function onScroll() {
        if (!ticking) {
            requestAnimationFrame(function () {
                nav.classList.toggle('scrolled', window.scrollY > 60);
                scrollBtn.classList.toggle('visible', window.scrollY > 320);
                ticking = false;
            });
            ticking = true;
        }
    }
    window.addEventListener('scroll', onScroll, { passive: true });

    /* Marcar link activo según la URL actual */
    const currentHref = window.location.href;
    document.querySelectorAll('.gp-nav-link').forEach(link => {
        const href = link.getAttribute('href');
        if (href && currentHref.includes(href.split('?')[1] || '__NONE__')) {
            link.classList.add('active');
        }
    });

    /* En móvil: quitar dropdown-menu-end para que no se salga del viewport
       Bootstrap añade dropdown-menu-end para alinear a la derecha, pero en
       pantallas pequeñas esto hace que el menú se desborde horizontalmente.
       Guardamos el estado original en dataset.hadEnd para restaurarlo al
       redimensionar de vuelta a escritorio. */
    function fixMobileDropdowns() {
        const isMobile = window.innerWidth < 992;
        document.querySelectorAll('.dropdown-menu.dropdown-menu-end').forEach(menu => {
            if (isMobile) {
                menu.classList.remove('dropdown-menu-end');
                menu.dataset.hadEnd = 'true';
            } else if (menu.dataset.hadEnd) {
                menu.classList.add('dropdown-menu-end');
                delete menu.dataset.hadEnd;
            }
        });
    }
    fixMobileDropdowns();
    window.addEventListener('resize', fixMobileDropdowns);

    /* En móvil: cerrar el collapse cuando se hace clic en un link */
    document.querySelectorAll('.gp-nav-link').forEach(link => {
        link.addEventListener('click', function () {
            const collapse = document.getElementById('navbarNav');
            if (collapse && collapse.classList.contains('show')) {
                const bsCollapse = bootstrap.Collapse.getInstance(collapse);
                if (bsCollapse) bsCollapse.hide();
            }
        });
    });

    /* Auto-cerrar alertas después de 5s para evitar que se acumulen
       en la interfaz y el usuario tenga que descartarlas manualmente */
    document.querySelectorAll('.alert.alert-dismissible').forEach(el => {
        setTimeout(() => {
            try { new bootstrap.Alert(el).close(); } catch (_) {}
        }, 5000);
    });
})();
</script>
</body>
</html>
