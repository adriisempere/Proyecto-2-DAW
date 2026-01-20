# 📦 Guía de Instalación - GreenPoints

Esta guía te ayudará a configurar el proyecto GreenPoints en tu entorno local.

## 📋 Requisitos Previos

Antes de comenzar, asegúrate de tener instalado:

- **PHP** >= 8.0
  - Verifica con: `php -v`
- **MySQL** >= 5.7 o **MariaDB** >= 10.2
  - Verifica con: `mysql --version`
- **Servidor Web** (Apache/Nginx) o usar el servidor integrado de PHP
- **Composer** (opcional, para futuras dependencias)
- **Git** (para clonar el repositorio)

## 🚀 Instalación Paso a Paso

### 1. Clonar el Repositorio

```bash
git clone https://github.com/tu-usuario/Proyecto-2-DAW.git
cd Proyecto-2-DAW
```

### 2. Configurar la Base de Datos

#### a) Crear la base de datos

Accede a MySQL desde la terminal:

```bash
mysql -u root -p
```

Ejecuta los siguientes comandos SQL:

```sql
CREATE DATABASE greenpoints CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

#### b) Importar el esquema de la base de datos

```bash
mysql -u root -p greenpoints < sql/greenpoints.sql
```

Esto creará todas las tablas necesarias e insertará datos de prueba.

### 3. Configurar las Credenciales de Base de Datos

Edita el archivo `config/database.php` y ajusta las credenciales:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'tu_usuario');     // Cambia esto
define('DB_PASS', 'tu_contraseña');  // Cambia esto
define('DB_NAME', 'greenpoints');
```

> **💡 Alternativa con Variables de Entorno:**
> 
> Puedes configurar variables de entorno en tu sistema operativo:
> 
> **En Windows (PowerShell):**
> ```powershell
> $env:DB_USER="tu_usuario"
> $env:DB_PASS="tu_contraseña"
> ```
> 
> **En Linux/Mac:**
> ```bash
> export DB_USER="tu_usuario"
> export DB_PASS="tu_contraseña"
> ```

### 4. Iniciar el Servidor

Tienes varias opciones para ejecutar el proyecto:

#### Opción A: Servidor PHP Integrado (Recomendado para desarrollo)

```bash
cd public
php -S localhost:8000
```

Luego abre tu navegador en: `http://localhost:8000`

#### Opción B: XAMPP

1. Copia el proyecto a `C:\xampp\htdocs\Proyecto-2-DAW` (Windows)
2. Inicia Apache y MySQL desde el panel de control de XAMPP
3. Abre: `http://localhost/Proyecto-2-DAW/public/`

#### Opción C: WAMP

1. Copia el proyecto a `C:\wamp64\www\Proyecto-2-DAW` (Windows)
2. Inicia los servicios de WAMP
3. Abre: `http://localhost/Proyecto-2-DAW/public/`

#### Opción D: Apache Configurado

Crea un archivo de configuración de host virtual:

**Linux/Mac** (`/etc/apache2/sites-available/greenpoints.conf`):
```apache
<VirtualHost *:80>
    ServerName greenpoints.local
    DocumentRoot /ruta/al/proyecto/public
    
    <Directory /ruta/al/proyecto/public>
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/greenpoints-error.log
    CustomLog ${APACHE_LOG_DIR}/greenpoints-access.log combined
</VirtualHost>
```

Luego:
```bash
sudo a2ensite greenpoints.conf
sudo systemctl restart apache2
```

Añade a `/etc/hosts`:
```
127.0.0.1 greenpoints.local
```

Accede a: `http://greenpoints.local`

### 5. Verificar la Instalación

1. Accede a la aplicación en tu navegador
2. Deberías ver la página de inicio de GreenPoints
3. Prueba iniciar sesión con la cuenta de administrador:
   - **Email:** `admin@greenpoints.com`
   - **Contraseña:** `admin123`

## 🔧 Solución de Problemas

### Error de conexión a la base de datos

**Síntoma:** "Error de conexión a la base de datos"

**Solución:**
- Verifica que MySQL esté ejecutándose
- Confirma que las credenciales en `config/database.php` sean correctas
- Asegúrate de que la base de datos `greenpoints` exista

```bash
# Verificar servicios de MySQL
# En Linux:
sudo systemctl status mysql

# En Windows con XAMPP:
# Verifica el panel de control de XAMPP
```

### Página en blanco o errores 500

**Síntoma:** La página se muestra en blanco o error 500

**Solución:**
- Activa la visualización de errores de PHP temporalmente
- Edita `public/index.php` y añade al inicio:

```php
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

- Verifica los logs de errores de PHP y Apache

### Los estilos no se cargan

**Síntoma:** La página se ve sin estilos

**Solución:**
- Verifica que estés accediendo a través de la carpeta `public/`
- Comprueba la ruta de los archivos CSS en las vistas
- Asegúrate de que Bootstrap CDN esté accesible

### Error de permisos en Linux

**Síntoma:** Errores de escritura o permisos denegados

**Solución:**
```bash
# Dar permisos al servidor web
sudo chown -R www-data:www-data /ruta/al/proyecto
sudo chmod -R 755 /ruta/al/proyecto
```

## 📝 Configuración Adicional

### Habilitar Reescritura de URLs (Opcional)

Para URLs amigables sin `index.php?action=`, crea un archivo `.htaccess` en `public/`:

```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?action=$1 [QSA,L]
```

Asegúrate de que `mod_rewrite` esté habilitado:

```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

### Configurar Variables de Entorno con .env (Avanzado)

Para mayor seguridad, puedes usar variables de entorno:

1. Crea un archivo `.env` (nunca lo subas a Git):

```env
DB_HOST=localhost
DB_USER=root
DB_PASS=tu_contraseña
DB_NAME=greenpoints
```

2. Instala phpdotenv con Composer:

```bash
composer require vlucas/phpdotenv
```

3. Modifica `config/database.php` para cargar el archivo `.env`

## 🎉 ¡Listo!

Ahora deberías tener GreenPoints funcionando en tu entorno local. 

### Próximos Pasos

- Explora la aplicación
- Crea una cuenta de usuario
- Registra actividades de reciclaje
- Consulta el ranking
- Lee el `README.md` para entender la arquitectura del proyecto

## 🆘 ¿Necesitas Ayuda?

Si encuentras problemas durante la instalación:

1. Revisa esta guía completa nuevamente
2. Consulta el archivo `README.md`
3. Verifica los logs de errores
4. Contacta al equipo de desarrollo

---

**¡Feliz reciclaje! 🌱💚**
