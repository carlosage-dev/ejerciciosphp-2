<?php
session_start();

require_once 'modelo/Vuelo.php';
require_once 'modelo/AccesoDatos.php';
require_once 'tools.php';


$plazas =  htmlspecialchars($_GET['plazas']);
$destino = htmlspecialchars($_GET['destino']);

$mensajeError = "";
if (! is_numeric($plazas) || $plazas <=0 || $plazas >5){
    $mensajeError = "EEROR, EL NUMERO DE PLAZAS DEBE SER ENTRE 1 Y 5";
    include 'vistas/error.php';
    die();
}

$ac = AccesoDatos::initModelo();

if (! $ac->hayVuelos($destino)){
    $mensajeError = "ERROR: NO HAY VUELOS CON DESTINO ".$destino;
    include 'vistas/error.php';
    die();
}

//ACTUALIZO EL COOKIE
$nconsultas =0;
if (isset($_COOKIE['consultas'])){
    $nconsultas = $_COOKIE['consultas'];
}
$nconsultas++;
setcookie('consultas', $nconsultas, time() + 3600*24*7, "/");
//OJO CON LA RUTA DEL FINAL "/"

$listaVuelos = $ac->getVuelos($destino,$plazas);
if (count($listaVuelos)==0){
    $mensajeError = "ERROR NO HAY VUELOS CON PLAZAS DISPONIBLES CON DESTINO ".$destino;
    include 'vistas/error.php';
    die();
}



include 'vistas/listado.php';
 
