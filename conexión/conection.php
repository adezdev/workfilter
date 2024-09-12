<?php

function conection()  {
    $host = "localhost"; // o la dirección IP del servidor MySQL
    $usuario = "root"; // tu usuario de MySQL
    $contrasena = ""; // tu contraseña de MySQL
    $basededatos = "workfilter"; // el nombre de tu base de datos

    // Crear conexión
    $conn = new mysqli($host, $usuario, $contrasena, $basededatos);

    // Verificar conexión
    if ($conn->connect_error) {
        die("La conexión falló: " . $conn->connect_error);
    }
    return $conn;
}

?>
