<!DOCTYPE html>
<html lang="en">

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
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $temp_usuario = $_POST['usuario'];
        $temp_contrasena = $_POST['contrasena'];
        $temp_fecha = $_POST['fecha'];

        if (strlen($temp_usuario) == 0) {
            $err_usuario = "El nombre es obligatorio";
        } else {

            if (!preg_match("/^[a-zA-Z_]{4,12}$/", $temp_usuario)) {
                $err_usuario = "El nombre solo puede tener letras, y barra baja";
            } else {
                $usuario = $temp_usuario;
            }
        }

        if (strlen($temp_contrasena) == 0) {
            $err_contrasena = "La contraseña es obligatoria";
        } else {
            $patron = "/^[a-zA-Z0-9]{4,255}$/";
            if (!preg_match($patron, $temp_contrasena)) {
                $err_contrasena = "La contraseña debe tener máximo 255 caracteres y contener solamente letras o números";
            } else {
                $contrasena_cifrada = password_hash($temp_contrasena, PASSWORD_DEFAULT);
            }
        }
        if (strlen($temp_fecha) == 0) {
            $err_fecha = "La fecha de nacimiento es obligatoria";
        } else {
            $fecha_actual = date("Y-m-d");
            list($anyo_actual, $mes_actual, $dia_actual) = explode('-', $fecha_actual);
            list($anyo, $mes, $dia) = explode('-', $temp_fecha);
            if (($anyo_actual - $anyo > 12) && ($anyo_actual - $anyo < 120)) {
                $fecha = $temp_fecha;
            } else if (($anyo_actual - $anyo < 12) || ($anyo_actual - $anyo > 120)) {
                $err_fecha = "Debes tener entre 12 y 120 años";
            } else {
                if ($mes_actual - $mes < 0) {
                    $fecha = $temp_fecha;
                } else if ($mes_actual - $mes < 0) {
                    $err_fecha = "Debes tener entre 12 y 120 años";
                } else {
                    if ($dia_actual - $dia >= 0) {
                        $fecha = $temp_fecha;
                    } else {
                        $err_fecha = "Debes tener entre 12 y 120 años";
                    }
                }
            }
        }
    }

    ?>
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
                        backdrop-filter: blur(30px);">
                        <div class="card-body p-5 shadow-5 text-center">
                            <h2 class="fw-bold mb-5">Registrarse</h2>
                            <form action="" method="post">

                                <div class="mb-3">
                                    <label class="form-label">Usuario:</label>
                                    <input class="form-control" type="text" name="usuario" id="input">
                                    <?php if (isset($err_usuario)) echo $err_usuario ?>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Contraseña:</label>
                                    <input class="form-control" type="password" name="contrasena" id="input">
                                    <?php if (isset($err_contrasena)) echo $err_contrasena ?>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Fecha de nacimiento:</label>
                                    <input class="form-control" type="date" name="fecha" id="input">
                                    <?php if (isset($err_fecha)) echo $err_fecha ?>
                                </div>
                                <input class="btn btn-primary" type="submit" value="Registrarse">
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 mb-5 mb-lg-0">
                    <img src="https://images.unsplash.com/photo-1517495306984-f84210f9daa8?q=80&w=1000&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxleHBsb3JlLWZlZWR8M3x8fGVufDB8fHx8fA%3D%3D" class="w-100 rounded-4 shadow-4" alt="" />
                </div>
            </div>
        </div>
    </section>
    <?php

    if (isset($usuario) && isset($contrasena_cifrada) && isset($fecha)) {
        $sql = "INSERT INTO usuarios VALUES ('$usuario','$contrasena_cifrada','$fecha')";
        $conexion->query($sql);
        echo "Usuario registrado con éxito";
        session_start();
        $_SESSION["usuario"] = $usuario;
        header("Location: iniciar_sesion.php");
    }
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>