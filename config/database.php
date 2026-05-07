<?php

// Configuración de la base de datos para hosting (desactivada)
// Las constantes de hosting usan getenv() con fallback para funcionar
// tanto con variables de entorno como con valores por defecto.
// Define los valores directamente si no usas variables de entorno.
/*
define('DB_HOST', getenv('DB_HOST') ?: 'sql305.infinityfree.com');
define('DB_USER', getenv('DB_USER') ?: 'if0_41618488');
define('DB_PASS', getenv('DB_PASS') ?: 'Adrianser120719');
define('DB_NAME', getenv('DB_NAME') ?: 'if0_41618488_greenpoints');
define('DB_CHARSET', getenv('DB_CHARSET') ?: 'utf8mb4');
*/

// Configuración de la base de datos para local
// Cambia estos valores según tu entorno de desarrollo
define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_NAME", "greenpoints");
define("DB_CHARSET", "utf8mb4");

// Crear conexión
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Verificar conexión
if ($mysqli->connect_error) {
    // En producción, registra el error en un log en lugar de mostrarlo
    // Nunca expongas detalles de la conexión al usuario final
    error_log(
        "Error de conexión a la base de datos: " . $mysqli->connect_error,
    );
    die(
        "Error de conexión a la base de datos. Por favor, contacta al administrador."
    );
}

// Establecer el charset para prevenir problemas de codificación
// utf8mb4 soporta caracteres Unicode completos (incluyendo emojis)
$mysqli->set_charset(DB_CHARSET);

/**
 * Función helper para obtener la conexión en cualquier parte de la aplicación
 * Usa la variable global $mysqli definida al inicio del archivo.
 * Para aplicaciones más grandes, considera usar un patrón singleton o inyección
 * de dependencias en lugar de variables globales.
 *
 * @return mysqli Instancia de la conexión a la base de datos
 */
function getConnection()
{
    global $mysqli;
    return $mysqli;
}
?>
