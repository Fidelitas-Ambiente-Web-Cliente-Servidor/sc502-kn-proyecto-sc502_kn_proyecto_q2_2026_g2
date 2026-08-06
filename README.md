# CarpoolMatch CR

CarpoolMatch CR es una aplicación web desarrollada como proyecto final para el curso SC-502 Ambiente Web Cliente/Servidor.

El sistema permite conectar usuarios que desean compartir viajes en rutas similares. La aplicación permite registrar usuarios, iniciar sesión, publicar viajes, solicitar ride, aprobar o rechazar solicitudes, consultar historial, calificar usuarios y actualizar el perfil.

## Tecnologías utilizadas

- HTML5
- CSS3
- JavaScript
- PHP
- MySQL
- XAMPP
- Git y GitHub

## Módulos principales

### Usuarios

Permite registrar usuarios, iniciar sesión, cerrar sesión y actualizar la información del perfil.

Tipos de usuario disponibles:

- Conductor
- Pasajero
- Ambos

### Viajes

Permite a los conductores publicar rutas con punto de salida, destino, fecha, hora, cantidad de asientos y observaciones.

Los pasajeros pueden buscar viajes disponibles y solicitar un espacio.

### Solicitudes

Los pasajeros pueden enviar solicitudes de ride.

Los conductores pueden revisar las solicitudes recibidas y aprobarlas o rechazarlas.

### Historial

Permite consultar la actividad del usuario según su tipo:

- Como pasajero: solicitudes realizadas.
- Como conductor: viajes publicados.
- Como ambos: ambas actividades.

### Calificaciones

Permite calificar usuarios después de compartir un viaje aprobado.

El sistema registra puntaje, comentario y actualiza la reputación del usuario evaluado.

## Base de datos

La base de datos se llama:

```sql
carpoolmatch