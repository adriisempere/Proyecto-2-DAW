<?php

// ── Configuración de la base de datos ────────────────────────────
// Las constantes se obtienen de variables de entorno con fallback a valores locales.
// getenv() permite configurar diferentes credenciales en desarrollo y producción.
define('DB_HOST', getenv('DB_HOST') ?: '127.0.0.1');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_NAME', getenv('DB_NAME') ?: 'greenpoints');
define('DB_CHARSET', getenv('DB_CHARSET') ?: 'utf8mb4'); // utf8mb4 permite emojis y caracteres Unicode completos

// ── Crear conexión ───────────────────────────────────────────────
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// ── Verificar conexión ───────────────────────────────────────────
if ($mysqli->connect_error) {
    // Solo registramos el error internamente; no exponemos detalles al usuario
    error_log("Error de conexión a la base de datos: " . $mysqli->connect_error);
    die("Error de conexión a la base de datos. Por favor, contacta al administrador.");
}

// ── Configurar charset ───────────────────────────────────────────
// Previene problemas de codificación con caracteres especiales (tildes, eñes, etc.)
$mysqli->set_charset(DB_CHARSET);

/**
 * Función helper para obtener la conexión desde cualquier parte de la aplicación.
 * Usa variable global $mysqli en lugar de singleton para mantener la simplicidad.
 *
 * @return mysqli Instancia de la conexión a la base de datos
 */
function getConnection() {
    global $mysqli;
    return $mysqli;
}
?>