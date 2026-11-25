<?php

/**
 * Años COMPLETOS desde la fecha de alta ('AAAA-MM-DD') hasta hoy.
 * DateTime::diff() calcula la diferencia y ->y son los años completos.
 */
function antiguedad(string $fecha_alta): int
{
    $alta = new DateTime($fecha_alta);
    $hoy  = new DateTime();
    $diferencia = $alta->diff($hoy);
    return $diferencia->y;
}
