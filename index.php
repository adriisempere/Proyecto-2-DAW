<?php

// =====================
// SESIÓN GLOBAL
// =====================
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'path' => '/',
        'httponly' => true,
        'secure' => isset($_SERVER['HTTPS']),
        'samesite' => 'Lax'
    ]);
    session_start();
}

require_once __DIR__ . '/../config/database.php';


// =====================
// ROUTER
// =====================

$allowedRoutes = [
    'home',
    'register',
    'login',
    'logout',
    'centros',
    'registro_create',
    'mis_registros',
    'ranking',
    'perfil'
];

$action = $_GET['action'] ?? 'home';

// Sanitización básica
if (!in_array($action, $allowedRoutes, true)) {
    http_response_code(404);
    echo "Página no encontrada";
    exit;
}


// =====================
// CONTROLADOR PRINCIPAL
// =====================

if ($action === 'logout') {

    // Limpieza completa de sesión
    $_SESSION = [];

    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    session_destroy();

    header('Location: index.php?action=home');
    exit;
}


// =====================
// CARGA DE VISTAS
// =====================

$viewPath = __DIR__ . '/../app/views/' . $action . '.php';

if (file_exists($viewPath)) {
    include $viewPath;
} else {
    http_response_code(404);
    echo "Página no encontrada";
}