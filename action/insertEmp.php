<?php
// Iniciar sesión para manejar variables de sesión
session_start();

// Incluir el archivo de conexión
include '../conexión/conection.php';

// Crear la conexión utilizando la función conection()
$conn = conection(); // Esto reemplaza la conexión manual

// Recibir datos del formulario
$user_nombre = $_POST['nombre'];
$user_descripcion = $_POST['descripcion'];
$user_email = $_POST['email'];
$user_direc = $_POST['direc'];
$user_telefono = $_POST['telefono'];
$user_link = $_POST['link'];
$user_contra = $_POST['contra'];


// Preparar la consulta SQL para el procedimiento almacenado
$sql = "CALL sp_InsEmpresa(?, ?, ?, ?, ?, ?, ?)";

// Preparar la declaración
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Error en la preparación de la consulta: " . $conn->error);
}

// Vincular los parámetros del formulario a la consulta
$stmt->bind_param(
    "sssssss", // Tipos de datos: s = string, i = integer
    $user_nombre, 
    $user_descripcion, 
    $user_email, 
    $user_contra, 
    $user_direc, 
    $user_telefono, 
    $user_link, 
);

// Ejecutar la consulta
$stmt->execute();

// Obtener el resultado de la ejecución
$result = $stmt->get_result();
if ($result === false) {
    die("Error al ejecutar el procedimiento: " . $stmt->error);
}

// Obtener el valor devuelto por el procedimiento (0 o 1)
$row = $result->fetch_assoc();
$com_estatus = $row['com_estatus'];

// Verificar el valor de retorno (0 = éxito, 1 = correo ya existe)
if ($com_estatus == '0') {
    echo "Usuario insertado exitosamente.";
    header("Location: ../index.html");
} elseif ($com_estatus == '1') {
    echo "El correo ya existe, no se pudo insertar el usuario.";
} else {
    echo "Error inesperado.";
}

// Cerrar la declaración y la conexión
$stmt->close();
$conn->close();
?>
