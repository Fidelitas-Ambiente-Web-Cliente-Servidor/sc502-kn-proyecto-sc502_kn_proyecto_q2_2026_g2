<?php

include "conexion.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST["nombre"]);
    $correo = trim($_POST["correo"]);
    $telefono = trim($_POST["telefono"]);
    $tipoUsuario = trim($_POST["tipoUsuario"]);
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirmPassword"];

    if (
        empty($nombre) ||
        empty($correo) ||
        empty($telefono) ||
        empty($tipoUsuario) ||
        empty($password) ||
        empty($confirmPassword)
    ) {
        header("Location: ../registro.html?error=campos");
        exit();
    }

    if (!preg_match("/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/", $nombre)) {
        header("Location: ../registro.html?error=nombre");
        exit();
    }

    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../registro.html?error=correo");
        exit();
    }

    if (!preg_match("/^[0-9]{8}$/", $telefono)) {
        header("Location: ../registro.html?error=telefono");
        exit();
    }

    if ($tipoUsuario != "Conductor" && $tipoUsuario != "Pasajero" && $tipoUsuario != "Ambos") {
        header("Location: ../registro.html?error=tipo");
        exit();
    }

    if (strlen($password) < 6) {
        header("Location: ../registro.html?error=password");
        exit();
    }

    if ($password != $confirmPassword) {
        header("Location: ../registro.html?error=confirmacion");
        exit();
    }

    $consultaCorreo = "SELECT id_usuario FROM usuarios WHERE correo = ?";
    $stmtCorreo = mysqli_prepare($conexion, $consultaCorreo);
    mysqli_stmt_bind_param($stmtCorreo, "s", $correo);
    mysqli_stmt_execute($stmtCorreo);
    mysqli_stmt_store_result($stmtCorreo);

    if (mysqli_stmt_num_rows($stmtCorreo) > 0) {
        header("Location: ../registro.html?error=existe");
        exit();
    }

    $passwordSeguro = password_hash($password, PASSWORD_DEFAULT);

    $consulta = "INSERT INTO usuarios (nombre, correo, telefono, tipo_usuario, contrasena) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexion, $consulta);
    mysqli_stmt_bind_param($stmt, "sssss", $nombre, $correo, $telefono, $tipoUsuario, $passwordSeguro);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: ../login.html?registro=ok");
        exit();
    } else {
        header("Location: ../registro.html?error=bd");
        exit();
    }
} else {
    header("Location: ../registro.html");
    exit();
}

?>