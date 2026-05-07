<?php
$isLocal = in_array($_SERVER['SERVER_NAME'] ?? '', ['localhost', '127.0.0.1', '']);

if ($isLocal) {
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'greenpoints');
} else {
    define('DB_HOST', 'sql311.infinityfree.com');
    define('DB_USER', 'if0_41618488');
    define('DB_PASS', 'Adrianser120719');
    define('DB_NAME', 'if0_41618488_greenpoints');
}

define('DB_CHARSET', 'utf8mb4');

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($mysqli->connect_error) {
    error_log("Error de conexión: " . $mysqli->connect_error);
    die("Error de conexión a la base de datos. Contacta al administrador.");
}

$mysqli->set_charset(DB_CHARSET);

function getConnection() {
    global $mysqli;
    return $mysqli;
}
?>