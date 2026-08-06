<?php

session_start();
include "conexion.php";

if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../login.html");
    exit();
}

$tipoUsuario = $_SESSION["tipo_usuario"];

if ($tipoUsuario != "Conductor" && $tipoUsuario != "Ambos") {
    header("Location: ../dashboard.php?error=rol");
    exit();
}

if (!isset($_GET["id"]) || !isset($_GET["estado"])) {
    header("Location: ../solicitudes-recibidas.php?error=datos");
    exit();
}

$idSolicitud = $_GET["id"];
$nuevoEstado = $_GET["estado"];
$idConductor = $_SESSION["id_usuario"];

if ($nuevoEstado != "Aprobada" && $nuevoEstado != "Rechazada") {
    header("Location: ../solicitudes-recibidas.php?error=datos");
    exit();
}

$consultaValidar = "SELECT solicitudes.id_solicitud
                    FROM solicitudes
                    INNER JOIN viajes ON solicitudes.id_viaje = viajes.id_viaje
                    WHERE solicitudes.id_solicitud = ?
                    AND viajes.id_conductor = ?";

$stmtValidar = mysqli_prepare($conexion, $consultaValidar);
mysqli_stmt_bind_param($stmtValidar, "ii", $idSolicitud, $idConductor);
mysqli_stmt_execute($stmtValidar);
mysqli_stmt_store_result($stmtValidar);

if (mysqli_stmt_num_rows($stmtValidar) == 0) {
    header("Location: ../solicitudes-recibidas.php?error=permiso");
    exit();
}

$consultaActualizar = "UPDATE solicitudes
                       SET estado_solicitud = ?
                       WHERE id_solicitud = ?";

$stmtActualizar = mysqli_prepare($conexion, $consultaActualizar);
mysqli_stmt_bind_param($stmtActualizar, "si", $nuevoEstado, $idSolicitud);

if (mysqli_stmt_execute($stmtActualizar)) {
    header("Location: ../solicitudes-recibidas.php?actualizado=ok");
    exit();
} else {
    header("Location: ../solicitudes-recibidas.php?error=bd");
    exit();
}

?>