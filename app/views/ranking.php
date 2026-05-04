<?php
/**
 * Vista de Ranking Global — GreenPoints
 * ---------------------------------------------------------------
 * Muestra el top 100 de usuarios ordenados por puntos totales.
 * Si el usuario está autenticado, destaca su posición en la tabla.
 * Los datos se cargan desde:
 *   - api/ranking.php?action=list  → tabla principal
 *   - api/ranking.php?action=me    → posición del usuario actual
 * ---------------------------------------------------------------
 */

$pageTitle = 'Ranking | GreenPoints';
include __DIR__ . '/partials/header.php';
?>

<div class="container py-5">

    <!-- Cabecera -->
    <div class="text-center mb-5">
        <h1 class="fw-bold display-6">
            <i class="bi bi-trophy-fill text-warning me-2"></i>Ranking Global
        </h1>
        <p class="text-muted">Los mejores recicladores de la comunidad</p>
    </div>

    <!-- Tarjeta de posición del usuario autenticado -->
    <?php if (isset($_SESSION['usuario_id'])): ?>
        <div id="myPosition" class="alert alert-success d-flex align-items-center mb-4 d-none" role="alert">
            <i class="bi bi-person-circle fs-4 me-3 flex-shrink-0"></i>
            <div id="myPositionText"></div>
        </div>
    <?php endif; ?>

    <!-- Podio top 3 -->
    <div id="podium" class="row g-3 justify-content-center mb-5 d-none">

        <!-- 2º puesto -->
        <div class="col-auto text-center d-flex flex-column justify-content-end" style="min-width:130px">
            <div id="podium2name" class="fw-semibold small mb-1 text-truncate"></div>
            <div id="podium2pts"  class="text-muted small mb-2"></div>
            <div class="bg-secondary text-white rounded-top-3 d-flex align-items-center justify-content-center fw-bold fs-4"
                 style="height:80px">🥈</div>
        </div>

        <!-- 1er puesto -->
        <div class="col-auto text-center d-flex flex-column justify-content-end" style="min-width:130px">
            <div id="podium1name" class="fw-bold mb-1 text-truncate"></div>
            <div id="podium1pts"  class="text-muted small mb-2"></div>
            <div class="bg-warning text-white rounded-top-3 d-flex align-items-center justify-content-center fw-bold fs-3"
                 style="height:110px">🥇</div>
        </div>

        <!-- 3er puesto -->
        <div class="col-auto text-center d-flex flex-column justify-content-end" style="min-width:130px">
            <div id="podium3name" class="fw-semibold small mb-1 text-truncate"></div>
            <div id="podium3pts"  class="text-muted small mb-2"></div>
            <div class="rounded-top-3 d-flex align-items-center justify-content-center fw-bold fs-4"
                 style="height:60px; background:#cd7f32; color:white">🥉</div>
        </div>

    </div>

    <!-- Tabla principal -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div id="rankingContainer">
                <!-- Esqueleto de carga -->
                <div id="rankingSkeleton" class="p-4">
                    <?php for ($i = 0; $i < 6; $i++): ?>
                        <div class="d-flex align-items-center mb-3 placeholder-glow">
                            <span class="placeholder rounded me-3" style="width:32px;height:32px;"></span>
                            <span class="placeholder rounded col-3 me-3"></span>
                            <span class="placeholder rounded col-2 ms-auto"></span>
                            <span class="placeholder rounded col-2 ms-3"></span>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const myUid = <?= isset($_SESSION['usuario_id']) ? (int)$_SESSION['usuario_id'] : 'null' ?>;

    // ── Escapar texto para prevenir XSS al usar innerHTML ────────
    function esc(str) {
        const d = document.createElement('div');
        d.textContent = str ?? '';
        return d.innerHTML;
    }

    // ── Medalla o número según posición ──────────────────────────
    function medal(pos) {
        if (pos === 1) return '🥇';
        if (pos === 2) return '🥈';
        if (pos === 3) return '🥉';
        return `<span class="text-muted">${pos}</span>`;
    }

    // ── Cargar posición del usuario autenticado ───────────────────
    if (myUid) {
        fetch('api/ranking.php?action=me')
            .then(r => r.json())
            .then(json => {
                if (!json.success) return;
                const d   = json.data;
                const box = document.getElementById('myPosition');
                const txt = document.getElementById('myPositionText');
                txt.innerHTML =
                    `Estás en la posición <strong>#${esc(String(d.posicion))}</strong> · ` +
                    `<strong>${esc(String(d.puntos_totales))}</strong> pts · ` +
                    `${esc(parseFloat(d.kg_reciclados).toFixed(2))} kg reciclados`;
                box.classList.remove('d-none');
            })
            .catch(() => {});
    }

    // ── Cargar ranking principal ──────────────────────────────────
    fetch('api/ranking.php?action=list')
        .then(r => r.json())
        .then(json => {
            const container = document.getElementById('rankingContainer');
            const podiumEl  = document.getElementById('podium');

            if (!json.success || !Array.isArray(json.data)) {
                container.innerHTML = '<p class="text-muted text-center p-4">No hay datos de ranking disponibles.</p>';
                return;
            }

            const data = json.data;

            if (data.length === 0) {
                container.innerHTML = '<p class="text-muted text-center p-4">Aún no hay usuarios en el ranking. ¡Sé el primero!</p>';
                return;
            }

            // ── Podio top 3 ──────────────────────────────────────
            if (data.length >= 3) {
                [1, 2, 3].forEach(n => {
                    const u = data[n - 1];
                    document.getElementById(`podium${n}name`).textContent = u.nombre;
                    document.getElementById(`podium${n}pts`).textContent  = u.puntos_totales + ' pts';
                });
                podiumEl.classList.remove('d-none');
            }

            // ── Tabla ─────────────────────────────────────────────
            let rows = '';
            data.forEach(u => {
                const isMe = myUid && parseInt(u.id) === myUid;
                const kg   = parseFloat(u.kg_reciclados || 0).toFixed(2);
                rows += `
                    <tr class="${isMe ? 'table-success fw-semibold' : ''}">
                        <td class="ps-4">${medal(u.posicion)}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
                                     style="width:36px;height:36px;font-size:.9rem">
                                    ${esc(u.nombre.charAt(0).toUpperCase())}
                                </div>
                                <span>${esc(u.nombre)}</span>
                                ${isMe ? '<span class="badge bg-success ms-1">Tú</span>' : ''}
                            </div>
                        </td>
                        <td class="text-end fw-bold text-success">${esc(String(u.puntos_totales))}</td>
                        <td class="text-end pe-4 d-none d-md-table-cell text-muted">${esc(kg)} kg</td>
                        <td class="text-end pe-4 d-none d-md-table-cell text-muted">${esc(String(u.total_reciclajes))}</td>
                    </tr>
                `;
            });

            container.innerHTML = `
                <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4" style="width:60px">Pos.</th>
                            <th>Usuario</th>
                            <th class="text-end">Puntos</th>
                            <th class="text-end pe-4 d-none d-md-table-cell">Kg reciclados</th>
                            <th class="text-end pe-4 d-none d-md-table-cell">Reciclajes</th>
                        </tr>
                    </thead>
                    <tbody>${rows}</tbody>
                </table>
                </div>
            `;
        })
        .catch(() => {
            document.getElementById('rankingContainer').innerHTML =
                '<p class="text-danger text-center p-4"><i class="bi bi-exclamation-triangle me-2"></i>Error al cargar el ranking. Inténtalo más tarde.</p>';
        });
});
</script>

<?php include __DIR__ . '/partials/footer.php'; ?>