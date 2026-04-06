# 📦 Guía de Instalación - GreenPoints

Esta guía te ayudará a configurar el proyecto **GreenPoints** en tu entorno local de desarrollo (por ejemplo: XAMPP, WAMP o nativo).

---

## 📋 Requisitos Previos

Asegúrate de tener instalado:
- **PHP** >= 8.0 (Recomendado 8.2+)
- **MySQL** >= 5.7 o **MariaDB** >= 10.4
- Servidor Web (**Apache** a través de XAMPP / MAMP u otro compatible)
- **Git**

---

## 🚀 Instalación Paso a Paso

### 1. Clonar el repositorio
Al clonar o descargar este proyecto, fíjate en su estructura. Si lo descargas directamente o utilizas git dentro de tu directorio público (`htdocs/` en XAMPP):
```bash
git clone https://github.com/tu-usuario/Proyecto-2-DAW.git
```
*(Nota de Archivos: Actualmente el proyecto está contenido dentro de una subcarpeta repetida, es decir `Proyecto-2-DAW-RamaAdrian/Proyecto-2-DAW-RamaAdrian`. Ten en cuenta esto al abrirlo en tu navegador).*

### 2. Configurar la Base de Datos y Codificación

1. **Crear la Base de Datos:** (Puedes usar phpMyAdmin)
   ```sql
   CREATE DATABASE greenpoints CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```
2. **Importar los Datos Iniciales y las Recompensas (MUY IMPORTANTE):**
   Para evitar que el terminal corrompa los símbolos como la 'Á' o el euro ('€') introduciendo caracteres extraños, al importar el archivo `.sql` debes forzar explícitamente el modo `utf8mb4`. En la consola `cmd` ejecuta:
   ```bash
   mysql --default-character-set=utf8mb4 -u root greenpoints < sql/greenpoints.sql
   ```
   *Esto desplegará las tablas base de usuario, registros y el catálogo con todas las tarjetas de recompensa ya configuradas y con su formato intacto.*

### 3. Configurar Credenciales Seguras y Rápidas
El puente a la base de datos se rige por el código en `config/database.php` (y/o `app/config/database.php`). Confirma que coinciden con tu XAMPP:
```php
define('DB_HOST', '127.0.0.1');  // ¡Usa siempre 127.0.0.1 en lugar de localhost para evitar la lentitud por IPv6 de Windows!
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'greenpoints');
```

---

## 💻 Ejecución del Proyecto

### Opción A: Mediante XAMPP (Apache)
Encendiendo los módulos de Apache y MySQL en el panel de control XAMPP, ingresa en el navegador (adecuando el nombre de tu carpeta):
```
http://localhost/Proyecto-2-DAW-RamaAdrian/Proyecto-2-DAW-RamaAdrian/public/
```

### Opción B: Servidor PHP Interno (Opcional)
Desde la terminal, accede directamente a la carpeta `public/` y ejecuta:
```bash
cd Proyecto-2-DAW-RamaAdrian/Proyecto-2-DAW-RamaAdrian/public
php -S 127.0.0.1:8000
```
Y visita en tu navegador la URL `http://127.0.0.1:8000`.

---

## 🔐 Cuentas de Acceso (Administración)

La base de datos original contiene un perfil administrador prediseñado para poder acceder a los ajustes de validación de los Centros y registros:
- **Email:** `admin@greenpoints.com`
- **Contraseña:** `admin123`

---

## 🛠️ Solución a Problemas Comunes

- **Pantalla Blanca (Error 500) o `"Error al cargar el catálogo"`**: Significa que falta la tabla `recompensa` en MySQL. Regresa al *Paso 2* y re-importa el archivo `greenpoints.sql` de la base de código.
- **Micro-Lentitud: Tarda 1 segundo en cargar cada vista**: Revisa que tus archivos `database.php` usan verdaderamente `define('DB_HOST', '127.0.0.1')` en vez de `"localhost"`. XAMPP a menudo enruta lento el fallback de DNS si se usa localmente por defecto.
- **Acentos cortados en nombres (ej. `lvaro`) o `10Ôé¼` en tienda**: Vuelve a volcar la base de datos pero recuerda añadir el flag explícito de `--default-character-set=utf8mb4` desde tu terminal nativa.
