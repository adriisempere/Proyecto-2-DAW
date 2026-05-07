<?php
// Sugerencia: Iniciar sesión si no está iniciada para evitar errores de variables indefinidas
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Limpieza de datos de sesión para evitar XSS
$userName = htmlspecialchars($_SESSION['usuario_nombre'] ?? 'Usuario');
$userPoints = number_format($_SESSION['usuario_puntos'] ?? 0, 0, ',', '.');
$firstLetter = strtoupper(substr($userName, 0, 1));
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? $pageTitle . " | GreenPoints" : 'GreenPoints - Recicla y Gana' ?></title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
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
        
        main { flex: 1; }
        
        /* Navbar Styles Mejorados */
        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            padding: 0.8rem 0;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .navbar.scrolled {
            padding: 0.4rem 0;
            background: rgba(40, 167, 69, 0.98);
            backdrop-filter: blur(15px);
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: white !important;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .nav-link {
            color: white !important; /* Mejorado contraste */
            font-weight: 500;
            padding: 0.5rem 1.2rem !important;
            border-radius: 50px;
            transition: all 0.3s ease;
            opacity: 0.9;
        }
        
        .nav-link:hover, .nav-link.active {
            background: rgba(255,255,255,0.25);
            opacity: 1;
            transform: translateY(-1px);
        }
        
        /* User Profile & Badge */
        .user-profile-btn {
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.3);
            color: white;
            padding: 0.4rem 1rem;
            border-radius: 50px;
            transition: all 0.3s;
        }

        .user-profile-btn:hover {
            background: white;
            color: var(--primary-color);
        }

        .badge-points {
            background: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.3);
            font-size: 0.9rem;
        }

        /* Glassmorphism Dropdown */
        .dropdown-menu {
            border: none;
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
            border-radius: 12px;
            padding: 0.8rem;
            margin-top: 10px;
        }
        
        .dropdown-item {
            border-radius: 8px;
            padding: 0.6rem 1rem;
            display: flex;
            align-items: center;
        }

        /* Mobile Adjustments */
        @media (max-width: 991.98px) {
            .navbar-collapse {
                background: rgba(0,0,0,0.05);
                margin-top: 1rem;
                padding: 1rem;
                border-radius: 15px;
            }
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg sticky-top" id="mainNavbar">
        <div class="container">
            <a class="navbar-brand animate__animated animate__fadeInLeft" href="index.php?action=home">
                <i class="bi bi-recycle me-2"></i>GreenPoints
            </a>
            
            <button class="navbar-toggler border-0 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-label="Menu Principal">
                <i class="bi bi-list fs-1"></i>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?action=home">
                            <i class="bi bi-house-door me-1"></i>Inicio
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?action=ranking">
                            <i class="bi bi-trophy me-1"></i>Ranking
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?action=centros">
                            <i class="bi bi-geo-alt me-1"></i>Centros
                        </a>
                    </li>
                </ul>
                
                <div class="d-flex align-items-center gap-2">
                    <?php if(isset($_SESSION['usuario_id'])): ?>
                        <div class="badge-points rounded-pill px-3 py-2 text-white d-none d-md-flex align-items-center">
                            <i class="bi bi-star-fill text-warning me-2"></i>
                            <span class="fw-bold"><?= $userPoints ?> pts</span>
                        </div>

                        <div class="dropdown">
                            <button class="user-profile-btn dropdown-toggle d-flex align-items-center border-0" 
                                    type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="bg-white text-success rounded-circle d-flex align-items-center justify-content-center me-2" 
                                     style="width: 28px; height: 28px; font-weight: bold; font-size: 0.8rem;">
                                    <?= $firstLetter ?>
                                </div>
                                <span class="d-none d-sm-inline"><?= $userName ?></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userDropdown">
                                <li><h6 class="dropdown-header">Mi Panel</h6></li>
                                <li><a class="dropdown-item" href="index.php?action=perfil"><i class="bi bi-person me-2"></i>Perfil</a></li>
                                <li><a class="dropdown-item" href="index.php?action=mis_registros"><i class="bi bi-clock-history me-2"></i>Mis Registros</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="index.php?action=logout"><i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión</a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="index.php?action=login" class="btn btn-outline-light rounded-pill px-4 me-2">Login</a>
                        <a href="index.php?action=register" class="btn btn-light text-success rounded-pill px-4 fw-bold shadow-sm">Registro</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <main>