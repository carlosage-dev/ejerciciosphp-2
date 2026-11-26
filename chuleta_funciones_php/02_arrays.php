<?php
/*
 * =====================================================================
 *  02 · FUNCIONES DE ARRAYS
 * =====================================================================
 *  LO MÁS IMPORTANTE: hay dos tipos de funciones.
 *
 *  [MODIFICA]  Cambian el array ORIGINAL y NO devuelven el array.
 *              sort, rsort, asort, arsort, ksort, krsort, usort, shuffle,
 *              array_push, array_pop, array_shift, array_unshift, unset
 *              ✗ MAL:  $ordenado = sort($a);   // $ordenado vale true, no el array
 *              ✓ BIEN: sort($a);               // y luego usas $a
 *              ✓ Si quieres conservar el original: $copia = $a; sort($copia);
 *
 *  [NUEVO]     NO tocan el original: DEVUELVEN un array/valor nuevo.
 *              Hay que GUARDARLO en una variable (o usarlo directamente).
 *              array_reverse, array_slice, array_merge, array_unique, array_values,
 *              array_keys, array_filter, array_map, array_column, array_sum, count...
 *              ✗ MAL:  array_reverse($a);       // no hace nada con $a
 *              ✓ BIEN: $alReves = array_reverse($a);
 * =====================================================================
 */
require_once '_ver.php';
require_once 'datos.php';

$frutas = ["pera", "manzana", "kiwi", "uva"];
$notas  = ["Ana" => 7.5, "Luis" => 4, "Marta" => 9, "Pablo" => 5.5];

// =====================================================================
titulo("1. Contar y comprobar");
// =====================================================================
echo count($frutas) . "\n";                      // → 4
echo count($departamentos) . "\n";               // → 5
echo count($empleados["Logística"]) . "\n";      // → 5
var_dump(empty([]));                             // → bool(true)   array vacío

// ¿Está este VALOR?  in_array → true / false
var_dump(in_array("kiwi", $frutas));             // → bool(true)
var_dump(in_array("melón", $frutas));            // → bool(false)
// Uso típico: lista blanca de valores permitidos
$puestos = ["Jefe", "Programador", "Becario"];
var_dump(in_array("Becario", $puestos));         // → bool(true)

// ¿Existe esta CLAVE?  isset / array_key_exists
var_dump(isset($notas["Marta"]));                // → bool(true)
var_dump(isset($departamentos["Calidad"]));      // → bool(false)
var_dump(array_key_exists("Ana", $notas));       // → bool(true)

// ¿En qué posición / clave está este valor?  array_search → clave o false
var_dump(array_search("kiwi", $frutas));         // → int(2)
var_dump(array_search(9, $notas));               // → string(5) "Marta"
var_dump(array_search("pera", $frutas));         // → int(0)   ¡OJO! 0 == false
if (array_search("pera", $frutas) !== false) {   // por eso siempre !== false
    echo "pera está\n";                          // → pera está
}

// =====================================================================
titulo("2. Añadir y quitar   [MODIFICA el original]");
// =====================================================================
$lista = ["pan", "leche"];

$lista[] = "huevos";                             // al final
mostrar($lista);                                 // → [0 => pan, 1 => leche, 2 => huevos]

array_push($lista, "sal", "agua");               // al final (varios a la vez)
mostrar($lista);                                 // → [0 => pan, 1 => leche, 2 => huevos, 3 => sal, 4 => agua]

array_unshift($lista, "café");                   // al PRINCIPIO
mostrar($lista);                                 // → [0 => café, 1 => pan, 2 => leche, 3 => huevos, 4 => sal, 5 => agua]

$ultimo = array_pop($lista);                     // quita el último Y LO DEVUELVE
echo "Quitado: " . $ultimo . "\n";               // → Quitado: agua

$primero = array_shift($lista);                  // quita el primero Y LO DEVUELVE
echo "Quitado: " . $primero . "\n";              // → Quitado: café
mostrar($lista);                                 // → [0 => pan, 1 => leche, 2 => huevos, 3 => sal]

unset($lista[1]);                                // quita por posición: ¡deja un HUECO!
mostrar($lista);                                 // → [0 => pan, 2 => huevos, 3 => sal]

$lista = array_values($lista);                   // [NUEVO] renumera 0, 1, 2...
mostrar($lista);                                 // → [0 => pan, 1 => huevos, 2 => sal]

// Quitar por VALOR (no sabes la posición)
$pos = array_search("huevos", $lista);
if ($pos !== false) {
    unset($lista[$pos]);
}
$lista = array_values($lista);
mostrar($lista);                                 // → [0 => pan, 1 => sal]

// Asociativos: añadir/cambiar es asignar con la clave
$notas2 = $notas;                                // copia para no tocar $notas
$notas2["Sara"] = 8;                             // añade
$notas2["Luis"] = 5;                             // cambia
unset($notas2["Pablo"]);                         // quita
mostrar($notas2);                                // → [Ana => 7.5, Luis => 5, Marta => 9, Sara => 8]

// =====================================================================
titulo("3. Ordenar   [MODIFICA el original y devuelve true]");
// =====================================================================
// Si quieres conservar el original, ordena una COPIA.

$a = $frutas;
sort($a);                                        // de A a Z, RENUMERA las claves
mostrar($a);                                     // → [0 => kiwi, 1 => manzana, 2 => pera, 3 => uva]

$a = $frutas;
rsort($a);                                       // de Z a A
mostrar($a);                                     // → [0 => uva, 1 => pera, 2 => manzana, 3 => kiwi]

$numeros = [15, 4, 42, 8];
sort($numeros);
mostrar($numeros);                               // → [0 => 4, 1 => 8, 2 => 15, 3 => 42]

// ✗ El error típico:
$mal = sort($numeros);
var_dump($mal);                                  // → bool(true)   ¡no es el array!

// ASOCIATIVOS: asort / arsort ordenan por VALOR y MANTIENEN la clave
$n = $notas;
asort($n);                                       // de menor a mayor nota
mostrar($n);                                     // → [Luis => 4, Pablo => 5.5, Ana => 7.5, Marta => 9]

$n = $notas;
arsort($n);                                      // de mayor a menor nota
mostrar($n);                                     // → [Marta => 9, Ana => 7.5, Pablo => 5.5, Luis => 4]

$n = $notas;
rsort($n);                                       // ✗ con sort/rsort se PIERDEN los nombres
mostrar($n);                                     // → [0 => 9, 1 => 7.5, 2 => 5.5, 3 => 4]

// ksort / krsort ordenan por CLAVE
$d = $departamentos;
ksort($d);
echo implode(", ", array_keys($d)) . "\n";       // → Informática, Logística, Marketing, Recursos Humanos, Ventas

$d = $departamentos;
krsort($d);
echo implode(", ", array_keys($d)) . "\n";       // → Ventas, Recursos Humanos, Marketing, Logística, Informática

// Mezclar al azar
$a = $frutas;
shuffle($a);                                     // → cada vez un orden distinto

// =====================================================================
titulo("4. Ordenar un array de ARRAYS por un campo: usort");
// =====================================================================
// usort necesita una función que compare dos elementos y devuelva:
//   negativo si $a va antes, positivo si $b va antes, 0 si da igual.
// El operador <=> (nave espacial) hace justo eso.
function porSalarioDesc(array $a, array $b): int
{
    return $b["Salario"] <=> $a["Salario"];      // $b primero = de MAYOR a menor
}
function porNombre(array $a, array $b): int
{
    return strcmp($a["Nombre"], $b["Nombre"]);   // alfabético
}

$ventas = $empleados["Ventas"];                  // copia de la lista de Ventas
usort($ventas, "porSalarioDesc");                // [MODIFICA] $ventas
foreach ($ventas as $emp) {
    echo $emp["Nombre"] . " " . $emp["Salario"] . " | ";
}
echo "\n";  // → Pablo Soler 3000 | Elena Ríos 2000 | Carmen Ortiz 1900 | Raúl Díaz 1800 |

usort($ventas, "porNombre");
foreach ($ventas as $emp) {
    echo $emp["Nombre"] . " | ";
}
echo "\n";  // → Carmen Ortiz | Elena Ríos | Pablo Soler | Raúl Díaz |

// Ordenar $departamentos por presupuesto manteniendo el nombre: uasort (como asort pero con función)
function porPresupuesto(array $a, array $b): int
{
    return $b["Presupuesto"] <=> $a["Presupuesto"];
}
$d = $departamentos;
uasort($d, "porPresupuesto");
echo implode(", ", array_keys($d)) . "\n";       // → Ventas, Informática, Logística, Marketing, Recursos Humanos

// =====================================================================
titulo("5. Dar la vuelta, trozos, unir   [NUEVO: guárdalo en una variable]");
// =====================================================================
$alReves = array_reverse($frutas);
mostrar($alReves);                               // → [0 => uva, 1 => kiwi, 2 => manzana, 3 => pera]
mostrar($frutas);                                // → [0 => pera, 1 => manzana, 2 => kiwi, 3 => uva]   (intacto)

mostrar(array_reverse($notas));                  // → [Pablo => 5.5, Marta => 9, Luis => 4, Ana => 7.5]  (claves de texto se mantienen)

// array_slice(array, desde, cuántos)
mostrar(array_slice($frutas, 0, 2));             // → [0 => pera, 1 => manzana]   los 2 primeros
mostrar(array_slice($frutas, -2));               // → [0 => kiwi, 1 => uva]       los 2 últimos
mostrar(array_slice($frutas, 1, 2));             // → [0 => manzana, 1 => kiwi]

// Unir arrays
$verduras = ["tomate", "kiwi"];
$todo = array_merge($frutas, $verduras);
mostrar($todo);                                  // → [0 => pera, 1 => manzana, 2 => kiwi, 3 => uva, 4 => tomate, 5 => kiwi]

// Quitar repetidos (deja huecos: renumera con array_values)
mostrar(array_values(array_unique($todo)));      // → [0 => pera, 1 => manzana, 2 => kiwi, 3 => uva, 4 => tomate]

// Primero y último sin bucle
echo $frutas[0] . " / " . end($frutas) . "\n";   // → pera / uva
echo array_key_first($notas) . " / " . array_key_last($notas) . "\n";   // → Ana / Pablo

// =====================================================================
titulo("6. Claves, valores y columnas   [NUEVO]");
// =====================================================================
mostrar(array_keys($notas));                     // → [0 => Ana, 1 => Luis, 2 => Marta, 3 => Pablo]
mostrar(array_values($notas));                   // → [0 => 7.5, 1 => 4, 2 => 9, 3 => 5.5]

// Nombres de todos los departamentos (son las claves)
echo implode(", ", array_keys($departamentos)) . "\n";
// → Informática, Ventas, Marketing, Recursos Humanos, Logística

// array_column: saca UNA columna de un array de arrays
mostrar(array_column($empleados["Ventas"], "Nombre"));
// → [0 => Pablo Soler, 1 => Carmen Ortiz, 2 => Raúl Díaz, 3 => Elena Ríos]
mostrar(array_column($departamentos, "Jefe"));
// → [0 => Marta Ruiz, 1 => Pablo Soler, 2 => Lucía Prieto, 3 => Andrés Gil, 4 => Irene Cano]
// Con un tercer parámetro: la columna que hará de CLAVE
mostrar(array_column($empleados["Marketing"], "Salario", "Nombre"));
// → [Lucía Prieto => 2800, Álvaro Nieto => 2000, Nuria Pardo => 1900]

// array_combine: un array de claves + uno de valores
mostrar(array_combine(["a", "b"], [1, 2]));      // → [a => 1, b => 2]

// array_flip: intercambia claves y valores
mostrar(array_flip(["pan" => 1, "sal" => 2]));   // → [1 => pan, 2 => sal]

// =====================================================================
titulo("7. Calcular   [NUEVO: devuelven un número]");
// =====================================================================
echo array_sum($notas) . "\n";                   // → 26
echo max($notas) . "\n";                         // → 9
echo min($notas) . "\n";                         // → 4
echo array_sum($notas) / count($notas) . "\n";   // → 6.5   (media)
echo array_search(max($notas), $notas) . "\n";   // → Marta   (quién tiene la máxima)

$salariosVentas = array_column($empleados["Ventas"], "Salario");
echo array_sum($salariosVentas) . "\n";          // → 8700
echo max($salariosVentas) . "\n";                // → 3000

// Cuántas veces sale cada valor
mostrar(array_count_values(["php", "sql", "php", "css", "php"]));   // → [php => 3, sql => 1, css => 1]

// =====================================================================
titulo("8. Transformar y filtrar con una función   [NUEVO]");
// =====================================================================
// array_map: aplica una función a cada elemento → array del mismo tamaño
mostrar(array_map("strtoupper", $frutas));       // → [0 => PERA, 1 => MANZANA, 2 => KIWI, 3 => UVA]
mostrar(array_map("trim", ["  a ", " b"]));      // → [0 => a, 1 => b]

// array_filter: se queda con los que cumplen la condición (mantiene las claves)
function esAprobado($nota): bool
{
    return $nota >= 5;
}
mostrar(array_filter($notas, "esAprobado"));     // → [Ana => 7.5, Marta => 9, Pablo => 5.5]

// Sin función: quita los valores "vacíos" ("", 0, null, false)
mostrar(array_values(array_filter(["a", "", "b", null, "c"])));   // → [0 => a, 1 => b, 2 => c]

// Lo mismo con un foreach (por si no te acuerdas de array_filter):
$aprobados = [];
foreach ($notas as $nombre => $nota) {
    if ($nota >= 5) {
        $aprobados[$nombre] = $nota;
    }
}
mostrar($aprobados);                             // → [Ana => 7.5, Marta => 9, Pablo => 5.5]

// =====================================================================
titulo("9. Array ↔ texto   [NUEVO]");
// =====================================================================
echo implode(", ", $frutas) . "\n";              // → pera, manzana, kiwi, uva
mostrar(explode(",", "Teclado,Ratón,Monitor"));  // → [0 => Teclado, 1 => Ratón, 2 => Monitor]
mostrar(range(1, 5));                            // → [0 => 1, 1 => 2, 2 => 3, 3 => 4, 4 => 5]

// =====================================================================
titulo("10. Copiar un array");
// =====================================================================
// Con ARRAYS, asignar = COPIAR. Cambiar la copia no toca el original.
$original = [1, 2, 3];
$copia = $original;
$copia[] = 4;
mostrar($original);                              // → [0 => 1, 1 => 2, 2 => 3]
mostrar($copia);                                 // → [0 => 1, 1 => 2, 2 => 3, 3 => 4]
// (Con OBJETOS no pasa: ver 04_objetos.php)
