<?php
/*
 * =====================================================================
 *  06 · NÚMEROS, FECHAS Y VALIDACIONES
 * =====================================================================
 *  Todas DEVUELVEN un valor nuevo: guárdalo o úsalo directamente.
 * =====================================================================
 */
require_once '_ver.php';

// =====================================================================
titulo("1. Números");
// =====================================================================
echo number_format(2575, 2, ',', '.') . " €\n";  // → 2.575,00 €
echo round(2233.3333, 2) . "\n";                 // → 2233.33
echo round(2.5) . "\n";                          // → 3
echo floor(2.9) . "\n";                          // → 2     hacia abajo
echo ceil(2.1) . "\n";                           // → 3     hacia arriba
echo abs(-15) . "\n";                            // → 15
echo max(3, 9, 4) . " / " . min(3, 9, 4) . "\n"; // → 9 / 3
echo 17 % 5 . "\n";                              // → 2     resto de la división
echo intdiv(17, 5) . "\n";                       // → 3     división entera
echo 10 / 4 . "\n";                              // → 2.5
var_dump(8 % 2 == 0);                            // → bool(true)   ¿es par?

// Porcentajes
$salario = 2000;
echo $salario * 1.10 . "\n";                     // → 2200   (+10 %)
echo $salario * (1 + 5 / 100) . "\n";            // → 2100   (+5 %)
echo round(1800 / 8700 * 100, 1) . " %\n";       // → 20.7 %  (qué parte del total es)

// Aleatorio (cada vez distinto)
$dado = rand(1, 6);                              // → un número del 1 al 6

// =====================================================================
titulo("2. Convertir y comprobar tipos");
// =====================================================================
var_dump((int) "42");                            // → int(42)
var_dump((int) "3.9");                           // → int(3)     corta decimales
var_dump((int) "abc");                           // → int(0)     ¡sin avisar! valida ANTES
var_dump((float) "2.5");                         // → float(2.5)
var_dump(is_numeric("42"));                      // → bool(true)
var_dump(is_numeric("4.2"));                     // → bool(true)
var_dump(is_numeric("abc"));                     // → bool(false)
var_dump(is_numeric(""));                        // → bool(false)
var_dump(ctype_digit("123"));                    // → bool(true)   solo dígitos (sin punto ni signo)
var_dump(ctype_digit("12.5"));                   // → bool(false)

// == compara valor, === valor Y tipo
var_dump(5 == "5");                              // → bool(true)
var_dump(5 === "5");                             // → bool(false)
var_dump(0.0 === 0);                             // → bool(false)  float vs int: usa == con números

// =====================================================================
titulo("3. Validar lo que llega de un formulario");
// =====================================================================
// isset: ¿ha llegado?   empty: ¿está vacío?  (ojo: empty("0") es true)
$campo = "0";
var_dump(empty($campo));                         // → bool(true)   ✗ un 0 válido cuenta como vacío
var_dump(trim($campo) == "");                    // → bool(false)  ✓ para números, compara con ""

// El orden de siempre: ¿llega? → ¿vacío? → ¿formato? → ¿rango? → convertir
function validarUnidades($valor): string
{
    if (!isset($valor)) {
        return "No ha llegado";
    }
    $valor = trim($valor);
    if ($valor == "") {
        return "Vacío";
    }
    if (!is_numeric($valor)) {
        return "No es un número";
    }
    if ($valor < 1 || $valor > 20) {
        return "Fuera de rango (1-20)";
    }
    return "OK: " . (int) $valor;
}
echo validarUnidades(null) . "\n";               // → No ha llegado
echo validarUnidades("  ") . "\n";               // → Vacío
echo validarUnidades("abc") . "\n";              // → No es un número
echo validarUnidades("25") . "\n";               // → Fuera de rango (1-20)
echo validarUnidades(" 5 ") . "\n";              // → OK: 5

// Lista blanca: solo valores permitidos
$permitidos = ["claro", "oscuro"];
var_dump(in_array("rosa", $permitidos));         // → bool(false)

// filter_var: validar email y números
var_dump(filter_var("ana@gmail.com", FILTER_VALIDATE_EMAIL) !== false);   // → bool(true)
var_dump(filter_var("ana@", FILTER_VALIDATE_EMAIL) !== false);            // → bool(false)
var_dump(filter_var("42", FILTER_VALIDATE_INT));                          // → int(42)
var_dump(filter_var("4.2", FILTER_VALIDATE_INT));                         // → bool(false)

// =====================================================================
titulo("4. Fechas");
// =====================================================================
// Con fechas FIJAS para que la salida sea siempre la misma:
$ts = mktime(10, 30, 0, 11, 26, 2026);           // 26/11/2026 10:30 (hora, min, seg, MES, DÍA, año)
echo date("d/m/Y", $ts) . "\n";                  // → 26/11/2026
echo date("d/m/Y H:i", $ts) . "\n";              // → 26/11/2026 10:30
echo date("Y-m-d", $ts) . "\n";                  // → 2026-11-26   formato MySQL
echo date("d/m/Y", strtotime("2026-11-26 +7 days")) . "\n";   // → 03/12/2026
echo date("d/m/Y", strtotime("2026-11-26 -1 month")) . "\n";  // → 26/10/2026

// Ahora mismo (cambia cada vez):
// date("d/m/Y H:i:s")  → la fecha y hora actuales
// time()               → segundos desde 1970 (para caducidades: time() + 3600)

// Días de la semana y meses en español
$dias = ["domingo", "lunes", "martes", "miércoles", "jueves", "viernes", "sábado"];
$meses = ["", "enero", "febrero", "marzo", "abril", "mayo", "junio", "julio",
          "agosto", "septiembre", "octubre", "noviembre", "diciembre"];
echo $dias[date("w", $ts)] . ", " . date("j", $ts) . " de " . $meses[date("n", $ts)] . "\n";
// → jueves, 26 de noviembre

// Convertir formatos
echo date("d/m/Y", strtotime("2015-03-01")) . "\n";                       // → 01/03/2015   (MySQL → usuario)
echo DateTime::createFromFormat("d/m/Y", "26/11/2026")->format("Y-m-d") . "\n";   // → 2026-11-26   (usuario → MySQL)

// Diferencias con DateTime
$alta = new DateTime("2015-03-01");
$fecha = new DateTime("2026-11-26");
$dif = $alta->diff($fecha);
echo $dif->y . " años, " . $dif->m . " meses, " . $dif->d . " días\n";    // → 11 años, 8 meses, 25 días
echo $dif->days . " días en total\n";                                     // → 4288 días en total

// Comparar fechas
var_dump(new DateTime("2026-11-26") > new DateTime("2026-01-01"));        // → bool(true)
var_dump(checkdate(2, 30, 2026));                                          // → bool(false)  30 de febrero no existe

// Caducidad (patrón de sesión): ¿han pasado más de 300 s?
$ultimo = $ts - 400;                             // simulado: hace 400 s
var_dump($ts - $ultimo > 300);                   // → bool(true)   → caducada
