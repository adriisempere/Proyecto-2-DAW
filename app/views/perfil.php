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