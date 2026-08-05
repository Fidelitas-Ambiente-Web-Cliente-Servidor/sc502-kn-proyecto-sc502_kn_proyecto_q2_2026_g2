<?php

session_start();
include "php/conexion.php";

if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.html");
    exit();
}

$idUsuario = $_SESSION["id_usuario"];

$consulta = "SELECT id_usuario, nombre, correo, telefono, tipo_usuario, reputacion, estado, fecha_registro
             FROM usuarios
             WHERE id_usuario = ?";

$stmt = mysqli_prepare($conexion, $consulta);
mysqli_stmt_bind_param($stmt, "i", $idUsuario);
mysqli_stmt_execute($stmt);

$resultado = mysqli_stmt_get_result($stmt);
$usuario = mysqli_fetch_assoc($resultado);

$nombreUsuario = $usuario["nombre"];

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil - CarpoolMatch CR</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <header class="header">
        <div class="brand">
            <h1>CarpoolMatch CR</h1>
            <p>Perfil de usuario</p>
        </div>

        <nav class="nav">
            <a href="index.php">Inicio</a>
            <a href="dashboard.php">Dashboard</a>
            <a href="viajes.php">Viajes</a>
            <a href="publicar-viaje.php">Publicar viaje</a>
            <a href="solicitudes.php">Mis solicitudes</a>
            <a href="solicitudes-recibidas.php">Recibidas</a>
            <a href="historial.php">Historial</a>
            <a href="calificaciones.php">Calificaciones</a>
            <a href="perfil.php" class="nav-btn">Perfil</a>
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
                <span class="tag">Mi perfil</span>

                <h2>Información del usuario</h2>

                <p>
                    En esta sección puede consultar y actualizar la información básica de su cuenta.
                </p>
            </div>

            <div class="dashboard-user">
                <strong>Reputación</strong>
                <span><?php echo $usuario["reputacion"]; ?></span>
            </div>
        </section>

        <section class="card dashboard-section">
            <h3>Datos personales</h3>

            <p id="mensajePerfil" class="message"></p>

            <form class="form" action="php/actualizar_perfil.php" method="POST">
                <label for="nombre">Nombre completo</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo $usuario["nombre"]; ?>"
                    autocomplete="off">

                <label for="correo">Correo electrónico</label>
                <input type="email" id="correo" value="<?php echo $usuario["correo"]; ?>" readonly>

                <label for="telefono">Teléfono</label>
                <input type="tel" id="telefono" name="telefono" value="<?php echo $usuario["telefono"]; ?>"
                    maxlength="8" inputmode="numeric" autocomplete="off">

                <label for="tipoUsuario">Tipo de usuario</label>
                <select id="tipoUsuario" name="tipoUsuario">
                    <option value="Conductor" <?php if ($usuario["tipo_usuario"] == "Conductor")
                        echo "selected"; ?>>
                        Conductor</option>
                    <option value="Pasajero" <?php if ($usuario["tipo_usuario"] == "Pasajero")
                        echo "selected"; ?>>
                        Pasajero</option>
                    <option value="Ambos" <?php if ($usuario["tipo_usuario"] == "Ambos")
                        echo "selected"; ?>>Ambos
                    </option>
                </select>

                <label>Estado de la cuenta</label>
                <input type="text" value="<?php echo $usuario["estado"]; ?>" readonly>

                <label>Fecha de registro</label>
                <input type="text" value="<?php echo $usuario["fecha_registro"]; ?>" readonly>

                <button type="submit" class="btn btn-primary full">
                    Guardar cambios
                </button>
            </form>
        </section>

    </main>

    <footer class="footer">
        <p>CarpoolMatch CR - Perfil de usuario</p>
    </footer>

    <script>
        const parametrosPerfil = new URLSearchParams(window.location.search);
        const mensajePerfil = document.getElementById("mensajePerfil");

        if (mensajePerfil) {
            const actualizado = parametrosPerfil.get("actualizado");
            const error = parametrosPerfil.get("error");

            if (actualizado === "ok") {
                mensajePerfil.textContent = "Perfil actualizado correctamente.";
                mensajePerfil.style.color = "green";
            }

            if (error === "campos") {
                mensajePerfil.textContent = "Debe completar todos los campos.";
                mensajePerfil.style.color = "red";
            } else if (error === "nombre") {
                mensajePerfil.textContent = "El nombre solo puede contener letras.";
                mensajePerfil.style.color = "red";
            } else if (error === "telefono") {
                mensajePerfil.textContent = "El teléfono debe tener 8 dígitos.";
                mensajePerfil.style.color = "red";
            } else if (error === "tipo") {
                mensajePerfil.textContent = "Debe seleccionar un tipo de usuario válido.";
                mensajePerfil.style.color = "red";
            } else if (error === "bd") {
                mensajePerfil.textContent = "Ocurrió un error al actualizar el perfil.";
                mensajePerfil.style.color = "red";
            }
        }
    </script>

    <script src="js/app.js"></script>

</body>

</html>