<!-- /*
* Bootstrap 5
* Template Name: Furni
* Template Author: Untree.co
* Template URI: https://untree.co/
* License: https://creativecommons.org/licenses/by/3.0/
*/ -->
<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="author" content="Untree.co">
	<link rel="shortcut icon" href="favicon.png">

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
	<script defer src="../js/bootstrap.bundle.min.js"></script>
	<script defer src="../js/tiny-slider.js"></script>
	<script defer src="../js/custom.js"></script>
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
	<!-- Start Header/Navigation -->
	<nav class="custom-navbar navbar navbar navbar-expand-md navbar-dark bg-dark" arial-label="Furni navigation bar">

		<div class="container">
			<a class="navbar-brand" href="index.html">Ayyoub's Shop<span>.</span></a>

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
						<a class="nav-link" aria-current="page" href="cesta.php" aria-disabled="true"><b>Cesta</b></a>
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
	<!-- End Header/Navigation -->

	<?php
	// Manejo de formularios POST para añadir productos a la cesta
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if (isset($_POST["addProduct"])) {
			$idProducto = $_POST["idProducto"];
			$cantidad = (int)$_POST["cantidad"];

			//si el usuario es invitado se le redirige a iniciar sesion
			if ($usuario == "invitado") {
				header("Location: ./sesiones/iniciar_sesion.php");
			}

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
	<!-- Start Hero Section -->
	<div class="hero">
		<div class="container">
			<div class="row justify-content-between">
				<div class="col-lg-5">
					<div class="intro-excerpt">
						<h1>Shop</h1>
					</div>
				</div>
				<div class="col-lg-7">

				</div>
			</div>
		</div>
	</div>
	<!-- End Hero Section -->



	<div class="untree_co-section product-section before-footer-section">
		<div class="container">
			<div class="row">
				<?php foreach ($productos as $producto) : ?>
					<div class="col-12 col-md-4 col-lg-3 mb-5">
						<a class="product-item" href="#">
							<img src="<?php echo $producto->imagen; ?>" class="img-fluid product-thumbnail" height="300" width="250">
							<h3 class="product-title"><?php echo $producto->nombreProducto; ?></h3>
							<strong class="product-price"><?php echo $producto->precio . " €"; ?></strong>
							<form action="" method="POST">
							<?php 
                                                if($producto->cantidad == 0){
                                                    echo "<p>No hay stock</p>";
                                                }else{
                                                    ?>
                                                <select name="cantidad" class="form-control">
                                                    <?php
                                                    for ($i = 1; $i <= $producto->cantidad; $i++) {
                                                        echo "<option value='$i'>$i</option>";
                                                    }
                                                    ?>
                                                </select>
                                                <?php
                                                }
                                                ?>
								<button type="submit" <?php if ($producto->cantidad == 0) {
															echo 'disabled';
														} ?> class="btn btn-success d-inline p-0 border-0">
									<span class="icon-cross">
										<img src="../images/cross.svg" class="img-fluid" alt="Añadir">
									</span>
								</button>
								<input type="hidden" name="idProducto" value="<?php echo $producto->idProducto ?>">
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
                <img src="../images/sofa.png" alt="Image" class="img-fluid">
            </div>

			<div class="row">
				<div class="col-lg-8">
					<div class="subscription-form">
						<h3 class="d-flex align-items-center"><span class="me-1"><img src="images/envelope-outline.svg" alt="Image" class="../img-fluid"></span><span>Subscribe to Newsletter</span></h3>

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