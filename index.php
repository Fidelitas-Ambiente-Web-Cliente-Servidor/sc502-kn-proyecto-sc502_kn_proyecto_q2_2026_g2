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
    <title>CarpoolMatch CR - Inicio</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <header class="header">
        <div class="brand">
            <h1>CarpoolMatch CR</h1>
            <p>Viajes compartidos para estudiantes y trabajadores</p>
        </div>

        <nav class="nav">
            <a href="index.php" class="nav-btn">Inicio</a>

            <div class="menu-dropdown">
                <span class="menu-btn">Información ▾</span>

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

    <main>

        <section class="hero container">

            <div class="hero-text card">
                <span class="tag">Proyecto universitario</span>

                <h2>Compartir viajes de forma más organizada y segura</h2>

                <p>
                    CarpoolMatch CR es una aplicación web pensada para conectar personas que realizan rutas similares,
                    permitiendo publicar viajes, solicitar ride y administrar la actividad desde una plataforma centralizada.
                </p>

                <div class="actions">

                    <?php if ($haySesion) { ?>

                        <a href="dashboard.php" class="btn btn-primary">Ir al dashboard</a>

                        <?php if ($puedeBuscarViajes) { ?>
                            <a href="viajes.php" class="btn btn-secondary">Buscar viajes</a>
                        <?php } ?>

                        <?php if ($puedePublicarViajes) { ?>
                            <a href="publicar-viaje.php" class="btn btn-secondary">Publicar viaje</a>
                        <?php } ?>

                    <?php } else { ?>

                        <a href="registro.html" class="btn btn-primary">Crear cuenta</a>
                        <a href="login.html" class="btn btn-secondary">Iniciar sesión</a>

                    <?php } ?>

                </div>

                <div class="stats">
                    <div>
                        <strong>4</strong>
                        <span>Módulos principales</span>
                    </div>

                    <div>
                        <strong>100%</strong>
                        <span>Con base de datos</span>
                    </div>

                    <div>
                        <strong>CR</strong>
                        <span>Enfoque local</span>
                    </div>
                </div>
            </div>

            <div class="trip-card card">
                <div class="trip-top">
                    <span class="trip-label">Ruta destacada</span>
                    <span class="status">Activo</span>
                </div>

                <div class="route-box">
                    <div class="route-item">
                        <span class="dot blue"></span>
                        <div>
                            <h3>Heredia Centro</h3>
                            <p>Punto de salida</p>
                        </div>
                    </div>

                    <div class="line"></div>

                    <div class="route-item">
                        <span class="dot green"></span>
                        <div>
                            <h3>Universidad Fidélitas</h3>
                            <p>Destino</p>
                        </div>
                    </div>
                </div>

                <div class="trip-data">
                    <div>
                        <span>Fecha</span>
                        <strong>05/08/2026</strong>
                    </div>

                    <div>
                        <span>Espacios</span>
                        <strong>3 disponibles</strong>
                    </div>
                </div>

                <?php if ($haySesion && $puedeBuscarViajes) { ?>
                    <a href="viajes.php" class="btn btn-primary trip-btn">Ver viajes</a>
                <?php } else if ($haySesion && $puedePublicarViajes) { ?>
                    <a href="publicar-viaje.php" class="btn btn-primary trip-btn">Publicar viaje</a>
                <?php } else { ?>
                    <a href="login.html" class="btn btn-primary trip-btn">Iniciar sesión</a>
                <?php } ?>
            </div>

        </section>

        <section class="search container card">
            <h2>Buscar rutas compartidas</h2>

            <p>
                Ingrese un punto de salida y un destino para consultar viajes disponibles dentro de la plataforma.
            </p>

            <form class="search-form" action="<?php echo ($haySesion && $puedeBuscarViajes) ? 'viajes.php' : 'login.html'; ?>" method="GET">
                <input type="text" name="salida" placeholder="Punto de salida">
                <input type="text" name="destino" placeholder="Destino">

                <button type="submit" class="btn btn-primary">
                    Buscar
                </button>
            </form>
        </section>

        <section class="container">
            <div class="section-title">
                <span>Funcionalidades</span>
                <h2>¿Qué puede hacer el sistema?</h2>
            </div>

            <div class="grid-3">
                <div class="info-card card">
                    <div class="number">1</div>
                    <h3>Para pasajeros</h3>
                    <p>
                        Permite buscar viajes disponibles, enviar solicitudes y revisar el estado de cada ride solicitado.
                    </p>
                </div>

                <div class="info-card card">
                    <div class="number">2</div>
                    <h3>Para conductores</h3>
                    <p>
                        Facilita publicar rutas, indicar espacios disponibles y gestionar las solicitudes recibidas.
                    </p>
                </div>

                <div class="info-card card">
                    <div class="number">3</div>
                    <h3>Control de actividad</h3>
                    <p>
                        El usuario puede consultar historial, calificaciones y reputación registrada en la base de datos.
                    </p>
                </div>
            </div>
        </section>

        <section class="container">
            <div class="section-title">
                <span>Módulos</span>
                <h2>Módulos actuales</h2>
            </div>

            <div class="grid-4">
                <div class="simple-card card">
                    <h3>Usuarios</h3>
                    <p>
                        Registro, inicio de sesión, perfil y cierre de sesión mediante PHP.
                    </p>
                </div>

                <div class="simple-card card">
                    <h3>Viajes</h3>
                    <p>
                        Publicación y búsqueda de rutas disponibles con información almacenada en MySQL.
                    </p>
                </div>

                <div class="simple-card card">
                    <h3>Solicitudes</h3>
                    <p>
                        Envío de solicitudes de ride y administración de aprobación o rechazo.
                    </p>
                </div>

                <div class="simple-card card">
                    <h3>Calificaciones</h3>
                    <p>
                        Registro de puntajes, comentarios y actualización de reputación de usuarios.
                    </p>
                </div>
            </div>
        </section>

        <section class="cta container">
            <div class="cta-box">
                <span class="tag">Problema que resuelve</span>

                <h2>Una alternativa a coordinar rides por mensajes sueltos</h2>

                <p>
                    Muchas personas coordinan viajes compartidos por grupos de WhatsApp o conversaciones informales.
                    Esto puede generar desorden, falta de seguimiento y poca claridad sobre quién solicitó un espacio.
                    Con CarpoolMatch CR, la información queda registrada en una base de datos y puede consultarse desde
                    diferentes módulos.
                </p>

                <div class="actions">
                    <?php if ($haySesion) { ?>

                        <a href="dashboard.php" class="btn btn-primary">Entrar al sistema</a>
                        <a href="historial.php" class="btn btn-secondary">Ver historial</a>

                    <?php } else { ?>

                        <a href="registro.html" class="btn btn-primary">Crear cuenta</a>
                        <a href="login.html" class="btn btn-secondary">Iniciar sesión</a>

                    <?php } ?>
                </div>
            </div>
        </section>

    </main>

    <footer class="footer">
        <p>CarpoolMatch CR - Proyecto Final SC-502</p>
    </footer>

</body>

</html>