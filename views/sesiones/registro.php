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
	<link rel="shortcut icon" href="../../images/ordenador-portatil.png">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
	<nav class="custom-navbar navbar navbar navbar-expand-md navbar-dark bg-dark" arial-label="Furni navigation bar">

		<div class="container">
			<a class="navbar-brand" href="index.html">TechTribe<span>.</span></a>

			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsFurni" aria-controls="navbarsFurni" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="navbarsFurni">
				<ul class="custom-navbar-nav navbar-nav ms-auto mb-2 mb-md-0">
					<li class="nav-item">
						<a class="nav-link" aria-current="page" href="iniciar_sesion.php" aria-disabled="true">Iniciar sesión</a>
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
        $temp_usuario = depurar($_POST["usuario"]);
        $temp_contrasena = depurar($_POST["contrasena"]);
        $temp_fechaNacimiento = depurar($_POST["fechaNacimiento"]);


        // Validar usuario
        if (strlen($temp_usuario) == 0) {
            $err_usuario = "El nombre es obligatorio";
        } else {
            if (strlen($temp_usuario) > 12 || strlen($temp_usuario) < 4) {
                $err_usuario = "El nombre de usuario debe de tener entre 4 y 12 caracteres";
            } else {
                $patron = "/^[A-Za-z_]{4,12}$/";
                if (!preg_match($patron, $temp_usuario)) {
                    $err_usuario = "El nombre solo pude contener letras o espacios en blanco";
                } else {
                    $usuario = $temp_usuario;
                }
            }
        }

        if (strlen($temp_contrasena) == 0) {
            $err_contrasena = "La contraseña es obligatorio";
        } else {
            if (strlen($temp_contrasena) > 255 || strlen($temp_contrasena) < 4) {
                $err_contrasena = "La contraseña debe tener minimo 4 caracteres y maximo 255";
            } else {
                $patron =  "/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,20}$/";
                if (!preg_match($patron, $temp_contrasena)) {
                    $err_contrasena = "la contraseña tiene que tener mínimo un carácter en minúscula, uno en mayúscula, un número y un carácter especial";
                } else {
                    $contrasena = $temp_contrasena;
                    $contrasena_cifrada = password_hash($contrasena, PASSWORD_DEFAULT);
                }
            }
        }



        // Validar fecha
        if (strlen($temp_fechaNacimiento) == 0) {
            $err_fechaNacimiento = "La fecha de nacimiento es obligatoria";
        } else {
            $fecha_actual = date("Y-m-d");
            list($anyo_actual, $mes_actual, $dia_actual) = explode('-', $fecha_actual);
            list($anyo, $mes, $dia) = explode('-', $temp_fechaNacimiento);
            if ($anyo_actual - $anyo > 12 && $anyo_actual - $anyo < 120) {
                $fechaNacimiento = $temp_fechaNacimiento;
            } else if ($anyo_actual - $anyo < 12) {
                $err_fechaNacimiento = "No puedes ser menor de 12 años";
            } else if ($anyo_actual - $anyo > 120) {
                $err_fechaNacimiento = "No puedes ser mayor de 120 años";
            } else {
                if ($mes_actual - $mes < 0) {
                    $fechaNacimiento = $temp_fechaNacimiento;
                } else if ($mes_actual - $mes < 0) {
                    $err_fechaNacimiento = "No puedes ser menor de 12 o mayor de 120";
                } else {
                    if ($dia_actual - $dia >= 0) {
                        $fechaNacimiento = $temp_fechaNacimiento;
                    } else {
                        $err_fechaNacimiento = "No puedes ser menor de 12 o mayor de 120";
                    }
                }
            }
        }
    }

    //modifica este codigo para que, si ya existe el usuario, no se registre

    if (isset($usuario) && isset($contrasena) && isset($fechaNacimiento)) {
        $consulta = "SELECT * FROM usuarios WHERE usuario = '$usuario'";
        $resultado = mysqli_query($conexion, $consulta);
        if (mysqli_num_rows($resultado) == 0) {
            $consulta = "INSERT INTO usuarios (usuario, contrasena, fechaNacimiento) VALUES ('$usuario', '$contrasena_cifrada', '$fechaNacimiento')";
            $sql_cesta = "INSERT INTO cestas (usuario, precioTotal) VALUES ('$usuario', 0)";
            mysqli_query($conexion, $sql_cesta);
            $resultado = mysqli_query($conexion, $consulta);
            if ($resultado) {
                echo '<script>
            Swal.fire({icon: "success",
            title: "Eliminado de la cesta",
            showConfirmButton: false,
            timer: 1000});</script>';
                header("Location: iniciar_sesion.php");
            } else {
                echo "Error al insertar en la base de datos";
            }
        } else {
            $err_usuario = "El usuario ya existe";
            echo '<script>
            Swal.fire({icon: "error",
            title: "El usuario ya existe",
            showConfirmButton: false,
            timer: 1000});</script>';
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
						<div class="img" style="background-image: url(images/registro.jpeg);">
						</div>
						<div class="login-wrap p-4 p-md-5">
							<div class="d-flex">
								<div class="w-100">
									<h3 class="mb-4">Registrarse</h3>
								</div>
							</div>
							<form action="#" class="signin-form" method="post">
								<div class="form-group mb-3">
									<label class="label" for="name">Username</label>
									<input type="text" class="form-control" name="usuario" placeholder="Usuario" required>
                                    <?php if (isset($err_usuario)) echo '<label class=text-danger>' . $err_usuario . '</label>' ?>
								</div>
								<div class="form-group mb-3">
									<label class="label" for="password">Password</label>
									<input type="password" class="form-control" name="contrasena" placeholder="Contraseña" required>
                                    <?php if (isset($err_contrasena)) echo '<label class=text-danger>' . $err_contrasena . '</label>' ?>
								</div>
                                <div class="form-group mb-3">
									<label class="label" for="date">Fecha de nacimiento</label>
									<input type="date" class="form-control" name="fechaNacimiento" required>
                                    <?php if (isset($err_fechaNacimiento)) echo '<label class=text-danger>' . $err_fechaNacimiento . '</label>' ?>
								</div>
								<div class="form-group">
									<button type="submit" class="form-control btn btn-primary rounded submit px-3">Registrate</button>
								</div>
							</form>
							<p class="text-center">Ya eres miembro? <a data-toggle="tab" href="iniciar_sesion.php">inicia sesión</a></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<script src="../../js/bootstrap.bundle.min.js"></script>
	<script src="../../js/tiny-slider.js"></script>
	<script src="../../js/custom.js"></script>
	<script src="../../js/script.js"></script>
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>