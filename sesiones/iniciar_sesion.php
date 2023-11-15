<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <link rel="shortcut icon" href="../img/grow-shop.png" />
    <?php require "../bd/bd_productos.php" ?>
    <link href="../css/style.css" rel="stylesheet">
    <script defer src="../js/script.js"></script>
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
                    <li class="nav-item">
                        <a class="nav-link" href="registro.php" id="enlaceRegistro"><b>Registrarse</b></a>
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
                header('location: ../listado_productos.php');
            } else {
            ?>
                <div class="alert alert-danger" role="alert">
                    Contraseña incorrecta
                </div>
    <?php
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
                        <div class="card-body p-5 shadow-5 text-center cajalogin">
                            <h1>Iniciar sesion</h1>
                            <form action="" method="post">
                                <div class="mb-3">
                                    <label class="form-label">Usuario:</label>
                                    <input class="form-control" type="text" name="usuario" id="input">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Contraseña:</label>
                                    <input class="form-control" type="password" name="contrasena" id="input">
                                </div>
                                <input class="btn btn-primary" type="submit" value="Iniciar sesion">
                            </form>

                            <div>
                                <p class="mt-4">No tienes cuenta? <a href="registro.php" class="text-black-50 fw-bold">Registrate</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 mb-5 mb-lg-0">
                    <img src="https://images.unsplash.com/photo-1600262606369-acb8a2cf69fb?q=80&w=1000&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8YXBwbGUlMjBpcGhvbmUlMjAxMSUyMHByb3xlbnwwfHwwfHx8MA%3D%3D" class="w-100 rounded-4 shadow-4" alt="" />
                </div>
            </div>
        </div>
    </section>



    <!-- <div class="container">
        <h1>Iniciar sesion</h1>
        <form action="" method="post">
            <div class="mb-3">
                <label class="form-label">Usuario:</label>
                <input class="form-control" type="text" name="usuario" id="input">
            </div>
            <div class="mb-3">
                <label class="form-label">Contraseña:</label>
                <input class="form-control" type="password" name="contrasena" id="input">
            </div>
            <input class="btn btn-primary" type="submit" value="Iniciar sesion">
        </form>
    </div> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>