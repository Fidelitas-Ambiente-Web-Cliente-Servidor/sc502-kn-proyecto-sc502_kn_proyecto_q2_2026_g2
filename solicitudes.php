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

if ($tipoUsuario != "Pasajero" && $tipoUsuario != "Ambos") {
    header("Location: dashboard.php?error=rol");
    exit();
}

$consulta = "SELECT solicitudes.id_solicitud,
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
    <title>Mis solicitudes - CarpoolMatch CR</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <header class="header">
        <div class="brand">
            <h1>CarpoolMatch CR</h1>
            <p>Solicitudes de ride</p>
        </div>

        <nav class="nav">
            <a href="index.php">Inicio</a>
            <a href="dashboard.php">Dashboard</a>

            <div class="menu-dropdown">
                <span class="menu-btn">Viajes ▾</span>

                <div class="menu-content">
                    <a href="viajes.php">Buscar viajes</a>
                    <a href="solicitudes.php">Mis solicitudes</a>

                    <?php if ($tipoUsuario == "Ambos") { ?>
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
                <span class="tag">Mis solicitudes</span>

                <h2>Solicitudes realizadas</h2>

                <p>
                    En esta sección puede consultar los viajes que ha solicitado y el estado actual de cada solicitud.
                </p>
            </div>

            <div class="dashboard-user">
                <strong>Acción rápida</strong>
                <a href="viajes.php" class="btn btn-primary full">Buscar viajes</a>
            </div>
        </section>

        <section class="card dashboard-section">
            <h3>Listado de solicitudes</h3>

            <p id="mensajeSolicitudes" class="message"></p>

            <div class="dashboard-list">

                <?php if (mysqli_num_rows($resultado) > 0) { ?>

                    <?php while ($solicitud = mysqli_fetch_assoc($resultado)) { ?>

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
        <p>CarpoolMatch CR - Solicitudes de ride</p>
    </footer>

    <script>
        const parametrosSolicitudes = new URLSearchParams(window.location.search);
        const mensajeSolicitudes = document.getElementById("mensajeSolicitudes");

        if (mensajeSolicitudes) {
            const solicitud = parametrosSolicitudes.get("solicitud");

            if (solicitud === "ok") {
                mensajeSolicitudes.textContent = "Solicitud enviada correctamente.";
                mensajeSolicitudes.style.color = "green";
            }
        }
    </script>

</body>

</html>