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
                    <a class="nav-link" aria-current="page" href="catalogo.php" aria-disabled="true">Catálogo</a>
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
        if (isset($_POST["buy"])) {
            try {
                $calle = $_POST["calle"];
                $ciudad = $_POST["ciudad"];
                $provincia = $_POST["provincia"];
                $codigo_postal = $_POST["codigo_postal"];
                $pais = $_POST["pais"];

                $sqlInsertarDireccion = "INSERT INTO direcciones (nombre_usuario, calle, ciudad, provincia, codigo_postal, pais) VALUES (?, ?, ?, ?, ?, ?)";
                $stmtInsertarDireccion = $conexion->prepare($sqlInsertarDireccion);
                $stmtInsertarDireccion->bind_param("ssssss", $usuario, $calle, $ciudad, $provincia, $codigo_postal, $pais);

                // Insertar un nuevo pedido

                $sqlInsertPedido = "INSERT INTO pedidos (usuario, precioTotal, fechaPedido) 
                                    SELECT ?, SUM(productos.precio * productocestas.cantidad), NOW()
                                    FROM productocestas 
                                    INNER JOIN productos ON productocestas.idProducto = productos.idProducto 
                                    WHERE productocestas.idCesta IN (SELECT idCesta FROM cestas WHERE usuario=?)";
                $sqlInsertPedido = $conexion->prepare($sqlInsertPedido);
                $sqlInsertPedido->bind_param("ss", $usuario, $usuario);


                if ($stmtInsertarDireccion->execute() && $sqlInsertPedido->execute()) {
                    // Obtener el ID autogenerado del pedido
                    $idPedido = $conexion->insert_id;

                    // Insertar líneas de pedido en la tabla lineaspedidos
                    $sqlInsertLineasPedido = "INSERT INTO lineasPedidos (idProducto, idPedido, precioUnitario, cantidad) 
                            SELECT productocestas.idProducto, ?, productos.precio, productocestas.cantidad
                            FROM productocestas 
                            INNER JOIN productos ON productocestas.idProducto = productos.idProducto 
                            WHERE productocestas.idCesta IN (SELECT idCesta FROM cestas WHERE usuario=?)";

                    $stmtInsertLineasPedido = $conexion->prepare($sqlInsertLineasPedido);
                    $stmtInsertLineasPedido->bind_param("is", $idPedido, $usuario);

                    if ($stmtInsertLineasPedido->execute()) {
                        // Obtener la cantidad comprada de cada producto en la cesta
                        $sqlCantidad = "SELECT idProducto, cantidad FROM productocestas WHERE IdCesta IN (SELECT IdCesta FROM cestas WHERE usuario=?)";

                        $stmtCantidad = $conexion->prepare($sqlCantidad);
                        $stmtCantidad->bind_param("s", $usuario);
                        $stmtCantidad->execute();
                        $resultadoCantidad = $stmtCantidad->get_result();
                        $stmtCantidad->close();

                        // Iterar sobre los resultados y actualizar la cantidad en la tabla de productos
                        while ($row = $resultadoCantidad->fetch_assoc()) {
                            $idProducto = $row['idProducto'];
                            $cantidadComprada = $row['cantidad'];

                            // Actualizar la cantidad del producto en la tabla de productos
                            $sqlUpdateProductos = "UPDATE productos SET cantidad = cantidad - ? WHERE idProducto = ?";

                            $stmtUpdateProductos = $conexion->prepare($sqlUpdateProductos);
                            $stmtUpdateProductos->bind_param("ii", $cantidadComprada, $idProducto);

                            if (!$stmtUpdateProductos->execute()) {
                                echo "Error al actualizar la cantidad del producto: " . $stmtUpdateProductos->error;
                            }

                            $stmtUpdateProductos->close();
                        }

                        // Eliminar todos los productos de la cesta
                        $sqlVaciarCesta = "DELETE FROM productocestas WHERE idCesta IN (SELECT idCesta FROM cestas WHERE usuario= ?)";
                        $stmtVaciarCesta = $conexion->prepare($sqlVaciarCesta);
                        $stmtVaciarCesta->bind_param("s", $usuario);
                        $stmtVaciarCesta->execute();
                        $stmtVaciarCesta->close();
                        //eliminar la cantidad comprada de la tabla productos del producto comprado
                        $sqlCantidad = "SELECT cantidad FROM productocestas WHERE IdCesta IN (SELECT IdCesta FROM cestas WHERE usuario= ?)";
                        $stmtCantidad = $conexion->prepare($sqlCantidad);
                        $stmtCantidad->bind_param("s", $usuario);
                        $stmtCantidad->execute();
                        $resultadoCantidad = $stmtCantidad->get_result();
                        $stmtCantidad->close();
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
                        echo "Error al insertar líneas de pedido: " . $conexion->error;
                    }
                } else {
                    echo "Error al realizar el pedido: " . $conexion->error;
                }
            } catch (Exception $e) {
                echo "Error al realizar el pedido: " . $e->getMessage();
            }
        }
    }
    ?>

    <div class="untree_co-section before-footer-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="mb-4 text-center">Ingrese su dirección:</h3>
                            <form method="post" action="">
                                <div class="mb-3">
                                    <label for="calle" class="form-label">Calle:</label>
                                    <input type="text" class="form-control" name="calle" required>
                                </div>
                                <div class="mb-3">
                                    <label for="ciudad" class="form-label">Ciudad:</label>
                                    <input type="text" class="form-control" name="ciudad" required>
                                </div>
                                <div class="mb-3">
                                    <label for="provincia" class="form-label">Provincia: </label>
                                    <input type="text" class="form-control" name="provincia" required>
                                </div>
                                <div class="mb-3">
                                    <label for="codigo_postal" class="form-label">Código Postal:</label>
                                    <input type="text" class="form-control" name="codigo_postal" required>
                                </div>
                                <div class="mb-3">
                                    <label for="pais" class="form-label">País:</label>
                                    <input type="text" class="form-control" name="pais" required>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button class="btn btn-success" type="submit" name="buy">Finalizar compra</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Start Footer Section -->
    <footer class="footer-section">
        <div class="container relative">

            <div class="sofa-img">
                <img src="https://support.apple.com/library/content/dam/edam/applecare/images/en_US/macbookpro/macbook-pro-14in-m3-nov-2023-silver-space-gray.png" alt="Image" class="img-fluid">
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="subscription-form">
                        <h3 class="d-flex align-items-center"><span class="me-1"><img src="../images/envelope-outline.svg" alt="Image" class="img-fluid"></span><span>Subscribe to Newsletter</span></h3>

                        <form action="#" class="row g-3">
                            <div class="col-auto">
                                <input type="text" class="form-control" placeholder="Enter your name">
                            </div>
                            <div class="col-auto">
                                <input type="email" class="form-control" placeholder="Enter your email">
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
                        <p class="mb-2 text-center text-lg-start">Copyright &copy;
                            <script>
                                document.write(new Date().getFullYear());
                            </script>. All Rights Reserved. &mdash; Designed with love by <a href="https://untree.co">Untree.co</a> Distributed By <a hreff="https://themewagon.com">ThemeWagon</a>
                            <!-- License information: https://untree.co/license/ -->
                        </p>
                    </div>

                    <div class="col-lg-6 text-center text-lg-end">
                        <ul class="list-unstyled d-inline-flex ms-auto">
                            <li class="me-4"><a href="#">Terms &amp; Conditions</a></li>
                            <li><a href="#">Privacy Policy</a></li>
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