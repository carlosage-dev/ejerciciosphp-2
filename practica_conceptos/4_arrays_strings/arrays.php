<?php
/*
 * ================================================================
 *  4a · ARRAYS Y BUCLES                    (~3-5 min cada función)
 * ================================================================
 *  Rellena cada función. Abre este archivo en el navegador:
 *  abajo verás ✔ / ✘ para cada prueba. No toques las pruebas.
 *
 *  Intenta hacer algunas CON BUCLE (foreach / for) aunque exista una
 *  función de PHP que lo haga sola: en el examen te puede tocar recorrer.
 * ================================================================
 */
require_once '_test.php';

/** 1. Suma SOLO los números pares. (bucle + %) */
function sumaPares(array $nums): int
{
    return 0;
}

/** 2. Media de los números, como float. (array_sum, count) */
function media(array $nums): float
{
    return 0;
}

/** 3. Devuelve SOLO los alumnos aprobados (nota >= 5), manteniendo nombre => nota. */
function aprobados(array $notas): array
{
    return [];
}

/** 4. Nombre del alumno con la nota más alta. (max + array_search, o bucle) */
function mejorAlumno(array $notas): string
{
    return "";
}

/** 5. Mismo array ordenado de MAYOR a MENOR nota sin perder los nombres.
 *     Pista: hay 4 funciones parecidas: sort, rsort, asort, arsort. ¿Cuál mantiene la clave? */
function ordenarPorNota(array $notas): array
{
    return [];
}

/** 6. Cuenta cuántas veces sale cada palabra: ['php' => 3, 'sql' => 2, ...]  (CON BUCLE) */
function contarRepetidos(array $palabras): array
{
    return [];
}

/** 7. Posición del número buscado, o -1 si no está. (bucle con $i => $valor) */
function buscarPosicion(array $nums, int $buscado): int
{
    return 0;
}

/** 8. Devuelve el array al revés SIN usar array_reverse. (for hacia atrás) */
function invertir(array $nums): array
{
    return [];
}

/** 9. Quita el elemento con ese valor y devuelve el array con posiciones 0,1,2...
 *     Pista: array_search, unset, array_values */
function quitarElemento(array $lista, string $valor): array
{
    return [];
}

/** 10. Array de arrays (matriz): devuelve la suma de cada fila. (foreach dentro de foreach) */
function sumarFilas(array $matriz): array
{
    return [];
}

/** 11. Array de empleados (cada uno es un array asociativo):
 *      devuelve los NOMBRES de los que cobran más de $minimo. */
function nombresSueldoMayor(array $empleados, float $minimo): array
{
    return [];
}

// =====================================================================
//  PRUEBAS (no tocar)
// =====================================================================
$numeros = [4, 8, 15, 16, 23, 42];
$notas = ['Ana' => 7.5, 'Luis' => 4, 'Marta' => 9, 'Pablo' => 5.5, 'Sara' => 3];
$empleados = [
    ['nombre' => 'Ana',   'sueldo' => 3200],
    ['nombre' => 'Luis',  'sueldo' => 2100],
    ['nombre' => 'Pablo', 'sueldo' => 850],
    ['nombre' => 'Elena', 'sueldo' => 2300],
];

echo "<h2>Arrays</h2>";
comprobar("1. sumaPares", sumaPares($numeros), 70);
comprobar("2. media", media($numeros), 18.0);
comprobar("3. aprobados", aprobados($notas), ['Ana' => 7.5, 'Marta' => 9, 'Pablo' => 5.5]);
comprobar("4. mejorAlumno", mejorAlumno($notas), 'Marta');
comprobar("5. ordenarPorNota", ordenarPorNota($notas), ['Marta' => 9, 'Ana' => 7.5, 'Pablo' => 5.5, 'Luis' => 4, 'Sara' => 3]);
comprobar("6. contarRepetidos", contarRepetidos(['php', 'sql', 'php', 'html', 'sql', 'php']), ['php' => 3, 'sql' => 2, 'html' => 1]);
comprobar("7a. buscarPosicion (está)", buscarPosicion($numeros, 23), 4);
comprobar("7b. buscarPosicion (no está)", buscarPosicion($numeros, 99), -1);
comprobar("8. invertir", invertir($numeros), [42, 23, 16, 15, 8, 4]);
comprobar("9. quitarElemento", quitarElemento(['pan', 'leche', 'huevos'], 'leche'), ['pan', 'huevos']);
comprobar("10. sumarFilas", sumarFilas([[1, 2, 3], [4, 5, 6], [10, 0, 0]]), [6, 15, 10]);
comprobar("11. nombresSueldoMayor", nombresSueldoMayor($empleados, 2000), ['Ana', 'Luis', 'Elena']);
resumen();
