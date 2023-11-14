<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <?php require "../bd/bd_productos.php" ?>
    <link href="../css/style.css" rel="stylesheet">
    <link rel="shortcut icon" href="../img/grow-shop.png" />
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Ayyoub's Market</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <!-- 
                <li class="nav-item">
                    <a class="nav-link" href="../listado_productos.php">Productos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../productos.php">Insertar producto</a>
                </li>
                -->
                    <li class="nav-item">
                        <a class="nav-link" href="iniciar_sesion.php"><b>Iniciar sesión</b></a>
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
                $patron = "/^[A-Za-z0-9]{4,255}$/";
                if (!preg_match($patron, $temp_contrasena)) {
                    $err_contrasena = "La contraseña solo pude contener letras o numeros";
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
    ?>

    <!-- Section: Design Block -->
<section class="text-center text-lg-start">
  <style>
    .cascading-right {
      margin-right: -50px;
    }

    @media (max-width: 991.98px) {
      .cascading-right {
        margin-right: 0;
      }
    }
  </style>

  <div class="container py-1" id="login">
    <div class="row g-0 align-items-center">
      <div class="col-lg-6 mb-5 mb-lg-0">
        <div class="card cascading-right" style="
            background: hsla(0, 0%, 100%, 0.55);
            backdrop-filter: blur(30px);
            ">
          <div class="card-body p-5 shadow-5 text-center">
            <h2 class="fw-bold mb-5">Registrarse</h2>
        <form action="" method="post">
            <div class="mb-3">
                <label class="form-label">Usuario:</label>
                <input class="form-control" type="text" name="usuario" id="input">
                <?php if (isset($err_usuario)) echo '<label class=text-danger>' . $err_usuario . '</label>' ?>
            </div>
            <div class="mb-3">
                <label class="form-label">Contraseña:</label>
                <input class="form-control" type="password" name="contrasena" id="input">
                <?php if (isset($err_contrasena)) echo '<label class=text-danger>' . $err_contrasena . '</label>' ?>
            </div>
            <div class="mb-3">
                <label>Fecha de nacimiento: </label>
                <input type="date" name="fechaNacimiento" id="input">
                <?php if (isset($err_fechaNacimiento)) echo '<label class=text-danger>' . $err_fechaNacimiento . '</label>' ?>
            </div>
            <input class="btn btn-primary" type="submit" value="Registrarse">
        </form>
          </div>
        </div>
      </div>

      <div class="col-lg-6 mb-5 mb-lg-0">
        <img src="https://img.freepik.com/premium-photo/white-horizon-background_926199-4910.jpg" class="w-100 rounded-4 shadow-4"
          alt="" />
      </div>
    </div>
  </div>
</section>

        

    <?php
    if (isset($usuario) && isset($contrasena_cifrada) && isset($fechaNacimiento)) {
        $sql = "INSERT INTO usuarios(usuario, contrasena, fechaNacimiento) VALUES ('$usuario','$contrasena_cifrada','$fechaNacimiento')";
        $sql2 = "INSERT INTO cestas(usuario, precioTotal) VALUES ('$usuario',0)";
        if ($conexion->query($sql) && $conexion->query($sql2)) {
    ?>
            <!-- <div class="alert alert-success" role="alert">
                Usuario registrado correctamente
            </div> -->
        <?php
            header('location: iniciar_sesion.php');
        } else {
        ?>
            <!-- <div class="alert alert-danger" role="alert">
                Ha habido un error al registrarse
            </div> -->
    <?php
        }
    }
    ?>
</body>

</html>