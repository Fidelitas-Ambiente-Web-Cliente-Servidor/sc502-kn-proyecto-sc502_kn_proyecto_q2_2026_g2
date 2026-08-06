<?php

session_start();
include "conexion.php";

if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../login.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: ../calificaciones.php");
    exit();
}

$idEvaluador = $_SESSION["id_usuario"];
$tipoUsuario = $_SESSION["tipo_usuario"];

$datosViaje = $_POST["viaje"];
$puntaje = $_POST["puntaje"];
$comentario = trim($_POST["comentario"]);

if (empty($datosViaje) || empty($puntaje) || empty($comentario)) {
    header("Location: ../calificaciones.php?error=campos");
    exit();
}

$partes = explode("|", $datosViaje);

if (count($partes) != 2) {
    header("Location: ../calificaciones.php?error=campos");
    exit();
}

$idViaje = intval($partes[0]);
$idEvaluado = intval($partes[1]);
$puntaje = intval($puntaje);

if ($idViaje <= 0 || $idEvaluado <= 0) {
    header("Location: ../calificaciones.php?error=campos");
    exit();
}

if ($idEvaluador == $idEvaluado) {
    header("Location: ../calificaciones.php?error=propio");
    exit();
}

if ($puntaje < 1 || $puntaje > 5) {
    header("Location: ../calificaciones.php?error=puntaje");
    exit();
}

/*
    Validación de permiso:

    Pasajero:
    - Puede calificar al conductor si tiene una solicitud aprobada en ese viaje.

    Conductor:
    - Puede calificar al pasajero si ese pasajero tiene una solicitud aprobada en un viaje publicado por el conductor.

    Ambos:
    - Puede calificar en cualquiera de los dos casos.
*/

$puedeCalificar = false;

if ($tipoUsuario == "Pasajero" || $tipoUsuario == "Ambos") {
    $consultaPermisoPasajero = "SELECT solicitudes.id_solicitud
                                FROM solicitudes
                                INNER JOIN viajes ON solicitudes.id_viaje = viajes.id_viaje
                                WHERE solicitudes.id_viaje = ?
                                AND solicitudes.id_pasajero = ?
                                AND viajes.id_conductor = ?
                                AND solicitudes.estado_solicitud = 'Aprobada'";

    $stmtPermisoPasajero = mysqli_prepare($conexion, $consultaPermisoPasajero);
    mysqli_stmt_bind_param($stmtPermisoPasajero, "iii", $idViaje, $idEvaluador, $idEvaluado);
    mysqli_stmt_execute($stmtPermisoPasajero);
    mysqli_stmt_store_result($stmtPermisoPasajero);

    if (mysqli_stmt_num_rows($stmtPermisoPasajero) > 0) {
        $puedeCalificar = true;
    }
}

if (!$puedeCalificar && ($tipoUsuario == "Conductor" || $tipoUsuario == "Ambos")) {
    $consultaPermisoConductor = "SELECT solicitudes.id_solicitud
                                 FROM solicitudes
                                 INNER JOIN viajes ON solicitudes.id_viaje = viajes.id_viaje
                                 WHERE solicitudes.id_viaje = ?
                                 AND viajes.id_conductor = ?
                                 AND solicitudes.id_pasajero = ?
                                 AND solicitudes.estado_solicitud = 'Aprobada'";

    $stmtPermisoConductor = mysqli_prepare($conexion, $consultaPermisoConductor);
    mysqli_stmt_bind_param($stmtPermisoConductor, "iii", $idViaje, $idEvaluador, $idEvaluado);
    mysqli_stmt_execute($stmtPermisoConductor);
    mysqli_stmt_store_result($stmtPermisoConductor);

    if (mysqli_stmt_num_rows($stmtPermisoConductor) > 0) {
        $puedeCalificar = true;
    }
}

if (!$puedeCalificar) {
    header("Location: ../calificaciones.php?error=permiso");
    exit();
}

$consultaDuplicada = "SELECT id_calificacion
                      FROM calificaciones
                      WHERE id_evaluador = ?
                      AND id_evaluado = ?
                      AND id_viaje = ?";

$stmtDuplicada = mysqli_prepare($conexion, $consultaDuplicada);
mysqli_stmt_bind_param($stmtDuplicada, "iii", $idEvaluador, $idEvaluado, $idViaje);
mysqli_stmt_execute($stmtDuplicada);
mysqli_stmt_store_result($stmtDuplicada);

if (mysqli_stmt_num_rows($stmtDuplicada) > 0) {
    header("Location: ../calificaciones.php?error=duplicada");
    exit();
}

$consulta = "INSERT INTO calificaciones 
             (id_evaluador, id_evaluado, id_viaje, puntaje, comentario)
             VALUES (?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($conexion, $consulta);
mysqli_stmt_bind_param($stmt, "iiiis", $idEvaluador, $idEvaluado, $idViaje, $puntaje, $comentario);

if (mysqli_stmt_execute($stmt)) {
    $consultaPromedio = "SELECT AVG(puntaje) AS promedio
                         FROM calificaciones
                         WHERE id_evaluado = ?";

    $stmtPromedio = mysqli_prepare($conexion, $consultaPromedio);
    mysqli_stmt_bind_param($stmtPromedio, "i", $idEvaluado);
    mysqli_stmt_execute($stmtPromedio);

    $resultadoPromedio = mysqli_stmt_get_result($stmtPromedio);
    $promedio = mysqli_fetch_assoc($resultadoPromedio)["promedio"];

    $consultaActualizar = "UPDATE usuarios
                           SET reputacion = ?
                           WHERE id_usuario = ?";

    $stmtActualizar = mysqli_prepare($conexion, $consultaActualizar);
    mysqli_stmt_bind_param($stmtActualizar, "di", $promedio, $idEvaluado);
    mysqli_stmt_execute($stmtActualizar);

    header("Location: ../calificaciones.php?guardado=ok");
    exit();
} else {
    header("Location: ../calificaciones.php?error=bd");
    exit();
}

?>