<?php
$pageTitle = 'Ranking Global | GreenPoints';
include __DIR__ . '/partials/header.php';
?>

<style>
    .rank-1 { background-color: rgba(255, 215, 0, 0.1) !important; } /* Oro */
    .rank-2 { background-color: rgba(192, 192, 192, 0.1) !important; } /* Plata */
    .rank-3 { background-color: rgba(205, 127, 50, 0.1) !important; } /* Bronce */
    
    .medal { font-size: 1.2rem; }
    .table-hover tbody tr { transition: transform 0.2s; }
    .table-hover tbody tr:hover { transform: scale(1.01); z-index: 10; }
</style>

<div class="container py-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold">
            <i class="bi bi-trophy-fill text-warning me-2"></i>Ranking Global GreenPoints
        </h2>
        <p class="text-muted">Los héroes del reciclaje que están cambiando el planeta.</p>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div id="rankingContainer">
                <div class="text-center py-5">
                    <div class="spinner-border text-success" role="status"></div>
                    <p class="mt-2 text-muted">Calculando posiciones...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('rankingContainer');

    function escapeHTML(str) {
        if (!str) return '';
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    function getMedal(pos) {
        if (pos == 1) return '<span class="medal">🥇</span>';
        if (pos == 2) return '<span class="medal">🥈</span>';
        if (pos == 3) return '<span class="medal">🥉</span>';
        return `<span class="fw-bold text-muted">#${pos}</span>`;
    }

    fetch('api/ranking.php?action=list')
        .then(r => r.json())
        .then(j => {
            if (j.success && Array.isArray(j.data)) {
                if (j.data.length === 0) {
                    container.innerHTML = '<div class="p-4 text-center text-muted">No hay datos suficientes para el ranking aún.</div>';
                    return;
                }

                let html = `
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Posición</th>
                                <th>Héroe</th>
                                <th>Puntos Totales</th>
                                <th>Total Reciclado</th>
                            </tr>
                        </thead>
                        <tbody>`;

                j.data.forEach(u => {
                    const rowClass = u.posicion <= 3 ? `rank-${u.posicion}` : '';
                    html += `
                    <tr class="${rowClass}">
                        <td class="ps-4">${getMedal(u.posicion)}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                    ${u.nombre.charAt(0).toUpperCase()}
                                </div>
                                <span class="fw-semibold">${escapeHTML(u.nombre)}</span>
                            </div>
                        </td>
                        <td>
                            <span class="badge rounded-pill bg-success px-3">${u.puntos_totales.toLocaleString()} pts</span>
                        </td>
                        <td class="text-muted">
                            <i class="bi bi-recycle me-1"></i>${Number(u.kg_reciclados || 0).toFixed(2)} kg
                        </td>
                    </tr>`;
                });

                html += '</tbody></table></div>';
                container.innerHTML = html;
            } else {
                container.innerHTML = '<div class="p-4 text-center text-muted">No hay datos de ranking disponibles.</div>';
            }
        })
        .catch(() => {
            container.innerHTML = '<div class="p-4 text-center text-danger"><i class="bi bi-x-circle me-2"></i>Error al conectar con el servidor.</div>';
        });
});
</script>

<?php include __DIR__ . '/partials/footer.php'; ?>