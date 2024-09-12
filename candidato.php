<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styleVacante.css">
    <title>Document</title>
</head>

<body>
    <header class="header">
        <nav class="nav">
            <div class="nav_cont_logo">
                <img class="logo__item" src="imagenes/image.png" alt="">
            </div>
            <div class="nav_cont_lista">
                <ul class="nav__lista">
                    <li class="lista__item"><a href="#">Inicio</a></li>
                    <li class="lista__item"><a href="login.html">login in</a></li>
                    <li class="lista__item"><a href="select_sigin.html">sign in</a></li>
                </ul>
            </div>
        </nav>
    </header>
    


    <h1>Datos de la Tabla</h1>


    <div class="conten-tabla">
            <?php
        // Iniciar sesión para manejar variables de sesión
        session_start();

        // Incluir el archivo de conexión
        include 'conexión/conection.php';

        // Crear la conexión utilizando la función conection()
        $conn = conection(); // Esto reemplaza la conexión manual


        // Consulta SQL
        $sql = "SELECT * FROM vw_vacantes"; // Reemplaza con el nombre de tu tabla
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Salida de la tabla
            echo "<table>";
            
            // Obtener los nombres de las columnas
            $fields = $result->fetch_fields();
            echo "<tr>";
            foreach ($fields as $field) {
                echo "<th>" . $field->name . "</th>";
            }
            echo "</tr>";
            
            // Mostrar los datos
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                foreach ($row as $value) {
                    echo "<td>" . htmlspecialchars($value) . "</td>";
                }
                echo "</tr>";
            }
            
            echo "</table>";
        } else {
            echo "0 resultados";
        }

        $conn->close();
        ?>
    </div>
    
    <footer class="pieDePagina">
        <div class="pieDePagina__info" ><h4 style="background-color: #5cb85c;" >información de la empresa</h4>
            <p style="background-color: #5cb85c;">WordFilter Correo:Contact@WordFilter.com</p>
        
        </div>
        <div class="pieDePagina_info pieDePagina_info--espace">
            <img src="imagenes/facebook.png" alt="Facebook" class="info__Img">
            <img src="imagenes/instagram.png" alt="Instagram" class="info__Img">
            <img src="imagenes/gorjeo.png" alt="X" class="info__Img">
        </div>
        <div class="pieDePagina__info"> Copyrigth© <br> SomosTuzos</div>
        
    </footer>
</body>

</html>