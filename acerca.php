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
    <title>Acerca de Nosotros - CarpoolMatch CR</title>
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

    <main style="padding: 40px 20px; min-height: 70px;">
        <section class="container card"
            style="max-width: 800px; margin: 0 auto; padding: 40px; box-sizing: border-box; border-radius: 12px;">
            <h2 style="margin-top: 0; margin-bottom: 20px; color: #333; font-size: 2rem;">
                Acerca de Nosotros
            </h2>

            <span class="tag"
                style="background-color: #2ec4b6; color: white; padding: 6px 12px; border-radius: 4px; font-size: 0.85rem; display: inline-block; margin-bottom: 20px; font-weight: bold; letter-spacing: 0.5px;">
                Nuestra Misión
            </span>

            <div style="color: #4a5568; line-height: 1.7; font-size: 1.1rem;">
                <p style="margin-bottom: 20px;">
                    <strong>CarpoolMatch CR</strong> nació como un proyecto
                    enfocado en resolver de forma colaborativa los problemas
                    de movilidad urbana y estudiantil en Costa Rica.
                </p>

                <p style="margin-bottom: 0;">
                    Nuestra meta es conectar a personas que viajan
                    diariamente por las mismas rutas, ayudándoles a
                    compartir los gastos de combustible, reducir la huella
                    de carbono y disminuir el congestionamiento vial en
                    nuestras carreteras principales.
                </p>
            </div>
        </section>
    </main>

    <footer class="footer">
        <p>CarpoolMatch CR - Proyecto Ambiente Web G2</p>
    </footer>

</body>

</html>