-- ====================================================
--  BASE DE DATOS: GREENPOINTS
-- ====================================================

-- Borrar tablas si existen (para reiniciar)
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
-- ====================================================
CREATE TABLE usuario (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(150) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  rol ENUM('usuario','admin') NOT NULL DEFAULT 'usuario',
  puntos_totales INT NOT NULL DEFAULT 0,
  foto VARCHAR(255) DEFAULT NULL,
  creado_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ====================================================
--  TABLA ADMIN (Hereda de usuario)
-- ====================================================
CREATE TABLE admin (
  id INT PRIMARY KEY,
  FOREIGN KEY (id) REFERENCES usuario(id) ON DELETE CASCADE
);

-- ====================================================
--  TABLA CENTRO DE RECICLAJE
-- ====================================================
CREATE TABLE centro_reciclaje (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(200) NOT NULL,
  direccion TEXT NOT NULL,
  tipos_residuos VARCHAR(255) NOT NULL,
  horario VARCHAR(100) NOT NULL,
  creado_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ====================================================
--  TABLA REGISTRO DE RECICLAJE
-- ====================================================
CREATE TABLE registro_reciclaje (
  id INT AUTO_INCREMENT PRIMARY KEY,
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
-- ====================================================
CREATE TABLE ranking (
  id INT AUTO_INCREMENT PRIMARY KEY,
  fecha DATE NOT NULL,
  descripcion VARCHAR(255),
  creado_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ====================================================
--  TABLA DETALLE_RANKING
-- ====================================================
CREATE TABLE detalle_ranking (
  id INT AUTO_INCREMENT PRIMARY KEY,
  ranking_id INT NOT NULL,
  usuario_id INT NOT NULL,
  posicion INT NOT NULL,
  puntos INT NOT NULL,
  FOREIGN KEY (ranking_id) REFERENCES ranking(id) ON DELETE CASCADE,
  FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON DELETE CASCADE
);

-- ====================================================
--  INSERTS DE EJEMPLO (Opcionales)
-- ====================================================

-- Usuario administrador
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
