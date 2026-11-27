<?php
require_once "modelo/AccesoDatos.php";

// REPLAZAR Y COMPLETAR EL CÓDIGO

$mensajeError="";
/*if ($_SERVER['REQUEST_METHOD']=="POST"){
    
    if (isset($_POST['cod_socio']) && isset($_POST['plazas'])){
    
    }else{
    $mensajeError= "CAMPO/S VACIOS";
    include "vistas/verError.php";
    die();
    }


}*/

/*if (empty($_POST['codigo_socio']) || empty($_POST['plazas'])){
    $mensajeError="CAMPO/S VACIOS";
    include_once "vistas/verError";
    die();
}

if (isset($_POST['codigo_socio']) && isset($_POST['plazas']) && isset($_POST['actividad'])){
    $codigo_socio = $_POST['codigo_socio'];
    $plazas = $_POST['plazas'];
    $actividad = $_POST['actividad'];
}*/

if ($_SERVER['REQUEST_METHOD']!="POST"){
    header("Refresh:0 url='acceso.html'");
    header("Location: acceso.html");
    exit();
}

$codigo_socio_param= trim($_POST['codigo_socio']);
$plazas_param = trim($_POST['plazas']);
$actividad=$_POST['actividad'];


$ac = AccesoDatos::initModelo();
$soc = $ac->getSocio($codigo_socio_param);

if (!$soc){
    $mensajeError = "NO EXISTE NINGUN SOCIO ASOCIADO A ESE CODIGO";
    include "vistas/verError.php";
    exit();
}

if ($soc->bloqueado == 1) {
    $mensajeError = "Cuenta bloqueada. Contacte con RRHH";
    include "vistas/verError.php";
    exit();
}



$act = $ac->getActividad($actividad);

if ($act->plazas_libres<=0){
    $mensajeError ="NO HAY RESERVAS DISPONIBLES";
    include "vistas/verError.php";
    exit();
}

$ac->incrementarContadorSocio($soc->codigo_socio);
$soc->contador_asistencias = $soc->contador_asistencias+ 1;

$ac->decrementarPlazas($act->codigo_clase);
$act->plazas_libres = $act->plazas_libres-1;


$nombreCookie = "cookie" . $soc->codigo_socio;
if (isset($_COOKIE[$nombreCookie])) {
    $mensajeError = "YA HA RESERVADO USTED con fecha" . $_COOKIE[$nombreCookie];
    include "vistas/verError.php";
    exit();

} else {
    $msgCookie = "Es tu primer acceso desde este navegador";
}
setcookie($nombreCookie, date("d/m/Y H:i:s"), time() + 3600*24);

include "vistas/verReserva.php";