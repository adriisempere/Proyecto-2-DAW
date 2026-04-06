# GreenPoints

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![PHP](https://img.shields.io/badge/PHP-%3E%3D8.0-777bb4?logo=php&logoColor=white)](https://www.php.net/)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952b3?logo=bootstrap&logoColor=white)](https://getbootstrap.com/)

Aplicación web para fomentar el reciclaje mediante un sistema de puntos y ranking. Los usuarios registran sus actividades de reciclaje, acumulan puntos según el material y la cantidad depositada, y compiten en un ranking global.

---

## Funcionalidades

- Registro e inicio de sesión con protección CSRF y contraseñas hasheadas con bcrypt.
- Registro de reciclaje con puntos calculados por tipo de material y kg depositados.
- Historial personal con opción de eliminar registros.
- Ranking global con podio, posición del usuario autenticado y estadísticas.
- Listado de centros de reciclaje con buscador en tiempo real.
- Panel de perfil con estadísticas personales (puntos, nivel, kg reciclados, CO₂ ahorrado).
- Panel de administración para crear y eliminar centros de reciclaje.

## Puntos por material

| Material | Puntos por kg |
|---|---|
| Metal | 15 |
| Plástico | 10 |
| Vidrio | 8 |
| Papel | 5 |
| Orgánico | 3 |

---

## Estructura del proyecto

```
Proyecto-2-DAW/
├── app/
│   ├── helpers/
│   │   └── CsrfHelper.php      # Generación y verificación de tokens CSRF
│   └── views/
│       ├── partials/
│       │   ├── header.php      # Navbar y head HTML (incluido en todas las vistas)
│       │   └── footer.php      # Footer y scripts JS
│       ├── home.php            # Landing con estadísticas globales
│       ├── login.php           # Formulario de inicio de sesión
│       ├── register.php        # Formulario de registro
│       ├── perfil.php          # Panel personal del usuario
│       ├── registro_create.php # Formulario de registro de reciclaje
│       ├── mis_registros.php   # Historial de reciclaje del usuario
│       ├── ranking.php         # Tabla de clasificación global
│       └── centros.php         # Listado de centros de reciclaje
├── config/
│   └── database.php            # Conexión MySQL con soporte para variables de entorno
├── public/
│   ├── api/
│   │   ├── users.php           # Endpoints: register, login, logout, me, list
│   │   ├── registro.php        # Endpoints: store, list, delete
│   │   ├── centros.php         # Endpoints: list, store, update, delete
│   │   └── ranking.php         # Endpoints: list, stats, me
│   ├── css/
│   │   └── custom.css          # Estilos y animaciones propias
│   ├── img/
│   └── index.php               # Enrutador: mapea ?action= a la vista correspondiente
├── sql/
│   └── greenpoints.sql         # Esquema completo con datos de ejemplo
├── CHANGELOG.md
├── INSTALL.md
└── SECURITY.md
```

---

## Instalación

Consulta [INSTALL.md](./INSTALL.md) para la guía completa. Resumen:

```bash
# 1. Clonar el repositorio
git clone https://github.com/adriisempere/Proyecto-2-DAW.git
cd Proyecto-2-DAW

# 2. Importar la base de datos
mysql -u root -p greenpoints < sql/greenpoints.sql

# 3. Configurar credenciales en config/database.php
#    o definir las variables de entorno DB_HOST, DB_USER, DB_PASS, DB_NAME

# 4. Arrancar el servidor
cd public
php -S localhost:8000
```

Accede a `http://localhost:8000`.

**Credenciales de prueba:**
- Admin: `admin@greenpoints.com` / `admin123`

---

## Tecnologías

| Capa | Tecnología |
|---|---|
| Backend | PHP 8+, MySQL 8 |
| Frontend | HTML5, CSS3, JavaScript ES6+, Bootstrap 5.3 |
| Librerías | Animate.css, Bootstrap Icons, Google Fonts (Poppins) |
| Herramientas | Git |

---

## Seguridad

- Tokens CSRF en todos los formularios y endpoints de escritura.
- Contraseñas almacenadas con `password_hash()` (bcrypt).
- Sesiones con `HttpOnly`, `SameSite: Lax` y `Secure` en producción.
- Consultas con `prepare()` y `bind_param()` para evitar inyección SQL.
- Errores internos registrados en el log del servidor, nunca expuestos al cliente.
- Salida HTML escapada con `htmlspecialchars()` y datos de API con función `esc()` en cliente.

---

## Estado del proyecto

- [x] Autenticación y gestión de sesiones
- [x] API REST completa (usuarios, reciclaje, centros, ranking)
- [x] Vistas funcionales con carga dinámica
- [x] Panel de administración básico
- [x] Sistema de recompensas e insignias
- [ ] Tests automatizados
- [ ] Despliegue en producción

---

## Autor

**Adrián Sempere Serrano** — [GitHub](https://github.com/adriisempere)
