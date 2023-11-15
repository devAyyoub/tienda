<?php
    class Productocesta
    {
        public int $idProducto;
        public string $idCesta;
        public float $cantidad;
    
        function __construct($idProducto, $idCesta, $cantidad)
        {
            $this->idProducto = $idProducto;
            $this->idCesta = $idCesta;
            $this->cantidad = $cantidad;
        }
    }
?>