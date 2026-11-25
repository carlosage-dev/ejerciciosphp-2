<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>GESTIÓN DE EMPLEADOS</title>
    <link href="web/default.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="container" style="max-width: 450px;">
    <div id="header"><h1>GESTIÓN DE EMPLEADOS · Acceso</h1></div>

    <?php if ($msg != ""): ?>
        <p class="error"><?= $msg ?></p>
    <?php endif; ?>

    <?php if (!$bloqueadoPin): ?>
        <form method="POST">
            INTRODUZCA EL PIN DE ACCESO
            <input name="pin" type="password" size="5" autofocus>
            <input type="submit" value="Entrar">
        </form>
    <?php endif; ?>
</div>
</body>
</html>
