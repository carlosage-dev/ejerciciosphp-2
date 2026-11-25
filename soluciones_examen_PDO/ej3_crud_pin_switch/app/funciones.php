<?php
require_once __DIR__ . '/AccesoDatos.php';

/**
 * Tabla de empleados construida con $msg .= (estilo examII)
 */
function mostrarDatos(): string
{
    $db = AccesoDatos::getModelo();

    // Array cod_dep => nombre: UNA consulta para toda la tabla
    $nombresDep = [];
    foreach ($db->getDepartamentos() as $dep) {
        $nombresDep[$dep->cod_dep] = $dep->nombre;
    }

    $msg  = "<table>";
    $msg .= "<tr><th></th><th>Código</th><th>Nombre</th><th>Puesto</th><th>Departamento</th>"
          . "<th>Sueldo</th><th>Estado</th><th colspan='3'>Acciones</th></tr>";

    foreach ($db->getEmpleados() as $emp) {
        $clase  = "";
        $estado = "Activo";
        if ($emp->bloqueado == 1) {
            $clase  = "bloqueado";
            $estado = "🔒 Bloqueado";
        }

        $enlace = $_SERVER['PHP_SELF'] . "?id=" . $emp->cod_emp . "&orden=";

        $msg .= "<tr class='" . $clase . "'>";
        $msg .= "<td><input type='checkbox' name='templeados[]' value='" . $emp->cod_emp . "'></td>";
        $msg .= "<td>" . $emp->cod_emp . "</td>";
        $msg .= "<td>" . $emp->nombre . "</td>";
        $msg .= "<td>" . $emp->puesto . "</td>";
        $msg .= "<td>" . $nombresDep[$emp->cod_dep] . "</td>";
        $msg .= "<td>" . number_format($emp->sueldo, 2, ',', '.') . " €</td>";
        $msg .= "<td>" . $estado . "</td>";
        $msg .= "<td><a href='" . $enlace . "Detalles'>Detalles</a></td>";
        $msg .= "<td><a href='" . $enlace . "Modificar'>Modificar</a></td>";
        $msg .= "<td><a href='" . $enlace . "Borrar'>Borrar</a></td>";
        $msg .= "</tr>";
    }
    $msg .= "</table>";
    return $msg;
}

/**
 * <option> de los departamentos, marcando el seleccionado.
 */
function opcionesDepartamentos($seleccionado = null): string
{
    $db = AccesoDatos::getModelo();
    $opciones = "";
    foreach ($db->getDepartamentos() as $dep) {
        $selected = "";
        if ($seleccionado !== null && $dep->cod_dep == $seleccionado) {
            $selected = "selected";
        }
        $opciones .= "<option value='" . $dep->cod_dep . "' " . $selected . ">" . $dep->nombre . "</option>";
    }
    return $opciones;
}

/**
 * Segundos que faltan para caducar por inactividad.
 */
function tiempoRestante(): int
{
    $restante = TIEMPO_INACTIVIDAD - (time() - $_SESSION['ultimo']);
    if ($restante < 0) {
        $restante = 0;
    }
    return $restante;
}

/*
 *  Funciones para limpiar la entrada (las de clase)
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
        // Mejora: si el valor es un array (checkbox[]), no se toca
        if (is_string($value)) {
            $entrada[$key] = limpiarEntrada($value);
        }
    }
}
