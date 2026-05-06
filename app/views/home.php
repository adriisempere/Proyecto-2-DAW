<?php
/**
 * Vista de Inicio (Home) — GreenPoints
 * ---------------------------------------------------------------
 * SIN SESIÓN INICIADA:
 *   - Hero con fondo de gradiente verde
 *   - Estadísticas globales
 *   - Sección "¿Cómo funciona?"
 *   - Sección de impacto
 *   - CTA final
 *
 * CON SESIÓN INICIADA:
 *   - Banner de bienvenida personalizado
 *   - Estadísticas personales (puntos, kg, CO2, nivel)
 *   - Acciones rápidas (registrar, canjear, ranking, centros)
 *   - Actividad reciente
 * ---------------------------------------------------------------
 */

$logueado = isset($_SESSION['usuario_id']);

if ($logueado) {
    $pageTitle = 'GreenPoints | Mi Panel';
} else {
    $pageTitle = 'GreenPoints | Recicla y Gana';
}

include __DIR__ . '/partials/header.php';

$logueado = isset($_SESSION['usuario_id']);
$nombre   = $_SESSION['usuario_nombre'] ?? 'Usuario';
$puntos   = (int)($_SESSION['usuario_puntos'] ?? 0);
$inicial  = mb_strtoupper(mb_substr($nombre, 0, 1, 'UTF-8'), 'UTF-8');

function calcularNivel($puntos) {
    if ($puntos > 5000) return 'Maestro Verde';
    if ($puntos > 2000) return 'Experto';
    if ($puntos > 500)  return 'Avanzado';
    return 'Principiante';
}

$nivel = calcularNivel($puntos);
?>

<!-- ════════════════════════════════════════════════════════════
     HOME PÚBLICO (sin sesión)
     ════════════════════════════════════════════════════════════ -->
<?php if (!$logueado): ?>

<!-- ── Hero con gradiente verde ──────────────────────────────── -->
<header class="hero-public px-4 py-5 text-center">
    <div class="container">
        <img class="d-block mx-auto img-fluid animate-float mb-3"
             src="img/LogoGreenPoints.png"
             alt="Logo GreenPoints"
             width="160" height="160"
             loading="lazy">

        <h1 class="display-4 fw-bold text-white animate__animated animate__fadeInDown">
            GreenPoints
        </h1>
        <h1 class="display-4 fw-bold text-white animate__animated animate__fadeInDown">
            GreenPoints
        </h1>

        <div class="col-lg-6 mx-auto animate__animated animate__fadeInUp delay-200">
            <p class="lead mb-4 text-white-50">
                Fomentamos el reciclaje a través de un sistema de puntos y recompensas.
                Registra tus actividades, escala en el ranking y compite con tu comunidad.
            </p>
            <p class="lead fw-semibold mb-4 text-white">¡Empieza a reciclar hoy mismo!</p>
        <div class="col-lg-6 mx-auto animate__animated animate__fadeInUp delay-200">
            <p class="lead mb-4 text-white-50">
                Fomentamos el reciclaje a través de un sistema de puntos y recompensas.
                Registra tus actividades, escala en el ranking y compite con tu comunidad.
            </p>
            <p class="lead fw-semibold mb-4 text-white">¡Empieza a reciclar hoy mismo!</p>

            <div class="d-flex gap-2 justify-content-center flex-wrap">
            <div class="d-flex gap-2 justify-content-center flex-wrap">
                <a href="index.php?action=register"
                   class="btn btn-light btn-lg px-5 rounded-pill text-success fw-bold btn-pulse">
                   class="btn btn-light btn-lg px-5 rounded-pill text-success fw-bold btn-pulse">
                    <i class="bi bi-person-plus me-2"></i>Registro
                </a>
                <a href="index.php?action=login"
                   class="btn btn-outline-light btn-lg px-4 rounded-pill">
                   class="btn btn-outline-light btn-lg px-4 rounded-pill">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión
                </a>
            </div>
            </div>
        </div>
    </div>
</header>

<!-- ── Estadísticas globales ─────────────────────────────────── -->
<section class="py-5 bg-white shadow-sm">
    <div class="container text-center">
        <h5 class="fw-bold text-muted mb-4">Impacto Global</h5>
        <div class="row g-4" id="statsRow">
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

<!-- ── Cómo funciona ─────────────────────────────────────────── -->
<section id="servicios" class="py-5 bg-light">
    <div class="container py-lg-5">
        <div class="text-center mb-5 scroll-reveal">
            <h6 class="text-success fw-bold text-uppercase" style="letter-spacing: 2px;">¿Cómo funciona?</h6>
            <h2 class="display-5 fw-bold mt-2">Nuestros Servicios Clave</h2>
        </div>
        <div class="row g-4">
            <div class="col-md-4 scroll-reveal">
                <div class="card h-100 p-4 border-0 shadow-sm hover-lift">
                    <div class="card-body text-center">
                        <div class="bg-success bg-opacity-10 p-3 rounded-circle d-inline-block mb-4">
                            <i class="bi bi-recycle text-success fs-2"></i>
                        </div>
                        <h4 class="fw-bold">Registro Ágil</h4>
                        <p class="text-muted mb-0">
                            Anota tus actividades de reciclaje y acumula puntos al instante.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 scroll-reveal">
                <div class="card h-100 p-4 border-0 shadow-sm hover-lift">
                    <div class="card-body text-center">
                        <div class="bg-warning bg-opacity-10 p-3 rounded-circle d-inline-block mb-4">
                            <i class="bi bi-trophy text-warning fs-2"></i>
                        </div>
                        <h4 class="fw-bold">Gamificación</h4>
                        <p class="text-muted mb-0">
                            Sube posiciones en el ranking y compite con otros usuarios.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 scroll-reveal">
                <div class="card h-100 p-4 border-0 shadow-sm hover-lift">
                    <div class="card-body text-center">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle d-inline-block mb-4">
                            <i class="bi bi-geo-alt text-primary fs-2"></i>
                        </div>
                        <h4 class="fw-bold">Centros Cercanos</h4>
                        <p class="text-muted mb-0">
                            Consulta los centros de reciclaje disponibles y sus horarios.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── Impacto ───────────────────────────────────────────────── -->
<section id="impacto" class="py-5">
    <div class="container py-lg-5">
        <div class="row align-items-center g-4 g-lg-5">
            <div class="col-lg-6 scroll-reveal">
                <img src="https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=800&q=80"
                     alt="Reciclaje comunitario"
                     class="img-fluid rounded-4 shadow hover-zoom">
            </div>
            <div class="col-lg-6 scroll-reveal">
                <h2 class="display-5 fw-bold mb-4">Por qué elegir GreenPoints</h2>
                <p class="lead text-secondary mb-4">
                    Transformamos la sostenibilidad en una experiencia social.
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
</section>
</section>

<!-- ── CTA final ─────────────────────────────────────────────── -->
<section class="py-5 bg-success text-white">
    <div class="container text-center py-4 scroll-reveal">
        <h2 class="display-4 fw-bold mb-3">¿Listo para hacer la diferencia?</h2>
        <p class="lead mb-4" style="opacity:0.85;">
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
</section>

<?php else: ?>

<!-- ════════════════════════════════════════════════════════════
     HOME PERSONALIZADO (usuario logueado)
     ════════════════════════════════════════════════════════════ -->

<!-- ── Banner de bienvenida ──────────────────────────────────── -->
<header class="hero-auth px-4 py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-auto">
                <div class="welcome-avatar">
                    <?= htmlspecialchars($inicial) ?>
                </div>
            </div>
            <div class="col">
                <h1 class="display-6 fw-bold text-white mb-1">
                    ¡Hola, <?= htmlspecialchars($nombre) ?>!
                </h1>
                <p class="mb-0 text-white-50">
                    Nivel: <span class="badge bg-warning text-dark fw-bold"><?= htmlspecialchars($nivel) ?></span>
                    &nbsp;·&nbsp;
                    <i class="bi bi-star-fill text-warning"></i>
                    <span id="userPointsHeader"><?= number_format($puntos) ?> puntos</span>
                </p>
            </div>
            <div class="col-auto d-none d-md-block">
                <a href="index.php?action=registro_create"
                   class="btn btn-light btn-lg rounded-pill text-success fw-bold btn-pulse">
                    <i class="bi bi-plus-circle me-2"></i>Registrar Reciclaje
                </a>
            </div>
        </div>
    </div>
</header>

<!-- ── Estadísticas personales ───────────────────────────────── -->
<section class="py-5 bg-white shadow-sm">
    <div class="container">
        <h5 class="fw-bold text-muted mb-4 text-center scroll-reveal">Tu Progreso</h5>
        <div class="row g-3">
            <div class="col-6 col-md-3 scroll-reveal">
                <div class="stat-card text-center p-4">
                    <div class="stat-icon"><i class="bi bi-star-fill"></i></div>
                    <div class="stat-value counter" id="statPuntos"><?= $puntos ?></div>
                    <div class="stat-label">Puntos Totales</div>
                </div>
            </div>
            <div class="col-6 col-md-3 scroll-reveal">
                <div class="stat-card text-center p-4">
                    <div class="stat-icon"><i class="bi bi-recycle"></i></div>
                    <div class="stat-value" id="statKg">
                        <span class="placeholder col-6 rounded"></span>
                    </div>
                    <div class="stat-label">Kg Reciclados</div>
                </div>
            </div>
            <div class="col-6 col-md-3 scroll-reveal">
                <div class="stat-card text-center p-4">
                    <div class="stat-icon"><i class="bi bi-wind"></i></div>
                    <div class="stat-value" id="statCo2">
                        <span class="placeholder col-6 rounded"></span>
                    </div>
                    <div class="stat-label">CO₂ Ahorrado</div>
                </div>
            </div>
            <div class="col-6 col-md-3 scroll-reveal">
                <div class="stat-card text-center p-4">
                    <div class="stat-icon"><i class="bi bi-trophy-fill"></i></div>
                    <div class="stat-value" id="statRank">
                        <span class="placeholder col-4 rounded"></span>
                    </div>
                    <div class="stat-label">Posición Ranking</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── Acciones rápidas ──────────────────────────────────────── -->
<section class="py-5 bg-light">
    <div class="container">
        <h5 class="fw-bold text-muted mb-4 text-center scroll-reveal">Acciones Rápidas</h5>
        <div class="row g-4">
            <div class="col-md-6 col-lg-3 scroll-reveal">
                <a href="index.php?action=registro_create" class="action-card card h-100 border-0 shadow-sm text-decoration-none">
                    <div class="card-body text-center p-4">
                        <div class="action-icon bg-success bg-opacity-10 p-3 rounded-circle d-inline-block mb-3">
                            <i class="bi bi-plus-circle-fill text-success fs-3"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Registrar Reciclaje</h6>
                        <p class="text-muted small mb-0">Añade un nuevo registro</p>
                    </div>
                </a>
            </div>
            <div class="col-md-6 col-lg-3 scroll-reveal">
                <a href="index.php?action=tienda" class="action-card card h-100 border-0 shadow-sm text-decoration-none">
                    <div class="card-body text-center p-4">
                        <div class="action-icon bg-warning bg-opacity-10 p-3 rounded-circle d-inline-block mb-3">
                            <i class="bi bi-gift-fill text-warning fs-3"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Canjear Puntos</h6>
                        <p class="text-muted small mb-0">Recompensas disponibles</p>
                    </div>
                </a>
            </div>
            <div class="col-md-6 col-lg-3 scroll-reveal">
                <a href="index.php?action=ranking" class="action-card card h-100 border-0 shadow-sm text-decoration-none">
                    <div class="card-body text-center p-4">
                        <div class="action-icon bg-primary bg-opacity-10 p-3 rounded-circle d-inline-block mb-3">
                            <i class="bi bi-trophy-fill text-primary fs-3"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Ver Ranking</h6>
                        <p class="text-muted small mb-0">Clasificación general</p>
                    </div>
                </a>
            </div>
            <div class="col-md-6 col-lg-3 scroll-reveal">
                <a href="index.php?action=centros" class="action-card card h-100 border-0 shadow-sm text-decoration-none">
                    <div class="card-body text-center p-4">
                        <div class="action-icon bg-info bg-opacity-10 p-3 rounded-circle d-inline-block mb-3">
                            <i class="bi bi-geo-alt-fill text-info fs-3"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Centros</h6>
                        <p class="text-muted small mb-0">Puntos de reciclaje</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ── Distribución por material ─────────────────────────────── -->
<section class="py-5">
    <div class="container">
        <h5 class="fw-bold text-muted mb-4 text-center scroll-reveal">Tu Reciclaje por Material</h5>
        <div class="row justify-content-center">
            <div class="col-lg-8 scroll-reveal">
                <div class="card border-0 shadow-sm p-4">
                    <div id="materialBars">
                        <div class="placeholder-glow">
                            <?php for ($i = 0; $i < 5; $i++): ?>
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <span class="placeholder rounded" style="width:90px;height:28px;"></span>
                                <span class="placeholder flex-grow-1 rounded" style="height:28px;"></span>
                            </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── Actividad reciente ────────────────────────────────────── -->
<section class="py-5 bg-light">
    <div class="container">
        <h5 class="fw-bold text-muted mb-4 text-center scroll-reveal">Actividad Reciente</h5>
        <div class="row justify-content-center">
            <div class="col-lg-8 scroll-reveal">
                <div class="card border-0 shadow-sm" id="recentActivity">
                    <div class="card-body p-4">
                        <?php for ($i = 0; $i < 3; $i++): ?>
                        <div class="d-flex align-items-center gap-3 mb-3 pb-3 border-bottom">
                            <div class="placeholder rounded-circle" style="width:40px;height:40px;"></div>
                            <div class="flex-grow-1">
                                <span class="placeholder col-6 rounded"></span>
                            </div>
                            <span class="placeholder col-2 rounded"></span>
                        </div>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php endif; ?>

<!-- ════════════════════════════════════════════════════════════
     SCRIPTS
     ════════════════════════════════════════════════════════════ -->
<script>
<?php if (!$logueado): ?>
/* Estadísticas globales */
document.addEventListener('DOMContentLoaded', function () {
    fetch('api/ranking.php?action=stats')
        .then(r => r.json())
        .then(json => {
            if (!json.success) throw new Error(json.message);
            const d = json.data;
            const kg  = parseFloat(d.kg_reciclados || 0);
            const kgLabel = kg >= 1000 ? (kg / 1000).toFixed(1) + ' Ton' : kg.toFixed(1) + ' kg';
            const kgLabel = kg >= 1000 ? (kg / 1000).toFixed(1) + ' Ton' : kg.toFixed(1) + ' kg';
            const co2  = parseFloat(d.co2_ahorrado_kg || 0);
            const co2Label = co2 >= 1000 ? (co2 / 1000).toFixed(1) + ' Ton CO₂' : co2.toFixed(1) + ' kg CO₂';
            const stats = [
                { valor: (parseInt(d.usuarios_activos) || 0).toLocaleString('es-ES'), label: 'Usuarios Activos' },
                { valor: kgLabel,  label: 'Material Reciclado' },
                { valor: co2Label, label: 'CO₂ Ahorrado' },
            ];
            const row = document.getElementById('statsRow');
            if (row) {
                row.innerHTML = stats.map((s, i) => `
                    <div class="col-md-4 col-sm-6">
                        <div class="py-3 animate__animated animate__zoomIn" style="animation-delay:${i * 0.1}s">
                            <h3 class="fw-bold text-success mb-1">${s.valor}</h3>
                            <p class="text-muted mb-0">${s.label}</p>
                        </div>
                    </div>
                `).join('');
            }
        })
        .catch(() => {
            document.getElementById('statsRow').innerHTML =
                '<div class="col-md-4 col-sm-6"><div class="py-3"><h3 class="fw-bold text-success mb-1">—</h3><p class="text-muted mb-0">Usuarios Activos</p></div></div>' +
                '<div class="col-md-4 col-sm-6"><div class="py-3"><h3 class="fw-bold text-success mb-1">—</h3><p class="text-muted mb-0">Material Reciclado</p></div></div>' +
                '<div class="col-md-4 col-sm-6"><div class="py-3"><h3 class="fw-bold text-success mb-1">—</h3><p class="text-muted mb-0">CO₂ Ahorrado</p></div></div>';
        });
});
<?php else: ?>
/* Estadísticas personales y actividad */
const MATERIALES_INFO = {
    'plastico': { label: 'Plástico', color: '#0d6efd', icon: 'bi-cup' },
    'papel':    { label: 'Papel',    color: '#fd7e14', icon: 'bi-file-earmark' },
    'vidrio':   { label: 'Vidrio',   color: '#198754', icon: 'bi-cup-straw' },
    'metal':    { label: 'Metal',    color: '#6c757d', icon: 'bi-gear' },
    'organico': { label: 'Orgánico', color: '#20c997', icon: 'bi-apple' },
};

function esc(s) { const d = document.createElement('div'); d.textContent = s; return d.innerHTML; }

document.addEventListener('DOMContentLoaded', function () {

    fetch('api/ranking.php?action=me')
        .then(r => r.json())
        .then(json => {
            if (!json.success) return;
            const d = json.data;

            const kg = parseFloat(d.kg_reciclados || 0);
            document.getElementById('statKg').textContent =
                kg >= 1000 ? (kg / 1000).toFixed(1) + ' t' : kg.toFixed(2) + ' kg';

            const co2 = kg * 1.5;
            document.getElementById('statCo2').textContent =
                co2 >= 1000 ? (co2 / 1000).toFixed(1) + ' t' : co2.toFixed(2) + ' kg';

            document.getElementById('statRank').textContent = '#' + d.posicion;

            document.getElementById('userPointsHeader').textContent =
                (parseInt(d.puntos_totales) || <?= $puntos ?>).toLocaleString('es-ES') + ' puntos';
        })
        .catch(() => {
            document.getElementById('statKg').textContent  = '—';
            document.getElementById('statCo2').textContent = '—';
            document.getElementById('statRank').textContent = '—';
        });

    fetch('api/ranking.php?action=stats_material')
        .then(r => r.json())
        .then(json => {
            const barsContainer = document.getElementById('materialBars');
            if (!json.success || !json.data || json.data.length === 0) {
                barsContainer.innerHTML = '<p class="text-muted text-center mb-0">Aún no has registrado reciclaje.</p>';
                return;
            }
            const data = json.data;
            const maxKg = Math.max(...data.map(d => parseFloat(d.kg_totales)), 1);
            barsContainer.innerHTML = data.map((d, i) => {
                const info = MATERIALES_INFO[d.tipo_material] || { label: d.tipo_material, color: '#6c757d' };
                const pct  = Math.max((d.kg_totales / maxKg) * 100, 4);
                const kgStr = d.kg_totales >= 1000
                    ? (d.kg_totales / 1000).toFixed(1) + ' Ton'
                    : d.kg_totales.toFixed(1) + ' kg';
                return `
                    <div class="d-flex align-items-center gap-3 mb-3 animate__animated animate__fadeInLeft" style="animation-delay:${i * 0.08}s">
                        <div class="flex-shrink-0 text-end" style="width:80px;">
                            <span class="small fw-semibold text-dark">${esc(info.label)}</span>
                        </div>
                        <div class="flex-grow-1 position-relative" style="height:28px;background:#e9ecef;border-radius:14px;overflow:hidden;">
                            <div style="width:${pct}%;height:100%;background:linear-gradient(90deg,${info.color},${info.color}dd);border-radius:14px;transition:width 1s ease;min-width:28px;"></div>
                            <span class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-end pe-3">
                                <span class="small fw-bold text-dark">${esc(kgStr)}</span>
                            </span>
                        </div>
                    </div>`;
            }).join('');
        })
        .catch(() => {
            const barsContainer = document.getElementById('materialBars');
            barsContainer.innerHTML = '<p class="text-muted text-center mb-0">No se pudo cargar la información.</p>';
        });

    fetch('api/registro.php?action=recent')
        .then(r => r.json())
        .then(json => {
            const container = document.getElementById('recentActivity');
            if (!json.success || !json.data || json.data.length === 0) {
                container.innerHTML = `
                    <div class="card-body p-4 text-center">
                        <i class="bi bi-clock-history text-muted fs-1 mb-3 d-block"></i>
                        <p class="text-muted mb-0">Aún no tienes registros. ¡Empieza a reciclar!</p>
                        <a href="index.php?action=registro_create" class="btn btn-success btn-sm rounded-pill mt-3">
                            <i class="bi bi-plus-circle me-1"></i>Registrar Reciclaje
                        </a>
                    </div>`;
                return;
            }
            const iconMap = {
                'plastico': 'bi-cup-fill text-primary',
                'papel': 'bi-file-earmark-fill text-warning',
                'vidrio': 'bi-cup-straw-fill text-success',
                'metal': 'bi-gear-fill text-secondary',
                'organico': 'bi-apple text-info',
            };
            container.innerHTML = `<div class="card-body p-4">` +
                json.data.slice(0, 5).map((r, i) => {
                    const fecha = new Date(r.fecha).toLocaleDateString('es-ES', {
                        day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit'
                    });
                    const icon = iconMap[r.tipo_material] || 'bi-recycle text-success';
                    return `
                        <div class="d-flex align-items-center gap-3 mb-3 pb-3${i < json.data.length - 1 ? ' border-bottom' : ''} animate__animated animate__fadeInUp" style="animation-delay:${i * 0.08}s">
                            <div class="bg-light rounded-circle p-2 d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                                <i class="bi ${icon}"></i>
                            </div>
                            <div class="flex-grow-1">
                                <span class="fw-semibold d-block">${esc(r.tipo_material)}</span>
                                <span class="small text-muted">${esc(fecha)}</span>
                            </div>
                            <div class="text-end">
                                <span class="fw-bold text-success">+${parseInt(r.puntos_ganados)} pts</span>
                                <span class="small text-muted d-block">${parseFloat(r.cantidad).toFixed(1)} kg</span>
                            </div>
                        </div>`;
                }).join('') + `</div>`;
        })
        .catch(() => {
            document.getElementById('recentActivity').innerHTML = `
                <div class="card-body p-4 text-center">
                    <p class="text-muted mb-0">No se pudo cargar la actividad reciente.</p>
                </div>`;
        });
});
<?php endif; ?>
<?php endif; ?>
</script>

<!-- ════════════════════════════════════════════════════════════
     ESTILOS
     ════════════════════════════════════════════════════════════ -->
<style>
    /* ── Hero público: gradiente verde ───────────────────────── */
    .hero-public {
        position: relative;
        min-height: 85vh;
        display: flex;
        align-items: center;
        background: linear-gradient(135deg, #0d6b35 0%, #14803a 30%, #28a745 55%, #20c997 80%, #17a576 100%);
        overflow: hidden;
    }

    .hero-public::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(ellipse at 30% 20%, rgba(255,255,255,0.08) 0%, transparent 60%);
        pointer-events: none;
    }

    /* ── Hero autenticado: gradiente más oscuro y compacto ─── */
    .hero-auth {
        position: relative;
        background: linear-gradient(135deg, #0a4f28 0%, #14693a 40%, #1a8a4e 100%);
        overflow: hidden;
        border-radius: 0 0 30px 30px;
        min-height: 30vh;
        display: flex;
        align-items: center;
        box-shadow: 0 8px 30px rgba(0,0,0,0.12);
    }

    .hero-auth::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(ellipse at 70% 30%, rgba(255,255,255,0.06) 0%, transparent 50%);
        pointer-events: none;
    }

    /* ── Avatar de bienvenida ─────────────────────────────────── */
    .welcome-avatar {
        width: 80px;
        height: 80px;
        background: white;
        color: var(--primary-color, #28a745);
        font-size: 2rem;
        font-weight: 700;
        border-radius: 50%;
        border: 3px solid rgba(255,255,255,0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    }

    /* ── Tarjetas de estadísticas ─────────────────────────────── */
    .stat-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 14px rgba(0,0,0,0.05);
        transition: transform 0.25s, box-shadow 0.25s;
        border: 1px solid rgba(0,0,0,0.03);
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 24px rgba(0,0,0,0.09);
        border-color: rgba(40,167,69,0.15);
    }

    .stat-icon {
        font-size: 1.8rem;
        margin-bottom: 0.5rem;
        background: linear-gradient(135deg, var(--primary-color, #28a745), var(--secondary-color, #20c997));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .stat-value {
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--dark-color, #343a40);
        margin-bottom: 0.25rem;
    }

    .stat-label {
        color: #6c757d;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 0.7rem;
    }

    /* ── Tarjetas de acción rápida ────────────────────────────── */
    .action-card {
        transition: transform 0.25s, box-shadow 0.25s;
        border-radius: 15px !important;
    }

    .action-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 28px rgba(0,0,0,0.1) !important;
    }

    .action-card .action-icon {
        transition: transform 0.25s;
    }

    .action-card:hover .action-icon {
        transform: scale(1.1);
    }

    /* ── Scroll reveal: elementos ocultos hasta que entran en viewport */
    .scroll-reveal {
        opacity: 0;
        transform: translateY(30px);
        transition: opacity 0.6s ease, transform 0.6s ease;
    }

    .scroll-reveal.visible {
        opacity: 1;
        transform: translateY(0);
    }

    /* ── Hover zoom para imágenes ─────────────────────────────── */
    .hover-zoom {
        transition: transform 0.4s ease;
    }

    .hover-zoom:hover {
        transform: scale(1.03);
    }

    /* ── Responsive ───────────────────────────────────────────── */
    @media (max-width: 768px) {
        .hero-public { min-height: 70vh; }
        .hero-auth { min-height: auto; padding: 2rem 0; }
        .welcome-avatar { width: 60px; height: 60px; font-size: 1.5rem; }
    }
</style>

<?php include __DIR__ . '/partials/footer.php'; ?>

