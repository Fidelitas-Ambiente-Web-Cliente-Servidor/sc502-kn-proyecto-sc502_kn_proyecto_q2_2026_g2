<?php

session_start();
include "conexion.php";

if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../login.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idEvaluador = $_SESSION["id_usuario"];
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

    $idViaje = $partes[0];
    $idEvaluado = $partes[1];

    if ($puntaje < 1 || $puntaje > 5) {
        header("Location: ../calificaciones.php?error=puntaje");
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
} else {
    header("Location: ../calificaciones.php");
    exit();
}

?>