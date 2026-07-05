<?php

include("conexion.php");

$nombre = $_POST['nombre'];
$correo = $_POST['correo'];
$telefono = $_POST['telefono'];
$tipo = $_POST['tipoUsuario'];
$contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);

$sql = "INSERT INTO usuarios(nombre,correo,telefono,tipo_usuario,contrasena)
VALUES('$nombre','$correo','$telefono','$tipo','$contrasena')";

if($conn->query($sql)==TRUE){

echo "<script>
alert('Usuario registrado correctamente');
window.location='../login.html';
</script>";

}else{

echo "<script>
alert('Ese correo ya está registrado');
history.back();
</script>";

}

$conn->close();

?>