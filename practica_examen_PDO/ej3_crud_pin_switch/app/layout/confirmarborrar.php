<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>GESTIÓN DE EMPLEADOS</title>
    <link href="web/default.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="container" style="max-width: 500px;">
    <div id="header"><h1>Confirmar borrado</h1></div>
    <p class="error">
        ¿Seguro que quieres borrar a <b><?= $emp->nombre ?></b> (<?= $emp->cod_emp ?>)?
    </p>
    <form method="POST">
        <input type="hidden" name="cod_emp" value="<?= $emp->cod_emp ?>">
        <button name="orden" value="ConfirmarBorrar">Sí, borrar</button>
        <button name="orden" value="Cancelar">Cancelar</button>
    </form>
</div>
</body>
</html>
<?php exit(); ?>
