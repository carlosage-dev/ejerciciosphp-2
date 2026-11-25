<?php
/*
 * NO TOCAR. Pequeño "corrector automático":
 * compara lo que devuelve tu función con lo esperado y pinta ✔ o ✘.
 * Usa === (mismo valor Y mismo tipo, y en los arrays también el mismo orden).
 */
$GLOBALS['aciertos'] = 0;
$GLOBALS['total'] = 0;

function comprobar(string $descripcion, $obtenido, $esperado)
{
    $GLOBALS['total']++;
    if ($obtenido === $esperado) {
        $GLOBALS['aciertos']++;
        echo "<p style='color:green;margin:2px'>✔ $descripcion</p>";
    } else {
        echo "<p style='color:#c00;margin:2px'>✘ $descripcion<br>"
           . "&nbsp;&nbsp;&nbsp;Esperado: <code>" . htmlspecialchars(var_export($esperado, true)) . "</code><br>"
           . "&nbsp;&nbsp;&nbsp;Obtenido: <code>" . htmlspecialchars(var_export($obtenido, true)) . "</code></p>";
    }
}

function resumen()
{
    echo "<h3>Resultado: {$GLOBALS['aciertos']} / {$GLOBALS['total']}</h3>";
}
