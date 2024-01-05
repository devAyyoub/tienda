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
	<link href="../css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
	<link href="../css/tiny-slider.css" rel="stylesheet">
	<link href="../css/style.css" rel="stylesheet">
	<!-- Se establece la codificación de caracteres y la escala de la ventana de visualización -->
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- Título de la página y enlaces a hojas de estilo de Bootstrap y personalizada -->
	<title>Catálogo</title>
	<!-- Inclusión de archivos PHP para la conexión a la base de datos y la clase Producto -->
	<?php require '../util/bd/bd_productos.php' ?>
	<?php require '../util/objetos/producto.php' ?>
	<script defer src="../js/jquery-3.6.4.min.js"></script>
	<script defer src="../js/bootstrap.bundle.min.js"></script>
	<script defer src="../js/tiny-slider.js"></script>
	<script defer src="../js/custom.js"></script>
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
							echo '<a class="nav-link" href="modificarUsuarios.php""><b>Modificar usuarios</b></a>';
							echo '</li>';
						}
						?>
					</li>
					<li class="nav-item">
                        <a class="nav-link" aria-current="page" href="catalogo.php" aria-disabled="true">Catálogo</a>
                    </li>
					<li class="nav-item">
						<a class="nav-link" aria-current="page" href="cesta.php" aria-disabled="true"><img src="../images/cart.svg" alt=""></a>
					</li>
					<li class="nav-item">
						<div class="dropdown">
							<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
								<img class="img-fluid" src="../images/user.svg" alt="">
							</button>
							<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">	
								<?php
								if ($usuario != "invitado") { ?>
									<li><a class="dropdown-item" href="">Mi cuenta</a></li>
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
			</div>
		</div>

	</nav>
	<!-- End Header/Navigation -->

        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card cartaCuenta">
                    <div class="card-header">
                        <h3>Datos del Usuario</h3>
                    </div>
                    <div class="card-body">
                        <?php
                        // Realizar la consulta a la tabla usuarios
                        $consulta = "SELECT * FROM usuarios WHERE usuario = '$usuario'";
                        $resultado = $conexion->query($consulta);
                        $fila = $resultado->fetch_assoc();
                        echo "<p><strong>Nombre de usuario: </strong>" . $fila["usuario"] . "</p>";
                        echo "<p><strong>Fecha de nacimiento: </strong>" . $fila["fechaNacimiento"] . "</p>";
                        echo "<a href='./sesiones/cambiarContrasena.php' class='btn btn-primary'>Cambiar contraseña</a>"
                        ?>
                    </div>
                </div>
            </div>
        </div>



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

            <div class="row g-5 mb-5">
                <div class="col-lg-4">
                    <div class="mb-4 footer-logo-wrap">
                        <!-- Puedes cambiar el texto del enlace y el span con el nombre de la tienda -->
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
                        <!-- Puedes cambiar los enlaces y nombres según las secciones de tu tienda -->
                        <div class="col-6 col-sm-6 col-md-3">
                            <ul class="list-unstyled">
                                <li><a href="#">Acerca de nosotros</a></li>
                                <li><a href="#">Productos</a></li>
                                <li><a href="#">Blog</a></li>
                                <li><a href="#">Contáctanos</a></li>
                            </ul>
                        </div>

                        <div class="col-6 col-sm-6 col-md-3">
                            <ul class="list-unstyled">
                                <li><a href="#">Soporte</a></li>
                                <li><a href="#">Base de conocimientos</a></li>
                                <li><a href="#">Chat en vivo</a></li>
                            </ul>
                        </div>

                        <div class="col-6 col-sm-6 col-md-3">
                            <ul class="list-unstyled">
                                <li><a href="#">Trabajos</a></li>
                                <li><a href="#">Nuestro equipo</a></li>
                                <li><a href="#">Privacidad</a></li>
                                <li><a href="#">Términos y condiciones</a></li>
                            </ul>
                        </div>

                        <div class="col-6 col-sm-6 col-md-3">
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


</body>

</html>