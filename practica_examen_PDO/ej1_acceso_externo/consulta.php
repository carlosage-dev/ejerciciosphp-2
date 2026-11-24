<?php
require_once "Empleado.php";
require_once "Departamento.php";
require_once "AccesoDatos.php";
require_once "utilidades.php";
$empleado_param="";
$clave_param="";
$num_bloq=0;
$mensajeError = "";
$msgCookie    = "";
$emp          = null;   // Empleado que entra
$dep          = null;   // Su departamento
$tcompaneros  = [];     // Array de Empleado

// ---------------------------------------------------------------------
// PASO 1: Si no se llega por POST -> redirigir a acceso.html y terminar
// ---------------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD']!="POST"){
    header("Refresh:0 url='acceso.html'");
    exit();
}

// ---------------------------------------------------------------------
// PASO 2: Campos vacíos -> vistaerror.php y terminar
// ---------------------------------------------------------------------
if (empty($_POST['cod_emple'] || $_POST['clave'])){
    $mensajeError = "CAMPOS VACIOS";
    include 'vistaerror.php';
    die();
}

if (isset($_POST['cod_emp']) && isset($_POST['clave'])){
$empleado_param = $_POST['cod_emple'];
$clave_param = $_POST['clave'];
}

// ---------------------------------------------------------------------
// PASO 3: Credenciales incorrectas -> error
// ---------------------------------------------------------------------
$ac = AccesoDatos::initModelo();
$emp = $ac->getEmpleadoLogin($empleado_param, $clave_param);

if (! $emp){
    $mensajeError="CREDENCIALES INCORRECTAS";
    include 'vistaerror.php';
    die();
}else{
    $num_bloq=$emp->bloqueado;
}

// ---------------------------------------------------------------------
// PASO 4: Empleado bloqueado -> error
// ---------------------------------------------------------------------

if ($num_bloq==1){
    $mensajeError="EMPLEADO BLOQUEADO, CONTACTE CON RRHH";
    include 'vistaerror.php';
    die();
}

// ---------------------------------------------------------------------
// PASO 5: Accesos +1 (en BD y en el objeto)
// ---------------------------------------------------------------------
$ac->actualizarAccesoCliente($emp->cod_emp);

// ---------------------------------------------------------------------
// PASO 6: Cookie ultimoacceso_<cod_emp>  (leer valor viejo -> $msgCookie, luego setcookie)
//         Recuerda: setcookie() tiene que ejecutarse ANTES de que salga cualquier HTML
// ---------------------------------------------------------------------


// ---------------------------------------------------------------------
// PASO 7: Departamento y compañeros
// ---------------------------------------------------------------------


include 'vistaempleado.php';
