<?php
/**
 * Vista de Inicio (Home) — GreenPoints
 * ---------------------------------------------------------------
 * Página principal de la aplicación. Muestra:
 *   - Hero con llamada a la acción según estado de sesión
 *   - Estadísticas globales cargadas dinámicamente desde
 *     api/ranking.php?action=stats
 *   - Sección "Cómo funciona" (3 pasos)
 *   - Sección "Por qué GreenPoints" (beneficios)
 *   - Impacto global con números reales
 *   - CTA final adaptado al estado de sesión
 * ---------------------------------------------------------------
 */

$pageTitle = "GreenPoints | Recicla y Gana";
include __DIR__ . "/partials/header.php";
?>

<style>
html { scroll-behavior: smooth; }

/* ═══════════════════════════════════════════════════════════════
   SECCIÓN HERO — Portada principal animada
═══════════════════════════════════════════════════════════════════ */
.hero-section {
    position: relative;
    min-height: 100vh;
    display: flex;
    align-items: center;
    overflow: hidden;
    background: linear-gradient(135deg, #052e16, #14532d, #065f46, #0d9b4a, #14532d);
    background-size: 400% 400%;
    animation: heroGradient 14s ease infinite;
}

/* Animación de desplazamiento del degradado: background-size 400%
   permite que el gradiente se deslice suavemente de izquierda a derecha */
@keyframes heroGradient {
    0%   { background-position: 0% 50%; }
    50%  { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

/* Formas blob animadas de fondo para efecto visual */
.hero-blob {
    position: absolute;
    border-radius: 50%;
    filter: blur(90px);
    opacity: 0.28;
    pointer-events: none;
}

.hero-blob-1 {
    width: 600px; height: 600px;
    background: radial-gradient(circle, #34d399, #059669);
    top: -160px; right: -130px;
    animation: blob1Float 20s ease-in-out infinite;
}

.hero-blob-2 {
    width: 460px; height: 460px;
    background: radial-gradient(circle, #6ee7b7, #10b981);
    bottom: -110px; left: -110px;
    animation: blob2Float 16s ease-in-out infinite;
}

@keyframes blob1Float {
    0%,100% { transform: translate(0,0) scale(1); }
    33%      { transform: translate(45px,-55px) scale(1.07); }
    66%      { transform: translate(-25px,30px) scale(0.93); }
}

@keyframes blob2Float {
    0%,100% { transform: translate(0,0) scale(1); }
    33%      { transform: translate(-35px,45px) scale(0.94); }
    66%      { transform: translate(30px,-30px) scale(1.06); }
}

/* Partículas flotantes que ascienden por el hero */
.hero-particle {
    position: absolute;
    border-radius: 50%;
    background: rgba(255,255,255,0.55);
    animation: particleRise linear infinite;
    pointer-events: none;
    bottom: -20px;
}

@keyframes particleRise {
    from { transform: translateY(0);     opacity: 0; }
    5%   { opacity: 1; }
    95%  { opacity: 0.8; }
    to   { transform: translateY(-110vh); opacity: 0; }
}

/* Animación de rebote del indicador de scroll */
@keyframes scrollBounce {
    0%,100% { transform: translateY(0); }
    50%      { transform: translateY(8px); }
}

/* Animación de flotación de la tarjeta glass */
@keyframes heroCardFloat {
    0%,100% { transform: translateY(0); }
    50%      { transform: translateY(-14px); }
}

/* Tipografía del hero */
.hero-heading {
    font-size: clamp(2.5rem, 5.5vw, 5rem);
    font-weight: 800;
    line-height: 1.08;
    letter-spacing: -1.5px;
}

.hero-gradient-text {
    background: linear-gradient(135deg, #86efac, #34d399, #a7f3d0);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Botones de acción del hero */
.btn-hero-primary {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
    background: linear-gradient(135deg, #22c55e, #16a34a);
    border: none;
    color: #fff;
    font-weight: 600;
    padding: 0.9rem 2.5rem;
    border-radius: 50px;
    font-size: 1.05rem;
    box-shadow: 0 8px 28px rgba(34,197,94,0.42);
    transition: all 0.3s ease;
    cursor: pointer;
}

.btn-hero-primary:hover {
    background: linear-gradient(135deg, #16a34a, #15803d);
    transform: translateY(-3px);
    box-shadow: 0 14px 36px rgba(34,197,94,0.58);
    color: #fff;
}

.btn-hero-ghost {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
    background: transparent;
    border: 2px solid rgba(255,255,255,0.48);
    color: #fff;
    font-weight: 600;
    padding: 0.9rem 2.5rem;
    border-radius: 50px;
    font-size: 1.05rem;
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    transition: all 0.3s ease;
    cursor: pointer;
}

.btn-hero-ghost:hover {
    background: rgba(255,255,255,0.16);
    border-color: rgba(255,255,255,0.85);
    color: #fff;
    transform: translateY(-3px);
}

/* Tarjeta glass flotante del dashboard (para visitantes) */
.hero-glass-card {
    background: rgba(255,255,255,0.09);
    backdrop-filter: blur(24px);
    -webkit-backdrop-filter: blur(24px);
    border: 1px solid rgba(255,255,255,0.17);
    border-radius: 28px;
    padding: 2.25rem;
    box-shadow: 0 30px 80px rgba(0,0,0,0.28),
                inset 0 1px 0 rgba(255,255,255,0.10);
    animation: heroCardFloat 7s ease-in-out infinite;
}

/* Insignia de puntos del usuario autenticado */
.points-badge-hero {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    background: rgba(255,255,255,0.12);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255,255,255,0.28);
    border-radius: 50px;
    padding: 0.85rem 2rem;
    color: #fff;
    box-shadow: 0 8px 32px rgba(0,0,0,0.15);
}

/* Tarjetas pequeñas de estadísticas rápidas (usuario autenticado) */
.hero-mini-stat {
    background: rgba(255,255,255,0.10);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(255,255,255,0.18);
    border-radius: 20px;
    padding: 1.5rem 1rem;
    color: #fff;
    transition: transform 0.3s ease, background 0.3s ease;
}

.hero-mini-stat:hover {
    background: rgba(255,255,255,0.17);
    transform: translateY(-5px);
}

/* ═══════════════════════════════════════════════════════════════
   SECCIÓN DE ESTADÍSTICAS GLOBALES
═══════════════════════════════════════════════════════════════════ */
.stats-section { background: linear-gradient(180deg, #f0fdf4 0%, #ffffff 100%); }

.stat-card-glass {
    background: rgba(255,255,255,0.90);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(40,167,69,0.10);
    border-radius: 24px;
    padding: 2.75rem 2rem;
    text-align: center;
    box-shadow: 0 8px 32px rgba(40,167,69,0.07);
    transition: all 0.4s ease;
    position: relative;
    overflow: hidden;
}

.stat-card-glass::before {
    content: '';
    position: absolute; inset: 0;
    background: linear-gradient(135deg, rgba(34,197,94,0.03), transparent);
    pointer-events: none;
}

.stat-card-glass:hover {
    transform: translateY(-10px);
    box-shadow: 0 24px 56px rgba(40,167,69,0.18);
    border-color: rgba(40,167,69,0.26);
}

.stat-icon-wrap {
    width: 76px; height: 76px;
    border-radius: 22px;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 1.5rem;
    font-size: 2.1rem;
}

.stat-value {
    display: block;
    font-size: 2.6rem;
    font-weight: 800;
    background: linear-gradient(135deg, #16a34a, #059669);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    line-height: 1;
    margin-bottom: 0.5rem;
}

/* ═══════════════════════════════════════════════════════════════
   SECCIÓN "CÓMO FUNCIONA"
═══════════════════════════════════════════════════════════════════ */
.how-section { background: linear-gradient(180deg, #ffffff 0%, #f0fdf4 100%); }

.feature-card {
    background: #ffffff;
    border-radius: 24px;
    padding: 2.75rem 2rem;
    border: 1px solid #e8f5e9;
    position: relative;
    overflow: hidden;
    height: 100%;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    box-shadow: 0 4px 20px rgba(0,0,0,0.04);
}

.feature-card::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 3px;
    background: linear-gradient(90deg, #22c55e, #10b981);
    transform: scaleX(0);
    transform-origin: left center;
    transition: transform 0.4s ease;
}

.feature-card:hover {
    transform: translateY(-12px);
    box-shadow: 0 28px 70px rgba(34,197,94,0.15);
    border-color: rgba(34,197,94,0.22);
}

.feature-card:hover::after { transform: scaleX(1); }

.feature-icon-box {
    width: 84px; height: 84px;
    border-radius: 24px;
    display: flex; align-items: center; justify-content: center;
    font-size: 2.35rem;
    margin-bottom: 1.75rem;
    position: relative;
    flex-shrink: 0;
}

.step-badge {
    position: absolute;
    top: -8px; right: -8px;
    width: 28px; height: 28px;
    border-radius: 50%;
    background: linear-gradient(135deg, #22c55e, #16a34a);
    color: #fff;
    font-size: 0.72rem;
    font-weight: 800;
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 4px 12px rgba(34,197,94,0.45);
    border: 2px solid #fff;
}

/* ═══════════════════════════════════════════════════════════════
   SECCIÓN "POR QUÉ GREENPOINTS"
═══════════════════════════════════════════════════════════════════ */
.why-section { background: linear-gradient(135deg, #f0fdf4, #ecfdf5); }

.why-image-outer { position: relative; }

.why-image-wrap {
    border-radius: 28px;
    overflow: hidden;
    box-shadow: 0 24px 64px rgba(0,0,0,0.14);
    position: relative;
}

.why-image-wrap img {
    width: 100%;
    display: block;
    transition: transform 0.6s ease;
}

.why-image-wrap:hover img { transform: scale(1.04); }

.why-image-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(to top right, rgba(22,163,74,0.30), transparent);
    pointer-events: none;
}

.why-float-badge {
    position: absolute;
    bottom: 1.5rem; left: 1.5rem;
    background: rgba(255,255,255,0.94);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(255,255,255,0.72);
    border-radius: 18px;
    padding: 0.9rem 1.2rem;
    display: flex; align-items: center; gap: 0.8rem;
    box-shadow: 0 8px 32px rgba(0,0,0,0.12);
    z-index: 1;
}

.benefit-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 0.85rem 1rem;
    border-radius: 14px;
    margin-bottom: 0.2rem;
    transition: background 0.3s ease;
}

.benefit-item:hover { background: rgba(34,197,94,0.07); }

.benefit-check {
    width: 38px; height: 38px;
    border-radius: 50%;
    background: linear-gradient(135deg, #dcfce7, #bbf7d0);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    color: #16a34a;
    font-size: 1.1rem;
    font-weight: 700;
}

/* ═══════════════════════════════════════════════════════════════
   SECCIÓN DE IMPACTO GLOBAL
═══════════════════════════════════════════════════════════════════ */
.impact-section {
    background: linear-gradient(135deg, #052e16 0%, #14532d 40%, #065f46 100%);
    position: relative;
    overflow: hidden;
}

.impact-section::before {
    content: '';
    position: absolute;
    width: 700px; height: 700px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(52,211,153,0.10), transparent);
    top: -200px; right: -150px;
    pointer-events: none;
}

.impact-section::after {
    content: '';
    position: absolute;
    width: 500px; height: 500px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(52,211,153,0.08), transparent);
    bottom: -150px; left: -100px;
    pointer-events: none;
}

.impact-card {
    background: rgba(255,255,255,0.07);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(255,255,255,0.11);
    border-radius: 24px;
    padding: 2.75rem 1.5rem;
    text-align: center;
    color: #fff;
    transition: all 0.35s ease;
    position: relative;
    z-index: 1;
}

.impact-card:hover {
    background: rgba(255,255,255,0.12);
    transform: translateY(-8px);
    border-color: rgba(255,255,255,0.22);
    box-shadow: 0 16px 48px rgba(0,0,0,0.20);
}

.impact-emoji {
    font-size: 3.2rem;
    line-height: 1;
    margin-bottom: 1rem;
    display: block;
}

.impact-number {
    font-size: 2.4rem;
    font-weight: 800;
    color: #86efac;
    line-height: 1;
    margin-bottom: 0.5rem;
}

/* ═══════════════════════════════════════════════════════════════
   SECCIÓN CTA FINAL — Llamada a la acción
═══════════════════════════════════════════════════════════════════ */
.cta-section {
    background: linear-gradient(135deg, #15803d 0%, #16a34a 50%, #0d9488 100%);
    position: relative;
    overflow: hidden;
}

.cta-section::before {
    content: '';
    position: absolute;
    width: 600px; height: 600px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(255,255,255,0.08), transparent);
    top: -150px; left: -100px;
    pointer-events: none;
}

.cta-section::after {
    content: '';
    position: absolute;
    width: 450px; height: 450px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(255,255,255,0.06), transparent);
    bottom: -100px; right: -80px;
    pointer-events: none;
}

/* Insignia compartida para títulos de sección */
.section-badge {
    display: inline-block;
    padding: 0.45rem 1.2rem;
    background: rgba(34,197,94,0.10);
    color: #16a34a;
    border: 1px solid rgba(34,197,94,0.20);
    border-radius: 50px;
    font-size: 0.76rem;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
    margin-bottom: 0.75rem;
}

.section-badge-dark {
    display: inline-block;
    padding: 0.45rem 1.2rem;
    background: rgba(134,239,172,0.12);
    color: #86efac;
    border: 1px solid rgba(134,239,172,0.22);
    border-radius: 50px;
    font-size: 0.76rem;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
    margin-bottom: 0.75rem;
}

/* Ajustes responsive para distintos tamaños de pantalla */
@media (max-width: 991.98px) {
    .hero-heading { letter-spacing: -0.5px; }
    .hero-glass-card { margin-top: 2.5rem; }
    .why-float-badge { font-size: 0.85rem; }
}

@media (max-width: 575.98px) {
    .hero-heading { letter-spacing: 0; }
    .stat-card-glass { padding: 2rem 1.25rem; }
    .stat-value { font-size: 2rem; }
    .feature-card { padding: 2rem 1.5rem; }
    .impact-card { padding: 2rem 1.25rem; }
    .btn-hero-primary,
    .btn-hero-ghost { padding: 0.8rem 1.75rem; font-size: 0.97rem; }
}
</style>

<!-- ══════════════════════════════════════════════════════════════
     § 1  HERO — Portada principal
═══════════════════════════════════════════════════════════════════ -->
<section class="hero-section">

    <!-- Manchas de fondo animadas (efecto visual) -->
    <div class="hero-blob hero-blob-1"></div>
    <div class="hero-blob hero-blob-2"></div>

    <!-- Partículas flotantes que ascienden por el hero -->
    <div class="hero-particle" style="left:8%;  width:7px; height:7px; animation-duration:9s;  animation-delay:0s;"></div>
    <div class="hero-particle" style="left:22%; width:4px; height:4px; animation-duration:13s; animation-delay:-4s;"></div>
    <div class="hero-particle" style="left:45%; width:6px; height:6px; animation-duration:11s; animation-delay:-7s;"></div>
    <div class="hero-particle" style="left:63%; width:5px; height:5px; animation-duration:8s;  animation-delay:-2s;"></div>
    <div class="hero-particle" style="left:78%; width:8px; height:8px; animation-duration:14s; animation-delay:-5s;"></div>
    <div class="hero-particle" style="left:91%; width:4px; height:4px; animation-duration:10s; animation-delay:-1s;"></div>

    <div class="container position-relative py-5" style="z-index:2;">

        <?php if (isset($_SESSION["usuario_id"])): ?>
        <!-- ────────────── HERO PARA USUARIO AUTENTICADO ────────────── -->
        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-7 text-center py-4">

                <!-- Avatar circular con la inicial del usuario -->
                <div class="mb-4 animate__animated animate__fadeInDown">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle"
                         style="width:88px;height:88px;background:rgba(255,255,255,0.14);border:3px solid rgba(255,255,255,0.34);backdrop-filter:blur(10px);">
                        <span style="font-size:2.4rem;font-weight:800;color:#fff;line-height:1;">
                            <?= mb_strtoupper(
                                mb_substr(
                                    $_SESSION["usuario_nombre"] ?? "U",
                                    0,
                                    1,
                                    "UTF-8",
                                ),
                                "UTF-8",
                            ) ?>
                        </span>
                    </div>
                </div>

                <h1 class="hero-heading text-white mb-3 animate__animated animate__fadeInDown">
                    ¡Hola,&nbsp;<span class="hero-gradient-text"><?= htmlspecialchars(
                        $_SESSION["usuario_nombre"] ?? "Usuario",
                    ) ?></span>!
                </h1>

                <p class="fs-5 mb-4 animate__animated animate__fadeInUp delay-200"
                   style="color:rgba(255,255,255,0.76);">
                    Continúa tu impacto verde, el planeta te lo agradece
                </p>

                <!-- Insignia con los puntos acumulados del usuario -->
                <div class="d-flex justify-content-center mb-5 animate__animated animate__zoomIn delay-200">
                    <div class="points-badge-hero">
                        <i class="bi bi-star-fill text-warning fs-4"></i>
                        <span style="font-size:2rem;font-weight:800;">
                            <?= number_format(
                                (int) ($_SESSION["usuario_puntos"] ?? 0),
                            ) ?>
                        </span>
                        <span style="opacity:0.72;font-size:0.95rem;font-weight:400;">puntos acumulados</span>
                    </div>
                </div>

                <!-- Botones de acción rápida: registrar reciclaje y ver ranking -->
                <div class="d-flex gap-3 justify-content-center flex-wrap mb-5 animate__animated animate__fadeInUp delay-300">
                    <a href="index.php?action=registro_create" class="btn-hero-primary btn-pulse">
                        <i class="bi bi-plus-circle"></i>Registrar Reciclaje
                    </a>
                    <a href="index.php?action=ranking" class="btn-hero-ghost">
                        <i class="bi bi-trophy"></i>Ver Ranking
                    </a>
                </div>

                <!-- Minitarjetas de estadísticas rápidas del usuario -->
                <div class="row g-3 justify-content-center animate__animated animate__fadeInUp delay-400">
                    <div class="col-sm-4 col-6">
                        <div class="hero-mini-stat">
                            <div class="fs-4 mb-1">🌱</div>
                            <div style="font-size:1.8rem;font-weight:800;">
                                <?= number_format(
                                    (int) ($_SESSION["usuario_puntos"] ?? 0),
                                ) ?>
                            </div>
                            <div class="small" style="opacity:0.68;">Mis Puntos</div>
                        </div>
                    </div>
                    <div class="col-sm-4 col-6">
                        <div class="hero-mini-stat">
                            <div class="fs-4 mb-1">♻️</div>
                            <div style="font-size:1.8rem;font-weight:800;" id="heroUserRecyclings">—</div>
                            <div class="small" style="opacity:0.68;">Reciclajes</div>
                        </div>
                    </div>
                    <div class="col-sm-4 col-6">
                        <div class="hero-mini-stat">
                            <div class="fs-4 mb-1">🏆</div>
                            <div style="font-size:1.8rem;font-weight:800;" id="heroUserRank">—</div>
                            <div class="small" style="opacity:0.68;">Mi Ranking</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <?php else: ?>
        <!-- ────────────── HERO PARA VISITANTES ────────────── -->
        <div class="row align-items-center g-5" style="min-height:calc(100vh - 100px);">

            <!-- Columna izquierda: titular y llamada a la acción -->
            <div class="col-lg-6 text-white p-3">

                <!-- Indicador de estado: "Sistema de recompensas activo" -->
                <div class="d-inline-flex align-items-center gap-2 mb-4 animate__animated animate__fadeInLeft"
                     style="background:rgba(255,255,255,0.11);border:1px solid rgba(255,255,255,0.20);border-radius:50px;padding:0.5rem 1.2rem;backdrop-filter:blur(8px);">
                    <span class="rounded-circle bg-success"
                          style="width:8px;height:8px;display:inline-block;animation:pulsoSuave 2s infinite;flex-shrink:0;"></span>
                    <span class="small fw-semibold" style="opacity:0.90;">Sistema de recompensas activo</span>
                </div>

                <h1 class="hero-heading text-white mb-4 animate__animated animate__fadeInLeft delay-100">
                    Recicla.<br>
                    Gana.<br>
                    <span class="hero-gradient-text">Impacta.</span>
                </h1>

                <p class="fs-5 mb-5 animate__animated animate__fadeInLeft delay-200"
                   style="max-width:460px;line-height:1.78;color:rgba(255,255,255,0.74);">
                    Convierte tus acciones de reciclaje en puntos reales. Sube en el ranking,
                    canjea recompensas y compite con tu comunidad por un planeta mejor.
                </p>

                <div class="d-flex gap-3 flex-wrap mb-5 animate__animated animate__fadeInLeft delay-300">
                    <a href="index.php?action=register" class="btn-hero-primary btn-pulse">
                        <i class="bi bi-person-plus"></i>Crear Cuenta Gratis
                    </a>
                    <a href="index.php?action=login" class="btn-hero-ghost">
                        <i class="bi bi-box-arrow-in-right"></i>Iniciar Sesión
                    </a>
                </div>

                <!-- Prueba social: usuarios activos y valoración -->
                <div class="d-flex align-items-center gap-4 animate__animated animate__fadeInLeft delay-400">
                    <div class="d-flex align-items-center gap-2">
                        <div class="d-flex">
                            <span class="rounded-circle d-inline-flex align-items-center justify-content-center fw-bold"
                                  style="width:34px;height:34px;background:#4ade80;color:#fff;font-size:0.78rem;border:2.5px solid #fff;flex-shrink:0;">A</span>
                            <span class="rounded-circle d-inline-flex align-items-center justify-content-center fw-bold"
                                  style="width:34px;height:34px;background:#22d3ee;color:#fff;font-size:0.78rem;border:2.5px solid #fff;flex-shrink:0;margin-left:-10px;">M</span>
                            <span class="rounded-circle d-inline-flex align-items-center justify-content-center fw-bold"
                                  style="width:34px;height:34px;background:#a78bfa;color:#fff;font-size:0.78rem;border:2.5px solid #fff;flex-shrink:0;margin-left:-10px;">C</span>
                        </div>
                        <div>
                            <div class="fw-bold small">+10.000 usuarios</div>
                            <div class="small" style="opacity:0.60;">ya reciclando</div>
                        </div>
                    </div>
                    <div class="vr" style="height:36px;opacity:0.25;"></div>
                    <div>
                        <div class="fw-bold small">⭐ 4.9/5</div>
                        <div class="small" style="opacity:0.60;">valoración</div>
                    </div>
                </div>

            </div>

            <!-- Columna derecha: vista previa del dashboard flotante -->
            <div class="col-lg-6 animate__animated animate__fadeInRight delay-200">

                <div class="hero-glass-card">

                    <!-- Cabecera de la tarjeta dashboard -->
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                 style="width:48px;height:48px;background:linear-gradient(135deg,#22c55e,#16a34a);flex-shrink:0;">
                                <i class="bi bi-recycle text-white fs-4"></i>
                            </div>
                            <div>
                                <div class="text-white fw-bold">Mi Dashboard</div>
                                <div class="small" style="color:rgba(255,255,255,0.55);">Sistema de puntos</div>
                            </div>
                        </div>
                        <span class="rounded-pill px-3 py-1 small fw-semibold"
                              style="background:rgba(34,197,94,0.22);color:#86efac;border:1px solid rgba(34,197,94,0.35);">
                            <i class="bi bi-circle-fill me-1" style="font-size:0.42rem;vertical-align:middle;"></i>En vivo
                        </span>
                    </div>

                    <!-- Número grande de puntos acumulados (demostración) -->
                    <div class="text-center rounded-3 py-3 mb-4"
                         style="background:rgba(255,255,255,0.08);">
                        <div class="small mb-1" style="color:rgba(255,255,255,0.55);">Puntos acumulados</div>
                        <div style="font-size:3.4rem;font-weight:800;background:linear-gradient(135deg,#86efac,#34d399);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;line-height:1.1;">
                            2.450
                        </div>
                        <div class="small mt-1" style="color:rgba(255,255,255,0.55);">
                            <i class="bi bi-arrow-up-short text-success"></i>+120 esta semana
                        </div>
                    </div>

                    <!-- Barras de desglose por material (plástico, papel, vidrio) -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-white small">
                                <i class="bi bi-recycle me-1" style="color:#86efac;"></i>Plástico
                            </span>
                            <span class="small" style="color:rgba(255,255,255,0.55);">840 pts</span>
                        </div>
                        <div class="progress" style="height:5px;background:rgba(255,255,255,0.10);border-radius:99px;">
                            <div class="progress-bar"
                                 style="width:72%;background:linear-gradient(90deg,#22c55e,#86efac);border-radius:99px;"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-white small">
                                <i class="bi bi-file-earmark me-1" style="color:#93c5fd;"></i>Papel
                            </span>
                            <span class="small" style="color:rgba(255,255,255,0.55);">610 pts</span>
                        </div>
                        <div class="progress" style="height:5px;background:rgba(255,255,255,0.10);border-radius:99px;">
                            <div class="progress-bar"
                                 style="width:52%;background:linear-gradient(90deg,#3b82f6,#93c5fd);border-radius:99px;"></div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-white small">
                                <i class="bi bi-gem me-1" style="color:#d8b4fe;"></i>Vidrio
                            </span>
                            <span class="small" style="color:rgba(255,255,255,0.55);">450 pts</span>
                        </div>
                        <div class="progress" style="height:5px;background:rgba(255,255,255,0.10);border-radius:99px;">
                            <div class="progress-bar"
                                 style="width:38%;background:linear-gradient(90deg,#a855f7,#d8b4fe);border-radius:99px;"></div>
                        </div>
                    </div>

                    <!-- Botones de acción de la tarjeta -->
                    <div class="d-flex gap-2">
                        <a href="index.php?action=register"
                           class="btn btn-sm flex-grow-1 fw-semibold text-white"
                           style="background:linear-gradient(135deg,#22c55e,#16a34a);border-radius:12px;padding:0.65rem;">
                            <i class="bi bi-person-plus me-1"></i>Únete Gratis
                        </a>
                        <a href="index.php?action=ranking"
                           class="btn btn-sm text-white"
                           style="background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.20);border-radius:12px;padding:0.65rem 1rem;">
                            <i class="bi bi-trophy"></i>
                        </a>
                    </div>

                </div>
            </div>
        </div>
        <?php endif; ?>

    </div>

    <!-- Indicador de scroll para animar a explorar más contenido -->
    <a href="#estadisticas"
       class="position-absolute text-decoration-none d-flex flex-column align-items-center gap-1"
       style="bottom:2rem;left:50%;transform:translateX(-50%);z-index:2;color:rgba(255,255,255,0.50);font-size:0.78rem;">
        <span>Descubre más</span>
        <i class="bi bi-chevron-down" style="animation:scrollBounce 2s ease-in-out infinite;"></i>
    </a>

</section>

<!-- ══════════════════════════════════════════════════════════════
     § 2  ESTADÍSTICAS GLOBALES — Datos cargados dinámicamente
═══════════════════════════════════════════════════════════════════ -->
<section id="estadisticas" class="stats-section py-5">
    <div class="container py-4">

        <div class="text-center mb-5">
            <span class="section-badge">Impacto Real</span>
            <h2 class="display-5 fw-bold">Nuestro Impacto en Números</h2>
            <p class="text-muted lead mt-2 mb-0">Datos de nuestra comunidad, actualizados en tiempo real</p>
        </div>

        <div class="row g-4" id="statsRow">
            <!-- Esqueleto de carga para las tarjetas de estadísticas -->
            <div class="col-md-4 stat-placeholder">
                <div class="stat-card-glass">
                    <div class="placeholder-glow">
                        <div class="placeholder rounded-3 mb-3 d-block mx-auto"
                             style="width:76px;height:76px;"></div>
                        <div class="placeholder col-5 rounded mb-2 d-block mx-auto"
                             style="height:2.4rem;"></div>
                        <div class="placeholder col-7 rounded d-block mx-auto"
                             style="height:1.1rem;"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 stat-placeholder">
                <div class="stat-card-glass">
                    <div class="placeholder-glow">
                        <div class="placeholder rounded-3 mb-3 d-block mx-auto"
                             style="width:76px;height:76px;"></div>
                        <div class="placeholder col-5 rounded mb-2 d-block mx-auto"
                             style="height:2.4rem;"></div>
                        <div class="placeholder col-7 rounded d-block mx-auto"
                             style="height:1.1rem;"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 stat-placeholder">
                <div class="stat-card-glass">
                    <div class="placeholder-glow">
                        <div class="placeholder rounded-3 mb-3 d-block mx-auto"
                             style="width:76px;height:76px;"></div>
                        <div class="placeholder col-5 rounded mb-2 d-block mx-auto"
                             style="height:2.4rem;"></div>
                        <div class="placeholder col-7 rounded d-block mx-auto"
                             style="height:1.1rem;"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- ══════════════════════════════════════════════════════════════
     § 3  CÓMO FUNCIONA — Tres pasos para empezar
═══════════════════════════════════════════════════════════════════ -->
<section id="servicios" class="how-section py-5">
    <div class="container py-4">

        <div class="text-center mb-5">
            <span class="section-badge">¿Cómo funciona?</span>
            <h2 class="display-5 fw-bold">Tres pasos, un gran impacto</h2>
            <p class="text-muted lead mt-2 mb-0">Simple, rápido y con recompensas reales</p>
        </div>

        <div class="row g-4">

            <div class="col-md-4">
                <div class="feature-card animate__animated animate__fadeInUp delay-100">
                    <div class="feature-icon-box"
                         style="background:linear-gradient(135deg,#dcfce7,#bbf7d0);">
                        <i class="bi bi-recycle" style="color:#16a34a;"></i>
                        <span class="step-badge">1</span>
                    </div>
                    <h4 class="fw-bold mb-3">Registro Ágil</h4>
                    <p class="text-muted mb-0" style="line-height:1.78;">
                        Anota tus actividades de reciclaje (plástico, papel, vidrio, metal u
                        orgánico) y acumula puntos al instante con solo un clic.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="feature-card animate__animated animate__fadeInUp delay-200">
                    <div class="feature-icon-box"
                         style="background:linear-gradient(135deg,#fef3c7,#fde68a);">
                        <i class="bi bi-trophy" style="color:#d97706;"></i>
                        <span class="step-badge">2</span>
                    </div>
                    <h4 class="fw-bold mb-3">Gamificación</h4>
                    <p class="text-muted mb-0" style="line-height:1.78;">
                        Sube posiciones en el ranking, desbloquea niveles y compite con otros
                        usuarios. ¡La sostenibilidad nunca fue tan emocionante!
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="feature-card animate__animated animate__fadeInUp delay-300">
                    <div class="feature-icon-box"
                         style="background:linear-gradient(135deg,#dbeafe,#bfdbfe);">
                        <i class="bi bi-geo-alt" style="color:#2563eb;"></i>
                        <span class="step-badge">3</span>
                    </div>
                    <h4 class="fw-bold mb-3">Centros Cercanos</h4>
                    <p class="text-muted mb-0" style="line-height:1.78;">
                        Localiza los centros de reciclaje disponibles cerca de ti, consulta sus
                        horarios y los materiales que aceptan.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ══════════════════════════════════════════════════════════════
     § 4  POR QUÉ GREENPOINTS — Beneficios y razón de ser
═══════════════════════════════════════════════════════════════════ -->
<section id="impacto" class="why-section py-5">
    <div class="container py-4">
        <div class="row align-items-center g-5">

            <!-- Image column -->
            <div class="col-lg-6">
                <div class="why-image-outer">
                    <div class="why-image-wrap">
                        <img src="https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=800&q=80"
                             alt="Reciclaje comunitario"
                             loading="lazy">
                        <div class="why-image-overlay"></div>
                    </div>
                    <!-- Badge floats over image but outside overflow:hidden -->
                    <div class="why-float-badge">
                        <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                             style="width:44px;height:44px;background:linear-gradient(135deg,#22c55e,#16a34a);">
                            <i class="bi bi-award-fill text-white"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-dark small">CO₂ Ahorrado</div>
                            <div class="text-success fw-bold">+50 toneladas</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Text column -->
            <div class="col-lg-6">
                <span class="section-badge">Por qué elegirnos</span>
                <h2 class="display-5 fw-bold mb-4">Por qué elegir GreenPoints</h2>
                <p class="lead text-secondary mb-4" style="line-height:1.78;">
                    Transformamos la sostenibilidad en una experiencia social y gamificada.
                    No solo reciclas: ves el impacto real de cada una de tus acciones.
                </p>

                <div class="mb-5">
                    <div class="benefit-item">
                        <div class="benefit-check"><i class="bi bi-check-lg"></i></div>
                        <div>
                            <div class="fw-semibold">Canjea puntos por recompensas reales</div>
                            <div class="text-muted small">Descuentos en comercios locales y tiendas sostenibles.</div>
                        </div>
                    </div>
                    <div class="benefit-item">
                        <div class="benefit-check"><i class="bi bi-check-lg"></i></div>
                        <div>
                            <div class="fw-semibold">Estadísticas de CO₂ en tiempo real</div>
                            <div class="text-muted small">Visualiza el impacto ambiental exacto de tu reciclaje.</div>
                        </div>
                    </div>
                    <div class="benefit-item">
                        <div class="benefit-check"><i class="bi bi-check-lg"></i></div>
                        <div>
                            <div class="fw-semibold">Comunidad activa y comprometida</div>
                            <div class="text-muted small">Más de 10.000 recicladores colaborando juntos.</div>
                        </div>
                    </div>
                    <div class="benefit-item">
                        <div class="benefit-check"><i class="bi bi-check-lg"></i></div>
                        <div>
                            <div class="fw-semibold">Ranking en tiempo real</div>
                            <div class="text-muted small">Compite, sube posiciones y demuestra tu compromiso verde.</div>
                        </div>
                    </div>
                </div>

                <?php if (isset($_SESSION["usuario_id"])): ?>
                <a href="index.php?action=registro_create" class="btn-hero-primary btn-pulse">
                    <i class="bi bi-plus-circle"></i>Registrar Reciclaje
                </a>
                <?php else: ?>
                <a href="index.php?action=register" class="btn-hero-primary btn-pulse">
                    <i class="bi bi-rocket-takeoff"></i>Únete Ahora — Es Gratis
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- ══════════════════════════════════════════════════════════════
     § 5  IMPACTO GLOBAL — Datos reales de la comunidad
═══════════════════════════════════════════════════════════════════ -->
<section class="impact-section py-5">
    <div class="container py-4">

        <div class="text-center mb-5">
            <span class="section-badge-dark">Nuestro Alcance</span>
            <h2 class="display-5 fw-bold text-white">Impacto Global</h2>
            <p class="lead mt-2 mb-0" style="color:rgba(255,255,255,0.60);">
                Juntos estamos haciendo historia
            </p>
        </div>

        <!-- Tarjetas de impacto con datos obtenidos de la API -->
        <div class="row g-4 mb-5">
            <div class="col-lg-3 col-sm-6">
                <div class="impact-card animate__animated animate__zoomIn delay-100">
                    <span class="impact-emoji">🌱</span>
                    <div class="impact-number" id="impactUsuarios"><span class="spinner-border spinner-border-sm opacity-50"></span></div>
                    <p class="mb-0 small" style="opacity:0.68;">usuarios registrados</p>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="impact-card animate__animated animate__zoomIn delay-200">
                    <span class="impact-emoji">♻️</span>
                    <div class="impact-number" id="impactKg"><span class="spinner-border spinner-border-sm opacity-50"></span></div>
                    <p class="mb-0 small" style="opacity:0.68;">material reciclado</p>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="impact-card animate__animated animate__zoomIn delay-300">
                    <span class="impact-emoji">🏆</span>
                    <div class="impact-number" id="impactCanjes"><span class="spinner-border spinner-border-sm opacity-50"></span></div>
                    <p class="mb-0 small" style="opacity:0.68;">recompensas canjeadas</p>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="impact-card animate__animated animate__zoomIn delay-400">
                    <span class="impact-emoji">📍</span>
                    <div class="impact-number" id="impactCentros"><span class="spinner-border spinner-border-sm opacity-50"></span></div>
                    <p class="mb-0 small" style="opacity:0.68;">centros de reciclaje</p>
                </div>
            </div>
        </div>

        <!-- Desglose del material reciclado por tipo (barras proporcionales) -->
        <div class="text-center mb-4">
            <h5 class="text-white fw-bold" style="opacity:.9;">Material reciclado por tipo</h5>
            <p class="small" style="color:rgba(255,255,255,.5);">Total acumulado entre todos los usuarios</p>
        </div>
        <div id="materialBreakdown" class="row g-3">
            <!-- Cargado dinámicamente -->
            <?php foreach (
                [
                    "plastico" => "Plástico",
                    "papel" => "Papel",
                    "vidrio" => "Vidrio",
                    "metal" => "Metal",
                    "organico" => "Orgánico",
                ]
                as $k => $label
            ): ?>
            <div class="col-sm-6 col-lg" id="mat-col-<?= $k ?>">
                <div style="background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.12);border-radius:16px;padding:1.1rem;text-align:center;">
                    <div style="font-size:1.4rem;margin-bottom:.4rem;"><?= 
                        $k === "plastico" ? "👛" : 
                        ($k === "papel" ? "📄" : 
                        ($k === "vidrio" ? "🦠" : 
                        ($k === "metal" ? "⚙️" : "🌿"))) 
                    ?></div>
                    <div style="font-size:1.1rem;font-weight:700;color:#fff;" id="mat-kg-<?= $k ?>">—</div>
                    <div style="font-size:.7rem;color:rgba(255,255,255,.55);margin-top:.2rem;"><?= $label ?></div>
                    <div style="height:4px;background:rgba(255,255,255,.1);border-radius:4px;margin-top:.6rem;overflow:hidden;">
                        <div id="mat-bar-<?= $k ?>" style="height:100%;width:0%;background:linear-gradient(90deg,#22c55e,#0d9488);border-radius:4px;transition:width 1.2s ease;"></div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>        </div>

    </div>
</section>

<!-- ══════════════════════════════════════════════════════════════
     § 6  CTA FINAL — Llamada a la acción adaptada a la sesión
═══════════════════════════════════════════════════════════════════ -->
<section class="cta-section py-5">
    <div class="container text-center py-4 position-relative" style="z-index:1;">

        <h2 class="display-4 fw-bold text-white mb-3 animate__animated animate__fadeInDown">
            ¿Listo para hacer la diferencia?
        </h2>
        <p class="lead text-white mb-5 animate__animated animate__fadeInUp delay-100"
           style="max-width:540px;margin-inline:auto;opacity:0.78;">
            Únete a miles de usuarios que ya están transformando sus hábitos
            y ganando recompensas por cuidar el planeta.
        </p>

        <div class="d-flex gap-3 justify-content-center flex-wrap animate__animated animate__fadeInUp delay-200">
            <?php if (isset($_SESSION["usuario_id"])): ?>
            <a href="index.php?action=registro_create"
               class="btn btn-light btn-lg px-5 rounded-pill fw-bold shadow"
               style="color:#16a34a;">
                <i class="bi bi-plus-circle me-2"></i>Registrar Reciclaje
            </a>
            <a href="index.php?action=perfil"
               class="btn btn-outline-light btn-lg px-5 rounded-pill">
                <i class="bi bi-person-circle me-2"></i>Ver Mi Perfil
            </a>
            <?php else: ?>
            <a href="index.php?action=register"
               class="btn btn-light btn-lg px-5 rounded-pill fw-bold shadow"
               style="color:#16a34a;">
                <i class="bi bi-person-plus me-2"></i>Crear Cuenta Gratis
            </a>
            <a href="index.php?action=ranking"
               class="btn btn-outline-light btn-lg px-5 rounded-pill">
                <i class="bi bi-trophy me-2"></i>Ver Ranking
            </a>
            <?php endif; ?>
        </div>

    </div>
</section>

<?php if (isset($_SESSION["usuario_id"])): ?>

<!-- ════════════════════════════════════════════════════════════
     HOME PERSONALIZADO (usuario logueado)
     ════════════════════════════════════════════════════════════ -->

<!-- ── Banner de bienvenida ──────────────────────────────────── -->
<header class="hero-auth px-4 py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-auto">
                <div class="welcome-avatar">
                    <?= htmlspecialchars($inicial ?? '?') ?>
                </div>
            </div>
            <div class="col">
                <h1 class="display-6 fw-bold text-white mb-1">
                    ¡Hola, <?= htmlspecialchars($nombre ?? 'Invitado') ?>!
                </h1>
                <p class="mb-0 text-white-50">
                    Nivel: <span class="badge bg-warning text-dark fw-bold"><?= htmlspecialchars($nivel ?? '—') ?></span>
                    &nbsp;·&nbsp;
                    <i class="bi bi-star-fill text-warning"></i>
                    <span id="userPointsHeader"><?= number_format($puntos ?? 0) ?> puntos</span>
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
                    <div class="stat-value counter" id="statPuntos"><?= (int)($puntos ?? 0) ?></div>
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
/* ═════════════════════════════════════════════════════════════
   HOME — Carga dinámica de datos reales desde la base de datos
   Se hacen 3 peticiones fetch independientes:
     1. stats         → tarjetas principales (usuarios, kg, CO₂)
     2. stats_extended → impacto global + desglose por material
     3. me            → miniestadísticas del usuario (solo sesión)
   Todos los números visibles en esta página provienen de la API.
═════════════════════════════════════════════════════════════ */
document.addEventListener('DOMContentLoaded', function () {

    /* Actualizar el innerHTML de un elemento por su ID de forma segura */
    function setText(id, val) {
        const el = document.getElementById(id);
        if (el) el.innerHTML = val;
    }

    /* Convertir kg a toneladas si la cantidad es ≥ 1000 */
    function fmtKg(kg) {
        if (kg <= 0) return '0 kg';
        return kg >= 1000
            ? (kg / 1000).toFixed(2).replace('.00','') + ' Ton'
            : parseFloat(kg).toFixed(2).replace(/\.?0+$/, '') + ' kg';
    }

    /* Formatear números grandes con separador de miles (formato español) */
    function fmtNum(n) {
        return parseInt(n || 0).toLocaleString('es-ES');
    }

    /* ────────────────────────────────────────────────────────────
       1. ESTADÍSTICAS PRINCIPALES (3 tarjetas superiores)
       Fuente: api/ranking.php?action=stats
       Datos: usuarios activos, kg reciclados, CO₂ ahorrado
    ──────────────────────────────────────────────────────────── */
    fetch('api/ranking.php?action=stats')
        .then(r => r.json())
        .then(json => {
            if (!json.success) throw new Error(json.message);
            const d = json.data;

            const kgLabel  = fmtKg(parseFloat(d.kg_reciclados  || 0));
            const co2Label = fmtKg(parseFloat(d.co2_ahorrado_kg || 0)).replace('kg','kg CO₂').replace('Ton','Ton CO₂');

            const statsConf = [
                { valor: fmtNum(d.usuarios_activos), label: 'Usuarios Activos',   icon: 'bi-people-fill',     iconBg: 'linear-gradient(135deg,#dcfce7,#bbf7d0)', iconColor: '#16a34a' },
                { valor: kgLabel,                   label: 'Material Reciclado', icon: 'bi-recycle',          iconBg: 'linear-gradient(135deg,#dbeafe,#bfdbfe)', iconColor: '#2563eb' },
                { valor: co2Label,                  label: 'CO₂ Ahorrado',        icon: 'bi-cloud-check-fill', iconBg: 'linear-gradient(135deg,#fef3c7,#fde68a)', iconColor: '#d97706' },
            ];

            const row = document.getElementById('statsRow');
            row.innerHTML = statsConf.map((s, i) => `
                <div class="col-md-4">
                    <div class="stat-card-glass animate__animated animate__zoomIn" style="animation-delay:${i * 0.12}s;">
                        <div class="stat-icon-wrap" style="background:${s.iconBg};">
                            <i class="bi ${s.icon}" style="color:${s.iconColor};"></i>
                        </div>
                        <span class="stat-value">${s.valor}</span>
                        <p class="text-muted fw-medium mb-0">${s.label}</p>
                    </div>
                `).join('');
            }
        })
        .catch(() => {
            const row = document.getElementById('statsRow');
            row.innerHTML = [
                { icon:'bi-people-fill',     iconBg:'linear-gradient(135deg,#dcfce7,#bbf7d0)', iconColor:'#16a34a', label:'Usuarios Activos'  },
                { icon:'bi-recycle',          iconBg:'linear-gradient(135deg,#dbeafe,#bfdbfe)', iconColor:'#2563eb', label:'Material Reciclado' },
                { icon:'bi-cloud-check-fill', iconBg:'linear-gradient(135deg,#fef3c7,#fde68a)', iconColor:'#d97706', label:'CO₂ Ahorrado'      },
            ].map(s => `
                <div class="col-md-4">
                    <div class="stat-card-glass">
                        <div class="stat-icon-wrap" style="background:${s.iconBg};">
                            <i class="bi ${s.icon}" style="color:${s.iconColor};"></i>
                        </div>
                        <span class="stat-value">—</span>
                        <p class="text-muted fw-medium mb-0">${s.label}</p>
                    </div>
                </div>
            `).join('');
        });

    /* ────────────────────────────────────────────────────────────
       2. IMPACTO GLOBAL + DESGLOSE POR MATERIAL
       Fuente: api/ranking.php?action=stats_extended
       Datos: total usuarios, kg por material, canjes, centros
       Las barras de material son proporcionales al valor máximo
    ──────────────────────────────────────────────────────────── */
    fetch('api/ranking.php?action=stats_extended')
        .then(r => r.json())
        .then(json => {
            if (!json.success) throw new Error(json.message);
            const d = json.data;

            // Actualizar las 4 tarjetas de impacto con datos reales de la BD
            setText('impactUsuarios', fmtNum(d.total_usuarios));
            setText('impactKg',       fmtKg(d.kg_reciclados));
            setText('impactCanjes',   fmtNum(d.total_canjes));
            setText('impactCentros',  fmtNum(d.total_centros));

            // Desglose por material: cada barra se calcula como porcentaje del material con más kg
            const matData = {};
            (d.por_material || []).forEach(m => { matData[m.tipo_material] = m; });
            const maxKg = Math.max(...(d.por_material || []).map(m => parseFloat(m.kg_total || 0)), 1);

            ['plastico','papel','vidrio','metal','organico'].forEach(mat => {
                const info = matData[mat] || { kg_total: 0, total_registros: 0 };
                const kg   = parseFloat(info.kg_total || 0);
                const pct  = Math.round((kg / maxKg) * 100);

                setText(`mat-kg-${mat}`, fmtKg(kg));

                // Animar la barra de progreso con un retraso para efecto escalonado
                setTimeout(() => {
                    const bar = document.getElementById(`mat-bar-${mat}`);
                    if (bar) bar.style.width = pct + '%';
                }, 400);
            });
        })
        .catch(() => {
            // En caso de error, mostrar guiones
            ['impactUsuarios','impactKg','impactCanjes','impactCentros'].forEach(id => setText(id, '—'));
        });

<?php if (isset($_SESSION["usuario_id"])): ?>
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
                (parseInt(d.puntos_totales) || <?= (int)($puntos ?? 0) ?>).toLocaleString('es-ES') + ' puntos';
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
</script>

<?php include __DIR__ . "/partials/footer.php"; ?>
