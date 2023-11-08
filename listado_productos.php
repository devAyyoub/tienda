<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado películas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="./css/style.css" rel="stylesheet">
    <?php require './bd/bd_productos.php' ?>
    <?php require 'producto.php' ?>
</head>

<body>
    <div class="container">
        <h1>Listado de productos</h1>
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

                    $productos = [];

                    while ($fila = $resultado->fetch_assoc()) {
                        $nuevo_producto = new Producto(
                            $fila["idProducto"],
                            $fila["nombreProducto"],
                            $fila["precio"],
                            $fila["descripcion"],
                            $fila["cantidad"],
                            $fila["imagen"]
                        );
                        array_push($productos, $nuevo_producto);
                    }

                    foreach ($productos as $producto) {
                        echo "<tr class='table-info'>";
                        echo "<td>" . $producto->idProducto . "</td>";
                        echo "<td>" . $producto->nombreProducto . "</td>";
                        echo "<td>" . $producto->precio . "</td>";
                        echo "<td>" . $producto->descripcion . "</td>";
                        echo "<td>" . $producto->cantidad . "</td>";
                    ?>
                        <td><img witdh="50" height="100" src="<?php echo $producto->imagen ?>"></td>
                    <?php
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>