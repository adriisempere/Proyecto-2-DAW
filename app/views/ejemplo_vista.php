<?php
/**
 * EJEMPLO DE USO DE HEADER Y FOOTER
 * 
 * Este archivo muestra cómo usar correctamente los partials header.php y footer.php
 * en cualquier vista de la aplicación.
 */

// Definir el título de la página (opcional)
$pageTitle = "Título de la Página - GreenPoints";

// Incluir el header
include __DIR__ . '/partials/header.php';
?>

<!-- AQUÍ VA EL CONTENIDO DE TU PÁGINA -->

<div class="container my-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <!-- Título de la sección -->
            <h1 class="display-4 fw-bold mb-4 text-center">
                <i class="bi bi-card-heading text-success me-3"></i>
                Título de tu Página
            </h1>
            
            <!-- Contenido de ejemplo -->
            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <h3 class="fw-bold mb-3">Sección de Contenido</h3>
                    <p class="text-muted">
                        Este es un ejemplo de cómo estructurar el contenido de tu página.
                        Puedes usar cualquier componente de Bootstrap 5 aquí.
                    </p>
                    
                    <!-- Ejemplo de formulario -->
                    <form class="mt-4">
                        <div class="mb-3">
                            <label for="ejemplo" class="form-label fw-semibold">
                                <i class="bi bi-pencil me-2"></i>Campo de Ejemplo
                            </label>
                            <input 
                                type="text" 
                                class="form-control form-control-lg" 
                                id="ejemplo" 
                                placeholder="Escribe algo..."
                            >
                        </div>
                        
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="bi bi-check-circle me-2"></i>Enviar
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Cards de ejemplo -->
            <div class="row g-4 mt-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="bg-success bg-opacity-10 p-3 rounded-circle d-inline-block mb-3">
                                <i class="bi bi-recycle text-success fs-2"></i>
                            </div>
                            <h5 class="fw-bold">Característica 1</h5>
                            <p class="text-muted small">Descripción de la característica</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="bg-warning bg-opacity-10 p-3 rounded-circle d-inline-block mb-3">
                                <i class="bi bi-trophy text-warning fs-2"></i>
                            </div>
                            <h5 class="fw-bold">Característica 2</h5>
                            <p class="text-muted small">Descripción de la característica</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-circle d-inline-block mb-3">
                                <i class="bi bi-star text-primary fs-2"></i>
                            </div>
                            <h5 class="fw-bold">Característica 3</h5>
                            <p class="text-muted small">Descripción de la característica</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FIN DEL CONTENIDO DE TU PÁGINA -->

<?php
// Incluir el footer
include __DIR__ . '/partials/footer.php';
?>
