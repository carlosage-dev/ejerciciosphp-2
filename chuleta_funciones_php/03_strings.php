<?php
/*
 * =====================================================================
 *  03 · FUNCIONES DE STRINGS (textos)
 * =====================================================================
 *  Un string NUNCA se modifica "solo": TODAS estas funciones DEVUELVEN
 *  un texto nuevo. Si quieres conservarlo, guárdalo:
 *      ✗ MAL:  trim($nombre);              // no cambia $nombre
 *      ✓ BIEN: $nombre = trim($nombre);    // sobrescribes la misma variable
 *      ✓ BIEN: $limpio = trim($nombre);    // o guardas en otra si quieres el original
 *
 *  TILDES Y Ñ: las funciones normales cuentan BYTES, y "á", "ñ", "€" ocupan
 *  2 o 3 bytes. Con tildes usa la versión mb_ (multibyte):
 *      strlen → mb_strlen      strtoupper → mb_strtoupper
 *      substr → mb_substr      strtolower → mb_strtolower
 *      strpos → mb_strpos      str_split  → mb_str_split
 *  (Necesitan la extensión mbstring, que XAMPP trae activada.)
 * =====================================================================
 */
require_once '_ver.php';
require_once 'datos.php';

$nombre = "Lucía Prieto";
$frase  = "el examen de php es hoy";

// =====================================================================
titulo("1. Longitud: strlen vs mb_strlen");
// =====================================================================
echo strlen("Ana") . "\n";                       // → 3
echo strlen("Lucía") . "\n";                     // → 6   ✗ la í ocupa 2 bytes
echo mb_strlen("Lucía") . "\n";                  // → 5   ✓
echo mb_strlen("Logística") . "\n";              // → 9
echo strlen("") . "\n";                          // → 0

// Validar una longitud mínima
$clave = "abc";
if (mb_strlen($clave) < 4) {
    echo "Clave demasiado corta\n";              // → Clave demasiado corta
}

// =====================================================================
titulo("2. Mayúsculas y minúsculas");
// =====================================================================
echo strtoupper("ñandú") . "\n";                 // → ñANDú   ✗ no toca la ñ ni la ú
echo mb_strtoupper("ñandú") . "\n";              // → ÑANDÚ   ✓
echo mb_strtolower("ÁLVARO NIETO") . "\n";       // → álvaro nieto
echo strtolower("PHP") . "\n";                   // → php     (sin tildes, la normal vale)

echo ucfirst("marta") . "\n";                    // → Marta   primera letra
echo ucwords("marta ruiz garcía") . "\n";        // → Marta Ruiz García   primera de cada palabra
echo lcfirst("Hola") . "\n";                     // → hola

// Normalizar lo que escribe el usuario: todo minúsculas o "Tipo Título"
echo mb_convert_case("aNA lÓPEZ", MB_CASE_TITLE) . "\n";   // → Ana López   ✓ con tildes

// Comparar sin importar mayúsculas
var_dump(mb_strtolower("PAN") == mb_strtolower("pan"));   // → bool(true)
var_dump(strcasecmp("PAN", "pan") == 0);                  // → bool(true)  (0 = iguales)

// =====================================================================
titulo("3. Quitar espacios: trim");
// =====================================================================
$escrito = "   pan   ";
echo "[" . trim($escrito) . "]\n";               // → [pan]
echo "[" . ltrim($escrito) . "]\n";              // → [pan   ]
echo "[" . rtrim($escrito) . "]\n";              // → [   pan]
echo "[" . trim("--hola--", "-") . "]\n";        // → [hola]   quita el carácter que digas

// ✓ Siempre antes de comprobar si está vacío:
$campo = "    ";
var_dump($campo == "");                          // → bool(false)  ✗ tiene espacios
var_dump(trim($campo) == "");                    // → bool(true)   ✓

// =====================================================================
titulo("4. Trozos: substr / mb_substr");
// =====================================================================
// substr(texto, desde, cuántos)   la primera letra es la posición 0
echo substr("ana01", 0, 3) . "\n";               // → ana     los 3 primeros
echo substr("ana01", 3) . "\n";                  // → 01      desde la posición 3 hasta el final
echo substr("ana01", -2) . "\n";                 // → 01      los 2 últimos
echo substr("2026-11-26", 0, 4) . "\n";          // → 2026    el año de una fecha MySQL

echo mb_substr("Álvaro", 0, 1) . "\n";           // → Á       ✓ (con substr saldría medio carácter)
echo mb_substr("Logística", 0, 4) . "\n";        // → Logí

// Iniciales de un nombre con tildes
$iniciales = "";
foreach (explode(" ", "Álvaro Nieto") as $palabra) {
    $iniciales .= mb_substr($palabra, 0, 1);
}
echo $iniciales . "\n";                          // → ÁN

// Una letra concreta (sin tildes): como un array
echo "Hola"[0] . "\n";                           // → H

// =====================================================================
titulo("5. Buscar dentro de un texto");
// =====================================================================
// strpos → posición o false. Igual que array_search: compara con !== false
var_dump(strpos($frase, "php"));                 // → int(13)
var_dump(strpos($frase, "java"));                // → bool(false)
var_dump(strpos($frase, "el"));                  // → int(0)   ¡OJO! 0 == false
if (strpos($frase, "el") !== false) {
    echo "contiene 'el'\n";                      // → contiene 'el'
}

// PHP 8: más cómodas, devuelven true/false directamente
var_dump(str_contains($frase, "examen"));        // → bool(true)
var_dump(str_starts_with("ana01", "ana"));       // → bool(true)
var_dump(str_ends_with("foto.jpg", ".jpg"));     // → bool(true)

// Cuántas veces aparece
echo substr_count("banana", "a") . "\n";         // → 3

// Partir un email por la @
$email = "carlos@gmail.com";
$arroba = strpos($email, "@");
echo substr($email, 0, $arroba) . "\n";          // → carlos
echo substr($email, $arroba + 1) . "\n";         // → gmail.com

// =====================================================================
titulo("6. Reemplazar, repetir, rellenar, dar la vuelta");
// =====================================================================
echo str_replace(" ", "_", "Recursos Humanos") . "\n";          // → Recursos_Humanos
echo str_replace(["á", "í", "ó"], ["a", "i", "o"], "Logística, Información") . "\n";  // → Logistica, Informacion
echo str_ireplace("PHP", "Java", "me gusta php") . "\n";        // → me gusta Java   (sin distinguir mayúsculas)

echo str_repeat("*", 5) . "\n";                  // → *****
echo str_pad("7", 3, "0", STR_PAD_LEFT) . "\n";  // → 007
echo str_pad("Total", 10, ".") . "100\n";        // → Total.....100

echo strrev("hola") . "\n";                      // → aloh
echo implode("", array_reverse(mb_str_split("Raúl"))) . "\n";   // → lúaR   ✓ al revés con tildes

// Ocultar un email: c*****@gmail.com
echo substr($email, 0, 1) . str_repeat("*", $arroba - 1) . substr($email, $arroba) . "\n";   // → c*****@gmail.com

// =====================================================================
titulo("7. Partir y unir: explode / implode");
// =====================================================================
$palabras = explode(" ", $frase);                // [NUEVO] array
mostrar($palabras);                              // → [0 => el, 1 => examen, 2 => de, 3 => php, 4 => es, 5 => hoy]
echo count($palabras) . " palabras\n";           // → 6 palabras

$linea = "ana01;Ana López;3200";
$campos = explode(";", $linea);
echo $campos[1] . " cobra " . $campos[2] . "\n"; // → Ana López cobra 3200

echo implode(" - ", ["Marta", "Luis", "Sara"]) . "\n";   // → Marta - Luis - Sara

// Fila de tabla en una línea
echo "<tr><td>" . implode("</td><td>", ["ana01", "Ana", "Jefe"]) . "</td></tr>\n";
// → <tr><td>ana01</td><td>Ana</td><td>Jefe</td></tr>

// Letra a letra (con tildes)
mostrar(mb_str_split("Ñu"));                     // → [0 => Ñ, 1 => u]

// =====================================================================
titulo("8. Recorrer letra a letra");
// =====================================================================
// Contar vocales (con tildes incluidas)
$texto = mb_strtolower("Álvaro Nieto");
$vocales = 0;
foreach (mb_str_split($texto) as $letra) {
    if (in_array($letra, ["a", "e", "i", "o", "u", "á", "é", "í", "ó", "ú"])) {
        $vocales++;
    }
}
echo "Vocales: " . $vocales . "\n";              // → Vocales: 6

// Palíndromo (sin espacios ni mayúsculas)
$p = mb_strtolower(str_replace(" ", "", "Anita lava la tina"));
var_dump($p == strrev($p));                      // → bool(true)

// Palabra más larga
$larga = "";
foreach (explode(" ", $frase) as $palabra) {
    if (mb_strlen($palabra) > mb_strlen($larga)) {
        $larga = $palabra;
    }
}
echo $larga . "\n";                              // → examen

// =====================================================================
titulo("9. Formato");
// =====================================================================
echo number_format(3200.5, 2, ',', '.') . " €\n";   // → 3.200,50 €
echo number_format(1234567) . "\n";                 // → 1,234,567   (formato inglés por defecto)
echo sprintf("%.2f", 3.14159) . "\n";               // → 3.14
echo sprintf("%05d", 42) . "\n";                    // → 00042
echo sprintf("%s cobra %d €", "Ana", 3200) . "\n";  // → Ana cobra 3200 €

// Comillas dobles: las variables se meten dentro. Arrays y objetos, entre llaves.
$jefe = $departamentos["Ventas"]["Jefe"];
echo "El jefe de Ventas es $jefe\n";                                  // → El jefe de Ventas es Pablo Soler
echo "Presupuesto: {$departamentos['Ventas']['Presupuesto']} €\n";   // → Presupuesto: 9500 €
echo 'Comillas simples: $jefe' . "\n";                                // → Comillas simples: $jefe

// =====================================================================
titulo("10. Seguridad: lo que escribe el usuario");
// =====================================================================
$entrada = "<b>Hola</b> <script>alert(1)</script>";
echo htmlspecialchars($entrada) . "\n";          // → &lt;b&gt;Hola&lt;/b&gt; &lt;script&gt;alert(1)&lt;/script&gt;
echo strip_tags($entrada) . "\n";                // → Hola alert(1)

// Limpieza típica de un campo de formulario
$sucio = "  <i>PAN</i>  ";
$limpio = mb_strtolower(trim(strip_tags($sucio)));
echo "[" . $limpio . "]\n";                      // → [pan]
