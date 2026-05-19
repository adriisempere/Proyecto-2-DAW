<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? "GreenPoints - Recicla y Gana" ?></title>

    <!-- CSS de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Iconos de Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Fuentes de Google: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800&display=swap" rel="stylesheet">
    <!-- Biblioteca de animaciones Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <!-- Sistema de diseño GreenPoints -->
    <link rel="stylesheet" href="css/custom.css">

    <style>
        /*
         * IMPORTANTE: NO usar backdrop-filter en el nav ni en .gp-nav-pill.
         * backdrop-filter en position:sticky crea un stacking context que
         * atrapa los modales de Bootstrap (position:fixed) detrás del nav.
         * En su lugar usamos un fondo opaco con borde sutil.
         */

        /* El <nav> cubre todo el ancho y tiene el mismo color de fondo
           que el hero para que no se vea el blanco del body */
        #mainNavbar {
            background: linear-gradient(110deg,
                #042608 0%,
                #053828 100%);
            border-bottom: 1px solid rgba(255,255,255,0.07);
            box-shadow: 0 2px 20px rgba(0,0,0,0.3);
            padding: 6px 0;
            transition: all 0.35s ease;
            /* Asegurar que el nav siempre esté POR DEBAJO de los modales */
            z-index: 1030 !important;
        }
        #mainNavbar.scrolled {
            padding: 4px 0;
            background: linear-gradient(110deg,
                rgba(2, 18, 7, 0.98) 0%,
                rgba(3, 28, 24, 0.98) 100%);
            box-shadow: 0 4px 32px rgba(0,0,0,0.4);
        }

        /* La «pastilla»: solo estructura visual, SIN backdrop-filter */
        .gp-nav-pill {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 14px;
            padding: 0.4rem 1rem;
            display: flex;
            align-items: center;
            gap: 0;
            width: 100%;
            transition: all 0.3s ease;
        }
        #mainNavbar.scrolled .gp-nav-pill {
            background: rgba(255,255,255,0.03);
            border-color: rgba(255,255,255,0.06);
        }

        /* Marca */
        .gp-brand {
            font-weight: 800;
            font-size: 1.45rem;
            color: #fff !important;
            letter-spacing: -0.3px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }
        .gp-brand:hover { opacity: 0.9; transform: scale(1.02); }
        .gp-brand-icon {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, #22c55e, #0d9488);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem;
            box-shadow: 0 4px 14px rgba(34,197,94,0.4);
            flex-shrink: 0;
            transition: all 0.3s ease;
        }
        .gp-brand:hover .gp-brand-icon {
            box-shadow: 0 6px 20px rgba(34,197,94,0.6);
            transform: rotate(10deg);
        }

        /* Enlaces de navegación */
        .gp-nav-link {
            color: rgba(255,255,255,0.72) !important;
            font-weight: 500;
            font-size: 0.88rem;
            padding: 0.5rem 0.85rem !important;
            border-radius: 10px;
            transition: all 0.22s ease;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            text-decoration: none;
            position: relative;
            letter-spacing: 0.01em;
        }
        /* Línea inferior animada al hacer hover: empieza desde el centro (left:50% right:50%)
           y se expande hacia los lados al pasar el ratón */
        .gp-nav-link::after {
            content: '';
            position: absolute;
            bottom: 2px;
            left: 50%; right: 50%;
            height: 2px;
            background: linear-gradient(90deg, #22c55e, #0d9488);
            border-radius: 2px;
            transition: left 0.25s ease, right 0.25s ease;
        }
        .gp-nav-link:hover {
            color: #fff !important;
            background: rgba(255,255,255,0.08);
        }
        .gp-nav-link:hover::after {
            left: 16%; right: 16%;
        }
        .gp-nav-link.active {
            color: #fff !important;
            background: rgba(34,197,94,0.15);
        }
        .gp-nav-link.active::after {
            left: 12%; right: 12%;
        }
        .gp-nav-link i { font-size: 0.88rem; flex-shrink: 0; }

        /* Insignia de puntos */
        .gp-points-badge {
            background: linear-gradient(135deg,
                rgba(34,197,94,0.25) 0%,
                rgba(13,148,136,0.25) 100%);
            border: 1px solid rgba(34,197,94,0.4);
            backdrop-filter: blur(8px);
            border-radius: 50px;
            padding: 0.35rem 0.9rem;
            color: #fff;
            font-size: 0.85rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            white-space: nowrap;
        }
        .gp-points-badge .pts-star {
            color: #fbbf24;
            filter: drop-shadow(0 0 4px rgba(251,191,36,0.6));
            animation: pulsoSuave 2.5s infinite;
        }

        /* Avatar de usuario */
        .gp-avatar-btn {
            background: linear-gradient(135deg,
                rgba(255,255,255,0.18) 0%,
                rgba(255,255,255,0.08) 100%);
            border: 1px solid rgba(255,255,255,0.28);
            color: #fff;
            border-radius: 50px;
            padding: 0.38rem 0.85rem 0.38rem 0.5rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            font-weight: 500;
        }
        .gp-avatar-btn:hover {
            background: rgba(255,255,255,0.26);
            border-color: rgba(255,255,255,0.45);
            box-shadow: 0 4px 16px rgba(0,0,0,0.2);
            transform: translateY(-1px);
            color: #fff;
        }
        .gp-avatar-circle {
            width: 30px; height: 30px;
            background: linear-gradient(135deg, #22c55e, #0d9488);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700;
            font-size: 0.78rem;
            color: #fff;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(34,197,94,0.5);
        }

        /* Menú desplegable — SIN backdrop-filter (evita stacking context)
           El nav tiene position:sticky, lo que crea su propio stacking context.
           Si el dropdown usara backdrop-filter, se crearía otro stacking context
           anidado que podría interferir con los modales de Bootstrap. */
        .gp-dropdown {
            border: 1px solid rgba(255,255,255,0.14);
            background: #0a2a14;
            /* NO backdrop-filter aquí: el nav ya tiene un fondo opaco */
            border-radius: 16px;
            padding: 0.6rem;
            box-shadow: 0 16px 48px rgba(0,0,0,0.5), 0 1px 0 rgba(255,255,255,0.06) inset;
            min-width: 220px;
            z-index: 1031;
            animation: slideInUp 0.22s ease;
        }
        .gp-dropdown .dropdown-header {
            color: rgba(255,255,255,0.45);
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 0.4rem 0.75rem 0.2rem;
        }
        .gp-dropdown .dropdown-item {
            color: rgba(255,255,255,0.82);
            border-radius: 10px;
            padding: 0.6rem 0.85rem;
            font-size: 0.88rem;
            font-weight: 500;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .gp-dropdown .dropdown-item:hover {
            background: rgba(255,255,255,0.1);
            color: #fff;
            transform: translateX(3px);
        }
        .gp-dropdown .dropdown-item.text-danger { color: rgba(248,113,113,0.85); }
        .gp-dropdown .dropdown-item.text-danger:hover {
            background: rgba(239,68,68,0.15);
            color: #f87171;
        }
        .gp-dropdown .dropdown-divider {
            border-color: rgba(255,255,255,0.1);
            margin: 0.3rem 0;
        }

        /* Botón de menú responsive */
        .gp-toggler {
            border: 1px solid rgba(255,255,255,0.25);
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
            padding: 0.4rem 0.6rem;
            color: #fff;
            transition: all 0.25s ease;
        }
        .gp-toggler:hover { background: rgba(255,255,255,0.2); }
        .gp-toggler:focus { box-shadow: none; outline: none; }

        /* Botones de autenticación */
        .btn-nav-login {
            border: 1.5px solid rgba(255,255,255,0.45);
            color: #fff;
            border-radius: 50px;
            padding: 0.4rem 1.1rem;
            font-weight: 600;
            font-size: 0.88rem;
            background: transparent;
            transition: all 0.25s ease;
            text-decoration: none;
        }
        .btn-nav-login:hover {
            background: rgba(255,255,255,0.15);
            color: #fff;
            border-color: rgba(255,255,255,0.7);
        }
        .btn-nav-register {
            background: linear-gradient(135deg, #22c55e, #0d9488);
            color: #fff;
            border: none;
            border-radius: 50px;
            padding: 0.4rem 1.2rem;
            font-weight: 700;
            font-size: 0.88rem;
            box-shadow: 0 4px 14px rgba(34,197,94,0.4);
            transition: all 0.25s ease;
            text-decoration: none;
        }
        .btn-nav-register:hover {
            box-shadow: 0 6px 22px rgba(34,197,94,0.6);
            transform: translateY(-2px);
            color: #fff;
        }

        /* Colapso en móvil */
        @media (max-width: 991.98px) {
            /* La pill en móvil ocupa todo el ancho */
            .gp-nav-pill {
                border-radius: 14px;
                padding: 0.45rem 1rem;
                flex-wrap: wrap;
            }
            /* El colapso móvil necesita overflow visible para dropdowns
               z-index < 1050 para no interferir con el backdrop de Bootstrap */
            #navbarNav {
                position: relative;
                z-index: 1031;
                width: 100%;
            }
            #navbarNav.show {
                background: rgba(3, 20, 10, 0.98);
                border-top: 1px solid rgba(255,255,255,0.08);
                border-radius: 0 0 12px 12px;
                padding: 0.75rem;
                margin-top: 0.25rem;
                overflow: visible !important;
            }
            .gp-nav-link {
                padding: 0.65rem 1rem !important;
                border-radius: 10px;
                width: 100%;
            }
            /* Puntos badge: mostrar en línea en móvil */
            .gp-points-badge {
                width: fit-content;
                margin-bottom: 0.5rem;
            }
            /* En móvil, el área derecha apila verticalmente */
            #navbarNav .d-flex.align-items-center {
                flex-direction: column;
                align-items: flex-start !important;
                gap: 0.5rem !important;
                width: 100%;
                padding-top: 0.5rem;
                border-top: 1px solid rgba(255,255,255,0.08);
                margin-top: 0.25rem;
            }
            /* Dropdown en móvil: ancho completo, posición estática */
            #navbarNav .dropdown {
                width: 100%;
            }
            #navbarNav .gp-avatar-btn {
                width: 100%;
                justify-content: flex-start;
            }
            #navbarNav .gp-dropdown {
                position: static !important;
                transform: none !important;
                width: 100%;
                margin-top: 0.4rem;
                box-shadow: 0 4px 16px rgba(0,0,0,0.3);
                border-radius: 12px;
            }
            /* Botones login/registro en móvil */
            .btn-nav-login,
            .btn-nav-register {
                width: 100%;
                text-align: center;
                justify-content: center;
                padding: 0.6rem 1rem;
            }
        }

        /* Animación escalonada de elementos del menú
           Cada .nav-item aparece secuencialmente con un retardo creciente
           para crear un efecto de cascada al cargar la página */
        .nav-item { animation: slideInUp 0.4s ease backwards; }
        .nav-item:nth-child(1) { animation-delay: 0.05s; }
        .nav-item:nth-child(2) { animation-delay: 0.10s; }
        .nav-item:nth-child(3) { animation-delay: 0.15s; }
        .nav-item:nth-child(4) { animation-delay: 0.20s; }

        /* Separador visual entre links y perfil en escritorio */
        .gp-nav-separator {
            width: 1px;
            height: 28px;
            background: rgba(255,255,255,0.2);
            margin: 0 0.5rem;
            flex-shrink: 0;
        }
        @media (max-width: 991.98px) {
            .gp-nav-separator { display: none; }
        }
    </style>
</head>
<body>

<!-- ── Barra de navegación ────────────────────────────────── -->
<nav class="navbar navbar-expand-lg sticky-top" id="mainNavbar" aria-label="Navegación principal">
    <div class="container">
        <div class="gp-nav-pill">

        <!-- Marca -->
        <a class="gp-brand me-3" href="index.php?action=home">
            <span class="gp-brand-icon">
                <i class="bi bi-recycle text-white"></i>
            </span>
            <span class="d-none d-sm-inline">GreenPoints</span>
        </a>

        <!-- Botón de menú para móvil -->
        <button class="gp-toggler navbar-toggler ms-auto" type="button"
                data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false"
                aria-label="Alternar navegación">
            <i class="bi bi-list fs-4 text-white"></i>
        </button>

        <!-- Nav links + right section (dentro del pill en desktop) -->
        <div class="collapse navbar-collapse" id="navbarNav">

            <!-- Enlaces izquierdos -->
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-1">
                <li class="nav-item">
                    <a class="gp-nav-link" href="index.php?action=home">
                        <i class="bi bi-house-door"></i><span>Inicio</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="gp-nav-link" href="index.php?action=ranking">
                        <i class="bi bi-trophy"></i><span>Ranking</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="gp-nav-link" href="index.php?action=centros">
                        <i class="bi bi-geo-alt"></i><span>Centros</span>
                    </a>
                </li>
                <?php if (isset($_SESSION["usuario_id"])): ?>
                <li class="nav-item">
                    <a class="gp-nav-link" href="index.php?action=tienda">
                        <i class="bi bi-gift"></i><span>Recompensas</span>
                    </a>
                </li>
                <?php endif; ?>
            </ul>

            <!-- Sección derecha -->
            <div class="d-flex align-items-center gap-2 flex-wrap mt-2 mt-lg-0">
                <?php if (isset($_SESSION["usuario_id"])): ?>

                    <!-- Insignia de puntos -->
                    <div class="gp-points-badge d-none d-sm-flex">
                        <i class="bi bi-star-fill pts-star"></i>
                        <span class="badge-points">
                            <span class="fw-bold"><?= number_format(
                                (int) ($_SESSION["usuario_puntos"] ?? 0),
                            ) ?></span> pts
                        </span>
                    </div>

                    <!-- Desplegable de usuario -->
                    <div class="dropdown">
                        <button class="gp-avatar-btn dropdown-toggle"
                                type="button" id="userDropdown"
                                data-bs-toggle="dropdown"
                                aria-expanded="false">
                            <div class="gp-avatar-circle">
                                <?= mb_strtoupper(
                                    mb_substr(
                                        $_SESSION["usuario_nombre"] ?? "U",
                                        0,
                                        1,
                                        "UTF-8",
                                    ),
                                    "UTF-8",
                                ) ?>
                            </div>
                            <span class="d-none d-sm-inline"><?= htmlspecialchars(
                                $_SESSION["usuario_nombre"] ?? "Usuario",
                            ) ?></span>
                            <i class="bi bi-chevron-down" style="font-size:0.7rem;opacity:0.7;"></i>
                        </button>

                        <ul class="dropdown-menu gp-dropdown dropdown-menu-end"
                            aria-labelledby="userDropdown">
                            <li><h6 class="dropdown-header">Mi Cuenta</h6></li>
                            <li>
                                <a class="dropdown-item" href="index.php?action=perfil">
                                    <i class="bi bi-person text-primary"></i>Perfil
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="index.php?action=mis_registros">
                                    <i class="bi bi-clock-history" style="color:#38bdf8;"></i>Mis Registros
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="index.php?action=registro_create">
                                    <i class="bi bi-plus-circle text-success"></i>Registrar Puntos
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="index.php?action=tienda">
                                    <i class="bi bi-gift text-success"></i>Tienda de Recompensas
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="index.php?action=mis_canjes">
                                    <i class="bi bi-bag-check" style="color:#38bdf8;"></i>Mis Recompensas
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="index.php?action=logout">
                                    <i class="bi bi-box-arrow-right"></i>Cerrar Sesión
                                </a>
                            </li>
                        </ul>
                    </div>

                <?php else: ?>
                    <a href="index.php?action=login" class="btn-nav-login">
                        <i class="bi bi-box-arrow-in-right me-1"></i>Login
                    </a>
                    <a href="index.php?action=register" class="btn-nav-register">
                        <i class="bi bi-person-plus me-1"></i>Registro
                    </a>
                <?php endif; ?>
            </div>

        </div>
        </div><!-- /.gp-nav-pill -->
    </div>
</nav>

<!-- ── Contenido Principal ────────────────────────────────── -->
<main>
