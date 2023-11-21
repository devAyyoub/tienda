<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Se establece la codificación de caracteres y la escala de la ventana de visualización -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Título de la página y enlaces a hojas de estilo de Bootstrap y personalizada -->
    <title>Listado de productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="../views/styles/style.css" rel="stylesheet">
    <!-- Inclusión de archivos PHP para la conexión a la base de datos y la clase Producto -->
    <?php require './bd/bd_productos.php' ?>
    <?php require './objetos/producto.php' ?>
    <!-- Icono de la página web -->
    <link rel="shortcut icon" href="./img/grow-shop.png" />
    <!--data-aos-->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>

<body>
    <?php
    // Inicio de la sesión y comprobación del usuario y su rol
    session_start();
    if (isset($_SESSION["usuario"])) {
        $usuario = $_SESSION["usuario"];
        $rol = $_SESSION["rol"];
    } else {
        // Configuración de valores predeterminados para un usuario invitado
        $_SESSION["usuario"] = "invitado";
        $usuario = $_SESSION["usuario"];
        $_SESSION["rol"] = "cliente";
        $rol = $_SESSION["rol"];
        header("Location: ./sesiones/iniciar_sesion.php");
    }
    ?>

    <!-- Barra de navegación utilizando Bootstrap -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Ayyoub's Market</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- Menú de navegación y enlaces según el rol del usuario -->
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
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
                            echo '<a class="nav-link" href="modificarUsuarios.php""><b>Modificar usuarios</b></a>';
                            echo '</li>';
                        }
                        ?>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="cesta.php" aria-disabled="true"><b>Cesta</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="catalogo.php" aria-disabled="true"><b>Catálogo</b></a>
                    </li>
                    <li class="nav-item" id="logout">
                        <?php
                        // Enlace para cerrar sesión o iniciar sesión según la condición
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

    <!-- Mensaje de bienvenida con el nombre de usuario -->
    <div class="at-container bienvenido">
        <h2 class="at-item bienvenido-nombre">Bienvenido <?php echo $usuario ?> este es el listado de productos</h2>
    </div>

    <?php
    // Manejo de formularios POST para añadir productos a la cesta
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["addProduct"])) {
            $idProducto = $_POST["idProducto"];
            $cantidad = (int)$_POST["cantidad"];

            //inserta la cantidad de producto en la cesta y si se vuelve a añadir el mismo producto se actualiza la cantidad
            $sql3 = "INSERT INTO productocestas (idProducto, idCesta, cantidad) VALUES ('$idProducto', (SELECT idCesta FROM cestas WHERE usuario = '$usuario'), '$cantidad') ON DUPLICATE KEY UPDATE cantidad = cantidad + '$cantidad'";
            if ($conexion->query($sql3)) {
                echo "Producto " . $idProducto . " añadido a la cesta";
                //actualiza la cantidad de productos
                $sql4 = "UPDATE productos SET cantidad = cantidad - '$cantidad' WHERE idProducto = '$idProducto'";
                $conexion->query($sql4);
            } else {
                echo "Error: " . $sql3 . "<br>" . $conexion->error;
            }
        }
        // Manejo de formularios POST para eliminar productos de la cesta y la base de datos
        if (isset($_POST["deleteProduct"])) {
            $idProducto = $_POST["idProducto"];
            $sql4 = "DELETE FROM productocestas WHERE idProducto = '$idProducto'";
            $conexion->query($sql4);

            $sql3 = "DELETE FROM productos WHERE idProducto = '$idProducto' ";

            $sql = "select imagen from productos where idProducto = '$idProducto'";
            $resultado = $conexion->query($sql);
            if (!$resultado) {
                die("Error al obtener la imagen del producto");
            }
            $ruta_img = $resultado->fetch_assoc()["imagen"];

            // Eliminación de la imagen asociada al producto si existe
            if (file_exists($ruta_img)) {
                unlink($ruta_img);
            }
            //eliminar el producto de la tabla lineaspedidos
            $sql5 = "DELETE FROM lineaspedidos WHERE idProducto = '$idProducto'";
            $conexion->query($sql5);

            // Confirmación de la eliminación del producto
            if ($conexion->query($sql3)) {
                echo "Producto " . $idProducto . " eliminado de la cesta";
            } else {
                echo "Error: " . $sql3 . "<br>" . $conexion->error;
            }
        }
    }
    ?>

    <?php
    // Consulta a la base de datos para obtener todos los productos
    $sql = "SELECT * FROM productos";
    $resultado = $conexion->query($sql);

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

    <!-- Contenedor principal con una tabla para mostrar los productos -->
    <div class="container" data-aos="zoom-in-up">

        <div class="row">
            <div class="col-md-offset-1 col-md-20">
                <div class="panel">
                    <div class="panel-body table-responsive">
                        <?php
                        // Verifica si hay productos para mostrar y agrega el encabezado de la tabla si es necesario
                        if (!empty($productos)) {
                        ?>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID Producto</th>
                                        <th>Nombre</th>
                                        <th>Precio</th>
                                        <th>Descripción</th>
                                        <th>Cantidad</th>
                                        <th>Imagen</th>
                                        <th></th>
                                        <?php
                                        // Agrega una columna adicional para acciones si el usuario es un administrador
                                        if ($rol == "admin") {
                                        ?>
                                            <th></th>
                                        <?php
                                        }
                                        ?>
                                    </tr>
                                </thead>
                            <?php
                        } else {
                            echo "<h3><b>No hay productos en la base de datos</b></h3>";
                        }
                            ?>
                            <tbody>

                                <?php
                                // Itera sobre los productos y muestra cada uno en una fila de la tabla
                                foreach ($productos as $producto) { ?>
                                    <tr>
                                        <td><?php echo $producto->idProducto ?> </td>
                                        <td><?php echo $producto->nombreProducto ?> </td>
                                        <td><?php echo $producto->precio . " €" ?> </td>
                                        <td><?php echo $producto->descripcion ?> </td>
                                        <!-- <td><?php echo $producto->cantidad ?> </td> -->
                                        <td>
                                            <form action="" method="post">
                                                <?php 
                                                if($producto->cantidad == 0){
                                                    echo "<p>No hay stock</p>";
                                                }else{
                                                    ?>
                                                <select name="cantidad" class="select">
                                                    <?php
                                                    for ($i = 1; $i <= $producto->cantidad; $i++) {
                                                        echo "<option value='$i'>$i</option>";
                                                    }
                                                    ?>
                                                </select>
                                                <?php
                                                }
                                                ?>
                                        </td>
                                        <td><img witdh="50" height="100" src="<?php echo $producto->imagen ?>"></td>
                                        <td>
                                            <!-- Formulario para añadir el producto a la cesta -->

                                            <input type="hidden" name="idProducto" value="<?php echo $producto->idProducto ?>">
                                            <input type="hidden" name="addProduct" value="true">

                                            <input class="btn btn-success" type="submit" value="Añadir">
                                            </form>
                                        </td>
                                        <?php
                                        // Agrega una columna adicional para eliminar el producto si el usuario es un administrador
                                        if ($rol == "admin") {
                                        ?>
                                            <td>
                                                <!-- Formulario para eliminar el producto -->
                                                <form action="" method="post">
                                                    <input type="hidden" name="idProducto" value="<?php echo $producto->idProducto ?>">
                                                    <input type="hidden" name="deleteProduct" value="true">

                                                    <input class="btn btn-danger" type="submit" value="Eliminar">
                                                </form>
                                            </td>
                                        <?php
                                        }
                                        ?>
                                    </tr>
                                <?php } ?>

                            </tbody>
                            </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Inclusión de scripts de Bootstrap para funcionalidad adicional -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
</body>

</html>