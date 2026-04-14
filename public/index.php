<?php
// Sesión sencilla con path común para todo el proyecto
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'path' => '/',
    ]);
    session_start();
}

require_once __DIR__ . '/../config/database.php';

$action = $_GET['action'] ?? 'home';

// Rutas simplificadas: las operaciones con datos se realizan mediante las APIs en public/api/
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
        // Logout simple
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

?>  case 'mis_canjes':  
        include '../app/views/mis_canjes.php';  
        break;
    default:
        http_response_code(404);
        echo "Página no encontrada";
}

?>