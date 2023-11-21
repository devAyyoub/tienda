<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar usurios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="../views/styles/style.css" rel="stylesheet">
    <?php require './bd/bd_productos.php' ?>
    <?php require './objetos/usuario.php' ?>
    <link rel="shortcut icon" href="./img/grow-shop.png" />
</head>

<body>
    <?php
    session_start();
    if (isset($_SESSION["usuario"])) {
        $usuario = $_SESSION["usuario"];
        $rol = $_SESSION["rol"];
    } else {
        $_SESSION["usuario"] = "invitado";
        $usuario = $_SESSION["usuario"];
        $_SESSION["rol"] = "cliente";
        $rol = $_SESSION["rol"];
    }
    if ($rol != "admin") {
        header("Location: ./listado_productos.php");
    }
    ?>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Ayyoub's Market</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="listado_productos.php"><b>Productos</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="productos.php"><b>Insertar producto</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="cesta.php" aria-disabled="true"><b>Cesta</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="catalogo.php" aria-disabled="true"><b>Catálogo</b></a>
                    </li>
                    <li class="nav-item">
                        <?php
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

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $usuario = $_POST["usuario"];

        $sql5 = "DELETE FROM productocestas WHERE IdCesta IN (SELECT IdCesta FROM cestas WHERE usuario='$usuario')";
        if ($conexion->query($sql5)) {
            $sql4 = "DELETE FROM cestas WHERE usuario='$usuario'";
            $sql3 = "DELETE FROM usuarios WHERE usuario='$usuario'";

            if ($conexion->query($sql4) && $conexion->query($sql3)) {
                echo "Usuario ".$usuario ." eliminado correctamente";
            } else {
                echo "Error: " . $sql3 . " " . $sql4 . "<br>" . $conexion->error;
            }
        } else {
            echo "Error: " . $sql5 . "<br>" . $conexion->error;
        }
    }
    ?>

    <div class="container ">
        <div class="row">
            <div class="col-md-offset-1 col-md-20"> 
                <div class="panel">
                    <div class="panel-body table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Usuario</th>
                                    <th>Hash</th>
                                    <th>Fecha de nacimiento</th>
                                    <th>rol</th>
                                    <th></th>

                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                $sql = "SELECT * FROM usuarios";
                                $resultado = $conexion->query($sql);

                                $usuarios = [];

                                while ($fila = $resultado->fetch_assoc()) {
                                    $nuevo_usuario = new Usuario(
                                        $fila["usuario"],
                                        $fila["contrasena"],
                                        $fila["fechaNacimiento"],
                                        $fila["rol"]
                                    );
                                    array_push($usuarios, $nuevo_usuario);
                                }
                                foreach ($usuarios as $usuario) { ?>
                                    <tr>
                                        <td><?php echo $usuario->usuario ?> </td>
                                        <td><?php echo $usuario->contrasena ?> </td>
                                        <td><?php echo $usuario->fechaNacimiento ?> </td>
                                        <td><?php echo $usuario->rol ?> </td>
                                        <?php
                                            if($usuario->rol == "cliente"){?>
                                        <td>
                                            <form action="" method="post">
                                                <input type="hidden" name="usuario" value="<?php echo $usuario->usuario ?>">
                                                <input class="btn btn-danger" type="submit" value="Eliminar">
                                            </form>
                                        </td>
                                        <?php } ?>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>
</body>

</html>