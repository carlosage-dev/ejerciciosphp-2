<?php
require_once __DIR__ . '/modelo/AccesoDatos.php';

define('MAX_FALLOS', 3);
define('PORCENTAJE_PAGA_EXTRA', 5);

// =====================================================================
//  SESIÓN / ROLES
// =====================================================================

/** TODO: true si hay un empleado identificado en la sesión */
function estaIdentificado(): bool
{
    return false;
}

/** TODO: true si el empleado identificado es Jefe */
function esJefe(): bool
{
    return false;
}

// =====================================================================
//  LOGIN
// =====================================================================

/**
 * TODO: procesa el login completo (validaciones, intentos, bloqueo, sesión, accesos, cookie).
 * @param bool $recordar  checkbox "Recordar mi usuario" marcado o no
 * @return string ""  si el login es correcto, o el mensaje de error
 */
function procesarLogin(string $cod_emp, string $clave, bool $recordar): string
{
    return "";
}

// =====================================================================
//  PINTAR TABLA GENÉRICA
// =====================================================================

/**
 * TODO: Devuelve el HTML de una tabla a partir de un array de objetos.
 * @param array  $objetos     array de objetos (Empleado u otra clase con __get)
 * @param array  $columnas    ['atributo' => 'Título columna', ...]
 *                            ej: ['cod_emp' => 'Código', 'nombre' => 'Nombre']
 * @param string $campoCheck  si no es "", añade una 1ª columna con
 *                            <input type="checkbox" name="seleccion[]" value="$obj->$campoCheck">
 *                            (sin checkbox si la fila es del propio usuario o está bloqueado)
 */
function pintarTabla(array $objetos, array $columnas, string $campoCheck = ""): string
{
    $html = "";

    return $html;
}

// =====================================================================
//  ACCIONES
// =====================================================================

/**
 * TODO: Paga extra (solo jefe) a la lista de códigos recibidos.
 * Comprobar EN EL SERVIDOR por cada uno: su departamento, no él mismo, no bloqueado.
 * @return string mensaje "Paga extra aplicada a N empleado(s)"
 */
function pagaExtra(array $listaCod): string
{
    return "";
}

/**
 * TODO: Cambia la clave del empleado identificado.
 * @return string mensaje de error o de éxito
 */
function cambiarClave(string $actual, string $nueva, string $repetida): string
{
    return "";
}
