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

if ($tipoUsuario == "Pasajero") {

    $consultaViajes = "SELECT DISTINCT viajes.id_viaje,
                              viajes.punto_salida,
                              viajes.destino,
                              viajes.fecha_hora,
                              conductor.id_usuario AS id_evaluado,
                              conductor.nombre AS nombre_evaluado,
                              'Conductor' AS tipo_evaluado
                       FROM solicitudes
                       INNER JOIN viajes ON solicitudes.id_viaje = viajes.id_viaje
                       INNER JOIN usuarios AS conductor ON viajes.id_conductor = conductor.id_usuario
                       WHERE solicitudes.id_pasajero = ?
                       AND solicitudes.estado_solicitud = 'Aprobada'
                       ORDER BY viajes.fecha_hora DESC";

    $stmtViajes = mysqli_prepare($conexion, $consultaViajes);
    mysqli_stmt_bind_param($stmtViajes, "i", $idUsuario);

} else if ($tipoUsuario == "Conductor") {

    $consultaViajes = "SELECT DISTINCT viajes.id_viaje,
                              viajes.punto_salida,
                              viajes.destino,
                              viajes.fecha_hora,
                              pasajero.id_usuario AS id_evaluado,
                              pasajero.nombre AS nombre_evaluado,
                              'Pasajero' AS tipo_evaluado
                       FROM solicitudes
                       INNER JOIN viajes ON solicitudes.id_viaje = viajes.id_viaje
                       INNER JOIN usuarios AS pasajero ON solicitudes.id_pasajero = pasajero.id_usuario
                       WHERE viajes.id_conductor = ?
                       AND solicitudes.estado_solicitud = 'Aprobada'
                       ORDER BY viajes.fecha_hora DESC";

    $stmtViajes = mysqli_prepare($conexion, $consultaViajes);
    mysqli_stmt_bind_param($stmtViajes, "i", $idUsuario);

} else {

    $consultaViajes = "SELECT DISTINCT viajes.id_viaje,
                              viajes.punto_salida,
                              viajes.destino,
                              viajes.fecha_hora,
                              conductor.id_usuario AS id_evaluado,
                              conductor.nombre AS nombre_evaluado,
                              'Conductor' AS tipo_evaluado
                       FROM solicitudes
                       INNER JOIN viajes ON solicitudes.id_viaje = viajes.id_viaje
                       INNER JOIN usuarios AS conductor ON viajes.id_conductor = conductor.id_usuario
                       WHERE solicitudes.id_pasajero = ?
                       AND solicitudes.estado_solicitud = 'Aprobada'

                       UNION

                       SELECT DISTINCT viajes.id_viaje,
                              viajes.punto_salida,
                              viajes.destino,
                              viajes.fecha_hora,
                              pasajero.id_usuario AS id_evaluado,
                              pasajero.nombre AS nombre_evaluado,
                              'Pasajero' AS tipo_evaluado
                       FROM solicitudes
                       INNER JOIN viajes ON solicitudes.id_viaje = viajes.id_viaje
                       INNER JOIN usuarios AS pasajero ON solicitudes.id_pasajero = pasajero.id_usuario
                       WHERE viajes.id_conductor = ?
                       AND solicitudes.estado_solicitud = 'Aprobada'

                       ORDER BY fecha_hora DESC";

    $stmtViajes = mysqli_prepare($conexion, $consultaViajes);
    mysqli_stmt_bind_param($stmtViajes, "ii", $idUsuario, $idUsuario);
}

mysqli_stmt_execute($stmtViajes);
$resultadoViajes = mysqli_stmt_get_result($stmtViajes);

$consultaCalificaciones = "SELECT calificaciones.puntaje,
                                  calificaciones.comentario,
                                  calificaciones.fecha_calificacion,
                                  evaluador.nombre AS nombre_evaluador,
                                  viajes.punto_salida,
                                  viajes.destino
                           FROM calificaciones
                           INNER JOIN usuarios AS evaluador ON calificaciones.id_evaluador = evaluador.id_usuario
                           INNER JOIN viajes ON calificaciones.id_viaje = viajes.id_viaje
                           WHERE calificaciones.id_evaluado = ?
                           ORDER BY calificaciones.fecha_calificacion DESC";

$stmtCalificaciones = mysqli_prepare($conexion, $consultaCalificaciones);
mysqli_stmt_bind_param($stmtCalificaciones, "i", $idUsuario);
mysqli_stmt_execute($stmtCalificaciones);
$resultadoCalificaciones = mysqli_stmt_get_result($stmtCalificaciones);

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calificaciones - CarpoolMatch CR</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <header class="header">
        <div class="brand">
            <h1>CarpoolMatch CR</h1>
            <p>Calificaciones</p>
        </div>

        <nav class="nav">
            <a href="index.php">Inicio</a>
            <a href="dashboard.php">Dashboard</a>

            <div class="menu-dropdown">
                <span class="menu-btn">Viajes ▾</span>

                <div class="menu-content">
                    <?php if ($tipoUsuario == "Pasajero" || $tipoUsuario == "Ambos") { ?>
                        <a href="viajes.php">Buscar viajes</a>
                        <a href="solicitudes.php">Mis solicitudes</a>
                    <?php } ?>

                    <?php if ($tipoUsuario == "Conductor" || $tipoUsuario == "Ambos") { ?>
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
                <span class="tag">Reputación</span>

                <h2>Calificaciones de usuarios</h2>

                <p>
                    En esta sección puede calificar usuarios con los que compartió un viaje aprobado y consultar las
                    calificaciones recibidas.
                </p>
            </div>

            <div class="dashboard-user">
                <strong>Tipo de usuario</strong>
                <span><?php echo $tipoUsuario; ?></span>
            </div>
        </section>

        <section class="card dashboard-section">
            <h3>Realizar calificación</h3>

            <p id="mensajeCalificacion" class="message"></p>

            <form class="form" action="php/guardar_calificacion.php" method="POST">
                <label for="viaje">Viaje aprobado</label>

                <select id="viaje" name="viaje">
                    <option value="">Seleccione un viaje</option>

                    <?php while ($viaje = mysqli_fetch_assoc($resultadoViajes)) { ?>

                        <option value="<?php echo $viaje["id_viaje"] . "|" . $viaje["id_evaluado"]; ?>">
                            <?php echo $viaje["punto_salida"]; ?> → <?php echo $viaje["destino"]; ?> |
                            <?php echo $viaje["tipo_evaluado"]; ?>:
                            <?php echo $viaje["nombre_evaluado"]; ?> |
                            Fecha: <?php echo $viaje["fecha_hora"]; ?>
                        </option>

                    <?php } ?>
                </select>

                <label for="puntaje">Puntaje</label>
                <select id="puntaje" name="puntaje">
                    <option value="">Seleccione una calificación</option>
                    <option value="5">5 - Excelente</option>
                    <option value="4">4 - Muy bueno</option>
                    <option value="3">3 - Bueno</option>
                    <option value="2">2 - Regular</option>
                    <option value="1">1 - Malo</option>
                </select>

                <label for="comentario">Comentario</label>
                <input type="text" id="comentario" name="comentario" placeholder="Escriba un comentario sobre el viaje">

                <button type="submit" class="btn btn-primary full">
                    Guardar calificación
                </button>
            </form>
        </section>

        <section class="card dashboard-section">
            <h3>Calificaciones recibidas</h3>

            <div class="dashboard-list">

                <?php if (mysqli_num_rows($resultadoCalificaciones) > 0) { ?>

                    <?php while ($calificacion = mysqli_fetch_assoc($resultadoCalificaciones)) { ?>

                        <div class="dashboard-item">
                            <div>
                                <strong>
                                    <?php echo $calificacion["punto_salida"]; ?> → <?php echo $calificacion["destino"]; ?>
                                </strong>

                                <p>
                                    Evaluador: <?php echo $calificacion["nombre_evaluador"]; ?> |
                                    Puntaje: <?php echo $calificacion["puntaje"]; ?>/5
                                </p>

                                <p>
                                    Comentario: <?php echo $calificacion["comentario"]; ?>
                                </p>

                                <p>
                                    Fecha: <?php echo $calificacion["fecha_calificacion"]; ?>
                                </p>
                            </div>

                            <span class="status">
                                <?php echo $calificacion["puntaje"]; ?>/5
                            </span>
                        </div>

                    <?php } ?>

                <?php } else { ?>

                    <p>No tiene calificaciones recibidas todavía.</p>

                <?php } ?>

            </div>
        </section>

    </main>

    <footer class="footer">
        <p>CarpoolMatch CR - Calificaciones</p>
    </footer>

    <script>
        const parametrosCalificacion = new URLSearchParams(window.location.search);
        const mensajeCalificacion = document.getElementById("mensajeCalificacion");

        if (mensajeCalificacion) {
            const guardado = parametrosCalificacion.get("guardado");
            const error = parametrosCalificacion.get("error");

            if (guardado === "ok") {
                mensajeCalificacion.textContent = "Calificación guardada correctamente.";
                mensajeCalificacion.style.color = "green";
            }

            if (error === "campos") {
                mensajeCalificacion.textContent = "Debe completar todos los campos.";
                mensajeCalificacion.style.color = "red";
            } else if (error === "puntaje") {
                mensajeCalificacion.textContent = "El puntaje debe estar entre 1 y 5.";
                mensajeCalificacion.style.color = "red";
            } else if (error === "duplicada") {
                mensajeCalificacion.textContent = "Ya calificó este viaje anteriormente.";
                mensajeCalificacion.style.color = "red";
            } else if (error === "permiso") {
                mensajeCalificacion.textContent = "No tiene permiso para calificar ese viaje.";
                mensajeCalificacion.style.color = "red";
            } else if (error === "propio") {
                mensajeCalificacion.textContent = "No puede calificarse a usted mismo.";
                mensajeCalificacion.style.color = "red";
            } else if (error === "bd") {
                mensajeCalificacion.textContent = "Ocurrió un error al guardar la calificación.";
                mensajeCalificacion.style.color = "red";
            }
        }
    </script>

</body>

</html>