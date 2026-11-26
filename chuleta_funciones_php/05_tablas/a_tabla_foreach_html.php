<?php
/*
 * =====================================================================
 *  05a · TABLA EN EL HTML CON foreach  (se ve SIEMPRE, al cargar)
 * =====================================================================
 *  Los datos existen siempre (no dependen de un formulario), así que
 *  en el HTML NO hace falta ningún if para decidir si se pinta la tabla.
 *
 *  Sintaxis alternativa para mezclar con HTML:
 *      <?php foreach ($lista as $x): ?>  ...HTML...  <?php endforeach; ?>
 *      <?= $variable ?>   es lo mismo que   <?php echo $variable; ?>
 * =====================================================================
 */
require_once '../datos.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>05a · Tabla con foreach en el HTML</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
<div class="caja">
    <nav><a href="a_tabla_foreach_html.php">a) foreach HTML</a> <a href="b_tabla_get.php">b) GET</a>
         <a href="c_tabla_post.php">c) POST</a> <a href="d_tabla_funcion_checkbox.php">d) función + checkbox</a></nav>
    <h1>a) Tablas con foreach dentro del HTML</h1>

    <!-- =============================================================
         TABLA 1: $departamentos → UN foreach (campos fijos por clave)
         ============================================================= -->
    <h2>Departamentos (un foreach)</h2>
    <table>
        <tr><th>Departamento</th><th>Jefe</th><th>Presupuesto</th></tr>
        <?php foreach ($departamentos as $nombreDep => $datos): ?>
            <tr>
                <td><?= $nombreDep ?></td>
                <td><?= $datos["Jefe"] ?></td>
                <td><?= number_format($datos["Presupuesto"], 2, ',', '.') ?> €</td>
            </tr>
        <?php endforeach; ?>
    </table>

    <!-- =============================================================
         TABLA 2: $empleados → DOS foreach (departamentos → empleados)
         Fila de cabecera por departamento + sus empleados + subtotal
         ============================================================= -->
    <h2>Empleados por departamento (dos foreach anidados)</h2>
    <?php $totalGeneral = 0; ?>
    <table>
        <tr><th>Empleado</th><th>Salario</th></tr>

        <?php foreach ($empleados as $nombreDep => $lista): ?>
            <?php $totalDep = 0; ?>
            <tr class="dep"><td colspan="2"><?= $nombreDep ?> (<?= count($lista) ?> empleados)</td></tr>

            <?php foreach ($lista as $emp): ?>
                <?php $totalDep += $emp["Salario"]; ?>
                <tr>
                    <td><?= $emp["Nombre"] ?></td>
                    <td><?= number_format($emp["Salario"], 2, ',', '.') ?> €</td>
                </tr>
            <?php endforeach; ?>

            <tr><td><i>Subtotal <?= $nombreDep ?></i></td><td><i><?= number_format($totalDep, 2, ',', '.') ?> €</i></td></tr>
            <?php $totalGeneral += $totalDep; ?>
        <?php endforeach; ?>

        <tr class="total"><td>TOTAL EMPRESA</td><td><?= number_format($totalGeneral, 2, ',', '.') ?> €</td></tr>
    </table>

    <!-- =============================================================
         TABLA 3: combinar los dos arrays + una clase CSS según una condición
         ============================================================= -->
    <h2>Gasto frente a presupuesto (combina los dos arrays)</h2>
    <table>
        <tr><th>Departamento</th><th>Gasto</th><th>Presupuesto</th><th>Estado</th></tr>
        <?php foreach ($departamentos as $nombreDep => $datos): ?>
            <?php
                $gasto = 0;
                foreach ($empleados[$nombreDep] as $emp) {
                    $gasto += $emp["Salario"];
                }
                // decidir texto y clase ANTES de la fila (más fácil de leer)
                if ($gasto > $datos["Presupuesto"]) {
                    $estado = "EXCEDIDO";
                    $clase = "excedido";
                } else {
                    $estado = "OK";
                    $clase = "ok";
                }
            ?>
            <tr>
                <td><?= $nombreDep ?></td>
                <td><?= number_format($gasto, 2, ',', '.') ?> €</td>
                <td><?= number_format($datos["Presupuesto"], 2, ',', '.') ?> €</td>
                <td class="<?= $clase ?>"><?= $estado ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <p class="nota">Ninguna de estas tablas necesita un <code>if</code> alrededor: los datos están siempre.
        Mira <code>b_tabla_get.php</code> y <code>c_tabla_post.php</code> para cuando la tabla depende de un formulario.</p>
</div>
</body>
</html>
