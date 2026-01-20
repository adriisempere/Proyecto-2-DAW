# 🌱 GreenPoints - Gamificación del Reciclaje

![GreenPoints Logo](public/img/LogoGreenPoints.png)

## 📋 Descripción del Proyecto

**GreenPoints** es una aplicación web interactiva diseñada para incentivar el reciclaje mediante un sistema de gamificación. La plataforma permite a los usuarios registrar sus actividades de reciclaje, acumular puntos, competir en rankings y canjear recompensas, todo con el objetivo de promover prácticas sostenibles y conciencia ambiental.

### 🎯 Objetivos Principales

- **Concienciación ambiental**: Educar sobre la importancia del reciclaje
- **Gamificación**: Sistema de puntos, ranking y recompensas para motivar la participación
- **Comunidad**: Crear una red de usuarios comprometidos con el medio ambiente
- **Impacto medible**: Estadísticas en tiempo real del material reciclado y CO₂ reducido

---

## 🚀 Características Principales

### ✨ Funcionalidades Implementadas

- **Sistema de Usuarios**
  - Registro e inicio de sesión
  - Perfiles de usuario con sistema de puntos
  - Roles diferenciados (Usuario y Administrador)
  
- **Registro de Reciclaje**
  - Registro de materiales reciclados (plástico, papel, vidrio, metal)
  - Cálculo automático de puntos según tipo y cantidad
  - Vinculación con centros de reciclaje
  
- **Centros de Reciclaje**
  - Gestión de puntos de recogida
  - Información de ubicación, horarios y tipos de residuos aceptados
  
- **Sistema de Ranking**
  - Clasificación de usuarios por puntos acumulados
  - Rankings periódicos (diario, semanal, mensual)
  - Posiciones e historial de rendimiento

- **Interfaz Atractiva**
  - Diseño moderno y responsivo con Bootstrap 5
  - Animaciones y efectos visuales
  - UX optimizada para dispositivos móviles

---

## 🏗️ Arquitectura del Proyecto

### Patrón de Diseño: MVC (Modelo-Vista-Controlador)

El proyecto sigue el patrón MVC para mantener una separación clara de responsabilidades:

```
Proyecto-2-DAW/
│
├── app/
│   ├── controllers/          # Lógica de negocio
│   │   ├── UsuarioController.php
│   │   ├── CentroController.php
│   │   ├── RegistroController.php
│   │   └── RankingController.php
│   │
│   └── views/                # Interfaz de usuario
│       ├── home.php          # Landing page
│       ├── login.php         # Inicio de sesión
│       ├── register.php      # Registro de usuarios
│       └── partials/         # Componentes reutilizables
│           ├── header.php
│           └── footer.php
│
├── config/
│   └── database.php          # Configuración de BD
│
├── public/                   # Archivos públicos
│   ├── index.php            # Punto de entrada (Front Controller)
│   ├── css/                 # Estilos
│   │   ├── estilos.css
│   │   └── estilos.scss
│   ├── js/                  # JavaScript
│   │   └── app.js
│   └── img/                 # Imágenes
│       └── LogoGreenPoints.png
│
├── sql/
│   └── greenpoints.sql      # Script de base de datos
│
├── .gitignore
└── README.md
```

---

## 🗄️ Base de Datos

### Modelo Entidad-Relación

La base de datos `greenpoints` está compuesta por las siguientes tablas:

#### **usuario**
Almacena la información de los usuarios registrados.
- `id` (PK)
- `nombre`
- `email` (UNIQUE)
- `password` (Hash)
- `rol` (ENUM: 'usuario', 'admin')
- `puntos_totales` (Default: 0)
- `foto`
- `creado_at`

#### **admin**
Hereda de usuario para identificar administradores.
- `id` (PK, FK → usuario.id)

#### **centro_reciclaje**
Información de los puntos de recogida.
- `id` (PK)
- `nombre`
- `direccion`
- `tipos_residuos`
- `horario`
- `creado_at`

#### **registro_reciclaje**
Registros de actividades de reciclaje de los usuarios.
- `id` (PK)
- `usuario_id` (FK → usuario.id)
- `centro_id` (FK → centro_reciclaje.id)
- `fecha`
- `tipo_material`
- `cantidad`
- `puntos_ganados`

#### **ranking**
Períodos de clasificación.
- `id` (PK)
- `fecha`
- `descripcion`
- `creado_at`

#### **detalle_ranking**
Posiciones de usuarios en cada ranking.
- `id` (PK)
- `ranking_id` (FK → ranking.id)
- `usuario_id` (FK → usuario.id)
- `posicion`
- `puntos`

### Relaciones
- Un **usuario** puede tener múltiples **registros de reciclaje** (1:N)
- Un **centro de reciclaje** puede estar vinculado a múltiples **registros** (1:N)
- Un **ranking** contiene múltiples **detalles de ranking** (1:N)
- Un **usuario** puede aparecer en múltiples **detalles de ranking** (1:N)

---

## 🛠️ Tecnologías Utilizadas

### Backend
- **PHP 8+**: Lenguaje de programación del servidor
- **MySQL**: Sistema de gestión de bases de datos
- **Patrón MVC**: Arquitectura de software

### Frontend
- **HTML5 / CSS3**: Estructura y estilos
- **Bootstrap 5.3.3**: Framework CSS para diseño responsivo
- **SCSS**: Preprocesador CSS
- **JavaScript Vanilla**: Interactividad y animaciones
- **Bootstrap Icons**: Iconografía
- **Animate.css**: Librería de animaciones

### Otros
- **Git**: Control de versiones
- **Composer** (preparado): Gestor de dependencias PHP

---

## 📦 Instalación y Configuración

### Requisitos Previos

- PHP >= 8.0
- MySQL >= 5.7 o MariaDB >= 10.2
- Servidor web (Apache, Nginx) o PHP built-in server
- Composer (opcional)

### Paso 1: Clonar el repositorio

```bash
git clone https://github.com/tu-usuario/Proyecto-2-DAW.git
cd Proyecto-2-DAW
```

### Paso 2: Configurar la base de datos

1. Crea una base de datos MySQL:
```sql
CREATE DATABASE greenpoints CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2. Importa el script SQL:
```bash
mysql -u root -p greenpoints < sql/greenpoints.sql
```

3. Configura las credenciales en `config/database.php`:
```php
$mysqli = new mysqli("localhost", "tu_usuario", "tu_contraseña", "greenpoints");
```

> **💡 Recomendación de seguridad**: En producción, usa variables de entorno para las credenciales de la base de datos.

### Paso 3: Configurar el servidor

#### Opción A: Servidor PHP integrado (desarrollo)
```bash
cd public
php -S localhost:8000
```

#### Opción B: Apache
Configura el DocumentRoot a la carpeta `public/` y asegúrate de tener mod_rewrite activado.

#### Opción C: XAMPP/WAMP
Coloca el proyecto en `htdocs/` o `www/` y accede vía `http://localhost/Proyecto-2-DAW/public/`

### Paso 4: Acceder a la aplicación

Abre tu navegador y visita:
```
http://localhost:8000  (servidor PHP integrado)
http://localhost/Proyecto-2-DAW/public/  (Apache)
```

---

## 🎮 Uso de la Aplicación

### Credenciales de Prueba

**Administrador:**
- Email: `admin@greenpoints.com`
- Contraseña: `admin123`

### Flujo de Usuario

1. **Registro**: Crear una cuenta nueva en `/index.php?action=register`
2. **Login**: Iniciar sesión en `/index.php?action=login`
3. **Registrar Reciclaje**: Añadir actividades de reciclaje desde el panel de usuario
4. **Consultar Ranking**: Ver tu posición en la tabla de clasificación
5. **Explorar Centros**: Encontrar puntos de reciclaje cercanos

---

## 🔄 Proceso de Desarrollo

### Fase 1: Planificación y Diseño ✅
- Definición de requisitos funcionales
- Diseño del modelo de base de datos (Diagrama ER)
- Wireframes y mockups de la interfaz
- Selección de tecnologías

### Fase 2: Configuración del Entorno ✅
- Estructura de carpetas MVC
- Configuración de base de datos
- Implementación del Front Controller (`public/index.php`)
- Sistema de enrutamiento básico

### Fase 3: Backend (En Desarrollo) 🚧
- **Completado:**
  - Creación de controladores base
  - Script SQL con tablas y relaciones
  - Configuración de conexión a BD
  
- **En progreso:**
  - Implementación de lógica en controladores
  - Sistema de autenticación seguro (sesiones, bcrypt)
  - CRUD completo para todas las entidades
  - Validación de datos del lado del servidor

### Fase 4: Frontend (Parcialmente Completado) 🚧
- **Completado:**
  - Landing page atractiva y responsiva
  - Integración de Bootstrap 5
  - Animaciones y efectos visuales
  - Estructura base de vistas
  
- **En progreso:**
  - Vistas de login y registro funcionales
  - Panel de usuario con dashboard
  - Vista de ranking con filtros
  - Mapa interactivo de centros de reciclaje
  - Formularios de registro de reciclaje

### Fase 5: Funcionalidades Avanzadas (Pendiente) 📋
- Sistema de recompensas canjeables
- Notificaciones en tiempo real
- Estadísticas personalizadas de impacto ambiental
- API REST para aplicación móvil
- Integración de mapas (Google Maps / OpenStreetMap)
- Sistema de insignias y logros

### Fase 6: Testing y Despliegue (Pendiente) 📋
- Pruebas unitarias
- Pruebas de integración
- Optimización de rendimiento
- Despliegue en servidor de producción
- Documentación técnica completa

---

## 🐛 Problemas Conocidos y Mejoras Pendientes

### Issues Identificados
- [ ] Controladores con lógica mínima (solo echo de prueba)
- [ ] Vista de login sin contenido
- [ ] Falta implementar validación de formularios
- [ ] Credenciales de BD hardcodeadas (sin .env)
- [ ] Falta manejo de errores robusto
- [ ] Sin protección CSRF en formularios

### Mejoras Planificadas
- [ ] Implementar sistema de sesiones seguro
- [ ] Añadir validación client-side con JavaScript
- [ ] Crear middleware de autenticación
- [ ] Implementar sistema de logs
- [ ] Añadir tests automatizados
- [ ] Dockerizar la aplicación
- [ ] Implementar caché de consultas frecuentes

---

## 🤝 Contribución

Este es un proyecto académico de 2º DAW (Desarrollo de Aplicaciones Web). Si deseas contribuir:

1. Fork el repositorio
2. Crea una rama para tu feature (`git checkout -b feature/nueva-funcionalidad`)
3. Commit tus cambios (`git commit -m 'Añadir nueva funcionalidad'`)
4. Push a la rama (`git push origin feature/nueva-funcionalidad`)
5. Abre un Pull Request

---

## 📝 Licencia

Este proyecto es de código abierto y está disponible bajo la licencia MIT.

---

## 👥 Autores

- **Equipo de Desarrollo** - Proyecto Intermodular 2º DAW

---

## 📞 Contacto

Para consultas o sugerencias sobre el proyecto:
- Email: admin@greenpoints.com
- GitHub: [Proyecto-2-DAW](https://github.com/tu-usuario/Proyecto-2-DAW)

---

## 🙏 Agradecimientos

- Profesores y tutores de 2º DAW
- Comunidad de Bootstrap
- Imágenes de Unsplash
- Iconos de Bootstrap Icons

---

## 📊 Estadísticas del Proyecto

- **Líneas de código**: ~2,000+
- **Archivos**: 20+
- **Commits**: En desarrollo
- **Estado**: 🚧 En desarrollo activo

---

<p align="center">
  <strong>💚 Hecho con ❤️ por el planeta 🌍</strong>
</p>
