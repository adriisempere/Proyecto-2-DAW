<?php
/**
 * Vista del Perfil de Usuario — GreenPoints
 * ---------------------------------------------------------------
 * Panel personal del usuario autenticado. Muestra:
 *   - Avatar con inicial, nombre, email
 *   - Estadísticas personales (puntos, nivel, kg reciclados, CO₂)
 *     cargadas desde api/ranking.php?action=me
 *   - Información de cuenta
 *   - Acciones rápidas: registrar reciclaje, historial, ranking, logout
 *
 * Si no hay sesión activa muestra pantalla de acceso restringido
 * con botones de login y registro, sin redirigir automáticamente.
 * ---------------------------------------------------------------
 */

$pageTitle = "Mi Perfil | GreenPoints";
include __DIR__ . "/partials/header.php";

$nombre = $_SESSION["usuario_nombre"] ?? "Usuario";
$email = $_SESSION["usuario_email"] ?? "No disponible";
$puntos = (int) ($_SESSION["usuario_puntos"] ?? 0);

// Calcular el nivel del usuario según sus puntos acumulados.
// Rangos: >5000 → Maestro Verde, >2000 → Experto, >500 → Avanzado, ≤500 → Principiante
function calcularNivel($puntos)
{
    if ($puntos > 5000) {
        return "Maestro Verde";
    }
    if ($puntos > 2000) {
        return "Experto";
    }
    if ($puntos > 500) {
        return "Avanzado";
    }
    return "Principiante";
}

$nivel = calcularNivel($puntos);
$inicial = strtoupper(substr($nombre ?: "U", 0, 1));
?>

<style>
    :root {
        --primary-color: #16a34a;
        --secondary-color: #0d9488;
        --dark-color: #1f2937;
    }

    .profile-header {
        background: linear-gradient(135deg, #065f46 0%, #047857 40%, #0d9488 100%);
        border-radius: 20px;
        color: white;
        padding: 3rem 2rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(40, 167, 69, 0.2);
    }

    .profile-header::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 70% 30%, rgba(255,255,255,0.12) 0%, transparent 60%);
        pointer-events: none;
    }

    .profile-avatar {
        width: 110px;
        height: 110px;
        background: linear-gradient(135deg, #22c55e, #0d9488);
        color: #fff;
        font-size: 2.8rem;
        font-weight: 700;
        border-radius: 50%;
        border: 4px solid rgba(255,255,255,0.4);
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.3s, box-shadow 0.3s;
        position: relative;
        z-index: 1;
    }

    .profile-avatar:hover {
        transform: scale(1.06);
        box-shadow: 0 12px 30px rgba(0,0,0,0.18);
    }

    .stat-card {
        background: rgba(255,255,255,.92);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-radius: 15px;
        padding: 1.5rem;
        text-align: center;
        box-shadow: 0 4px 14px rgba(0,0,0,0.05);
        transition: transform 0.25s, box-shadow 0.25s;
        border: 1px solid rgba(0,0,0,0.03);
        height: 100%;
    }

    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 16px 40px rgba(6,78,59,.14);
        border-color: rgba(34,197,94,.25);
    }

    .stat-icon {
        font-size: 2.2rem;
        margin-bottom: 0.75rem;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .stat-value {
        font-size: 1.6rem;
        font-weight: 700;
        color: var(--dark-color);
        margin-bottom: 0.3rem;
        min-height: 2rem;
    }

    .stat-label {
        color: #6c757d;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 0.8rem;
    }

    .info-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 14px rgba(0,0,0,0.05);
        border: none;
        overflow: hidden;
    }

    .info-card .card-header {
        background: rgba(40,167,69,0.05);
        border-bottom: 1px solid rgba(40,167,69,0.1);
        padding: 1.2rem 1.5rem;
        font-weight: 600;
    }

    .info-item {
        padding: 1rem 0;
        border-bottom: 1px solid #f2f2f2;
        display: flex;
        align-items: center;
    }

    .info-item:last-child { border-bottom: none; }

    .info-icon {
        width: 38px;
        height: 38px;
        background: rgba(40,167,69,0.08);
        color: var(--primary-color);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        margin-right: 14px;
        flex-shrink: 0;
    }

    .info-content .label {
        display: block;
        font-size: 0.8rem;
        color: #6c757d;
        margin-bottom: 0.15rem;
    }

    .info-content .value {
        display: block;
        font-weight: 500;
        font-size: 1rem;
    }

    .action-btn {
        padding: 0.75rem 1.25rem;
        border-radius: 50px;
        font-weight: 500;
        transition: all 0.25s;
    }

    .btn-custom-outline {
        border: 2px solid var(--primary-color);
        color: var(--primary-color);
        background: transparent;
    }

    .btn-custom-outline:hover {
        background: var(--primary-color);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 14px rgba(40,167,69,0.2);
    }

    .btn-custom-primary {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        border: none;
    }

    .btn-custom-primary:hover {
        filter: brightness(1.08);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 14px rgba(40,167,69,0.3);
    }

    /* Botones de acción rápida con estilo uniforme */
    .profile-action-btn {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        padding: 0.7rem 1rem;
        border-radius: 12px;
        background: linear-gradient(135deg, rgba(22,163,74,.07), rgba(13,148,136,.07));
        border: 1px solid rgba(22,163,74,.18);
        color: #1f2937;
        font-weight: 500;
        font-size: 0.875rem;
        text-decoration: none;
        transition: all 0.25s ease;
        width: 100%;
    }
    .profile-action-btn:hover {
        background: linear-gradient(135deg, #16a34a, #0d9488);
        border-color: transparent;
        color: #fff;
        transform: translateX(4px);
        box-shadow: 0 6px 20px rgba(22,163,74,.3);
    }
    .profile-action-btn i:first-child {
        width: 20px;
        text-align: center;
        font-size: 1rem;
        flex-shrink: 0;
        color: #16a34a;
    }
    .profile-action-btn:hover i { color: #fff !important; }

    /* Estilos del modal de edición de perfil */
    .edit-form-group label {
        font-weight: 600;
        font-size: 0.84rem;
        color: #374151;
        margin-bottom: 0.35rem;
    }
    .edit-form-control {
        background: rgba(240,253,244,.8);
        border: 1.5px solid #d1fae5;
        border-radius: 10px;
        padding: 0.6rem 0.9rem;
        font-size: 0.9rem;
        font-family: 'Poppins', sans-serif;
        transition: all 0.2s;
        width: 100%;
    }
    .edit-form-control:focus {
        outline: none;
        border-color: #059669;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(5,150,105,.12);
    }
</style>

<div class="container py-5">

<?php if (isset($_SESSION["usuario_id"])): ?>

    <?php
    $nombre = $_SESSION["usuario_nombre"] ?? "Usuario";
    $inicial = mb_strtoupper(mb_substr($nombre, 0, 1, "UTF-8"), "UTF-8");
    $email = $_SESSION["usuario_email"] ?? "";
    $puntos = (int) ($_SESSION["usuario_puntos"] ?? 0);
    $rol = $_SESSION["usuario_rol"] ?? "usuario";

    // Calcular nivel según los puntos del usuario (misma lógica que calcularNivel())
    $nivel = "Principiante";
    if ($puntos > 5000) {
        $nivel = "Maestro Verde";
    } elseif ($puntos > 2000) {
        $nivel = "Experto";
    } elseif ($puntos > 500) {
        $nivel = "Avanzado";
    }
    ?>

    <!-- Cabecera del perfil con avatar, nombre y rol -->
    <div class="profile-header text-center mb-5 animate__animated animate__fadeInDown">
        <div class="profile-avatar mb-3 profile-avatar-text">
            <?= htmlspecialchars($inicial) ?>
        </div>
        <h2 class="fw-bold mb-1 position-relative">
            ¡Hola, <span class="profile-nombre-display"><?= htmlspecialchars(
                $nombre,
            ) ?></span>!
        </h2>
        <p class="mb-0 opacity-75 position-relative">
            <?= $rol === "admin"
                ? '<span class="badge bg-warning text-dark me-2"><i class="bi bi-shield-check me-1"></i>Administrador</span>'
                : "" ?>
            Bienvenido a tu panel personal
        </p>
    </div>

    <!-- Tarjetas de estadísticas del usuario -->
    <div class="row g-3 mb-5">

        <div class="col-6 col-md-3 animate__animated animate__fadeInUp" style="animation-delay:.1s">
            <div class="stat-card">
                <div class="stat-icon"><i class="bi bi-star-fill"></i></div>
                <div class="stat-value"><?= number_format($puntos) ?></div>
                <div class="stat-label">Puntos</div>
            </div>
        </div>

        <div class="col-6 col-md-3 animate__animated animate__fadeInUp" style="animation-delay:.2s">
            <div class="stat-card">
                <div class="stat-icon"><i class="bi bi-trophy-fill"></i></div>
                <div class="stat-value" style="font-size:1.1rem;padding-top:.4rem">
                    <?= htmlspecialchars($nivel) ?>
                </div>
                <div class="stat-label">Nivel</div>
            </div>
        </div>

        <div class="col-6 col-md-3 animate__animated animate__fadeInUp" style="animation-delay:.3s">
            <div class="stat-card">
                <div class="stat-icon"><i class="bi bi-recycle"></i></div>
                <div class="stat-value" id="statKg">
                    <span class="placeholder col-6 rounded"></span>
                </div>
                <div class="stat-label">Kg reciclados</div>
            </div>
        </div>

        <div class="col-6 col-md-3 animate__animated animate__fadeInUp" style="animation-delay:.4s">
            <div class="stat-card">
                <div class="stat-icon"><i class="bi bi-wind"></i></div>
                <div class="stat-value" id="statCo2">
                    <span class="placeholder col-6 rounded"></span>
                </div>
                <div class="stat-label">CO₂ ahorrado</div>
            </div>
        </div>

    </div>

    <div class="row g-4">

        <!-- Sección de información personal del usuario -->
        <div class="col-lg-8 animate__animated animate__fadeInLeft" style="animation-delay:.5s">
            <div class="card info-card h-100">
                <div class="card-header">
                    <span><i class="bi bi-person-lines-fill text-success me-2"></i>Información Personal</span>
                    <button class="btn btn-sm rounded-pill fw-semibold" id="btnEditarPerfil"
                            style="background:linear-gradient(135deg,#16a34a,#0d9488);color:#fff;border:none;padding:.35rem 1rem;">
                        <i class="bi bi-pencil-square me-1"></i>Editar
                    </button>
                </div>
                <div class="card-body px-4">

                    <div class="info-item">
                        <div class="info-icon"><i class="bi bi-person"></i></div>
                        <div class="info-content">
                            <span class="label">Nombre completo</span>
                            <span class="value"><?= htmlspecialchars(
                                $nombre,
                            ) ?></span>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon"><i class="bi bi-envelope"></i></div>
                        <div class="info-content">
                            <span class="label">Correo electrónico</span>
                            <span class="value profile-email-display"><?= htmlspecialchars(
                                $email,
                            ) ?></span>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon"><i class="bi bi-bar-chart-line"></i></div>
                        <div class="info-content">
                            <span class="label">Posición en el ranking</span>
                            <span class="value" id="posicionRanking">
                                <span class="placeholder col-3 rounded"></span>
                            </span>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Sección de acciones rápidas y enlaces útiles -->
        <div class="col-lg-4 animate__animated animate__fadeInRight" style="animation-delay:.6s">
            <div class="card info-card h-100">
                <div class="card-header">
                    <i class="bi bi-lightning-charge-fill text-warning me-2"></i>Acciones Rápidas
                </div>
                <div class="card-body d-flex flex-column gap-2 p-3">

                    <?php
                    $acciones = [
                        [
                            "url" => "index.php?action=registro_create",
                            "icon" => "bi-plus-circle-fill",
                            "label" => "Registrar Reciclaje",
                        ],
                        [
                            "url" => "index.php?action=mis_registros",
                            "icon" => "bi-clock-history",
                            "label" => "Mis Registros",
                        ],
                        [
                            "url" => "index.php?action=ranking",
                            "icon" => "bi-trophy-fill",
                            "label" => "Ver Ranking",
                        ],
                        [
                            "url" => "index.php?action=tienda",
                            "icon" => "bi-gift-fill",
                            "label" => "Tienda de Recompensas",
                        ],
                        [
                            "url" => "index.php?action=mis_canjes",
                            "icon" => "bi-bag-check-fill",
                            "label" => "Mis Recompensas",
                        ],
                    ];
                    if ($rol === "admin") {
                        $acciones[] = [
                            "url" => "index.php?action=centros",
                            "icon" => "bi-geo-alt-fill",
                            "label" => "Gestionar Centros",
                        ];
                    }
                    foreach ($acciones as $i => $a): ?>
                    <a href="<?= $a["url"] ?>" class="profile-action-btn">
                        <i class="bi <?= $a["icon"] ?>"></i>
                        <?= $a["label"] ?>
                        <i class="bi bi-chevron-right ms-auto" style="font-size:.75rem;opacity:.5;"></i>
                    </a>
                    <?php endforeach;
                    ?>

                    <div class="mt-auto pt-3 text-center border-top" style="border-color:#e8f5e9!important;">
                        <a href="index.php?action=logout"
                           class="text-danger text-decoration-none fw-medium small d-flex align-items-center justify-content-center gap-1">
                            <i class="bi bi-box-arrow-right"></i>Cerrar Sesión
                        </a>
                    </div>

                </div>
            </div>
        </div>

    </div>

<?php else: ?>

    <!-- Pantalla de acceso restringido para usuarios no autenticados -->
    <div class="row justify-content-center align-items-center" style="min-height:50vh">
        <div class="col-md-7 text-center py-5 animate__animated animate__fadeIn">
            <i class="bi bi-shield-lock text-success d-block mb-4"
               style="font-size:5rem;opacity:.45"></i>
            <h2 class="fw-bold mb-3">Acceso Restringido</h2>
            <p class="text-muted fs-5 mb-4">
                Inicia sesión para ver tu perfil y gestionar tus puntos de reciclaje.
            </p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="index.php?action=login"
                   class="btn btn-success btn-lg rounded-pill px-4 shadow-sm">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión
                </a>
                <a href="index.php?action=register"
                   class="btn btn-outline-success btn-lg rounded-pill px-4">
                    Crear Cuenta
                </a>
            </div>
        </div>
    </div>

<?php endif; ?>

</div>

<!-- ── Modal para editar los datos del perfil ─────────────────── -->
<?php if (isset($_SESSION["usuario_id"])): ?>
<div class="modal fade" id="editarPerfilModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0" style="border-radius:20px;overflow:hidden;box-shadow:0 24px 64px rgba(0,0,0,.18);">
            <div class="modal-header border-0 text-white"
                 style="background:linear-gradient(135deg,#065f46,#0d9488);padding:1.5rem 1.75rem;">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-pencil-square me-2"></i>Editar Perfil
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" style="background:#f0fdf4;">
                <div id="editAlert" class="alert d-none mb-3" style="border-radius:10px;border:none;"></div>

                <form id="editPerfilForm" novalidate>
                    <?php
                    require_once __DIR__ . "/../helpers/CsrfHelper.php";
                    echo CsrfHelper::getTokenField();
                    ?>

                    <!-- Campo de nombre completo -->
                    <div class="edit-form-group mb-3">
                        <label for="editNombre"><i class="bi bi-person me-1 text-success"></i>Nombre completo</label>
                        <input type="text" class="edit-form-control" id="editNombre" name="nombre"
                               value="<?= htmlspecialchars(
                                   $_SESSION["usuario_nombre"] ?? "",
                               ) ?>"
                               required minlength="3" placeholder="Tu nombre completo">
                    </div>

                    <!-- Campo de correo electrónico -->
                    <div class="edit-form-group mb-3">
                        <label for="editEmail"><i class="bi bi-envelope me-1 text-success"></i>Correo electrónico</label>
                        <input type="email" class="edit-form-control" id="editEmail" name="email"
                               value="<?= htmlspecialchars(
                                   $_SESSION["usuario_email"] ?? "",
                               ) ?>"
                               required placeholder="tu@email.com">
                    </div>

                    <hr style="border-color:#d1fae5;">
                    <p class="text-muted small mb-3"><i class="bi bi-lock me-1"></i>Cambiar contraseña (opcional — déjalo vacío para mantener la actual)</p>

                    <!-- Campo de contraseña actual (obligatorio si se desea cambiar) -->
                    <div class="edit-form-group mb-3">
                        <label for="editPassActual"><i class="bi bi-key me-1 text-success"></i>Contraseña actual</label>
                        <input type="password" class="edit-form-control" id="editPassActual" name="password_actual"
                               placeholder="Tu contraseña actual" autocomplete="current-password">
                    </div>

                    <!-- Campo de nueva contraseña (opcional) -->
                    <div class="edit-form-group mb-1">
                        <label for="editPassNueva"><i class="bi bi-lock-fill me-1 text-success"></i>Nueva contraseña</label>
                        <input type="password" class="edit-form-control" id="editPassNueva" name="nueva_password"
                               placeholder="Nueva contraseña (mín. 6 caracteres)" autocomplete="new-password">
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0" style="background:#f0fdf4;padding:1rem 1.75rem 1.5rem;">
                <button type="button" class="btn btn-light rounded-pill px-4 fw-semibold"
                        data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn rounded-pill px-4 fw-bold" id="guardarPerfilBtn"
                        style="background:linear-gradient(135deg,#16a34a,#0d9488);color:#fff;border:none;">
                    <i class="bi bi-check-lg me-1"></i>Guardar Cambios
                </button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if (isset($_SESSION["usuario_id"])): ?>
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ── Cargar estadísticas dinámicas desde la API de ranking ─── */
    fetch('api/ranking.php?action=me')
        .then(r => r.json())
        .then(json => {
            if (!json.success) return;
            const d  = json.data;
            const kg = parseFloat(d.kg_reciclados || 0);
            // CO₂ ahorrado estimado: 1 kg reciclado ≈ 1.5 kg CO₂ evitado
            const co2 = kg * 1.5;
            const fmt = v => v >= 1000 ? (v/1000).toFixed(1)+' t' : v.toFixed(2)+' kg';

            document.getElementById('statKg').textContent   = fmt(kg);
            document.getElementById('statCo2').textContent  = fmt(co2);
            document.getElementById('posicionRanking').textContent = '#' + d.posicion;
        })
        .catch(() => {
            ['statKg','statCo2','posicionRanking'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.textContent = '—';
            });
        });

    /* ── Lógica del modal de edición de perfil ─────────────────── */
    // Al guardar, envía los datos a api/users.php?action=update via fetch
    // y actualiza el DOM con los valores devueltos (nombre, email, avatar).
    const editModal = new bootstrap.Modal(document.getElementById('editarPerfilModal'));
    const btnEditar = document.getElementById('btnEditarPerfil');
    const btnGuardar = document.getElementById('guardarPerfilBtn');
    const editAlert = document.getElementById('editAlert');
    const editForm  = document.getElementById('editPerfilForm');

    btnEditar?.addEventListener('click', () => editModal.show());

    function showEditAlert(msg, type = 'danger') {
        editAlert.className = `alert alert-${type}`;
        editAlert.innerHTML = `<i class="bi bi-${type==='success'?'check-circle':'exclamation-triangle'}-fill me-2"></i>${msg}`;
    }

    btnGuardar?.addEventListener('click', async function () {
        const nombre    = document.getElementById('editNombre').value.trim();
        const email     = document.getElementById('editEmail').value.trim();
        const passActual = document.getElementById('editPassActual').value;
        const passNueva  = document.getElementById('editPassNueva').value;
        const csrfToken  = editForm.querySelector('[name="csrf_token"]')?.value ?? '';

        if (!nombre || !email) {
            showEditAlert('El nombre y el email son obligatorios.');
            return;
        }

        btnGuardar.disabled = true;
        btnGuardar.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Guardando…';

        try {
            const res = await fetch('api/users.php?action=update', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    csrf_token:      csrfToken,
                    nombre,
                    email,
                    password_actual: passActual,
                    nueva_password:  passNueva,
                }),
            });
            const json = await res.json();

            if (json.success) {
                showEditAlert('¡Perfil actualizado correctamente!', 'success');

                // Actualizar nombre visible en el perfil y en el navbar
                document.querySelectorAll('.profile-nombre-display').forEach(el => el.textContent = json.nombre);
                const navUser = document.querySelector('.gp-avatar-btn span');
                if (navUser) navUser.textContent = json.nombre;

                // Actualizar avatar (inicial)
                const inicial = json.nombre.charAt(0).toUpperCase();
                document.querySelectorAll('.profile-avatar-text, .gp-avatar-circle').forEach(el => el.textContent = inicial);

                // Actualizar email visible
                document.querySelectorAll('.profile-email-display').forEach(el => el.textContent = json.email);

                // Limpiar campos de contraseña
                document.getElementById('editPassActual').value = '';
                document.getElementById('editPassNueva').value  = '';

                setTimeout(() => editModal.hide(), 1200);
            } else {
                showEditAlert(json.message || 'No se pudo guardar.');
            }
        } catch {
            showEditAlert('Error de red. Inténtalo más tarde.');
        } finally {
            btnGuardar.disabled = false;
            btnGuardar.innerHTML = '<i class="bi bi-check-lg me-1"></i>Guardar Cambios';
        }
    });
});
</script>
<?php endif; ?>

<?php include __DIR__ . "/partials/footer.php"; ?>
