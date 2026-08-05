<?php

session_start();
include "php/conexion.php";

if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.html");
    exit();
}

$idUsuario = $_SESSION["id_usuario"];
$nombreUsuario = $_SESSION["nombre"];

$consultaViajesPublicados = "SELECT id_viaje,
                                    punto_salida,
                                    destino,
                                    fecha_hora,
                                    asientos_disponibles,
                                    estado,
                                    fecha_publicacion
                             FROM viajes
                             WHERE id_conductor = ?
                             ORDER BY fecha_publicacion DESC";

$stmtPublicados = mysqli_prepare($conexion, $consultaViajesPublicados);
mysqli_stmt_bind_param($stmtPublicados, "i", $idUsuario);
mysqli_stmt_execute($stmtPublicados);
$resultadoPublicados = mysqli_stmt_get_result($stmtPublicados);

$consultaSolicitudesRealizadas = "SELECT solicitudes.id_solicitud,
                                         solicitudes.estado_solicitud,
                                         solicitudes.fecha_solicitud,
                                         viajes.punto_salida,
                                         viajes.destino,
                                         viajes.fecha_hora,
                                         usuarios.nombre AS nombre_conductor
                                  FROM solicitudes
                                  INNER JOIN viajes ON solicitudes.id_viaje = viajes.id_viaje
                                  INNER JOIN usuarios ON viajes.id_conductor = usuarios.id_usuario
                                  WHERE solicitudes.id_pasajero = ?
                                  ORDER BY solicitudes.fecha_solicitud DESC";

$stmtSolicitudes = mysqli_prepare($conexion, $consultaSolicitudesRealizadas);
mysqli_stmt_bind_param($stmtSolicitudes, "i", $idUsuario);
mysqli_stmt_execute($stmtSolicitudes);
$resultadoSolicitudes = mysqli_stmt_get_result($stmtSolicitudes);

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial - CarpoolMatch CR</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <header class="header">
        <div class="brand">
            <h1>CarpoolMatch CR</h1>
            <p>Historial de actividad</p>
        </div>

        <nav class="nav">
            <a href="index.html">Inicio</a>
            <a href="dashboard.php">Dashboard</a>
            <a href="viajes.php">Viajes</a>
            <a href="publicar-viaje.php">Publicar viaje</a>
            <a href="solicitudes.php">Mis solicitudes</a>
            <a href="solicitudes-recibidas.php">Recibidas</a>
            <a href="historial.php" class="nav-btn">Historial</a>
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
                <span class="tag">Historial</span>

                <h2>Historial de viajes y solicitudes</h2>

                <p>
                    En esta sección puede revisar los viajes publicados y las solicitudes realizadas dentro de la plataforma.
                </p>
            </div>

            <div class="dashboard-user">
                <strong>Acción rápida</strong>
                <a href="viajes.php" class="btn btn-primary full">Buscar viajes</a>
            </div>
        </section>

        <section class="card dashboard-section">
            <h3>Viajes publicados por mí</h3>

            <div class="dashboard-list">

                <?php if (mysqli_num_rows($resultadoPublicados) > 0) { ?>

                    <?php while ($viaje = mysqli_fetch_assoc($resultadoPublicados)) { ?>

                        <div class="dashboard-item">
                            <div>
                                <strong>
                                    <?php echo $viaje["punto_salida"]; ?> → <?php echo $viaje["destino"]; ?>
                                </strong>

                                <p>
                                    Fecha del viaje: <?php echo $viaje["fecha_hora"]; ?> |
                                    Espacios: <?php echo $viaje["asientos_disponibles"]; ?>
                                </p>

                                <p>
                                    Publicado: <?php echo $viaje["fecha_publicacion"]; ?>
                                </p>
                            </div>

                            <span class="status">
                                <?php echo $viaje["estado"]; ?>
                            </span>
                        </div>

                    <?php } ?>

                <?php } else { ?>

                    <p>No ha publicado viajes todavía.</p>

                <?php } ?>

            </div>
        </section>

        <section class="card dashboard-section">
            <h3>Solicitudes realizadas por mí</h3>

            <div class="dashboard-list">

                <?php if (mysqli_num_rows($resultadoSolicitudes) > 0) { ?>

                    <?php while ($solicitud = mysqli_fetch_assoc($resultadoSolicitudes)) { ?>

                        <div class="dashboard-item">
                            <div>
                                <strong>
                                    <?php echo $solicitud["punto_salida"]; ?> → <?php echo $solicitud["destino"]; ?>
                                </strong>

                                <p>
                                    Conductor: <?php echo $solicitud["nombre_conductor"]; ?> |
                                    Fecha del viaje: <?php echo $solicitud["fecha_hora"]; ?>
                                </p>

                                <p>
                                    Fecha de solicitud: <?php echo $solicitud["fecha_solicitud"]; ?>
                                </p>
                            </div>

                            <span class="status">
                                <?php echo $solicitud["estado_solicitud"]; ?>
                            </span>
                        </div>

                    <?php } ?>

                <?php } else { ?>

                    <p>No ha realizado solicitudes todavía.</p>

                <?php } ?>

            </div>
        </section>

    </main>

    <footer class="footer">
        <p>CarpoolMatch CR - Historial de actividad</p>
    </footer>

</body>

</html>