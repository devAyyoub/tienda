<?php
require_once('../tcpdf/tcpdf.php');
require '../util/bd/bd_productos.php';

if (isset($_GET['idPedido'])) {
    $idPedido = $_GET['idPedido'];

    // Realiza una consulta a la base de datos para obtener los detalles del pedido desde la tabla lineasPedidos
    $sql = "SELECT * FROM lineasPedidos WHERE idPedido = '$idPedido'";
    $resultado = $conexion->query($sql);

    // Realiza otra consulta de la tabla pedidos para obtener todo el pedido
    $sql2 = "SELECT * FROM pedidos WHERE idPedido = '$idPedido'";
    $resultado2 = $conexion->query($sql2);

    // Saca el usuario y el precioTotal de la tabla pedidos
    while ($fila = $resultado2->fetch_assoc()) {
        $usuario = $fila["usuario"];
        $precioTotal = $fila["precioTotal"];
    }

    // Saca el nombre del usuario de la tabla usuarios
    $sql3 = "SELECT * FROM usuarios WHERE usuario = '$usuario'";
    $resultado3 = $conexion->query($sql3);
    while ($fila = $resultado3->fetch_assoc()) {
        $nombre = $fila["usuario"];
    }

    // Saca la fecha del pedido de la tabla pedidos
    $sql4 = "SELECT * FROM pedidos WHERE idPedido = '$idPedido'";
    $resultado4 = $conexion->query($sql4);
    while ($fila = $resultado4->fetch_assoc()) {
        $fechaPedido = $fila["fechaPedido"];
    }

    if ($resultado->num_rows > 0) {
        // Crea un nuevo objeto TCPDF
        $pdf = new TCPDF();
        $pdf->AddPage();

        // Logo de la empresa
        $pdf->Image('../images/Tech.png', 10, 10, 30);

        // Nombre del cliente en la esquina superior derecha
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->SetXY(130, 10);
        $pdf->Cell(0, 10, 'Cliente: ' . $nombre, 0, 1);

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
        while ($fila = $resultado->fetch_assoc()) {
            $idProducto = $fila["idProducto"];
            $precioUnitario = $fila["precioUnitario"];
            $cantidad = $fila["cantidad"];

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
