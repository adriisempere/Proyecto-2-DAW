<?php

/**
 * Clase Database
 * Maneja la conexión a la base de datos de forma segura y eficiente.
 */
class Database {
    private static $instance = null;
    private $connection;

    // Configuración mediante constantes (priorizando variables de entorno)
    private const HOST =  'DB_HOST';
    private const USER =  'DB_USER';
    private const PASS =  'DB_PASS';
    private const NAME =  'DB_NAME';
    private const CHARSET = 'DB_CHARSET';

    /**
     * Constructor privado para evitar instanciación externa (Singleton)
     */
    private function __construct() {
        // Configurar mysqli para que lance excepciones en lugar de errores tradicionales
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        try {
            $this->connection = new mysqli(
                getenv(self::HOST) ?: 'localhost',
                getenv(self::USER) ?: 'root',
                getenv(self::PASS) ?: '',
                getenv(self::NAME) ?: 'greenpoints'
            );

            $charset = getenv(self::CHARSET) ?: 'utf8mb4';
            $this->connection->set_charset($charset);

        } catch (mysqli_sql_exception $e) {
            // Loguear el error real para el desarrollador
            error_log("Error de Conexión DB: " . $e->getMessage());
            
            // Mensaje amigable para el usuario final
            die("Lo sentimos, hay un problema temporal con el servidor de datos.");
        }
    }

    /**
     * Obtiene la instancia única de la conexión
     * @return mysqli
     */
    public static function getConnection() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->connection;
    }

    // Evitar la clonación del objeto
    private function __clone() {}
}

/**
 * Helper global para mantener compatibilidad con tu código actual
 * Uso: $db = getConnection();
 */
function getConnection() {
    return Database::getConnection();
}