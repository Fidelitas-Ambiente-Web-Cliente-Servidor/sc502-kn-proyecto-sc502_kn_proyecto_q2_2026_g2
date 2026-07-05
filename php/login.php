<?php

session_start();

include("conexion.php");

$correo = $_POST['correo'];
$contrasena = $_POST['contrasena'];

$sql = "SELECT * FROM usuarios WHERE correo='$correo'";

$resultado = $conn->query($sql);

if($resultado->num_rows > 0){

    $usuario = $resultado->fetch_assoc();

    if(password_verify($contrasena,$usuario['contrasena'])){

        $_SESSION['usuario'] = $usuario['nombre'];

        header("Location: ../index.html");

    }else{

        echo "<script>
        alert('Contraseña incorrecta');
        history.back();
        </script>";

    }

}else{

    echo "<script>
    alert('Usuario no encontrado');
    history.back();
    </script>";

}

$conn->close();

?>