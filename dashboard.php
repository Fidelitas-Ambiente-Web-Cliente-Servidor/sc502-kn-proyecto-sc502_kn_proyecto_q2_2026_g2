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
    <title>Dashboard - CarpoolMatch CR</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <header class="header">
        <div class="brand">
            <h1>CarpoolMatch CR</h1>
            <p>Panel principal</p>
        </div>

        <nav class="nav">
            <a href="index.html">Inicio</a>
            <a href="dashboard.php" class="nav-btn">Dashboard</a>
            <a href="#">Viajes</a>
            <a href="#">Solicitudes</a>
            <a href="#">Perfil</a>
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
                <span class="tag">Panel de control</span>

                <h2>Bienvenido a CarpoolMatch CR</h2>

                <p>
                    Desde este dashboard puedes revisar un resumen general de los viajes,
                    solicitudes, rutas disponibles y actividad reciente de la plataforma.
                </p>
            </div>

            <div class="dashboard-user">
                <strong>Usuario activo</strong>
                <span><?php echo $nombreUsuario . " - " . $tipoUsuario; ?></span>
            </div>
        </section>

        <section class="dashboard-stats">
            <article class="dashboard-card card">
                <span>Viajes disponibles</span>
                <strong>12</strong>
                <p>Rutas activas publicadas para hoy.</p>
            </article>

            <article class="dashboard-card card">
                <span>Solicitudes pendientes</span>
                <strong>4</strong>
                <p>Solicitudes esperando respuesta.</p>
            </article>

            <article class="dashboard-card card">
                <span>Rutas publicadas</span>
                <strong>6</strong>
                <p>Viajes creados por conductores.</p>
            </article>

            <article class="dashboard-card card">
                <span>Usuarios registrados</span>
                <strong>28</strong>
                <p>Personas activas en la plataforma.</p>
            </article>
        </section>

        <section class="dashboard-grid">

            <article class="card dashboard-section">
                <h3>Viajes recientes</h3>

                <div class="dashboard-list">

                    <div class="dashboard-item">
                        <div>
                            <strong>Heredia → Universidad Fidélitas</strong>
                            <p>Salida: 7:00 a.m. | Espacios disponibles: 3</p>
                        </div>

                        <span class="status">Disponible</span>
                    </div>

                    <div class="dashboard-item">
                        <div>
                            <strong>Alajuela → San José</strong>
                            <p>Salida: 6:30 a.m. | Espacios disponibles: 2</p>
                        </div>

                        <span class="status">Disponible</span>
                    </div>

                    <div class="dashboard-item">
                        <div>
                            <strong>San Pedro → Heredia</strong>
                            <p>Salida: 5:00 p.m. | Espacios disponibles: 4</p>
                        </div>

                        <span class="status">Disponible</span>
                    </div>

                </div>
            </article>

            <article class="card dashboard-section">
                <h3>Acciones rápidas</h3>

                <div class="quick-actions">
                    <a href="#" class="btn btn-primary full">Buscar viaje</a>
                    <a href="#" class="btn btn-secondary full">Publicar ruta</a>
                    <a href="#" class="btn btn-secondary full">Ver solicitudes</a>
                    <a href="#" class="btn btn-secondary full">Editar perfil</a>
                </div>
            </article>

        </section>

        <section class="card dashboard-section">
            <h3>Resumen de actividad</h3>

            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Ruta</th>
                        <th>Tipo</th>
                        <th>Estado</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td>31/07/2026</td>
                        <td>Heredia - San Pedro</td>
                        <td>Solicitud</td>
                        <td>Aprobada</td>
                    </tr>

                    <tr>
                        <td>31/07/2026</td>
                        <td>Alajuela - San José</td>
                        <td>Viaje publicado</td>
                        <td>Activo</td>
                    </tr>

                    <tr>
                        <td>30/07/2026</td>
                        <td>Cartago - San José</td>
                        <td>Solicitud</td>
                        <td>Pendiente</td>
                    </tr>

                    <tr>
                        <td>30/07/2026</td>
                        <td>Heredia - Fidélitas</td>
                        <td>Viaje reservado</td>
                        <td>Completado</td>
                    </tr>
                </tbody>
            </table>
        </section>

    </main>

    <footer class="footer">
        <p>CarpoolMatch CR - Dashboard del proyecto</p>
    </footer>

</body>

</html>