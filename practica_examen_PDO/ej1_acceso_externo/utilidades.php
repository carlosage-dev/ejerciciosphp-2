<?php

/**
 * TODO 5
 * Devuelve los años COMPLETOS que lleva el empleado en la empresa.
 * @param string $fecha_alta  fecha con formato de MySQL 'AAAA-MM-DD'
 * @return int
 *
 * Ejemplo (hoy es 24/11/2026):
 *   antiguedad('2015-03-01') -> 11
 *   antiguedad('2025-10-01') -> 1
 *   antiguedad('2026-09-01') -> 0
 */
function antiguedad(string $fecha_alta): int
{
$fechaAlta = DateTime::createFromFormat('Y-m-d', $fecha_alta);

// 2. Crea la fecha actual como objeto DateTime (en lugar de usar date())
$fechaActual = new DateTime(); //new DateTime('today') 'today' pone la hora a 00:00:00 para una comparación exacta de días

$diferencia = $fechaAlta->diff($fechaActual);
return $diferencia->y;
}
