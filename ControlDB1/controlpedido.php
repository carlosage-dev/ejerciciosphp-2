<?php
require_once "Cliente.php";
require_once "Pedido.php";
require_once "AccesoDatos.php";
$nombre="";
$clave="";
$codigo_cl = 0;
$veces = 0;
$msg="";

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
$cli =$ac->hayCliente($nombre, $clave);
if (! $cli){
    $mensajeError = "ERROR, USUARIO O CONTRASEÑA NO COINCIDE";
    include 'vistaerror.php';
    die();
}else{
    $cod_cl = $cli->cod_cliente;
    $ac->actualizarAccesoCliente($cod_cl);
    $cli->veces = $cli->veces + 1;
    $veces = $cli->veces;
}

$pedidosCliente = $ac->getPedidosCliente($cod_cl);

if (count($pedidosCliente)==0){
    $msg = "No existe pedidos para este cliente";
}
include 'vistapedido.php';



?>

