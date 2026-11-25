<?php
require_once '_lab/lab.php';
cabecera("Lo básico de PHP", "Lo que usas en <b>todos</b> los ejercicios: mostrar texto, tipos, comprobar si algo existe, comparar, convertir, decidir y repetir.");

// =====================================================================
seccion("1. Mostrar texto", "Con <code>echo</code> se escribe en la página. El punto <code>.</code> une textos.");
// =====================================================================

demo("echo y concatenar con .",
    'En PHP los textos se unen con el <b>punto</b> <code>.</code>, no con <code>+</code>. '
    . 'Con <code>.=</code> añades al final de lo que ya tenía la variable (así se montan las tablas con <code>$msg .=</code>).',
<<<'PHP'
$nombre = "Ana";
$edad = 20;

echo "Hola " . $nombre . ", tienes " . $edad . " años\n";

$texto = "Uno";
$texto .= ", dos";     // .= añade al final
$texto .= ", tres";
echo $texto;
PHP,
    'cambia el <code>.</code> de la primera línea de echo por un <code>+</code> y mira el error que da.');

demo("Comillas dobles vs simples",
    'Con comillas <b>dobles</b> PHP mete el valor de las variables dentro del texto. Con <b>simples</b>, no: lo escribe tal cual. '
    . 'Para meter un atributo de objeto o un array dentro de las dobles, usa llaves <code>{ }</code>.',
<<<'PHP'
$nombre = "Luis";
$datos = ["puesto" => "Becario"];

echo "Dobles: Hola $nombre\n";
echo 'Simples: Hola $nombre' . "\n";
echo "Con llaves: es {$datos['puesto']}\n";
echo "Salto de línea con \\n dentro de comillas dobles";
PHP,
    'escribe un echo con comillas dobles que diga "Luis es Becario" usando las dos variables.');

// =====================================================================
seccion("2. Tipos y comprobar si algo existe", "Lo que más fallos da en los ejercicios: <code>isset</code>, <code>empty</code> y las comparaciones.");
// =====================================================================

demo("Tipos de datos (var_dump)",
    '<code>var_dump()</code> enseña el <b>tipo</b> y el valor. Úsalo para depurar: <code>var_dump($_POST);</code> te dice exactamente qué ha llegado.',
<<<'PHP'
var_dump(25);          // int
var_dump(3.5);         // float
var_dump("25");        // string (¡un número entre comillas es TEXTO!)
var_dump(true);        // bool
var_dump(null);        // null
var_dump([1, 2]);      // array
echo gettype("hola");  // te dice solo el tipo
PHP,
    'haz <code>var_dump("3.5" + 1);</code> y mira de qué tipo es el resultado.');

demo("isset() vs empty() vs is_null()",
    '<code>isset</code> = "existe y no es null". <code>empty</code> = "no existe, o está vacío, o vale 0". '
    . '<b>Ojo:</b> <code>empty("0")</code> es <code>true</code>: si alguien escribe 0 en un formulario, <code>empty</code> lo trata como vacío.',
<<<'PHP'
$valores = ['""' => "", '"0"' => "0", '0' => 0, 'null' => null, '"hola"' => "hola", '[]' => []];

echo "<table class='tabla-lab'><tr><th>Valor</th><th>isset()</th><th>empty()</th><th>is_null()</th></tr>";
foreach ($valores as $texto => $v) {
    echo "<tr><td><code>$texto</code></td>";
    echo "<td>" . (isset($v) ? "✅ true" : "❌ false") . "</td>";
    echo "<td>" . (empty($v) ? "✅ true" : "❌ false") . "</td>";
    echo "<td>" . (is_null($v) ? "✅ true" : "❌ false") . "</td></tr>";
}
echo "</table>";
PHP,
    'añade <code>\'" "\' => " "</code> (un espacio) al array. ¿Es empty? Por eso se usa <code>trim()</code> antes de comprobar.', true);

caja('nota', 'Regla práctica', '<p>Para saber si un dato ha <b>llegado</b> (formulario, cookie, sesión): <code>isset()</code>. '
    . 'Para saber si está <b>vacío</b>: <code>$x == ""</code> o <code>empty()</code> (si no puede valer "0"). '
    . 'Y para campos numéricos: <code>$x == ""</code>, porque <code>empty("0")</code> da true.</p>');

demo("== (mismo valor) vs === (mismo valor Y mismo tipo)",
    '<code>==</code> compara el valor convirtiendo tipos. <code>===</code> exige también el mismo tipo. '
    . 'El fallo de la calculadora: <code>0.0 === 0</code> es false porque uno es float y otro int.',
<<<'PHP'
var_dump(5 == "5");      // true:  mismo valor
var_dump(5 === "5");     // false: int vs string
var_dump(0.0 == 0);      // true
var_dump(0.0 === 0);     // false: float vs int
var_dump(0 == false);    // true
var_dump(0 === false);   // false -> por eso array_search se compara con !== false
PHP,
    'comprueba qué da <code>null == false</code> y <code>null === false</code>.');

demo("Convertir tipos y is_numeric()",
    'Convierte con <code>(int)</code>, <code>(float)</code>, <code>(string)</code>. '
    . '<b>Primero valida con is_numeric() y luego convierte</b>: <code>(int) "abc"</code> da 0 sin avisar.',
<<<'PHP'
var_dump((int) "42");
var_dump((int) "3.9");     // corta los decimales
var_dump((int) "abc");     // 0 -> ¡por eso se valida ANTES!
var_dump((float) "2.5");
var_dump((string) 7);

var_dump(is_numeric("42"));
var_dump(is_numeric("4.2"));
var_dump(is_numeric("abc"));
var_dump(is_numeric(""));
PHP,
    'prueba <code>is_numeric(" 5")</code> (con espacio delante) y <code>is_numeric("5 ")</code> (detrás).');

// =====================================================================
seccion("3. Decidir: if, ternario, ?? y switch");
// =====================================================================

demo("if / elseif / else",
    'Se evalúan en orden y solo entra en el <b>primero</b> que se cumple. Las validaciones de un formulario se encadenan así: '
    . 'la primera que falla es la que sale.',
<<<'PHP'
$unidades = "abc";

if ($unidades == "") {
    echo "Error: unidades vacías";
} elseif (!is_numeric($unidades)) {
    echo "Error: no es un número";
} elseif ($unidades < 1) {
    echo "Error: mínimo 1";
} else {
    echo "OK: " . (int) $unidades . " unidades";
}
PHP,
    'cambia <code>$unidades</code> por <code>""</code>, <code>"0"</code> y <code>"3"</code> y mira qué rama se ejecuta cada vez.');

demo("Operador ternario  condición ? sí : no",
    'Es un <code>if/else</code> en una línea que <b>devuelve</b> un valor. Muy usado en el HTML: '
    . '<code>selected</code>, <code>checked</code>, <code>disabled</code>, clases CSS...',
<<<'PHP'
$edad = 17;
$texto = ($edad >= 18) ? "mayor de edad" : "menor de edad";
echo $texto . "\n";

$bloqueado = 1;
echo "Clase de la fila: " . (($bloqueado == 1) ? "bloqueado" : "normal");
PHP,
    'haz un ternario que escriba "Aprobado" o "Suspenso" según una variable <code>$nota</code>.');

demo("Operador ?? (valor por defecto)",
    '<code>$a ?? $b</code> = "si $a existe y no es null, $a; si no, $b". Ahorra un <code>isset</code>. '
    . 'Debajo tienes lo mismo escrito con <code>if</code>, por si prefieres esa forma.',
<<<'PHP'
$datos = ["nombre" => "Ana"];

echo $datos["nombre"] ?? "anónimo";
echo "\n";
echo $datos["apellido"] ?? "sin apellido";
echo "\n";

// Lo mismo, sin ??
if (isset($datos["apellido"])) {
    $apellido = $datos["apellido"];
} else {
    $apellido = "sin apellido";
}
echo $apellido;
PHP,
    'simula <code>$_GET</code> con <code>$get = ["dep" => "2"];</code> y saca <code>$get["dep"] ?? "todos"</code> y <code>$get["orden"] ?? "nombre"</code>.');

demo("switch",
    'Compara UNA variable con varios valores. Cada <code>case</code> termina con <code>break</code> (si no, sigue al siguiente). '
    . 'Es lo que usas con <code>$_GET[\'orden\']</code> o con varios botones con el mismo <code>name</code>.',
<<<'PHP'
$orden = "Borrar";

switch ($orden) {
    case "Nuevo":
        echo "Mostrar formulario vacío";
        break;
    case "Modificar":
    case "Editar":              // dos case seguidos = mismo código
        echo "Mostrar formulario relleno";
        break;
    case "Borrar":
        echo "Pedir confirmación";
        break;
    default:
        echo "Orden desconocida";
}
PHP,
    'quita el <code>break</code> del case "Borrar" y pon <code>$orden = "Nuevo"</code> sin su break. ¿Qué se escribe?');

demo("switch (true) para rangos",
    'Truco: con <code>switch (true)</code> cada <code>case</code> es una <b>condición</b>. Entra en la primera que sea verdadera.',
<<<'PHP'
function calificacion(float $n): string
{
    switch (true) {
        case $n < 5:
            return "Suspenso";
        case $n < 7:
            return "Aprobado";
        case $n < 9:
            return "Notable";
        default:
            return "Sobresaliente";
    }
}

echo calificacion(4.5) . "\n";
echo calificacion(6) . "\n";
echo calificacion(8.2) . "\n";
echo calificacion(10);
PHP,
    'añade un primer case <code>$n < 0 || $n > 10</code> que devuelva "Nota no válida".');

// =====================================================================
seccion("4. Repetir: bucles");
// =====================================================================

demo("for, while y foreach",
    '<code>for</code> cuando sabes cuántas vueltas, <code>while</code> mientras se cumpla algo, '
    . '<code>foreach</code> para recorrer arrays (el que más usarás).',
<<<'PHP'
for ($i = 1; $i <= 3; $i++) {
    echo "for: vuelta $i\n";
}

$n = 3;
while ($n > 0) {
    echo "while: quedan $n\n";
    $n--;
}

$frutas = ["manzana", "pera"];
foreach ($frutas as $fruta) {
    echo "foreach: $fruta\n";
}
foreach ($frutas as $pos => $fruta) {
    echo "posición $pos → $fruta\n";
}
PHP,
    'haz un for que escriba la tabla del 7 (7 x 1 = 7 ... 7 x 10 = 70).');

demo("break y continue",
    '<code>continue</code> salta a la siguiente vuelta. <code>break</code> sale del bucle. '
    . 'En <code>aplicarSubida()</code> del Ej2 se usaba <code>continue</code> para saltar a los bloqueados.',
<<<'PHP'
foreach ([1, 2, 3, 4, 5, 6] as $n) {
    if ($n == 2) {
        continue;      // salta el 2
    }
    if ($n == 5) {
        break;         // al llegar al 5 se acaba
    }
    echo $n . " ";
}
PHP,
    'recorre del 1 al 20 y escribe solo los múltiplos de 3 usando <code>continue</code>.');

// =====================================================================
seccion("5. Funciones y constantes");
// =====================================================================

demo("Funciones con tipos, valor por defecto y return",
    'Los tipos (<code>string</code>, <code>int</code>, <code>?int</code> = int o null...) ayudan a detectar errores. '
    . 'Un parámetro con <code>= valor</code> es opcional.',
<<<'PHP'
function saludar(string $nombre, string $saludo = "Hola"): string
{
    return $saludo . ", " . $nombre;
}

echo saludar("Ana") . "\n";
echo saludar("Luis", "Buenas");
PHP,
    'escribe una función <code>iva(float $precio, int $porcentaje = 21): float</code> que devuelva el precio con IVA.');

demo("Ámbito: dentro de una función NO ves las variables de fuera",
    'Una función solo ve sus parámetros y lo que crea dentro. Por eso en <code>aplicarSubida()</code> había que volver a pedir '
    . '<code>AccesoDatos::initModelo()</code>: el <code>$db</code> de fuera no existía dentro.',
<<<'PHP'
$mensaje = "soy de fuera";

function verFuera()
{
    return isset($mensaje) ? "la veo" : "NO veo \$mensaje (es de fuera)";
}

function verParametro($m)
{
    return "por parámetro sí: " . $m;
}

echo verFuera() . "\n";
echo verParametro($mensaje);
PHP,
    'piensa: ¿cómo harías que una función use un objeto <code>$db</code> creado fuera? (dos formas: pasarlo por parámetro o volver a pedirlo).');

demo("Constantes con define()",
    'Valores que no cambian: la BD, el PIN, el máximo de intentos... Se escriben en MAYÚSCULAS y <b>sin $</b>. Pueden ser arrays.',
<<<'PHP'
define('MAX_INTENTOS', 3);
define('PUESTOS', ['Jefe', 'Programador', 'Becario']);

echo "Intentos: " . MAX_INTENTOS . "\n";
echo "Segundo puesto: " . PUESTOS[1] . "\n";
echo "Total de puestos: " . count(PUESTOS);
PHP,
    'define <code>TIEMPO_SESION</code> con 300 y escribe "La sesión dura 5 minutos" calculando los minutos.');

// =====================================================================
seccion("6. Fechas y números");
// =====================================================================

demo("time(), date() y DateTime",
    '<code>time()</code> = segundos desde 1970 (para calcular caducidades). <code>date()</code> da formato. '
    . '<code>DateTime</code> + <code>diff()</code> calcula diferencias (años de antigüedad).',
<<<'PHP'
echo time() . "  ← segundos desde 1970\n";
echo date("d/m/Y H:i:s") . "  ← ahora\n";
echo date("Y-m-d") . "  ← formato de MySQL\n";
echo date("d/m/Y", time() + 7 * 24 * 3600) . "  ← dentro de una semana\n";

$alta = new DateTime("2015-03-01");
$hoy = new DateTime();
echo "Años completos desde 2015-03-01: " . $alta->diff($hoy)->y;
PHP,
    'calcula cuántos <b>días</b> faltan para el 31/12 de este año (pista: <code>->days</code>).');

demo("number_format() y redondeo",
    '<code>number_format(número, decimales, separador decimal, separador de miles)</code>. El formato español es <code>2, \',\', \'.\'</code>.',
<<<'PHP'
echo number_format(3200.5, 2, ',', '.') . " €\n";
echo number_format(1234567) . "\n";
echo round(2.456, 2) . "\n";
echo round(2.5) . "\n";
echo floor(2.9) . " y " . ceil(2.1);
PHP,
    'muestra 2100 * 1.05 con formato español y el símbolo €.');

// =====================================================================
seccion("7. Redirigir e incluir archivos", "Estas no se pueden ejecutar dentro de un ejemplo, así que solo ves el código.");
// =====================================================================

codigo("include / require",
    '<code>include</code> mete otro archivo PHP en ese punto (las vistas). <code>require</code> igual, pero si no existe da error fatal '
    . '(para clases y modelos). Con <code>_once</code> no se incluye dos veces.',
<<<'PHP'
require_once "AccesoDatos.php";      // clases: imprescindibles -> require_once
include "vistaerror.php";            // vistas: un trozo de HTML

// Desde una carpeta (app/), mejor con __DIR__ = carpeta de ESTE archivo
require_once __DIR__ . "/Empleado.php";
PHP);

codigo("header(\"Location\") y exit()",
    'Redirige el navegador a otra página. Va <b>antes</b> de cualquier HTML (como <code>setcookie</code> y <code>session_start</code>), '
    . 'y siempre seguido de <code>exit()</code> para que no se ejecute nada más.',
<<<'PHP'
if ($_SERVER['REQUEST_METHOD'] != "POST") {
    header("Location: acceso.html");
    exit();
}

// Variante de examII: recargar a los 0 segundos
header("Refresh:0; url=index.php");
PHP);

pie();
