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
    <title>Preguntas Frecuentes - CarpoolMatch CR</title>
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

            <h2
                style="margin-top: 0; margin-bottom: 30px; color: #333; font-size: 2rem; border-bottom: 2px solid #f0f2f5; padding-bottom: 15px;">
                Preguntas Frecuentes (FAQ)
            </h2>

            <div style="display: flex; flex-direction: column; gap: 30px;">
                <div class="faq-item" style="text-align: left;">
                    <h3 style="color: #2ec4b6; margin-top: 0; margin-bottom: 10px; font-size: 1.3rem;">
                        ¿Cómo funciona CarpoolMatch CR?
                    </h3>

                    <p style="color: #4a5568; line-height: 1.6; margin: 0;">
                        Es muy simple: los conductores publican sus rutas
                        programadas detallando los asientos disponibles, y
                        los pasajeros buscan viajes que coincidan con su
                        origen o destino para coordinar juntos.
                    </p>
                </div>

                <div class="faq-item" style="text-align: left;">
                    <h3 style="color: #2ec4b6; margin-top: 0; margin-bottom: 10px; font-size: 1.3rem;">
                        ¿Es seguro viajar con alguien de la plataforma?
                    </h3>

                    <p style="color: #4a5568; line-height: 1.6; margin: 0;">
                        La plataforma promueve una comunidad de confianza
                        mediante la verificación de perfiles y un sistema de
                        calificaciones mutuas que los usuarios completan
                        después de finalizar un trayecto.
                    </p>
                </div>

                <div class="faq-item" style="text-align: left;">
                    <h3 style="color: #2ec4b6; margin-top: 0; margin-bottom: 10px; font-size: 1.3rem;">
                        ¿Cómo se coordinan los gastos?
                    </h3>

                    <p style="color: #4a5568; line-height: 1.6; margin: 0;">
                        Los gastos estimados de combustible y peajes se calculan
                        de manera sugerida y se dividen de mutuo acuerdo
                        entre el conductor y los pasajeros antes de iniciar
                        el trayecto.
                    </p>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <p>CarpoolMatch CR - Proyecto Ambiente Web G2</p>
    </footer>

</body>

</html>