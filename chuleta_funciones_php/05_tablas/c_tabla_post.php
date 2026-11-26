<?php
/*
 * =====================================================================
 *  05c · TABLA QUE SE MUESTRA DESPUÉS DE UN FORMULARIO POST
 * =====================================================================
 *  Busca los empleados que cobran AL MENOS el salario que escribas.
 *
 *  ¿HACE FALTA UN if EN EL HTML?  SÍ, y aquí DOS:
 *   1) ¿Se ha enviado el formulario? Antes de enviarlo no hay búsqueda
 *      → variable $buscado = true/false
 *   2) ¿Ha encontrado a alguien? Puede salir vacío
 *      → if (count($resultado) == 0) mensaje, si no, la tabla
 * =====================================================================
 */
require_once '../datos.php';

$buscado = false;       // ¿se ha hecho la búsqueda?
$minimo = "";           // lo que escribió (para volver a pintarlo en el input)
$resultado = [];        // filas encontradas
$msg = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['minimo'])) {
        $minimo = trim($_POST['minimo']);
    }

    if ($minimo == "" || !is_numeric($minimo)) {
        $msg = "Escribe un salario válido";
    } else {
        $buscado = true;
        // Dos niveles de listas → dos foreach
        foreach ($empleados as $nombreDep => $lista) {
            foreach ($lista as $emp) {
                if ($emp["Salario"] >= $minimo) {
                    $resultado[] = [
                        "nombre"       => $emp["Nombre"],
                        "departamento" => $nombreDep,
                        "salario"      => $emp["Salario"],
                    ];
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>05c · Tabla por POST</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
<div class="caja">
    <nav><a href="a_tabla_foreach_html.php">a) foreach HTML</a> <a href="b_tabla_get.php">b) GET</a>
         <a href="c_tabla_post.php">c) POST</a> <a href="d_tabla_funcion_checkbox.php">d) función + checkbox</a></nav>
    <h1>c) Tabla con el resultado de un formulario POST</h1>

    <form method="post">
        Salario mínimo: <input type="number" name="minimo" value="<?= $minimo ?>">
        <button>Buscar</button>
    </form>

    <?php if ($msg != ""): ?>
        <p class="msg"><?= $msg ?></p>
    <?php endif; ?>

    <!-- if 1: ¿se ha buscado? -->
    <?php if ($buscado): ?>

        <!-- if 2: ¿hay resultados? -->
        <?php if (count($resultado) == 0): ?>
            <p class="msg">Nadie cobra <?= $minimo ?> € o más.</p>
        <?php else: ?>
            <p class="resultado"><?= count($resultado) ?> empleado(s) cobran <?= $minimo ?> € o más.</p>
            <table>
                <tr><th>Nombre</th><th>Departamento</th><th>Salario</th></tr>
                <?php foreach ($resultado as $fila): ?>
                    <tr>
                        <td><?= $fila["nombre"] ?></td>
                        <td><?= $fila["departamento"] ?></td>
                        <td><?= number_format($fila["salario"], 2, ',', '.') ?> €</td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>

    <?php endif; ?>

    <p class="nota">Prueba con 2500 (salen 6), con 5000 (nadie) y con "abc" (error).
        Con POST el número no aparece en la URL; si pulsas F5, el navegador pregunta si quieres reenviar.</p>
</div>
</body>
</html>
