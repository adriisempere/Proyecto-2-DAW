# 🔒 Guía de Seguridad - GreenPoints

Este documento detalla las consideraciones de seguridad y mejores prácticas para el proyecto GreenPoints.

## ⚠️ Advertencias de Seguridad Actuales

El proyecto está en fase de desarrollo y **NO ESTÁ LISTO PARA PRODUCCIÓN**. Los siguientes aspectos de seguridad requieren atención:

### 🚨 Problemas Críticos a Resolver

1. **Credenciales Hardcodeadas**
   - ❌ Las credenciales de BD están en el código fuente
   - ✅ **Solución:** Usar variables de entorno o archivo `.env` (no versionado)

2. **Sin Protección CSRF**
   - ❌ Los formularios no tienen tokens CSRF
   - ✅ **Solución:** Implementar tokens CSRF en todos los formularios

3. **Validación Insuficiente**
   - ❌ Validación básica en servidor
   - ✅ **Solución:** Implementar validación robusta con librerías como Respect\Validation

4. **Sin Rate Limiting**
   - ❌ No hay límite de intentos de login
   - ✅ **Solución:** Implementar rate limiting para prevenir ataques de fuerza bruta

5. **Manejo de Errores**
   - ❌ Los errores de base de datos podrían exponer información sensible
   - ✅ **Solución:** Implementar logging apropiado sin exponer detalles al usuario

## ✅ Medidas de Seguridad Implementadas

### 1. Hashing de Contraseñas

Las contraseñas se almacenan usando `password_hash()` con bcrypt:

```php
$passwordHash = password_hash($password, PASSWORD_DEFAULT);
```

### 2. Prepared Statements

Se utilizan prepared statements para prevenir inyección SQL:

```php
$stmt = $this->db->prepare("SELECT * FROM usuario WHERE email = ?");
$stmt->bind_param("s", $email);
```

### 3. Escape de Salida HTML

En las vistas se usa `htmlspecialchars()`:

```php
<?= htmlspecialchars($_SESSION['error']); ?>
```

### 4. Charset UTF-8

La conexión a BD usa UTF-8 para prevenir problemas de codificación:

```php
$mysqli->set_charset('utf8mb4');
```

## 🛡️ Mejoras Recomendadas

### Para Desarrollo Inmediato

#### 1. Implementar Protección CSRF

Crea una clase helper para tokens CSRF:

```php
class CSRF {
    public static function generateToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    public static function validateToken($token) {
        return isset($_SESSION['csrf_token']) && 
               hash_equals($_SESSION['csrf_token'], $token);
    }
}
```

En formularios:

```php
<input type="hidden" name="csrf_token" value="<?= CSRF::generateToken(); ?>">
```

Al procesar:

```php
if (!CSRF::validateToken($_POST['csrf_token'] ?? '')) {
    die('Token CSRF inválido');
}
```

#### 2. Usar Variables de Entorno

Instala phpdotenv:

```bash
composer require vlucas/phpdotenv
```

Crea `.env` (y añádelo a `.gitignore`):

```env
DB_HOST=localhost
DB_USER=root
DB_PASS=tu_password_seguro
DB_NAME=greenpoints
```

En `config/database.php`:

```php
require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

define('DB_HOST', $_ENV['DB_HOST']);
define('DB_USER', $_ENV['DB_USER']);
define('DB_PASS', $_ENV['DB_PASS']);
define('DB_NAME', $_ENV['DB_NAME']);
```

#### 3. Implementar Rate Limiting

Limita intentos de login:

```php
class RateLimiter {
    public static function checkLoginAttempts($email) {
        $key = 'login_attempts_' . md5($email);
        
        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = ['count' => 0, 'time' => time()];
        }
        
        // Reset después de 15 minutos
        if (time() - $_SESSION[$key]['time'] > 900) {
            $_SESSION[$key] = ['count' => 0, 'time' => time()];
        }
        
        // Máximo 5 intentos
        if ($_SESSION[$key]['count'] >= 5) {
            return false;
        }
        
        $_SESSION[$key]['count']++;
        return true;
    }
}
```

#### 4. Validación de Entrada Robusta

```php
function validarEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function validarPassword($password) {
    // Mínimo 8 caracteres, una mayúscula, una minúscula, un número
    return strlen($password) >= 8 &&
           preg_match('/[A-Z]/', $password) &&
           preg_match('/[a-z]/', $password) &&
           preg_match('/[0-9]/', $password);
}
```

#### 5. Headers de Seguridad

En `public/index.php`, antes de cualquier salida:

```php
// Prevenir XSS
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");

// Content Security Policy
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' cdn.jsdelivr.net fonts.googleapis.com; font-src 'self' cdn.jsdelivr.net fonts.gstatic.com; img-src 'self' data: https:;");

// HTTPS (solo en producción)
// header("Strict-Transport-Security: max-age=31536000; includeSubDomains");
```

#### 6. Sanitización de Archivos Subidos

Cuando implementes subida de fotos de perfil:

```php
function validarImagen($file) {
    $allowed = ['image/jpeg', 'image/png', 'image/gif'];
    $maxSize = 2 * 1024 * 1024; // 2MB
    
    if (!in_array($file['type'], $allowed)) {
        return false;
    }
    
    if ($file['size'] > $maxSize) {
        return false;
    }
    
    // Verificar que realmente es una imagen
    $imageInfo = getimagesize($file['tmp_name']);
    return $imageInfo !== false;
}
```

### Para Producción

#### 1. HTTPS Obligatorio

- Obtén un certificado SSL (Let's Encrypt es gratis)
- Configura redirección HTTP → HTTPS
- Añade header Strict-Transport-Security

#### 2. Configuración de PHP Segura

En `php.ini` o `.htaccess`:

```ini
# Desactivar visualización de errores
display_errors = Off
log_errors = On
error_log = /ruta/a/logs/php_errors.log

# Limitar información expuesta
expose_php = Off

# Prevenir ejecución de código en uploads
allow_url_fopen = Off
allow_url_include = Off

# Límites de recursos
max_execution_time = 30
max_input_time = 60
memory_limit = 128M
post_max_size = 8M
upload_max_filesize = 2M
```

#### 3. Permisos de Archivos

```bash
# Archivos: 644 (rw-r--r--)
find . -type f -exec chmod 644 {} \;

# Directorios: 755 (rwxr-xr-x)
find . -type d -exec chmod 755 {} \;

# config/database.php: 600 (rw-------)
chmod 600 config/database.php

# Propietario del servidor web
chown -R www-data:www-data /ruta/al/proyecto
```

#### 4. Backup de Base de Datos

Automatiza backups regulares:

```bash
#!/bin/bash
# backup_db.sh
mysqldump -u root -p greenpoints > /backups/greenpoints_$(date +%Y%m%d_%H%M%S).sql
```

#### 5. Monitoreo y Logging

- Implementa logging de acciones críticas
- Monitorea intentos de login fallidos
- Audita cambios en la base de datos
- Configura alertas para actividad sospechosa

#### 6. Autenticación de Dos Factores (2FA)

Considera implementar 2FA para administradores usando:
- Google Authenticator
- Códigos por SMS
- Emails de verificación

## 🔍 Auditoría de Seguridad

### Checklist Pre-Producción

- [ ] Credenciales en variables de entorno
- [ ] Tokens CSRF en todos los formularios
- [ ] Validación robusta de entrada
- [ ] Escape de salida HTML
- [ ] Rate limiting implementado
- [ ] HTTPS configurado
- [ ] Headers de seguridad activos
- [ ] Logging implementado (sin datos sensibles)
- [ ] Permisos de archivos correctos
- [ ] Backups automatizados
- [ ] Plan de respuesta a incidentes
- [ ] Documentación de seguridad actualizada

### Herramientas Recomendadas

- **OWASP ZAP**: Escáner de vulnerabilidades
- **PHPStan**: Análisis estático de código PHP
- **Psalm**: Análisis de tipos y seguridad
- **Snyk**: Escaneo de dependencias vulnerables

## 📚 Recursos Adicionales

- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [PHP Security Guide](https://phpsecurity.readthedocs.io/)
- [MySQL Security Best Practices](https://dev.mysql.com/doc/refman/8.0/en/security-guidelines.html)

## 🆘 Reporte de Vulnerabilidades

Si encuentras una vulnerabilidad de seguridad, por favor **NO la publiques públicamente**. 

Contacta al equipo de desarrollo de forma privada en: security@greenpoints.com

---

**La seguridad es un proceso continuo, no un producto final. 🔒**
