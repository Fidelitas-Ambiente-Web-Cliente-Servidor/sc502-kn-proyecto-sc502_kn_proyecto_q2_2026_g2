<?php

$servidor = "localhost";
$usuario = "root";
$password = "";
$baseDatos = "carpoolmatch";

$conexion = mysqli_connect($servidor, $usuario, $password, $baseDatos);

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

mysqli_set_charset($conexion, "utf8");

?>