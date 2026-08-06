<?php

session_start();
include "conexion.php";

if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../login.html");
    exit();
}

$tipoUsuario = $_SESSION["tipo_usuario"];

if ($tipoUsuario != "Pasajero" && $tipoUsuario != "Ambos") {
    header("Location: ../dashboard.php?error=rol");
    exit();
}

if (!isset($_GET["id"])) {
    header("Location: ../viajes.php?error=sinviaje");
    exit();
}

$idViaje = $_GET["id"];
$idPasajero = $_SESSION["id_usuario"];

$consultaViaje = "SELECT id_conductor, asientos_disponibles, estado 
                  FROM viajes 
                  WHERE id_viaje = ?";

$stmtViaje = mysqli_prepare($conexion, $consultaViaje);
mysqli_stmt_bind_param($stmtViaje, "i", $idViaje);
mysqli_stmt_execute($stmtViaje);

$resultadoViaje = mysqli_stmt_get_result($stmtViaje);

if (mysqli_num_rows($resultadoViaje) == 0) {
    header("Location: ../viajes.php?error=noexiste");
    exit();
}

$viaje = mysqli_fetch_assoc($resultadoViaje);

if ($viaje["estado"] != "Activo") {
    header("Location: ../viajes.php?error=noactivo");
    exit();
}

if ($viaje["asientos_disponibles"] <= 0) {
    header("Location: ../viajes.php?error=sinasientos");
    exit();
}

if ($viaje["id_conductor"] == $idPasajero) {
    header("Location: ../viajes.php?error=propio");
    exit();
}

$consultaDuplicada = "SELECT id_solicitud 
                      FROM solicitudes 
                      WHERE id_viaje = ? AND id_pasajero = ?";

$stmtDuplicada = mysqli_prepare($conexion, $consultaDuplicada);
mysqli_stmt_bind_param($stmtDuplicada, "ii", $idViaje, $idPasajero);
mysqli_stmt_execute($stmtDuplicada);
mysqli_stmt_store_result($stmtDuplicada);

if (mysqli_stmt_num_rows($stmtDuplicada) > 0) {
    header("Location: ../viajes.php?error=duplicada");
    exit();
}

$consultaInsertar = "INSERT INTO solicitudes (id_viaje, id_pasajero, estado_solicitud) 
                     VALUES (?, ?, 'Pendiente')";

$stmtInsertar = mysqli_prepare($conexion, $consultaInsertar);
mysqli_stmt_bind_param($stmtInsertar, "ii", $idViaje, $idPasajero);

if (mysqli_stmt_execute($stmtInsertar)) {
    header("Location: ../solicitudes.php?solicitud=ok");
    exit();
} else {
    header("Location: ../viajes.php?error=bd");
    exit();
}

?>