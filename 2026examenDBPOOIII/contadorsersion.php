<?php
session_start();

$sesionAnulada = false;

if (!isset($_SESSION['contador'])) {
    $_SESSION['contador'] = 100;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    if ($_POST['accion'] === 'Incrementar') {
        $_SESSION['contador']++;
    } elseif ($_POST['accion'] === 'Decrementar') {
        $_SESSION['contador']--;
    } elseif ($_POST['accion'] === 'Anular') {
        $_SESSION = [];
        session_destroy();
        $sesionAnulada = true;
    }
}


?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Contador con sesión</title>
</head>
<body>
    <h1>Contador con sesión</h1>

    <?php if ($sesionAnulada): ?>
        <p>La sesión ha sido anulada <input type="button" value=" Recargar " onclick="location.href = location.pathname;"></p>
    <?php else: ?>
        <p>Valor de contador = <?= $_SESSION['contador'] ?></p>
        <form method="post">
            <input type="submit" name="accion" value="Incrementar">
            <input type="submit" name="accion" value="Decrementar">
            <input type="submit" name="accion" value="Anular">
        </form>
    <?php endif; ?>
</body>
</html>