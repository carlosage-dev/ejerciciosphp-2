<?php
require_once "Empleado.php";
require_once "Departamento.php";
require_once "AccesoDatos.php";
require_once "utilidades.php";

$mensajeError = "";
$msgCookie    = "";
$emp          = null;   // Empleado que entra
$dep          = null;   // Su departamento
$tcompaneros  = [];     // Array de Empleado

// PASO 1: si no se llega por POST -> a acceso.html
if ($_SERVER['REQUEST_METHOD'] != "POST") {
    header("Location: acceso.html");
    exit();
}

// PASO 2: campos vacíos (un empty por cada campo)
if (empty($_POST['cod_emp']) || empty($_POST['clave'])) {
    $mensajeError = "Debe rellenar todos los campos";
    include 'vistaerror.php';
    exit();
}
$cod_emp = trim($_POST['cod_emp']);
$clave   = trim($_POST['clave']);

// PASO 3: credenciales
$db  = AccesoDatos::initModelo();
$emp = $db->getEmpleadoLogin($cod_emp, $clave);
if (!$emp) {
    $mensajeError = "Código de empleado o clave incorrectos";
    include 'vistaerror.php';
    exit();
}

// PASO 4: bloqueado
if ($emp->bloqueado == 1) {
    $mensajeError = "Cuenta bloqueada. Contacte con RRHH";
    include 'vistaerror.php';
    exit();
}

// PASO 5: accesos +1 en la BD y en el objeto (para que la vista muestre el nuevo)
$db->incrementarAccesos($emp->cod_emp);
$emp->accesos = $emp->accesos + 1;

// PASO 6: cookie con nombre distinto por empleado. Primero LEER, luego ESCRIBIR
$nombreCookie = "ultimoacceso_" . $emp->cod_emp;
if (isset($_COOKIE[$nombreCookie])) {
    $msgCookie = "Tu último acceso desde este navegador fue el " . $_COOKIE[$nombreCookie];
} else {
    $msgCookie = "Es tu primer acceso desde este navegador";
}
setcookie($nombreCookie, date("d/m/Y H:i:s"), time() + 30 * 24 * 3600);

// PASO 7: departamento y compañeros
$dep         = $db->getDepartamento($emp->cod_dep);
$tcompaneros = $db->getEmpleadosDepartamento($emp->cod_dep);

include 'vistaempleado.php';
