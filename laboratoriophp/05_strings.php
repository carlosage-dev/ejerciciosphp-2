<?php
require_once '_lab/lab.php';
cabecera("Strings (textos)", "Todo lo que llega de un formulario, una cookie o la URL es <b>texto</b>. Estas funciones sirven para limpiarlo, validarlo y darle formato.");

seccion("Chuleta rápida");
chuleta([
    ['strlen($t)', 'Número de caracteres (ojo: las tildes cuentan como 2)'],
    ['strtolower / strtoupper', 'Todo minúsculas / todo MAYÚSCULAS'],
    ['ucfirst / ucwords', 'Primera letra en mayúscula / la primera de cada palabra'],
    ['trim / ltrim / rtrim', 'Quita espacios de los dos lados / izquierda / derecha'],
    ['substr($t, inicio, largo)', 'Un trozo del texto'],
    ['strpos($t, $buscar)', 'Posición donde aparece, o <code>false</code> (compara con <code>!== false</code>)'],
    ['str_contains / str_starts_with / str_ends_with', '¿Contiene / empieza por / acaba en...? (PHP 8)'],
    ['str_replace($buscar, $poner, $t)', 'Reemplaza'],
    ['str_repeat($t, n)', 'Repite un texto n veces'],
    ['str_pad($t, largo, relleno, lado)', 'Rellena hasta una longitud (ceros a la izquierda...)'],
    ['strrev($t)', 'Le da la vuelta'],
    ['explode(sep, $t)  /  implode(sep, $a)', 'Texto → array  /  array → texto'],
    ['number_format($n, 2, ",", ".")', 'Número con formato español: 3.200,50'],
    ['sprintf("%05.2f", $n)', 'Texto con formato (rellenos, decimales...)'],
    ['htmlspecialchars($t)', 'Convierte &lt; &gt; &amp; para que no se ejecute HTML'],
    ['strip_tags($t)', 'Quita las etiquetas HTML'],
    ['$t[0]  /  for + strlen', 'Una letra concreta / recorrer letra a letra'],
]);

// =====================================================================
seccion("1. Longitud, mayúsculas y espacios");
// =====================================================================

demo("strlen()",
    'Cuenta caracteres. Útil para validar longitudes (contraseña de mínimo 4 caracteres...).',
<<<'PHP'
$clave = "abc12";
echo "Longitud: " . strlen($clave) . "\n";

if (strlen($clave) < 8) {
    echo "Clave demasiado corta";
}
PHP,
    'cuenta la longitud de "Ratón": verás 6 porque la ó ocupa 2 (con <code>mb_strlen</code> da 5).');

demo("strtolower, strtoupper, ucfirst, ucwords",
    'Para normalizar lo que escribe el usuario (guardar "PAN", "Pan" y "pan" igual) o para mostrar bonito.',
<<<'PHP'
$t = "hola MUNDO desde php";

echo strtolower($t) . "\n";
echo strtoupper($t) . "\n";
echo ucfirst(strtolower($t)) . "\n";
echo ucwords(strtolower($t));
PHP,
    'convierte "aNA lOPEZ" en "Ana Lopez".');

demo("trim()",
    'Quita los espacios del principio y del final. <b>Úsalo siempre</b> con lo que llega de un formulario antes de comprobar si está vacío.',
<<<'PHP'
$escrito = "   pan   ";

echo "[" . $escrito . "]\n";
echo "[" . trim($escrito) . "]\n";
echo "[" . ltrim($escrito) . "]\n";
echo "[" . rtrim($escrito) . "]\n";

$soloEspacios = "    ";
var_dump(trim($soloEspacios) == "");
PHP,
    'combina <code>strtolower(trim("  PAN  "))</code> y mira el resultado.');

// =====================================================================
seccion("2. Trozos, buscar y reemplazar");
// =====================================================================

demo("substr()",
    '<code>substr($texto, desde, cuántos)</code>. La primera letra es la posición 0. Con un número negativo cuenta desde el final.',
<<<'PHP'
$t = "ana01";

echo substr($t, 0, 1) . "\n";     // primera letra
echo substr($t, 0, 3) . "\n";     // 3 primeras
echo substr($t, -2) . "\n";       // 2 últimas
echo strtoupper(substr("lopez", 0, 1)) . substr("lopez", 1);
PHP,
    'saca el año de <code>"2026-11-26"</code> con substr.');

demo("strpos() y str_contains()",
    '<code>strpos</code> da la posición o <code>false</code>. Igual que <code>array_search</code>: compara con <code>!== false</code> porque puede estar en la 0.',
<<<'PHP'
$email = "carlos@gmail.com";

$arroba = strpos($email, "@");
echo "La @ está en la posición $arroba\n";
echo "Usuario: " . substr($email, 0, $arroba) . "\n";
echo "Dominio: " . substr($email, $arroba + 1) . "\n";

if (strpos($email, "@") !== false) {
    echo "Tiene @\n";
}
var_dump(str_contains($email, "gmail"));
PHP,
    'comprueba si <code>"ana01"</code> empieza por "ana" con <code>str_starts_with</code>.');

demo("str_replace()",
    'Reemplaza todas las apariciones. También acepta arrays para cambiar varias cosas a la vez.',
<<<'PHP'
echo str_replace(" ", "", "Anita lava la tina") . "\n";
echo str_replace("€", "EUR", "Total: 25 €") . "\n";
echo str_replace(["á", "é", "í", "ó"], ["a", "e", "i", "o"], "Administración, café, aquí");
PHP,
    'cambia los puntos por comas en <code>"3.5"</code>.');

demo("str_repeat, str_pad, strrev",
    'Repetir, rellenar hasta una longitud y dar la vuelta.',
<<<'PHP'
echo str_repeat("*", 5) . "\n";
echo str_pad("7", 3, "0", STR_PAD_LEFT) . "\n";     // 007
echo strrev("hola") . "\n";

// Ocultar un email: c*****@gmail.com
$email = "carlos@gmail.com";
$pos = strpos($email, "@");
echo substr($email, 0, 1) . str_repeat("*", $pos - 1) . substr($email, $pos);
PHP,
    'crea códigos de empleado tipo "EMP001", "EMP002"... con un for y str_pad.');

// =====================================================================
seccion("3. Partir y unir: explode / implode");
// =====================================================================

demo("explode() (texto → array)",
    'Parte por un separador. Ideal para una línea tipo CSV o para separar palabras.',
<<<'PHP'
$linea = "ana01;Ana López;3200";
$partes = explode(";", $linea);
print_r($partes);

echo "Nombre: " . $partes[1] . "\n";

$frase = "el examen de php es el jueves";
$palabras = explode(" ", $frase);
echo "Palabras: " . count($palabras);
PHP,
    'saca la palabra más larga de la frase con un foreach y strlen.');

demo("implode() (array → texto)",
    'Une los elementos con el separador que digas. Truco para pintar filas: <code>implode("&lt;/td&gt;&lt;td&gt;", $datos)</code>.',
<<<'PHP'
$datos = ["ana01", "Ana", "Jefe"];

echo implode(", ", $datos) . "\n";
echo "<tr><td>" . implode("</td><td>", $datos) . "</td></tr>";
PHP,
    'saca las iniciales de "ana lopez garcia" → "ALG" (explode + foreach + substr + strtoupper).');

demo("Limpiar un nombre mal escrito",
    'Ejemplo completo: <code>trim</code>, <code>explode</code>, saltar huecos vacíos, <code>ucfirst</code>, <code>implode</code>.',
<<<'PHP'
$escrito = "  aNA   lOPEZ  ";

$limpias = [];
foreach (explode(" ", trim($escrito)) as $palabra) {
    if ($palabra != "") {                       // los espacios dobles dejan huecos
        $limpias[] = ucfirst(strtolower($palabra));
    }
}
echo implode(" ", $limpias);
PHP,
    'quita el <code>if</code> y mira qué pasa con los espacios dobles.');

// =====================================================================
seccion("4. Recorrer letra a letra");
// =====================================================================

demo('for + strlen + $texto[$i]',
    'Un string se puede leer como un array de letras.',
<<<'PHP'
$texto = "Programacion Web";
$vocales = 0;

for ($i = 0; $i < strlen($texto); $i++) {
    $letra = strtolower($texto[$i]);
    if (in_array($letra, ["a", "e", "i", "o", "u"])) {
        $vocales++;
    }
}
echo "Vocales: $vocales";
PHP,
    'cuenta cuántas MAYÚSCULAS tiene "Hola Mundo PHP".');

demo("Validar una contraseña",
    'Mínimo 8 caracteres, al menos un número y al menos una mayúscula. Se recorre y se van activando "banderas".',
<<<'PHP'
function claveValida(string $clave): bool
{
    if (strlen($clave) < 8) {
        return false;
    }
    $tieneNumero = false;
    $tieneMayus = false;
    for ($i = 0; $i < strlen($clave); $i++) {
        $c = $clave[$i];
        if (is_numeric($c)) {
            $tieneNumero = true;
        } elseif ($c == strtoupper($c) && $c != strtolower($c)) {
            $tieneMayus = true;
        }
    }
    return $tieneNumero && $tieneMayus;
}

var_dump(claveValida("abc"));
var_dump(claveValida("abcdefgh"));
var_dump(claveValida("Abcdefg1"));
PHP,
    'añade la regla "no puede tener espacios" (pista: <code>str_contains</code>).');

demo("Palíndromo",
    'Se lee igual al revés: quitar espacios, minúsculas y comparar con <code>strrev</code>.',
<<<'PHP'
function esPalindromo(string $t): bool
{
    $t = strtolower(str_replace(" ", "", $t));
    return $t == strrev($t);
}

var_dump(esPalindromo("Anita lava la tina"));
var_dump(esPalindromo("Hola mundo"));
PHP,
    'prueba con "Dabale arroz a la zorra el abad".');

// =====================================================================
seccion("5. Formato y seguridad");
// =====================================================================

demo("number_format y sprintf",
    'Para enseñar dinero y números con formato.',
<<<'PHP'
$sueldo = 3200.5;
echo number_format($sueldo, 2, ',', '.') . " €\n";
echo sprintf("%.2f", $sueldo) . "\n";               // 2 decimales con punto
echo sprintf("%05d", 42) . "\n";                    // 00042
echo sprintf("%s cobra %.2f €", "Ana", $sueldo);
PHP,
    'muestra un porcentaje con 1 decimal: 47.7707 → "47,8 %".');

demo("htmlspecialchars y strip_tags",
    'Si pintas lo que escribe el usuario, podría meter HTML o JavaScript. <code>htmlspecialchars</code> lo convierte en texto inofensivo; '
    . '<code>strip_tags</code> quita las etiquetas (es lo que hacía <code>limpiarEntrada()</code> de examII).',
<<<'PHP'
$comentario = "<b>Hola</b> <script>alert('hack')</script>";

echo htmlspecialchars($comentario) . "\n";
echo strip_tags($comentario);
PHP,
    'mira el código fuente de esta página (Ctrl + U) para ver cómo ha quedado el texto escapado.');

pie();
