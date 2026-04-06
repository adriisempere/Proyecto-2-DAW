<?php
session_start();

if (empty($_SESSION['usuario_id'])) {
    header('Location: index.php?action=login');
    exit;
}

$pageTitle = 'Perfil | GreenPoints';
include __DIR__ . '/partials/header.php';

$nombre = $_SESSION['usuario_nombre'] ?? 'Usuario';
$email  = $_SESSION['usuario_email'] ?? 'No disponible';
$puntos = (int)($_SESSION['usuario_puntos'] ?? 0);

function calcularNivel($puntos){
    if ($puntos > 5000) return 'Maestro Verde';
    if ($puntos > 2000) return 'Experto';
    if ($puntos > 500) return 'Avanzado';
    return 'Principiante';
}

$nivel = calcularNivel($puntos);
$inicial = strtoupper(substr($nombre ?: 'U',0,1));
?>

<!-- Custom CSS for Profile Page -->
<style>
    .profile-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        border-radius: 20px;
        color: white;
        padding: 3rem 2rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(40, 167, 69, 0.2);
    }
    
    .profile-header::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 60%);
        transform: rotate(30deg);
        pointer-events: none;
    }

    .profile-avatar {
        width: 120px;
        height: 120px;
        background-color: white;
        color: var(--primary-color);
        font-size: 3rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        border: 5px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        margin: 0 auto;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        position: relative;
        z-index: 2;
    }

    .profile-avatar:hover {
        transform: scale(1.05);
        box-shadow: 0 12px 30px rgba(0,0,0,0.15);
    }

    .stat-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        text-align: center;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        border: 1px solid rgba(0,0,0,0.02);
        height: 100%;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        border-color: rgba(40, 167, 69, 0.2);
    }

    .stat-icon {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .stat-value {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--dark-color);
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: #6c757d;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 0.85rem;
    }

    .info-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        border: none;
        overflow: hidden;
    }

    .info-card .card-header {
        background: rgba(40, 167, 69, 0.05);
        border-bottom: 1px solid rgba(40, 167, 69, 0.1);
        padding: 1.25rem 1.5rem;
        font-weight: 600;
        color: var(--dark-color);
    }

    .info-item {
        padding: 1rem 0;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        align-items: center;
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-icon {
        width: 40px;
        height: 40px;
        background: rgba(40, 167, 69, 0.1);
        color: var(--primary-color);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        margin-right: 15px;
    }

    .info-content .label {
        display: block;
        font-size: 0.85rem;
        color: #6c757d;
        margin-bottom: 0.2rem;
    }

    .info-content .value {
        display: block;
        font-weight: 500;
        color: var(--dark-color);
        font-size: 1.05rem;
    }

    .action-btn {
        padding: 0.8rem 1.5rem;
        border-radius: 50px;
        font-weight: 500;
        transition: all 0.3s;
    }
    
    .btn-custom-outline {
        border: 2px solid var(--primary-color);
        color: var(--primary-color);
        background: transparent;
    }
    
    .btn-custom-outline:hover {
        background: var(--primary-color);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(40, 167, 69, 0.2);
    }
    
    .btn-custom-primary {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        color: white;
        border: none;
    }
    
    .btn-custom-primary:hover {
        background: linear-gradient(135deg, var(--secondary-color) 0%, var(--primary-color) 100%);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
    }
</style>

<div class="container py-5">
    
   

        <!-- Header del Perfil -->
        <div class="profile-header text-center mb-5 animate__animated animate__fadeInDown">
            <div class="profile-avatar mb-3 animate__animated animate__zoomIn animate__delay-1s">
                <?= htmlspecialchars($inicial) ?>
            </div>
            <h2 class="fw-bold animate__animated animate__fadeInUp animate__delay-1s position-relative z-index-2">¡Hola, <?= htmlspecialchars($nombre) ?>!</h2>
            <p class="mb-0 text-white-50 animate__animated animate__fadeInUp animate__delay-1s position-relative z-index-2">Bienvenido a tu panel personal de GreenPoints</p>
        </div>

        <div class="row g-4 mb-5">
            <!-- Tarjeta de Puntos -->
            <div class="col-md-4 animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <div class="stat-value"><?= htmlspecialchars($puntos) ?></div>
                    <div class="stat-label">Puntos Actuales</div>
                </div>
            </div>
            
            <!-- Tarjeta de Nivel -->
            <div class="col-md-4 animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="bi bi-trophy-fill"></i>
                    </div>
                    <?php 
                        $nivel = 'Principiante';
                        if($puntos > 500) $nivel = 'Avanzado';
                        if($puntos > 2000) $nivel = 'Experto';
                        if($puntos > 5000) $nivel = 'Maestro Verde';
                    ?>
                    <div class="stat-value"><?= htmlspecialchars($nivel) ?></div>
                    <div class="stat-label">Tu Nivel</div>
                </div>
            </div>
            
            <!-- Tarjeta de Impacto -->
            <div class="col-md-4 animate__animated animate__fadeInUp" style="animation-delay: 0.4s;">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="bi bi-tree-fill"></i>
                    </div>
                    <div class="stat-value">Eco-Héroe</div>
                    <div class="stat-label">Impacto Ambiental</div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Información Personal -->
            <div class="col-lg-8 mb-4 animate__animated animate__fadeInLeft" style="animation-delay: 0.5s;">
                <div class="card info-card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-person-lines-fill me-2 text-success"></i> Información Personal</h5>
                        <button class="btn btn-sm btn-outline-success rounded-pill" disabled title="Próximamente">
                            <i class="bi bi-pencil-square"></i> Editar
                        </button>
                    </div>
                    <div class="card-body px-4">
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="bi bi-person"></i>
                            </div>
                            <div class="info-content">
                                <span class="label">Nombre completo</span>
                                <span class="value"><?= htmlspecialchars($nombre) ?></span>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="bi bi-envelope"></i>
                            </div>
                            <div class="info-content">
                                <span class="label">Correo electrónico</span>
                                <span class="value"><?= htmlspecialchars($email) ?></span>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="bi bi-calendar-check"></i>
                            </div>
                            <div class="info-content">
                                <span class="label">Miembro extra</span>
                                <span class="value text-muted fst-italic">Información no disponible</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acciones Rápidas -->
            <div class="col-lg-4 mb-4 animate__animated animate__fadeInRight" style="animation-delay: 0.6s;">
                <div class="card info-card h-100">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-lightning-charge-fill me-2 text-warning"></i> Acciones Rápidas</h5>
                    </div>
                    <div class="card-body d-flex flex-column gap-3">
                        <a href="index.php?action=registro_create" class="btn btn-custom-primary action-btn d-flex align-items-center justify-content-center w-100">
                            <i class="bi bi-plus-circle me-2"></i> Registrar Puntos
                        </a>
                        <a href="index.php?action=mis_registros" class="btn btn-custom-outline action-btn d-flex align-items-center justify-content-center w-100">
                            <i class="bi bi-clock-history me-2"></i> Ver Mis Registros
                        </a>
                        <a href="index.php?action=ranking" class="btn btn-light border action-btn d-flex align-items-center justify-content-center w-100 text-dark">
                            <i class="bi bi-trophy me-2 text-warning"></i> Ver Ranking
                        </a>
                        
                        <div class="mt-auto pt-4 text-center">
                            <a href="index.php?action=logout" class="text-danger text-decoration-none fw-medium d-inline-flex align-items-center transition-all" style="opacity: 0.8; transition: opacity 0.3s;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.8'">
                                <i class="bi bi-box-arrow-right me-1"></i> Cerrar Sesión
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    
        <div class="row justify-content-center align-items-center animate__animated animate__fadeIn" style="min-height: 50vh;">
            <div class="col-md-8 text-center py-5">
                <div class="mb-4">
                    <i class="bi bi-shield-lock text-success" style="font-size: 5rem; opacity: 0.5;"></i>
                </div>
                <h2 class="fw-bold mb-3">Acceso Restringido</h2>
                <p class="text-muted fs-5 mb-4">Debes iniciar sesión para poder ver la información de tu perfil y gestionar tus puntos de reciclaje.</p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="index.php?action=login" class="btn btn-success btn-lg rounded-pill px-4 shadow-sm">
                        <i class="bi bi-box-arrow-in-right me-2"></i> Iniciar Sesión
                    </a>
                    <a href="index.php?action=register" class="btn btn-outline-success btn-lg rounded-pill px-4">
                        Crear Cuenta
                    </a>
                </div>
            </div>
        </div>
    
    
</div>

<?php include __DIR__ . '/partials/footer.php'; ?>

