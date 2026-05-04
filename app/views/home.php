<?php
/**
 * Vista de Inicio (Home) — GreenPoints
 * ---------------------------------------------------------------
 * Landing principal de la aplicación. Muestra:
 *   - Hero con llamada a la acción según estado de sesión
 *   - Estadísticas globales cargadas dinámicamente desde
 *     public/api/ranking.php?action=stats
 *   - Sección de servicios y propuesta de valor
 *   - Sección de impacto con lista de beneficios
 *   - CTA final con acceso a registro y ranking
 * ---------------------------------------------------------------
 */

$pageTitle = 'GreenPoints | Recicla y Gana';
include __DIR__ . '/partials/header.php';
?>

<!-- ── Hero ──────────────────────────────────────────────────── -->
<header class="px-4 py-5 my-5 text-center">
    <picture>
        <source srcset="img/LogoGreenPoints.webp" type="image/webp">
        <img class="d-block mx-auto img-fluid animate-float mb-3"
             src="img/LogoGreenPoints.png"
             alt="Logo GreenPoints"
             width="160" height="160"
             loading="lazy">
    </picture>

    <h1 class="display-5 fw-bold text-gradient animate__animated animate__fadeInDown">
        GreenPoints
    </h1>

    <div class="col-lg-6 mx-auto animate__animated animate__fadeInUp delay-200">
        <p class="lead mb-4">
            Fomentamos el reciclaje a través de un sistema de puntos y recompensas.
            Registra tus actividades, escala en el ranking y compite con tu comunidad.
            Cuantos más puntos acumules, más recompensas podrás obtener.
        </p>
        <p class="lead fw-semibold mb-4">¡Empieza a reciclar hoy mismo!</p>

        <div class="d-flex gap-2 justify-content-center flex-wrap">
            <?php if (isset($_SESSION['usuario_id'])): ?>
                <a href="index.php?action=registro_create"
                   class="btn btn-success btn-lg px-4 rounded-pill btn-pulse">
                    <i class="bi bi-plus-circle me-2"></i>Registrar Reciclaje
                </a>
                <a href="index.php?action=ranking"
                   class="btn btn-outline-success btn-lg px-4 rounded-pill">
                    <i class="bi bi-trophy me-2"></i>Ver Ranking
                </a>
            <?php else: ?>
                <a href="index.php?action=register"
                   class="btn btn-success btn-lg px-4 rounded-pill btn-pulse">
                    <i class="bi bi-person-plus me-2"></i>Registro
                </a>
                <a href="index.php?action=login"
                   class="btn btn-outline-secondary btn-lg px-4 rounded-pill">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión
                </a>
            <?php endif; ?>
        </div>
    </div>
</header>

<!-- ── Estadísticas globales (cargadas desde la API) ─────────── -->
<section class="py-5 bg-white shadow-sm">
    <div class="container text-center">
        <div class="row g-4" id="statsRow">

            <!-- Esqueleto de carga -->
            <div class="col-md-4 col-sm-6 stat-placeholder">
                <div class="py-3">
                    <div class="placeholder-glow">
                        <span class="placeholder col-4 rounded mb-2 d-block mx-auto" style="height:2rem;"></span>
                        <span class="placeholder col-6 rounded d-block mx-auto"></span>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 stat-placeholder">
                <div class="py-3">
                    <div class="placeholder-glow">
                        <span class="placeholder col-4 rounded mb-2 d-block mx-auto" style="height:2rem;"></span>
                        <span class="placeholder col-6 rounded d-block mx-auto"></span>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 stat-placeholder">
                <div class="py-3">
                    <div class="placeholder-glow">
                        <span class="placeholder col-4 rounded mb-2 d-block mx-auto" style="height:2rem;"></span>
                        <span class="placeholder col-6 rounded d-block mx-auto"></span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ── Cómo funciona ──────────────────────────────────────────── -->
<section id="servicios" class="py-5 bg-light">
    <div class="container py-lg-5">
        <div class="text-center mb-5">
            <h6 class="text-success fw-bold text-uppercase" style="letter-spacing: 2px;">¿Cómo funciona?</h6>
            <h2 class="display-5 fw-bold mt-2">Nuestros Servicios Clave</h2>
        </div>
        <div class="row g-4">

            <div class="col-md-4">
                <div class="card h-100 p-4 border-0 shadow-sm hover-lift animate__animated animate__fadeInUp delay-100">
                    <div class="card-body">
                        <div class="bg-success bg-opacity-10 p-3 rounded-circle d-inline-block mb-4">
                            <i class="bi bi-recycle text-success fs-2"></i>
                        </div>
                        <h4 class="fw-bold">Registro Ágil</h4>
                        <p class="text-muted mb-0">
                            Anota tus actividades de reciclaje —plástico, papel, vidrio, metal u orgánico—
                            y acumula puntos al instante.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 p-4 border-0 shadow-sm hover-lift animate__animated animate__fadeInUp delay-200">
                    <div class="card-body">
                        <div class="bg-warning bg-opacity-10 p-3 rounded-circle d-inline-block mb-4">
                            <i class="bi bi-trophy text-warning fs-2"></i>
                        </div>
                        <h4 class="fw-bold">Gamificación</h4>
                        <p class="text-muted mb-0">
                            Sube posiciones en el ranking, desbloquea niveles y compite con
                            otros usuarios por el top 1.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 p-4 border-0 shadow-sm hover-lift animate__animated animate__fadeInUp delay-300">
                    <div class="card-body">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle d-inline-block mb-4">
                            <i class="bi bi-geo-alt text-primary fs-2"></i>
                        </div>
                        <h4 class="fw-bold">Centros Cercanos</h4>
                        <p class="text-muted mb-0">
                            Consulta los centros de reciclaje disponibles, sus horarios
                            y los materiales que aceptan.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>

</section>

<!-- ── Por qué GreenPoints ────────────────────────────────────── -->
<section id="impacto" class="py-5">
    <div class="container py-lg-5">
        <div class="row align-items-center g-4 g-lg-5">

            <div class="col-lg-6">
                <img src="https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=800&q=80"
                     alt="Reciclaje comunitario"
                     class="img-fluid rounded-4 shadow">
            </div>

            <div class="col-lg-6">
                <h2 class="display-5 fw-bold mb-4">Por qué elegir GreenPoints</h2>
                <p class="lead text-secondary mb-4">
                    Transformamos la sostenibilidad en una experiencia social.
                    No solo reciclas: ves el impacto real de tus acciones.
                </p>
                <ul class="list-unstyled">
                    <li class="mb-3 d-flex align-items-start">
                        <i class="bi bi-check-circle-fill text-success flex-shrink-0 mt-1 fs-5"></i>
                        <span class="ms-3">Canjea puntos por descuentos en comercios locales.</span>
                    </li>
                    <li class="mb-3 d-flex align-items-start">
                        <i class="bi bi-check-circle-fill text-success flex-shrink-0 mt-1 fs-5"></i>
                        <span class="ms-3">Estadísticas de CO₂ ahorrado gracias a tu reciclaje.</span>
                    </li>
                    <li class="mb-3 d-flex align-items-start">
                        <i class="bi bi-check-circle-fill text-success flex-shrink-0 mt-1 fs-5"></i>
                        <span class="ms-3">Comunidad activa comprometida con el medio ambiente.</span>
                    </li>
                </ul>
                <a href="index.php?action=register"
                   class="btn btn-success btn-lg rounded-pill px-5 mt-3">
                    <i class="bi bi-rocket-takeoff me-2"></i>Únete Ahora
                </a>
            </div>

        </div>
    </div>

<!-- ── CTA final ──────────────────────────────────────────────── -->
<section class="py-5 bg-success text-white">
    <div class="container text-center py-4">
        <h2 class="display-4 fw-bold mb-3">¿Listo para hacer la diferencia?</h2>
        <p class="lead mb-4 opacity-75">
            Únete a la comunidad de usuarios que ya están transformando el mundo.
        </p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="index.php?action=register"
               class="btn btn-light btn-lg px-5 rounded-pill text-success fw-bold">
                <i class="bi bi-person-plus me-2"></i>Crear Cuenta Gratis
            </a>
            <a href="index.php?action=ranking"
               class="btn btn-outline-light btn-lg px-5 rounded-pill">
                <i class="bi bi-trophy me-2"></i>Ver Ranking
            </a>
        </div>
    </div>

    <div class="col-md-4">
        <h3><?= $totalCentros ?></h3>
        <p>Centros registrados</p>
    </div>

</div>

<div class="text-center mt-5">

    <img
        src="assets/img/impacto.jpg"
        alt="Impacto ambiental GreenPoints"
        class="img-fluid rounded shadow"
        loading="lazy"
    >

</div>

</section>

<script>
// ── Carga dinámica de estadísticas globales ─────────────────────
document.addEventListener('DOMContentLoaded', function () {
    fetch('api/ranking.php?action=stats')
        .then(r => r.json())
        .then(json => {
            if (!json.success) throw new Error(json.message);

            const d = json.data;

            // Formatear kg en toneladas si supera 1000
            const kg  = parseFloat(d.kg_reciclados || 0);
            const kgLabel = kg >= 1000
                ? (kg / 1000).toFixed(1) + ' Ton'
                : kg.toFixed(1) + ' kg';

            const co2  = parseFloat(d.co2_ahorrado_kg || 0);
            const co2Label = co2 >= 1000
                ? (co2 / 1000).toFixed(1) + ' Ton CO₂'
                : co2.toFixed(1) + ' kg CO₂';

            const stats = [
                { valor: (parseInt(d.usuarios_activos) || 0).toLocaleString('es-ES'), label: 'Usuarios Activos' },
                { valor: kgLabel,  label: 'Material Reciclado' },
                { valor: co2Label, label: 'CO₂ Ahorrado' },
            ];

            // Reemplazar esqueletos con datos reales
            const row = document.getElementById('statsRow');
            row.innerHTML = stats.map((s, i) => `
                <div class="col-md-4 col-sm-6">
                    <div class="py-3 animate__animated animate__zoomIn" style="animation-delay:${i * 0.1}s">
                        <h3 class="fw-bold text-success mb-1">${s.valor}</h3>
                        <p class="text-muted mb-0">${s.label}</p>
                    </div>
                </div>
            `).join('');
        })
        .catch(() => {
            // Si la API falla, mostramos valores de fallback sin romper la página
            const row = document.getElementById('statsRow');
            row.innerHTML = `
                <div class="col-md-4 col-sm-6"><div class="py-3"><h3 class="fw-bold text-success mb-1">—</h3><p class="text-muted mb-0">Usuarios Activos</p></div></div>
                <div class="col-md-4 col-sm-6"><div class="py-3"><h3 class="fw-bold text-success mb-1">—</h3><p class="text-muted mb-0">Material Reciclado</p></div></div>
                <div class="col-md-4 col-sm-6"><div class="py-3"><h3 class="fw-bold text-success mb-1">—</h3><p class="text-muted mb-0">CO₂ Ahorrado</p></div></div>
            `;
        });
});
</script>

<style>
    @media (max-width: 768px) {
        .col-lg-6.ps-lg-5 { padding-left: 0 !important; margin-top: 1.5rem; }
    }
</style>

<?php include __DIR__ . '/partials/footer.php'; ?>