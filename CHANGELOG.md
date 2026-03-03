# 📋 Changelog - GreenPoints

Todos los cambios notables del proyecto serán documentados en este archivo.

El formato está basado en [Keep a Changelog](https://keepachangelog.com/es-ES/1.0.0/).

---

## [Unreleased] - 2026-01-20

### ✨ Añadido
- **Sistema de Partials Completo**
  - Header reutilizable con navbar, sistema de alertas y menú de usuario
  - Footer profesional de 4 columnas con newsletter y redes sociales
  - Archivo de ejemplo (`ejemplo_vista.php`) mostrando uso de partials
  
- **Header Features**
  - Navbar con gradiente verde (identidad de marca)
  - Sistema de usuario con badge de nombre y puntos
  - Menú dropdown con opciones de perfil y administración
  - Sistema de alertas automático (success, error, info)
  - Detección automática de página activa
  - Efecto sticky con animación al hacer scroll
  - Diseño completamente responsive

- **Footer Features**
  - Layout de 4 columnas (marca, app, soporte, newsletter)
  - Estadísticas destacadas con estilos mejorados
  - Enlaces a redes sociales con animaciones
  - Formulario de suscripción a newsletter
  - Información de contacto completa
  - Botón scroll-to-top automático
  - Copyright dinámico con año actual

- **Documentación**
  - Actualización completa del README.md
  - Sección detallada sobre sistema de partials
  - Ejemplos de uso de header y footer
  - CHANGELOG.md para seguimiento de cambios
  - INSTALL.md con guía de instalación paso a paso
  - SECURITY.md con mejores prácticas de seguridad

### 🔧 Modificado
- **Vistas Refactorizadas**
  - `home.php` ahora usa partials (header/footer)
  - Eliminado HTML duplicado de las vistas
  - Estructura más limpia y mantenible
  
- **Estilos del Footer**
  - Mejorado contraste de texto para mejor legibilidad
  - Colores ajustados: `rgba(255,255,255,0.7)` para texto secundario
  - Títulos en blanco puro para mejor visibilidad
  - Bordes sutiles en estadísticas para definición
  - Enlaces con hover hacia blanco completo

- **Controladores Mejorados**
  - `UsuarioController.php`: Sistema completo de autenticación
  - `CentroController.php`: CRUD de centros de reciclaje
  - `RegistroController.php`: Sistema de puntos y transacciones
  - `RankingController.php`: Ranking con estadísticas completas

- **Configuración de Base de Datos**
  - Soporte para variables de entorno
  - Manejo de errores mejorado
  - Función helper `getConnection()`
  - Charset UTF-8 configurado

### 🐛 Corregido
- Problema de visualización: vistas no usaban partials
- Conflicto de HTML duplicado en vistas
- Problemas de contraste en el footer
- Enlaces del navbar que apuntaban a `#`
- Botones del hero sin enlaces funcionales

### 🗑️ Eliminado
- Archivo duplicado `UsuarioControllers.php` (con 's' final)
- HTML redundante en vistas individuales
- Estilos inline duplicados
- **Archivos CSS/SCSS sin uso** (`estilos.css`, `estilos.scss`, `estilos.css.map`)
- **JavaScript sin uso** (`app.js` - solo tenía console.log)
- Archivos innecesarios que no se cargaban en ninguna vista

### 🔒 Seguridad
- Password hashing con bcrypt
- Prepared statements en todas las consultas
- Escape de salida HTML con `htmlspecialchars()`
- Manejo seguro de sesiones

---

### 🔁 Cambios importantes (2026-03-03)

### ✨ Añadido
- APIs JSON en `public/api/` para exponer la lógica de la aplicación: `users.php`, `registro.php`, `centros.php`, `ranking.php`.
- Scripts cliente en `public/js/` para manejar autenticación y formularios vía `fetch`: `api-users.js`, `api-registro.js`.
- Nuevas vistas con carga dinámica: `app/views/centros.php`, `ranking.php`, `mis_registros.php`, `perfil.php`.

### 🔧 Modificado
- Simplificado el enrutador: `public/index.php` ahora incluye vistas directamente y delega operaciones a las APIs.
- Vistas existentes actualizadas para funcionar con JavaScript (login, register, registro_create y otras), reduciendo recargas y mejorando UX.
- `app/views/partials/header.php` adaptado para usar variables de sesión simplificadas (`usuario_id`, `usuario_nombre`, `usuario_puntos`).

### 🗑️ Eliminado
- Eliminados los controladores PHP de la carpeta `app/controllers/` (UsuarioController, CentroController, RegistroController, RankingController). La lógica pasó a las APIs en `public/api/`.

### 🔒 Seguridad / Documentación
- `README.md` y `SECURITY.md` actualizados para reflejar la nueva arquitectura (API-first y JS-heavy) y las medidas de seguridad vigentes.
- Se mantuvieron las buenas prácticas: tokens CSRF, prepared statements y hashing de contraseñas.


## [0.2.0] - 2026-01-19

### ✨ Añadido
- Vistas de login y registro funcionales
- Sistema de validación básico
- Integración completa de Bootstrap 5
- Animaciones con Animate.css

### 🔧 Modificado
- Mejoras en la landing page
- Optimización de estilos CSS

---

## [0.1.0] - 2026-01-15

### ✨ Añadido
- Estructura inicial del proyecto MVC
- Configuración de base de datos
- Script SQL con tablas y relaciones
- Controladores base
- Sistema de enrutamiento (Front Controller)
- Vistas básicas
- README.md inicial

---

## 📝 Tipos de Cambios

- ✨ **Añadido**: Nuevas características
- 🔧 **Modificado**: Cambios en funcionalidad existente
- 🐛 **Corregido**: Corrección de bugs
- 🗑️ **Eliminado**: Características removidas
- 🔒 **Seguridad**: Mejoras de seguridad
- 📚 **Documentación**: Cambios en documentación
- 🎨 **Estilos**: Cambios en diseño/UI
- ⚡ **Performance**: Mejoras de rendimiento
- ♻️ **Refactoring**: Cambios en código sin afectar funcionalidad

---

## 🔗 Enlaces

- [README.md](README.md)
- [INSTALL.md](INSTALL.md)
- [SECURITY.md](SECURITY.md)
- [Repositorio GitHub](https://github.com/tu-usuario/Proyecto-2-DAW)
