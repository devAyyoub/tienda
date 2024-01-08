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
	<title>Catálogo</title>
	<!-- Inclusión de archivos PHP para la conexión a la base de datos y la clase Producto -->
	<?php require '../util/bd/bd_productos.php' ?>
	<?php require '../util/objetos/producto.php' ?>
	<script defer src="jquery-3.7.1.min.js"></script>
	<script defer src="../js/bootstrap.bundle.min.js"></script>
	<script defer src="../js/tiny-slider.js"></script>
	<script defer src="../js/custom.js"></script>
	<script defer src="../js/script.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script>
		function openNav() {
			document.getElementById("sidebar").style.width = "100%";
		}

		function closeNav() {
			document.getElementById("sidebar").style.width = "0";
		}
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
	<?php
	// Manejo de formularios POST para añadir productos a la cesta
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if (isset($_POST["buscar"])) {
			$buscador = $_POST["buscado"];
			$sql = "SELECT * FROM productos WHERE nombreProducto LIKE '%$buscador%'";
			$resultado = $conexion->query($sql);
		}
		if (isset($_POST["addProduct"])) {
			$idProducto = $_POST["idProducto"];
			$cantidad = (int)$_POST["cantidad"];

			// Si el usuario es invitado, redirige a iniciar sesión
			if ($usuario == "invitado") {
				header("Location: ./sesiones/iniciar_sesion.php");
				exit(); // Asegura que el script se detenga después de la redirección
			}

			// Utiliza sentencia preparada para evitar inyecciones SQL
			$sql3 = "INSERT INTO productocestas (idProducto, idCesta, cantidad) VALUES (?, (SELECT idCesta FROM cestas WHERE usuario = ?), ?) ON DUPLICATE KEY UPDATE cantidad = cantidad + ?";
			$stmt = $conexion->prepare($sql3);
			$stmt->bind_param("isii", $idProducto, $usuario, $cantidad, $cantidad);

			if ($stmt->execute()) {
				echo '<script>
				Swal.fire({icon: "success",
				title: "Añadido a la cesta",
				showConfirmButton: false,
				timer: 1000});</script>';
				header("Refresh:0.7");
			} else {
				echo "Error: " . $stmt->error;
			}
			$stmt->close();
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
	<!-- Start Header/Navigation -->
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
		<a class="navbar-brand logo" href="#">TechTribe.</a>
		<div class="dropdown dropdown2">
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
		<div class="collapse navbar-collapse" id="navbarNav">
			<ul class="navbar-nav mx-auto">
				<?php
				if ($rol == "admin") {
					echo '<li class="nav-item">';
					echo '<a class="nav-link" href="listado_productos.php"><Productos</b></a>';
					echo '</li>';
				}
				?>
				<?php
				if ($rol == "admin") {
					echo '<li class="nav-item">';
					echo '<a class="nav-link" href="productos.php"><b>Insertar producto</b></a>';
					echo '</li>';
				}
				?>
				<?php
				if ($rol == "admin") {
					echo '<li class="nav-item">';
					echo '<a class="nav-link" href="modificarUsuarios.php""><b>Modificar usuarios</b></a>';
					echo '</li>';
				}
				?>

				<li class="nav-item">
					<a class="nav-link" aria-current="page" href="cesta.php" aria-disabled="true">Cesta</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" aria-current="page" href="categorias.php" aria-disabled="true">Categorías</a>
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
		</div>
		<button class="navbar-toggler" type="button" onclick="openNav()">
			<span class="navbar-toggler-icon"></span>
		</button>
		<form class="d-flex" role="search" method="post" id="formBuscar">
			<input class="form-control me-2" name="buscado" type="search" placeholder="Search" aria-label="Search">
			<input type="hidden" name="buscar" value="true">
			<button class="btn btn-outline-success" type="submit"><i class="fa fa-search"></i></button>
		</form>
	</nav>
	<div id="sidebar">
		<a href="javascript:void(0)" class="close-btn" onclick="closeNav()">&times;</a>
		<?php
		if ($rol == "admin") {
			echo '<a class="nav-link" href="listado_productos.php"><Productos</b></a>';
		}
		?>
		<?php
		if ($rol == "admin") {
			echo '<a class="nav-link" href="productos.php"><b>Insertar producto</b></a>';
		}
		?>
		<?php
		if ($rol == "admin") {
			echo '<a class="nav-link" href="modificarUsuarios.php""><b>Modificar usuarios</b></a>';
		}
		?>
		<a href="cesta.php">Cesta</a>
		<a href="categorias.php">Categorías</a>
	</div>
	<!-- End Header/Navigation -->

	

	<?php
	if ($_SERVER["REQUEST_METHOD"] == "GET") {
		// Consulta a la base de datos para obtener todos los productos
		$sql = "SELECT * FROM productos";
		$resultado = $conexion->query($sql);
	}

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if (isset($_POST["columna"]) && isset($_POST["orden"])) {
			$columna = $_POST["columna"];
			$orden = $_POST["orden"];
			$sql = "SELECT * FROM productos ORDER BY $columna $orden";
			$resultado = $conexion->query($sql);
		}
	}

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
	<!-- Start Hero Section -->
	<div class="hero">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-lg-5">
					<div class="intro-excerpt">
						<h1 class="text-center mt-5 mb-0">Catálogo</h1>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- End Hero Section -->
	<form action="" method="POST">
		<div class="filter-form mt-3 divFiltrar">
			<div>
				<select class="form-control me-2 flex-grow-1" name="columna">
					<option selected value="precio">Precio</option>
					<option value="nombreProducto">Marca</option>
				</select>
			</div>
			<div>
				<select class="form-control me-2 flex-grow-1" name="orden">
					<option selected value="asc">Ascendente</option>
					<option value="desc">Descendente</option>
				</select>
			</div>
			<div>
				<input type="submit" class="btn btn-primary" value="Filtrar" id="filtrar">
			</div>
		</div>
	</form>

	<div class="untree_co-section product-section before-footer-section">
		<div class="container">
			<div class="row">
				<?php foreach ($productos as $producto) : ?>
					<div class="col-12 col-md-4 col-lg-3 mb-5">
						<a class="product-item" href="#">
							<img src="<?php echo $producto->imagen; ?>" class="img-fluid product-thumbnail">
							<h3 class="product-title"><?php echo $producto->nombreProducto; ?></h3>
							<strong class="product-price"><?php echo $producto->precio . " €"; ?></strong>
							<form method="POST">
								<div id="container">
									<?php
									if ($producto->cantidad <= 0) {
										echo "<h3>No hay stock</h3>";
									} else {
									?>
										<select class="form-control mySelect" name="cantidad">
											<<?php
												for ($i = 1; $i <= $producto->cantidad; $i++) {
													echo "<option value='$i'>$i</option>";
												}
												?> </select>
											<?php
										}
											?>
								</div>
								<script>
									$(document).ready(function() {
										$(".mySelect").on("click", function(event) {
											event.preventDefault();
										});
									});
								</script>

								<button type="submit" <?php if ($producto->cantidad <= 0) {
															echo 'disabled';
														} ?> class="btn btn-success d-inline p-0 border-0">
									<span class="icon-cross">
										<img src="../images/cross.svg" class="img-fluid" alt="Añadir">
									</span>
								</button>
								<input type="hidden" name="idProducto" value="<?php echo $producto->idProducto ?>">
								<input type="hidden" name="nombreProducto" value="<?php echo $producto->nombreProducto ?>">
								<input type="hidden" name="addProduct" value="true">
							</form>

						</a>
					</div>
				<?php endforeach; ?>
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
</body>

</html>