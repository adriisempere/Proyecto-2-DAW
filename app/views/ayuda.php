<?php
$pageTitle = "Ayuda | GreenPoints";
include __DIR__ . "/partials/header.php";
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm p-4">
                <h1 class="display-6 fw-bold mb-3">Ayuda</h1>
                <p class="text-muted mb-4">
                    Bienvenido al centro de ayuda de GreenPoints. Aquí tienes respuestas rápidas y recursos para resolver dudas frecuentes.
                </p>

                <div class="mb-4">
                    <h5 class="fw-semibold">¿Cómo puedo canjear mis puntos?</h5>
                    <p class="mb-2">Accede a la <a href="index.php?action=tienda">Tienda de Recompensas</a>, añade los productos al carrito y confirma el canje. Los códigos generados aparecerán en pantalla y también en <a href="index.php?action=mis_canjes">Mis Canjes</a>.</p>
                </div>

                <div class="mb-4">
                    <h5 class="fw-semibold">¿Dónde veo mis registros de reciclaje?</h5>
                    <p class="mb-2">En el menú de usuario, selecciona <strong>Mis Registros</strong> para revisar todas tus aportaciones y el impacto en puntos y kilos reciclados.</p>
                </div>

                <div class="mb-4">
                    <h5 class="fw-semibold">¿Qué hago si no puedo iniciar sesión?</h5>
                    <p class="mb-2">Verifica tu correo y contraseña, asegúrate de tener cookies habilitadas y, si el problema persiste, utiliza la página de <a href="index.php?action=contacto">Contacto</a> para solicitar soporte.</p>
                </div>

                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i> Si necesitas más detalles, visita las secciones de <a href="index.php?action=faq">FAQ</a> o envía una consulta desde <a href="index.php?action=contacto">Contacto</a>.
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . "/partials/footer.php"; ?>
