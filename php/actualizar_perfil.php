<?php

session_start();
include "conexion.php";

if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../login.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idUsuario = $_SESSION["id_usuario"];
    $nombre = trim($_POST["nombre"]);
    $telefono = trim($_POST["telefono"]);
    $tipoUsuario = trim($_POST["tipoUsuario"]);

    if (empty($nombre) || empty($telefono) || empty($tipoUsuario)) {
        header("Location: ../perfil.php?error=campos");
        exit();
    }

    if (!preg_match("/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/", $nombre)) {
        header("Location: ../perfil.php?error=nombre");
        exit();
    }

    if (!preg_match("/^[0-9]{8}$/", $telefono)) {
        header("Location: ../perfil.php?error=telefono");
        exit();
    }

    if ($tipoUsuario != "Conductor" && $tipoUsuario != "Pasajero" && $tipoUsuario != "Ambos") {
        header("Location: ../perfil.php?error=tipo");
        exit();
    }

    $consulta = "UPDATE usuarios
                 SET nombre = ?, telefono = ?, tipo_usuario = ?
                 WHERE id_usuario = ?";

    $stmt = mysqli_prepare($conexion, $consulta);
    mysqli_stmt_bind_param($stmt, "sssi", $nombre, $telefono, $tipoUsuario, $idUsuario);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION["nombre"] = $nombre;
        $_SESSION["telefono"] = $telefono;
        $_SESSION["tipo_usuario"] = $tipoUsuario;

        header("Location: ../perfil.php?actualizado=ok");
        exit();
    } else {
        header("Location: ../perfil.php?error=bd");
        exit();
    }
} else {
    header("Location: ../perfil.php");
    exit();
}

?>