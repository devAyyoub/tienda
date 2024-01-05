<!DOCTYPE html>
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
	<title>Producto</title>
	<!-- Inclusión de archivos PHP para la conexión a la base de datos y la clase Producto -->
	<?php require '../util/bd/bd_productos.php' ?>
	<?php require '../util/objetos/producto.php' ?>
	<script defer src="../js/jquery-3.6.4.min.js"></script>
	<script defer src="../js/bootstrap.bundle.min.js"></script>
	<script defer src="../js/tiny-slider.js"></script>
	<script defer src="../js/custom.js"></script>
	<script defer src="../js/script.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

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
					<form class="d-flex ms-auto wide-search" method="post">
						<input class="form-control me-2 flex-grow-1" name="buscado" type="search" placeholder="Buscar producto" aria-label="Search" id="searchInput" style="display: none;">
						<input type="hidden" name="buscar" value="true">
						<button class="btn btn-outline-light" type="submit" id="searchIcon"><i class="fas fa-search"></i></button>
					</form>
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
		</div>

	</nav>
	<!-- End Header/Navigation -->
    <?php
    if(!isset($_POST["nombreProducto"])) header('location: index.php');

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $nombreProducto = $_POST["nombreProducto"];

        $sql = $conexion -> prepare("SELECT * FROM productos
            WHERE nombreProducto = ?");
        $sql -> bind_param("s", $nombreProducto);
        $sql -> execute();
        $resultado = $sql -> get_result();

        $fila = $resultado -> fetch_assoc();
        $conexion -> close();

        $precio = $fila["precio"];
        $descripcion = $fila["descripcion"];
        $cantidad = $fila["cantidad"];
        $imagen = $fila["imagen"];
    }
    ?>
    <div class="container">
        <h3><?php echo $nombreProducto ?></h3>
        <h3><?php echo $precio ?></h3>
        <h3><?php echo $descripcion ?></h3>
        <h3><?php echo $cantidad ?></h3>
        <img src="<?php echo $imagen ?>" alt="Imagen del producto" width="400px">
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>