<?php
    class Usuario
    {
        public string $usuario;
        public string $contrasena;
        public string $fechaNacimiento;
        public string $rol;
    
        function __construct($usuario, $contrasena, $fechaNacimiento, $rol)
        {
            $this->usuario = $usuario;
            $this->contrasena = $contrasena;
            $this->fechaNacimiento = $fechaNacimiento;
            $this->rol = $rol;

        }
    }
?>