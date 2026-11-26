<?php
/*
 * =====================================================================
 *  01 · CÓMO RECORRER ARRAYS
 *  Ábrelo en el navegador: http://localhost/chuleta_funciones_php/01_recorrer_arrays.php
 *  Cada línea lleva al lado (// →) lo que muestra.
 * =====================================================================
 *
 *  RESUMEN: ¿qué foreach uso?
 *  ┌─────────────────────────────────────┬─────────────────────────────────────────────┐
 *  │ foreach ($lista as $valor)          │ Solo te interesan los valores               │
 *  │                                     │ (sumar, pintar, buscar un valor)            │
 *  ├─────────────────────────────────────┼─────────────────────────────────────────────┤
 *  │ foreach ($lista as $clave => $valor)│ Necesitas TAMBIÉN la clave: el nombre       │
 *  │                                     │ del país, el id, la posición, el usuario... │
 *  │                                     │ o vas a MODIFICAR el array ($a[$clave] = ..)│
 *  ├─────────────────────────────────────┼─────────────────────────────────────────────┤
 *  │ foreach dentro de foreach           │ Cuando un valor es a su vez una LISTA de    │
 *  │ (anidados)                          │ cosas que quieres recorrer (varios          │
 *  │                                     │ empleados en cada departamento)             │
 *  ├─────────────────────────────────────┼─────────────────────────────────────────────┤
 *  │ $array["clave"]["campo"]            │ Si ya sabes qué dato quieres: SIN bucle     │
 *  └─────────────────────────────────────┴─────────────────────────────────────────────┘
 *  Truco: cuenta los NIVELES que son listas. Un foreach por cada nivel-lista.
 *  Si un nivel tiene campos fijos (Jefe, Presupuesto), no se recorre: se accede por clave.
 */
require_once '_ver.php';
require_once 'datos.php';

// =====================================================================
titulo("1. Array INDEXADO (solo valores, posiciones 0, 1, 2...)");
// =====================================================================
$frutas = ["manzana", "pera", "kiwi"];

// a) Solo el valor: no te importa la posición
foreach ($frutas as $fruta) {
    echo $fruta . " ";
}
echo "\n";                                      // → manzana pera kiwi

// b) Posición y valor
foreach ($frutas as $pos => $fruta) {
    echo $pos . ": " . $fruta . "\n";
}
// → 0: manzana
// → 1: pera
// → 2: kiwi

// c) for clásico (solo sirve en indexados 0, 1, 2... sin huecos)
for ($i = 0; $i < count($frutas); $i++) {
    echo $frutas[$i] . " ";
}
echo "\n";                                      // → manzana pera kiwi

// d) Al revés, con for
for ($i = count($frutas) - 1; $i >= 0; $i--) {
    echo $frutas[$i] . " ";
}
echo "\n";                                      // → kiwi pera manzana

// e) Sin bucle
echo $frutas[0] . "\n";                         // → manzana
echo $frutas[count($frutas) - 1] . "\n";        // → kiwi   (el último)

// =====================================================================
titulo("2. Array ASOCIATIVO (clave => valor)");
// =====================================================================
$precios = ["Teclado" => 25, "Ratón" => 12, "Monitor" => 180];

// Necesito la CLAVE (el nombre del producto) → $clave => $valor
foreach ($precios as $producto => $precio) {
    echo $producto . " cuesta " . $precio . " €\n";
}
// → Teclado cuesta 25 €
// → Ratón cuesta 12 €
// → Monitor cuesta 180 €

// Solo necesito el VALOR (para sumar) → foreach simple
$total = 0;
foreach ($precios as $precio) {
    $total += $precio;
}
echo "Total: " . $total . " €\n";               // → Total: 217 €

// Sin bucle
echo $precios["Monitor"] . "\n";                // → 180

// =====================================================================
titulo('3. $departamentos: asociativo de asociativos (campos FIJOS)');
// =====================================================================
// Cada departamento tiene SIEMPRE los mismos campos: Jefe y Presupuesto.
// → UN foreach para los departamentos, y los campos se piden por su clave.
foreach ($departamentos as $nombreDep => $datos) {
    echo $nombreDep . " → jefe: " . $datos["Jefe"] . ", presupuesto: " . $datos["Presupuesto"] . "\n";
}
// → Informática → jefe: Marta Ruiz, presupuesto: 9000
// → Ventas → jefe: Pablo Soler, presupuesto: 9500
// → Marketing → jefe: Lucía Prieto, presupuesto: 7000
// → Recursos Humanos → jefe: Andrés Gil, presupuesto: 4000
// → Logística → jefe: Irene Cano, presupuesto: 9000

// Un foreach anidado aquí FUNCIONA, pero no hace falta.
// Solo tiene sentido si quieres pintar TODOS los campos sin saber cuáles son.
foreach ($departamentos as $nombreDep => $datos) {
    echo $nombreDep . ": ";
    foreach ($datos as $campo => $valor) {
        echo $campo . "=" . $valor . " ";
    }
    echo "\n";
}
// → Informática: Jefe=Marta Ruiz Presupuesto=9000
// → Ventas: Jefe=Pablo Soler Presupuesto=9500   ...

// Sin bucle: primero la fila, luego el campo
echo $departamentos["Ventas"]["Jefe"] . "\n";            // → Pablo Soler
echo $departamentos["Logística"]["Presupuesto"] . "\n";  // → 9000

// =====================================================================
titulo('4. $empleados: asociativo → LISTA → asociativo (AQUÍ SÍ hay que anidar)');
// =====================================================================
// Nivel 1: 'Informática' => [ ...lista... ]       ← clave = nombre del departamento
// Nivel 2: [ emp0, emp1, emp2 ... ]               ← una LISTA de longitud variable
// Nivel 3: ["Nombre" => ..., "Salario" => ...]   ← campos fijos: se piden por clave
// Hay dos niveles que son listas (departamentos y empleados) → DOS foreach.
foreach ($empleados as $nombreDep => $lista) {
    echo $nombreDep . " (" . count($lista) . " empleados)\n";
    foreach ($lista as $emp) {
        echo "   - " . $emp["Nombre"] . ": " . $emp["Salario"] . " €\n";
    }
}
// → Informática (4 empleados)
// →    - Marta Ruiz: 3200 €
// →    - Luis Pérez: 2400 €
// →    - Sara Vidal: 2600 €
// →    - Hugo Mora: 2100 €
// → Ventas (4 empleados)
// →    - Pablo Soler: 3000 €   ... y así con todos

// Solo un departamento: UN foreach (ya has elegido el nivel 1 a mano)
foreach ($empleados["Marketing"] as $emp) {
    echo $emp["Nombre"] . " ";
}
echo "\n";                                      // → Lucía Prieto Álvaro Nieto Nuria Pardo

// Sin bucle: departamento → posición en la lista → campo
echo $empleados["Ventas"][1]["Nombre"] . "\n";  // → Carmen Ortiz
echo $empleados["Logística"][0]["Salario"] . "\n";  // → 2500

// =====================================================================
titulo("5. Cálculos con bucles anidados");
// =====================================================================

// a) Total y media por departamento + total general
$totalGeneral = 0;
$numEmpleados = 0;
foreach ($empleados as $nombreDep => $lista) {
    $totalDep = 0;                              // se reinicia en CADA departamento
    foreach ($lista as $emp) {
        $totalDep += $emp["Salario"];
    }
    $media = $totalDep / count($lista);
    echo $nombreDep . ": " . $totalDep . " € (media " . number_format($media, 2, ',', '.') . " €)\n";

    $totalGeneral += $totalDep;                 // se acumula FUERA del foreach de dentro
    $numEmpleados += count($lista);
}
echo "TOTAL: " . $totalGeneral . " € entre " . $numEmpleados . " empleados\n";
// → Informática: 10300 € (media 2.575,00 €)
// → Ventas: 8700 € (media 2.175,00 €)
// → Marketing: 6700 € (media 2.233,33 €)
// → Recursos Humanos: 4700 € (media 2.350,00 €)
// → Logística: 8900 € (media 1.780,00 €)
// → TOTAL: 39300 € entre 18 empleados

// b) El salario más alto de todos (quién y dónde)
$max = 0;
$quien = "";
$donde = "";
foreach ($empleados as $nombreDep => $lista) {
    foreach ($lista as $emp) {
        if ($emp["Salario"] > $max) {
            $max = $emp["Salario"];
            $quien = $emp["Nombre"];
            $donde = $nombreDep;
        }
    }
}
echo "Máximo: " . $quien . " (" . $donde . ") con " . $max . " €\n";  // → Máximo: Marta Ruiz (Informática) con 3200 €

// c) Buscar a alguien: ¿en qué departamento está Nuria Pardo?
//    break 2 sale de los DOS foreach a la vez (break solo saldría del de dentro)
$encontrado = "no está";
foreach ($empleados as $nombreDep => $lista) {
    foreach ($lista as $emp) {
        if ($emp["Nombre"] == "Nuria Pardo") {
            $encontrado = $nombreDep;
            break 2;
        }
    }
}
echo "Nuria Pardo trabaja en: " . $encontrado . "\n";  // → Nuria Pardo trabaja en: Marketing

// d) Contar cuántos cobran 2000 o más en cada departamento
foreach ($empleados as $nombreDep => $lista) {
    $cuantos = 0;
    foreach ($lista as $emp) {
        if ($emp["Salario"] >= 2000) {
            $cuantos++;
        }
    }
    echo $nombreDep . ": " . $cuantos . "\n";
}
// → Informática: 4 · Ventas: 2 · Marketing: 2 · Recursos Humanos: 2 · Logística: 1

// =====================================================================
titulo("6. Combinar los dos arrays (comparten la clave: el departamento)");
// =====================================================================
// Recorro $departamentos y, con la MISMA clave, entro en $empleados.
foreach ($departamentos as $nombreDep => $datos) {
    $gasto = 0;
    foreach ($empleados[$nombreDep] as $emp) {
        $gasto += $emp["Salario"];
    }
    if ($gasto > $datos["Presupuesto"]) {
        $estado = "EXCEDIDO";
    } else {
        $estado = "OK";
    }
    echo $nombreDep . ": gasta " . $gasto . " de " . $datos["Presupuesto"] . " → " . $estado . "\n";
}
// → Informática: gasta 10300 de 9000 → EXCEDIDO
// → Ventas: gasta 8700 de 9500 → OK
// → Marketing: gasta 6700 de 7000 → OK
// → Recursos Humanos: gasta 4700 de 4000 → EXCEDIDO
// → Logística: gasta 8900 de 9000 → OK

// =====================================================================
titulo("7. Aplanar: de array anidado a una lista simple");
// =====================================================================
// Muy útil para pintar UNA tabla con todos o para usar checkbox (cada fila con su id).
// Es lo que hace plantillaPlana() en datos.php:
$plantilla = plantillaPlana($empleados);
echo count($plantilla) . " filas\n";             // → 18 filas
mostrar($plantilla[0]);    // → [id => 1, nombre => Marta Ruiz, departamento => Informática, salario => 3200]
mostrar($plantilla[17]);   // → [id => 18, nombre => Pilar Sanz, departamento => Logística, salario => 1500]

// Ahora basta UN foreach para todos:
foreach ($plantilla as $fila) {
    if ($fila["salario"] >= 2800) {
        echo $fila["id"] . " " . $fila["nombre"] . "\n";
    }
}
// → 1 Marta Ruiz
// → 5 Pablo Soler
// → 9 Lucía Prieto

// =====================================================================
titulo("8. Modificar un array mientras lo recorres");
// =====================================================================
// foreach te da una COPIA de cada valor: cambiar $s NO cambia el array.
$sueldos = [1000, 2000];
foreach ($sueldos as $s) {
    $s = $s * 2;
}
mostrar($sueldos);                               // → [0 => 1000, 1 => 2000]   ¡no ha cambiado!

// Forma 1 (la más clara): usar la clave y escribir en el array
foreach ($sueldos as $i => $s) {
    $sueldos[$i] = $s * 2;
}
mostrar($sueldos);                               // → [0 => 2000, 1 => 4000]

// Forma 2: con & (referencia). Hay que hacer unset($s) al acabar.
foreach ($sueldos as &$s) {
    $s = $s + 1;
}
unset($s);
mostrar($sueldos);                               // → [0 => 2001, 1 => 4001]

// En el array anidado: subir un 10 % a Ventas. Trabajo sobre una COPIA:
// con arrays, $copia = $empleados crea una copia independiente (con objetos NO pasa).
$copia = $empleados;
foreach ($copia["Ventas"] as $i => $emp) {
    $copia["Ventas"][$i]["Salario"] = round($emp["Salario"] * 1.10, 2);
}
echo $copia["Ventas"][0]["Salario"] . "\n";      // → 3300   (en la copia)
echo $empleados["Ventas"][0]["Salario"] . "\n";  // → 3000   (el original no cambia)

// =====================================================================
titulo("9. Añadir datos a arrays anidados");
// =====================================================================
$copia = $empleados;
$copia["Ventas"][] = ["Nombre" => "Nuevo Comercial", "Salario" => 1700];   // al final de Ventas
$copia["Calidad"] = [];                                                     // departamento nuevo vacío
$copia["Calidad"][] = ["Nombre" => "Rosa Gil", "Salario" => 2200];
echo count($copia["Ventas"]) . " en Ventas\n";   // → 5 en Ventas
echo count($copia) . " departamentos\n";         // → 6 departamentos
echo $copia["Calidad"][0]["Nombre"] . "\n";      // → Rosa Gil
