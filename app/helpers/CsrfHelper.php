<?php
/**
 * CsrfHelper — GreenPoints
 * ---------------------------------------------------------------
 * Protección contra ataques Cross-Site Request Forgery (CSRF).
 *
 * Uso típico en formularios HTML:
 *   echo CsrfHelper::getTokenField();
 *
 * Uso típico en APIs JSON (enviar en el body):
 *   { "csrf_token": "<?= CsrfHelper::generateToken() ?>" }
 *
 * Verificación en el servidor (APIs):
 *   if (!CsrfHelper::verifyToken($data['csrf_token'] ?? null)) { ... }
 *
 * El token se genera una vez por sesión con 32 bytes aleatorios
 * (64 caracteres hexadecimales) y se compara siempre con
 * hash_equals() para prevenir ataques de temporización.
 * ---------------------------------------------------------------
 */
class CsrfHelper
{
    /**
     * Genera un token CSRF y lo almacena en sesión si aún no existe.
     * Si la sesión no está activa la inicia, aunque lo recomendado
     * es iniciarla antes de llamar a este método.
     *
     * @return string Token CSRF en formato hexadecimal (64 chars).
     */
    public static function generateToken(): string
    {
        self::ensureSession();

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    /**
     * Verifica si el token enviado coincide con el almacenado en sesión.
     * Usa hash_equals() para evitar ataques de temporización.
     * Devuelve false si el token es null, vacío o no coincide.
     *
     * @param  string|null $token Token recibido desde el cliente.
     * @return bool True si el token es válido.
     */
    public static function verifyToken(?string $token): bool
    {
        self::ensureSession();

        if (empty($token) || empty($_SESSION['csrf_token'])) {
            return false;
        }

        return hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Regenera el token CSRF invalidando el anterior.
     * Llamar tras operaciones sensibles completadas con éxito
     * (p. ej. después de un login o registro exitoso) para que
     * el mismo token no pueda reutilizarse en otra petición.
     *
     * @return string El nuevo token generado.
     */
    public static function rotateToken(): string
    {
        self::ensureSession();
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        return $_SESSION['csrf_token'];
    }

    /**
     * Devuelve un campo input oculto listo para insertar en formularios HTML.
     * El valor del token se escapa con htmlspecialchars por defensa en profundidad.
     *
     * @return string Etiqueta <input type="hidden"> con el token CSRF.
     */
    public static function getTokenField(): string
    {
        $token = htmlspecialchars(self::generateToken(), ENT_QUOTES, 'UTF-8');
        return '<input type="hidden" name="csrf_token" value="' . $token . '">';
    }

    /**
     * Inicia la sesión si todavía no está activa.
     * Método privado usado internamente para garantizar que
     * $_SESSION está disponible antes de leer o escribir el token.
     */
    private static function ensureSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
}