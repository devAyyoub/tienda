<!doctype html>
<html lang="en">

<head>
	<title>Inicio de sesión</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="../../css/style.css">
	<?php require "../../util/bd/bd_productos.php" ?>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
	<link rel="shortcut icon" href="../../images/ordenador-portatil.png">
</head>

<body>
	<nav class="custom-navbar navbar navbar navbar-expand-md navbar-dark" arial-label="Furni navigation bar">

		<div class="container">
			<a class="navbar-brand" href="../index.php">TechTribe<span>.</span></a>

			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsFurni" aria-controls="navbarsFurni" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="navbarsFurni">
				<ul class="custom-navbar-nav navbar-nav ms-auto mb-2 mb-md-0">
					<li class="nav-item">
						<a class="nav-link" aria-current="page" href="registro.php" aria-disabled="true">Registrarse</a>
					</li>
				</ul>
			</div>
		</div>

	</nav>

	<?php
	function depurar($entrada)
	{
		return trim(htmlspecialchars($entrada));
	}

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$usuario = $_POST["usuario"];
		$contrasena = $_POST["contrasena"];

		$sql = "SELECT * FROM usuarios WHERE usuario = '$usuario'";
		// " 1 OR 1 = 1; DROP TABLE usuarios; -- "

		$resultado = $conexion->query($sql);

		if ($resultado->num_rows === 0) {
	?>
			<div class="alert alert-danger" role="alert">
				El usuario no existe
			</div>
			<?php
		} else {
			while ($fila = $resultado->fetch_assoc()) {
				$contrasena_cifrada = $fila["contrasena"];
				$rol = $fila["rol"];
			}

			$acceso_valido = password_verify($contrasena, $contrasena_cifrada);

			if ($acceso_valido) {
				echo "Inicio de sesion correcto";
				session_start();
				$_SESSION["usuario"] = $usuario;
				$_SESSION["rol"] = $rol;
				header('location: ../../views/catalogo.php');
			} else {
			?>
				<div class="container">
					<div class="alert alert-danger" role="alert">
						Contraseña incorrecta
					</div>
				</div>

	<?php
			}
		}
	}
	?>

	<section class="ftco-section">
		<div class="container">
			<div class="row justify-content-center">
			</div>
			<div class="row justify-content-center">
				<div class="col-md-12 col-lg-10">
					<div class="wrap d-md-flex">
						<div class="img" style="background-image: url(images/iphone11.avif);">
						</div>
						<div class="login-wrap p-4 p-md-5">
							<div class="d-flex">
								<div class="w-100">
									<h3 class="mb-4">Iniciar sesión</h3>
								</div>
							</div>
							<form action="#" class="signin-form" method="post">
								<div class="form-group mb-3">
									<label class="label" for="name">Username</label>
									<input type="text" class="form-control" name="usuario" placeholder="Usuario" required>
								</div>
								<div class="form-group mb-3">
									<label class="label" for="password">Password</label>
									<div class="input-group">
										<input type="password" class="form-control" name="contrasena" id="contrasena" placeholder="Contraseña" required>
										<button type="button" class="btn btn-secondary" onclick="mostrarContrasena('contrasena')"><img src="../../images/invisible.png" alt=""></button>
									</div>
								</div>
								<div class="form-group">
									<button type="submit" class="form-control btn btn-primary rounded submit px-3">Iniciar sesión</button>
								</div>
							</form>
							<p class="text-center">¿No eres miembro? <a data-toggle="tab" href="registro.php">Regístrate</a></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<script>
		function mostrarContrasena(id) {
			var campo = document.getElementById(id);
			if (campo.type === "password") {
				campo.type = "text";
				document.getElementsByTagName("img")[0].src = "../../images/ojo.png";
			} else {
				campo.type = "password";
				document.getElementsByTagName("img")[0].src = "../../images/invisible.png";
			}
		}
	</script>
	<script src="../../js/bootstrap.bundle.min.js"></script>
	<script src="../../js/tiny-slider.js"></script>
	<script src="../../js/custom.js"></script>
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>