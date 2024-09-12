<?php
// Mostrar todos los errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Iniciar sesión para manejar variables de sesión
session_start();

// Incluir el archivo de conexión
include '../conexión/conection.php';

// Crear la conexión utilizando la función conection()
$conn = conection(); // Esto reemplaza la conexión manual

// Validar la conexión a la base de datos
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Recibir datos del formulario
$user_nombre = $_POST['nombre'];
$user_apellidos = $_POST['apellidos'];
$user_email = $_POST['email'];
$user_telefono = $_POST['telefono'];
$user_direc = $_POST['direc'];
$user_contra = $_POST['contra'];
$user_puesto = $_POST['puesto'];
$user_link = $_POST['link'];

// ------------------------------------------------------------
// Realizar primero la inserción en la base de datos

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
if (!$stmt->execute()) {
    die("Error al ejecutar el procedimiento: " . $stmt->error);
}

// Obtener el resultado de la ejecución
$result = $stmt->get_result();
if ($result === false) {
    die("Error al obtener el resultado: " . $stmt->error);
}

$row = $result->fetch_assoc();
$com_estatus = $row['com_estatus'];

// Verificar el valor de retorno (0 = éxito, 1 = correo ya existe)
if ($com_estatus == '0') {
    // Aquí continúa el proceso de subida de archivo
    $stmt->close();
    echo "Usuario insertado exitosamente.";
      // Consulta SELECT para obtener el ID del usuario recién insertado
      $sql = "SELECT idUsuario FROM usuario WHERE usu_correo = ?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("s", $user_email);
      $stmt->execute();
      $result = $stmt->get_result();
      $row = $result->fetch_assoc();
      $last_id = $row['idUsuario'];

    
    // Obtener el último ID insertado (si es necesario para nombrar el archivo)
} elseif ($com_estatus == '1') {
    $stmt->close();
    die("El correo ya existe, no se pudo insertar el usuario.");
} else {
    $stmt->close();
    die("Error inesperado.");
}


// Cerrar la declaración SQL antes de proceder con la subida del archivo



// ------------------------------------------------------------
// Ahora manejar la subida del archivo (si la inserción fue exitosa)

    if (isset($_FILES['cv']) && $_FILES['cv']['error'] == 0) {
        // Directorio donde se guardará la imagen
        $target_dir = "../cv/";

        // Obtener la extensión del archivo
        $imageFileType = strtolower(pathinfo($_FILES["cv"]["name"], PATHINFO_EXTENSION));

        // Crear un nombre personalizado para la imagen (ejemplo: id_usuario_timestamp.extensión)
        $custom_file_name = $last_id. "." . $imageFileType;

        // Ruta completa con el nombre personalizado
        $target_file = $target_dir . $custom_file_name;

        // Verificación del archivo (si es una imagen)
        $check = getimagesize($_FILES["cv"]["tmp_name"]);
        if ($check !== false) {
            // Limitar el tamaño del archivo a 5MB
            if ($_FILES["cv"]["size"] > 5000000) {
                die("Lo siento, el archivo es muy grande.");
            }

            // Permitir solo archivos JPG, JPEG y PNG
            if ($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png") {
                die("Lo siento, solo se permiten archivos JPG, JPEG y PNG.");
            }

            // Verificar si el archivo ya existe
            if (file_exists($target_file)) {
                die("Lo siento, el archivo ya existe.");
            }

            // Si todo está bien, intentar mover el archivo
            if (move_uploaded_file($_FILES["cv"]["tmp_name"], $target_file)) {
                echo "El archivo " . htmlspecialchars($custom_file_name) . " ha sido subido exitosamente.";
            } else {
                die("Lo siento, hubo un error al subir tu archivo.");
            }
        } else {
            die("El archivo no es una imagen.");
        }
    } else {
        echo "No se subió ningún archivo.";
    }


// ------------------------------------------------------------

// Cerrar la conexión
$conn->close();

// Redirigir al usuario después de completar el proceso
header("Location: ../index.html");
exit();
?>
