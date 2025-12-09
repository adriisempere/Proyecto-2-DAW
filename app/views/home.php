<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>GreenPoints</title>

    <!-- Bootstrap para diseño rápido -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Tu CSS personalizado -->
    <link rel="stylesheet" href="/css/estilos.css">
</head>

<body>

<?php include __DIR__ . '/partials/header.php'; ?>

<div class="container mt-5 text-center">
    <h1 class="display-4">Bienvenido a GreenPoints</h1>
    <p class="lead">Registra tu reciclaje, gana puntos y compite en el ranking.</p>

    <a href="?action=register" class="btn btn-success btn-lg">Registrarme</a>
    <a href="?action=login" class="btn btn-primary btn-lg">Iniciar sesión</a>
    <a href="?action=ranking" class="btn btn-warning btn-lg">Ver Ranking</a>
</div>

<?php include __DIR__ . '/partials/footer.php'; ?>

<!-- Tu JavaScript -->
<script src="/js/app.js"></script>
</body>
</html>
