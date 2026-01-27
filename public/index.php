<?php
session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/controllers/UsuarioController.php';
require_once __DIR__ . '/../app/controllers/CentroController.php';
require_once __DIR__ . '/../app/controllers/RegistroController.php';
require_once __DIR__ . '/../app/controllers/RankingController.php';

$action = $_GET['action'] ?? 'home';

switch ($action) {
    case 'home':
        include __DIR__ . '/../app/views/home.php';
        break;
    case 'register':
        (new UsuarioController())->showRegister();
        break;
    case 'register_post':
        (new UsuarioController())->register();
        break;
    case 'login':
        (new UsuarioController())->showLogin();
        break;
    case 'login_post':
        (new UsuarioController())->login();
        break;
    case 'logout':
        (new UsuarioController())->logout();
        break;
    // centros
    case 'centros':
        (new CentroController())->index();
        break;
    case 'centro_create':
        (new CentroController())->showCreate();
        break;
    case 'centro_store':
        (new CentroController())->store();
        break;
    // registros de reciclaje
    case 'registro_create':
        (new RegistroController())->showCreate();
        break;
    case 'registro_store':
        (new RegistroController())->store();
        break;
    case 'mis_registros':
        (new RegistroController())->misRegistros();
        break;
    // ranking
    case 'ranking':
        (new RankingController())->index();
        break;
    // perfil de usuario
    case 'perfil':
        (new UsuarioController())->perfil();
        break;
    default:
        http_response_code(404);
        echo "Página no encontrada";
}

?>