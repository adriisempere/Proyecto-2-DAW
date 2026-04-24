<?php
session_start();

$pageTitle = "GreenPoints - Plataforma de Reciclaje con Recompensas";
include __DIR__ . '/partials/header.php';
?>

<!-- CSS externo -->
<link rel="stylesheet" href="assets/css/home.css">

<main>

<!-- HERO -->
<section id="hero" class="container py-5 text-center">

    <h1 class="display-4 fw-bold">
        GreenPoints - Plataforma de Reciclaje con Recompensas
    </h1>

    <p class="lead text-muted mb-4">
        Recicla, gana puntos y ayuda al planeta mientras compites con otros usuarios.
    </p>

    <?php if(isset($_SESSION['usuario_id'])): ?>
        <a href="index.php?action=perfil" class="btn btn-success btn-lg">
            Ir a mi perfil
        </a>
    <?php else: ?>
        <a href="index.php?action=register" class="btn btn-success btn-lg me-2">
            Crear Cuenta
        </a>

        <a href="index.php?action=login" class="btn btn-outline-success btn-lg">
            Iniciar Sesión
        </a>
    <?php endif; ?>

</section>


<!-- COMO FUNCIONA -->
<section id="como-funciona" class="container py-5">

    <h2 class="text-center mb-5">¿Cómo funciona?</h2>

    <div class="row text-center">

        <article class="col-md-4">
            <h4>1️⃣ Regístrate</h4>
            <p>Crea tu cuenta gratuita y empieza a reciclar.</p>
        </article>

        <article class="col-md-4">
            <h4>2️⃣ Recicla</h4>
            <p>Lleva tus residuos a centros autorizados.</p>
        </article>

        <article class="col-md-4">
            <h4>3️⃣ Gana recompensas</h4>
            <p>Obtén puntos y sube posiciones en el ranking.</p>
        </article>

    </div>

</section>


<!-- IMPACTO -->
<section id="impacto" class="container py-5">

<?php
/* FUTURO: datos desde BD */
$totalUsuarios = 10234;
$totalToneladas = 50;
$totalCentros = 120;
?>

<div class="row text-center">

    <div class="col-md-4">
        <h3><?= number_format($totalUsuarios) ?>+</h3>
        <p>Usuarios activos</p>
    </div>

    <div class="col-md-4">
        <h3><?= $totalToneladas ?> Ton</h3>
        <p>Material reciclado</p>
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


<!-- TESTIMONIOS -->
<section id="testimonios" class="container py-5 text-center">

    <h2 class="mb-5">Lo que dicen nuestros usuarios</h2>

    <blockquote class="blockquote">
        <p>"Gracias a GreenPoints ahora reciclo cada semana."</p>
        <footer class="blockquote-footer">Usuario GreenPoints</footer>
    </blockquote>

</section>

</main>

<?php include __DIR__ . '/partials/footer.php'; ?>