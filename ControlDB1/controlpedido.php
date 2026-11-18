<?php
require_once "Cliente.php";
require_once "Pedido.php";
require_once "AccesoDatos.php";
$nombre="";
$clave="";

$mensajeError ="";
if (empty($_GET['nombre']) || empty($_GET['clave'])){
    $mensajeError = "CAMPO/S VACIOS";
    include 'vistaerror.php';
    die();
}

if (isset($_GET['nombre']) && isset($_GET['clave'])){
    $nombre = $_GET['nombre'];
    $clave = $_GET['clave'];
}


$ac = AccesoDatos::initModelo();
$cli =$ac->hayCliente
if (! $ac->hayCliente($nombre, $clave)){
    $mensajeError = "ERROR, USUARIO O CONTRASEÑA NO COINCIDE";
    include 'vistaerror.php';
    die();
}




?>

ac->actualizarAccesoCliente();

array az->getPedidosCliente();
