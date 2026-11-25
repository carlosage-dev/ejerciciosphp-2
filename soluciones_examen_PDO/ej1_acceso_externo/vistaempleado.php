<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Portal del empleado</title>
    <link href="default.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="container">
    <div id="header">
        <h1>Bienvenido/a <?= $emp->nombre ?></h1>
    </div>

    <p>Puesto: <?= $emp->puesto ?> |
       Departamento: <?= $dep->nombre ?> (<?= $dep->localidad ?>) |
       Accesos: <?= $emp->accesos ?></p>

    <p class="msg"><?= $msgCookie ?></p>

    <h2>Compañeros de tu departamento</h2>
    <table>
        <tr>
            <th>Código</th><th>Nombre</th><th>Puesto</th><th>Sueldo</th><th>Antigüedad (años)</th>
        </tr>

        <?php
            $sumaSueldo = 0;
            $contEmple  = 0;
        ?>

        <?php foreach ($tcompaneros as $companero): ?>
            <?php
                // Clase de la fila: "yo", "bloqueado", las dos o ninguna
                $clase = "";
                if ($companero->cod_emp == $emp->cod_emp) {
                    $clase .= "yo ";
                }
                if ($companero->bloqueado == 1) {
                    $clase .= "bloqueado";
                }
                $sumaSueldo += $companero->sueldo;
                $contEmple++;
            ?>
            <tr class="<?= $clase ?>">
                <td><?= $companero->cod_emp ?></td>
                <td><?= $companero->nombre ?> <?= ($companero->cod_emp == $emp->cod_emp) ? "(tú)" : "" ?></td>
                <td><?= $companero->puesto ?></td>
                <td><?= number_format($companero->sueldo, 2, ',', '.') ?> €</td>
                <td><?= antiguedad($companero->fecha_alta) ?></td>
            </tr>
        <?php endforeach; ?>

        <?php $media = $sumaSueldo / $contEmple; ?>
        <tr class="total">
            <td colspan="3">TOTAL / MEDIA</td>
            <td><?= number_format($sumaSueldo, 2, ',', '.') ?> €</td>
            <td>Media: <?= number_format($media, 2, ',', '.') ?> €</td>
        </tr>
    </table>

    <a href="acceso.html">Salir</a>
</div>
</body>
</html>
