<?php
/**
 * Vista de Perfil — GreenPoints
 * ---------------------------------------------------------------
 * Panel personal del usuario autenticado. Muestra:
 *   - Avatar con inicial, nombre, email y fecha de registro
 *   - Estadísticas personales (puntos, nivel, kg reciclados, CO₂)
 *     cargadas desde api/ranking.php?action=me
 *   - Información de cuenta con fecha de registro real desde BD
 *   - Acciones rápidas: registrar, historial, ranking, logout
 *
 * Si no hay sesión activa muestra pantalla de acceso restringido
 * con botones de login y registro, sin redirigir automáticamente.
 * ---------------------------------------------------------------
 */

$pageTitle = 'Mi Perfil | GreenPoints';
include __DIR__ . '/partials/header.php';
?>

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
        inset: 0;
        background: radial-gradient(circle at 70% 30%, rgba(255,255,255,0.12) 0%, transparent 60%);
        pointer-events: none;
    }

    .profile-avatar {
        width: 110px;
        height: 110px;
        background: white;
        color: var(--primary-color);
        font-size: 2.8rem;
        font-weight: 700;
        border-radius: 50%;
        border: 4px solid rgba(255,255,255,0.4);
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.3s, box-shadow 0.3s;
        position: relative;
        z-index: 1;
    }

    .profile-avatar:hover {
        transform: scale(1.06);
        box-shadow: 0 12px 30px rgba(0,0,0,0.18);
    }

    .stat-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        text-align: center;
        box-shadow: 0 4px 14px rgba(0,0,0,0.05);
        transition: transform 0.25s, box-shadow 0.25s;
        border: 1px solid rgba(0,0,0,0.03);
        height: 100%;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 24px rgba(0,0,0,0.09);
        border-color: rgba(40,167,69,0.15);
    }

    .stat-icon {
        font-size: 2.2rem;
        margin-bottom: 0.75rem;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .stat-value {
        font-size: 1.6rem;
        font-weight: 700;
        color: var(--dark-color);
        margin-bottom: 0.3rem;
        min-height: 2rem;
    }

    .stat-label {
        color: #6c757d;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 0.8rem;
    }

    .info-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 14px rgba(0,0,0,0.05);
        border: none;
        overflow: hidden;
    }

    .info-card .card-header {
        background: rgba(40,167,69,0.05);
        border-bottom: 1px solid rgba(40,167,69,0.1);
        padding: 1.2rem 1.5rem;
        font-weight: 600;
    }

    .info-item {
        padding: 1rem 0;
        border-bottom: 1px solid #f2f2f2;
        display: flex;
        align-items: center;
    }

    .info-item:last-child { border-bottom: none; }

    .info-icon {
        width: 38px;
        height: 38px;
        background: rgba(40,167,69,0.08);
        color: var(--primary-color);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        margin-right: 14px;
        flex-shrink: 0;
    }

    .info-content .label {
        display: block;
        font-size: 0.8rem;
        color: #6c757d;
        margin-bottom: 0.15rem;
    }

    .info-content .value {
        display: block;
        font-weight: 500;
        font-size: 1rem;
    }

    .action-btn {
        padding: 0.75rem 1.25rem;
        border-radius: 50px;
        font-weight: 500;
        transition: all 0.25s;
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
        box-shadow: 0 5px 14px rgba(40,167,69,0.2);
    }

    .btn-custom-primary {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        border: none;
    }

    .btn-custom-primary:hover {
        filter: brightness(1.08);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 14px rgba(40,167,69,0.3);
    }
</style>

<div class="container py-5">

<?php if (isset($_SESSION['usuario_id'])): ?>

    <?php
    $nombre  = $_SESSION['usuario_nombre'] ?? 'Usuario';
    $inicial = mb_strtoupper(mb_substr($nombre, 0, 1, 'UTF-8'), 'UTF-8');
    $email   = $_SESSION['usuario_email']  ?? '';
    $puntos  = (int) ($_SESSION['usuario_puntos'] ?? 0);
    $rol     = $_SESSION['usuario_rol']    ?? 'usuario';

    // Calcular nivel según puntos
    $nivel = 'Principiante';
    if ($puntos > 5000) $nivel = 'Maestro Verde';
    elseif ($puntos > 2000) $nivel = 'Experto';
    elseif ($puntos > 500)  $nivel = 'Avanzado';
    ?>

    <!-- Cabecera del perfil -->
    <div class="profile-header text-center mb-5 animate__animated animate__fadeInDown">
        <div class="profile-avatar mb-3">
            <?= htmlspecialchars($inicial) ?>
        </div>
        <h2 class="fw-bold mb-1 position-relative">
            ¡Hola, <?= htmlspecialchars($nombre) ?>!
        </h2>
        <p class="mb-0 opacity-75 position-relative">
            <?= $rol === 'admin' ? '<span class="badge bg-warning text-dark me-2"><i class="bi bi-shield-check me-1"></i>Administrador</span>' : '' ?>
            Bienvenido a tu panel personal
        </p>
    </div>

    <!-- Estadísticas -->
    <div class="row g-3 mb-5">

        <div class="col-6 col-md-3 animate__animated animate__fadeInUp" style="animation-delay:.1s">
            <div class="stat-card">
                <div class="stat-icon"><i class="bi bi-star-fill"></i></div>
                <div class="stat-value"><?= number_format($puntos) ?></div>
                <div class="stat-label">Puntos</div>
            </div>
        </div>

        <div class="col-6 col-md-3 animate__animated animate__fadeInUp" style="animation-delay:.2s">
            <div class="stat-card">
                <div class="stat-icon"><i class="bi bi-trophy-fill"></i></div>
                <div class="stat-value" style="font-size:1.1rem;padding-top:.4rem">
                    <?= htmlspecialchars($nivel) ?>
                </div>
                <div class="stat-label">Nivel</div>
            </div>
        </div>

        <div class="col-6 col-md-3 animate__animated animate__fadeInUp" style="animation-delay:.3s">
            <div class="stat-card">
                <div class="stat-icon"><i class="bi bi-recycle"></i></div>
                <div class="stat-value" id="statKg">
                    <span class="placeholder col-6 rounded"></span>
                </div>
                <div class="stat-label">Kg reciclados</div>
            </div>
        </div>

        <div class="col-6 col-md-3 animate__animated animate__fadeInUp" style="animation-delay:.4s">
            <div class="stat-card">
                <div class="stat-icon"><i class="bi bi-wind"></i></div>
                <div class="stat-value" id="statCo2">
                    <span class="placeholder col-6 rounded"></span>
                </div>
                <div class="stat-label">CO₂ ahorrado</div>
            </div>
        </div>

    </div>

    <div class="row g-4">

        <!-- Información personal -->
        <div class="col-lg-8 animate__animated animate__fadeInLeft" style="animation-delay:.5s">
            <div class="card info-card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-person-lines-fill text-success me-2"></i>Información Personal</span>
                    <button class="btn btn-sm btn-outline-success rounded-pill" disabled title="Próximamente">
                        <i class="bi bi-pencil-square me-1"></i>Editar
                    </button>
                </div>
                <div class="card-body px-4">

                    <div class="info-item">
                        <div class="info-icon"><i class="bi bi-person"></i></div>
                        <div class="info-content">
                            <span class="label">Nombre completo</span>
                            <span class="value"><?= htmlspecialchars($nombre) ?></span>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon"><i class="bi bi-envelope"></i></div>
                        <div class="info-content">
                            <span class="label">Correo electrónico</span>
                            <span class="value"><?= htmlspecialchars($email) ?></span>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon"><i class="bi bi-calendar-check"></i></div>
                        <div class="info-content">
                            <span class="label">Miembro desde</span>
                            <span class="value" id="fechaRegistro">
                                <span class="placeholder col-4 rounded"></span>
                            </span>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon"><i class="bi bi-bar-chart-line"></i></div>
                        <div class="info-content">
                            <span class="label">Posición en el ranking</span>
                            <span class="value" id="posicionRanking">
                                <span class="placeholder col-3 rounded"></span>
                            </span>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Acciones rápidas -->
        <div class="col-lg-4 animate__animated animate__fadeInRight" style="animation-delay:.6s">
            <div class="card info-card h-100">
                <div class="card-header">
                    <i class="bi bi-lightning-charge-fill text-warning me-2"></i>Acciones Rápidas
                </div>
                <div class="card-body d-flex flex-column gap-2 p-3">

                    <a href="index.php?action=registro_create"
                       class="btn btn-custom-primary action-btn w-100 text-center">
                        <i class="bi bi-plus-circle me-2"></i>Registrar Reciclaje
                    </a>

                    <a href="index.php?action=mis_registros"
                       class="btn btn-custom-outline action-btn w-100 text-center">
                        <i class="bi bi-clock-history me-2"></i>Ver Mis Registros
                    </a>

                    <a href="index.php?action=ranking"
                       class="btn btn-light border action-btn w-100 text-center text-dark">
                        <i class="bi bi-trophy me-2 text-warning"></i>Ver Ranking
                    </a>

                    <a href="index.php?action=tienda"
                       class="btn btn-outline-success action-btn w-100 text-center">
                        <i class="bi bi-gift me-2"></i>Tienda de Recompensas
                    </a>

                    <a href="index.php?action=mis_canjes"
                       class="btn btn-outline-info action-btn w-100 text-center">
                        <i class="bi bi-bag-check me-2"></i>Mis Recompensas Obtenidas
                    </a>

                    <?php if ($rol === 'admin'): ?>
                    <a href="index.php?action=centros"
                       class="btn btn-outline-warning action-btn w-100 text-center">
                        <i class="bi bi-geo-alt me-2"></i>Gestionar Centros
                    </a>
                    <?php endif; ?>

                    <div class="mt-auto pt-3 text-center border-top">
                        <a href="index.php?action=logout"
                           class="text-danger text-decoration-none fw-medium small">
                            <i class="bi bi-box-arrow-right me-1"></i>Cerrar Sesión
                        </a>
                    </div>

                </div>
            </div>
        </div>

    </div>

<?php else: ?>

    <!-- Acceso restringido -->
    <div class="row justify-content-center align-items-center" style="min-height:50vh">
        <div class="col-md-7 text-center py-5 animate__animated animate__fadeIn">
            <i class="bi bi-shield-lock text-success d-block mb-4"
               style="font-size:5rem;opacity:.45"></i>
            <h2 class="fw-bold mb-3">Acceso Restringido</h2>
            <p class="text-muted fs-5 mb-4">
                Inicia sesión para ver tu perfil y gestionar tus puntos de reciclaje.
            </p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="index.php?action=login"
                   class="btn btn-success btn-lg rounded-pill px-4 shadow-sm">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión
                </a>
                <a href="index.php?action=register"
                   class="btn btn-outline-success btn-lg rounded-pill px-4">
                    Crear Cuenta
                </a>
            </div>
        </div>
    </div>

<?php endif; ?>

</div>

<?php if (isset($_SESSION['usuario_id'])): ?>
<script>
// Cargar estadísticas dinámicas desde la API
document.addEventListener('DOMContentLoaded', function () {

    fetch('api/ranking.php?action=me')
        .then(r => r.json())
        .then(json => {
            if (!json.success) return;
            const d = json.data;

            // Kg reciclados
            const kg = parseFloat(d.kg_reciclados || 0);
            document.getElementById('statKg').textContent =
                kg >= 1000 ? (kg / 1000).toFixed(1) + ' t' : kg.toFixed(2) + ' kg';

            // CO₂ ahorrado (1.5 kg CO₂ por kg reciclado, igual que en ranking API)
            const co2 = kg * 1.5;
            document.getElementById('statCo2').textContent =
                co2 >= 1000 ? (co2 / 1000).toFixed(1) + ' t' : co2.toFixed(2) + ' kg';

            // Posición en ranking
            document.getElementById('posicionRanking').textContent =
                '#' + d.posicion;
        })
        .catch(() => {
            document.getElementById('statKg').textContent  = '—';
            document.getElementById('statCo2').textContent = '—';
            document.getElementById('posicionRanking').textContent = '—';
        });

    // Cargar fecha de registro desde la API de usuario
    fetch('api/users.php?action=me')
        .then(r => r.json())
        .then(json => {
            if (!json.success || !json.data.creado_at) return;
            const fecha = new Date(json.data.creado_at);
            document.getElementById('fechaRegistro').textContent =
                isNaN(fecha) ? '—' : fecha.toLocaleDateString('es-ES', {
                    day: 'numeric', month: 'long', year: 'numeric'
                });
        })
        .catch(() => {
            document.getElementById('fechaRegistro').textContent = '—';
        });
});
</script>
<?php endif; ?>

<?php include __DIR__ . '/partials/footer.php'; ?>