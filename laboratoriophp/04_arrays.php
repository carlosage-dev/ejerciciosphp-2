<?php
require_once '_lab/lab.php';
cabecera("Arrays", "Listas de datos. Los vas a usar para todo: lo que llega de un formulario, los resultados de una consulta, las listas de la sesión...");

seccion("Chuleta rápida");
chuleta([
    ['$a = [1, 2, 3];', 'Array <b>indexado</b> (posiciones 0, 1, 2...)'],
    ['$a = ["clave" => "valor"];', 'Array <b>asociativo</b> (tú eliges las claves)'],
    ['$a[] = $x;  /  array_push($a, $x)', 'Añade al <b>final</b>'],
    ['array_unshift($a, $x)', 'Añade al <b>principio</b>'],
    ['array_pop($a)  /  array_shift($a)', 'Quita el último / el primero (y lo devuelve)'],
    ['unset($a[$pos])', 'Quita un elemento concreto (las posiciones NO se renumeran)'],
    ['array_values($a)', 'Renumera las posiciones 0, 1, 2...'],
    ['count($a)', 'Cuántos elementos tiene'],
    ['in_array($x, $a)', '¿Está ese <b>valor</b>? → true/false'],
    ['array_search($x, $a)', 'Posición/clave de ese valor, o <code>false</code> (compara con <code>!== false</code>)'],
    ['isset($a[$clave])  /  array_key_exists()', '¿Existe esa <b>clave</b>?'],
    ['array_keys($a)  /  array_values($a)', 'Solo las claves / solo los valores'],
    ['sort / rsort', 'Ordena por valor (↑ / ↓) y <b>renumera</b> las claves'],
    ['asort / arsort', 'Ordena por valor (↑ / ↓) <b>manteniendo</b> las claves'],
    ['ksort / krsort', 'Ordena por <b>clave</b> (↑ / ↓)'],
    ['array_sum / max / min', 'Suma, máximo, mínimo'],
    ['array_slice($a, inicio, cuántos)', 'Copia un trozo'],
    ['array_merge($a, $b)', 'Une dos arrays'],
    ['array_unique($a)', 'Quita repetidos'],
    ['array_count_values($a)', 'Cuenta cuántas veces sale cada valor'],
    ['array_reverse($a)', 'Le da la vuelta'],
    ['implode(",", $a)  /  explode(",", $t)', 'Array → texto / texto → array'],
    ['print_r($a)  /  var_dump($a)', 'Ver el contenido (para depurar)'],
]);

// =====================================================================
seccion("1. Crear y acceder");
// =====================================================================

demo("Array indexado",
    'Las posiciones empiezan en <b>0</b>. <code>print_r</code> enseña el contenido de forma legible.',
<<<'PHP'
$frutas = ["manzana", "pera", "plátano"];

echo $frutas[0] . "\n";           // la primera
echo $frutas[2] . "\n";           // la tercera
echo count($frutas) . " frutas\n";
$frutas[1] = "kiwi";              // cambiar un valor
print_r($frutas);
PHP,
    'muestra la ÚLTIMA fruta sin escribir el número 2 (pista: <code>count($frutas) - 1</code>).');

demo("Array asociativo (clave => valor)",
    'Tú eliges las claves. Es como funcionan <code>$_GET</code>, <code>$_POST</code>, <code>$_SESSION</code> y <code>$_COOKIE</code>.',
<<<'PHP'
$usuarios = [
    "admin"  => "1234",
    "carlos" => "php",
];

echo "Clave de carlos: " . $usuarios["carlos"] . "\n";
$usuarios["profe"] = "daw2";      // añadir una clave nueva
print_r($usuarios);
PHP,
    'cambia la clave de "admin" a "4321" y comprueba con print_r.');

demo("Array de arrays (como filas de una tabla)",
    'Cada elemento es otro array. Para llegar a un dato: <b>primero la fila, luego la columna</b>.',
<<<'PHP'
$productos = [
    "P01" => ["nombre" => "Teclado", "precio" => 25,  "stock" => 10],
    "P02" => ["nombre" => "Ratón",   "precio" => 12,  "stock" => 0],
    "P03" => ["nombre" => "Monitor", "precio" => 180, "stock" => 2],
];

echo $productos["P01"]["nombre"] . "\n";
echo $productos["P03"]["precio"] . " €\n";

$p = $productos["P02"];           // atajo: guardo la fila entera
echo $p["nombre"] . " tiene " . $p["stock"] . " uds.";
PHP,
    'escribe "Monitor cuesta 180 €" usando <code>$productos</code>.');

// =====================================================================
seccion("2. Recorrer con foreach");
// =====================================================================

demo("foreach solo valor / clave => valor",
    'Si necesitas también la <b>clave</b> (el id, la posición, el nombre de usuario...) usa <code>$clave => $valor</code>.',
<<<'PHP'
$notas = ["Ana" => 7.5, "Luis" => 4, "Marta" => 9];

foreach ($notas as $nota) {
    echo $nota . " ";
}
echo "\n";

foreach ($notas as $nombre => $nota) {
    echo $nombre . " sacó un " . $nota . "\n";
}
PHP,
    'escribe solo los aprobados (nota >= 5) con su nombre.');

demo("foreach con array de arrays",
    'Con <code>$id => $producto</code> tienes la clave ("P01") y la fila entera. Así se generan enlaces <code>?id=P01</code> o <code>&lt;option&gt;</code>.',
<<<'PHP'
$productos = [
    "P01" => ["nombre" => "Teclado", "precio" => 25],
    "P02" => ["nombre" => "Ratón",   "precio" => 12],
];

foreach ($productos as $id => $producto) {
    echo $id . " → " . $producto["nombre"] . " (" . $producto["precio"] . " €)\n";
}
PHP,
    'genera el texto de un enlace: <code>&lt;a href="?id=P01"&gt;Teclado&lt;/a&gt;</code> para cada producto.');

demo("Acumular dentro del bucle (suma, contador, máximo)",
    'Patrón típico: variable a 0 <b>antes</b> del bucle, se va sumando dentro y se usa <b>después</b>.',
<<<'PHP'
$sueldos = [3200, 2100, 1950, 850];

$total = 0;
$cuantos = 0;
foreach ($sueldos as $s) {
    $total += $s;
    $cuantos++;
}
echo "Total: $total\n";
echo "Media: " . ($total / $cuantos);
PHP,
    'calcula también el sueldo más alto con un <code>if</code> dentro del bucle (sin usar <code>max</code>).');

// =====================================================================
seccion("3. Añadir y quitar");
// =====================================================================

demo("Añadir: [] , array_push, array_unshift",
    '<code>$a[] = x</code> y <code>array_push</code> añaden al final. <code>array_unshift</code> al principio.',
<<<'PHP'
$lista = ["pan"];

$lista[] = "leche";
array_push($lista, "huevos");
array_unshift($lista, "café");

print_r($lista);
PHP,
    'añade "agua" al principio y "sal" al final.');

demo("Quitar: unset, array_pop, array_shift",
    '<code>unset</code> quita por posición, pero <b>deja un hueco</b> en la numeración. <code>array_values</code> la arregla.',
<<<'PHP'
$lista = ["café", "pan", "leche", "huevos"];

unset($lista[1]);              // quita "pan"
print_r($lista);               // fíjate: 0, 2, 3

$lista = array_values($lista); // renumera
print_r($lista);

$ultimo = array_pop($lista);   // quita y devuelve el último
echo "Quitado: $ultimo\n";
PHP,
    'quita el primero con <code>array_shift</code> y muéstralo.');

demo("Quitar un elemento por su VALOR",
    'No sabes la posición, solo el valor: búscala con <code>array_search</code> y luego <code>unset</code>.',
<<<'PHP'
$lista = ["pan", "leche", "huevos"];

$pos = array_search("leche", $lista);
if ($pos !== false) {
    unset($lista[$pos]);
}
$lista = array_values($lista);
print_r($lista);
PHP,
    'intenta quitar "agua" (que no está) y comprueba que no pasa nada.');

// =====================================================================
seccion("4. Buscar y comprobar");
// =====================================================================

demo("in_array (¿está el valor?)",
    'Devuelve true/false. Úsalo para <b>listas blancas</b>: comprobar que lo que llega es uno de los valores permitidos.',
<<<'PHP'
$temasPermitidos = ["claro", "oscuro"];

$tema = "rosa";   // imagina que viene de $_POST
if (in_array($tema, $temasPermitidos)) {
    echo "Tema válido";
} else {
    echo "Tema NO válido";
}
PHP,
    'cambia <code>$tema</code> a "oscuro".');

demo("array_search (¿en qué posición?) y la trampa del 0",
    'Devuelve la posición o <code>false</code>. Si está en la posición <b>0</b>, <code>0 == false</code> sería true: compara siempre con <code>!== false</code>.',
<<<'PHP'
$lista = ["pan", "leche"];

$pos = array_search("pan", $lista);
echo "Posición: ";
var_dump($pos);

if ($pos == false) {
    echo "MAL: con == dice que no está (¡pero está en la 0!)\n";
}
if ($pos !== false) {
    echo "BIEN: con !== false sí lo encuentra";
}
PHP,
    'busca "agua" y haz var_dump del resultado.');

demo('isset($array[$clave]) (¿existe la clave?)',
    'El patrón más importante del examen: <b>antes de usar <code>$array[$loQueLlega]</code>, comprueba que existe</b>.',
<<<'PHP'
$productos = ["P01" => "Teclado", "P02" => "Ratón"];

$id = "P99";   // imagina que viene de $_GET['id']
if (isset($productos[$id])) {
    echo "Producto: " . $productos[$id];
} else {
    echo "Producto no encontrado";
}
echo "\n";
var_dump(array_key_exists("P01", $productos));
PHP,
    'cambia <code>$id</code> a "P01".');

// =====================================================================
seccion("5. Ordenar", "Las 6 funciones de ordenar cambian el array <b>original</b> (no devuelven otro).");
// =====================================================================

demo("sort y rsort (pierden las claves)",
    'Ordenan los valores y <b>renumeran</b> 0, 1, 2... Bien para listas simples.',
<<<'PHP'
$numeros = [15, 4, 42, 8];

sort($numeros);
echo "sort:  " . implode(", ", $numeros) . "\n";

rsort($numeros);
echo "rsort: " . implode(", ", $numeros);
PHP,
    'ordena alfabéticamente <code>["pera", "kiwi", "manzana"]</code>.');

demo("asort y arsort (mantienen las claves)",
    'Ordenan por valor pero cada valor <b>conserva su clave</b>. Imprescindible en arrays asociativos (si no, pierdes los nombres).',
<<<'PHP'
$notas = ["Ana" => 7.5, "Luis" => 4, "Marta" => 9];

arsort($notas);           // de mayor a menor nota
print_r($notas);

$mal = ["Ana" => 7.5, "Luis" => 4, "Marta" => 9];
rsort($mal);              // ¡se pierden los nombres!
print_r($mal);
PHP,
    'ordena <code>$notas</code> de menor a mayor con <code>asort</code>.');

demo("ksort y krsort (por clave)",
    'Ordenan por la <b>clave</b> en vez de por el valor.',
<<<'PHP'
$stock = ["Teclado" => 10, "Cascos" => 5, "Monitor" => 2];

ksort($stock);
print_r($stock);
PHP,
    'usa <code>krsort</code> y mira el orden.');

// =====================================================================
seccion("6. Calcular y transformar");
// =====================================================================

demo("count, array_sum, max, min",
    'Atajos para no tener que hacer el bucle. <code>max</code> con <code>array_search</code> te da QUIÉN tiene el máximo.',
<<<'PHP'
$notas = ["Ana" => 7.5, "Luis" => 4, "Marta" => 9];

echo "Alumnos: " . count($notas) . "\n";
echo "Suma: " . array_sum($notas) . "\n";
echo "Media: " . round(array_sum($notas) / count($notas), 2) . "\n";
echo "Máxima: " . max($notas) . "\n";
echo "Mínima: " . min($notas) . "\n";
echo "Mejor alumno: " . array_search(max($notas), $notas);
PHP,
    'saca quién tiene la nota más baja.');

demo("array_keys y array_values",
    'Te quedas solo con las claves o solo con los valores.',
<<<'PHP'
$usuarios = ["admin" => "1234", "carlos" => "php"];

print_r(array_keys($usuarios));
print_r(array_values($usuarios));
echo "Usuarios: " . implode(", ", array_keys($usuarios));
PHP,
    'cuenta cuántos usuarios hay usando <code>array_keys</code>.');

demo("array_slice (copiar un trozo)",
    '<code>array_slice($a, desde, cuántos)</code>. Se usaba para quedarse con los <b>3 últimos vistos</b>.',
<<<'PHP'
$vistos = ["Monitor", "Teclado", "Ratón", "Cascos", "Portátil"];

$tresPrimeros = array_slice($vistos, 0, 3);
print_r($tresPrimeros);
PHP,
    'quédate con los 2 últimos (pista: el inicio puede ser negativo, <code>-2</code>).');

demo("array_merge, array_unique, array_reverse",
    'Unir arrays, quitar repetidos y dar la vuelta.',
<<<'PHP'
$a = ["pan", "leche"];
$b = ["leche", "huevos"];

$todo = array_merge($a, $b);
echo "merge:   " . implode(", ", $todo) . "\n";
echo "unique:  " . implode(", ", array_unique($todo)) . "\n";
echo "reverse: " . implode(", ", array_reverse($todo));
PHP,
    'une tres arrays a la vez con <code>array_merge</code>.');

demo("array_count_values (y lo mismo con bucle)",
    'Cuenta cuántas veces aparece cada valor. Debajo, la versión con bucle por si te piden recorrer.',
<<<'PHP'
$votos = ["php", "sql", "php", "html", "sql", "php"];
print_r(array_count_values($votos));

// Con bucle:
$cuenta = [];
foreach ($votos as $v) {
    if (isset($cuenta[$v])) {
        $cuenta[$v]++;
    } else {
        $cuenta[$v] = 1;
    }
}
print_r($cuenta);
PHP,
    'cuenta las letras de "banana" (pista: <code>str_split("banana")</code> da un array de letras).');

demo("Montar un array \"mapa\" (clave => valor) a partir de otro",
    'El truco del Ej2 y Ej3: para pintar el <b>nombre</b> del departamento en vez del código, se monta un array <code>cod => nombre</code> UNA vez.',
<<<'PHP'
$departamentos = [
    ["cod_dep" => 1, "nombre" => "Desarrollo"],
    ["cod_dep" => 2, "nombre" => "Sistemas"],
    ["cod_dep" => 3, "nombre" => "Administración"],
];

$nombresDep = [];
foreach ($departamentos as $d) {
    $nombresDep[$d["cod_dep"]] = $d["nombre"];
}
print_r($nombresDep);

$empleadoCodDep = 2;
echo "Trabaja en: " . $nombresDep[$empleadoCodDep];
PHP,
    'monta al revés el mapa <code>nombre => cod_dep</code>.');

demo("implode y explode (array ↔ texto)",
    '<code>implode</code> une un array en un texto. <code>explode</code> parte un texto en un array. Así se guarda una lista en una cookie.',
<<<'PHP'
$lista = ["Teclado", "Ratón", "Monitor"];

$texto = implode(",", $lista);
echo "Texto: $texto\n";

$deVuelta = explode(",", $texto);
print_r($deVuelta);
PHP,
    'parte la fecha <code>"2026-11-26"</code> con explode y escríbela como 26/11/2026.');

pie();
