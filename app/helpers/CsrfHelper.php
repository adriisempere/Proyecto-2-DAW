<?php
/**
 * CsrfHelper
 * Ayuda a proteger contra ataques Cross-Site Request Forgery
 */
class CsrfHelper {
    /**
     * Genera un token CSRF y lo guarda en la sesión si no existe
     * @return string El token CSRF
     */
    public static function generateToken() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        
        return $_SESSION['csrf_token'];
    }

    /**
     * Verifica si el token enviado coincide con el de la sesión
     * @param string $token El token enviado por el formulario
     * @return bool True si es válido
     */
    public static function verifyToken($token) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['csrf_token'])) {
            return false;
        }
        
        return hash_equals($_SESSION['csrf_token'], $token);
    }
    
    /**
     * Genera el campo input oculto para usar en formularios
     * @return string HTML input tag
     */
    public static function getTokenField() {
        return '<input type="hidden" name="csrf_token" value="' . self::generateToken() . '">';
    }
}
