<?php
// Iniciar sesión para manejar variables de sesión
session_start();

// Incluir el archivo de conexión
include 'conection.php';

// Crear la conexión utilizando la función conection()
$conn = conection(); // Esto reemplaza la conexión manual

// Recibir datos del formulario
$user_nombre = $_POST['nombre'];
$user_apellidos = $_POST['apellidos'];
$user_email = $_POST['email'];
$user_telefono = $_POST['telefono'];
$user_direc = $_POST['direc'];
$user_contra = $_POST['contra'];
$user_puesto = $_POST['puesto'];
$user_link = $_POST['link'];

// ----------------------------------------------------------
$file = $_FILES["fileTest"]["name"]; //Nombre de nuestro archivo

$url_temp = $_FILES["fileTest"]["tmp_name"]; //Ruta temporal a donde se carga el archivo 

//dirname(_FILE_) nos otorga la ruta absoluta hasta el archivo en ejecución
$url_insert = dirname(__FILE__) . "/files"; //Carpeta donde subiremos nuestros archivos

//Ruta donde se guardara el archivo, usamos str_replace para reemplazar los "\" por "/"
$url_target = str_replace('\\', '/', $url_insert) . '/' . $file;

//Si la carpeta no existe, la creamos
if (!file_exists($url_insert)) {
    mkdir($url_insert, 0777, true);
};

//movemos el archivo de la carpeta temporal a la carpeta objetivo y verificamos si fue exitoso
if (move_uploaded_file($url_temp, $url_target)) {
    echo "El archivo " . htmlspecialchars(basename($file)) . " ha sido cargado con éxito.";
} else {
    echo "Ha habido un error al cargar tu archivo.";
}
// ----------------------------------------------------------

// Preparar la consulta SQL para el procedimiento almacenado
$sql = "CALL sp_InsUser(?, ?, ?, ?, ?, ?, ?, ?)";

// Preparar la declaración
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Error en la preparación de la consulta: " . $conn->error);
}

// Vincular los parámetros del formulario a la consulta
$stmt->bind_param(
    "ssssssss", // Tipos de datos: s = string, i = integer
    $user_nombre, 
    $user_apellidos, 
    $user_email, 
    $user_telefono, 
    $user_direc, 
    $user_contra, 
    $user_puesto, 
    $user_link
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
