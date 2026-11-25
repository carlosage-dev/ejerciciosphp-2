<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>GESTIÓN DE EMPLEADOS</title>
    <link href="web/default.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="container">
    <div id="header"><h1>GESTIÓN DE EMPLEADOS</h1></div>

    <p><small>La sesión caduca en <?= tiempoRestante() ?> s</small></p>

    <?php if ($msg != ""): ?>
        <p class="msg"><?= $msg ?></p>
    <?php endif; ?>

    <!-- Formulario POST: las acciones masivas MODIFICAN datos -->
    <form method="POST">
        <?= $contenido ?>

        <button name="orden" value="CambiarBloqueo">🔒 Cambiar bloqueo de los marcados</button>
        <br>
        Trasladar marcados a:
        <select name="dep_destino">
            <option value="">-- Elige departamento --</option>
            <?= opcionesDepartamentos() ?>
        </select>
        <button name="orden" value="Trasladar">🚚 Trasladar</button>
    </form>

    <!-- Formulario GET: Nuevo y Terminar solo PIDEN una pantalla -->
    <form method="GET">
        <button name="orden" value="Nuevo">➕ Nuevo empleado</button>
        <button name="orden" value="Terminar">🚪 Terminar</button>
    </form>
</div>
</body>
</html>
