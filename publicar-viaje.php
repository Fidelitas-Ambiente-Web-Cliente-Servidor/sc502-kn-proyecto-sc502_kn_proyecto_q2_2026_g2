<?php

session_start();

if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.html");
    exit();
}

$nombreUsuario = $_SESSION["nombre"];
$tipoUsuario = $_SESSION["tipo_usuario"];

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
            <a href="index.html">Inicio</a>
            <a href="dashboard.php">Dashboard</a>
            <a href="viajes.php">Viajes</a>
            <a href="publicar-viaje.php" class="nav-btn">Publicar viaje</a>
            <a href="#">Solicitudes</a>
            <a href="historial.php">Historial</a>
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