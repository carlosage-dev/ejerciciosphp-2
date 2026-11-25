<?php
// Según la orden, unos campos se pueden editar y otros no
$soloLectura = "";
$desactivado = "";
$codReadonly = "readonly";       // el código solo se escribe en Nuevo

if ($orden == "Nuevo") {
    $codReadonly = "";
}
if ($orden == "Detalles") {
    $soloLectura = "readonly";
    $desactivado = "disabled";   // un <select> no tiene readonly: se usa disabled (y NO se envía)
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>GESTIÓN DE EMPLEADOS</title>
    <link href="web/default.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="container" style="max-width: 600px;">
    <div id="header"><h1>EMPLEADO · <?= $orden ?></h1></div>

    <form method="POST">
        <table>
            <tr><td>Código</td>
                <td><input type="text" name="cod_emp" value="<?= $emp->cod_emp ?>" size="10" <?= $codReadonly ?>></td></tr>
            <tr><td>Nombre</td>
                <td><input type="text" name="nombre" value="<?= $emp->nombre ?>" size="30" <?= $soloLectura ?>></td></tr>
            <tr><td>Clave</td>
                <td><input type="password" name="clave" value="<?= $emp->clave ?>" size="15" <?= $soloLectura ?>></td></tr>
            <tr><td>Puesto</td>
                <td>
                    <select name="puesto" <?= $desactivado ?>>
                        <?php foreach (PUESTOS as $p): ?>
                            <option value="<?= $p ?>" <?= ($p == $emp->puesto) ? "selected" : "" ?>><?= $p ?></option>
                        <?php endforeach; ?>
                    </select>
                </td></tr>
            <tr><td>Sueldo</td>
                <td><input type="text" name="sueldo" value="<?= $emp->sueldo ?>" size="10" <?= $soloLectura ?>></td></tr>
            <tr><td>Departamento</td>
                <td>
                    <select name="cod_dep" <?= $desactivado ?>>
                        <?= opcionesDepartamentos($emp->cod_dep) ?>
                    </select>
                </td></tr>
        </table>

        <?php if ($orden != "Detalles"): ?>
            <input type="submit" name="orden" value="<?= $orden ?>">
        <?php endif; ?>
        <input type="submit" name="orden" value="Volver">
    </form>
</div>
</body>
</html>
<?php exit(); ?>
