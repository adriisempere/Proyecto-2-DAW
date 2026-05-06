<?php
/* Configuración de sesión con path '/' para que la cookie
 * sea accesible en toda la aplicación, independientemente
 * de la ruta URL donde se encuentre el usuario. */
if (session_status() === PHP_SESSION_NONE) {
    session_name('GREENPOINTS_SESSID');
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'domain'   => '',
        'secure'   => false,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

require_once __DIR__ . '/../config/database.php';

$action = $_GET['action'] ?? 'home';

/* Router principal: mapea cada action a su vista correspondiente.
 * Las vistas se incluyen como archivos PHP y reciben acceso a la
 * variable $action y a la sesión activa. Las operaciones con datos
 * se delegan a las APIs en public/api/ (patrón front-controller). */
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
        include '../app/views/tienda.php';     
        break;
    case 'mis_canjes':  
        include '../app/views/mis_canjes.php';  
        break;
    default:
        http_response_code(404);
        echo "Página no encontrada";
}

?>