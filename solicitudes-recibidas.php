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

if ($tipoUsuario != "Conductor" && $tipoUsuario != "Ambos") {
    header("Location: dashboard.php?error=rol");
    exit();
}

$consulta = "SELECT solicitudes.id_solicitud,
                    solicitudes.estado_solicitud,
                    solicitudes.fecha_solicitud,
                    viajes.punto_salida,
                    viajes.destino,
                    viajes.fecha_hora,
                    usuarios.nombre AS nombre_pasajero,
                    usuarios.correo AS correo_pasajero,
                    usuarios.telefono AS telefono_pasajero
             FROM solicitudes
             INNER JOIN viajes ON solicitudes.id_viaje = viajes.id_viaje
             INNER JOIN usuarios ON solicitudes.id_pasajero = usuarios.id_usuario
             WHERE viajes.id_conductor = ?
             ORDER BY solicitudes.fecha_solicitud DESC";

$stmt = mysqli_prepare($conexion, $consulta);
mysqli_stmt_bind_param($stmt, "i", $idUsuario);
mysqli_stmt_execute($stmt);

$resultado = mysqli_stmt_get_result($stmt);

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitudes recibidas - CarpoolMatch CR</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <header class="header">
        <div class="brand">
            <h1>CarpoolMatch CR</h1>
            <p>Solicitudes recibidas</p>
        </div>

        <nav class="nav">
            <a href="index.php">Inicio</a>
            <a href="dashboard.php">Dashboard</a>

            <div class="menu-dropdown">
                <span class="menu-btn">Viajes ▾</span>

                <div class="menu-content">
                    <?php if ($tipoUsuario == "Ambos") { ?>
                        <a href="viajes.php">Buscar viajes</a>
                        <a href="solicitudes.php">Mis solicitudes</a>
                    <?php } ?>

                    <a href="publicar-viaje.php">Publicar viaje</a>
                    <a href="solicitudes-recibidas.php">Solicitudes recibidas</a>
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
                <span class="tag">Solicitudes recibidas</span>

                <h2>Solicitudes para mis viajes</h2>

                <p>
                    En esta sección puede revisar las solicitudes enviadas por pasajeros a los viajes que usted publicó.
                </p>
            </div>

            <div class="dashboard-user">
                <strong>Acción rápida</strong>
                <a href="publicar-viaje.php" class="btn btn-primary full">Publicar viaje</a>
            </div>
        </section>

        <section class="card dashboard-section">
            <h3>Listado de solicitudes recibidas</h3>

            <p id="mensajeSolicitudesRecibidas" class="message"></p>

            <div class="dashboard-list">

                <?php if (mysqli_num_rows($resultado) > 0) { ?>

                    <?php while ($solicitud = mysqli_fetch_assoc($resultado)) { ?>

                        <div class="dashboard-item">
                            <div>
                                <strong>
                                    <?php echo $solicitud["punto_salida"]; ?> → <?php echo $solicitud["destino"]; ?>
                                </strong>

                                <p>
                                    Pasajero: <?php echo $solicitud["nombre_pasajero"]; ?> |
                                    Correo: <?php echo $solicitud["correo_pasajero"]; ?> |
                                    Teléfono: <?php echo $solicitud["telefono_pasajero"]; ?>
                                </p>

                                <p>
                                    Fecha del viaje: <?php echo $solicitud["fecha_hora"]; ?> |
                                    Fecha de solicitud: <?php echo $solicitud["fecha_solicitud"]; ?>
                                </p>
                            </div>

                            <div class="acciones-viaje">
                                <span class="status">
                                    <?php echo $solicitud["estado_solicitud"]; ?>
                                </span>

                                <?php if ($solicitud["estado_solicitud"] == "Pendiente") { ?>

                                    <a href="php/actualizar_solicitud.php?id=<?php echo $solicitud["id_solicitud"]; ?>&estado=Aprobada"
                                        class="btn btn-primary">
                                        Aprobar
                                    </a>

                                    <a href="php/actualizar_solicitud.php?id=<?php echo $solicitud["id_solicitud"]; ?>&estado=Rechazada"
                                        class="btn btn-secondary">
                                        Rechazar
                                    </a>

                                <?php } ?>
                            </div>
                        </div>

                    <?php } ?>

                <?php } else { ?>

                    <p>No tiene solicitudes recibidas todavía.</p>

                <?php } ?>

            </div>
        </section>

    </main>

    <footer class="footer">
        <p>CarpoolMatch CR - Solicitudes recibidas</p>
    </footer>

    <script>
        const parametrosRecibidas = new URLSearchParams(window.location.search);
        const mensajeRecibidas = document.getElementById("mensajeSolicitudesRecibidas");

        if (mensajeRecibidas) {
            const actualizado = parametrosRecibidas.get("actualizado");
            const error = parametrosRecibidas.get("error");

            if (actualizado === "ok") {
                mensajeRecibidas.textContent = "Solicitud actualizada correctamente.";
                mensajeRecibidas.style.color = "green";
            }

            if (error === "datos") {
                mensajeRecibidas.textContent = "Datos de solicitud inválidos.";
                mensajeRecibidas.style.color = "red";
            } else if (error === "permiso") {
                mensajeRecibidas.textContent = "No tiene permiso para modificar esta solicitud.";
                mensajeRecibidas.style.color = "red";
            } else if (error === "bd") {
                mensajeRecibidas.textContent = "Ocurrió un error al actualizar la solicitud.";
                mensajeRecibidas.style.color = "red";
            }
        }
    </script>

</body>

</html>