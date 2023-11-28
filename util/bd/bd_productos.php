<?php
    $_servidor = 'localhost';
    $_usuario = 'root';
    $_contrasena = '';
    $_base_de_datos = 'db_tienda';

    $conexion = new Mysqli($_servidor, $_usuario, $_contrasena, $_base_de_datos);

    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }
?>