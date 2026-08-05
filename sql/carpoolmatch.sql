CREATE DATABASE IF NOT EXISTS carpoolmatch;
USE carpoolmatch;

DROP TABLE IF EXISTS calificaciones;
DROP TABLE IF EXISTS solicitudes;
DROP TABLE IF EXISTS viajes;
DROP TABLE IF EXISTS usuarios;

CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(120) NOT NULL UNIQUE,
    telefono VARCHAR(8) NOT NULL,
    tipo_usuario ENUM('Conductor', 'Pasajero', 'Ambos') NOT NULL,
    contrasena VARCHAR(255) NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    reputacion DECIMAL(3,2) DEFAULT 5.00,
    estado ENUM('Activo', 'Inactivo') DEFAULT 'Activo'
);

CREATE TABLE viajes (
    id_viaje INT AUTO_INCREMENT PRIMARY KEY,
    id_conductor INT NOT NULL,
    punto_salida VARCHAR(150) NOT NULL,
    destino VARCHAR(150) NOT NULL,
    fecha_hora DATETIME NOT NULL,
    asientos_disponibles INT NOT NULL,
    observaciones TEXT,
    estado ENUM('Activo', 'Completo', 'Cancelado') DEFAULT 'Activo',
    fecha_publicacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_conductor) REFERENCES usuarios(id_usuario)
);

CREATE TABLE solicitudes (
    id_solicitud INT AUTO_INCREMENT PRIMARY KEY,
    id_viaje INT NOT NULL,
    id_pasajero INT NOT NULL,
    estado_solicitud ENUM('Pendiente', 'Aprobada', 'Rechazada') DEFAULT 'Pendiente',
    fecha_solicitud TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_viaje) REFERENCES viajes(id_viaje),
    FOREIGN KEY (id_pasajero) REFERENCES usuarios(id_usuario)
);

CREATE TABLE calificaciones (
    id_calificacion INT AUTO_INCREMENT PRIMARY KEY,
    id_evaluador INT NOT NULL,
    id_evaluado INT NOT NULL,
    id_viaje INT NOT NULL,
    puntaje INT NOT NULL,
    comentario TEXT,
    fecha_calificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_evaluador) REFERENCES usuarios(id_usuario),
    FOREIGN KEY (id_evaluado) REFERENCES usuarios(id_usuario),
    FOREIGN KEY (id_viaje) REFERENCES viajes(id_viaje),
    CHECK (puntaje BETWEEN 1 AND 5)
);

INSERT INTO usuarios (nombre, correo, telefono, tipo_usuario, contrasena)
VALUES
('Carlos Ramirez', 'carlos@correo.com', '88887777', 'Conductor', '123456'),
('Maria Lopez', 'maria@correo.com', '77778888', 'Pasajero', '123456'),
('Andros Rodriguez', 'andros@correo.com', '88889999', 'Ambos', '123456');

INSERT INTO viajes (id_conductor, punto_salida, destino, fecha_hora, asientos_disponibles, observaciones)
VALUES
(1, 'Heredia Centro', 'Universidad Fidélitas', '2026-08-05 07:00:00', 3, 'Salida puntual desde el parque central.'),
(1, 'Alajuela Centro', 'San José', '2026-08-05 06:30:00', 2, 'Ruta por la General Cañas.'),
(3, 'San Pedro', 'Heredia', '2026-08-05 17:00:00', 4, 'Regreso en la tarde.');

INSERT INTO solicitudes (id_viaje, id_pasajero, estado_solicitud)
VALUES
(1, 2, 'Pendiente'),
(2, 3, 'Aprobada');

INSERT INTO calificaciones (id_evaluador, id_evaluado, id_viaje, puntaje, comentario)
VALUES
(2, 1, 1, 5, 'Buen conductor y viaje puntual.');