# Manual de Usuario — GreenPoints

## Sistema de Reciclaje Gamificado

---

# Índice

1. [Introducción](#1-introducción)
2. [Requisitos del Sistema](#2-requisitos-del-sistema)
3. [Acceso a la Aplicación](#3-acceso-a-la-aplicación)
4. [Roles de Usuario](#4-roles-de-usuario)
5. [Funcionalidades para Visitantes (Sin sesión)](#5-funcionalidades-para-visitantes-sin-sesión)
6. [Funcionalidades para Usuarios Registrados](#6-funcionalidades-para-usuarios-registrados)
7. [Funcionalidades para Administradores](#7-funcionalidades-para-administradores)
8. [Sistema de Puntos y Niveles](#8-sistema-de-puntos-y-niveles)
9. [Preguntas Frecuentes](#9-preguntas-frecuentes)
10. [Solución de Problemas](#10-solución-de-problemas)

---

# 1. Introducción

**GreenPoints** es una aplicación web diseñada para fomentar el reciclaje mediante un sistema gamificado de puntos y recompensas. Los usuarios registran sus actividades de reciclaje, acumulan puntos según el tipo y peso de los materiales depositados, compiten en un ranking global y canjean sus puntos por tarjetas regalo de marcas populares.

## Objetivo

Promover hábitos sostenibles premiando económicamente a los ciudadanos que más reciclan, creando una comunidad comprometida con el medio ambiente.

---

# 2. Requisitos del Sistema

## Navegadores Soportados

- Google Chrome 90+
- Mozilla Firefox 88+
- Microsoft Edge 90+
- Safari 14+
- Opera 76+

## Conexión

- Conexión a Internet estable
- JavaScript habilitado
- Cookies habilitadas (para sesiones)

## Dispositivos

- La aplicación es responsive y funciona en ordenadores, tablets y móviles.

---

# 3. Acceso a la Aplicación

## URL de Acceso

```
http://sempere-greenpoints.infinityfree.me/index.php?action=home
```

## Credenciales por Defecto

| Rol           | Email                 | Contraseña               |
| ------------- | --------------------- | ------------------------ |
| Administrador | admin@greenpoints.com | admin123                 |
| Usuario       | (registro propio)     | (elegida por el usuario) |

---

# 4. Roles de Usuario

GreenPoints cuenta con **tres niveles de acceso**:

| Rol               | Descripción                                                               |
| ----------------- | ------------------------------------------------------------------------- |
| **Visitante**     | Usuario no autenticado. Puede consultar información pública.              |
| **Usuario**       | Usuario registrado. Puede reciclar, ganar puntos, canjear recompensas.    |
| **Administrador** | Usuario con permisos avanzados. Gestiona centros de reciclaje y usuarios. |

---

# 5. Funcionalidades para Visitantes (Sin sesión)

## 5.1 Página de Inicio

- Visualización de estadísticas globales (puntos totales, kg reciclados, CO₂ ahorrado).
- Guía interactiva de 3 pasos para comenzar.
- Desglose de materiales reciclados con gráficos de impacto.

## 5.2 Ranking Global

- Visualización del top 100 de usuarios.
- Podio destacado con medallas para los 3 primeros puestos.
- Tabla completa con posición, nombre, puntos, kg reciclados y número de reciclajes.
- Actualización en tiempo real.

## 5.3 Centros de Reciclaje

- Listado completo de centros de reciclaje disponibles.
- Búsqueda en vivo por nombre, dirección o tipo de residuo.
- Información detallada: dirección, tipos de residuos aceptados, horario.

## 5.4 Registro de Nuevo Cuenta

- Formulario de registro con:
  - Nombre completo
  - Correo electrónico
  - Contraseña (con indicador de seguridad)
  - Aceptación de términos y condiciones
- Protección CSRF integrada.
- Validación en tiempo real.

## 5.5 Inicio de Sesión

- Formulario de acceso con email y contraseña.
- Opción de mostrar/ocultar contraseña.
- Protección contra enumeración de emails (mensaje genérico de error).

---

# 6. Funcionalidades para Usuarios Registrados

## 6.1 Panel de Perfil

### Acceso

`Perfil` en el menú de navegación (requiere sesión iniciada).

### Elementos del Perfil

- **Avatar**: Inicial del nombre mostrada en un círculo.
- **Nombre de usuario** y **rol**.
- **Puntos totales** acumulados.
- **Nivel** actual (Principiante, Avanzado, Experto, Maestro Verde).
- **Kilogramos totales** reciclados.
- **CO₂ ahorrado** estimado (1.5 kg CO₂ por kg reciclado).
- **Posición en el ranking** global.
- **Acciones rápidas**: botones para acceder a registrar reciclaje, historial, tienda y canjes.

### Editar Perfil

- Modificar nombre.
- Cambiar correo electrónico.
- Actualizar contraseña.
- Los cambios se guardan mediante ventana modal.

## 6.2 Registrar Reciclaje

### Acceso

`Registrar Reciclaje` desde el menú o perfil.

### Proceso

1. **Seleccionar material**: Interfaz visual con tarjetas para cada tipo:
   - Plástico
   - Papel
   - Vidrio
   - Metal
   - Orgánico
2. **Introducir cantidad**: Peso en kilogramos.
3. **Seleccionar centro** (opcional): Centro de reciclaje donde se depositó.
4. **Vista previa de puntos**: Cálculo en tiempo real de los puntos a ganar.
5. **Confirmar**: El registro se guarda y los puntos se añaden automáticamente.

### Tabla de Puntuación

| Material | Puntos por kg |
| -------- | ------------- |
| Metal    | 15            |
| Plástico | 10            |
| Vidrio   | 8             |
| Papel    | 5             |
| Orgánico | 3             |

## 6.3 Mis Registros (Historial)

### Acceso

`Mis Registros` desde el menú o perfil.

### Funcionalidades

- Visualización de todos los reciclajes realizados.
- **Resumen superior**: Total de registros, kg totales, puntos totales.
- **Tarjetas individuales** con:
  - Fecha del registro.
  - Material reciclado (con icono).
  - Cantidad en kg.
  - Puntos ganados.
  - Centro asociado (si se indicó).
- **Eliminar registro**: Botón con confirmación modal.
  - Al eliminar, los puntos se descuentan automáticamente (nunca por debajo de 0).

## 6.4 Tienda de Recompensas

### Acceso

`Tienda` desde el menú o perfil.

### Catálogo

- Tarjetas regalo de las siguientes marcas:
  - **Amazon** (10€ y 15€)
  - **Steam** (10€ y 20€)
  - **Netflix** (10€)
  - **Apple** (10€ y 25€)
  - **Spotify** (10€)
- Visualización con logotipo, nombre, marca y puntos necesarios.
- **Filtros por marca**: Botones para filtrar por marca específica.

### Proceso de Compra

1. Añadir recompensas al carrito.
2. Seleccionar cantidad deseada (máximo 10 unidades por producto).
3. El carrito se muestra como panel deslizante lateral.
4. Verificación del saldo disponible en tiempo real.
5. Finalizar compra con confirmación modal.
6. **Confirmación final**: Modal con los códigos generados para cada recompensa.

### Formato de Códigos

Los códigos de tarjeta regalo siguen el formato:

```
GP-XXXX-XXXX-XXXX
```

### Historial de Canjes

- Acceso desde `Mis Canjes` en el menú.
- Listado completo de todas las recompensas canjeadas.
- Códigos generados con botón "Copiar" para portapapeles.
- Fecha de canje y puntos gastados.

## 6.5 Ranking Personal

### Acceso

`Ranking` en el menú de navegación.

### Funcionalidades

- **Posición personal**: Banner destacado mostrando tu puesto si estás entre los top 100.
- **Podio**: Los 3 primeros con medallas (oro, plata, bronce).
- **Tabla completa**: Top 100 con columnas:
  - Posición.
  - Nombre del usuario.
  - Puntos totales.
  - Kilogramos reciclados.
  - Número de reciclajes.

## 6.6 Centros de Reciclaje

### Acceso

`Centros` en el menú de navegación.

### Funcionalidades

- Listado completo de centros disponibles.
- Barra de búsqueda en vivo (filtra por nombre, dirección o tipo de residuo).
- Información de cada centro: nombre, dirección, tipos de residuos y horario.

## 6.7 Cierre de Sesión

- Opción `Cerrar Sesión` en el menú de navegación.
- Destruye la sesión y redirige a la página principal.

---

# 7. Funcionalidades para Administradores

## 7.1 Identificación

- Los administradores ven la etiqueta **"Administrador"** en su perfil.
- Botón adicional **"Gestionar Centros"** en las acciones rápidas del perfil.

## 7.2 Gestión de Centros de Reciclaje

### Acceso

`Gestionar Centros` desde el perfil o botón "Nuevo Centro" en la página de centros.

### Crear Centro

1. Hacer clic en "Nuevo Centro".
2. Rellenar formulario modal:
   - Nombre del centro.
   - Dirección.
   - Tipos de residuos aceptados.
   - Horario.
3. Confirmar para guardar.

### Editar Centro

1. Hacer clic en el botón de edición en la tarjeta del centro.
2. Modificar los campos necesarios en la ventana modal.
3. Guardar cambios.

### Eliminar Centro

1. Hacer clic en el botón de eliminar en la tarjeta del centro.
2. Confirmar la eliminación.
3. Los registros de reciclaje asociados mantienen el centro como NULL.

## 7.3 Listado de Usuarios

- Los administradores pueden ver el listado completo de usuarios registrados.
- Acceso a través de la API de administración.

## 7.4 Acceso a Funciones de Usuario

Los administradores heredan todas las funcionalidades de los usuarios regulares:

- Registrar reciclaje.
- Ver historial.
- Canjear recompensas.
- Participar en el ranking.
- Editar perfil.

---

# 8. Sistema de Puntos y Niveles

## 8.1 Cálculo de Puntos

```
Puntos ganados = Cantidad (kg) × Puntos por kg del material
```

### Ejemplos

- 2 kg de Metal: 2 × 15 = **30 puntos**
- 5 kg de Papel: 5 × 5 = **25 puntos**
- 1 kg de Plástico + 3 kg de Vidrio: (1 × 10) + (3 × 8) = **34 puntos**

## 8.2 Niveles de Usuario

| Nivel         | Puntos Requeridos | Insignia |
| ------------- | ----------------- | -------- |
| Principiante  | 0 — 499           | 🌱       |
| Avanzado      | 500 — 1,999       | 🌿       |
| Experto       | 2,000 — 4,999     | 🌳       |
| Maestro Verde | 5,000+            | 🏆       |

## 8.3 Cálculo de CO₂ Ahorrado

```
CO₂ ahorrado (kg) = Cantidad reciclada (kg) × 1.5
```

## 8.4 Transacciones Atómicas

- Al registrar reciclaje: los puntos se añaden al instante.
- Al eliminar un registro: los puntos se descuentan automáticamente.
- Al canjear una recompensa: los puntos se descuentan y el código se genera.
- Todas las operaciones críticas usan transacciones atómicas para garantizar consistencia.

---

# 9. Preguntas Frecuentes

## ¿Puedo eliminar un registro de reciclaje?

Sí. Desde "Mis Registros" puedes eliminar cualquier registro. Los puntos se descontarán automáticamente.

## ¿Qué pasa si no tengo suficientes puntos para canjear?

La tienda mostrará el mensaje "No tienes suficientes puntos" y no permitirá completar la compra.

## ¿Puedo canjear varias recompensas a la vez?

Sí. Puedes añadir múltiples recompensas al carrito y canjearlas todas juntas.

## ¿Los administradores también pueden canjear recompensas?

Sí. Los administradores heredan todas las funcionalidades de los usuarios regulares.

## ¿Cómo recupero mi contraseña?

Actualmente la recuperación de contraseña debe hacerse contactando con un administrador.

## ¿Puedo cambiar mi correo electrónico?

Sí. Desde la edición de perfil puedes modificar tu correo electrónico.

## ¿Los datos se guardan de forma segura?

Sí. Las contraseñas se almacenan con bcrypt, las sesiones son seguras con HttpOnly y SameSite, y todas las operaciones usan prepared statements para prevenir inyección SQL.

---

# 10. Solución de Problemas

## No puedo iniciar sesión

- Verifica que el correo electrónico sea correcto.
- Verifica que la contraseña sea correcta.
- Asegúrate de que las cookies estén habilitadas en tu navegador.
- Si olvidaste la contraseña, contacta al administrador.

## No veo la opción de registrar reciclaje

- Asegúrate de haber iniciado sesión.
- Las opciones de usuario solo aparecen para usuarios autenticados.

## Los puntos no se actualizan

- Refresca la página.
- Cierra sesión y vuelve a iniciarla.
- Si el problema persiste, contacta al administrador.

## La página no carga correctamente

- Verifica tu conexión a Internet.
- Asegúrate de usar un navegador compatible.
- Borra la caché del navegador.
- Habilita JavaScript en tu navegador.

## No encuentro un centro de reciclaje

- Usa la barra de búsqueda en la página de centros.
- Prueba diferentes términos de búsqueda.
- Si no encuentras lo que buscas, contacta al administrador para que añada nuevos centros.

---

_Documento generado el 11 de mayo de 2026_

_GreenPoints — Recicla, acumula puntos y gana recompensas._
