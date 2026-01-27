<?php

// Configuración de la base de datos
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_NAME', getenv('DB_NAME') ?: 'greenpoints');
define('DB_CHARSET', getenv('DB_CHARSET') ?: 'utf8mb4');

// Crear conexión
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Verificar conexión
if ($mysqli->connect_error) {
    // En producción, registra el error en un log en lugar de mostrarlo
    error_log("Error de conexión a la base de datos: " . $mysqli->connect_error);
    die("Error de conexión a la base de datos. Por favor, contacta al administrador.");
}

// Establecer el charset para prevenir problemas de codificación
$mysqli->set_charset(DB_CHARSET);

/**
 * Función helper para obtener la conexión en cualquier parte de la aplicación
 * 
 * @return mysqli Instancia de la conexión a la base de datos
 */
function getConnection() {
    global $mysqli;
    return $mysqli;
}
?>