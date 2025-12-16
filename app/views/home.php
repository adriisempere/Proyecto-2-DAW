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
    
    <link rel="stylesheet" href="css/estilos.css">
</head>
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

    <section class="py-4 bg-white shadow-sm border-bottom">
        <div class="container text-center">
            <div class="row">
                <div class="col-md-4 mb-3 mb-md-0">
                    <h3 class="fw-bold text-primary">+10k</h3>
                    <p class="text-muted mb-0">Usuarios Activos</p>
                </div>
                <div class="col-md-4 mb-3 mb-md-0">
                    <h3 class="fw-bold text-primary">50 Ton</h3>
                    <p class="text-muted mb-0">Material Reciclado</p>
                </div>
                <div class="col-md-4">
                    <h3 class="fw-bold text-primary">120</h3>
                    <p class="text-muted mb-0">Puntos de Recogida</p>
                </div>
            </div>
        </div>
    </section>

    <section id="servicios" class="py-5 bg-light">
        <div class="container py-lg-5">
            <div class="text-center mb-5">
                <h6 class="text-success fw-bold text-uppercase">¿Cómo funciona?</h6>
                <h2 class="display-5 fw-bold">Nuestros Servicios Clave</h2>
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

    <section id="impacto" class="py-5">
        <div class="container py-lg-5">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <img src="https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=800&q=80" alt="Reciclaje comunitario" class="img-fluid rounded-4 shadow-lg">
                </div>
                <div class="col-lg-6 ps-lg-5">
                    <h2 class="display-5 fw-bold mb-4">Por qué elegir GreenPoints</h2>
                    <p class="lead text-secondary mb-4">
                        Transformamos la sostenibilidad en una experiencia social. No solo reciclas, sino que ves el impacto real de tus acciones.
                    </p>
                    <ul class="list-unstyled">
                        <li class="mb-3 d-flex align-items-center">
                            <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i>
                            <span>Canjea puntos por descuentos en comercios locales.</span>
                        </li>
                        <li class="mb-3 d-flex align-items-center">
                            <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i>
                            <span>Estadísticas detalladas de tu huella de carbono reducida.</span>
                        </li>
                    </ul>
                    <a href="mision.php" class="btn btn-primary rounded-pill px-4 mt-3">Descubre nuestra misión</a>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-dark text-white pt-5 pb-3">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <h5 class="fw-bold mb-4">🌱 GreenPoints</h5>
                    <p class="text-muted small">Liderando la revolución verde a través de la tecnología y el compromiso ciudadano.</p>
                    <div class="d-flex gap-3 mt-4">
                        <a href="#" class="text-white fs-5"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-white fs-5"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="text-white fs-5"><i class="bi bi-twitter-x"></i></a>
                    </div>
                </div>
                <div class="col-6 col-lg-2 ms-auto">
                    <h6 class="fw-bold mb-4">App</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-muted text-decoration-none small">Ranking</a></li>
                        <li><a href="#" class="text-muted text-decoration-none small">Centros</a></li>
                        <li><a href="#" class="text-muted text-decoration-none small">Premios</a></li>
                    </ul>
                </div>
                <div class="col-6 col-lg-2">
                    <h6 class="fw-bold mb-4">Soporte</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-muted text-decoration-none small">Ayuda</a></li>
                        <li><a href="#" class="text-muted text-decoration-none small">Privacidad</a></li>
                        <li><a href="#" class="text-muted text-decoration-none small">Contacto</a></li>
                    </ul>
                </div>
            </div>
            <hr class="mt-5 opacity-10">
            <p class="text-center text-muted small mt-4">© 2025 GreenPoints. Hecho con ❤️ por el planeta.</p>
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
    </script>
</body>
</html>