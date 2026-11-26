<?php
/*
 * Ayuda para VER los resultados. No es parte de lo que hay que estudiar.
 *  - La página se muestra como texto plano: los "\n" son saltos de línea.
 *  - mostrar($array) enseña un array en una línea CON sus claves:
 *        [0 => kiwi, 1 => pera]      [Ana => 7.5, Luis => 4]
 */
header('Content-Type: text/plain; charset=utf-8');
date_default_timezone_set('Europe/Madrid');

function mostrar(array $a): void
{
    $partes = [];
    foreach ($a as $clave => $valor) {
        if (is_array($valor)) {
            $valor = "[" . implode(", ", $valor) . "]";
        } elseif (is_bool($valor)) {
            $valor = $valor ? "true" : "false";
        } elseif (is_null($valor)) {
            $valor = "null";
        }
        $partes[] = $clave . " => " . $valor;
    }
    echo "[" . implode(", ", $partes) . "]\n";
}

function titulo(string $t): void
{
    echo "\n==================== " . $t . " ====================\n";
}
