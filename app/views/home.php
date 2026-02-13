<?php
$pageTitle = "GreenPoints | Recicla y Gana";
include __DIR__ . '/partials/header.php';
?>

<!-- Hero Section -->
<header class="px-4 py-5 my-5 text-center">
    <img class="d-block mx-lg-auto img-fluid animate-float" src="img/LogoGreenPoints.png" alt="logo del hero" width="160px" height="160px" loading="lazy">
    <h1 class="display-5 fw-bold animate__animated animate__fadeInDown text-gradient">GreenPoints</h1>
    <div class="col-lg-6 mx-auto animate__animated animate__fadeInUp delay-200">
        <p class="lead mb-4">
            Nuestro proyecto trata de concienciar a las personas sobre la importancia de reciclar. Ésto lo hacemos mediante un sistema de puntos y recompensas, además de un ranking para incentivar a la competitividad. Cuantos más puntos tengas, más recompensas podrás obtener.
        </p>
        <p class="lead mb-4 h4">¡Empieza a reciclar hoy mismo!</p>
        <div class="d-grid gap-2 d-sm-flex justify-content-md-center">
            <a href="index.php?action=register" class="btn btn-primary btn-lg px-4 gap-3 btn-pulse">
                <i class="bi bi-person-plus me-2"></i>Registro
            </a>
            <a href="index.php?action=login" class="btn btn-outline-secondary btn-lg px-4">
                <i class="bi bi-box-arrow-in-right me-2"></i>Login
            </a>
        </div>
    </div>
</header>

<!-- Stats Section -->
<section class="py-5 bg-white shadow-sm border-0">
    <div class="container text-center">
        <div class="row g-4">
            <div class="col-md-4 col-sm-6">
                <div class="py-3 animate__animated animate__zoomIn delay-100">
                    <h3 class="fw-bold text-primary mb-2">+10k</h3>
                    <p class="text-muted mb-0 fw-500">Usuarios Activos</p>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="py-3 animate__animated animate__zoomIn delay-200">
                    <h3 class="fw-bold text-primary mb-2">50 Ton</h3>
                    <p class="text-muted mb-0 fw-500">Material Reciclado</p>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="py-3 animate__animated animate__zoomIn delay-300">
                    <h3 class="fw-bold text-primary mb-2">120</h3>
                    <p class="text-muted mb-0 fw-500">Puntos de Recogida</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section id="servicios" class="py-5 bg-light">
    <div class="container py-lg-5">
        <div class="text-center mb-5">
            <h6 class="text-success fw-bold text-uppercase" style="letter-spacing: 2px;">¿Cómo funciona?</h6>
            <h2 class="display-5 fw-bold mt-3">Nuestros Servicios Clave</h2>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 p-4 border-0 shadow-sm hover-lift animate__animated animate__fadeInUp delay-100">
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
                <div class="card h-100 p-4 border-0 shadow-sm hover-lift animate__animated animate__fadeInUp delay-200">
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
                <div class="card h-100 p-4 border-0 shadow-sm hover-lift animate__animated animate__fadeInUp delay-300">
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

<!-- Impact Section -->
<section id="impacto" class="py-5">
    <div class="container py-lg-5">
        <div class="row align-items-center g-4 g-lg-5">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <img src="https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=800&q=80" alt="Reciclaje comunitario" class="img-fluid rounded-4 shadow">
            </div>
            <div class="col-lg-6 ps-lg-4 ps-lg-5">
                <h2 class="display-5 fw-bold mb-4">Por qué elegir GreenPoints</h2>
                <p class="lead text-secondary mb-4">
                    Transformamos la sostenibilidad en una experiencia social. No solo reciclas, sino que ves el impacto real de tus acciones.
                </p>
                <ul class="list-unstyled">
                    <li class="mb-4 d-flex align-items-start">
                        <i class="bi bi-check-circle-fill text-success flex-shrink-0 mt-1 fs-4"></i>
                        <span class="ms-3">Canjea puntos por descuentos en comercios locales.</span>
                    </li>
                    <li class="mb-4 d-flex align-items-start">
                        <i class="bi bi-check-circle-fill text-success flex-shrink-0 mt-1 fs-4"></i>
                        <span class="ms-3">Estadísticas detalladas de tu huella de carbono reducida.</span>
                    </li>
                    <li class="mb-4 d-flex align-items-start">
                        <i class="bi bi-check-circle-fill text-success flex-shrink-0 mt-1 fs-4"></i>
                        <span class="ms-3">Comunidad activa comprometida con el medio ambiente.</span>
                    </li>
                </ul>
                <a href="index.php?action=register" class="btn btn-success btn-lg rounded-pill px-5 mt-4">
                    <i class="bi bi-rocket-takeoff me-2"></i>Únete Ahora
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Section Ranking-Cuenta -->
<section class="py-5 bg-success text-white">
    <div class="container text-center py-5">
        <h2 class="display-4 fw-bold mb-4">¿Listo para hacer la diferencia?</h2>
        <p class="lead mb-4">Únete a miles de usuarios que ya están transformando el mundo</p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="index.php?action=register" class="btn btn-light btn-lg px-5 rounded-pill">
                <i class="bi bi-person-plus me-2"></i>Crear Cuenta Gratis
            </a>
            <a href="index.php?action=ranking" class="btn btn-outline-light btn-lg px-5 rounded-pill">
                <i class="bi bi-trophy me-2"></i>Ver Ranking
            </a>
        </div>
    </div>
</section>

<style>
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

<?php
include __DIR__ . '/partials/footer.php';
?>
