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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idConductor = $_SESSION["id_usuario"];
    $puntoSalida = trim($_POST["puntoSalida"]);
    $destino = trim($_POST["destino"]);
    $fechaHora = trim($_POST["fechaHora"]);
    $asientos = trim($_POST["asientos"]);
    $observaciones = trim($_POST["observaciones"]);

    if (
        empty($puntoSalida) ||
        empty($destino) ||
        empty($fechaHora) ||
        empty($asientos)
    ) {
        header("Location: ../publicar-viaje.php?error=campos");
        exit();
    }

    if ($asientos <= 0) {
        header("Location: ../publicar-viaje.php?error=asientos");
        exit();
    }

    $consulta = "INSERT INTO viajes 
                (id_conductor, punto_salida, destino, fecha_hora, asientos_disponibles, observaciones) 
                VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conexion, $consulta);
    mysqli_stmt_bind_param($stmt, "isssis", $idConductor, $puntoSalida, $destino, $fechaHora, $asientos, $observaciones);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: ../solicitudes-recibidas.php?publicado=ok");
        exit();
    } else {
        header("Location: ../publicar-viaje.php?error=bd");
        exit();
    }
} else {
    header("Location: ../publicar-viaje.php");
    exit();
}

?>