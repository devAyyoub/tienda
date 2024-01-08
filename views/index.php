<!-- /*
* Bootstrap 5
* Template Name: Furni
* Template Author: Untree.co
* Template URI: https://untree.co/
* License: https://creativecommons.org/licenses/by/3.0/
*/ -->
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
	<link href="../css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
	<link href="../css/tiny-slider.css" rel="stylesheet">
	<link href="../css/style.css" rel="stylesheet">
	<!-- Se establece la codificación de caracteres y la escala de la ventana de visualización -->
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- Título de la página y enlaces a hojas de estilo de Bootstrap y personalizada -->
	<title>Inicio</title>
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



	?>
	<!-- Start Header/Navigation -->
	<nav class="custom-navbar navbar navbar navbar-expand-md navbar-dark bg-dark" arial-label="Furni navigation bar">
		<a class="navbar-brand" href="" id="logo">TechTribe<span>.</span></a>

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
						echo '<a class="nav-link" href="modificarUsuarios.php""><b>Modificar usuarios</b></a>';
						echo '</li>';
					}
					?>
				</li>
				<?php
				if ($usuario != "invitado") { ?>
					<li class="nav-item">
						<a class="nav-link" aria-current="page" href="cesta.php" aria-disabled="true"><img src="../images/cart.svg" alt=""></a>
					</li>
				<?php } ?>
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
								<li><a class="dropdown-item" href="#">Mi cuenta</a></li>
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

	<!-- Start Hero Section -->
	<div class="hero">
		<div class="container">
			<div class="row justify-content-between">
				<div class="col-lg-5">
					<div class="intro-excerpt">
						<h1>¡Conecta con la Tecnología!</h1>
						<p>Descubre lo último en productos electrónicos. Explora y encuentra la innovación que buscas.</p>
						<p><a href="#" class="btn btn-secondary me-2">Ver Ahora</a><a href="#" class="btn btn-white-outline">Explorar</a></p>
					</div>
				</div>
				<div class="col-lg-7">
					<div class="hero-img-wrap">
						<img src="https://cdn.mos.cms.futurecdn.net/GfinEMFXnT42BFxAcDc2rA-1200-80.jpg" class="img-fluid fotohero" alt="Productos Electrónicos">
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- End Hero Section -->

	<!-- Start Product Section -->
	<div class="product-section">
		<div class="container">
			<div class="row">

				<!-- Start Column 1 -->
				<div class="col-md-12 col-lg-3 mb-5 mb-lg-0">
					<h2 class="mb-4 section-title"><b>Innovación en Cada Detalle</b></h2>
					<p class="mb-4">Descubre la excelencia en cada producto. Materiales de primera calidad para una experiencia única.</p>
					<p><a href="shop.html" class="btn btn-primary">Explorar</a></p>
				</div>
				<!-- End Column 1 -->

				<?php
				// Consulta a la base de datos para obtener todos los productos
				$sql = "SELECT * FROM productos";
				$resultado = $conexion->query($sql);

				$productos = [];

				// Creación de objetos Producto a partir de los resultados de la consulta
				while ($fila = $resultado->fetch_assoc()) {
					$nuevo_productocesta = new Producto(
						$fila["idProducto"],
						$fila["nombreProducto"],
						$fila["precio"],
						$fila["descripcion"],
						$fila["cantidad"],
						$fila["imagen"],
						$fila["categoria"]

					);
					array_push($productos, $nuevo_productocesta);
				}
				?>

				<!-- Start Column 2 -->
				<?php
				// Bucle para mostrar los productos
				foreach ($productos as $producto) {
					//solo saca los productos de la catergoria movil
					if ($producto->categoria == "movil") {
				?>
						<div class="col-12 col-md-4 col-lg-3 mb-5 mb-md-0">
							<a class="product-item" href="cart.html">
								<img src="<?php echo $producto->imagen; ?>" class="img-fluid product-thumbnail">
								<h3 class="product-title"><?php echo $producto->nombreProducto; ?></h3>
								<strong class="product-price"><?php echo $producto->precio; ?> €</strong>

								<span class="icon-cross">
									<img src="../images/cross.svg" class="img-fluid">
								</span>
							</a>
						</div>
				<?php
					}
				}
				?>
				<!-- End Column 2 -->

			</div>
		</div>
	</div>
	<!-- End Product Section -->

	<!-- Start Why Choose Us Section -->
	<div class="why-choose-section">
		<div class="container">
			<div class="row justify-content-between">
				<div class="col-lg-6">
					<h2 class="section-title">Razones para Elegirnos</h2>
					<p>Descubre por qué somos tu mejor opción para productos electrónicos de calidad.</p>

					<div class="row my-5">
						<div class="col-md-6">
							<div class="feature">
								<div class="icon">
									<img src="../images/truck.svg" alt="Fast & Free Shipping" class="img-fluid">
								</div>
								<h3>Envío Rápido y Gratuito</h3>
								<p>Entrega rápida y sin costos adicionales. Porque tu tiempo es valioso.</p>
							</div>
						</div>

						<div class="col-md-6">
							<div class="feature">
								<div class="icon">
									<img src="../images/bag.svg" alt="Easy to Shop" class="img-fluid">
								</div>
								<h3>Compra Fácil</h3>
								<p>Explora nuestra tienda de manera sencilla y encuentra lo que necesitas.</p>
							</div>
						</div>

						<div class="col-md-6">
							<div class="feature">
								<div class="icon">
									<img src="../images/support.svg" alt="24/7 Support" class="img-fluid">
								</div>
								<h3>Soporte 24/7</h3>
								<p>Estamos aquí para ayudarte en cualquier momento. Tu satisfacción es nuestra prioridad.</p>
							</div>
						</div>

						<div class="col-md-6">
							<div class="feature">
								<div class="icon">
									<img src="../images/return.svg" alt="Hassle Free Returns" class="img-fluid">
								</div>
								<h3>Devoluciones Sin Problemas</h3>
								<p>Proceso de devolución simple y sin complicaciones. Queremos que estés completamente satisfecho.</p>
							</div>
						</div>
					</div>
				</div>

				<div class="col-lg-5">
					<div class="img-wrap">
						<img src="../images/why-choose-us-img.jpg" alt="Image" class="img-fluid">
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- End Why Choose Us Section -->


	<!-- Start We Help Section -->
	<div class="we-help-section">
		<div class="container">
			<div class="row justify-content-between">
				<div class="col-lg-7 mb-5 mb-lg-0">
					<div class="imgs-grid">
						<div class="grid grid-1"><img src="../images/img-grid-1.jpg" alt="Untree.co"></div>
						<div class="grid grid-2"><img src="../images/img-grid-2.jpg" alt="Untree.co"></div>
						<div class="grid grid-3"><img src="../images/img-grid-3.jpg" alt="Untree.co"></div>
					</div>
				</div>
				<div class="col-lg-5 ps-lg-5">
					<h2 class="section-title mb-4">We Help You Make Modern Interior Design</h2>
					<p>Donec facilisis quam ut purus rutrum lobortis. Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate velit imperdiet dolor tempor tristique. Pellentesque habitant morbi tristique senectus et netus et malesuada</p>

					<ul class="list-unstyled custom-list my-4">
						<li>Donec vitae odio quis nisl dapibus malesuada</li>
						<li>Donec vitae odio quis nisl dapibus malesuada</li>
						<li>Donec vitae odio quis nisl dapibus malesuada</li>
						<li>Donec vitae odio quis nisl dapibus malesuada</li>
					</ul>
					<p><a herf="#" class="btn">Explore</a></p>
				</div>
			</div>
		</div>
	</div>
	<!-- End We Help Section -->

	<!-- Start Popular Product -->
	<div class="popular-product">
		<div class="container">
			<div class="row">

				<div class="col-12 col-md-6 col-lg-4 mb-4 mb-lg-0">
					<div class="product-item-sm d-flex">
						<div class="thumbnail">
							<img src="../images/product-1.png" alt="Image" class="img-fluid">
						</div>
						<div class="pt-3">
							<h3>Nordic Chair</h3>
							<p>Donec facilisis quam ut purus rutrum lobortis. Donec vitae odio </p>
							<p><a href="#">Read More</a></p>
						</div>
					</div>
				</div>

				<div class="col-12 col-md-6 col-lg-4 mb-4 mb-lg-0">
					<div class="product-item-sm d-flex">
						<div class="thumbnail">
							<img src="../images/product-2.png" alt="Image" class="img-fluid">
						</div>
						<div class="pt-3">
							<h3>Kruzo Aero Chair</h3>
							<p>Donec facilisis quam ut purus rutrum lobortis. Donec vitae odio </p>
							<p><a href="#">Read More</a></p>
						</div>
					</div>
				</div>

				<div class="col-12 col-md-6 col-lg-4 mb-4 mb-lg-0">
					<div class="product-item-sm d-flex">
						<div class="thumbnail">
							<img src="../images/product-3.png" alt="Image" class="img-fluid">
						</div>
						<div class="pt-3">
							<h3>Ergonomic Chair</h3>
							<p>Donec facilisis quam ut purus rutrum lobortis. Donec vitae odio </p>
							<p><a href="#">Read More</a></p>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
	<!-- End Popular Product -->

	<!-- Start Testimonial Slider -->
	<div class="testimonial-section">
		<div class="container">
			<div class="row">
				<div class="col-lg-7 mx-auto text-center">
					<h2 class="section-title">Testimonials</h2>
				</div>
			</div>

			<div class="row justify-content-center">
				<div class="col-lg-12">
					<div class="testimonial-slider-wrap text-center">

						<div id="testimonial-nav">
							<span class="prev" data-controls="prev"><span class="fa fa-chevron-left"></span></span>
							<span class="next" data-controls="next"><span class="fa fa-chevron-right"></span></span>
						</div>

						<div class="testimonial-slider">

							<div class="item">
								<div class="row justify-content-center">
									<div class="col-lg-8 mx-auto">

										<div class="testimonial-block text-center">
											<blockquote class="mb-5">
												<p>&ldquo;Donec facilisis quam ut purus rutrum lobortis. Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate velit imperdiet dolor tempor tristique. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Integer convallis volutpat dui quis scelerisque.&rdquo;</p>
											</blockquote>

											<div class="author-info">
												<div class="author-pic">
													<img src="../images/person-1.png" alt="Maria Jones" class="img-fluid">
												</div>
												<h3 class="font-weight-bold">Maria Jones</h3>
												<span class="position d-block mb-3">CEO, Co-Founder, XYZ Inc.</span>
											</div>
										</div>

									</div>
								</div>
							</div>
							<!-- END item -->

							<div class="item">
								<div class="row justify-content-center">
									<div class="col-lg-8 mx-auto">

										<div class="testimonial-block text-center">
											<blockquote class="mb-5">
												<p>&ldquo;Donec facilisis quam ut purus rutrum lobortis. Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate velit imperdiet dolor tempor tristique. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Integer convallis volutpat dui quis scelerisque.&rdquo;</p>
											</blockquote>

											<div class="author-info">
												<div class="author-pic">
													<img src="../images/person-1.png" alt="Maria Jones" class="img-fluid">
												</div>
												<h3 class="font-weight-bold">Maria Jones</h3>
												<span class="position d-block mb-3">CEO, Co-Founder, XYZ Inc.</span>
											</div>
										</div>

									</div>
								</div>
							</div>
							<!-- END item -->

							<div class="item">
								<div class="row justify-content-center">
									<div class="col-lg-8 mx-auto">

										<div class="testimonial-block text-center">
											<blockquote class="mb-5">
												<p>&ldquo;Donec facilisis quam ut purus rutrum lobortis. Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate velit imperdiet dolor tempor tristique. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Integer convallis volutpat dui quis scelerisque.&rdquo;</p>
											</blockquote>

											<div class="author-info">
												<div class="author-pic">
													<img src="../images/person-1.png" alt="Maria Jones" class="img-fluid">
												</div>
												<h3 class="font-weight-bold">Maria Jones</h3>
												<span class="position d-block mb-3">CEO, Co-Founder, XYZ Inc.</span>
											</div>
										</div>

									</div>
								</div>
							</div>
							<!-- END item -->

						</div>

					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- End Testimonial Slider -->

	<!-- Start Blog Section -->
	<div class="blog-section">
		<div class="container">
			<div class="row mb-5">
				<div class="col-md-6">
					<h2 class="section-title">Recent Blog</h2>
				</div>
				<div class="col-md-6 text-start text-md-end">
					<a href="#" class="more">View All Posts</a>
				</div>
			</div>

			<div class="row">

				<div class="col-12 col-sm-6 col-md-4 mb-4 mb-md-0">
					<div class="post-entry">
						<a href="#" class="post-thumbnail"><img src="../images/post-1.jpg" alt="Image" class="img-fluid"></a>
						<div class="post-content-entry">
							<h3><a href="#">First Time Home Owner Ideas</a></h3>
							<div class="meta">
								<span>by <a href="#">Kristin Watson</a></span> <span>on <a href="#">Dec 19, 2021</a></span>
							</div>
						</div>
					</div>
				</div>

				<div class="col-12 col-sm-6 col-md-4 mb-4 mb-md-0">
					<div class="post-entry">
						<a href="#" class="post-thumbnail"><img src="../images/post-2.jpg" alt="Image" class="img-fluid"></a>
						<div class="post-content-entry">
							<h3><a href="#">How To Keep Your Furniture Clean</a></h3>
							<div class="meta">
								<span>by <a href="#">Robert Fox</a></span> <span>on <a href="#">Dec 15, 2021</a></span>
							</div>
						</div>
					</div>
				</div>

				<div class="col-12 col-sm-6 col-md-4 mb-4 mb-md-0">
					<div class="post-entry">
						<a href="#" class="post-thumbnail"><img src="../images/post-3.jpg" alt="Image" class="img-fluid"></a>
						<div class="post-content-entry">
							<h3><a href="#">Small Space Furniture Apartment Ideas</a></h3>
							<div class="meta">
								<span>by <a href="#">Kristin Watson</a></span> <span>on <a href="#">Dec 12, 2021</a></span>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
	<!-- End Blog Section -->

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


	<script src="js/bootstrap.bundle.min.js"></script>
	<script src="js/tiny-slider.js"></script>
	<script src="js/custom.js"></script>
</body>

</html>