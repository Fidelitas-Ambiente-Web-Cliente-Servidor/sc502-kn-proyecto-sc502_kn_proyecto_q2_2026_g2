<?php

session_start();
include "conexion.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = trim($_POST["correo"]);
    $password = $_POST["password"];

    if (empty($correo) || empty($password)) {
        header("Location: ../login.html?error=campos");
        exit();
    }

    $consulta = "SELECT id_usuario, nombre, correo, telefono, tipo_usuario, contrasena, estado 
                 FROM usuarios 
                 WHERE correo = ?";

    $stmt = mysqli_prepare($conexion, $consulta);
    mysqli_stmt_bind_param($stmt, "s", $correo);
    mysqli_stmt_execute($stmt);

    $resultado = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($resultado) == 1) {
        $usuario = mysqli_fetch_assoc($resultado);

        if ($usuario["estado"] != "Activo") {
            header("Location: ../login.html?error=inactivo");
            exit();
        }

        if (password_verify($password, $usuario["contrasena"])) {
            $_SESSION["id_usuario"] = $usuario["id_usuario"];
            $_SESSION["nombre"] = $usuario["nombre"];
            $_SESSION["correo"] = $usuario["correo"];
            $_SESSION["telefono"] = $usuario["telefono"];
            $_SESSION["tipo_usuario"] = $usuario["tipo_usuario"];

            header("Location: ../dashboard.php");
            exit();
        } else {
            header("Location: ../login.html?error=credenciales");
            exit();
        }
    } else {
        header("Location: ../login.html?error=credenciales");
        exit();
    }
} else {
    header("Location: ../login.html");
    exit();
}

?>