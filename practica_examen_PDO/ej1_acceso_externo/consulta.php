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
    header("Location: acceso.html");
    exit();
}

// ---------------------------------------------------------------------
// PASO 2: Campos vacíos -> vistaerror.php y terminar
// ---------------------------------------------------------------------
if (empty($_POST['cod_emp'] || empty($_POST['clave']))){
    $mensajeError = "CAMPOS VACIOS";
    include 'vistaerror.php';
    die();
}

if (isset($_POST['cod_emp']) && isset($_POST['clave'])){
$empleado_param = $_POST['cod_emp'];
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
$ac->incrementarAccesos($emp->cod_emp);
//SUPERIMPORTANTE, HACES EL UPDATE EN LA BDD PERO NO LO INCREMENTAS EN EL CLIENTE
$emp->accesos = $emp->accesos+1;




// ---------------------------------------------------------------------
// PASO 6: Cookie ultimoacceso_<cod_emp>  (leer valor viejo -> $msgCookie, luego setcookie)
//         Recuerda: setcookie() tiene que ejecutarse ANTES de que salga cualquier HTML
// ---------------------------------------------------------------------
// 1. Nombre de la cookie: uno distinto para cada empleado
$nombreCookie = "ultimoacceso_" . $emp->cod_emp;
// seria "ultimoacceso_ana01"; esta sera la clave del array del $_COOKIE

// 2. Leer el valor ANTERIOR (lo que mandó el navegador en esta petición)
if (isset($_COOKIE[$nombreCookie])) {
    $msgCookie = "Tu último acceso desde este navegador fue el " . $_COOKIE[$nombreCookie];
} else {
    $msgCookie = "Es tu primer acceso desde este navegador";
}

// 3. Guardar la fecha de AHORA (se leerá en la próxima visita). 30 días
setcookie($nombreCookie, date("d/m/Y H:i:s"), time() + 30 * 24 * 3600);
// seria ""ultimoacceso_ana01, es la clave del array y su valor sera la ultima vez que se conectó"




// ---------------------------------------------------------------------
// PASO 7: Departamento y compañeros
// ---------------------------------------------------------------------
$dep = $ac->getDepartamento($emp->cod_dep);

$tcompaneros = $ac->getEmpleadosDepartamento($emp->cod_dep);

include 'vistaempleado.php';
