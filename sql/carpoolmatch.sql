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

INSERT INTO usuarios (id_usuario, nombre, correo, telefono, tipo_usuario, contrasena, reputacion, estado)
VALUES
(1, 'Carlos Ramirez', 'carlos@correo.com', '88887777', 'Conductor', '$2y$10$/tphkXvieqDb9T7nGCu45e3gO63hCBbKJ3Xc/8ocD8mZCnwDxKU5.', 5.00, 'Activo'),
(2, 'Maria Lopez', 'maria@correo.com', '77778888', 'Pasajero', '$2y$10$/tphkXvieqDb9T7nGCu45e3gO63hCBbKJ3Xc/8ocD8mZCnwDxKU5.', 5.00, 'Activo'),
(3, 'Andros Rodriguez', 'andros@correo.com', '88889999', 'Ambos', '$2y$10$/tphkXvieqDb9T7nGCu45e3gO63hCBbKJ3Xc/8ocD8mZCnwDxKU5.', 5.00, 'Activo'),
(4, 'Laura Mendez', 'laura@correo.com', '60601212', 'Conductor', '$2y$10$/tphkXvieqDb9T7nGCu45e3gO63hCBbKJ3Xc/8ocD8mZCnwDxKU5.', 5.00, 'Activo'),
(5, 'Miguel Vargas', 'miguel@hotmail.com', '70704545', 'Pasajero', '$2y$10$/tphkXvieqDb9T7nGCu45e3gO63hCBbKJ3Xc/8ocD8mZCnwDxKU5.', 5.00, 'Activo');

INSERT INTO viajes (id_viaje, id_conductor, punto_salida, destino, fecha_hora, asientos_disponibles, observaciones, estado)
VALUES
(1, 1, 'Heredia Centro', 'Universidad Fidélitas', '2026-08-05 07:00:00', 3, 'Salida puntual desde el parque central.', 'Activo'),
(2, 1, 'Alajuela Centro', 'San José', '2026-08-05 06:30:00', 2, 'Ruta por la General Cañas.', 'Activo'),
(3, 3, 'San Pedro', 'Heredia', '2026-08-05 17:00:00', 4, 'Regreso en la tarde.', 'Activo'),
(4, 4, 'Cartago Centro', 'San Pedro', '2026-08-06 06:45:00', 3, 'Punto de salida frente a la parada principal.', 'Activo'),
(5, 4, 'Tres Ríos', 'Universidad Fidélitas', '2026-08-06 07:15:00', 2, 'Se permite llevar bolso pequeño.', 'Activo');

INSERT INTO solicitudes (id_solicitud, id_viaje, id_pasajero, estado_solicitud)
VALUES
(1, 1, 2, 'Pendiente'),
(2, 2, 3, 'Aprobada'),
(3, 4, 5, 'Pendiente'),
(4, 5, 5, 'Aprobada');

INSERT INTO calificaciones (id_calificacion, id_evaluador, id_evaluado, id_viaje, puntaje, comentario)
VALUES
(1, 2, 1, 1, 5, 'Buen conductor y viaje puntual.'),
(2, 5, 4, 5, 5, 'Excelente viaje y buena comunicación.');