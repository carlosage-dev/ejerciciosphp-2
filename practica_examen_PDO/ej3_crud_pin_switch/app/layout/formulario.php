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

    <!--
      TODO: rellena cada campo con los datos de $emp (value="...")
            - Detalles  -> todo readonly / disabled
            - Modificar -> cod_emp readonly
            - Nuevo     -> todo editable
            - puesto: <select> con PUESTOS, marcando el del empleado
            - departamento: <select> con opcionesDepartamentos(...)
    -->
    <form method="POST">
        <table>
            <tr><td>Código</td>
                <td><input type="text" name="cod_emp" value="" size="10"></td></tr>
            <tr><td>Nombre</td>
                <td><input type="text" name="nombre" value="" size="30"></td></tr>
            <tr><td>Clave</td>
                <td><input type="password" name="clave" value="" size="15"></td></tr>
            <tr><td>Puesto</td>
                <td><select name="puesto"></select></td></tr>
            <tr><td>Sueldo</td>
                <td><input type="text" name="sueldo" value="" size="10"></td></tr>
            <tr><td>Departamento</td>
                <td><select name="cod_dep"></select></td></tr>
        </table>
        <input type="submit" name="orden" value="<?= $orden ?>">
        <input type="submit" name="orden" value="Volver">
    </form>
</div>
</body>
</html>
<?php exit(); ?>
