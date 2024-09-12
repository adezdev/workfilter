<?php
include("../conexión/conection.php");
$con = conection(); 
$id = null;
$nombre = $_POST['nombre'];
$des = $_POST['des'];
$correo = $_POST['correo'];
$pass = $_POST['pass'];
$direc = $_POST['direc'];
$tel = $_POST['tel'];
$web = $_POST['web'];

$sql = "call sp_InsEmpresa('$nombre', '$des', '$correo', '$pass','$direc','$tel','$web')";

$query = mysqli_query($con, $sql);

if ($query) {
    Header("Location: ../index.html");
} else {
    echo "Error: " . mysqli_error($con);
}
?>