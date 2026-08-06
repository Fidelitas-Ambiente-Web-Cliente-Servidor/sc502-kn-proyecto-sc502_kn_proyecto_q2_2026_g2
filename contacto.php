<?php

session_start();

$haySesion = isset($_SESSION["id_usuario"]);
$nombreUsuario = "";
$tipoUsuario = "";

$puedeBuscarViajes = false;
$puedePublicarViajes = false;

if ($haySesion) {
    $nombreUsuario = $_SESSION["nombre"];
    $tipoUsuario = $_SESSION["tipo_usuario"];

    $puedeBuscarViajes = ($tipoUsuario == "Pasajero" || $tipoUsuario == "Ambos");
    $puedePublicarViajes = ($tipoUsuario == "Conductor" || $tipoUsuario == "Ambos");
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto - CarpoolMatch CR</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <header class="header">
        <div class="brand">
            <h1>CarpoolMatch CR</h1>
            <p>Viajes compartidos en Costa Rica</p>
        </div>

        <nav class="nav">
            <a href="index.php">Inicio</a>

            <div class="menu-dropdown">
                <span class="menu-btn nav-btn">Información ▾</span>

                <div class="menu-content">
                    <a href="acerca.php">Acerca de nosotros</a>
                    <a href="faq.php">Preguntas frecuentes</a>
                    <a href="contacto.php">Contacto</a>
                </div>
            </div>

            <?php if ($haySesion) { ?>

                <a href="dashboard.php">Dashboard</a>

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

            <?php } else { ?>

                <div class="menu-dropdown">
                    <span class="menu-btn">Cuenta ▾</span>

                    <div class="menu-content">
                        <a href="login.html">Iniciar sesión</a>
                        <a href="registro.html">Registrarse</a>
                    </div>
                </div>

            <?php } ?>
        </nav>

        <?php if ($haySesion) { ?>
            <div class="usuario-header">
                👤 Bienvenido, <span><?php echo $nombreUsuario; ?></span>
            </div>
        <?php } ?>
    </header>

    <main class="login-simple" style="padding: 40px 20px;">
        <section class="login-card" style="max-width: 600px; margin: 0 auto; text-align: center;">
            <h2>Contacto</h2>

            <p>
                ¿Tienes dudas, sugerencias o reportes? Envíanos un mensaje y el equipo de CarpoolMatch CR te responderá
                lo antes posible.
            </p>

            <form id="formContacto" class="form"
                style="display: flex; flex-direction: column; gap: 15px; text-align: left; margin-top: 20px;">

                <div>
                    <label for="c-nombre" style="font-weight: bold; display: block; margin-bottom: 5px;">
                        Nombre completo
                    </label>

                    <input type="text" id="c-nombre" placeholder="Tu nombre" required
                        style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                </div>

                <div>
                    <label for="c-correo" style="font-weight: bold; display: block; margin-bottom: 5px;">
                        Correo electrónico
                    </label>

                    <input type="email" id="c-correo" placeholder="correo@ejemplo.com" required
                        style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                </div>

                <div>
                    <label for="c-mensaje" style="font-weight: bold; display: block; margin-bottom: 5px;">
                        Mensaje o Consulta
                    </label>

                    <textarea id="c-mensaje" rows="4" placeholder="Escribe tu consulta aquí..." required
                        style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-family: inherit; resize: vertical;"></textarea>
                </div>

                <button type="submit" class="btn btn-primary full" style="cursor: pointer; font-weight: bold;">
                    Enviar Mensaje
                </button>
            </form>
        </section>
    </main>

    <footer class="footer">
        <p>CarpoolMatch CR - Proyecto Ambiente Web G2</p>
    </footer>

    <script>
        document.getElementById("formContacto").addEventListener("submit", (e) => {
            e.preventDefault();
            alert("¡Gracias por escribirnos! Tu consulta ha sido enviada al equipo de soporte de CarpoolMatch CR.");
            e.target.reset();
        });
    </script>

</body>

</html>