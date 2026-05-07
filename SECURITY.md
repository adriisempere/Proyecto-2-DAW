# Guía de seguridad - GreenPoints

---

## Medidas implementadas

### CSRF
Tokens únicos por sesión generados con `random_bytes(32)`. Se verifican en todas las peticiones que modifican datos (registro, login, reciclaje, canje). Comparación con `hash_equals()` para evitar ataques de temporización.

### Contraseñas
Hash con `password_hash(PASSWORD_DEFAULT)` -> bcrypt. Verificación con `password_verify()`. Nunca se almacenan en texto plano.

### Inyección SQL
Todas las consultas con datos de usuario usan sentencias preparadas (`prepare()` + `bind_param()`).

### Sesiones
- Cookie configurada con `HttpOnly`, `SameSite=Lax`, `path=/`
- Regeneración del ID de sesión tras login (`session_regenerate_id()`)
- La cookie de sesión se limpia explícitamente al cerrar sesión

### XSS
`htmlspecialchars()` en toda salida de datos en vistas. En JavaScript se usa `textContent` para evitar inyección al insertar contenido dinámico.

### Transacciones atómicas
Las operaciones que modifican múltiples tablas (canje de recompensas, registro de reciclaje, eliminación) se envuelven en transacciones con rollback ante fallos.

### Precios validados en servidor
En el canje de recompensas, los costes se leen de la base de datos. Nunca se confía en los precios enviados desde el cliente.

---

## Pendiente

- Límite de intentos de acceso (fuerza bruta)
- Cabeceras HTTP de seguridad (`Content-Security-Policy`, `X-Frame-Options`, etc.)

---

## Reporte de vulnerabilidades

**Contacto:** [adriisempere@proton.me](mailto:adriisempere@proton.me)
