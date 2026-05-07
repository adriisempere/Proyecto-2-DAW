# Guía de Instalación - GreenPoints

Guía para configurar GreenPoints en un entorno local.

---

## Requisitos

- PHP >= 8.0
- MySQL >= 5.7 o MariaDB >= 10.4
- Apache (XAMPP, WAMP, Laragon, etc.)
- Git

---

## Instalación

### 1. Clonar el repositorio

```bash
git clone https://github.com/tu-usuario/Proyecto-2-DAW.git
```

### 2. Crear la base de datos

```sql
CREATE DATABASE greenpoints CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 3. Importar las tablas y datos iniciales

```bash
mysql --default-character-set=utf8mb4 -u root greenpoints < sql/greenpoints.sql
```

Esto crea todas las tablas (usuarios, centros, reciclajes, recompensas, canjes, ranking) e inserta los datos de ejemplo: recompensas, centros y un administrador por defecto.

### 4. Configurar la conexión a la base de datos

El archivo `config/database.php` ya viene preparado para local y producción. Por defecto conecta a `localhost` con usuario `root` y sin contraseña. Si tu configuración local es distinta, edita las constantes:

```php
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'greenpoints');
```

---

## Ejecución

### Opción A: Apache (XAMPP)
Coloca el proyecto en `htdocs/` y accede a:

```
http://localhost/Proyecto-2-DAW/public/
```

### Opción B: Servidor PHP interno

```bash
cd Proyecto-2-DAW/public
php -S 127.0.0.1:8000
```

Y abre `http://127.0.0.1:8000` en el navegador.

---

## Cuenta de administrador

- **Email:** `admin@greenpoints.com`
- **Contraseña:** `admin123`

---

## Solución a problemas comunes

- **"Error al cargar el catálogo"** — Falta la tabla `recompensa`. Reimporta `sql/greenpoints.sql` o ejecuta `import_recompensas.sql`.
- **Acentos corruptos (ej. `10€` mostrado como `10â‚¬`)** — Al importar el SQL usa siempre `--default-character-set=utf8mb4`.
- **Cargos lentos** — Asegúrate de que `DB_HOST` es `127.0.0.1` y no `localhost` para evitar resolución DNS lenta en Windows.
- **Quieres reiniciar solo las recompensas** — Ejecuta `import_recompensas.sql` (borra y recrea las tablas `recompensa` y `canje` con los datos iniciales).
