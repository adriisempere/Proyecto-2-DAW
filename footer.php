</main>

<footer class="bg-dark text-white pt-5 pb-3 mt-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                <h5 class="fw-bold mb-4 text-white">
                    <i class="bi bi-leaf-fill text-success me-2"></i>
                    GreenPoints
                </h5>
                <p class="mb-4" style="color: rgba(255,255,255,0.8);">
                    Liderando la revolución verde a través de la tecnología y el compromiso ciudadano. 
                    Cada acción cuenta para un planeta más sostenible.
                </p>
                
                <div class="row text-center mb-4">
                    <div class="col-6">
                        <div class="stat-box p-3 rounded border border-success border-opacity-25" style="background: rgba(40, 167, 69, 0.15);">
                            <h5 class="text-success fw-bold mb-0">+10k</h5>
                            <small class="text-white-50">Usuarios</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-box p-3 rounded border border-success border-opacity-25" style="background: rgba(32, 201, 151, 0.15);">
                            <h5 class="text-success fw-bold mb-0">50 Ton</h5>
                            <small class="text-white-50">Reciclado</small>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex gap-3">
                    <a href="#" class="social-link text-white fs-4" title="Facebook" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="social-link text-white fs-4" title="Instagram" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="social-link text-white fs-4" title="Twitter/X" aria-label="Twitter"><i class="bi bi-twitter-x"></i></a>
                    <a href="#" class="social-link text-white fs-4" title="LinkedIn" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-6 col-6 mb-4 mb-lg-0">
                <h6 class="fw-bold mb-4 text-white">Aplicación</h6>
                <ul class="list-unstyled">
                    <li><a href="index.php?action=home" class="footer-link text-decoration-none d-block mb-2" style="color: rgba(255,255,255,0.7);"><i class="bi bi-chevron-right me-1 small"></i>Inicio</a></li>
                    <li><a href="index.php?action=ranking" class="footer-link text-decoration-none d-block mb-2" style="color: rgba(255,255,255,0.7);"><i class="bi bi-chevron-right me-1 small"></i>Ranking</a></li>
                    <li><a href="index.php?action=centros" class="footer-link text-decoration-none d-block mb-2" style="color: rgba(255,255,255,0.7);"><i class="bi bi-chevron-right me-1 small"></i>Centros</a></li>
                </ul>
            </div>
            
            <div class="col-lg-2 col-md-6 col-6 mb-4 mb-lg-0">
                <h6 class="fw-bold mb-4 text-white">Soporte</h6>
                <ul class="list-unstyled">
                    <li><a href="#" class="footer-link text-decoration-none d-block mb-2" style="color: rgba(255,255,255,0.7);"><i class="bi bi-chevron-right me-1 small"></i>Ayuda</a></li>
                    <li><a href="#" class="footer-link text-decoration-none d-block mb-2" style="color: rgba(255,255,255,0.7);"><i class="bi bi-chevron-right me-1 small"></i>FAQ</a></li>
                    <li><a href="#" class="footer-link text-decoration-none d-block mb-2" style="color: rgba(255,255,255,0.7);"><i class="bi bi-chevron-right me-1 small"></i>Contacto</a></li>
                </ul>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                <h6 class="fw-bold mb-4 text-white">Newsletter</h6>
                <p class="small mb-3" style="color: rgba(255,255,255,0.8);">Suscríbete para recibir noticias sobre sostenibilidad.</p>
                
                <form class="mb-4" id="newsletterForm">
                    <div class="input-group">
                        <input type="email" class="form-control bg-transparent text-white border-secondary" placeholder="tu@email.com" style="border-radius: 25px 0 0 25px;" required>
                        <button class="btn btn-success" type="submit" style="border-radius: 0 25px 25px 0;">
                            <i class="bi bi-send-fill"></i>
                        </button>
                    </div>
                </form>
                
                <div class="contact-info small" style="color: rgba(255,255,255,0.8);">
                    <p class="mb-2"><i class="bi bi-envelope-fill text-success me-2"></i>info@greenpoints.com</p>
                    <p class="mb-0"><i class="bi bi-geo-alt-fill text-success me-2"></i>Madrid, España</p>
                </div>
            </div>
        </div>
        
        <hr class="mt-4 mb-3" style="opacity: 0.1;">
        
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <p class="small mb-0" style="color: rgba(255,255,255,0.6);">
                    © <?= date('Y') ?> GreenPoints. Todos los derechos reservados.
                </p>
            </div>
            <div class="col-md-6 text-center text-md-end d-none d-md-block">
                <p class="small mb-0" style="color: rgba(255,255,255,0.6);">
                    Hecho con <i class="bi bi-heart-fill text-danger"></i> por el planeta <i class="bi bi-globe text-success"></i>
                </p>
            </div>
        </div>
        
        <button id="scrollTopBtn" class="btn btn-success rounded-circle position-fixed shadow-lg" 
                style="width: 45px; height: 45px; bottom: 20px; right: 20px; display: none; z-index: 1050;"
                aria-label="Volver arriba">
            <i class="bi bi-arrow-up-short fs-4"></i>
        </button>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Manejo del Scroll (Optimizado)
        const scrollBtn = document.getElementById('scrollTopBtn');
        const navbar = document.getElementById('mainNavbar');

        window.addEventListener('scroll', function() {
            const scrollPos = window.scrollY;
            
            // Toggle Navbar class (solo si existe el elemento)
            if (navbar) {
                navbar.classList.toggle('scrolled', scrollPos > 50);
            }
            
            // Toggle Scroll Button
            scrollBtn.style.display = scrollPos > 300 ? 'block' : 'none';
        });

        scrollBtn.addEventListener('click', () => {
            window.scrollTo({top: 0, behavior: 'smooth'});
        });

        // 2. Marcar enlace activo (Lógica mejorada usando URLSearchParams)
        const currentParams = new URLSearchParams(window.location.search);
        const currentAction = currentParams.get('action');
        
        if (currentAction) {
            document.querySelectorAll('.nav-link').forEach(link => {
                if (link.href.includes(`action=${currentAction}`)) {
                    link.classList.add('active');
                }
            });
        }

        // 3. Newsletter Alert (Mejorado)
        const newsForm = document.getElementById('newsletterForm');
        if (newsForm) {
            newsForm.addEventListener('submit', (e) => {
                e.preventDefault();
                alert('¡Gracias por unirte a la revolución verde!');
                newsForm.reset();
            });
        }

        // 4. Auto-cerrar alertas de Bootstrap
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                const bsAlert = bootstrap.Alert.getInstance(alert) || new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    });
</script>