<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="Untree.co">
    <link rel="shortcut icon" href="../images/ordenador-portatil.png">

    <meta name="description" content="" />
    <meta name="keywords" content="bootstrap, bootstrap4" />

    <!-- Bootstrap CSS -->
    <!-- Bootstrap CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="../css/tiny-slider.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <title>Cesta</title>
    <?php require '../util/bd/bd_productos.php' ?>
    <?php require '../util/objetos/productoCesta.php' ?>
    <?php require '../util/objetos/producto.php' ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmacion() {
            var respuesta = confirm("¿Estás seguro de que quieres eliminar el producto de la cesta?");
            if (respuesta == true) {
                return true;
            } else {
                return false;
            }
        }
    </script>
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
    }

    if ($usuario == "invitado") {
        header("Location: index.php");
        exit(); // Asegura que el script se detenga después de la redirección
    }
    ?>


    <!-- Start Header/Navigation -->
    <nav class="custom-navbar navbar navbar navbar-expand-md navbar-dark bg-dark" arial-label="Furni navigation bar">
        <a class="navbar-brand" href="index.php" id="logo">TechTribe<span>.</span></a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsFurni" aria-controls="navbarsFurni" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsFurni">
            <ul class="custom-navbar-nav navbar-nav mx-auto mb-2 mb-md-0">
                <li class="nav-item">
                    <?php
                    if ($rol == "admin") {
                        echo '<li class="nav-item">';
                        echo '<a class="nav-link" href="listado_productos.php"><Productos</b></a>';
                        echo '</li>';
                    }
                    ?>
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
                    <a class="nav-link" aria-current="page" href="categorias.php" aria-disabled="true">Categorías</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="catalogo.php" aria-disabled="true">Todos los productos</a>
                </li>
                <li class="nav-item">
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            <img class="img-fluid" src="../images/user.svg" alt="">
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <?php
                            if ($usuario != "invitado") { ?>
                                <li><a class="dropdown-item" href="miCuenta.php">Mi cuenta</a></li>
                                <li><a class="dropdown-item" href="mispedidos.php">Mis pedidos</a></li>
                            <?php }
                            // Enlace para cerrar sesión o iniciar sesión según la condición
                            if ($usuario != "invitado") {
                            ?>
                                <li><a class="dropdown-item" href="./sesiones/cerrar_sesion.php">Cerrar sesión</a></li>
                            <?php } else { ?>
                                <li><a class="dropdown-item" href="./sesiones/iniciar_sesion.php">Iniciar sesión</a></li>
                            <?php } ?>
                        </ul>
                    </div>
                </li>
            </ul>
            <form class="d-flex" role="search" method="post" id="formBuscar" action="catalogo.php">
                <input class="form-control me-2" name="buscado" type="search" placeholder="Search" aria-label="Search">
                <input type="hidden" name="buscar" value="true">
                <button class="btn btn-outline-success" type="submit"><i class="fa fa-search"></i></button>
            </form>
        </div>
    </nav>
    <!-- End Header/Navigation -->

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["delete"])) {
            $productocesta = $_POST["productocesta"];

            // Obtener la cantidad del producto en la cesta
            $sqlCantidad = "SELECT cantidad FROM productocestas WHERE IdCesta IN (SELECT IdCesta FROM cestas WHERE usuario=?) AND idProducto=?";
            $stmtCantidad = $conexion->prepare($sqlCantidad);
            $stmtCantidad->bind_param("ss", $usuario, $productocesta);
            $stmtCantidad->execute();
            $resultadoCantidad = $stmtCantidad->get_result();
            $cantidadEliminada = $resultadoCantidad->fetch_assoc()["cantidad"];

            // Eliminar el producto de la cesta
            $sqlDelete = "DELETE FROM productocestas WHERE IdCesta IN (SELECT IdCesta FROM cestas WHERE usuario=?) AND idProducto=?";
            $stmtDelete = $conexion->prepare($sqlDelete);
            $stmtDelete->bind_param("ss", $usuario, $productocesta);

            if ($stmtDelete->execute()) {
                echo '<script>
                    Swal.fire({
                        icon: "success",
                        title: "Eliminado de la cesta",
                        showConfirmButton: false,
                        timer: 1000
                    });
                </script>';
            } else {
                echo "Error al eliminar el producto de la cesta: " . $stmtDelete->error;
            }

            $stmtCantidad->close();
            $stmtDelete->close();
        }
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["buy"])) {
                // Insertar un nuevo pedido
                $sqlInsertPedido = "INSERT INTO pedidos (usuario, precioTotal, fechaPedido) 
                                    SELECT ?, SUM(productos.precio * productocestas.cantidad), NOW()
                                    FROM productocestas 
                                    INNER JOIN productos ON productocestas.idProducto = productos.idProducto 
                                    WHERE productocestas.idCesta IN (SELECT idCesta FROM cestas WHERE usuario=?)";

                $stmtInsertPedido = $conexion->prepare($sqlInsertPedido);
                $stmtInsertPedido->bind_param("ss", $usuario, $usuario);

                if ($stmtInsertPedido->execute()) {
                    // Obtener el ID autogenerado del pedido
                    $idPedido = $conexion->insert_id;

                    // Insertar líneas de pedido en la tabla lineaspedidos
                    $sqlInsertLineasPedido = "INSERT INTO lineasPedidos (idProducto, idPedido, precioUnitario, cantidad) 
                        SELECT productocestas.idProducto, ?, productos.precio, productocestas.cantidad
                        FROM productocestas 
                        INNER JOIN productos ON productocestas.idProducto = productos.idProducto 
                        WHERE productocestas.idCesta IN (SELECT idCesta FROM cestas WHERE usuario=?)";

                    $stmtInsertLineasPedido = $conexion->prepare($sqlInsertLineasPedido);
                    $stmtInsertLineasPedido->bind_param("iss", $idPedido, $usuario);

                    if ($stmtInsertLineasPedido->execute()) {
                        // Actualizar la cantidad en la tabla de productos
                        $sqlUpdateProductos = "UPDATE productos 
                                               SET cantidad = cantidad - productocestas.cantidad
                                               WHERE idProducto = (SELECT idProducto FROM productocestas WHERE idCesta IN (SELECT idCesta FROM cestas WHERE usuario=?) LIMIT 1)";

                        $stmtUpdateProductos = $conexion->prepare($sqlUpdateProductos);
                        $stmtUpdateProductos->bind_param("s", $usuario);

                        if ($stmtUpdateProductos->execute()) {
                            // Eliminar todos los productos de la cesta
                            $sqlVaciarCesta = "DELETE FROM productocestas WHERE idCesta IN (SELECT idCesta FROM cestas WHERE usuario=?)";
                            $stmtVaciarCesta = $conexion->prepare($sqlVaciarCesta);
                            $stmtVaciarCesta->bind_param("s", $usuario);

                            if ($stmtVaciarCesta->execute()) {
                                echo '<script>
                                    Swal.fire({
                                        icon: "success",
                                        title: "Pedido realizado correctamente",
                                        showConfirmButton: false,
                                        timer: 1000
                                    }).then(function() {
                                        window.location.href = "mispedidos.php";
                                    });
                                </script>';
                            } else {
                                echo "Error al vaciar la cesta: " . $stmtVaciarCesta->error;
                            }
                        } else {
                            echo "Error al actualizar la cantidad del producto: " . $stmtUpdateProductos->error;
                        }

                        $stmtUpdateProductos->close();
                    } else {
                        echo "Error al insertar líneas de pedido: " . $stmtInsertLineasPedido->error;
                    }

                    $stmtInsertLineasPedido->close();
                } else {
                    echo "Error al realizar el pedido: " . $stmtInsertPedido->error;
                }

                $stmtInsertPedido->close();
            }
        }
    }
    ?>

    <?php
    //productoscestas
    $sql = "SELECT pc.idProducto, p.nombreProducto, p.precio, p.descripcion, pc.cantidad, p.imagen, p.categoria FROM productocestas pc JOIN productos p ON pc.idProducto = p.idProducto WHERE pc.idCesta = (SELECT idCesta FROM cestas WHERE usuario = '$usuario')";

    $resultado = $conexion->query($sql);


    //productos

    $productoscesta = [];

    // Creación de objetos Producto a partir de los resultados de la consulta
    while ($fila = $resultado->fetch_assoc()) {
        $nuevo_productocestacesta = new Producto(
            $fila["idProducto"],
            $fila["nombreProducto"],
            $fila["precio"],
            $fila["descripcion"],
            $fila["cantidad"],
            $fila["imagen"],
            $fila["categoria"]
        );
        array_push($productoscesta, $nuevo_productocestacesta);
    }
    ?>

    <!-- Start Hero Section -->
    <div class="hero">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5">
                    <div class="intro-excerpt">
                        <h1 class="text-center mt-5 mb-0">Cesta</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Hero Section -->


    <?php
    // Verifica si hay productos para mostrar y agrega el encabezado de la tabla si es necesario
    if (!empty($productoscesta)) { ?>
        <div class="untree_co-section before-footer-section">
            <div class="container">
                <div class="row mb-5">
                    <form class="col-md-12" method="post">
                        <div class="site-blocks-table">

                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="product-thumbnail">Imagen</th>
                                        <th class="product-name">Producto</th>
                                        <th class="product-price">Precio</th>
                                        <th class="product-quantity">Cantidad</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($productoscesta as $nuevo_productocesta) { ?>
                                        <tr>
                                            <td class="product-thumbnail">
                                                <img src="<?php echo $nuevo_productocesta->imagen ?>" alt="Image" class="img-fluid">
                                            </td>
                                            <td class="product-name">
                                                <h2 class="h5 text-black">
                                                    <?php

                                                    echo $nuevo_productocesta->nombreProducto ?>
                                                </h2>
                                            </td>
                                            <td>
                                                <?php echo $nuevo_productocesta->precio . ' €' ?>
                                            </td>
                                            <td>
                                                <?php echo $nuevo_productocesta->cantidad ?>
                                            </td>
                                            <td>
                                                <form action="" method="post">
                                                    <input type="hidden" name="productocesta" value="<?php echo $nuevo_productocesta->idProducto ?>">
                                                    <input class="btn btn-danger" type="submit" name="delete" value="X" onclick="return confirmacion()">
                                                </form>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="row mb-5">
                            <div class="col-md-6">
                                <a href="catalogo.php"><button class="btn btn-outline-black btn-sm btn-block">Continue Shopping</button></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 pl-5">
                        <div class="row justify-content-end">
                            <div class="col-md-7">
                                <div class="row">
                                    <div class="col-md-12 text-right border-bottom mb-5">
                                        <h3 class="text-black h4 text-uppercase">Cart Totals</h3>
                                    </div>
                                </div>
                                <div class="row mb-5">
                                    <div class="col-md-6">
                                        <span class="text-black">Total</span>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <?php
                                        // saca el precio total de la cesta 
                                        $sql = "SELECT SUM(productos.precio * productocestas.cantidad) AS precioTotal

                                        FROM productocestas
                                        INNER JOIN productos ON productocestas.idProducto = productos.idProducto
                                        WHERE productocestas.idCesta IN (SELECT idCesta FROM cestas WHERE usuario='$usuario')";
                                        $resultado = $conexion->query($sql);
                                        $fila = $resultado->fetch_assoc();

                                        ?>
                                        <strong class="text-black"><?php echo $fila["precioTotal"] . ' €'; ?></strong>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <form action="" method="post">
                                            <a class="btn btn-success" href="direcciones.php">Finalizar compra</a>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
    } else {
        echo '<div class="empty-cart-message text-center py-5">';
        echo '<h3 class="at-item text-center"><b>No hay productos en la cesta</b></h3>';
    ?>
        <a href="catalogo.php"><button class="btn btn-success">Ver productos</button></a>
    <?php
        echo '</div>';
    }
    ?>


    <!-- Start Footer Section -->
    <footer class="footer-section">
        <div class="container relative">

            <div class="sofa-img">
                <!-- Puedes cambiar la URL de la imagen con el logo de TechTribe -->
                <img src="https://support.apple.com/library/content/dam/edam/applecare/images/en_US/macbookpro/macbook-pro-14in-m3-nov-2023-silver-space-gray.png" alt="TechTribe Logo" class="img-fluid">
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="subscription-form">
                        <h3 class="d-flex align-items-center">
                            <span class="me-1"><img src="../images/envelope-outline.svg" alt="Image" class="../img-fluid"></span>
                            <span>Suscríbete al Boletín</span>
                        </h3>

                        <form action="#" class="row g-3">
                            <div class="col-auto">
                                <input type="text" class="form-control" placeholder="Ingresa tu nombre">
                            </div>
                            <div class="col-auto">
                                <input type="email" class="form-control" placeholder="Ingresa tu correo electrónico">
                            </div>
                            <div class="col-auto">
                                <button class="btn btn-primary">
                                    <span class="fa fa-paper-plane"></span>
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

            <div class="row g-3 mb-5">
                <div class="col-lg-4 mb-3 mb-lg-0">
                    <div class="mb-4 footer-logo-wrap">
                        <a href="#" class="footer-logo">TechTribe<span>.</span></a>
                    </div>
                    <p class="mb-4">Tu destino para productos electrónicos de última generación. Descubre lo último en tecnología móvil e informática.</p>

                    <ul class="list-unstyled custom-social">
                        <li><a href="#"><span class="fa fa-brands fa-facebook-f"></span></a></li>
                        <li><a href="#"><span class="fa fa-brands fa-twitter"></span></a></li>
                        <li><a href="#"><span class="fa fa-brands fa-instagram"></span></a></li>
                        <li><a href="#"><span class="fa fa-brands fa-linkedin"></span></a></li>
                    </ul>
                </div>

                <div class="col-lg-8">
                    <div class="row links-wrap">
                        <div class="col-6 col-sm-6 col-md-3 mb-3">
                            <ul class="list-unstyled">
                                <li><a href="#">Acerca de nosotros</a></li>
                                <li><a href="#">Productos</a></li>
                                <li><a href="#">Blog</a></li>
                                <li><a href="#">Contáctanos</a></li>
                            </ul>
                        </div>

                        <div class="col-6 col-sm-6 col-md-3 mb-3">
                            <ul class="list-unstyled">
                                <li><a href="#">Soporte</a></li>
                                <li><a href="#">Base de conocimientos</a></li>
                                <li><a href="#">Chat en vivo</a></li>
                            </ul>
                        </div>

                        <div class="col-6 col-sm-6 col-md-3 mb-3">
                            <ul class="list-unstyled">
                                <li><a href="#">Trabajos</a></li>
                                <li><a href="#">Nuestro equipo</a></li>
                                <li><a href="#">Privacidad</a></li>
                                <li><a href="#">Términos y condiciones</a></li>
                            </ul>
                        </div>

                        <div class="col-6 col-sm-6 col-md-3 mb-3">
                            <ul class="list-unstyled">
                                <li><a href="#">Móviles</a></li>
                                <li><a href="#">Portátiles</a></li>
                                <li><a href="#">Accesorios</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>


            <div class="border-top copyright">
                <div class="row pt-4">
                    <div class="col-lg-6">
                        <p class="mb-2 text-center text-lg-start">©<script>
                                document.write(new Date().getFullYear());
                            </script> TechTribe. Todos los derechos reservados. &mdash; <!-- Información de la licencia: https://untree.co/license/ -->
                        </p>
                    </div>

                    <div class="col-lg-6 text-center text-lg-end">
                        <ul class="list-unstyled d-inline-flex ms-auto">
                            <li class="me-4"><a href="#">Términos y condiciones</a></li>
                            <li><a href="#">Política de privacidad</a></li>
                        </ul>
                    </div>

                </div>
            </div>

        </div>
    </footer>
    <!-- End Footer Section -->


    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="../js/tiny-slider.js"></script>
    <script src="../js/custom.js"></script>
</body>

</html>