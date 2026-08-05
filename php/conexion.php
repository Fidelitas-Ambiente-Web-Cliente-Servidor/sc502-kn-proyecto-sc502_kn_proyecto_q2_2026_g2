<?php

include "config_local.php";

$conexion = mysqli_connect($servidor, $usuario, $password, $baseDatos);

if (!$conexion) {
    die("Error de conexion: " . mysqli_connect_error());
}

mysqli_set_charset($conexion, "utf8");

?>