<?php
session_start();

$sesionAnulada = false;

// Comprobamos si se ha enviado el formulario mediante POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    switch ($_POST['accion']) {
        case 'Incrementar':
            if (!isset($_SESSION['contador'])) {
                $_SESSION['contador'] = 100;
            }
            $_SESSION['contador']++;
            break;

        case 'Decrementar':
            if (!isset($_SESSION['contador'])) {
                $_SESSION['contador'] = 100;
            }
            $_SESSION['contador']--;
            break;

        case 'Anular':
            // Eliminamos variables de sesión y destruimos la sesión
            $_SESSION = [];
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(
                    session_name(),
                    '',
                    time() - 42000,
                    $params["path"],
                    $params["domain"],
                    $params["secure"],
                    $params["httponly"]
                );
            }
            session_destroy();
            $sesionAnulada = true;
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
            break;
    }
}

// Si la sesión no ha sido anulada y no existe el contador, se inicializa en 100
if (!$sesionAnulada && !isset($_SESSION['contador'])) {
    $_SESSION['contador'] = 100;
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
        <p>La sesión ha sido anulada <input type="button" value=" Recargar " onclick="location.reload();"></p>
    <?php else: ?>
        <p>Valor de contador = <?= htmlspecialchars((string) $_SESSION['contador']) ?></p>
        <form method="post" action="">
            <input type="submit" name="accion" value="Incrementar">
            <input type="submit" name="accion" value="Decrementar">
            <input type="submit" name="accion" value="Anular">
        </form>
    <?php endif; ?>
</body>
</html>