# Changelog — GreenPoints

Todos los cambios relevantes del proyecto quedan registrados aquí.  
El formato sigue el estándar [Keep a Changelog](https://keepachangelog.com/es-ES/1.0.0/).

---

## [Unreleased]

### Seguridad

- **`public/api/users.php`** — Cookie de sesión reforzada con los atributos `HttpOnly`, `SameSite: Lax` y `Secure` (activado automáticamente cuando el servidor usa HTTPS). Antes la cookie se creaba sin ninguna de estas protecciones.
- **`public/api/users.php`** — `logout` ahora vacía `$_SESSION`, elimina la cookie del navegador y destruye la sesión. El comportamiento anterior solo llamaba a `session_destroy()`, dejando la cookie activa en el cliente.
- **`public/api/users.php`** — El endpoint `list` requiere ahora sesión activa con rol `admin`. Antes era accesible sin autenticación y devolvía el email de todos los usuarios.
- **`public/api/users.php`** — El bloque `catch` ya no retorna `$e->getMessage()` al cliente; el detalle del error se redirige al log del servidor mediante `error_log()`.
- **`public/api/centros.php`** — La inicialización de sesión pasa a usar `session_set_cookie_params()` con `HttpOnly`, `SameSite: Lax` y `Secure`, en línea con el resto de APIs. Antes usaba `session_start()` directo sin ningún parámetro de seguridad.
- **`public/api/centros.php`** — El bloque `catch` redirige el detalle del error al log del servidor en lugar de exponerlo al cliente.

### Añadido

- **`public/api/users.php`** — Nuevo endpoint `me`: devuelve los datos del usuario autenticado directamente desde la sesión, sin consulta adicional a la base de datos. Útil para poblar el header y la vista de perfil.
- **`public/api/users.php`** — El endpoint `login` incluye ahora el campo `rol` en la respuesta, permitiendo al frontend mostrar u ocultar opciones de administrador sin una petición extra.
- **`public/api/users.php`** — Validación de longitud mínima de contraseña (6 caracteres) en el registro.
- **`public/api/users.php`** — Funciones auxiliares internas `isAuthenticated()` e `isAdmin()` para centralizar las comprobaciones de acceso y evitar código duplicado.
- **`public/api/users.php`** — Cabecera de documentación describiendo cada endpoint, esquema de respuesta JSON y propósito general del módulo.
- **`public/api/centros.php`** — Nuevo endpoint `update`: permite a un administrador modificar el nombre, dirección, tipos de residuos y horario de un centro existente.
- **`public/api/centros.php`** — Nuevo endpoint `delete`: permite a un administrador eliminar un centro. Devuelve error si el ID no existe.
- **`public/api/centros.php`** — El endpoint `store` devuelve ahora el `id` del centro recién creado en la respuesta, evitando una consulta adicional desde el cliente.
- **`public/api/centros.php`** — Cabecera de documentación describiendo cada endpoint y su nivel de acceso requerido.

### Modificado

- **`public/api/users.php`** — `verifyCsrf()` recibe ahora el array `$data` completo en lugar del token ya extraído, centralizando la lógica de búsqueda del token en un único punto.
- **`public/api/users.php`** — Mensajes de error de login unificados: usuario inexistente y contraseña incorrecta devuelven el mismo texto (`Credenciales incorrectas`) para evitar la enumeración de emails registrados.
- **`public/api/centros.php`** — El endpoint `store` valida que ningún campo llegue vacío antes de intentar la inserción. Antes insertaba registros con campos en blanco sin avisar.
- **`public/api/centros.php`** — La función `requireAdmin()` centraliza la comprobación de rol, eliminando el condicional inline que había en `store`.
- **`public/api/registro.php`** — Cookie de sesión actualizada con `HttpOnly`, `SameSite: Lax` y `Secure`, en línea con el resto de APIs.
- **`public/api/registro.php`** — Los bloques `catch` redirigen los detalles del error al log del servidor en lugar de exponer mensajes genéricos sin contexto.
- **`public/api/registro.php`** — El mapa de puntos por material pasa a ser una constante `PUNTOS_POR_MATERIAL` definida a nivel de archivo, en lugar de un array hardcodeado dentro del `switch`. Facilita futuros ajustes de puntuación sin buscar dentro de la lógica.
- **`public/api/registro.php`** — Nuevo endpoint `delete`: elimina un registro de reciclaje propio y descuenta los puntos correspondientes al usuario, todo dentro de una transacción. Usa `GREATEST(0, ...)` en el UPDATE para evitar que los puntos totales bajen de cero. Verifica que el registro pertenezca al usuario antes de actuar.
- **`public/api/registro.php`** — `store` devuelve ahora el `id` del nuevo registro y los `puntos_totales` actualizados, evitando que el cliente tenga que hacer una petición extra para refrescar el marcador de puntos.
- **`public/api/registro.php`** — `list` selecciona ahora columnas explícitas en lugar de `r.*`, evitando traer datos innecesarios como `usuario_id` que el cliente ya conoce.
- **`public/api/registro.php`** — Función `requireAuth()` centraliza la comprobación de sesión activa, eliminando el condicional `isset($_SESSION['usuario_id'])` repetido en cada `case`.
- **`public/api/registro.php`** — Validaciones de `store` separadas: error específico si el material no es válido, y error específico si la cantidad es ≤ 0. Antes ambos casos devolvían el mismo mensaje `Datos inválidos`.
- **`public/api/ranking.php`** — Cookie de sesión actualizada con `HttpOnly`, `SameSite: Lax` y `Secure`, en línea con el resto de APIs.
- **`public/api/ranking.php`** — El bloque `catch` redirige el error al log del servidor. Antes el mensaje genérico se devolvía sin registrar nada.

### Añadido (ranking)

- **`public/api/ranking.php`** — Nuevo endpoint `stats`: devuelve los totales globales de la plataforma (usuarios activos, reciclajes realizados, kg reciclados, puntos repartidos y CO₂ ahorrado estimado). Útil para la sección de impacto de la landing o el dashboard.
- **`public/api/ranking.php`** — Nuevo endpoint `me`: calcula y devuelve la posición exacta del usuario autenticado dentro del ranking junto con sus estadísticas personales, sin necesidad de cargar el top 100 completo.
- **`public/api/ranking.php`** — `list` devuelve ahora el campo `total` con el número de entradas del ranking.
- **`public/api/ranking.php`** — Constante `KG_CO2_POR_KG_RECICLADO` (1.5) documentada para el cálculo del CO₂ ahorrado, con comentario sobre la estimación.
- **`public/api/ranking.php`** — `list` maneja correctamente el caso de 0 usuarios, devolviendo array vacío en lugar de fallar.
- **`public/api/ranking.php`** — Cabecera de documentación describiendo cada endpoint y su nivel de acceso.
- **`app/helpers/CsrfHelper.php`** — Nuevo método `rotateToken()`: regenera el token invalidando el anterior. Permite llamarlo tras un login o registro exitoso para que el mismo token no pueda reutilizarse en una segunda petición.
- **`app/helpers/CsrfHelper.php`** — Método privado `ensureSession()` que centraliza la comprobación `session_status() === PHP_SESSION_NONE` eliminando el bloque duplicado que existía en `generateToken()` y `verifyToken()`.
- **`app/helpers/CsrfHelper.php`** — Cabecera de documentación con ejemplos de uso en formularios HTML y en APIs JSON.

### Modificado (helpers)

- **`app/helpers/CsrfHelper.php`** — `verifyToken()` acepta ahora `?string` en lugar de `$token` sin tipo. Evita el warning de PHP 8 cuando llega `null` desde la API antes de que `hash_equals()` lo reciba.
- **`app/helpers/CsrfHelper.php`** — `getTokenField()` escapa el token con `htmlspecialchars()` antes de insertarlo en el HTML. Aunque `bin2hex` produce solo caracteres seguros, es una capa de defensa en profundidad consistente con el resto del proyecto.
- **`app/helpers/CsrfHelper.php`** — Todos los métodos públicos tienen type hints de retorno explícitos (`string`, `bool`, `void`) para consistencia con las APIs mejoradas en esta misma versión.
- **`app/views/login.php`** — Migrada para usar `partials/header.php` y `partials/footer.php`. Antes tenía su propio `<!DOCTYPE html>` completo, duplicando navbar, footer y todos los assets. Cualquier cambio en el header había que aplicarlo en tres sitios distintos.
- **`app/views/login.php`** — Añadida redirección automática al inicio si el usuario ya tiene sesión activa, evitando que un usuario autenticado vea el formulario de login.
- **`app/views/login.php`** — Añadido botón de mostrar/ocultar contraseña.
- **`app/views/login.php`** — Añadida alerta dinámica `#loginAlert` para mostrar errores de la API sin recargar la página. Antes los errores solo podían venir de `$_SESSION['error']`.
- **`app/views/login.php`** — El botón de submit muestra spinner y se deshabilita durante el envío para evitar envíos duplicados.
- **`app/views/register.php`** — Migrada para usar `partials/header.php` y `partials/footer.php`, igual que login.
- **`app/views/register.php`** — Añadida redirección automática si el usuario ya tiene sesión activa.
- **`app/views/register.php`** — Añadido botón de mostrar/ocultar contraseña.
- **`app/views/register.php`** — El indicador de fuerza de contraseña muestra ahora la etiqueta textual (Débil / Media / Fuerte) además de la barra de color.
- **`app/views/register.php`** — La revalidación de confirmación de contraseña se dispara también al modificar el campo de contraseña principal, manteniendo ambos campos sincronizados.
- **`app/views/register.php`** — El botón de submit muestra spinner y se deshabilita durante el envío.
- **`app/views/register.php`** — Tras registro exitoso redirige automáticamente al login con mensaje de éxito, en lugar de requerir navegación manual.
- **`app/views/home.php`** — Las tres estadísticas de la sección de impacto (`+10k usuarios`, `50 Ton`, `120 puntos`) eran valores hardcodeados. Ahora se cargan dinámicamente desde `api/ranking.php?action=stats` al cargar la página.
- **`app/views/home.php`** — Mientras se cargan las estadísticas se muestra un esqueleto de carga (Bootstrap placeholder) en lugar de la página en blanco o los datos falsos.
- **`app/views/home.php`** — Si la API falla, las estadísticas muestran `—` en lugar de datos inventados, evitando información falsa visible al usuario.
- **`app/views/home.php`** — Los kg reciclados y CO₂ ahorrado se formatean automáticamente en toneladas cuando superan 1000 kg.
- **`app/views/home.php`** — El hero adapta los botones de acción según el estado de sesión: un usuario autenticado ve "Registrar Reciclaje" y "Ver Ranking" en lugar de "Registro" e "Iniciar Sesión".
- **`app/views/home.php`** — Limpieza del texto del hero: eliminada la frase con errata ("Ésto lo hacemos"), redactado de forma más directa y clara.
- **`app/views/ranking.php`** — Todos los datos de usuario insertados via `innerHTML` ahora pasan por `esc()`, eliminando el riesgo de XSS que existía antes.
- **`app/views/ranking.php`** — Añadido podio visual para el top 3 con medallas y barras de altura proporcional al puesto.
- **`app/views/ranking.php`** — Si el usuario está autenticado, se carga su posición exacta desde `api/ranking.php?action=me` y se muestra en un banner sobre la tabla. Su fila en el top 100 se resalta en verde con etiqueta "Tú".
- **`app/views/ranking.php`** — La tabla incluye columnas de kg reciclados y número de reciclajes, visibles en pantallas medianas y grandes.
- **`app/views/ranking.php`** — Esqueleto de carga y estado vacío con mensaje específico.
- **`app/views/mis_registros.php`** — Todos los datos insertados via `innerHTML` pasan por `esc()` para prevenir XSS.
- **`app/views/mis_registros.php`** — Añadida protección de ruta: redirige al login si no hay sesión activa.
- **`app/views/mis_registros.php`** — Botón de eliminar en cada tarjeta con modal de confirmación. Tras borrar actualiza el resumen y el badge de puntos del header sin recargar la página.
- **`app/views/mis_registros.php`** — Sección de resumen rápido con total de registros, kg reciclados y puntos acumulados.
- **`app/views/mis_registros.php`** — Las fechas se formatean en español legible en lugar del timestamp en crudo.
- **`app/views/mis_registros.php`** — Icono distinto por tipo de material y esqueleto de carga animado.
- **`app/views/centros.php`** — Todos los datos insertados via `innerHTML` pasan por `esc()` para prevenir XSS.
- **`app/views/centros.php`** — Buscador en tiempo real que filtra por nombre, dirección y tipo de residuo sin llamadas adicionales a la API.
- **`app/views/centros.php`** — Los tipos de residuos se muestran como badges individuales en lugar de texto plano.
- **`app/views/centros.php`** — Si el usuario es admin, aparece un botón "Nuevo Centro" que abre un modal. Tras guardar, el centro aparece en la lista sin recargar la página.
- **`app/views/centros.php`** — Esqueleto de carga animado mientras se obtienen los datos.
- **`app/views/perfil.php`** — Las tarjetas de kg reciclados y CO₂ ahorrado ahora se cargan desde `api/ranking.php?action=me` en lugar de mostrar datos estáticos hardcodeados.
- **`app/views/perfil.php`** — La fecha de registro se obtiene desde `api/users.php?action=me` y se muestra en formato legible en español, en lugar de "Información no disponible".
- **`app/views/perfil.php`** — Añadida tarjeta de posición en el ranking cargada dinámicamente, sin necesidad de abrir la vista de ranking.
- **`app/views/perfil.php`** — El badge de Administrador se muestra en la cabecera del perfil si el usuario tiene rol admin, junto con un botón adicional "Gestionar Centros" en las acciones rápidas.
- **`app/views/perfil.php`** — Las tarjetas de estadísticas dinámicas muestran un esqueleto de carga mientras esperan la respuesta de la API.
- **`app/views/perfil.php`** — El nivel se calcula correctamente en PHP con `elseif` encadenado. La versión anterior usaba `if` independientes que podían sobreescribirse entre sí.
- **`app/views/registro_create.php`** — Añadida redirección al login si no hay sesión activa, igual que en `mis_registros.php`.
- **`app/views/registro_create.php`** — El campo de centro de reciclaje pasa a ser opcional con opción por defecto "Sin centro específico", en lugar de requerido. Permite registrar sin un centro asociado.
- **`app/views/registro_create.php`** — Añadido preview en tiempo real de los puntos que se ganarán, calculado en cliente a partir del material y la cantidad antes de enviar el formulario.
- **`app/views/registro_create.php`** — Los radio buttons de material se generan desde la constante `MATERIALES` en JS, que incluye los pts/kg de cada tipo, manteniéndose en sincronía con `PUNTOS_POR_MATERIAL` de la API.
- **`app/views/registro_create.php`** — Tras registro exitoso, actualiza el badge de puntos del header y redirige al historial automáticamente.

### Rendimiento

- **`public/img/LogoGreenPoints.png`** — Comprimido de 1.1 MB a 11.8 KB (reducción del 99%). La imagen original era un PNG 1024×1024 sin optimizar.
- **`public/img/LogoGreenPoints.webp`** — Nueva versión WebP del logo (5 KB). Los navegadores modernos la sirven automáticamente gracias al elemento `<picture>` en `home.php`.
- **`app/views/home.php`** — El `<img>` del logo en el hero sustituido por `<picture>` con `<source type="image/webp">` y fallback PNG para navegadores sin soporte WebP.

### Corregido

- **`public/index.php`** — `case 'logout'`: añadido `exit` tras `header('Location: ...')`. Sin él PHP seguía ejecutando el resto del script tras enviar el redirect, lo que podía exponer código de otras rutas.
- **`public/index.php`** — `case 'tienda'` y `case 'mis_canjes'`: paths de `include` cambiados de relativos (`'../app/views/...'`) a absolutos con `__DIR__`. Los paths relativos dependen del directorio de trabajo del servidor y fallan cuando el entry point no es `public/`.
- **`app/views/registro_create.php`** — El JS de validación de Bootstrap ha sido reemplazado por validación propia que soporta el campo de material (radio buttons) y da mensajes específicos por campo.
- **`sql/greenpoints_recompensas.sql`** — Nuevas tablas `recompensa` y `canje`. Incluye 8 recompensas de ejemplo (Amazon, Steam, Netflix, Apple, Spotify) con sus precios en puntos.
- **`public/api/recompensas.php`** — Nueva API con tres endpoints: `list` (catálogo público), `checkout` (procesa el carrito en una transacción, valida puntos en servidor sin confiar en el cliente, y genera códigos ficticios con formato `GP-XXXX-XXXX-XXXX`) y `mis_canjes` (historial del usuario con códigos).
- **`app/views/tienda.php`** — Nueva vista de tienda con carrito lateral deslizante, filtros por marca, controles de cantidad por tarjeta, preview del saldo resultante antes de confirmar, modal de confirmación con desglose completo y modal de éxito con los códigos generados listos para copiar al portapapeles.
- **`app/views/mis_canjes.php`** — Nueva vista con historial de todos los canjes y sus códigos, con copia al portapapeles en un clic.

---

## [0.4.0] — 2026-03-30

### Añadido

- **`app/views/perfil.php`** — Rediseño completo basado en tarjetas consistentes con la identidad visual del proyecto (variables CSS `--primary-color` y `--secondary-color`).
- **`app/views/perfil.php`** — Header con degradado dinámico y avatar circular que muestra la inicial del usuario.
- **`app/views/perfil.php`** — Animaciones de aparición encadenadas con `Animate.css` (`fadeInDown`, `fadeInUp`, `zoomIn`).
- **`app/views/perfil.php`** — Panel de acciones rápidas con acceso directo a registros, historial y ranking.
- **`app/views/partials/header.php`** — Navbar reutilizable con gradiente de marca, badge de puntos del usuario, menú dropdown con opciones de perfil y administración, detección automática de página activa y efecto sticky al hacer scroll.
- **`app/views/partials/footer.php`** — Footer de cuatro columnas (marca, aplicación, soporte, newsletter) con estadísticas destacadas, enlaces a redes sociales, formulario de suscripción y botón de scroll-to-top.
- **`CHANGELOG.md`**, **`INSTALL.md`**, **`SECURITY.md`** — Documentación inicial del proyecto.

### Modificado

- **`app/views/home.php`** — Integración de los partials de header y footer; eliminado el HTML duplicado.
- **`app/views/partials/footer.php`** — Contraste de texto mejorado (`rgba(255,255,255,0.7)` para texto secundario, blanco puro en títulos); bordes sutiles en estadísticas para mayor definición.
- **`app/controllers/`** — Controladores actualizados: `UsuarioController`, `CentroController`, `RegistroController` y `RankingController`.
- **`config/database.php`** — Añadido soporte para variables de entorno, manejo de errores mejorado, función helper `getConnection()` y charset UTF-8 explícito.

### Eliminado

- **`app/controllers/UsuarioControllers.php`** — Archivo duplicado con 's' final en el nombre.
- **`public/css/estilos.css`**, **`estilos.scss`**, **`estilos.css.map`** — Archivos de estilos sin uso que no se cargaban en ninguna vista.
- **`public/js/app.js`** — Archivo JavaScript vacío (solo contenía un `console.log`).
- HTML redundante y estilos inline duplicados en vistas individuales.

### Corregido

- Vistas que no incluían los partials de header y footer.
- Conflicto de HTML duplicado al incluir partials.
- Problemas de contraste en el footer.
- Enlaces del navbar que apuntaban a `#` sin destino.
- Botones del hero sin rutas funcionales.

---

## [0.3.0] — 2026-03-03

### Añadido

- **`public/api/`** — APIs JSON para exponer la lógica de la aplicación: `users.php`, `registro.php`, `centros.php`, `ranking.php`.
- **`public/js/`** — Scripts cliente para gestionar autenticación y formularios vía `fetch`: `api-users.js`, `api-registro.js`.
- **`app/views/`** — Nuevas vistas con carga dinámica: `centros.php`, `ranking.php`, `mis_registros.php`, `perfil.php`.

### Modificado

- **`public/index.php`** — Enrutador simplificado: incluye vistas directamente y delega las operaciones a las APIs, reduciendo recargas de página.
- **`app/views/`** — Vistas de login, registro y registro de reciclaje actualizadas para funcionar con JavaScript mediante `fetch`.
- **`app/views/partials/header.php`** — Adaptado para usar variables de sesión simplificadas (`usuario_id`, `usuario_nombre`, `usuario_puntos`).

### Eliminado

- **`app/controllers/`** — Eliminados los controladores PHP (`UsuarioController`, `CentroController`, `RegistroController`, `RankingController`). La lógica se trasladó a las APIs en `public/api/`.

### Seguridad

- Mantenidas las buenas prácticas existentes: tokens CSRF, prepared statements y hashing de contraseñas con bcrypt.
- Actualizados `README.md` y `SECURITY.md` para reflejar la nueva arquitectura API-first.

---

## [0.2.0] — 2026-01-19

### Añadido

- Vistas de login y registro funcionales.
- Sistema de validación básico en cliente.
- Integración completa de Bootstrap 5.
- Animaciones con `Animate.css`.

### Modificado

- Mejoras en la landing page.
- Optimización de estilos CSS.

---

## [0.1.0] — 2026-01-15

### Añadido

- Estructura inicial del proyecto siguiendo el patrón MVC.
- Configuración de base de datos (`config/database.php`).
- Script SQL con tablas, relaciones e inserts de ejemplo (`sql/greenpoints.sql`).
- Controladores base.
- Sistema de enrutamiento (Front Controller en `public/index.php`).
- Vistas básicas.
- `README.md` inicial.

---

## Tipos de cambio

| Etiqueta | Descripción |
|---|---|
| **Añadido** | Nuevas funcionalidades o archivos |
| **Modificado** | Cambios en funcionalidad existente |
| **Eliminado** | Funcionalidades o archivos eliminados |
| **Corregido** | Corrección de errores |
| **Seguridad** | Mejoras relacionadas con la seguridad |