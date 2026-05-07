<?php
/**
 * Vista del Ranking Global — GreenPoints
 * ---------------------------------------------------------------
 * Muestra el top 100 de usuarios ordenados por puntos totales.
 * Si el usuario está autenticado, se resalta su posición en la tabla.
 * Los datos se cargan desde:
 *   - api/ranking.php?action=list  → tabla principal
 *   - api/ranking.php?action=me    → posición del usuario actual
 * ---------------------------------------------------------------
 */

$pageTitle = "Ranking | GreenPoints";
include __DIR__ . "/partials/header.php";
?>

<style>
/* ═══════════════════════════════════════════════════════════════
   RANKING — Estilos específicos de la página
   ═══════════════════════════════════════════════════════════════ */

/* ── Sección Hero del ranking ───────────────────────────────────── */
.ranking-hero {
    background: linear-gradient(135deg, #155724 0%, #28a745 45%, #20c997 100%);
    padding: 4rem 0 6.5rem;
    position: relative;
    overflow: hidden;
}
.ranking-hero::before {
    content: '';
    position: absolute;
    top: -40%; right: -8%;
    width: 480px; height: 480px;
    background: rgba(255,255,255,0.055);
    border-radius: 50%;
    pointer-events: none;
}
.ranking-hero::after {
    content: '';
    position: absolute;
    bottom: -30%; left: -5%;
    width: 360px; height: 360px;
    background: rgba(255,255,255,0.045);
    border-radius: 50%;
    pointer-events: none;
}
.hero-blob {
    position: absolute;
    border-radius: 50%;
    background: rgba(255,255,255,0.07);
    pointer-events: none;
}
.hero-blob-a { width: 110px; height: 110px; top: 18%; left: 9%;  animation: float 7s ease-in-out infinite; }
.hero-blob-b { width: 65px;  height: 65px;  bottom: 22%; right: 12%; animation: float 5.5s ease-in-out infinite reverse; }

/* Tarjeta de vidrio (glass) dentro del hero */
.hero-glass-card {
    background: rgba(255,255,255,0.13);
    backdrop-filter: blur(22px);
    -webkit-backdrop-filter: blur(22px);
    border: 1px solid rgba(255,255,255,0.22);
    border-radius: 22px;
    padding: 1.75rem 2.75rem;
    box-shadow: 0 8px 40px rgba(0,0,0,0.13);
    display: inline-block;
}

/* ── Elevación del contenido para solaparlo con el hero ──────────── */
.ranking-content {
    margin-top: -3.5rem;
    position: relative;
    z-index: 10;
}

/* ── Tarjeta de posición del usuario autenticado ─────────────────── */
.user-position-card {
    background: linear-gradient(135deg, rgba(40,167,69,0.1) 0%, rgba(32,201,151,0.07) 100%);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(40,167,69,0.22);
    border-radius: 16px;
    padding: 1rem 1.25rem;
    margin-bottom: 1.25rem;
    box-shadow: 0 4px 22px rgba(40,167,69,0.1), inset 0 1px 0 rgba(255,255,255,0.7);
}
.upc-icon {
    width: 48px; height: 48px;
    background: rgba(40,167,69,0.12);
    border: 1.5px solid rgba(40,167,69,0.18);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem; color: #28a745;
    flex-shrink: 0;
}

/* ── Sección del podio (top 3) ──────────────────────────────────── */
.podium-section {
    background: rgba(255,255,255,0.97);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255,255,255,0.8);
    border-radius: 20px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.07);
    padding: 2rem 1.5rem 0;
    margin-bottom: 1.25rem;
    overflow: hidden;
}
.podium-wrapper {
    display: flex;
    align-items: flex-end;
    justify-content: center;
    gap: 1rem;
}
.podium-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    min-width: 100px;
    max-width: 145px;
    flex: 1;
}
.podium-name {
    font-weight: 600;
    font-size: 0.85rem;
    max-width: 100%;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    color: #2c3e50;
    margin-bottom: 2px;
    text-align: center;
}
.podium-pts {
    font-size: 0.72rem;
    color: #6c757d;
    margin-bottom: 10px;
    font-weight: 500;
    text-align: center;
}
.podium-block {
    width: 100%;
    border-radius: 14px 14px 0 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 3px;
    padding: 0.55rem 0;
    position: relative;
    transition: transform 0.3s ease;
    cursor: default;
}
.podium-block::after {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: rgba(255,255,255,0.45);
    border-radius: 14px 14px 0 0;
}
.podium-block:hover { transform: translateY(-5px); }
.podium-medal {
    font-size: 2.2rem;
    line-height: 1.1;
    filter: drop-shadow(0 2px 5px rgba(0,0,0,0.22));
}
.podium-num {
    font-size: 0.78rem;
    font-weight: 800;
    color: rgba(255,255,255,0.65);
    letter-spacing: 0.04em;
}

/* Oro — 1er puesto */
.podium-1st .podium-block {
    height: 130px;
    background: linear-gradient(170deg, #ffe066 0%, #ffb800 45%, #e07b00 100%);
    box-shadow: 0 8px 28px rgba(255,185,0,0.55),
                0 2px 6px rgba(0,0,0,0.14),
                inset 0 1px 0 rgba(255,255,255,0.35);
    animation: goldGlow 3s ease-in-out infinite;
}
/* Plata — 2º puesto */
.podium-2nd .podium-block {
    height: 100px;
    background: linear-gradient(170deg, #ebebeb 0%, #bebebe 45%, #888 100%);
    box-shadow: 0 8px 22px rgba(155,155,155,0.55),
                0 2px 6px rgba(0,0,0,0.14),
                inset 0 1px 0 rgba(255,255,255,0.5);
    animation: silverGlow 3s ease-in-out infinite 0.5s;
}
/* Bronce — 3er puesto */
.podium-3rd .podium-block {
    height: 72px;
    background: linear-gradient(170deg, #f0b27a 0%, #cd7f32 45%, #8b4513 100%);
    box-shadow: 0 8px 22px rgba(205,127,50,0.55),
                0 2px 6px rgba(0,0,0,0.14),
                inset 0 1px 0 rgba(255,255,255,0.3);
    animation: bronzeGlow 3s ease-in-out infinite 1s;
}

@keyframes goldGlow {
    0%,100% { box-shadow: 0 8px 28px rgba(255,185,0,0.5),   0 2px 6px rgba(0,0,0,0.14), inset 0 1px 0 rgba(255,255,255,0.35); }
    50%     { box-shadow: 0 8px 36px rgba(255,185,0,0.76),  0 2px 6px rgba(0,0,0,0.1),  inset 0 1px 0 rgba(255,255,255,0.35); }
}
@keyframes silverGlow {
    0%,100% { box-shadow: 0 8px 22px rgba(155,155,155,0.5),  0 2px 6px rgba(0,0,0,0.14), inset 0 1px 0 rgba(255,255,255,0.5); }
    50%     { box-shadow: 0 8px 30px rgba(155,155,155,0.75), 0 2px 6px rgba(0,0,0,0.1),  inset 0 1px 0 rgba(255,255,255,0.5); }
}
@keyframes bronzeGlow {
    0%,100% { box-shadow: 0 8px 22px rgba(205,127,50,0.5),  0 2px 6px rgba(0,0,0,0.14), inset 0 1px 0 rgba(255,255,255,0.3); }
    50%     { box-shadow: 0 8px 30px rgba(205,127,50,0.75), 0 2px 6px rgba(0,0,0,0.1),  inset 0 1px 0 rgba(255,255,255,0.3); }
}

/* ── Tabla con estilo glass ─────────────────────────────────────── */
.table-glass {
    background: rgba(255,255,255,0.97);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255,255,255,0.8);
    border-radius: 20px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.07), inset 0 1px 0 rgba(255,255,255,0.9);
    overflow: hidden;
}
.table-glass-hdr {
    padding: 1rem 1.5rem;
    background: linear-gradient(135deg, rgba(40,167,69,0.04) 0%, rgba(32,201,151,0.02) 100%);
    border-bottom: 1px solid rgba(0,0,0,0.05);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.table-glass .table { margin-bottom: 0; }
.table-glass .table thead th {
    background: linear-gradient(135deg, rgba(40,167,69,0.06) 0%, rgba(32,201,151,0.03) 100%);
    border-bottom: 2px solid rgba(40,167,69,0.11);
    font-weight: 600;
    font-size: 0.73rem;
    text-transform: uppercase;
    letter-spacing: 0.07em;
    color: #6c757d;
    padding: 0.875rem 1rem;
}
.table-glass .table tbody tr {
    border-bottom: 1px solid rgba(0,0,0,0.036);
    transition: background 0.18s ease;
}
.table-glass .table tbody tr:hover      { background: rgba(40,167,69,0.04) !important; }
.table-glass .table tbody tr:last-child { border-bottom: none; }

/* Fila resaltada del usuario actual ("Tú") */
.table-glass .table tbody tr.table-success {
    background: linear-gradient(135deg, rgba(40,167,69,0.09) 0%, rgba(32,201,151,0.06) 100%) !important;
    border-left: 3px solid #28a745;
}
.table-glass .table tbody tr.table-success:hover {
    background: linear-gradient(135deg, rgba(40,167,69,0.13) 0%, rgba(32,201,151,0.09) 100%) !important;
}

/* Animación de entrada escalonada para cada fila de la tabla */
.ranking-row {
    opacity: 0;
    animation: fadeInUpSuave 0.38s ease-out forwards;
}

/* Círculo del avatar del usuario en la tabla */
.rank-avatar {
    width: 36px; height: 36px;
    border-radius: 50%;
    background: rgba(40,167,69,0.09);
    border: 2px solid rgba(40,167,69,0.13);
    color: #28a745;
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: 0.85rem;
    flex-shrink: 0;
}
.rank-avatar.is-me {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border-color: rgba(32,201,151,0.3);
    color: white;
    box-shadow: 0 2px 10px rgba(40,167,69,0.35);
}

/* Celda de puntos totales */
.rank-pts { font-weight: 700; color: #28a745; }

/* Insignia "Tú" en línea para el usuario autenticado */
.badge-me {
    display: inline-block;
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
    font-size: 0.68rem; font-weight: 600;
    padding: 2px 9px; border-radius: 20px;
    letter-spacing: 0.03em;
}

/* Tamaño de la celda de medallas */
.medal-cell { font-size: 1.35rem; line-height: 1; }
</style>

<!-- ═══════════════════════════════════════════════════════════════
     CABECERA HERO DEL RANKING
═══════════════════════════════════════════════════════════════ -->
<div class="ranking-hero">
    <div class="hero-blob hero-blob-a"></div>
    <div class="hero-blob hero-blob-b"></div>

    <div class="container text-center position-relative" style="z-index:2">
        <div class="hero-glass-card animate__animated animate__fadeInDown animate__faster">
            <div class="d-flex align-items-center justify-content-center gap-3 flex-wrap">
                <i class="bi bi-trophy-fill text-warning"
                   style="font-size:2.7rem;filter:drop-shadow(0 3px 10px rgba(255,193,0,0.6))"></i>
                <div class="text-white text-start">
                    <h1 class="fw-bold mb-1 lh-1" style="font-size:2rem">Ranking Global</h1>
                    <p class="mb-0 opacity-75" style="font-size:.93rem">
                        Los mejores recicladores de la comunidad
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════════════════════
     CONTENIDO PRINCIPAL
═══════════════════════════════════════════════════════════════ -->
<div class="container ranking-content pb-5">

    <!-- ── Podio con los 3 primeros puestos ──────────────────────── -->
    <div id="podium" class="podium-section d-none animate__animated animate__fadeInUp">

        <div class="text-center mb-3">
            <span class="text-success fw-semibold"
                  style="font-size:.78rem;letter-spacing:.1em;text-transform:uppercase;opacity:.85">
                <i class="bi bi-stars me-1"></i>Top 3 Recicladores
            </span>
        </div>

        <div class="podium-wrapper">

            <!-- Segundo puesto → plata -->
            <div class="podium-item podium-2nd">
                <div id="podium2name" class="podium-name"></div>
                <div id="podium2pts"  class="podium-pts"></div>
                <div class="podium-block">
                    <span class="podium-medal">🥈</span>
                    <span class="podium-num">2º</span>
                </div>
            </div>

            <!-- Primer puesto → oro -->
            <div class="podium-item podium-1st">
                <div id="podium1name" class="podium-name fw-bold"></div>
                <div id="podium1pts"  class="podium-pts fw-semibold text-success"></div>
                <div class="podium-block">
                    <span class="podium-medal">🥇</span>
                    <span class="podium-num">1º</span>
                </div>
            </div>

            <!-- Tercer puesto → bronce -->
            <div class="podium-item podium-3rd">
                <div id="podium3name" class="podium-name"></div>
                <div id="podium3pts"  class="podium-pts"></div>
                <div class="podium-block">
                    <span class="podium-medal">🥉</span>
                    <span class="podium-num">3º</span>
                </div>
            </div>

        </div>
    </div>

    <!-- ── Tabla del ranking completo ────────────────────────────── -->
    <div class="table-glass">

        <div class="table-glass-hdr">
            <i class="bi bi-list-ol text-success"></i>
            <span class="text-muted fw-semibold"
                  style="font-size:.74rem;letter-spacing:.07em;text-transform:uppercase">
                Clasificación completa
            </span>
        </div>

        <div id="rankingContainer">
            <!-- Esqueleto de carga (skeleton placeholder) -->
            <div id="rankingSkeleton" class="p-4">
                <?php for ($i = 0; $i < 6; $i++): ?>
                    <div class="d-flex align-items-center mb-3 placeholder-glow">
                        <span class="placeholder rounded me-3" style="width:34px;height:20px;"></span>
                        <span class="placeholder rounded-circle me-3 flex-shrink-0"
                              style="width:36px;height:36px;"></span>
                        <span class="placeholder rounded col-3 me-auto"></span>
                        <span class="placeholder rounded col-2 me-3"></span>
                        <span class="placeholder rounded col-2"></span>
                    </div>
                <?php endfor; ?>
            </div>
        </div>

    </div>

</div>

<script>
// ── Lógica del ranking: carga el listado y la posición del usuario
//    autenticado mediante fetch a la API de ranking. ──────────────
document.addEventListener('DOMContentLoaded', function () {

    const myUid = <?= isset($_SESSION["usuario_id"])
        ? (int) $_SESSION["usuario_id"]
        : "null" ?>;

    // ── Escapar texto para evitar inyección XSS al insertar con innerHTML ────
    function esc(str) {
        const d = document.createElement('div');
        d.textContent = str ?? '';
        return d.innerHTML;
    }

    // ── Devuelve emoji de medalla para top 3, o el número para el resto ──
    function medal(pos) {
        if (pos === 1) return '🥇';
        if (pos === 2) return '🥈';
        if (pos === 3) return '🥉';
        return `<span class="text-muted fw-semibold">${pos}</span>`;
    }

    // ── Solo se carga la posición si el usuario tiene sesión activa ──
    //    myUid será null para visitantes, saltando esta petición.
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

    // ── Obtener el listado completo del ranking desde la API ───────
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

            // ── Rellenar el podio con los 3 primeros ─────────────────
            if (data.length >= 3) {
                [1, 2, 3].forEach(n => {
                    const u = data[n - 1];
                    document.getElementById(`podium${n}name`).textContent = u.nombre;
                    document.getElementById(`podium${n}pts`).textContent  = u.puntos_totales + ' pts';
                });
                podiumEl.classList.remove('d-none');
            }

            // ── Construir las filas de la tabla con datos de cada usuario ──
            //    El retardo escalonado (delay) para la animación de entrada
            //    se calcula en hasta 25 filas × 0.045s = ~1.1s máx.
            let rows = '';
            data.forEach((u, idx) => {
                const isMe  = myUid && parseInt(u.id) === myUid;
                const kg    = parseFloat(u.kg_reciclados || 0).toFixed(2);
                const delay = (Math.min(idx, 25) * 0.045).toFixed(3);
                rows += `
                    <tr class="ranking-row ${isMe ? 'table-success fw-semibold' : ''}"
                        style="animation-delay:${delay}s">
                        <td class="ps-4 medal-cell">${medal(u.posicion)}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="rank-avatar ${isMe ? 'is-me' : ''}">
                                    ${esc(u.nombre.charAt(0).toUpperCase())}
                                </div>
                                <span>${esc(u.nombre)}</span>
                                ${isMe ? '<span class="badge-me ms-1">Tú</span>' : ''}
                            </div>
                        </td>
                        <td class="text-end">
                            <span class="rank-pts">${esc(String(u.puntos_totales))}</span>
                        </td>
                        <td class="text-end pe-4 d-none d-md-table-cell text-muted">${esc(kg)} kg</td>
                        <td class="text-end pe-4 d-none d-md-table-cell text-muted">${esc(String(u.total_reciclajes))}</td>
                    </tr>
                `;
            });

            container.innerHTML = `
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4" style="width:64px">Pos.</th>
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

<?php include __DIR__ . "/partials/footer.php"; ?>
