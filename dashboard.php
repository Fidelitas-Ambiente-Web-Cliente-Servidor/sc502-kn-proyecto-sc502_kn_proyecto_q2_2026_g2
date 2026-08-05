<?php

session_start();
include "php/conexion.php";

if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.html");
    exit();
}

$idUsuario = $_SESSION["id_usuario"];
$nombreUsuario = $_SESSION["nombre"];
$tipoUsuario = $_SESSION["tipo_usuario"];

$consultaViajesDisponibles = "SELECT COUNT(*) AS total FROM viajes WHERE estado = 'Activo'";
$resultadoViajesDisponibles = mysqli_query($conexion, $consultaViajesDisponibles);
$viajesDisponibles = mysqli_fetch_assoc($resultadoViajesDisponibles)["total"];

$consultaSolicitudesPendientes = "SELECT COUNT(*) AS total 
                                  FROM solicitudes 
                                  WHERE estado_solicitud = 'Pendiente'";
$resultadoSolicitudesPendientes = mysqli_query($conexion, $consultaSolicitudesPendientes);
$solicitudesPendientes = mysqli_fetch_assoc($resultadoSolicitudesPendientes)["total"];

$consultaRutasPublicadas = "SELECT COUNT(*) AS total 
                            FROM viajes 
                            WHERE id_conductor = ?";
$stmtRutas = mysqli_prepare($conexion, $consultaRutasPublicadas);
mysqli_stmt_bind_param($stmtRutas, "i", $idUsuario);
mysqli_stmt_execute($stmtRutas);
$resultadoRutas = mysqli_stmt_get_result($stmtRutas);
$rutasPublicadas = mysqli_fetch_assoc($resultadoRutas)["total"];

$consultaUsuariosRegistrados = "SELECT COUNT(*) AS total FROM usuarios";
$resultadoUsuariosRegistrados = mysqli_query($conexion, $consultaUsuariosRegistrados);
$usuariosRegistrados = mysqli_fetch_assoc($resultadoUsuariosRegistrados)["total"];

$consultaViajesRecientes = "SELECT viajes.punto_salida,
                                   viajes.destino,
                                   viajes.fecha_hora,
                                   viajes.asientos_disponibles,
                                   viajes.estado,
                                   usuarios.nombre AS nombre_conductor
                            FROM viajes
                            INNER JOIN usuarios ON viajes.id_conductor = usuarios.id_usuario
                            WHERE viajes.estado = 'Activo'
                            ORDER BY viajes.fecha_hora ASC
                            LIMIT 3";

$resultadoViajesRecientes = mysqli_query($conexion, $consultaViajesRecientes);

$consultaActividad = "SELECT viajes.punto_salida,
                            viajes.destino,
                            viajes.fecha_hora,
                            viajes.estado,
                            'Viaje publicado' AS tipo
                     FROM viajes
                     WHERE id_conductor = ?
                     
                     UNION
                     
                     SELECT viajes.punto_salida,
                            viajes.destino,
                            solicitudes.fecha_solicitud AS fecha_hora,
                            solicitudes.estado_solicitud AS estado,
                            'Solicitud enviada' AS tipo
                     FROM solicitudes
                     INNER JOIN viajes ON solicitudes.id_viaje = viajes.id_viaje
                     WHERE solicitudes.id_pasajero = ?
                     
                     ORDER BY fecha_hora DESC
                     LIMIT 4";

$stmtActividad = mysqli_prepare($conexion, $consultaActividad);
mysqli_stmt_bind_param($stmtActividad, "ii", $idUsuario, $idUsuario);
mysqli_stmt_execute($stmtActividad);
$resultadoActividad = mysqli_stmt_get_result($stmtActividad);

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CarpoolMatch CR</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <header class="header">
        <div class="brand">
            <h1>CarpoolMatch CR</h1>
            <p>Panel principal</p>
        </div>

        <nav class="nav">
            <a href="index.html">Inicio</a>
            <a href="dashboard.php" class="nav-btn">Dashboard</a>
            <a href="viajes.php">Viajes</a>
            <a href="publicar-viaje.php">Publicar viaje</a>
            <a href="solicitudes.php">Mis solicitudes</a>
            <a href="solicitudes-recibidas.php">Recibidas</a>
            <a href="historial.php">Historial</a>
            <a href="calificaciones.php">Calificaciones</a>
            <a href="perfil.php">Perfil</a>
            <a href="php/logout.php" class="logout-icon" title="Cerrar sesión">
                <svg viewBox="0 0 24 24">
                    <path d="M10 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h5v-2H5V5h5V3z"></path>
                    <path d="M16.6 17.6 15.2 16.2 18.4 13H8v-2h10.4l-3.2-3.2 1.4-1.4L22.2 12z"></path>
                </svg>
            </a>
        </nav>

        <div class="usuario-header">
            👤 Bienvenido, <span><?php echo $nombreUsuario; ?></span>
        </div>
    </header>

    <main class="dashboard container">

        <section class="dashboard-welcome card">
            <div>
                <span class="tag">Panel de control</span>

                <h2>Bienvenido a CarpoolMatch CR</h2>

                <p>
                    Desde este dashboard puedes revisar un resumen general de los viajes,
                    solicitudes, rutas disponibles y actividad reciente de la plataforma.
                </p>
            </div>

            <div class="dashboard-user">
                <strong>Usuario activo</strong>
                <span><?php echo $nombreUsuario . " - " . $tipoUsuario; ?></span>
            </div>
        </section>

        <section class="dashboard-stats">
            <article class="dashboard-card card">
                <span>Viajes disponibles</span>
                <strong><?php echo $viajesDisponibles; ?></strong>
                <p>Rutas activas publicadas en la plataforma.</p>
            </article>

            <article class="dashboard-card card">
                <span>Solicitudes pendientes</span>
                <strong><?php echo $solicitudesPendientes; ?></strong>
                <p>Solicitudes esperando respuesta.</p>
            </article>

            <article class="dashboard-card card">
                <span>Rutas publicadas</span>
                <strong><?php echo $rutasPublicadas; ?></strong>
                <p>Viajes creados por el usuario activo.</p>
            </article>

            <article class="dashboard-card card">
                <span>Usuarios registrados</span>
                <strong><?php echo $usuariosRegistrados; ?></strong>
                <p>Personas activas en la plataforma.</p>
            </article>
        </section>

        <section class="dashboard-grid">

            <article class="card dashboard-section">
                <h3>Viajes recientes</h3>

                <div class="dashboard-list">

                    <?php if (mysqli_num_rows($resultadoViajesRecientes) > 0) { ?>

                        <?php while ($viaje = mysqli_fetch_assoc($resultadoViajesRecientes)) { ?>

                            <div class="dashboard-item">
                                <div>
                                    <strong>
                                        <?php echo $viaje["punto_salida"]; ?> → <?php echo $viaje["destino"]; ?>
                                    </strong>

                                    <p>
                                        Conductor: <?php echo $viaje["nombre_conductor"]; ?> |
                                        Fecha y hora: <?php echo $viaje["fecha_hora"]; ?> |
                                        Espacios disponibles: <?php echo $viaje["asientos_disponibles"]; ?>
                                    </p>
                                </div>

                                <span class="status">
                                    <?php echo $viaje["estado"]; ?>
                                </span>
                            </div>

                        <?php } ?>

                    <?php } else { ?>

                        <p>No hay viajes recientes disponibles.</p>

                    <?php } ?>

                </div>
            </article>

            <article class="card dashboard-section">
                <h3>Acciones rápidas</h3>

                <div class="quick-actions">
                    <a href="viajes.php" class="btn btn-primary full">Buscar viaje</a>
                    <a href="publicar-viaje.php" class="btn btn-secondary full">Publicar ruta</a>
                    <a href="solicitudes.php" class="btn btn-secondary full">Mis solicitudes</a>
                    <a href="solicitudes-recibidas.php" class="btn btn-secondary full">Solicitudes recibidas</a>
                    <a href="perfil.php" class="btn btn-secondary full">Editar perfil</a>
                </div>
            </article>

        </section>

        <section class="card dashboard-section">
            <h3>Resumen de actividad</h3>

            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Ruta</th>
                        <th>Tipo</th>
                        <th>Estado</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (mysqli_num_rows($resultadoActividad) > 0) { ?>

                        <?php while ($actividad = mysqli_fetch_assoc($resultadoActividad)) { ?>

                            <tr>
                                <td><?php echo $actividad["fecha_hora"]; ?></td>
                                <td><?php echo $actividad["punto_salida"]; ?> - <?php echo $actividad["destino"]; ?></td>
                                <td><?php echo $actividad["tipo"]; ?></td>
                                <td><?php echo $actividad["estado"]; ?></td>
                            </tr>

                        <?php } ?>

                    <?php } else { ?>

                        <tr>
                            <td colspan="4">No hay actividad registrada todavía.</td>
                        </tr>

                    <?php } ?>
                </tbody>
            </table>
        </section>

    </main>

    <footer class="footer">
        <p>CarpoolMatch CR - Dashboard del proyecto</p>
    </footer>

</body>

</html>