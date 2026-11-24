<?php
require_once __DIR__ . '/AccesoDatos.php';
require_once __DIR__ . '/funciones.php';

// =====================================================================
//  ACCIONES GET  (muestran un formulario / pantalla y hacen exit)
// =====================================================================

/** Formulario vacío para dar de alta */
function accionAlta()
{
    // TODO: crear Empleado con campos vacíos, $orden = "Nuevo", incluir formulario
}

function accionDetalles(string $cod_emp)
{
    // TODO
}

function accionModificar(string $cod_emp)
{
    // TODO
}

/** Muestra la pantalla de confirmación (NO borra) */
function accionBorrar(string $cod_emp)
{
    // TODO: obtener el empleado e incluir layout/confirmarborrar.php
}

/** Cierra conexión, destruye la sesión y recarga index.php */
function accionTerminar()
{
    // TODO
}

// =====================================================================
//  ACCIONES POST  (modifican la BD y devuelven un mensaje)
// =====================================================================

function accionPostAlta(): string
{
    $msg = "";
    // TODO: limpiar entrada, validar (código vacío / repetido, sueldo), crear objeto, addEmpleado
    return $msg;
}

function accionPostModificar(): string
{
    $msg = "";
    // TODO
    return $msg;
}

function accionPostBorrar(): string
{
    $msg = "";
    // TODO
    return $msg;
}

function accionCambiarBloqueo(): string
{
    $msg = "";
    // TODO: ¿hay empleados marcados?
    return $msg;
}

function accionTrasladar(): string
{
    $msg = "";
    // TODO: ¿hay empleados marcados? ¿se ha elegido departamento?
    return $msg;
}
