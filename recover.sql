DELETE FROM recompensa;
ALTER TABLE recompensa AUTO_INCREMENT = 1;

INSERT INTO recompensa (nombre, marca, puntos_coste, descripcion, imagen_url) VALUES
('Tarjeta Regalo 10€',  'Amazon',  500,  'Vale de 10€ para Amazon.es',          'https://upload.wikimedia.org/wikipedia/commons/a/a9/Amazon_logo.svg'),
('Tarjeta Regalo 15€',  'Amazon',  750,  'Vale de 15€ para Amazon.es',          'https://upload.wikimedia.org/wikipedia/commons/a/a9/Amazon_logo.svg'),
('Tarjeta Regalo 10€',  'Steam',   500,  'Saldo de 10€ para tu cuenta Steam',   'https://upload.wikimedia.org/wikipedia/commons/8/83/Steam_icon_logo.svg'),
('Tarjeta Regalo 20€',  'Steam',   950,  'Saldo de 20€ para tu cuenta Steam',   'https://upload.wikimedia.org/wikipedia/commons/8/83/Steam_icon_logo.svg'),
('Tarjeta Regalo 10€',  'Netflix', 600,  'Un mes de Netflix estándar',          'https://upload.wikimedia.org/wikipedia/commons/0/08/Netflix_2015_logo.svg'),
('Tarjeta Regalo 10€',  'Apple',   600,  'Saldo de 10€ para App Store / iTunes','https://upload.wikimedia.org/wikipedia/commons/f/fa/Apple_logo_black.svg'),
('Tarjeta Regalo 25€',  'Apple',   1200, 'Saldo de 25€ para App Store / iTunes','https://upload.wikimedia.org/wikipedia/commons/f/fa/Apple_logo_black.svg'),
('Tarjeta Regalo 10€',  'Spotify', 500,  'Un mes de Spotify Premium',           'https://upload.wikimedia.org/wikipedia/commons/1/19/Spotify_logo_without_text.svg');
