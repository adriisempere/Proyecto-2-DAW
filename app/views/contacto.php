<?php
$pageTitle = "Contacto | GreenPoints";
include __DIR__ . "/partials/header.php";
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm p-4">
                <h1 class="display-6 fw-bold mb-3">Contacto</h1>
                <p class="text-muted mb-4">Si tienes preguntas, sugerencias o necesitas ayuda, escríbenos usando los datos de contacto o el formulario de abajo.</p>

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <div class="p-3 rounded-4" style="background:#ecfdf5;">
                            <h6 class="fw-semibold">Correo electrónico</h6>
                            <p class="mb-0">info@greenpoints.com</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded-4" style="background:#eff6ff;">
                            <h6 class="fw-semibold">Teléfono</h6>
                            <p class="mb-0">+34 900 123 456</p>
                        </div>
                    </div>
                </div>

                <form class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Nombre</label>
                        <input type="text" class="form-control" placeholder="Tu nombre" required>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Correo electrónico</label>
                        <input type="email" class="form-control" placeholder="tu@email.com" required>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Asunto</label>
                        <input type="text" class="form-control" placeholder="¿En qué podemos ayudarte?" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Mensaje</label>
                        <textarea class="form-control" rows="5" placeholder="Describe tu consulta" required></textarea>
                    </div>
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-success rounded-pill px-4">
                            Enviar mensaje
                        </button>
                    </div>
                </form>

                <div class="alert alert-secondary mt-4" role="alert">
                    <i class="bi bi-info-circle me-2"></i> Este formulario es informativo. Si deseas soporte real, utiliza el correo <strong>info@greenpoints.com</strong>.
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . "/partials/footer.php"; ?>
