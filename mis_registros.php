<?php
$pageTitle = 'Mis Registros | GreenPoints';
include __DIR__ . '/partials/header.php';
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-success">
            <i class="bi bi-clock-history me-2"></i>Mis Registros de Reciclaje
        </h2>
        <a href="index.php?action=nuevo_registro" class="btn btn-success">
            <i class="bi bi-plus-lg me-1"></i> Nuevo Registro
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="tablaRegistros">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Fecha</th>
                            <th>Material</th>
                            <th>Cantidad</th>
                            <th>Puntos</th>
                            <th>Centro</th>
                        </tr>
                    </thead>
                    <tbody id="misRegistros">
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="spinner-border text-success" role="status">
                                    <span class="visually-hidden">Cargando...</span>
                                </div>
                                <p class="mt-2 text-muted">Obteniendo tus registros...</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('misRegistros');

    // Función para escapar HTML (Previene XSS)
    function escapeHTML(str) {
        if (!str) return '';
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    // Función para formatear fecha
    function formatDate(dateString) {
        const options = { year: 'numeric', month: 'short', day: 'numeric' };
        return new Date(dateString).toLocaleDateString('es-ES', options);
    }

    fetch('api/registro.php?action=list')
        .then(response => response.json())
        .then(json => {
            if (json.success && Array.isArray(json.data)) {
                if (json.data.length === 0) {
                    container.innerHTML = `
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                <i class="bi bi-info-circle me-1"></i> Aún no tienes registros de reciclaje.
                            </td>
                        </tr>`;
                    return;
                }

                let html = '';
                json.data.forEach(r => {
                    html += `
                    <tr>
                        <td class="ps-4 fw-semibold">${formatDate(r.fecha)}</td>
                        <td><span class="badge bg-light text-dark border">${escapeHTML(r.tipo_material)}</span></td>
                        <td>${parseFloat(r.cantidad).toFixed(2)} kg</td>
                        <td><span class="text-success fw-bold">+${r.puntos_ganados} pts</span></td>
                        <td class="text-muted small">${r.centro_nombre ? escapeHTML(r.centro_nombre) : '<em>No especificado</em>'}</td>
                    </tr>`;
                });
                container.innerHTML = html;
            } else {
                throw new Error('Error en la respuesta');
            }
        })
        .catch(err => {
            container.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center py-4 text-danger">
                        <i class="bi bi-exclamation-triangle me-1"></i> Error al cargar los registros. Por favor, intenta de nuevo.
                    </td>
                </tr>`;
        });
});
</script>

<?php include __DIR__ . '/partials/footer.php'; ?>