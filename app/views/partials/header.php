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
    
    <!-- Estilos Premium -->
    <style>
        :root {
            --primary: #28a745;
            --secondary: #20c997;
            --text-on-nav: #ffffff;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-body, #f8fdf9);
            color: var(--text-main, #333);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: background-color 0.4s ease, color 0.4s ease;
        }
        
        main { flex: 1; }
        
        .navbar {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            padding: 0.75rem 0;
            transition: all 0.3s ease;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--text-on-nav) !important;
            letter-spacing: -0.5px;
        }
        
        .nav-link {
            color: rgba(255,255,255,0.85) !important;
            font-weight: 500;
            padding: 0.5rem 1.2rem !important;
            border-radius: 50px;
            transition: all 0.3s ease;
        }
        
        .nav-link:hover, .nav-link.active {
            background: rgba(255,255,255,0.15);
            color: #fff !important;
            transform: translateY(-1px);
        }

        /* Theme Toggle */
        #themeToggle {
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.2);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
        }

        #themeToggle:hover {
            background: white;
            color: var(--primary);
            transform: rotate(15deg);
        }
        
        .user-profile-btn {
            background: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.3);
            color: white;
            padding: 0.5rem 1.2rem;
            border-radius: 50px;
            transition: all 0.3s;
        }
        
        .user-profile-btn:hover {
            background: white;
            color: var(--primary);
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
                    <?php if(isset($_SESSION['usuario_id'])): ?>
                    <li class="nav-item animate-nav-item delay-4">
                        <a class="nav-link" href="index.php?action=tienda">
                            <i class="bi bi-gift me-1"></i>Recompensas
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
                
                <div class="d-flex align-items-center animate__animated animate__fadeInRight">
                    <?php if(isset($_SESSION['usuario_id'])): ?>
                        <!-- Puntos de usuario -->
                        <div class="badge-points rounded-pill px-3 py-2 text-white me-3 d-none d-md-flex align-items-center">
                            <i class="bi bi-star-fill text-warning me-2"></i>
                            <span class="fw-bold"><?= $_SESSION['usuario_puntos'] ?? 0 ?> pts</span>
                        </div>

                        <!-- Perfil de usuario Dropdown -->
                        <div class="dropdown">
                            <button class="user-profile-btn dropdown-toggle d-flex align-items-center text-decoration-none" 
                                    type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="bg-white text-success rounded-circle d-flex align-items-center justify-content-center me-2" 
                                     style="width: 32px; height: 32px; font-weight: bold;">
                                    <?= mb_strtoupper(mb_substr($_SESSION['usuario_nombre'] ?? 'U', 0, 1, 'UTF-8'), 'UTF-8') ?>
                                </div>
                                <span class="d-none d-sm-inline"><?= htmlspecialchars($_SESSION['usuario_nombre'] ?? 'Usuario') ?></span>
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
                                <li>
                                    <a class="dropdown-item" href="index.php?action=tienda">
                                        <i class="bi bi-gift me-2 text-success"></i>Tienda de Recompensas
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="index.php?action=mis_canjes">
                                        <i class="bi bi-bag-check me-2 text-info"></i>Mis Recompensas Obtenidas
                                    </a>
                                </li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <!-- Login/Register -->
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
