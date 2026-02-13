<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'GreenPoints - Recicla y Gana' ?></title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <!-- Custom Animations -->
    <link rel="stylesheet" href="css/custom.css">
    
    <!-- Estilos Custom -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-color: var(--bg-body);
            color: var(--text-main);
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        
        .main-content {
            flex: 1;
        }
        
        /* Navbar Styles */
        .navbar {
            background: linear-gradient(135deg, var(--navbar-bg-start) 0%, var(--navbar-bg-end) 100%) !important;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            padding: 1rem 0;
            transition: all 0.3s ease;
        }
        
        .navbar.scrolled {
            padding: 0.5rem 0;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: white !important;
            display: flex;
            align-items: center;
            transition: transform 0.3s;
        }
        
        .navbar-brand:hover {
            transform: scale(1.05);
        }
        
        .navbar-brand i {
            font-size: 1.8rem;
            margin-right: 0.5rem;
        }
        
        .nav-link {
            color: rgba(255,255,255,0.9) !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            transition: all 0.3s;
            position: relative;
        }
        
        .nav-link:hover {
            color: white !important;
            transform: translateY(-2px);
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: white;
            transition: all 0.3s;
            transform: translateX(-50%);
        }
        
        .nav-link:hover::after {
            width: 80%;
        }
        
        .nav-link.active {
            color: white !important;
            font-weight: 600;
        }
        
        .user-badge {
            background: var(--badge-bg);
            padding: 0.4rem 1rem;
            border-radius: 25px;
            color: white;
            font-weight: 500;
            margin-right: 1rem;
            backdrop-filter: blur(10px);
        }
        
        .points-badge {
            background: var(--accent-color);
            color: #000;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .btn-login {
            background: var(--badge-bg);
            border: 2px solid white;
            color: white;
            font-weight: 600;
            padding: 0.5rem 1.5rem;
            border-radius: 25px;
            transition: all 0.3s;
            backdrop-filter: blur(10px);
        }
        
        .btn-login:hover {
            background: white;
            color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255,255,255,0.3);
        }
        
        .btn-register {
            background: white;
            color: var(--primary-color);
            font-weight: 600;
            padding: 0.5rem 1.5rem;
            border-radius: 25px;
            transition: all 0.3s;
            border: 2px solid white;
        }
        
        .btn-register:hover {
            background: transparent;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255,255,255,0.3);
        }
        
        .dropdown-menu {
            border-radius: 15px;
            background-color: var(--dropdown-bg);
            box-shadow: 0 5px 25px rgba(0,0,0,0.15);
            border: none;
            margin-top: 0.5rem;
        }
        
        .dropdown-item {
            padding: 0.7rem 1.5rem;
            transition: all 0.3s;
            color: var(--text-main);
        }
        
        .dropdown-item:hover {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
        }
        
        .dropdown-divider {
            margin: 0.5rem 0;
        }
        
        /* Responsive */
        @media (max-width: 991px) {
            .navbar-collapse {
                background: rgba(255,255,255,0.1);
                backdrop-filter: blur(10px);
                padding: 1rem;
                border-radius: 15px;
                margin-top: 1rem;
            }
            
            .user-badge {
                margin: 0.5rem 0;
            }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark sticky-top" id="mainNavbar">
    <div class="container">
        <a class="navbar-brand" href="index.php?action=home">
            <i class="bi bi-leaf-fill"></i>
            GreenPoints
        </a>
        
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
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
                <?php if (isset($_SESSION['usuario_id'])): ?>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?action=registro_create">
                        <i class="bi bi-recycle me-1"></i>Registrar Reciclaje
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?action=mis_registros">
                        <i class="bi bi-list-check me-1"></i>Mis Registros
                    </a>
                </li>
                <?php endif; ?>
            </ul>
            
            <div class="d-flex align-items-center flex-wrap">
                <!-- Theme Toggle -->
                <button id="theme-toggle" class="btn btn-outline-light rounded-circle me-3 border-0" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background: rgba(255,255,255,0.1); backdrop-filter: blur(10px);">
                    <i class="bi bi-moon-fill"></i>
                </button>

                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <!-- Usuario logueado -->
                    <div class="user-badge me-2">
                        <i class="bi bi-person-circle me-2"></i>
                        <?= htmlspecialchars($_SESSION['usuario_nombre'] ?? 'Usuario') ?>
                    </div>
                    <span class="points-badge me-2">
                        <i class="bi bi-star-fill me-1"></i>
                        <?= number_format($_SESSION['usuario_puntos'] ?? 0) ?> pts
                    </span>
                    
                    <div class="dropdown">
                        <button class="btn btn-light btn-sm rounded-circle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="width: 40px; height: 40px;">
                            <i class="bi bi-gear-fill"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li>
                                <a class="dropdown-item" href="index.php?action=perfil">
                                    <i class="bi bi-person me-2"></i>Mi Perfil
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="index.php?action=mis_registros">
                                    <i class="bi bi-list-ul me-2"></i>Mis Registros
                                </a>
                            </li>
                            <?php if (isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin'): ?>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="index.php?action=admin">
                                    <i class="bi bi-shield-check me-2"></i>Panel Admin
                                </a>
                            </li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="index.php?action=logout">
                                    <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
                                </a>
                            </li>
                        </ul>
                    </div>
                <?php else: ?>
                    <!-- Usuario no logueado -->
                    <a href="index.php?action=login" class="btn btn-login me-2">
                        <i class="bi bi-box-arrow-in-right me-1"></i>Login
                    </a>
                    <a href="index.php?action=register" class="btn btn-register">
                        <i class="bi bi-person-plus me-1"></i>Registro
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<!-- Alerts (si existen mensajes) -->
<?php if (isset($_SESSION['success']) || isset($_SESSION['error']) || isset($_SESSION['info'])): ?>
<div class="container mt-3">
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show animate__animated animate__fadeInDown" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <?= htmlspecialchars($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show animate__animated animate__fadeInDown" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <?= htmlspecialchars($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['info'])): ?>
        <div class="alert alert-info alert-dismissible fade show animate__animated animate__fadeInDown" role="alert">
            <i class="bi bi-info-circle-fill me-2"></i>
            <?= htmlspecialchars($_SESSION['info']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['info']); ?>
    <?php endif; ?>
</div>
<?php endif; ?>

<!-- Main Content -->
<main class="main-content">
