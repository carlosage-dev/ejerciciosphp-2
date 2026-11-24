<?php
require_once 'AccesoDatos.php';

$db  = AccesoDatos::initModelo();
$msg = "";

// =====================================================================
//  FUNCIONES
// =====================================================================

/**
 * TODO A  (usa switch(true))
 * @return string "OK" | "AL LÍMITE" | "EXCEDIDO"
 */
function estadoPresupuesto(float $gasto, float $presupuesto): string
{
    return "";
}

/**
 * TODO B  (usa switch normal)
 * "OK" -> "ok", "AL LÍMITE" -> "limite", "EXCEDIDO" -> "excedido"
 */
function claseEstado(string $estado): string
{
    return "";
}

/**
 * TODO C
 * Lista blanca: traduce lo que llega en ?orden= a un ORDER BY seguro.
 *   "nombre"    -> por nombre del departamento (ascendente)   [por defecto]
 *   "gasto"     -> por gasto descendente
 *   "empleados" -> por nº de empleados descendente
 */
function columnaOrden(string $orden): string
{
    return "";
}

/**
 * TODO D
 * Devuelve la tabla resumen construida con $html .= ...
 * Cada array de $resumen es asociativo: $fila['nombre'], $fila['gasto']...
 * @param string $orden  el orden actual, para no perderlo en los enlaces ?orden=..&dep=..
 */
function pintarResumen(array $resumen, string $orden): string
{
    $html = "";

    return $html;
}

// =====================================================================
//  CONTROLADOR
// =====================================================================

// 1. Orden (GET)
$orden = "nombre";

// 2. POST Ampliar  (comprobar de nuevo en servidor)


// 3. Departamento a detallar: ?dep  o  cookie 'ultimodep'
$depDetalle  = null;    // objeto Departamento o null
$templeados  = [];
$gastoDep    = 0;
$desdeCookie = false;


// 4. Resumen (siempre al final, para que refleje los cambios del POST)
$resumen = [];

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resumen de departamentos</title>
    <link href="default.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="container">
    <div id="header"><h1>RESUMEN DE DEPARTAMENTOS</h1></div>

    <p>Ordenar por:
        <a href="?orden=nombre">Nombre</a> |
        <a href="?orden=gasto">Gasto</a> |
        <a href="?orden=empleados">Nº empleados</a>
    </p>

    <!-- TODO: $msg -->

    <!-- TODO: echo pintarResumen(...) -->

    <!-- ===================== DETALLE ===================== -->
    <!-- TODO: si hay departamento elegido:
               título "Empleados de ..." (+ aviso si viene de la cookie)
               tabla con foreach: ... endforeach; y columna % del gasto
               o "Este departamento no tiene empleados"                     -->

</div>
</body>
</html>
