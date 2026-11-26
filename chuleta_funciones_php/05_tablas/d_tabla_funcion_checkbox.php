<?php
/*
 * =====================================================================
 *  05d · TABLA CON UNA FUNCIÓN ($msg .=) + CHECKBOX + OPERACIONES
 * =====================================================================
 *  1. pintarTabla() monta la tabla en un string con $msg .= y la devuelve.
 *  2. Cada fila lleva:  <input type="checkbox" name="tseleccion[]" value="ID">
 *       - name con [] → PHP recibe un ARRAY con todos los marcados
 *       - value      → el ID del empleado de esa fila
 *  3. Al pulsar un botón (POST) se recupera ese array:
 *
 *       $_POST['tseleccion']   →  ["1", "5", "9"]   (los ids marcados, como TEXTO)
 *
 *     ⚠️ Si no marcas ninguno, $_POST['tseleccion'] NO EXISTE → isset() primero.
 *
 *  4. Con esos ids se buscan los empleados y se hacen las operaciones.
 *
 *  ¿Y por GET? Si el <form> fuera method="get", llegaría igual en
 *  $_GET['tseleccion'] y la URL sería  ?tseleccion[]=1&tseleccion[]=5
 *  (para operaciones que cambian datos, mejor POST).
 * =====================================================================
 */
require_once '../datos.php';

// Lista plana con id: [ ["id"=>1, "nombre"=>..., "departamento"=>..., "salario"=>...], ... ]
$plantilla = plantillaPlana($empleados);

/**
 * Monta la tabla con $msg .= y la DEVUELVE (no hace echo).
 * $marcados: ids que tienen que salir ya marcados (para no perder la selección).
 */
function pintarTabla(array $plantilla, array $marcados): string
{
    $msg  = "<table>";
    $msg .= "<tr><th></th><th>Id</th><th>Nombre</th><th>Departamento</th><th>Salario</th></tr>";

    foreach ($plantilla as $emp) {
        $checked = "";
        $clase = "";
        if (in_array($emp["id"], $marcados)) {
            $checked = "checked";
            $clase = "marcado";
        }

        $msg .= "<tr class='" . $clase . "'>";
        $msg .= "<td><input type='checkbox' name='tseleccion[]' value='" . $emp["id"] . "' " . $checked . "></td>";
        $msg .= "<td>" . $emp["id"] . "</td>";
        $msg .= "<td>" . $emp["nombre"] . "</td>";
        $msg .= "<td>" . $emp["departamento"] . "</td>";
        $msg .= "<td>" . number_format($emp["salario"], 2, ',', '.') . " €</td>";
        $msg .= "</tr>";
    }

    $msg .= "</table>";
    return $msg;
}

/** Busca un empleado por su id en la plantilla (o null) */
function buscarPorId(array $plantilla, int $id)
{
    foreach ($plantilla as $emp) {
        if ($emp["id"] == $id) {
            return $emp;
        }
    }
    return null;
}

// =====================================================================
//  CONTROLADOR: recuperar lo marcado y operar
// =====================================================================
$marcados = [];         // ids marcados (para volver a marcarlos en la tabla)
$resultado = "";        // HTML con el resultado de la operación
$msg = "";

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['accion'])) {

    if (!isset($_POST['tseleccion'])) {
        $msg = "No has marcado ningún empleado";
    } else {
        // 1) Recuperar el array de ids que llega del name="tseleccion[]"
        $marcados = $_POST['tseleccion'];               // p.ej. ["1", "5", "9"]

        // 2) Convertir los ids en los empleados de verdad
        $seleccionados = [];
        foreach ($marcados as $id) {
            $emp = buscarPorId($plantilla, (int) $id);
            if ($emp !== null) {                        // nunca te fíes de lo que manda el navegador
                $seleccionados[] = $emp;
            }
        }

        // 3) Operar según el botón pulsado
        switch ($_POST['accion']) {

            case "Sumar":
                $total = 0;
                foreach ($seleccionados as $emp) {
                    $total += $emp["salario"];
                }
                $media = $total / count($seleccionados);
                $resultado = "Has marcado " . count($seleccionados) . ". Suman <b>"
                           . number_format($total, 2, ',', '.') . " €</b> (media "
                           . number_format($media, 2, ',', '.') . " €).";
                break;

            case "Subida":
                $resultado = "Con una subida del 10 %:<br>";
                foreach ($seleccionados as $emp) {
                    $nuevo = round($emp["salario"] * 1.10, 2);
                    $resultado .= "· " . $emp["nombre"] . ": " . number_format($emp["salario"], 2, ',', '.')
                                . " € → <b>" . number_format($nuevo, 2, ',', '.') . " €</b><br>";
                }
                break;

            case "Nombres":
                $nombres = [];
                foreach ($seleccionados as $emp) {
                    $nombres[] = $emp["nombre"];
                }
                sort($nombres);                         // [MODIFICA] ordena alfabéticamente
                $resultado = "Marcados (A-Z): " . implode(", ", $nombres);
                break;
        }
    }
}

$tabla = pintarTabla($plantilla, $marcados);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>05d · Tabla con función y checkbox</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
<div class="caja">
    <nav><a href="a_tabla_foreach_html.php">a) foreach HTML</a> <a href="b_tabla_get.php">b) GET</a>
         <a href="c_tabla_post.php">c) POST</a> <a href="d_tabla_funcion_checkbox.php">d) función + checkbox</a></nav>
    <h1>d) Tabla hecha con una función ($msg .=) + checkbox</h1>

    <?php if ($msg != ""): ?>
        <p class="msg"><?= $msg ?></p>
    <?php endif; ?>
    <?php if ($resultado != ""): ?>
        <p class="resultado"><?= $resultado ?></p>
    <?php endif; ?>

    <!-- La tabla va DENTRO del form para que los checkbox se envíen -->
    <form method="post">
        <?= $tabla ?>
        <button name="accion" value="Sumar">∑ Sumar salarios</button>
        <button name="accion" value="Subida">📈 Simular subida 10 %</button>
        <button name="accion" value="Nombres">🔤 Nombres ordenados</button>
    </form>

    <?php if (isset($_POST['tseleccion'])): ?>
        <p class="nota">Lo que ha llegado en <code>$_POST['tseleccion']</code>:
            <code>[<?= implode(", ", $_POST['tseleccion']) ?>]</code></p>
    <?php endif; ?>
</div>
</body>
</html>
