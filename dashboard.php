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

$esPasajero = ($tipoUsuario == "Pasajero");
$esConductor = ($tipoUsuario == "Conductor");
$esAmbos = ($tipoUsuario == "Ambos");

$puedeBuscarViajes = ($esPasajero || $esAmbos);
$puedePublicarViajes = ($esConductor || $esAmbos);

$consultaViajesDisponibles = "SELECT COUNT(*) AS total FROM viajes WHERE estado = 'Activo'";
$resultadoViajesDisponibles = mysqli_query($conexion, $consultaViajesDisponibles);
$viajesDisponibles = mysqli_fetch_assoc($resultadoViajesDisponibles)["total"];

$consultaSolicitudesPasajero = "SELECT COUNT(*) AS total
                                FROM solicitudes
                                WHERE id_pasajero = ?
                                AND estado_solicitud = 'Pendiente'";

$stmtSolicitudesPasajero = mysqli_prepare($conexion, $consultaSolicitudesPasajero);
mysqli_stmt_bind_param($stmtSolicitudesPasajero, "i", $idUsuario);
mysqli_stmt_execute($stmtSolicitudesPasajero);
$resultadoSolicitudesPasajero = mysqli_stmt_get_result($stmtSolicitudesPasajero);
$solicitudesPasajero = mysqli_fetch_assoc($resultadoSolicitudesPasajero)["total"];

$consultaSolicitudesConductor = "SELECT COUNT(*) AS total
                                 FROM solicitudes
                                 INNER JOIN viajes ON solicitudes.id_viaje = viajes.id_viaje
                                 WHERE viajes.id_conductor = ?
                                 AND solicitudes.estado_solicitud = 'Pendiente'";

$stmtSolicitudesConductor = mysqli_prepare($conexion, $consultaSolicitudesConductor);
mysqli_stmt_bind_param($stmtSolicitudesConductor, "i", $idUsuario);
mysqli_stmt_execute($stmtSolicitudesConductor);
$resultadoSolicitudesConductor = mysqli_stmt_get_result($stmtSolicitudesConductor);
$solicitudesConductor = mysqli_fetch_assoc($resultadoSolicitudesConductor)["total"];

$consultaRutasPublicadas = "SELECT COUNT(*) AS total 
                            FROM viajes 
                            WHERE id_conductor = ?";

$stmtRutas = mysqli_prepare($conexion, $consultaRutasPublicadas);
mysqli_stmt_bind_param($stmtRutas, "i", $idUsuario);
mysqli_stmt_execute($stmtRutas);
$resultadoRutas = mysqli_stmt_get_result($stmtRutas);
$rutasPublicadas = mysqli_fetch_assoc($resultadoRutas)["total"];

$consultaCalificaciones = "SELECT COUNT(*) AS total
                           FROM calificaciones
                           WHERE id_evaluado = ?";

$stmtCalificaciones = mysqli_prepare($conexion, $consultaCalificaciones);
mysqli_stmt_bind_param($stmtCalificaciones, "i", $idUsuario);
mysqli_stmt_execute($stmtCalificaciones);
$resultadoCalificaciones = mysqli_stmt_get_result($stmtCalificaciones);
$totalCalificaciones = mysqli_fetch_assoc($resultadoCalificaciones)["total"];

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
                      
                      UNION
                      
                      SELECT viajes.punto_salida,
                             viajes.destino,
                             solicitudes.fecha_solicitud AS fecha_hora,
                             solicitudes.estado_solicitud AS estado,
                             'Solicitud recibida' AS tipo
                      FROM solicitudes
                      INNER JOIN viajes ON solicitudes.id_viaje = viajes.id_viaje
                      WHERE viajes.id_conductor = ?
                      
                      ORDER BY fecha_hora DESC
                      LIMIT 4";

$stmtActividad = mysqli_prepare($conexion, $consultaActividad);
mysqli_stmt_bind_param($stmtActividad, "iii", $idUsuario, $idUsuario, $idUsuario);
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
            <a href="index.php">Inicio</a>
            <a href="dashboard.php" class="nav-btn">Dashboard</a>

            <div class="menu-dropdown">
                <span class="menu-btn">Viajes ▾</span>

                <div class="menu-content">
                    <?php if ($puedeBuscarViajes) { ?>
                        <a href="viajes.php">Buscar viajes</a>
                        <a href="solicitudes.php">Mis solicitudes</a>
                    <?php } ?>

                    <?php if ($puedePublicarViajes) { ?>
                        <a href="publicar-viaje.php">Publicar viaje</a>
                        <a href="solicitudes-recibidas.php">Solicitudes recibidas</a>
                    <?php } ?>

                    <a href="historial.php">Historial</a>
                    <a href="calificaciones.php">Calificaciones</a>
                </div>
            </div>

            <div class="menu-dropdown">
                <span class="menu-btn">Cuenta ▾</span>

                <div class="menu-content">
                    <a href="perfil.php">Perfil</a>
                    <a href="php/logout.php">Cerrar sesión</a>
                </div>
            </div>
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
                    Desde este dashboard puedes revisar un resumen de tu actividad dentro de la plataforma,
                    según tu tipo de usuario registrado.
                </p>
            </div>

            <div class="dashboard-user">
                <strong>Usuario activo</strong>
                <span><?php echo $nombreUsuario . " - " . $tipoUsuario; ?></span>
            </div>
        </section>

        <section class="dashboard-stats">

            <?php if ($puedeBuscarViajes) { ?>

                <article class="dashboard-card card">
                    <span>Viajes disponibles</span>
                    <strong><?php echo $viajesDisponibles; ?></strong>
                    <p>Rutas activas que puedes consultar y solicitar.</p>
                </article>

                <article class="dashboard-card card">
                    <span>Mis solicitudes pendientes</span>
                    <strong><?php echo $solicitudesPasajero; ?></strong>
                    <p>Solicitudes que enviaste y aún esperan respuesta.</p>
                </article>

            <?php } ?>

            <?php if ($puedePublicarViajes) { ?>

                <article class="dashboard-card card">
                    <span>Rutas publicadas</span>
                    <strong><?php echo $rutasPublicadas; ?></strong>
                    <p>Viajes creados por tu usuario.</p>
                </article>

                <article class="dashboard-card card">
                    <span>Solicitudes recibidas</span>
                    <strong><?php echo $solicitudesConductor; ?></strong>
                    <p>Solicitudes pendientes en tus viajes publicados.</p>
                </article>

            <?php } ?>

            <article class="dashboard-card card">
                <span>Calificaciones recibidas</span>
                <strong><?php echo $totalCalificaciones; ?></strong>
                <p>Opiniones registradas sobre tu comportamiento en viajes.</p>
            </article>

        </section>

        <section class="dashboard-grid">

            <article class="card dashboard-section">

                <?php if ($puedeBuscarViajes) { ?>
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
                <?php } else { ?>

                    <h3>Gestión de conductor</h3>

                    <div class="dashboard-list">
                        <div class="dashboard-item">
                            <div>
                                <strong>Administra tus rutas publicadas</strong>
                                <p>
                                    Como conductor puedes publicar viajes y revisar las solicitudes recibidas por los pasajeros.
                                </p>
                            </div>

                            <span class="status">Conductor</span>
                        </div>

                        <div class="dashboard-item">
                            <div>
                                <strong>Solicitudes pendientes</strong>
                                <p>
                                    Actualmente tienes <?php echo $solicitudesConductor; ?> solicitud(es) esperando respuesta.
                                </p>
                            </div>

                            <span class="status"><?php echo $solicitudesConductor; ?></span>
                        </div>
                    </div>

                <?php } ?>

            </article>

            <article class="card dashboard-section">
                <h3>Acciones rápidas</h3>

                <div class="quick-actions">

                    <?php if ($puedeBuscarViajes) { ?>
                        <a href="viajes.php" class="btn btn-primary full">Buscar viaje</a>
                        <a href="solicitudes.php" class="btn btn-secondary full">Mis solicitudes</a>
                    <?php } ?>

                    <?php if ($puedePublicarViajes) { ?>
                        <a href="publicar-viaje.php" class="btn btn-primary full">Publicar ruta</a>
                        <a href="solicitudes-recibidas.php" class="btn btn-secondary full">Solicitudes recibidas</a>
                    <?php } ?>

                    <a href="historial.php" class="btn btn-secondary full">Historial</a>
                    <a href="calificaciones.php" class="btn btn-secondary full">Calificaciones</a>
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