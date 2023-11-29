<?php
require_once('../tcpdf/tcpdf.php');
 require '../util/bd/bd_productos.php';

if (isset($_GET['idPedido'])) {
    $idPedido = $_GET['idPedido'];

    // Realiza una consulta a la base de datos para obtener los detalles del pedido desde la tabla lineasPedidos
    $sql = "SELECT * FROM lineasPedidos WHERE idPedido = '$idPedido'";
    $resultado = $conexion->query($sql);
    //realiza otra consulta de la tabla pedidos para obtener todo el pedido
    $sql2 = "SELECT * FROM pedidos WHERE idPedido = '$idPedido'";
    $resultado2 = $conexion->query($sql2);
    //saca el usuario y el precioTotal de la tabla pedidos
    while ($fila = $resultado2->fetch_assoc()) {
        $usuario = $fila["usuario"];
        $precioTotal = $fila["precioTotal"];
    }
    //saca el nombre del usuario de la tabla usuarios
    $sql3 = "SELECT * FROM usuarios WHERE usuario = '$usuario'";
    $resultado3 = $conexion->query($sql3);
    while ($fila = $resultado3->fetch_assoc()) {
        $nombre = $fila["usuario"];
    }
    //saca la fecha del pedido de la tabla pedidos
    $sql4 = "SELECT * FROM pedidos WHERE idPedido = '$idPedido'";
    $resultado4 = $conexion->query($sql4);
    while ($fila = $resultado4->fetch_assoc()) {
        $fechaPedido = $fila["fechaPedido"];
    }
    //saca el precioTotal de la tabla pedidos;
    $sql5 = "SELECT * FROM pedidos WHERE idPedido = '$idPedido'";
    $resultado5 = $conexion->query($sql5);
    while ($fila = $resultado5->fetch_assoc()) {
        $precioTotal = $fila["precioTotal"];
    }

    if ($resultado->num_rows > 0) {
        // Crea un nuevo objeto TCPDF
        $pdf = new TCPDF();
        $pdf->AddPage();

        // Agrega el contenido del PDF con los detalles del pedido
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(0, 10, 'Detalles del pedido ' . $idPedido, 0, 1);
        $pdf->Ln(10);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 10, 'Productos', 0, 1);
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Ln(5);
        $pdf->Cell(0, 10, 'ID Producto - Precio unitario - Cantidad', 0, 1);
        $pdf->Ln(5);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 10, 'Usuario: ' . $nombre, 0, 1);
        $pdf->Ln(5);
        $pdf->Cell(0, 10, 'Fecha del pedido: ' . $fechaPedido, 0, 1);
        $pdf->Ln(5);
        $pdf->Cell(0, 10, 'Precio total: ' . $precioTotal, 0, 1);
        $pdf->Ln(5);
        while ($fila = $resultado->fetch_assoc()) {
            $idProducto = $fila["idProducto"];
            $precioUnitario = $fila["precioUnitario"];
            $cantidad = $fila["cantidad"];
            $pdf->Cell(0, 10, $idProducto . ' - ' . $precioUnitario . ' - ' . $cantidad, 0, 1);
        }

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
