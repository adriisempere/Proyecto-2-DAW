# 🔒 Guía de Seguridad - GreenPoints

Este documento detalla las consideraciones de seguridad y mejores prácticas implementadas en el proyecto GreenPoints.

---

## 🛡️ Medidas de Seguridad Implementadas

### 1. Protección contra CSRF (Cross-Site Request Forgery)
✅ **Implementado**: Utilizamos tokens CSRF únicos por sesión para validar cualquier petición POST crítica (Login, Registro, etc.).
- **Helper**: `app/helpers/CsrfHelper.php`
- **Uso**: Se genera un campo oculto en los formularios y se valida en los controladores antes de procesar la lógica de negocio.

### 2. Hashing de Contraseñas
✅ **Implementado**: Las contraseñas NUNCA se almacenan en texto plano. Utilizamos `password_hash()` con el algoritmo **Bcrypt**.
- **Algoritmo**: `PASSWORD_DEFAULT`
- **Verificación**: `password_verify()` durante el inicio de sesión.

### 3. Prevención de Inyección SQL
✅ **Implementado**: Todas las consultas a la base de datos se realizan mediante **Sentencias Preparadas (Prepared Statements)** a través de la extensión MySQLi.
- **Técnica**: `bind_param()` para desacoplar los datos de la lógica SQL.

### 4. Gestión de Sesiones Seguras
✅ **Implementado**:
- Uso de `session_start()` con configuraciones recomendadas.
- Regeneración de ID de sesión tras el login para prevenir el secuestro de sesiones.

### 5. Escape de Salida (XSS)
✅ **Implementado**: Se utiliza `htmlspecialchars()` en las vistas para sanitizar cualquier dato dinámico proveniente de la base de datos o del usuario antes de renderizarlo en el navegador.

---

## 🏗️ Configuración Segura

### Variables de Entorno
El sistema está preparado para no exponer credenciales sensibles en el código fuente.
- **Archivo de Configuración**: `config/database.php` utiliza `getenv()` para cargar las credenciales.
- **Recomendación**: En entornos locales, crea un archivo `.env` (siguiendo el ejemplo de `INSTALL.md`) y asegúrate de que esté en tu `.gitignore`.

### Permisos de Archivos Recomendados
En un entorno de producción (Linux/Apache), se recomiendan los siguientes permisos:
- **Directorios**: `755`
- **Archivos**: `644`
- **Configuraciones sensibles**: `600` (especialmente `config/database.php`)

---

## 🚦 Problemas Conocidos y Mejoras Pendientes

Aunque hemos avanzado significativamente, todavía hay áreas en desarrollo:
- [ ] **Rate Limiting**: Implementar un límite de intentos de inicio de sesión para prevenir ataques de fuerza bruta.
- [ ] **Validación Avanzada**: Migrar a una validación de inputs más robusta por el lado del servidor.
- [ ] **Headers de Seguridad**: Implementar headers HTTP como `Content-Security-Policy` y `X-Frame-Options`.

---

## 🆘 Reporte de Vulnerabilidades

Si detectas un fallo de seguridad, por favor repórtalo de manera privada para que podamos solucionarlo antes de que se haga público.

- **Contacto**: [admin@greenpoints.com](mailto:admin@greenpoints.com)

---

> **Nota**: Este proyecto es parte de un entorno académico (2º DAW). La seguridad se revisa constantemente como parte del proceso de aprendizaje. 🎓🔒
