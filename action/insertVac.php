<?php
// Iniciar sesión para manejar variables de sesión
session_start();

// Incluir el archivo de conexión
include '../conexión/conection.php';

// Crear la conexión utilizando la función conection()
$conn = conection(); // Esto reemplaza la conexión manual

// Recibir datos del formulario
$user_titulo = $_POST['titulo'];
$user_descripcion = $_POST['descripcion'];
$user_requisitos = $_POST['requisitos'];
$user_salario = $_POST['salario'];
$user_direccion = $_POST['direccion'];
$user_id= $_SESSION['user_id'];


// Preparar la consulta SQL para el procedimiento almacenado
$sql = "CALL sp_InsVacante(?, ?, ?, ?, ?, ?)";

// Preparar la declaración
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Error en la preparación de la consulta: " . $conn->error);
}

// Vincular los parámetros del formulario a la consulta
$stmt->bind_param(
    "ssssss", // Tipos de datos: s = string, i = integer
    $user_titulo, 
    $user_descripcion, 
    $user_requisitos, 
    $user_salario, 
    $user_direccion, 
    $user_id, 
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
    //header("Location: ../index.html");
} elseif ($com_estatus == '1') {
    echo "El correo ya existe, no se pudo insertar el usuario.";
} else {
    echo "Error inesperado.";
}

// Cerrar la declaración y la conexión
$stmt->close();
$conn->close();
?>
