<?php

session_start();

if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.html");
    exit();
}

$nombreUsuario = $_SESSION["nombre"];
$tipoUsuario = $_SESSION["tipo_usuario"];

if ($tipoUsuario != "Conductor" && $tipoUsuario != "Ambos") {
    header("Location: dashboard.php?error=rol");
    exit();
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publicar viaje - CarpoolMatch CR</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <header class="header">
        <div class="brand">
            <h1>CarpoolMatch CR</h1>
            <p>Publicar viaje</p>
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

    <main class="login-simple">
        <section class="login-card registro-card">
            <h2>Publicar viaje</h2>

            <p>
                Complete la información del viaje para que otros usuarios puedan solicitar un espacio.
            </p>

            <form class="form" action="php/guardar_viaje.php" method="POST">
                <label for="puntoSalida">Punto de salida</label>
                <input type="text" id="puntoSalida" name="puntoSalida" placeholder="Ejemplo: Heredia Centro">

                <label for="destino">Destino</label>
                <input type="text" id="destino" name="destino" placeholder="Ejemplo: Universidad Fidélitas">

                <label for="fechaHora">Fecha y hora</label>
                <input type="datetime-local" id="fechaHora" name="fechaHora">

                <label for="asientos">Espacios disponibles</label>
                <input type="number" id="asientos" name="asientos" min="1" max="8" placeholder="Ejemplo: 3">

                <label for="observaciones">Observaciones</label>
                <input type="text" id="observaciones" name="observaciones" placeholder="Ejemplo: Salida puntual">

                <button type="submit" class="btn btn-primary full">
                    Guardar viaje
                </button>
            </form>

            <p id="mensajeViaje" class="message"></p>
        </section>
    </main>

    <script>
        const parametrosViaje = new URLSearchParams(window.location.search);
        const mensajeViaje = document.getElementById("mensajeViaje");

        if (mensajeViaje) {
            const error = parametrosViaje.get("error");

            if (error === "campos") {
                mensajeViaje.textContent = "Debe completar los campos obligatorios.";
                mensajeViaje.style.color = "red";
            } else if (error === "asientos") {
                mensajeViaje.textContent = "Los espacios disponibles deben ser mayores a cero.";
                mensajeViaje.style.color = "red";
            } else if (error === "bd") {
                mensajeViaje.textContent = "Ocurrió un error al guardar el viaje.";
                mensajeViaje.style.color = "red";
            }
        }
    </script>

</body>

</html>