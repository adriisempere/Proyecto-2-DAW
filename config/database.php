<?php
// Detección de entorno: localhost vs producción (InfinityFree)
$isLocal = in_array($_SERVER['SERVER_NAME'] ?? '', ['localhost', '127.0.0.1', '::1', '']);

if ($isLocal) {
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'greenpoints');
} else {
    // Configuración para InfinityFree
    // Cambia estos valores según tu panel de control
    define('DB_HOST', 'sql311.infinityfree.com');
    define('DB_USER', 'if0_41618488');
    define('DB_PASS', 'Adrianser120719');
    define('DB_NAME', 'if0_41618488_greenpoints');
}

define('DB_CHARSET', 'utf8mb4');

// Suprimir warnings de mysqli para manejarlos nosotros
$mysqli = @new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($mysqli->connect_error) {
    // En producción, registrar el error sin exponer detalles
    error_log("[GreenPoints] Error de conexión a BD: " . $mysqli->connect_error);
    http_response_code(503);
    die("<h1>Servicio no disponible</h1><p>No se pudo conectar a la base de datos. Inténtalo más tarde.</p>");
}

$mysqli->set_charset(DB_CHARSET);

function getConnection() {
    global $mysqli;
    return $mysqli;
}