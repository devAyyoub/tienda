<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado películas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <?php require 'bd_productos.php' ?>
</head>

<body>
    <div class="container">
        <h1>Listado de productos</h1>
    </div>

    <div>
        <table class="table table-striped table-hover">
            <thead class="table table-dark">
                <tr>
                    <th>ID Producto</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Descripción</th>
                    <th>Cantidad</th>
                    <th>Imagen</th>
                </tr>
            </thead>
            <tbody>
                <?php
                session_start();
                $usuario = $_SESSION["usuario"];
                ?>

                <div class="container">
                    <h2>Bienvenid@ <?php echo $usuario ?></h2>
                </div>
                <?php
                $sql = "SELECT * FROM productos";
                $resultado = $conexion->query($sql);

                while ($fila = $resultado->fetch_assoc()) {

                    echo "<tr>";
                    echo "<td>" . $fila['idProducto'] . "</td>";
                    echo "<td>" . $fila['nombreProducto'] . "</td>";
                    echo "<td>" . $fila['precio'] . "</td>";
                    echo "<td>" . $fila['descripcion'] . "</td>";
                    echo "<td>" . $fila['cantidad'] . "</td>";
                    echo "<td>";
                ?>
                    <img witdh="50" height="100" src="<?php echo $fila["imagen"] ?>">
                <?php
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>