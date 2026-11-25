<?php
/*
 * ================================================================
 *  4b · STRINGS Y BUCLES                    (~3-5 min cada función)
 * ================================================================
 *  Igual que arrays.php: rellena las funciones y abre el archivo
 *  en el navegador para ver ✔ / ✘.
 *
 *  Funciones que te van a hacer falta (búscalas si no las recuerdas):
 *  strlen, strtolower, strtoupper, ucfirst, trim, substr, strpos,
 *  str_replace, str_repeat, strrev, explode, implode, number_format
 *
 *  Recorrer un string letra a letra:
 *      for ($i = 0; $i < strlen($texto); $i++) { $letra = $texto[$i]; }
 *  (sin tildes en las pruebas a propósito: las tildes ocupan 2 "letras")
 * ================================================================
 */
require_once '_test.php';

/** 1. Cuántas vocales tiene (mayúsculas o minúsculas). (bucle) */
function contarVocales(string $texto): int
{
    return 0;
}

/** 2. ¿Se lee igual al revés? Ignora espacios y mayúsculas. */
function esPalindromo(string $texto): bool
{
    return false;
}

/** 3. Iniciales en mayúscula: "ana lopez garcia" -> "ALG" */
function iniciales(string $nombre): string
{
    return "";
}

/** 4. Limpia un nombre mal escrito: "  aNA   lOPEZ " -> "Ana Lopez"
 *     (ojo con los espacios dobles del medio: explode deja huecos vacíos) */
function formatearNombre(string $nombre): string
{
    return "";
}

/** 5. Oculta el email: "carlos@gmail.com" -> "c*****@gmail.com"
 *     (primera letra + tantos * como letras quedan antes de la @) */
function ocultarEmail(string $email): string
{
    return "";
}

/** 6. Clave válida = mínimo 8 caracteres, al menos 1 número y 1 mayúscula. (bucle) */
function claveValida(string $clave): bool
{
    return false;
}

/** 7. La palabra más larga de la frase (si empatan, la primera). */
function palabraMasLarga(string $frase): string
{
    return "";
}

/** 8. Convierte una línea "ana01;Ana Lopez;3200" en
 *     ['cod' => 'ana01', 'nombre' => 'Ana Lopez', 'sueldo' => 3200.0]
 *     (el sueldo como float) */
function lineaAArray(string $linea): array
{
    return [];
}

/** 9. Monta una fila de tabla HTML: ['a','b'] -> "<tr><td>a</td><td>b</td></tr>"
 *     (¡esto es lo que haces al pintar tablas con $msg .=!) Pista: implode */
function generarFila(array $datos): string
{
    return "";
}

/** 10. Formato de euros español: 3200.5 -> "3.200,50 €" */
function euros(float $cantidad): string
{
    return "";
}

// =====================================================================
//  PRUEBAS (no tocar)
// =====================================================================
echo "<h2>Strings</h2>";
comprobar("1. contarVocales", contarVocales("Programacion Web"), 6);
comprobar("2a. esPalindromo (sí)", esPalindromo("Anita lava la tina"), true);
comprobar("2b. esPalindromo (no)", esPalindromo("Hola mundo"), false);
comprobar("3. iniciales", iniciales("ana lopez garcia"), "ALG");
comprobar("4. formatearNombre", formatearNombre("  aNA   lOPEZ "), "Ana Lopez");
comprobar("5. ocultarEmail", ocultarEmail("carlos@gmail.com"), "c*****@gmail.com");
comprobar("6a. claveValida corta", claveValida("Ab1"), false);
comprobar("6b. claveValida sin número", claveValida("Abcdefgh"), false);
comprobar("6c. claveValida sin mayúscula", claveValida("abcdefg1"), false);
comprobar("6d. claveValida buena", claveValida("Abcdefg1"), true);
comprobar("7. palabraMasLarga", palabraMasLarga("el examen de php es el jueves"), "examen");
comprobar("8. lineaAArray", lineaAArray("ana01;Ana Lopez;3200"), ['cod' => 'ana01', 'nombre' => 'Ana Lopez', 'sueldo' => 3200.0]);
comprobar("9. generarFila", generarFila(['ana01', 'Ana', 'Jefe']), "<tr><td>ana01</td><td>Ana</td><td>Jefe</td></tr>");
comprobar("10. euros", euros(3200.5), "3.200,50 €");
resumen();
