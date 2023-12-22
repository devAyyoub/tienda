<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="Untree.co">
    <link rel="shortcut icon" href="../images/ordenador-portatil.png">

    <meta name="description" content="" />
    <meta name="keywords" content="bootstrap, bootstrap4" />

    <!-- Bootstrap CSS -->
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="../../css/tiny-slider.css" rel="stylesheet">
    <link href="../../css/style.css" rel="stylesheet">
    <!-- Se establece la codificación de caracteres y la escala de la ventana de visualización -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Título de la página y enlaces a hojas de estilo de Bootstrap y personalizada -->
    <title>Catálogo</title>
    <!-- Inclusión de archivos PHP para la conexión a la base de datos y la clase Producto -->
    <?php require '../../util/bd/bd_productos.php' ?>
    <?php require '../../util/objetos/producto.php' ?>
    <script defer src="../../js/jquery-3.6.4.min.js"></script>
    <script defer src="../../js/bootstrap.bundle.min.js"></script>
    <script defer src="../../js/tiny-slider.js"></script>
    <script defer src="../../js/custom.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $(".mySelect").on("click", function(event) {
                event.preventDefault();
            });
        });
    </script>
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
    }

    if ($usuario == "invitado") {
        header("Location: index.php");
        exit(); // Asegura que el script se detenga después de la redirección
    }

    ?>
    <!-- Start Header/Navigation -->
    <nav class="custom-navbar navbar navbar navbar-expand-md navbar-dark bg-dark" aria-label="Furni navigation bar">

        <div class="container">
            <a class="navbar-brand" href="index.php">TechTribe<span>.</span></a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsFurni" aria-controls="navbarsFurni" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarsFurni">
                <ul class="custom-navbar-nav navbar-nav ms-auto mb-2 mb-md-0">
                    <li class="nav-item">
                        <?php
                        if ($rol == "admin") {
                            echo '<li class="nav-item">';
                            echo '<a class="nav-link" href="../listado_productos.php"><Productos</b></a>';
                            echo '</li>';
                        }
                        ?>
                    </li>
                    <li class="nav-item">
                        <?php
                        if ($rol == "admin") {
                            echo '<li class="nav-item">';
                            echo '<a class="nav-link" href="../productos.php"><b>Insertar producto</b></a>';
                            echo '</li>';
                        }
                        ?>
                    </li>
                    <li>
                        <?php
                        if ($rol == "admin") {
                            echo '<li class="nav-item">';
                            echo '<a class="nav-link" href="../modificarUsuarios.php""><b>Modificar usuarios</b></a>';
                            echo '</li>';
                        }
                        ?>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="../catalogo.php" aria-disabled="true">Catálogo</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="../cesta.php" aria-disabled="true"><img src="../../images/cart.svg" alt=""></a>
                    </li>
                    <li class="nav-item">
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                <img class="img-fluid" src="../../images/user.svg" alt="">
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <?php
                                if ($usuario != "invitado") { ?>
                                    <li><a class="dropdown-item" href="../miCuenta.php">Mi cuenta</a></li>
                                    <li><a class="dropdown-item" href="../mispedidos.php">Mis pedidos</a></li>
                                <?php }
                                // Enlace para cerrar sesión o iniciar sesión según la condición
                                if ($usuario != "invitado") {
                                ?>
                                    <li><a class="dropdown-item" href="cerrar_sesion.php">Cerrar sesión</a></li>
                                <?php } else { ?>
                                    <li><a class="dropdown-item" href="iniciar_sesion.php">Iniciar sesión</a></li>
                                <?php } ?>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

    </nav>
    <!-- End Header/Navigation -->


    <?php
    function depurar($entrada)
    {
        return trim(htmlspecialchars($entrada));
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $contrasenaActual = depurar($_POST["contrasenaActual"]);
        $contrasenaNueva = depurar($_POST["contrasenaNueva"]);
        $confirmarContrasenaNueva = depurar($_POST["confirmarContrasenaNueva"]);

        // Validar la coincidencia de la nueva contraseña y la confirmación
        if ($contrasenaActual == $contrasenaNueva) {
            echo '<script>
                Swal.fire({icon: "error",
                title: "La contraseña nueva no puede ser igual a la actual",
                showConfirmButton: false,
                timer: 1000});</script>';
        } else if ($contrasenaNueva != $confirmarContrasenaNueva) {
            echo '<script>
                Swal.fire({icon: "error",
                title: "Las contraseñas no coinciden. Por favor, inténtalo de nuevo.",
                showConfirmButton: false,
                timer: 1000});</script>';
        } else {
            // Validar longitud y complejidad de la nueva contraseña
            if (strlen($contrasenaNueva) == 0) {
                echo 'La contraseña es obligatoria. ';
            } elseif (strlen($contrasenaNueva) > 255 || strlen($contrasenaNueva) < 4) {
                echo '<script>
                Swal.fire({icon: "error",
                title: "La contraseña debe tener entre 4 y 255 caracteres.",
                showConfirmButton: false,
                timer: 1000});</script>';
            } else {
                $patron = "/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z])(?=.*[!@#$%^&*.]).{8,20}$/";
                if (!preg_match($patron, $contrasenaNueva)) {
                    echo '<script>
                    Swal.fire({icon: "error",
                    title: "La contraseña debe contener al menos un carácter en minúscula, uno en mayúscula, un número y un carácter especial.",
                    showConfirmButton: false,
                    timer: 1000});</script>';
                } else {
                    // Verificar la contraseña actual del usuario desde la base de datos
                    $query_verificar_contrasena = "SELECT contrasena FROM usuarios WHERE usuario = ?";
                    $stmt_verificar_contrasena = $conexion->prepare($query_verificar_contrasena);
                    $stmt_verificar_contrasena->bind_param("s", $usuario);
                    $stmt_verificar_contrasena->execute();
                    $resultado_verificar = $stmt_verificar_contrasena->get_result();

                    if ($resultado_verificar->num_rows > 0) {
                        $fila = $resultado_verificar->fetch_assoc();
                        $contrasenaHash = $fila["contrasena"];

                        // Verificar si la contraseña actual es válida
                        if (password_verify($contrasenaActual, $contrasenaHash)) {
                            // La contraseña actual es válida, actualizar la contraseña
                            $contrasenaNuevaCifrada = password_hash($contrasenaNueva, PASSWORD_DEFAULT);

                            $query_actualizar_contrasena = "UPDATE usuarios SET contrasena = ? WHERE usuario = ?";
                            $stmt_actualizar_contrasena = $conexion->prepare($query_actualizar_contrasena);
                            $stmt_actualizar_contrasena->bind_param("ss", $contrasenaNuevaCifrada, $usuario);

                            if ($stmt_actualizar_contrasena->execute()) {
                                echo '<script>
                                Swal.fire({icon: "success",
                                title: "Contraseña actualizada correctamente",
                                showConfirmButton: false,
                                timer: 1000});</script>';
                            } else {
                                echo '<script>alert("Error al actualizar la contraseña. Por favor, inténtalo de nuevo.");</script>';
                            }
                        } else {
                            // La contraseña actual no es válida
                            echo '<script>
                                Swal.fire({icon: "error",
                                title: "La contraseña actual es incorrecta",
                                showConfirmButton: false,
                                timer: 1000});</script>';
                        }
                    } else {
                        // No se encontró el usuario en la base de datos
                        echo '<script>alert("Error al verificar la contraseña. Por favor, inténtalo de nuevo.");</script>';
                    }

                    // Cerrar las declaraciones preparadas
                    $stmt_verificar_contrasena->close();
                    $stmt_actualizar_contrasena->close();
                }
            }
        }
    }

    ?>

    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card cartaCuenta">
                <div class="card-header">
                    <h3>Cambiar contraseña</h3>
                </div>
                <div class="card-body">
                    <form action="#" class="signin-form" method="post">
                        <div class="form-group mb-3">
                            <label class="label" for="name">Escribe tu contraseña actual</label>
                            <div class="input-group">
                                <input type="password" class="form-control" name="contrasenaActual" id="contrasenaActual" required>
                                <button type="button" class="btn btn-secondary" onclick="mostrarContrasena('contrasenaActual')"><img src="../../images/invisible.png" alt=""></button>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label class="label" for="password">Escribe tu contraseña nueva:</label>
                            <div class="input-group">
                                <input type="password" class="form-control" name="contrasenaNueva" id="contrasenaNueva" required>
                                <button type="button" class="btn btn-secondary" onclick="mostrarContrasena('contrasenaNueva')"><img src="../../images/invisible.png" alt=""></button>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label class="label" for="date">Confirma tu contraseña nueva:</label>
                            <div class="input-group">
                                <input type="password" class="form-control" name="confirmarContrasenaNueva" id="confirmarContrasenaNueva" required>
                                <button type="button" class="btn btn-secondary" onclick="mostrarContrasena('confirmarContrasenaNueva')"><img src="../../images/invisible.png" alt=""></button>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="form-control btn btn-primary rounded submit px-3">Cambiar contraseña</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        function mostrarContrasena(id) {
            var campo = document.getElementById(id);
            var contenedor = campo.closest(".input-group"); // Busca el contenedor más cercano con la clase "input-group"
            var imagen = contenedor.querySelector("img"); // Busca la imagen dentro de ese contenedor
            if (campo.type === "password") {
                campo.type = "text";
                imagen.src = "../../images/ojo.png";
            } else {
                campo.type = "password";
                imagen.src = "../../images/invisible.png";
            }
        }
    </script>

    <!-- Start Footer Section -->
    <footer class="footer-section">
        <div class="container relative">

            <div class="sofa-img">
                <img src="https://support.apple.com/library/content/dam/edam/applecare/images/en_US/macbookpro/macbook-pro-14in-m3-nov-2023-silver-space-gray.png" alt="Image" class="img-fluid">
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="subscription-form">
                        <h3 class="d-flex align-items-center"><span class="me-1"><img src="../../images/envelope-outline.svg" alt="Image" class="../../img-fluid"></span><span>Subscribe to Newsletter</span></h3>

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

            <div class="row g-5 mb-5">
                <div class="col-lg-4">
                    <div class="mb-4 footer-logo-wrap"><a href="#" class="footer-logo">Furni<span>.</span></a></div>
                    <p class="mb-4">Donec facilisis quam ut purus rutrum lobortis. Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate velit imperdiet dolor tempor tristique. Pellentesque habitant</p>

                    <ul class="list-unstyled custom-social">
                        <li><a href="#"><span class="fa fa-brands fa-facebook-f"></span></a></li>
                        <li><a href="#"><span class="fa fa-brands fa-twitter"></span></a></li>
                        <li><a href="#"><span class="fa fa-brands fa-instagram"></span></a></li>
                        <li><a href="#"><span class="fa fa-brands fa-linkedin"></span></a></li>
                    </ul>
                </div>

                <div class="col-lg-8">
                    <div class="row links-wrap">
                        <div class="col-6 col-sm-6 col-md-3">
                            <ul class="list-unstyled">
                                <li><a href="#">About us</a></li>
                                <li><a href="#">Services</a></li>
                                <li><a href="#">Blog</a></li>
                                <li><a href="#">Contact us</a></li>
                            </ul>
                        </div>

                        <div class="col-6 col-sm-6 col-md-3">
                            <ul class="list-unstyled">
                                <li><a href="#">Support</a></li>
                                <li><a href="#">Knowledge base</a></li>
                                <li><a href="#">Live chat</a></li>
                            </ul>
                        </div>

                        <div class="col-6 col-sm-6 col-md-3">
                            <ul class="list-unstyled">
                                <li><a href="#">Jobs</a></li>
                                <li><a href="#">Our team</a></li>
                                <li><a href="#">Leadership</a></li>
                                <li><a href="#">Privacy Policy</a></li>
                            </ul>
                        </div>

                        <div class="col-6 col-sm-6 col-md-3">
                            <ul class="list-unstyled">
                                <li><a href="#">Nordic Chair</a></li>
                                <li><a href="#">Kruzo Aero</a></li>
                                <li><a href="#">Ergonomic Chair</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>

            <div class="border-top copyright">
                <div class="row pt-4">
                    <div class="col-lg-6">
                        <p class="mb-2 text-center text-lg-start">Copyright &copy;<script>
                                document.write(new Date().getFullYear());
                            </script>. All Rights Reserved. &mdash; Designed with love by <a href="https://untree.co">Untree.co</a> Distributed By <a href="https://themewagon.com">ThemeWagon</a> <!-- License information: https://untree.co/license/ -->
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


</body>

</html>