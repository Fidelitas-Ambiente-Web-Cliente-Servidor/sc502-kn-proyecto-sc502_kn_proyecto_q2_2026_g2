<?php

session_start();
include "php/conexion.php";

if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.html");
    exit();
}

$nombreUsuario = $_SESSION["nombre"];

$consulta = "SELECT viajes.id_viaje,
                    viajes.punto_salida,
                    viajes.destino,
                    viajes.fecha_hora,
                    viajes.asientos_disponibles,
                    viajes.observaciones,
                    viajes.estado,
                    usuarios.nombre AS nombre_conductor
             FROM viajes
             INNER JOIN usuarios ON viajes.id_conductor = usuarios.id_usuario
             WHERE viajes.estado = 'Activo'
             ORDER BY viajes.fecha_hora ASC";

$resultado = mysqli_query($conexion, $consulta);

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Viajes disponibles - CarpoolMatch CR</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <header class="header">
        <div class="brand">
            <h1>CarpoolMatch CR</h1>
            <p>Viajes disponibles</p>
        </div>

        <nav class="nav">
            <a href="index.html">Inicio</a>
            <a href="dashboard.php">Dashboard</a>
            <a href="viajes.php" class="nav-btn">Viajes</a>
            <a href="publicar-viaje.php">Publicar viaje</a>
            <a href="solicitudes.php">Mis solicitudes</a>
            <a href="solicitudes-recibidas.php">Recibidas</a>
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
                <span class="tag">Viajes activos</span>

                <h2>Viajes disponibles</h2>

                <p>
                    Aquí puede consultar las rutas publicadas por los conductores y buscar una opción que se adapte a su
                    traslado.
                </p>
            </div>

            <div class="dashboard-user">
                <strong>Acción rápida</strong>
                <a href="publicar-viaje.php" class="btn btn-primary full">Publicar viaje</a>
            </div>
        </section>

        <section class="card dashboard-section">
            <h3>Listado de viajes</h3>

            <p id="mensajeViajes" class="message"></p>

            <div class="dashboard-list">

                <?php if (mysqli_num_rows($resultado) > 0) { ?>

                    <?php while ($viaje = mysqli_fetch_assoc($resultado)) { ?>

                        <div class="dashboard-item">
                            <div>
                                <strong>
                                    <?php echo $viaje["punto_salida"]; ?> → <?php echo $viaje["destino"]; ?>
                                </strong>

                                <p>
                                    Conductor: <?php echo $viaje["nombre_conductor"]; ?> |
                                    Fecha y hora: <?php echo $viaje["fecha_hora"]; ?> |
                                    Espacios: <?php echo $viaje["asientos_disponibles"]; ?>
                                </p>

                                <p>
                                    <?php echo $viaje["observaciones"]; ?>
                                </p>
                            </div>

                            <div class="acciones-viaje">
                                <span class="status">
                                    <?php echo $viaje["estado"]; ?>
                                </span>

                                <a href="php/solicitar_ride.php?id=<?php echo $viaje["id_viaje"]; ?>" class="btn btn-primary">
                                    Solicitar ride
                                </a>
                            </div>
                        </div>

                    <?php } ?>

                <?php } else { ?>

                    <p>No hay viajes disponibles en este momento.</p>

                <?php } ?>

            </div>
        </section>

    </main>

    <footer class="footer">
        <p>CarpoolMatch CR - Viajes disponibles</p>
    </footer>

    <script>
        const parametrosViajes = new URLSearchParams(window.location.search);
        const mensajeViajes = document.getElementById("mensajeViajes");

        if (mensajeViajes) {
            const publicado = parametrosViajes.get("publicado");
            const error = parametrosViajes.get("error");

            if (publicado === "ok") {
                mensajeViajes.textContent = "Viaje publicado correctamente.";
                mensajeViajes.style.color = "green";
            }

            if (error === "propio") {
                mensajeViajes.textContent = "No puede solicitar un viaje publicado por usted mismo.";
                mensajeViajes.style.color = "red";
            } else if (error === "duplicada") {
                mensajeViajes.textContent = "Ya existe una solicitud para este viaje.";
                mensajeViajes.style.color = "red";
            } else if (error === "sinasientos") {
                mensajeViajes.textContent = "Este viaje ya no tiene espacios disponibles.";
                mensajeViajes.style.color = "red";
            } else if (error === "noactivo") {
                mensajeViajes.textContent = "Este viaje no se encuentra activo.";
                mensajeViajes.style.color = "red";
            } else if (error === "noexiste") {
                mensajeViajes.textContent = "El viaje seleccionado no existe.";
                mensajeViajes.style.color = "red";
            } else if (error === "sinviaje") {
                mensajeViajes.textContent = "Debe seleccionar un viaje válido.";
                mensajeViajes.style.color = "red";
            } else if (error === "bd") {
                mensajeViajes.textContent = "Ocurrió un error al registrar la solicitud.";
                mensajeViajes.style.color = "red";
            }
        }
    </script>

</body>

</html>