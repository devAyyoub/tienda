<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cesta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="./styles/style.css" rel="stylesheet">
    <?php require '../util/bd/bd_productos.php' ?>
    <?php require '../util/objetos/productoCesta.php' ?>
    <?php require '../util/objetos/producto.php' ?>
    <link rel="shortcut icon" href="./img/grow-shop.png" />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>

<body>
    <?php
    session_start();
    if (isset($_SESSION["usuario"])) {
        $usuario = $_SESSION["usuario"];
        $rol = $_SESSION["rol"];
    } else {
        $_SESSION["usuario"] = "invitado";
        $usuario = $_SESSION["usuario"];
        $_SESSION["rol"] = "cliente";
        $rol = $_SESSION["rol"];
        header("Location: ./sesiones/iniciar_sesion.php");
    }
    ?>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="listado_productos.php">Ayyoub's Market</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="listado_productos.php"><b>Productos</b></a>
                    </li>
                    <li class="nav-item">
                        <?php
                        if ($rol == "admin") {
                            echo '<li class="nav-item">';
                            echo '<a class="nav-link" href="productos.php"><b>Insertar producto</b></a>';
                            echo '</li>';
                        }
                        ?>
                    </li>
                    <li>
                        <?php
                        if ($rol == "admin") {
                            echo '<li class="nav-item">';
                            echo '<a class="nav-link" href="modificarUsuarios.php"><b>Modificar usuarios</b></a>';
                            echo '</li>';
                        }
                        ?>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="catalogo.php" aria-disabled="true"><b>Catálogo</b></a>
                    </li>
                    <li class="nav-item">
                        <?php
                        if (isset($_SESSION['usuario'])) {
                            echo '<a class="nav-link" href="./sesiones/cerrar_sesion.php"><b>Cerrar sesión</b></a>';
                        } else {
                            echo '<a class="nav-link" href="./sesiones/iniciar_sesion.php">Iniciar sesión</a>';
                        }
                        ?>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["delete"])) {
            $productocesta = $_POST["productocesta"];

            // Obtener la cantidad del producto en la cesta
            $sqlCantidad = "SELECT cantidad FROM productocestas WHERE IdCesta IN (SELECT IdCesta FROM cestas WHERE usuario='$usuario') AND idProducto='$productocesta'";
            $resultadoCantidad = $conexion->query($sqlCantidad);
            $cantidadEliminada = $resultadoCantidad->fetch_assoc()["cantidad"];

            // Eliminar el producto de la cesta
            $sqlDelete = "DELETE FROM productocestas WHERE IdCesta IN (SELECT IdCesta FROM cestas WHERE usuario='$usuario') AND idProducto='$productocesta'";
            if ($conexion->query($sqlDelete)) {
                echo "Producto en la cesta eliminado correctamente";
                // Actualizar la cantidad del producto en la tabla de productos
                $sqlUpdate = "UPDATE productos SET cantidad = cantidad + $cantidadEliminada WHERE idProducto = '$productocesta'";
                $conexion->query($sqlUpdate);
            } else {
                echo "Error al eliminar el producto de la cesta: " . $conexion->error;
            }
        }
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["buy"])) {
                // Insertar un nuevo pedido
                $sqlInsertPedido = "INSERT INTO pedidos (usuario, precioTotal, fechaPedido) 
                                    SELECT '$usuario', SUM(productos.precio * productocestas.cantidad), NOW()
                                    FROM productocestas 
                                    INNER JOIN productos ON productocestas.idProducto = productos.idProducto 
                                    WHERE productocestas.idCesta IN (SELECT idCesta FROM cestas WHERE usuario='$usuario')";

                if ($conexion->query($sqlInsertPedido)) {
                    // Obtener el ID autogenerado del pedido
                    $idPedido = $conexion->insert_id;

                    // Insertar líneas de pedido en la tabla lineaspedidos
                    $sqlInsertLineasPedido = "INSERT INTO lineaspedidos (idProducto, idPedido, precioUnitario, cantidad) 
                            SELECT productocestas.idProducto, $idPedido, productos.precio, productocestas.cantidad
                            FROM productocestas 
                            INNER JOIN productos ON productocestas.idProducto = productos.idProducto 
                            WHERE productocestas.idCesta IN (SELECT idCesta FROM cestas WHERE usuario='$usuario')";

                    if ($conexion->query($sqlInsertLineasPedido)) {
                        // Eliminar todos los productos de la cesta
                        $sqlVaciarCesta = "DELETE FROM productocestas WHERE idCesta IN (SELECT idCesta FROM cestas WHERE usuario='$usuario')";
                        $conexion->query($sqlVaciarCesta);

                        echo "Pedido realizado correctamente";
                    } else {
                        echo "Error al insertar líneas de pedido: " . $conexion->error;
                    }
                } else {
                    echo "Error al realizar el pedido: " . $conexion->error;
                }
            }
        }
    }
    ?>

    <?php
    //productoscestas
    $sql = "SELECT * FROM productocestas where idCesta = (SELECT idCesta FROM cestas WHERE usuario='$usuario')";
    $resultado = $conexion->query($sql);

    $productoscesta = [];

    while ($fila = $resultado->fetch_assoc()) {
        $nuevo_productocesta = new Productocesta(
            $fila["idProducto"],
            $fila["idCesta"],
            $fila["cantidad"],
        );
        array_push($productoscesta, $nuevo_productocesta);
    }

    //productos
    $sql2 = "SELECT * FROM productos";
    $resultado = $conexion->query($sql2);

    $productos = [];

    // Creación de objetos Producto a partir de los resultados de la consulta
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
    ?>

    <div class="container" data-aos="zoom-in-up">
        <div class="row">
            <div class="col-md-offset-1 col-md-20">
                <div class="panel">
                    <div class="panel-body table-responsive">
                        <?php
                        // Verifica si hay productos para mostrar y agrega el encabezado de la tabla si es necesario
                        if (!empty($productoscesta)) {
                        ?>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <!-- <th>id Cesta</th> -->
                                        <th>Cantidad</th>
                                        <th>Precio</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>


                                    <?php
                                    foreach ($productoscesta as $productocesta) { ?>
                                        <tr>
                                            <td><?php
                                                foreach ($productos as $nuevo_producto) {
                                                    if ($productocesta->idProducto == $nuevo_producto->idProducto) {
                                                        break;
                                                    }
                                                }
                                                echo $nuevo_producto->nombreProducto ?>
                                            </td>
                                            <td><?php echo $productocesta->cantidad ?> </td>
                                            <td><?php echo $nuevo_producto->precio . ' €' ?> </td>
                                            <td><?php
                                                foreach ($productos as $nuevo_producto) {
                                                    if ($productocesta->idProducto == $nuevo_producto->idProducto) {
                                                        break;
                                                    }
                                                }
                                                ?>
                                                <img witdh="50" height="100" src="<?php echo $nuevo_producto->imagen ?>" alt="">
                                            </td>
                                            <td>
                                                <form action="" method="post">
                                                    <input type="hidden" name="productocesta" value="<?php echo $productocesta->idProducto ?>">
                                                    <input class="btn btn-danger" type="submit" name="delete" value="Eliminar">
                                                </form>
                                            </td>
                                        </tr>
                                    <?php } ?>

                                </tbody>
                            </table>
                            <form action="" method="post" class="mb-3">
                                <input class="btn btn-success" type="submit" name="buy" value="Enviar">
                            </form>
                        <?php
                        } else {
                            echo "<h3 class='at-item'><b>No hay productos en la cesta</b></h3>";
                        }
                        ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
</body>

</html>
</body>

</html>