<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GreenPoints | Recicla y Gana</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <link rel="stylesheet" href="../css/estilos.css">
    
    <style>
        /* Clases de utilidad personalizadas */
        .letter-spacing {
            letter-spacing: 2px;
        }
        
        .fw-500 {
            font-weight: 500;
        }
        
        @media (max-width: 768px) {
            .ps-lg-5 {
                padding-left: 0 !important;
                margin-top: 30px;
            }
        }
    </style>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="main-nav">
        <div class="container">
            <a class="navbar-brand fw-bold fs-3" href="index.php?action=home">
                <i class="bi bi-leaf-fill text-success"></i> GreenPoints
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link px-3" href="#inicio">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link px-3" href="#servicios">Servicios</a></li>
                    <li class="nav-item"><a class="nav-link px-3" href="index.php?action=ranking">Ranking</a></li>
                    <li class="nav-item"><a class="nav-link px-3" href="index.php?action=centros">Centros</a></li>
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-success rounded-pill px-4 shadow-sm" href="index.php?action=login">Iniciar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="hero d-flex align-items-center text-center text-white" id="inicio">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h1 class="display-2 fw-bold mb-3 animate__animated animate__fadeInDown">Convierte tus residuos en <span class="text-success">premios</span></h1>
                    <p class="lead mb-5 animate__animated animate__fadeInUp opacity-90">
                        Únete a la comunidad de <strong>GreenPoints</strong>. Registra tu reciclaje, compite con amigos y ayuda a salvar el planeta mientras ganas beneficios exclusivos.
                    </p>
                    <div class="d-grid gap-3 d-sm-flex justify-content-sm-center animate__animated animate__zoomIn">
                        <a href="index.php?action=register" class="btn btn-success btn-lg px-5 py-3 rounded-pill fw-bold">Empezar a Reciclar</a>
                        <a href="#impacto" class="btn btn-outline-light btn-lg px-5 py-3 rounded-pill">Ver Impacto</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section class="py-5 bg-white shadow-sm border-0 stats-section">
        <div class="container text-center">
            <div class="row g-4">
                <div class="col-md-4 col-sm-6">
                    <div class="py-3">
                        <h3 class="fw-bold text-primary mb-2">+10k</h3>
                        <p class="text-muted mb-0 fw-500">Usuarios Activos</p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="py-3">
                        <h3 class="fw-bold text-primary mb-2">50 Ton</h3>
                        <p class="text-muted mb-0 fw-500">Material Reciclado</p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="py-3">
                        <h3 class="fw-bold text-primary mb-2">120</h3>
                        <p class="text-muted mb-0 fw-500">Puntos de Recogida</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="servicios" class="py-5 bg-light services-section">
        <div class="container py-lg-5">
            <div class="text-center mb-5">
                <h6 class="text-success fw-bold text-uppercase letter-spacing">¿Cómo funciona?</h6>
                <h2 class="display-5 fw-bold mt-3">Nuestros Servicios Clave</h2>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 p-4 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="bg-success bg-opacity-10 p-3 rounded-circle d-inline-block mb-4">
                                <i class="bi bi-box-seam text-success fs-2"></i>
                            </div>
                            <h4 class="fw-bold">Registro Ágil</h4>
                            <p class="text-muted">Escanea o sube tus tickets de reciclaje de plástico, papel o vidrio de forma inmediata.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 p-4 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="bg-warning bg-opacity-10 p-3 rounded-circle d-inline-block mb-4">
                                <i class="bi bi-trophy text-warning fs-2"></i>
                            </div>
                            <h4 class="fw-bold">Gamificación</h4>
                            <p class="text-muted">Sube de nivel en el ranking nacional y desbloquea insignias por tus logros ambientales.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 p-4 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-circle d-inline-block mb-4">
                                <i class="bi bi-geo-alt text-primary fs-2"></i>
                            </div>
                            <h4 class="fw-bold">Mapa de Centros</h4>
                            <p class="text-muted">Encuentra los contenedores y centros de acopio más cercanos a tu ubicación actual.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="impacto" class="py-5 impact-section">
        <div class="container py-lg-5">
            <div class="row align-items-center g-4 g-lg-5">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <img src="https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=800&q=80" alt="Reciclaje comunitario" class="img-fluid rounded-4">
                </div>
                <div class="col-lg-6 ps-lg-4 ps-lg-5">
                    <h2 class="display-5 fw-bold mb-4">Por qué elegir GreenPoints</h2>
                    <p class="lead text-secondary mb-4">
                        Transformamos la sostenibilidad en una experiencia social. No solo reciclas, sino que ves el impacto real de tus acciones.
                    </p>
                    <ul class="list-unstyled">
                        <li class="mb-4 d-flex align-items-start">
                            <i class="bi bi-check-circle-fill text-success flex-shrink-0 mt-1"></i>
                            <span class="ms-3">Canjea puntos por descuentos en comercios locales.</span>
                        </li>
                        <li class="mb-4 d-flex align-items-start">
                            <i class="bi bi-check-circle-fill text-success flex-shrink-0 mt-1"></i>
                            <span class="ms-3">Estadísticas detalladas de tu huella de carbono reducida.</span>
                        </li>
                    </ul>
                    <a href="index.php?action=home" class="btn btn-primary rounded-pill px-5 mt-4">Descubre nuestra misión</a>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-dark text-white pt-5 pb-3 footer-section">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <h5 class="fw-bold mb-4"><i class="bi bi-leaf-fill text-success me-2"></i>GreenPoints</h5>
                    <p class="text-muted small mb-4">Liderando la revolución verde a través de la tecnología y el compromiso ciudadano.</p>
                    <div class="d-flex gap-3 mt-4 social-links">
                        <a href="#" class="text-white fs-5" title="Facebook"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-white fs-5" title="Instagram"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="text-white fs-5" title="Twitter"><i class="bi bi-twitter-x"></i></a>
                    </div>
                </div>
                <div class="col-6 col-lg-2 ms-lg-auto mb-4 mb-lg-0">
                    <h6 class="fw-bold mb-4">App</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none small">Ranking</a></li>
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none small">Centros</a></li>
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none small">Premios</a></li>
                    </ul>
                </div>
                <div class="col-6 col-lg-2">
                    <h6 class="fw-bold mb-4">Soporte</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none small">Ayuda</a></li>
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none small">Privacidad</a></li>
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none small">Contacto</a></li>
                    </ul>
                </div>
            </div>
            <hr class="mt-5 opacity-10">
            <p class="text-center text-muted small mt-4 mb-0">© 2025 GreenPoints. Hecho con ❤️ por el planeta.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Efecto de scroll para el navbar
        window.addEventListener('scroll', function() {
            const nav = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                nav.classList.add('scrolled', 'shadow');
            } else {
                nav.classList.remove('scrolled', 'shadow');
            }
        });
        // Animación de números contadores
        function animateCounter(element, target, duration = 2000) {
            let current = 0;
            const increment = target / (duration / 16);
            
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    element.textContent = target;
                    clearInterval(timer);
                } else {
                    element.textContent = Math.floor(current);
                }
            }, 16);
        }

        // Observador para activar animaciones cuando las secciones entran en vista
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -100px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !entry.target.classList.contains('animated')) {
                    entry.target.classList.add('animated');
                    
                    // Animar números si es la sección de estadísticas
                    if (entry.target.querySelector('h3')) {
                        const numbers = entry.target.querySelectorAll('h3');
                        numbers.forEach(num => {
                            const text = num.textContent.match(/\\d+/);
                            if (text) {
                                const target = parseInt(text[0]);
                                animateCounter(num, target);
                            }
                        });
                    }
                }
            });
        }, observerOptions);

        // Observar secciones
        document.querySelectorAll('section').forEach(section => {
            observer.observe(section);
        });

        // Efecto parallax mejorado en el hero
        const hero = document.querySelector('.hero');
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            if (hero && scrolled < hero.offsetHeight) {
                hero.style.backgroundPosition = `center ${scrolled * 0.5}px`;
            }
        });

        // Mejorar comportamiento de botones
        const buttons = document.querySelectorAll('.btn');
        buttons.forEach(button => {
            button.addEventListener('click', function(e) {
                const ripple = document.createElement('span');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                
                ripple.style.width = ripple.style.height = size + 'px';
                ripple.style.left = x + 'px';
                ripple.style.top = y + 'px';
                this.appendChild(ripple);
                
                setTimeout(() => ripple.remove(), 600);
            });
        });    </script>
</body>
</html>