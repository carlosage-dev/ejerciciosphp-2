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
        <!-- TODO: nombre del empleado -->
        <h1>Bienvenido/a <?= $emp->nombre ?></h1>
    </div>

    <!-- TODO: puesto, departamento (nombre + localidad) y nº de accesos -->
    <p>Puesto: <?= $emp->puesto ?> | Departamento: <?=$dep->nombre  ?> | Accesos: <?= $emp->accesos ?></p>

    <!-- TODO: mensaje de la cookie -->
    <p class="msg"><?= $msgCookie ?></p>

    <h2>Compañeros de tu departamento</h2>
    <table>
        <tr>
            <th>Código</th><th>Nombre</th><th>Puesto</th><th>Sueldo</th><th>Antigüedad (años)</th>
        </tr>
        <?php
            $sumaSueldo=0;
            $sumaAnios=0;
            $anios = 0;
            $contEmple=0;        
        ?>

        <?php foreach($tcompaneros as $companero): ?>
            <?php
                $sumaSueldo = $sumaSueldo + $companero->sueldo;
            ?>
            <tr>
                <td><?= $companero->cod_emp ?></td>
                <td><?= $companero->nombre ?></td>
                <td><?= $companero->puesto ?></td>
                <td><?= $companero->sueldo ?></td>
                <td><?=$anios= antiguedad($companero->fecha_alta) ?></td>
            </tr>
            <?php
            $sumaAnios = $sumaAnios + $anios;
            $contEmple++;
            ?>

        <!-- TODO: una fila por empleado con la sintaxis alternativa  foreach (...):  ...  endforeach;
                   - clase "yo" para el empleado conectado + "(tú)" junto al nombre
                   - clase "bloqueado" si bloqueado == 1
                   - ve acumulando lo que necesites para la fila del total            -->


        <!-- TODO: fila de TOTAL (suma) y MEDIA -->
        <?php endforeach;?>

        <?php
            $sumaAnios=floor($sumaAnios/$contEmple);
            $sumaSueldo = $sumaSueldo/$contEmple;
        ?>
        <tr class="total">
            <td colspan="3">TOTAL / MEDIA</td><td><?= $sumaSueldo ?></td><td><?= $sumaAnios ?></td>
        </tr>
    </table>

    <a href="acceso.html">Salir</a>
</div>
</body>
</html>
