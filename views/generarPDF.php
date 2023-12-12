<?php
require_once('../tcpdf/tcpdf.php');
require '../util/bd/bd_productos.php';

if (isset($_GET['idPedido'])) {
    $idPedido = $_GET['idPedido'];

    // Obtén detalles del pedido desde la tabla lineasPedidos
    $sql = "SELECT * FROM lineasPedidos WHERE idPedido = '$idPedido'";
    $resultado = $conexion->query($sql);

    // Obtén toda la información del pedido desde la tabla pedidos
    $sql2 = "SELECT * FROM pedidos WHERE idPedido = '$idPedido'";
    $resultado2 = $conexion->query($sql2);

    // Obtiene el usuario y el precioTotal de la tabla pedidos
    while ($fila = $resultado2->fetch_assoc()) {
        $usuario = $fila["usuario"];
        $precioTotal = $fila["precioTotal"];
    }

    // Obtiene la dirección del usuario desde la tabla direcciones
    $sql3 = "SELECT * FROM direcciones WHERE nombre_usuario = '$usuario'";
    $resultado3 = $conexion->query($sql3);
    while ($filaDireccion = $resultado3->fetch_assoc()) {
        $calle = $filaDireccion["calle"];
        $ciudad = $filaDireccion["ciudad"];
        $provincia = $filaDireccion["provincia"];
        $codigoPostal = $filaDireccion["codigo_postal"];
        $pais = $filaDireccion["pais"];
    }

    // Obtiene el nombre del usuario desde la tabla usuarios
    $sql4 = "SELECT * FROM usuarios WHERE usuario = '$usuario'";
    $resultado4 = $conexion->query($sql4);
    while ($filaUsuario = $resultado4->fetch_assoc()) {
        $nombre = $filaUsuario["usuario"];
    }

    // Obtiene la fecha del pedido desde la tabla pedidos
    $sql5 = "SELECT * FROM pedidos WHERE idPedido = '$idPedido'";
    $resultado5 = $conexion->query($sql5);
    while ($filaFecha = $resultado5->fetch_assoc()) {
        $fechaPedido = $filaFecha["fechaPedido"];
    }

    if ($resultado->num_rows > 0) {
        // Crea un nuevo objeto TCPDF
        $pdf = new TCPDF();
        $pdf->AddPage();

        // Logo de la empresa
        $pdf->Image('../images/Tech.png', 10, 10, 30);

        // Nombre del cliente y dirección en la esquina superior derecha
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->SetXY(130, 10);
        $pdf->Cell(0, 10, $nombre, 0, 1);
        $pdf->SetXY(130, 15);
        $pdf->Cell(0, 10, $calle, 0, 1);
        $pdf->SetXY(130, 20);
        $pdf->Cell(0, 10, $provincia, 0, 1);
        $pdf->SetXY(130, 25);
        $pdf->Cell(0, 10, $codigoPostal, 0, 1);
        $pdf->SetXY(130, 30);
        $pdf->Cell(0, 10, $pais, 0, 1);

        // Detalles del pedido
        $pdf->Ln(20);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 10, 'Detalles del pedido ' . $idPedido, 0, 1);
        $pdf->Ln(5);

        // Tabla para mostrar los productos con bordes
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(40, 10, 'ID Producto', 1, 0, 'C');
        $pdf->Cell(40, 10, 'Precio Unitario', 1, 0, 'C');
        $pdf->Cell(40, 10, 'Cantidad', 1, 1, 'C');
        $pdf->SetFont('helvetica', '', 12);

        // Detalles de productos
        while ($filaProducto = $resultado->fetch_assoc()) {
            $idProducto = $filaProducto["idProducto"];
            $precioUnitario = $filaProducto["precioUnitario"];
            $cantidad = $filaProducto["cantidad"];

            // Agrega fila a la tabla
            $pdf->Cell(40, 10, $idProducto, 1, 0, 'C');
            $pdf->Cell(40, 10, '€' . number_format($precioUnitario, 2), 1, 0, 'C');
            $pdf->Cell(40, 10, $cantidad, 1, 1, 'C');
        }

        // Precio total
        $pdf->Ln(10);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 10, 'Precio total: €' . number_format($precioTotal, 2), 0, 1);
        $pdf->Ln(10);

        // Fecha del pedido
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 10, 'Fecha del pedido: ' . $fechaPedido, 0, 1);
        $pdf->Ln(10);

        // Establece la salida del PDF como descarga
        $pdf->Output('detalle_pedido_' . $idPedido . '.pdf', 'D');
    } else {
        // Maneja el caso en el que no se encuentran detalles del pedido
        echo "No se encontraron detalles del pedido";
    }
} else {
    // Maneja el caso en el que no se proporciona un ID de pedido válido
    echo "ID de pedido no válido";
}
?>
