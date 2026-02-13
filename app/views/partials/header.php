<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'GreenPoints - Recicla y Gana' ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <!-- Custom Animations -->
    <link rel="stylesheet" href="css/custom.css">
    
    <!-- Estilos Custom -->
    <style>
        :root {
            --primary-color: #28a745;
            --secondary-color: #20c997;
            --dark-color: #343a40;
            --light-color: #f8fdf9;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-color);
            color: #333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        main {
            flex: 1;
        }
        
        /* Navbar Styles */
        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            padding: 1rem 0;
            transition: all 0.3s ease;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: white !important;
            letter-spacing: -0.5px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .nav-link {
            color: rgba(255,255,255,0.9) !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            border-radius: 50px;
            transition: all 0.3s ease;
        }
        
        .nav-link:hover, .nav-link.active {
            background: rgba(255,255,255,0.2);
            color: white !important;
            transform: translateY(-2px);
        }
        
        /* User Profile Dropdown */
        .user-profile-btn {
            background: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.3);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            transition: all 0.3s;
        }
        
        .user-profile-btn:hover {
            background: white;
            color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        /* Badge Styles */
        .badge-points {
            background: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.3);
            backdrop-filter: blur(5px);
        }

        /* Glassmorphism for Dropdowns */
        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-radius: 15px;
            padding: 1rem;
            animation: fadeIn 0.3s ease;
        }
        
        .dropdown-item {
            border-radius: 10px;
            padding: 0.7rem 1rem;
            transition: all 0.2s;
        }
        
        .dropdown-item:hover {
            background-color: #f8f9fa;
            color: var(--primary-color);
            transform: translateX(5px);
        }
        
        .dropdown-item:active {
            background-color: var(--primary-color);
            color: white;
        }

        /* Mobile Toggle */
        .navbar-toggler {
            border: none;
            background: rgba(255,255,255,0.2);
        }
        
        .navbar-toggler:focus {
            box-shadow: none;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-nav-item {
            animation: fadeIn 0.5s ease backwards;
        }
        
        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }
        
        /* Navbar scroll effect */
        .navbar.scrolled {
            padding: 0.5rem 0;
            background: rgba(40, 167, 69, 0.95);
            backdrop-filter: blur(10px);
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top" id="mainNavbar">
        <div class="container">
            <a class="navbar-brand animate__animated animate__fadeInLeft" href="index.php?action=home">
                <i class="bi bi-recycle me-2"></i>GreenPoints
            </a>
            
            <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="bi bi-list fs-2"></i>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item animate-nav-item delay-1">
                        <a class="nav-link" href="index.php?action=home">
                            <i class="bi bi-house-door me-1"></i>Inicio
                        </a>
                    </li>
                    <li class="nav-item animate-nav-item delay-2">
                        <a class="nav-link" href="index.php?action=ranking">
                            <i class="bi bi-trophy me-1"></i>Ranking
                        </a>
                    </li>
                    <li class="nav-item animate-nav-item delay-3">
                        <a class="nav-link" href="index.php?action=centros">
                            <i class="bi bi-geo-alt me-1"></i>Centros
                        </a>
                    </li>
                </ul>
                
                <div class="d-flex align-items-center animate__animated animate__fadeInRight">
                    <?php if(isset($_SESSION['usuario'])): ?>
                        <!-- User Points Badge -->
                        <div class="badge-points rounded-pill px-3 py-2 text-white me-3 d-none d-md-flex align-items-center">
                            <i class="bi bi-star-fill text-warning me-2"></i>
                            <span class="fw-bold"><?= $_SESSION['usuario']['puntos'] ?? 0 ?> pts</span>
                        </div>

                        <!-- User Profile Dropdown -->
                        <div class="dropdown">
                            <button class="user-profile-btn dropdown-toggle d-flex align-items-center text-decoration-none" 
                                    type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="bg-white text-success rounded-circle d-flex align-items-center justify-content-center me-2" 
                                     style="width: 32px; height: 32px; font-weight: bold;">
                                    <?= strtoupper(substr($_SESSION['usuario']['nombre'], 0, 1)) ?>
                                </div>
                                <span class="d-none d-sm-inline"><?= htmlspecialchars($_SESSION['usuario']['nombre']) ?></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end animate__animated animate__fadeIn" aria-labelledby="userDropdown">
                                <li><h6 class="dropdown-header text-muted">Mi Cuenta</h6></li>
                                <li>
                                    <a class="dropdown-item" href="index.php?action=perfil">
                                        <i class="bi bi-person me-2 text-primary"></i>Perfil
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="index.php?action=mis_registros">
                                        <i class="bi bi-clock-history me-2 text-info"></i>Mis Registros
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="index.php?action=registro_create">
                                        <i class="bi bi-plus-circle me-2 text-success"></i>Registrar Puntos
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="index.php?action=logout">
                                        <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
                                    </a>
                                </li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <!-- Login/Register Buttons -->
                        <div class="d-flex gap-2">
                            <a href="index.php?action=login" class="btn btn-outline-light rounded-pill px-4">
                                Login
                            </a>
                            <a href="index.php?action=register" class="btn btn-light text-success rounded-pill px-4 fw-bold shadow-sm">
                                Registro
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content Wrapper -->
    <main>
