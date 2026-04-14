</main>

<!-- Footer -->
<footer class="bg-dark text-white pt-5 pb-3 mt-5">
    <div class="container">
        <div class="row g-4">
            <!-- Columna 1: Información de la marca -->
            <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                <h5 class="fw-bold mb-4 text-white">
                    <i class="bi bi-leaf-fill text-success me-2"></i>
                    GreenPoints
                </h5>
                <p class="mb-4" style="color: rgba(255,255,255,0.8);">
                    Liderando la revolución verde a través de la tecnología y el compromiso ciudadano. 
                    Cada acción cuenta para un planeta más sostenible.
                </p>
                
                <!-- Estadísticas rápidas -->
                <div class="row text-center mb-4">
                    <div class="col-6">
                        <div class="stat-box p-3 rounded" style="background: rgba(40, 167, 69, 0.15); border: 1px solid rgba(40, 167, 69, 0.3);">
                            <h5 class="text-success fw-bold mb-0">+10k</h5>
                            <small class="text-white-50">Usuarios</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-box p-3 rounded" style="background: rgba(32, 201, 151, 0.15); border: 1px solid rgba(32, 201, 151, 0.3);">
                            <h5 class="text-success fw-bold mb-0">50 Ton</h5>
                            <small class="text-white-50">Reciclado</small>
                        </div>
                    </div>
                </div>
                
                <!-- Redes sociales -->
                <div class="d-flex gap-3">
                    <a href="https://www.facebook.com/?locale=es_ES" class="social-link text-white fs-4" title="Facebook" target="_blank">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <a href="https://www.instagram.com/" class="social-link text-white fs-4" title="Instagram" target="_blank">
                        <i class="bi bi-instagram"></i>
                    </a>
                    <a href="https://x.com/" class="social-link text-white fs-4" title="Twitter/X" target="_blank">
                        <i class="bi bi-twitter-x"></i>
                    </a>
                    <a href="https://www.linkedin.com/" class="social-link text-white fs-4" title="LinkedIn" target="_blank">
                        <i class="bi bi-linkedin"></i>
                    </a>
                    <a href="https://www.youtube.com/" class="social-link text-white fs-4" title="YouTube" target="_blank">
                        <i class="bi bi-youtube"></i>
                    </a>
                </div>
            </div>
            
            <!-- Columna 2: Enlaces rápidos -->
            <div class="col-lg-2 col-md-6 col-6 mb-4 mb-lg-0">
                <h6 class="fw-bold mb-4 text-white">Aplicación</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="index.php?action=home" class="footer-link text-decoration-none" style="color: rgba(255,255,255,0.7);">
                            <i class="bi bi-chevron-right me-1"></i>Inicio
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="index.php?action=ranking" class="footer-link text-decoration-none" style="color: rgba(255,255,255,0.7);">
                            <i class="bi bi-chevron-right me-1"></i>Ranking
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="index.php?action=centros" class="footer-link text-decoration-none" style="color: rgba(255,255,255,0.7);">
                            <i class="bi bi-chevron-right me-1"></i>Centros
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="index.php?action=registro_create" class="footer-link text-decoration-none" style="color: rgba(255,255,255,0.7);">
                            <i class="bi bi-chevron-right me-1"></i>Registrar
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Columna 3: Soporte -->
            <div class="col-lg-2 col-md-6 col-6 mb-4 mb-lg-0">
                <h6 class="fw-bold mb-4 text-white">Soporte</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="#" class="footer-link text-decoration-none" style="color: rgba(255,255,255,0.7);">
                            <i class="bi bi-chevron-right me-1"></i>Ayuda
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="footer-link text-decoration-none" style="color: rgba(255,255,255,0.7);">
                            <i class="bi bi-chevron-right me-1"></i>FAQ
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="footer-link text-decoration-none" style="color: rgba(255,255,255,0.7);">
                            <i class="bi bi-chevron-right me-1"></i>Privacidad
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="footer-link text-decoration-none" style="color: rgba(255,255,255,0.7);">
                            <i class="bi bi-chevron-right me-1"></i>Términos
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="footer-link text-decoration-none" style="color: rgba(255,255,255,0.7);">
                            <i class="bi bi-chevron-right me-1"></i>Contacto
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Columna 4: Newsletter y contacto -->
            <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                <h6 class="fw-bold mb-4 text-white">Newsletter</h6>
                <p class="small mb-3" style="color: rgba(255,255,255,0.8);">
                    Suscríbete para recibir noticias sobre sostenibilidad y novedades de GreenPoints.
                </p>
                
                <form class="mb-4" onsubmit="event.preventDefault(); alert('¡Gracias por suscribirte!');">
                    <div class="input-group">
                        <input 
                            type="email" 
                            class="form-control" 
                            placeholder="tu@email.com"
                            style="border-radius: 25px 0 0 25px; border-right: none;"
                            required
                        >
                        <button 
                            class="btn btn-success" 
                            type="submit"
                            style="border-radius: 0 25px 25px 0;"
                        >
                            <i class="bi bi-send-fill"></i>
                        </button>
                    </div>
                </form>
                
                <!-- Información de contacto -->
                <div class="contact-info">
                    <p class="small mb-2" style="color: rgba(255,255,255,0.8);">
                        <i class="bi bi-envelope-fill text-success me-2"></i>
                        info@greenpoints.com
                    </p>
                    <p class="small mb-2" style="color: rgba(255,255,255,0.8);">
                        <i class="bi bi-telephone-fill text-success me-2"></i>
                        +34 900 123 456
                    </p>
                    <p class="small mb-0" style="color: rgba(255,255,255,0.8);">
                        <i class="bi bi-geo-alt-fill text-success me-2"></i>
                        Madrid, España
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Línea divisoria -->
        <hr class="mt-4 mb-3" style="opacity: 0.1;">
        
        <!-- Copyright y enlaces legales -->
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                <p class="small mb-0" style="color: rgba(255,255,255,0.6);">
                    © <?= date('Y') ?> GreenPoints. Todos los derechos reservados.
                </p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <p class="small mb-0" style="color: rgba(255,255,255,0.6);">
                    Hecho con <i class="bi bi-heart-fill text-danger"></i> por el planeta 
                    <i class="bi bi-globe text-success"></i>
                </p>
            </div>
        </div>
        
        <!-- Botón scroll to top -->
        <button 
            id="scrollTopBtn" 
            class="btn btn-success rounded-circle position-fixed bottom-0 end-0 m-4 shadow-lg animate-float"
            style="width: 50px; height: 50px; display: none; z-index: 1000; border: none; background: var(--primary);"
            onclick="window.scrollTo({top: 0, behavior: 'smooth'});"
        >
            <i class="bi bi-arrow-up-short fs-4 text-white"></i>
        </button>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Logica Premium: Animaciones y Scroll -->
<script>
    // ── 1. Animaciones al hacer Scroll ─────────────────────
    const observerOptions = { threshold: 0.15 };
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('active');
            }
        });
    }, observerOptions);

    document.querySelectorAll('.reveal-on-scroll').forEach(el => observer.observe(el));

    // ── 2. Efectos de Navegación y Scroll ───────────────────
    window.addEventListener('scroll', () => {
        // Mostrar botón subir
        const scrollBtn = document.getElementById('scrollTopBtn');
        if (scrollBtn) {
            scrollBtn.style.display = window.scrollY > 400 ? 'block' : 'none';
        }
    });

    // Marcar página activa
    const url = window.location.href;
    document.querySelectorAll('.nav-link').forEach(link => {
        if (url.includes(link.getAttribute('href'))) {
            link.classList.add('active');
        }
    });
</script>

</body>
</html>

</body>
</html>
