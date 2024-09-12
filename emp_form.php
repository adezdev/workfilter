<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style_emp.css">
    <title>Document</title>
</head>

<body>
    <div class="contForm">
        <h1>Formuario CRUD</h1>
        <form action="acction/insert.php" method="POST" class="contForm__form">
            <h3>Nombre:
                <input type="text" class="form__item" name="nombre">
            </h3>
            <h3>Descripción:
                <input type="text" class="form__item" name="des">
            </h3>
            <h3>Correo:
                <input type="text" class="form__item" name="correo">
            </h3>
            <h3>Contraseña:
                <input type="text" class="form__item" name="pass">
            </h3>
            <h3>Dirección:
                <input type="text" class="form__item" name="direc">
            </h3>
            <h3>Teléfono:
                <input type="text" class="form__item" name="tel">
            </h3>
            <h3>Sitio Web:
                <input type="text" class="form__item" name="web">
            </h3>
            <input type="submit" class="form__item form__item--btn" value="agregar">
        </form>
    </div>
    
</body>

</html>