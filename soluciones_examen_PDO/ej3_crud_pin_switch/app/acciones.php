<?php
require_once __DIR__ . '/AccesoDatos.php';
require_once __DIR__ . '/funciones.php';

// =====================================================================
//  ACCIONES GET  (muestran una pantalla y terminan con exit en el layout)
// =====================================================================

function accionAlta()
{
    $emp = new Empleado();
    $emp->cod_emp = "";
    $emp->nombre  = "";
    $emp->clave   = "";
    $emp->puesto  = "";
    $emp->sueldo  = "";
    $emp->cod_dep = null;
    $orden = "Nuevo";
    include __DIR__ . '/layout/formulario.php';
}

function accionDetalles(string $cod_emp)
{
    $db  = AccesoDatos::getModelo();
    $emp = $db->getEmpleado($cod_emp);
    if (!$emp) {
        return;                         // no existe: se vuelve a la tabla
    }
    $orden = "Detalles";
    include __DIR__ . '/layout/formulario.php';
}

function accionModificar(string $cod_emp)
{
    $db  = AccesoDatos::getModelo();
    $emp = $db->getEmpleado($cod_emp);
    if (!$emp) {
        return;
    }
    $orden = "Modificar";
    include __DIR__ . '/layout/formulario.php';
}

/** Solo muestra la confirmación; el borrado real es accionPostBorrar() */
function accionBorrar(string $cod_emp)
{
    $db  = AccesoDatos::getModelo();
    $emp = $db->getEmpleado($cod_emp);
    if (!$emp) {
        return;
    }
    include __DIR__ . '/layout/confirmarborrar.php';
}

function accionTerminar()
{
    AccesoDatos::closeModelo();
    session_destroy();
    header("Location: index.php");
    exit();
}

// =====================================================================
//  ACCIONES POST  (modifican la BD y devuelven el mensaje)
// =====================================================================

function accionPostAlta(): string
{
    limpiarArrayEntrada($_POST);
    $db = AccesoDatos::getModelo();

    if ($_POST['cod_emp'] == "" || $_POST['nombre'] == "") {
        return "Error: el código y el nombre son obligatorios";
    }
    if ($db->getEmpleado($_POST['cod_emp'])) {
        return "Error: ya existe un empleado con código " . $_POST['cod_emp'];
    }
    if (!is_numeric($_POST['sueldo']) || $_POST['sueldo'] <= 0) {
        return "Error: el sueldo tiene que ser un número mayor que 0";
    }

    $emp = new Empleado();
    $emp->cod_emp    = $_POST['cod_emp'];
    $emp->nombre     = $_POST['nombre'];
    $emp->clave      = $_POST['clave'];
    $emp->puesto     = $_POST['puesto'];
    $emp->sueldo     = $_POST['sueldo'];
    $emp->cod_dep    = $_POST['cod_dep'];
    $emp->bloqueado  = 0;
    $emp->accesos    = 0;
    $emp->fecha_alta = date("Y-m-d");

    if ($db->addEmpleado($emp)) {
        return "Empleado " . $emp->nombre . " dado de alta";
    }
    return "Error al dar de alta";
}

function accionPostModificar(): string
{
    limpiarArrayEntrada($_POST);
    $db = AccesoDatos::getModelo();

    $emp = $db->getEmpleado($_POST['cod_emp']);
    if (!$emp) {
        return "Error: el empleado no existe";
    }
    if (!is_numeric($_POST['sueldo']) || $_POST['sueldo'] <= 0) {
        return "Error: el sueldo tiene que ser un número mayor que 0";
    }

    $emp->nombre  = $_POST['nombre'];
    $emp->clave   = $_POST['clave'];
    $emp->puesto  = $_POST['puesto'];
    $emp->sueldo  = $_POST['sueldo'];
    $emp->cod_dep = $_POST['cod_dep'];

    if ($db->modEmpleado($emp)) {
        return "Empleado " . $emp->cod_emp . " modificado";
    }
    return "No se ha cambiado ningún dato";
}

function accionPostBorrar(): string
{
    $db = AccesoDatos::getModelo();
    if ($db->borrarEmpleado($_POST['cod_emp'])) {
        return "Empleado " . $_POST['cod_emp'] . " borrado";
    }
    return "Error al borrar";
}

function accionCambiarBloqueo(): string
{
    if (!isset($_POST['templeados'])) {
        return "Marca al menos un empleado";
    }
    $db = AccesoDatos::getModelo();
    $n = $db->cambiarBloqueo($_POST['templeados']);
    return "Se ha cambiado el bloqueo a " . $n . " empleado(s)";
}

function accionTrasladar(): string
{
    if (!isset($_POST['templeados'])) {
        return "Marca al menos un empleado";
    }
    if (!isset($_POST['dep_destino']) || $_POST['dep_destino'] == "") {
        return "Elige el departamento de destino";
    }
    $db = AccesoDatos::getModelo();
    $n = $db->trasladar($_POST['templeados'], (int) $_POST['dep_destino']);
    return "Se ha trasladado a " . $n . " empleado(s)";
}
