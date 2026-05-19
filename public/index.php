<?php
// Sesión con path común para compartirla entre todas las páginas del proyecto
if (session_status() === PHP_SESSION_NONE) {
    session_name('GREENPOINTS_SESSID');
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'domain'   => '',
        'secure'   => isset($_SERVER['HTTPS']),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

require_once __DIR__ . '/../config/database.php';

$action = $_GET['action'] ?? 'home';

// Enrutador simple: cada acción carga una vista diferente.
// La lógica de negocio y modificación de datos se maneja via API (public/api/),
// mientras que este router solo se encarga de incluir la vista correspondiente.
switch ($action) {
    case 'home':
        include __DIR__ . '/../app/views/home.php';
        break;
    case 'register':
        include __DIR__ . '/../app/views/register.php';
        break;
    case 'login':
        include __DIR__ . '/../app/views/login.php';
        break;
    case 'logout':
        /* Logout simple: destruye la sesión y redirige al inicio.
         * Para un logout completo se recomienda también limpiar
         * la cookie de sesión (ver public/api/users.php?action=logout). */
        session_destroy();
        header('Location: index.php?action=home');
        break;
    case 'centros':
        include __DIR__ . '/../app/views/centros.php';
        break;
    case 'registro_create':
        include __DIR__ . '/../app/views/registro_create.php';
        break;
    case 'mis_registros':
        include __DIR__ . '/../app/views/mis_registros.php';
        break;
    case 'ranking':
        include __DIR__ . '/../app/views/ranking.php';
        break;
    case 'perfil':
        include __DIR__ . '/../app/views/perfil.php';
        break;
    case 'tienda':
        include __DIR__ . '/../app/views/tienda.php';
        break;
    case 'mis_canjes':
        include __DIR__ . '/../app/views/mis_canjes.php';
        break;
    case 'ayuda':
        include __DIR__ . '/../app/views/ayuda.php';
        break;
    case 'faq':
        include __DIR__ . '/../app/views/faq.php';
        break;
    case 'terminos':
        include __DIR__ . '/../app/views/terminos.php';
        break;
    case 'contacto':
        include __DIR__ . '/../app/views/contacto.php';
        break;
    case 'privacidad':
        include __DIR__ . '/../app/views/privacidad.php';
        break;
    default:
        http_response_code(404);
        echo "Página no encontrada";
}

?>
