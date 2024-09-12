<?php
// Iniciar sesión para manejar variables de sesión
session_start();

// Incluir el archivo de conexión
include '../conexión/conection.php';

// Crear la conexión utilizando la función conection()
$conn = conection();

// Recibir datos del formulario
$user_nombre = $_POST['email'];
$user_contraseña = $_POST['password'];

// Preparar la consulta SQL para evitar inyecciones SQL en la tabla `Usuario`
$sql = "SELECT idUsuario, usu_password FROM usuario WHERE usu_correo = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_nombre);
$stmt->execute();
$stmt->store_result();

// Verificar si el usuario (candidato) existe
if ($stmt->num_rows > 0) {
    // Obtener la contraseña y el ID de la base de datos
    $stmt->bind_result($user_id, $db_password);
    $stmt->fetch();

    // Verificar si la contraseña coincide
    if ($user_contraseña === $db_password) {
        // Contraseña correcta, iniciar sesión como candidato
        $_SESSION['username'] = $user_nombre; // Guardar el correo
        $_SESSION['user_id'] = $user_id;      // Guardar el ID del usuario
        echo "Candidato autenticado.";
        //header("location: ../vacantes_usuario.php");
        //header("Location: land.php"); // Redirigir a la página deseada
        exit();
    } else {
        echo "Contraseña incorrecta.";
        header("location: validate.php");
    }
} else {
    // Si no se encuentra en la tabla Usuario, buscar en la tabla Empresa
    $sql = "SELECT idEmpresa, emp_password FROM empresa WHERE emp_correo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user_nombre);
    $stmt->execute();
    $stmt->store_result();

    // Verificar si el usuario (empresa) existe
    if ($stmt->num_rows > 0) {
        // Obtener la contraseña y el ID de la base de datos
        $stmt->bind_result($empresa_id, $db_password);
        $stmt->fetch();

        // Verificar si la contraseña coincide
        if ($user_contraseña === $db_password) {
            // Contraseña correcta, iniciar sesión como empresa
            $_SESSION['username'] = $user_nombre;  // Guardar el correo
            $_SESSION['user_id'] = $empresa_id; // Guardar el ID de la empresa
            echo "Empresa autenticada.";
            //header("Location: ../vacante.html"); // Redirigir a la página deseada
            exit();
        } else {
            echo "Contraseña incorrecta.";
            header("location: validate.php");
        }
    } else {
        echo "Usuario no encontrado.";
    }
}

// Cerrar la conexión
$stmt->close();
$conn->close();

?>
