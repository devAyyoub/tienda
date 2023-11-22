<?php
// Inicia la sesión si aún no está iniciada
session_start();

// Destruye todas las variables de sesión
$_SESSION = array();

// Borra la sesión actual
session_destroy();

// Redirecciona a la página de inicio de sesión o a donde lo desees
header('location: iniciar_sesion.php');
exit();
?>
