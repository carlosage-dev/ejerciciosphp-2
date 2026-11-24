<?php
require_once __DIR__ . '/AccesoDatos.php';

/**
 * TODO F1
 * Devuelve el HTML de la tabla de empleados construido con $msg .= ...
 * Columnas: ☐ | Código | Nombre | Puesto | Departamento | Sueldo | Estado | Detalles | Modificar | Borrar
 *  - checkbox name='templeados[]' value='CÓDIGO'
 *  - Departamento con su NOMBRE (array cod_dep => nombre)
 *  - Estado: "🔒 Bloqueado" / "Activo"  (y clase 'bloqueado' en la fila)
 *  - enlaces GET: $_SERVER['PHP_SELF']."?orden=Detalles&id=..."
 */
function mostrarDatos(): string
{
    $msg = "";

    return $msg;
}

/**
 * TODO F2
 * Devuelve las <option> de todos los departamentos.
 * Si $seleccionado coincide con un cod_dep, esa opción lleva 'selected'.
 * Se usa en formulario.php (departamento del empleado) y en principal.php (traslado).
 */
function opcionesDepartamentos($seleccionado = null): string
{
    $opciones = "";

    return $opciones;
}

/**
 * TODO F3
 * Segundos que faltan para que caduque la sesión por inactividad.
 */
function tiempoRestante(): int
{
    return 0;
}

/*
 *  Funciones para limpiar la entrada de posibles inyecciones (las de clase)
 */
function limpiarEntrada(string $entrada): string
{
    $salida = trim($entrada);
    $salida = strip_tags($salida);
    return $salida;
}

function limpiarArrayEntrada(array &$entrada)
{
    foreach ($entrada as $key => $value) {
        $entrada[$key] = limpiarEntrada($value);   // ⚠️ ¿y si $value es un array?
    }
}
