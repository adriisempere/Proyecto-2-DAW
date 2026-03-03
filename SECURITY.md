# Guía de seguridad del proyecto

Este documento detalla las consideraciones de seguridad y mejores prácticas implementadas en el proyecto GreenPoints.

---

## Medidas de seguridad implementadas

### 1. Protección contra CSRF (Cross-Site Request Forgery)
Implementamos tokens CSRF únicos por sesión para validar las llamadas que modifican datos (registro, login, envío de reciclaje, etc.).
- Se genera un campo oculto en cada formulario mediante `CsrfHelper`.
- El token se verifica en los endpoints antes de ejecutar cualquier acción.

### 2. Hashing de Contraseñas
Las contraseñas no se guardan en texto plano; se aplican hashes mediante la función `password_hash()` de PHP, usando el valor por defecto de algoritmo (actualmente Bcrypt). Durante el acceso se comprueba con `password_verify()`.

### 3. Prevención de Inyección SQL
Todas las consultas que incluyen datos de usuarios se ejecutan con sentencias preparadas (`prepare()` y `bind_param()`), eliminando la mayor parte del riesgo de inyección SQL.

### 4. Gestión de Sesiones Seguras
Las sesiones se inician con `session_start()` y, tras iniciar sesión, se regenera el identificador para dificultar el secuestro de sesiones.

### 5. Escape de Salida (XSS)
Se aplica `htmlspecialchars()` en la salida de datos en todas las vistas para evitar inyecciones de código (XSS).

---

## 🏗️ Configuración Segura

### Variables de entorno
La configuración de la base de datos se puede cargar desde variables de entorno. `config/database.php` utiliza `getenv()` y ofrece valores por defecto para un entorno de desarrollo. En producción se recomienda un `.env` (o manejo similar) fuera del control de versiones.

### Permisos de archivos recomendados
En servidores Linux conviene mantener permisos restrictivos:
- Directorios con `755`
- Archivos con `644`
- Archivos sensibles (`config/database.php`, etc.) con `600` si es posible.

---

## Problemas conocidos y mejoras pendientes

A partir de la versión actual se detectan algunas carencias que conviene resolver:
- Aplicar límites de intentos de acceso para mitigar ataques de fuerza bruta.
- Ampliar la validación de datos en el servidor, más allá de las comprobaciones locales de JavaScript.
- Añadir cabeceras de seguridad HTTP (`Content-Security-Policy`, `X-Frame-Options`, etc.).

---

## 🆘 Reporte de Vulnerabilidades

Si detectas un fallo de seguridad, por favor repórtalo de manera privada para que podamos solucionarlo antes de que se haga público.

- **Contacto**: [admin@greenpoints.com](mailto:admin@greenpoints.com)

---

> **Nota**: Este proyecto es parte de un entorno académico (2º DAW). La seguridad se revisa constantemente como parte del proceso de aprendizaje. 🎓🔒
