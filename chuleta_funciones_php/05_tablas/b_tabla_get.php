<?php
/*
 * =====================================================================
 *  05b · TABLA QUE SE MUESTRA DESPUÉS DE UN FORMULARIO GET
 * =====================================================================
 *  El usuario elige un departamento en un <select> y pulsa "Ver".
 *  La URL queda así:  b_tabla_get.php?depto=Ventas
 *
 *  ¿HACE FALTA UN if EN EL HTML?  SÍ.
 *  La primera vez que entras NO se ha elegido nada: no hay datos que pintar.
 *  Si pintas la tabla sin if, el foreach recorrería algo que no existe
 *  (Warning) o saldría una tabla vacía sin sentido.
 *
 *  Regla:
 *   - Los datos están SIEMPRE (listado general)          → sin if
 *   - Los datos dependen de un formulario o pueden faltar → con if
 *   - La lista puede venir VACÍA (filtro sin resultados) → if/else con mensaje
 * =====================================================================
 */
require_once '../datos.php';

$depElegido = "";       // nombre del departamento elegido ("" = ninguno todavía)
$lista = [];            // sus empleados
$msg = "";

// GET: ¿se ha elegido un departamento?
if (isset($_GET['depto'])) {
    $depElegido = $_GET['depto'];

    // Lista blanca: ¿existe ese departamento? (alguien puede escribir ?depto=Inventado)
    if (isset($empleados[$depElegido])) {
        $lista = $empleados[$depElegido];
    } else {
        $msg = "Ese departamento no existe";
        $depElegido = "";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>05b · Tabla por GET</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
<div class="caja">
    <nav><a href="a_tabla_foreach_html.php">a) foreach HTML</a> <a href="b_tabla_get.php">b) GET</a>
         <a href="c_tabla_post.php">c) POST</a> <a href="d_tabla_funcion_checkbox.php">d) función + checkbox</a></nav>
    <h1>b) Tabla que sale al elegir un departamento (GET)</h1>

    <form method="get">
        Departamento:
        <select name="depto">
            <?php foreach ($departamentos as $nombreDep => $datos): ?>
                <option value="<?= $nombreDep ?>" <?= ($nombreDep == $depElegido) ? "selected" : "" ?>><?= $nombreDep ?></option>
            <?php endforeach; ?>
        </select>
        <button>Ver</button>
    </form>

    <?php if ($msg != ""): ?>
        <p class="msg"><?= $msg ?></p>
    <?php endif; ?>

    <!-- AQUÍ SÍ HACE FALTA EL if: solo hay tabla si se ha elegido un departamento válido -->
    <?php if ($depElegido != ""): ?>
        <h2><?= $depElegido ?> · jefe: <?= $departamentos[$depElegido]["Jefe"] ?></h2>
        <table>
            <tr><th>#</th><th>Nombre</th><th>Salario</th></tr>
            <?php foreach ($lista as $pos => $emp): ?>
                <tr>
                    <td><?= $pos + 1 ?></td>
                    <td><?= $emp["Nombre"] ?></td>
                    <td><?= number_format($emp["Salario"], 2, ',', '.') ?> €</td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p class="nota">Elige un departamento y pulsa "Ver". (Fíjate en la URL al hacerlo: <code>?depto=...</code>)</p>
    <?php endif; ?>

    <p class="nota">Prueba a escribir en la barra <code>b_tabla_get.php?depto=Marketing</code> o <code>?depto=Inventado</code>.</p>
</div>
</body>
</html>
