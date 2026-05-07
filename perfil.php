<?php
session_start();

if (empty($_SESSION['usuario_id'])) {
    header('Location:index.php?action=login');
    exit;
}

$pageTitle='Perfil | GreenPoints';
include __DIR__.'/partials/header.php';

$nombre=$_SESSION['usuario_nombre']??'Usuario';
$email=$_SESSION['usuario_email']??'No disponible';
$puntos=(int)($_SESSION['usuario_puntos']??0);

function calcularNivel($p){
 if($p>5000)return'Maestro Verde';
 if($p>2000)return'Experto';
 if($p>500)return'Avanzado';
 return'Principiante';
}

$nivel=calcularNivel($puntos);
$inicial=strtoupper(substr($nombre?:'U',0,1));

$next=500;
if($puntos>500)$next=2000;
if($puntos>2000)$next=5000;
$progreso=min(100,($puntos/$next)*100);
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

<div class="profile-header text-center mb-5">
    <div class="profile-avatar">
        <?= htmlspecialchars($inicial) ?>
    </div>

    <h2>¡Hola, <?= htmlspecialchars($nombre) ?>!</h2>
</div>

<div class="stat-card text-center">
    <h3><?= $puntos ?> puntos</h3>
    <p><?= $nivel ?></p>

    <div class="progress">
        <div class="progress-bar bg-success"
             style="width:<?= $progreso ?>%">
        </div>
    </div>
</div>

<form method="POST" action="index.php?action=logout" class="mt-4 text-center">
    <button class="btn btn-danger">
        Cerrar sesión
    </button>
</form>

</div>

<?php include __DIR__.'/partials/footer.php'; ?>