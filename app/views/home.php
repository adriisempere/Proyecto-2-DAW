<?php
/**
 * Vista de Inicio (Home) — GreenPoints
 * ---------------------------------------------------------------
 * Landing principal de la aplicación con dos modos:
 *
 * SIN SESIÓN INICIADA:
 *   - Hero con fondo de gradiente verde
 *   - Estadísticas globales
 *   - Sección "¿Cómo funciona?"
 *   - Sección de impacto
 *   - CTA final
 *
 * CON SESIÓN INICIADA:
 *   - Panel de bienvenida personalizado con resumen rápido
 *   - Gráfico de barras con reciclaje por tipo de material
 *   - Acciones rápidas
 *   - Top 3 del ranking
 *   - Sección de impacto
 * ---------------------------------------------------------------
 */

$logueado = isset($_SESSION['usuario_id']);

if ($logueado) {
    $pageTitle = 'GreenPoints | Mi Panel';
} else {
    $pageTitle = 'GreenPoints | Recicla y Gana';
}

include __DIR__ . '/partials/header.php';
?>

<?php if (!$logueado): ?>
<!-- ============================================================
     VISTA PÚBLICA (sin iniciar sesión)
     ============================================================ -->

<!-- ── Hero ──────────────────────────────────────────────────── -->
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

        <div class="col-lg-6 mx-auto animate__animated animate__fadeInUp delay-200">
            <p class="lead mb-4 text-white-50">
                Fomentamos el reciclaje a través de un sistema de puntos y recompensas.
                Registra tus actividades, escala en el ranking y compite con tu comunidad.
            </p>
            <p class="lead fw-semibold mb-4 text-white">¡Empieza a reciclar hoy mismo!</p>

            <div class="d-flex gap-2 justify-content-center flex-wrap">
                <a href="index.php?action=register"
                   class="btn btn-light btn-lg px-5 rounded-pill text-success fw-bold btn-pulse">
                    <i class="bi bi-person-plus me-2"></i>Registro
                </a>
                <a href="index.php?action=login"
                   class="btn btn-outline-light btn-lg px-4 rounded-pill">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión
                </a>
            </div>
        </div>
    </div>
</header>

<!-- ── Estadísticas globales ─────────────────────────────────── -->
<section class="py-5" style="background:rgba(255,255,255,0.95);">
    <div class="container text-center">
        <h2 class="fw-bold mb-4 text-gradient">Impacto Global</h2>
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
<section id="servicios" class="py-5" style="background:#f0faf3;">
    <div class="container py-lg-5">
        <div class="text-center mb-5">
            <h6 class="text-success fw-bold text-uppercase" style="letter-spacing:2px;">¿Cómo funciona?</h6>
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

<!-- ── Por qué GreenPoints ───────────────────────────────────── -->
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
</section>

<!-- ── CTA final ─────────────────────────────────────────────── -->
<section class="py-5 text-white" style="background:linear-gradient(135deg,#1a7a32 0%,#28a745 50%,#20c997 100%);">
    <div class="container text-center py-4">
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
<!-- ============================================================
     VISTA AUTENTICADA (sesión iniciada)
     ============================================================ -->

<?php
$nombre  = $_SESSION['usuario_nombre'] ?? 'Usuario';
$puntos  = (int) ($_SESSION['usuario_puntos'] ?? 0);
$rol     = $_SESSION['usuario_rol'] ?? 'usuario';

$nivel = 'Principiante';
if ($puntos > 5000) $nivel = 'Maestro Verde';
elseif ($puntos > 2000) $nivel = 'Experto';
elseif ($puntos > 500)  $nivel = 'Avanzado';
?>

<!-- ── Bienvenida personalizada ──────────────────────────────── -->
<header class="hero-auth px-4 py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="display-5 fw-bold text-white mb-2 animate__animated animate__fadeInLeft">
                    ¡Hola, <?= htmlspecialchars($nombre) ?>!
                </h1>
                <p class="lead text-white-50 mb-1 animate__animated animate__fadeInLeft delay-100">
                    <?= $rol === 'admin' ? '<span class="badge bg-warning text-dark me-2"><i class="bi bi-shield-check me-1"></i>Admin</span>' : '' ?>
                    Bienvenido a tu panel de reciclaje
                </p>
                <div class="d-flex gap-4 mt-3 animate__animated animate__fadeInLeft delay-200">
                    <div>
                        <div class="text-white fs-3 fw-bold"><?= number_format($puntos) ?></div>
                        <div class="text-white-50 small">Puntos totales</div>
                    </div>
                    <div>
                        <div class="text-white fs-3 fw-bold" id="dashNivel"><?= htmlspecialchars($nivel) ?></div>
                        <div class="text-white-50 small">Nivel actual</div>
                    </div>
                    <div>
                        <div class="text-white fs-3 fw-bold" id="dashKg">—</div>
                        <div class="text-white-50 small">Kg reciclados</div>
                    </div>
                </div>
                <div class="d-flex gap-2 mt-4 animate__animated animate__fadeInLeft delay-300">
                    <a href="index.php?action=registro_create"
                       class="btn btn-light btn-lg px-4 rounded-pill text-success fw-bold btn-pulse">
                        <i class="bi bi-plus-circle me-2"></i>Registrar Reciclaje
                    </a>
                    <a href="index.php?action=ranking"
                       class="btn btn-outline-light btn-lg px-4 rounded-pill">
                        <i class="bi bi-trophy me-2"></i>Ranking
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- ── Estadísticas por tipo de material ─────────────────────── -->
<section class="py-5" style="background:rgba(255,255,255,0.95);">
    <div class="container">
        <h2 class="fw-bold text-center mb-2 text-gradient">Reciclaje por Material</h2>
        <p class="text-muted text-center mb-5">Cantidad total reciclada en la plataforma por cada tipo de material</p>

        <div class="row g-4" id="materialStats">
            <!-- Esqueletos de carga -->
            <?php for ($i = 0; $i < 5; $i++): ?>
            <div class="col-md-6 col-lg">
                <div class="card border-0 shadow-sm p-4 text-center">
                    <div class="placeholder-glow">
                        <span class="placeholder rounded-circle mx-auto d-block mb-3" style="width:48px;height:48px;"></span>
                        <span class="placeholder col-6 rounded d-block mx-auto mb-2"></span>
                        <span class="placeholder col-4 rounded d-block mx-auto"></span>
                    </div>
                </div>
            </div>
            <?php endfor; ?>
        </div>

        <!-- Barras horizontales de comparación -->
        <div class="mt-5">
            <h5 class="fw-bold text-center mb-4">Comparativa visual (kg)</h5>
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
</section>

<!-- ── Acciones rápidas ──────────────────────────────────────── -->
<section class="py-5" style="background:#f0faf3;">
    <div class="container">
        <h2 class="fw-bold text-center mb-4">Acciones Rápidas</h2>
        <div class="row g-3 justify-content-center">
            <div class="col-6 col-md-4 col-lg-2">
                <a href="index.php?action=registro_create"
                   class="card border-0 shadow-sm p-3 text-center text-decoration-none text-dark h-100 hover-lift">
                    <div class="fs-2 text-success mb-2"><i class="bi bi-plus-circle"></i></div>
                    <div class="fw-semibold small">Registrar</div>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <a href="index.php?action=mis_registros"
                   class="card border-0 shadow-sm p-3 text-center text-decoration-none text-dark h-100 hover-lift">
                    <div class="fs-2 text-info mb-2"><i class="bi bi-clock-history"></i></div>
                    <div class="fw-semibold small">Mis Registros</div>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <a href="index.php?action=ranking"
                   class="card border-0 shadow-sm p-3 text-center text-decoration-none text-dark h-100 hover-lift">
                    <div class="fs-2 text-warning mb-2"><i class="bi bi-trophy"></i></div>
                    <div class="fw-semibold small">Ranking</div>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <a href="index.php?action=tienda"
                   class="card border-0 shadow-sm p-3 text-center text-decoration-none text-dark h-100 hover-lift">
                    <div class="fs-2 text-success mb-2"><i class="bi bi-gift"></i></div>
                    <div class="fw-semibold small">Tienda</div>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <a href="index.php?action=mis_canjes"
                   class="card border-0 shadow-sm p-3 text-center text-decoration-none text-dark h-100 hover-lift">
                    <div class="fs-2 text-primary mb-2"><i class="bi bi-bag-check"></i></div>
                    <div class="fw-semibold small">Mis Canjes</div>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <a href="index.php?action=centros"
                   class="card border-0 shadow-sm p-3 text-center text-decoration-none text-dark h-100 hover-lift">
                    <div class="fs-2 text-secondary mb-2"><i class="bi bi-geo-alt"></i></div>
                    <div class="fw-semibold small">Centros</div>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ── Top 3 del Ranking ─────────────────────────────────────── -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-4">
            <h2 class="fw-bold"><i class="bi bi-trophy-fill text-warning me-2"></i>Top Recicladores</h2>
            <p class="text-muted">Los líderes de la comunidad GreenPoints</p>
        </div>
        <div id="topRanking" class="row g-3 justify-content-center">
            <div class="col-12 placeholder-glow">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm p-4 text-center">
                            <span class="placeholder rounded-circle mx-auto d-block mb-2" style="width:48px;height:48px;"></span>
                            <span class="placeholder col-6 rounded d-block mx-auto mb-2"></span>
                            <span class="placeholder col-4 rounded d-block mx-auto"></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm p-4 text-center">
                            <span class="placeholder rounded-circle mx-auto d-block mb-2" style="width:48px;height:48px;"></span>
                            <span class="placeholder col-6 rounded d-block mx-auto mb-2"></span>
                            <span class="placeholder col-4 rounded d-block mx-auto"></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm p-4 text-center">
                            <span class="placeholder rounded-circle mx-auto d-block mb-2" style="width:48px;height:48px;"></span>
                            <span class="placeholder col-6 rounded d-block mx-auto mb-2"></span>
                            <span class="placeholder col-4 rounded d-block mx-auto"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── Por qué GreenPoints ───────────────────────────────────── -->
<section id="impacto" class="py-5" style="background:#f0faf3;">
    <div class="container py-lg-4">
        <div class="row align-items-center g-4 g-lg-5">
            <div class="col-lg-6">
                <img src="https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=800&q=80"
                     alt="Reciclaje comunitario"
                     class="img-fluid rounded-4 shadow">
            </div>
            <div class="col-lg-6">
                <h2 class="display-5 fw-bold mb-4">Tu impacto importa</h2>
                <p class="lead text-secondary mb-4">
                    Cada kilo que reciclas contribuye a un planeta más limpio.
                    Sigue así y desbloquea recompensas exclusivas.
                </p>
                <ul class="list-unstyled">
                    <li class="mb-3 d-flex align-items-start">
                        <i class="bi bi-check-circle-fill text-success flex-shrink-0 mt-1 fs-5"></i>
                        <span class="ms-3">Canjea puntos por tarjetas regalo de marcas populares.</span>
                    </li>
                    <li class="mb-3 d-flex align-items-start">
                        <i class="bi bi-check-circle-fill text-success flex-shrink-0 mt-1 fs-5"></i>
                        <span class="ms-3">Visualiza tu progreso y el impacto de CO₂ ahorrado.</span>
                    </li>
                    <li class="mb-3 d-flex align-items-start">
                        <i class="bi bi-check-circle-fill text-success flex-shrink-0 mt-1 fs-5"></i>
                        <span class="ms-3">Compite con la comunidad por el primer puesto del ranking.</span>
                    </li>
                </ul>
                <a href="index.php?action=perfil"
                   class="btn btn-success btn-lg rounded-pill px-5 mt-3">
                    <i class="bi bi-person me-2"></i>Ir a mi Perfil
                </a>
            </div>
        </div>
    </div>
</section>

<?php endif; ?>

<!-- ============================================================
     SCRIPTS
     ============================================================ -->
<script>
<?php if (!$logueado): ?>
/* ── Vista pública: cargar estadísticas globales ────────────── */
document.addEventListener('DOMContentLoaded', function () {
    fetch('api/ranking.php?action=stats')
        .then(r => r.json())
        .then(json => {
            if (!json.success) throw new Error(json.message);
            const d = json.data;
            const kg  = parseFloat(d.kg_reciclados || 0);
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
            const row = document.getElementById('statsRow');
            if (row) {
                row.innerHTML = `
                    <div class="col-md-4"><div class="py-3"><h3 class="fw-bold text-success mb-1">—</h3><p class="text-muted mb-0">Usuarios Activos</p></div></div>
                    <div class="col-md-4"><div class="py-3"><h3 class="fw-bold text-success mb-1">—</h3><p class="text-muted mb-0">Material Reciclado</p></div></div>
                    <div class="col-md-4"><div class="py-3"><h3 class="fw-bold text-success mb-1">—</h3><p class="text-muted mb-0">CO₂ Ahorrado</p></div></div>
                `;
            }
        });
});
<?php else: ?>
/* ── Vista autenticada: cargar datos del dashboard ─────────── */
document.addEventListener('DOMContentLoaded', function () {

    /* Escapar texto para prevenir XSS */
    function esc(str) {
        const d = document.createElement('div');
        d.textContent = str ?? '';
        return d.innerHTML;
    }

    /* ── Estadísticas por tipo de material ────────────────── */
    const MATERIALES_INFO = {
        plastico: { label: 'Plástico', icon: 'bi-bag',          color: '#ffc107' },
        papel:    { label: 'Papel',    icon: 'bi-file-earmark', color: '#0dcaf0' },
        vidrio:   { label: 'Vidrio',   icon: 'bi-cup',          color: '#20c997' },
        metal:    { label: 'Metal',    icon: 'bi-gear',         color: '#6f42c1' },
        organico: { label: 'Orgánico', icon: 'bi-tree',         color: '#198754' },
    };

    fetch('api/ranking.php?action=stats_material')
        .then(r => r.json())
        .then(json => {
            if (!json.success) return;
            const data = json.data;
            const maxKg = Math.max(...data.map(d => d.kg_totales), 1);

            /* Tarjetas por material */
            const container = document.getElementById('materialStats');
            if (container) {
                container.innerHTML = data.map((d, i) => {
                    const info = MATERIALES_INFO[d.tipo_material] || { label: d.tipo_material, icon: 'bi-recycle', color: '#6c757d' };
                    const kgStr = d.kg_totales >= 1000
                        ? (d.kg_totales / 1000).toFixed(1) + ' Ton'
                        : d.kg_totales.toFixed(1) + ' kg';
                    return `
                        <div class="col-md-6 col-lg animate__animated animate__fadeInUp" style="animation-delay:${i * 0.1}s">
                            <div class="card border-0 shadow-sm p-4 text-center h-100 hover-lift">
                                <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                                     style="width:52px;height:52px;background:${info.color}15;color:${info.color};font-size:1.4rem;">
                                    <i class="bi ${info.icon}"></i>
                                </div>
                                <h5 class="fw-bold mb-1">${esc(info.label)}</h5>
                                <div class="fs-4 fw-bold text-success mb-1">${kgStr}</div>
                                <div class="text-muted small">${d.total_registros} registros · ${d.puntos_totales} pts</div>
                            </div>
                        </div>`;
                }).join('');
            }

            /* Barras horizontales de comparación */
            const barsContainer = document.getElementById('materialBars');
            if (barsContainer) {
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
            }
        });

    /* ── Kg reciclados del usuario (dash header) ──────────── */
    fetch('api/ranking.php?action=me')
        .then(r => r.json())
        .then(json => {
            if (!json.success) return;
            const kg = parseFloat(json.data.kg_reciclados || 0);
            const el = document.getElementById('dashKg');
            if (el) el.textContent = kg >= 1000 ? (kg / 1000).toFixed(1) + ' t' : kg.toFixed(2) + ' kg';
        });

    /* ── Top 3 del ranking ────────────────────────────────── */
    fetch('api/ranking.php?action=list')
        .then(r => r.json())
        .then(json => {
            if (!json.success || !json.data.length) return;
            const data = json.data.slice(0, 3);
            const container = document.getElementById('topRanking');
            if (!container) return;

            const medals = ['🥇', '🥈', '🥉'];
            const bgs = ['bg-warning', 'bg-secondary', ''];
            const heights = ['110px', '80px', '60px'];
            const sizes = ['fs-3', 'fs-4', 'fs-4'];

            /* Ordenar visualmente: 2º, 1º, 3º */
            const visualOrder = [1, 0, 2];

            container.innerHTML = '<div class="row g-3 justify-content-center">' +
                visualOrder.map(vi => {
                    const u = data[vi];
                    return `
                        <div class="col-auto text-center d-flex flex-column justify-content-end" style="min-width:130px">
                            <div class="fw-semibold small mb-1 text-truncate">${esc(u.nombre)}</div>
                            <div class="text-muted small mb-2">${esc(String(u.puntos_totales))} pts</div>
                            <div class="${bgs[vi]} text-white rounded-top-3 d-flex align-items-center justify-content-center ${sizes[vi]}"
                                 style="height:${heights[vi]};${vi === 2 ? 'background:#cd7f32;' : ''}">
                                ${medals[vi]}
                            </div>
                        </div>`;
                }).join('') +
            '</div>';
        });
});
<?php endif; ?>
</script>

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

    /* Capa decorativa sutil sobre el gradiente del hero público */
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
        min-height: 50vh;
        display: flex;
        align-items: center;
        box-shadow: 0 8px 30px rgba(0,0,0,0.12);
    }

    /* Capa decorativa sutil sobre el gradiente del hero autenticado */
    .hero-auth::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(ellipse at 70% 30%, rgba(255,255,255,0.06) 0%, transparent 50%);
        pointer-events: none;
    }

    /* ── Ajustar el main para que no tenga margen superior extra */
    main {
        position: relative;
    }

    /* Separadores de sección más sutiles */
    section + section {
        border-top: 1px solid rgba(0,0,0,0.04);
    }

    /* Títulos de sección con peso visual */
    section h2.fw-bold {
        letter-spacing: -0.3px;
    }

    @media (max-width: 768px) {
        .hero-public { min-height: 75vh; }
        .hero-auth {
            min-height: auto;
            padding-top: 3rem !important;
            padding-bottom: 3rem !important;
            border-radius: 0 0 20px 20px;
        }
        .hero-auth .d-flex.gap-4 { flex-wrap: wrap; gap: 1rem !important; }
    }
</style>

<?php include __DIR__ . '/partials/footer.php'; ?>
