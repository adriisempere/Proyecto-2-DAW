# 📦 Guía de Instalación - GreenPoints

Esta guía te ayudará a configurar el proyecto **GreenPoints** en tu entorno local de desarrollo.

---

## 📋 Requisitos Previos

Antes de comenzar, asegúrate de tener instalado:

- **PHP** >= 8.0 (Recomendado 8.2+)
- **MySQL** >= 5.7 o **MariaDB** >= 10.4
- **Servidor Web**: Apache (mediante XAMPP/WAMP) o el servidor integrado de PHP.
- **Git**: Para clonar el repositorio.
- **Composer** (opcional): Para futuras dependencias.

---

## 🚀 Instalación Paso a Paso

### 1. Obtener el Código
Clona el repositorio en tu carpeta de servidor local (`htdocs`, `www`, etc.):
```bash
git clone https://github.com/tu-usuario/Proyecto-2-DAW.git
cd Proyecto-2-DAW
```

### 2. Configurar la Base de Datos
1.  **Crear la DB**: Accede a tu gestor (phpMyAdmin o terminal) y crea una base de datos llamada `greenpoints`.
    ```sql
    CREATE DATABASE greenpoints CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
    ```
2.  **Importar Esquema**: Importa el archivo contenido en `sql/greenpoints.sql`.
    ```bash
    mysql -u root -p greenpoints < sql/greenpoints.sql
    ```

### 3. Configurar Credenciales
Edita el archivo `config/database.php`. El sistema buscará variables de entorno primero, pero puedes editarlas directamente para desarrollo local:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'tu_usuario'); // Por defecto 'root' en XAMPP
define('DB_PASS', 'tu_pass');    // Por defecto '' en XAMPP
define('DB_NAME', 'greenpoints');
```

---

## 💻 Ejecución

### Opción A: Servidor Integrado de PHP (Rápido)
Desde la raíz del proyecto, ejecuta:
```bash
cd public
php -S localhost:8000
```
Luego accede a `http://localhost:8000`.

### Opción B: XAMPP / WAMP
1. Asegúrate de que la carpeta del proyecto esté dentro de `htdocs`.
2. Inicia Apache y MySQL.
3. Accede a `http://localhost/Proyecto-2-DAW/public/`.

---

## 🔑 Credenciales de Acceso
Para probar las funcionalidades de administrador, utiliza:
- **Usuario**: `admin@greenpoints.com`
- **Contraseña**: `admin123`

---

## 🛠️ Solución de Problemas Comunes

- **Error de Conexión**: Revisa que los datos en `config/database.php` coincidan con tu configuración de MySQL.
- **Iconos no cargan**: Asegúrate de tener conexión a internet (los iconos de Bootstrap Icons se cargan vía CDN).
- **Charset/Acentos**: El sistema usa `utf8mb4`. Si ves caracteres extraños, verifica que tu base de datos y tablas usen este cotejamiento.

---

<p align="center">
  <strong>🌱 ¡Gracias por contribuir a un mundo más verde! 🌱</strong>
</p>
