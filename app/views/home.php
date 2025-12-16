<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GreenPoints</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/estilos.css">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="main-nav">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.html">🌱 GreenPoints</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#inicio">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#servicios">Servicios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="ranking.html">Ranking</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="centros.html">Centros</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-success ms-lg-3" href="login.html">Iniciar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="hero d-flex align-items-center" id="inicio">
        <div class="container text-center text-white">
            <h1 class="display-3 fw-bold mb-4 animate__animated animate__fadeInDown">¡Recicla y Gana!</h1>
            <p class="lead mb-5 animate__animated animate__fadeInUp">
                **GreenPoints** es la plataforma web que te **incentiva** a cuidar el planeta. Registra tus materiales reciclados y sube en el **ranking** de la sostenibilidad.
            </p>
            <a href="registro.html" class="btn btn-primary btn-lg me-3 animate__animated animate__zoomIn">Únete Hoy Mismo</a>
            <a href="#servicios" class="btn btn-outline-light btn-lg animate__animated animate__zoomIn">Saber Más</a>
        </div>
    </header>

    <section id="servicios" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5 fw-bold">Nuestros Servicios Clave</h2>
            <div class="row text-center">
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body">
                            <i class="bi bi-recycle text-success fs-1 mb-3"></i>
                            <h5 class="card-title fw-bold">Registro de Materiales</h5>
                            <p class="card-text">Registra la cantidad y tipo de residuos (plástico, papel, vidrio, etc.) que reciclas de forma sencilla.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body">
                            <i class="bi bi-trophy text-warning fs-1 mb-3"></i>
                            <h5 class="card-title fw-bold">Puntos y Ranking</h5>
                            <p class="card-text">Gana puntos por cada aporte y compite en un ranking dinámico con otros usuarios comprometidos.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body">
                            <i class="bi bi-geo-alt-fill text-danger fs-1 mb-3"></i>
                            <h5 class="card-title fw-bold">Centros Cercanos</h5>
                            <p class="card-text">Localiza centros de reciclaje, consulta sus horarios y tipos de materiales aceptados.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center mt-4">
                <a href="servicios.html" class="btn btn-outline-dark">Ver Todos los Módulos</a>
            </div>
        </div>
    </section>
    
    <section id="impacto" class="py-5 bg-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="fw-bold mb-4">¿Por qué GreenPoints?</h2>
                    <p class="lead text-secondary">
                        El creciente problema de la contaminación requiere soluciones tecnológicas que promuevan hábitos sostenibles. GreenPoints transforma el acto de reciclar en una **experiencia interactiva y gratificante**.
                    </p>
                    <ul class="list-group list-group-flush mb-4">
                        <li class="list-group-item"><i class="bi bi-check-circle-fill text-success me-2"></i> Refuerza la conciencia ecológica colectiva.</li>
                        <li class="list-group-item"><i class="bi bi-check-circle-fill text-success me-2"></i> Permite a las entidades obtener datos reales de participación.</li>
                        <li class="list-group-item"><i class="bi bi-check-circle-fill text-success me-2"></i> Combina tecnología, sostenibilidad y gamificación.</li>
                    </ul>
                    <a href="acercade.html" class="btn btn-secondary">Conoce Nuestra Misión</a>
                </div>
                <div class="col-lg-6 mt-4 mt-lg-0 text-center">
                    
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <h5 class="fw-bold">GreenPoints</h5>
                    <p class="small text-muted">Proyecto de Desarrollo de Aplicaciones Web. Transformando el reciclaje en un juego de puntos.</p>
                </div>
                <div class="col-md-4 mb-3">
                    <h5 class="fw-bold">Enlaces Rápidos</h5>
                    <ul class="list-unstyled">
                        <li><a href="index.html" class="text-white text-decoration-none small">Inicio</a></li>
                        <li><a href="ranking.html" class="text-white text-decoration-none small">Ranking</a></li>
                        <li><a href="login.html" class="text-white text-decoration-none small">Login</a></li>
                        <li><a href="admin/panel.html" class="text-white text-decoration-none small">Panel Admin</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-3">
                    <h5 class="fw-bold">Contacto</h5>
                    <p class="small text-muted">Email: info@greenpoints.es</p>
                    <p class="small text-muted">© 2025 GreenPoints. Todos los derechos reservados.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</body>
</html>