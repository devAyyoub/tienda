<?php
    class Producto
    {
        public int $idProducto;
        public string $nombreProducto;
        public float $precio;
        public string $descripcion;
        public int $cantidad;
        public string $imagen;

        public string $categoria;
    
        function __construct($idProducto, $nombreProducto, $precio, $descripcion, $cantidad, $imagen, $categoria)
        {
            $this->idProducto = $idProducto;
            $this->nombreProducto = $nombreProducto;
            $this->precio = $precio;
            $this->descripcion = $descripcion;
            $this->cantidad = $cantidad;
            $this->imagen = $imagen;
            $this->categoria = $categoria;
        }
    }
?>
