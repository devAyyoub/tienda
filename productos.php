<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="./css/style.css" rel="stylesheet">
    <?php require './bd/bd_productos.php' ?>
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
                        <a class="nav-link" aria-current="page" href="listado_productos.php" aria-disabled="true"><b>Productos</b></a>
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




    <div class="container">
        <h1>Insertar producto</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="exampleInputEmail1" name="nombre">
                <?php if (isset($err_nombre)) echo "<p class='text-danger'>$err_nombre</p>"; ?>
            </div>
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Precio</label>
                <input type="text" class="form-control" id="exampleInputEmail1" name="precio">
                <?php if (isset($err_precio)) echo "<p class='text-danger'>$err_precio</p>"; ?>
            </div>
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Descripción</label>
                <input type="text" class="form-control" id="exampleInputEmail1" name="descripcion">
                <?php if (isset($err_descripcion)) echo "<p class='text-danger'>$err_descripcion</p>"; ?>
            </div>
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Cantidad</label>
                <select class="form-control" id="" name="cantidad">
                    <option value="" selected disabled>Selecciona una cantidad</option>
                    <?php
                    for ($i = 1; $i <= 5; $i++) {
                        echo "<option value='$i'>$i</option>";
                    }
                    ?>
                </select>
                <!-- <input type="text" class="form-control" id="exampleInputEmail1" name="cantidad"> -->
                <?php if (isset($err_cantidad)) echo "<p class='text-danger'>$err_cantidad</p>"; ?>
            </div>
            <div class="mb-3">
                <label class="form-label">Imagen</label>
                <input class="form-control" type="file" name="imagen">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <?php
    function depurar($entrada)
    {
        $salida = htmlspecialchars($entrada);
        $salida = trim($salida);
        return $salida;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $temp_nombre = depurar($_POST["nombre"]);
        $temp_precio = depurar($_POST["precio"]);
        $temp_descripcion = depurar($_POST["descripcion"]);
        $temp_cantidad = depurar($_POST["cantidad"]);


        #Validación nombre ( solo puede aceptar caracteres, numeros y espacios en blanco)

        if (strlen($temp_nombre) == 0) {
            $err_nombre = "El nombre es obligatorio";
        } else {
            if (strlen($temp_nombre) > 40) {
                $err_nombre = "El nombre no puede tener mas de 40 caracteres";
            } else {
                $patron = "/^[A-Za-z0-9]*( [A-Za-z0-9]+)*$/";
                if (!preg_match($patron, $temp_nombre)) {
                    $err_nombre = "El nombre solo pude contener letras, numeros o espacios en blanco";
                } else {
                    $nombre = $temp_nombre;
                }
            }
        }

        // $_FILES

        $nombre_imagen = $_FILES["imagen"]["name"];
        $tipo_imagen = $_FILES["imagen"]["type"];
        $tamano_imagen = $_FILES["imagen"]["size"];
        $ruta_temporal = $_FILES["imagen"]["tmp_name"];

        if (strlen($nombre_imagen) > 1) {
            if ($_FILES["imagen"]["error"] != 0) {
                echo "Error al subir la imagen";
            } else {
                $permitidos = ["image/jpeg", "image/png", "image/gif"];
                if (!in_array($_FILES["imagen"]["type"], $permitidos)) {
                    echo "<h1>Error al subir la imagen</h1>";
                } else {
                    echo $nombre_imagen . " " . $tipo_imagen . " " . $tamano_imagen . " " . $ruta_temporal;
                    $ruta_final = "./img/" . $nombre_imagen;
                    move_uploaded_file($ruta_temporal, $ruta_final);
                }
            }
        } else {
            $err_imagen = "La imagen es obligatoria";
        }

        #Validación precio ( solo puede aceptar numeros y un punto para los decimales)

        // * Comprobar precio
        if (strlen($temp_precio) == 0) {
            $err_precio = "El precio es obligatorio";
        } elseif (!is_numeric($temp_precio)) {
            $err_precio = "El precio debe ser un número";
        } elseif ($temp_precio < 0) {
            $err_precio = "El precio no puede ser negativo";
        } elseif ($temp_precio > 99999.99) {
            $err_precio = "El precio no puede ser mayor de 99999.99";
        } else {
            $precio = $temp_precio;
        }

        #Validación descripcion ( solo puede aceptar caracteres, numeros y espacios en blanco)

        if (strlen($temp_descripcion) == 0) {
            $err_descripcion = "La descripcion es obligatoria";
        } else {
            if (strlen($temp_descripcion) > 255) {
                $err_descripcion = "La descripcion no puede tener mas de 255 caracteres";
            } else {
                $patron = "/^[A-Za-z0-9]*( [A-Za-z0-9]+)*$/";
                if (!preg_match($patron, $temp_descripcion)) {
                    $err_descripcion = "La descripcion solo pude contener letras, numeros o espacios en blanco";
                } else {
                    $descripcion = $temp_descripcion;
                }
            }
        }

        #Validación cantidad ( solo puede aceptar numeros)


        if (strlen($temp_cantidad) == 0) {
            $err_cantidad = "La cantidad es obligatoria";
        } elseif (filter_var($temp_cantidad, FILTER_VALIDATE_INT) === false) { // Otra forma de hacerlo, el ctype_digit no acepta negativos mientras que el filter_var si, pero el 0 te devuelve FALSE ya que el "0" se evalua como FALSE. Una forma de arreglar esto es comprobando estrictamente como he hecho
            // } elseif (!ctype_digit($temp_cantidad)) {
            $err_cantidad = "La cantidad debe ser un número entero";
        } elseif ($temp_cantidad < 0) {
            $err_cantidad = "La cantidad no puede ser negativa";
        } elseif ($temp_cantidad > 99999) {
            $err_cantidad = "La cantidad no puede ser mayor de 99999";
        } else {
            $cantidad = $temp_cantidad;
        }

        if (isset($nombre) && isset($precio) && isset($descripcion) && isset($cantidad) && isset($ruta_final)) {


            $sql = "INSERT INTO Productos (nombreProducto, precio, descripcion, cantidad, imagen) VALUES ('$nombre', '$precio', '$descripcion', '$cantidad', '$ruta_final')";

            if ($conexion->query($sql) === TRUE) {
                echo "<h1>Éxito!</h1>";
            } else {
                echo "Error al insertar datos: " . $conexion->error;
            }

            $conexion->close();
        }
    }

    ?>
</body>

</html>