-- ====================================================
--  BASE DE DATOS: GREENPOINTS
--  Esquema completo con tablas, relaciones y datos de ejemplo
-- ====================================================

-- Desactivar restricciones de clave foránea para poder borrar tablas en orden
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS detalle_ranking;
DROP TABLE IF EXISTS ranking;
DROP TABLE IF EXISTS registro_reciclaje;
DROP TABLE IF EXISTS admin;
DROP TABLE IF EXISTS centro_reciclaje;
DROP TABLE IF EXISTS usuario;
SET FOREIGN_KEY_CHECKS = 1;

-- ====================================================
--  TABLA USUARIO
--  Almacena todos los usuarios registrados en la plataforma.
--  La contraseña se guarda con hash bcrypt (60+ caracteres).
--  puntos_totales se actualiza con cada registro de reciclaje.
-- ====================================================
CREATE TABLE usuario (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(150) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  rol ENUM('usuario','admin') NOT NULL DEFAULT 'usuario',
  puntos_totales INT NOT NULL DEFAULT 0,
  foto VARCHAR(255) DEFAULT NULL,
  creado_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ====================================================
--  TABLA ADMIN
--  Tabla hija de usuario para administradores.
--  Usa la misma PK que usuario con ON DELETE CASCADE.
-- ====================================================
CREATE TABLE admin (
  id INT PRIMARY KEY,
  FOREIGN KEY (id) REFERENCES usuario(id) ON DELETE CASCADE
);

-- ====================================================
--  TABLA CENTRO DE RECICLAJE
--  Puntos físicos donde los usuarios pueden depositar residuos.
--  Cada centro especifica qué materiales acepta y su horario.
-- ====================================================
CREATE TABLE centro_reciclaje (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(200) NOT NULL,
  direccion TEXT NOT NULL,
  tipos_residuos VARCHAR(255) NOT NULL,
  horario VARCHAR(100) NOT NULL,
  creado_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ====================================================
--  TABLA REGISTRO DE RECICLAJE
--  Cada fila representa una acción de reciclaje de un usuario.
--  centro_id puede ser NULL si el reciclaje no fue presencial.
--  puntos_ganados se calcula según tipo_material × cantidad.
-- ====================================================
CREATE TABLE registro_reciclaje (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT NOT NULL,
  centro_id INT NULL,
  fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  tipo_material VARCHAR(50) NOT NULL,
  cantidad FLOAT NOT NULL,
  puntos_ganados INT NOT NULL,
  FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON DELETE CASCADE,
  FOREIGN KEY (centro_id) REFERENCES centro_reciclaje(id) ON DELETE SET NULL
);


-- ====================================================
--  TABLA RANKING
--  Instantáneas históricas del ranking en una fecha concreta.
-- ====================================================
CREATE TABLE ranking (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  fecha DATE NOT NULL,
  descripcion VARCHAR(255),
  creado_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ====================================================
--  TABLA DETALLE_RANKING
--  Relaciona cada instantánea de ranking con los usuarios
--  y su posición en ese momento.
-- ====================================================
CREATE TABLE detalle_ranking (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  ranking_id INT NOT NULL,
  usuario_id INT NOT NULL,
  posicion INT NOT NULL,
  puntos INT NOT NULL,
  FOREIGN KEY (ranking_id) REFERENCES ranking(id) ON DELETE CASCADE,
  FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON DELETE CASCADE
);

-- ====================================================
--  INSERTS DE EJEMPLO (Opcionales)
--  Datos iniciales para desarrollo y pruebas.
-- ====================================================

-- Usuario administrador por defecto
INSERT INTO usuario (nombre, email, password, rol, puntos_totales)
VALUES ('Administrador', 'admin@greenpoints.com', 
        '$2y$10$QDwN04j/Nb6XWxjZzUp3EeLwBvT3SO9zebP6LUBdJhFePbNQZXF6i', -- contraseña: admin123
        'admin', 0);

INSERT INTO admin (id)
SELECT id FROM usuario WHERE email = 'admin@greenpoints.com';

-- Centros de reciclaje iniciales
INSERT INTO centro_reciclaje (nombre, direccion, tipos_residuos, horario)
VALUES
('Centro EcoVida', 'Calle Río Verde 12', 'Plástico, Papel, Vidrio', 'L-V 09:00-18:00'),
('Punto Limpio Norte', 'Av. Montaña 208', 'Plástico, Metal', 'L-S 10:00-14:00'),
('EcoCentro Sur', 'Camino del Valle 44', 'Vidrio, Papel', 'L-V 08:00-20:00');

-- ====================================================
--  TABLAS DE RECOMPENSAS — GreenPoints
--  Sistema de tarjetas regalo canjeables con puntos.
-- ====================================================

-- Catálogo de tarjetas regalo disponibles para canje
CREATE TABLE recompensa (
  id           INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  nombre       VARCHAR(150)  NOT NULL,
  marca        VARCHAR(100)  NOT NULL,
  puntos_coste INT           NOT NULL,
  descripcion  VARCHAR(255)  DEFAULT NULL,
  imagen_url   VARCHAR(500)  DEFAULT NULL,
  activa       TINYINT(1)    NOT NULL DEFAULT 1,
  creado_at    TIMESTAMP     DEFAULT CURRENT_TIMESTAMP
);

-- Historial de canjes: un registro por tarjeta regalo generada
-- El código es ficticio con formato GP-XXXX-XXXX-XXXX y se genera en el servidor
CREATE TABLE canje (
  id            INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  usuario_id    INT          NOT NULL,
  recompensa_id INT          NOT NULL,
  puntos_gastados INT        NOT NULL,
  codigo        VARCHAR(50)  NOT NULL,
  canjeado_at   TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (usuario_id)    REFERENCES usuario(id)     ON DELETE CASCADE,
  FOREIGN KEY (recompensa_id) REFERENCES recompensa(id)  ON DELETE CASCADE
);

-- ====================================================
--  CATÁLOGO INICIAL DE RECOMPENSAS
--  Tarjetas de ejemplo para demostración del sistema.
-- ====================================================
INSERT INTO recompensa (nombre, marca, puntos_coste, descripcion, imagen_url) VALUES
('Tarjeta Regalo 10€',  'Amazon',  500,  'Vale de 10€ para Amazon.es',          'https://upload.wikimedia.org/wikipedia/commons/a/a9/Amazon_logo.svg'),
('Tarjeta Regalo 15€',  'Amazon',  750,  'Vale de 15€ para Amazon.es',          'https://upload.wikimedia.org/wikipedia/commons/a/a9/Amazon_logo.svg'),
('Tarjeta Regalo 10€',  'Steam',   500,  'Saldo de 10€ para tu cuenta Steam',   'https://upload.wikimedia.org/wikipedia/commons/8/83/Steam_icon_logo.svg'),
('Tarjeta Regalo 20€',  'Steam',   950,  'Saldo de 20€ para tu cuenta Steam',   'https://upload.wikimedia.org/wikipedia/commons/8/83/Steam_icon_logo.svg'),
('Tarjeta Regalo 10€',  'Netflix', 600,  'Un mes de Netflix estándar',          'https://upload.wikimedia.org/wikipedia/commons/0/08/Netflix_2015_logo.svg'),
('Tarjeta Regalo 10€',  'Apple',   600,  'Saldo de 10€ para App Store / iTunes','https://upload.wikimedia.org/wikipedia/commons/f/fa/Apple_logo_black.svg'),
('Tarjeta Regalo 25€',  'Apple',   1200, 'Saldo de 25€ para App Store / iTunes','https://upload.wikimedia.org/wikipedia/commons/f/fa/Apple_logo_black.svg'),
('Tarjeta Regalo 10€',  'Spotify', 500,  'Un mes de Spotify Premium',           'https://upload.wikimedia.org/wikipedia/commons/1/19/Spotify_logo_without_text.svg');