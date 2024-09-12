<?php
// Iniciar sesión para manejar variables de sesión
session_start();

// Incluir el archivo de conexión
include '../conexión/conection.php';

// Crear la conexión utilizando la función conection()
$conn = conection(); // Esto reemplaza la conexión manual

// Recibir datos del formulario
$user_nombre = $_POST['email'];
$user_contraseña = $_POST['password'];

// Preparar la consulta SQL para evitar inyecciones SQL en la tabla `Usuario`
$sql = "SELECT usu_password FROM usuario WHERE usu_correo = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_nombre);
$stmt->execute();
$stmt->store_result();

// Verificar si el usuario (candidato) existe
if ($stmt->num_rows > 0) {
    // Obtener la contraseña de la base de datos
    $stmt->bind_result($db_password);
    $stmt->fetch();

    // Verificar si la contraseña coincide
    if ($user_contraseña === $db_password) {
        // Contraseña correcta, iniciar sesión como candidato
        $_SESSION['username'] = $user_nombre; // Puedes guardar el nombre de usuario en la sesión
        //header("Location: land.php"); // Redirigir a land.php o alguna página específica de candidatos
        echo "candidato.";
        exit();
    } else {
        // Contraseña incorrecta
        echo "Contraseña incorrecta.";
    }
} else {
    // Si no se encuentra en la tabla Usuario, buscar en la tabla Empresa
    $sql = "SELECT emp_password FROM empresa WHERE emp_correo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user_nombre);
    $stmt->execute();
    $stmt->store_result();

    // Verificar si el usuario (empresa) existe
    if ($stmt->num_rows > 0) {
        // Obtener la contraseña de la base de datos
        $stmt->bind_result($db_password);
        $stmt->fetch();

        // Verificar si la contraseña coincide
        if ($user_contraseña === $db_password) {
            // Contraseña correcta, iniciar sesión como empresa
            $_SESSION['username'] = $user_nombre; // Puedes guardar el nombre de usuario en la sesión
            // header("Location: land.php"); // Redirigir a land.php o alguna página específica de empresas
            echo "empresa.";
            exit();
        } else {
            // Contraseña incorrecta
            echo "Contraseña incorrecta.";
        }
    } else {
        // Usuario no encontrado ni en Usuario ni en Empresa
        echo "Usuario no encontrado.";
    }
}

// Cerrar la conexión
$stmt->close();
$conn->close();
?>
