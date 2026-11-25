<?php
session_start();
$msg = "";
$intentosMax = 7;

if (!isset($_SESSION['numAdivino'])) {
    $_SESSION['numAdivino'] = rand(1, 100);
}

if (!isset($_SESSION['intentos'])) {
    $_SESSION['intentos'] = 0;
}

if (!isset($_SESSION['historial'])) {
    $_SESSION['historial'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['orden'])) {
    switch ($_POST['orden']) {
        case "probar":
            $numInput = $_POST['numero'];

            if ($numInput === "" || !is_numeric($numInput)) {
                $msg = "ERROR DE FORMATO: introduce un número válido.";
                break;
            }

            $num = (int) $numInput;

            if ($num < 1 || $num > 100) {
                $msg = "ERROR: debe ser un número entre 1 y 100.";
                break;
            }

            if ($_SESSION['intentos'] >= $intentosMax) {
                $msg = "Has perdido. El número era " . $_SESSION['numAdivino'] . ".";
                break;
            }

            $_SESSION['intentos']++;
            $_SESSION['historial'][] = $num;

            if ($num === $_SESSION['numAdivino']) {
                $msg = "¡ENHORABUENA! Has ganado en " . $_SESSION['intentos'] . " intentos.";
            } elseif ($num > $_SESSION['numAdivino']) {
                $msg = "Demasiado alto.";
            } else {
                $msg = "Demasiado pequeño.";
            }

            if ($num !== $_SESSION['numAdivino'] && $_SESSION['intentos'] >= $intentosMax) {
                $msg .= " Has agotado tus " . $intentosMax . " intentos. El número era " . $_SESSION['numAdivino'] . ".";
            }
            break;

        case "nuevapartida":
            $_SESSION['intentos'] = 0;
            $_SESSION['numAdivino'] = rand(1, 100);
            $_SESSION['historial'] = [];
            $msg = "Nueva partida iniciada.";
            break;

        case "salir":
            $msg = "SESIÓN CERRADA. ¡Hasta pronto!";
            $_SESSION = [];
            session_destroy();
            break;

        default:
            $msg = "Orden no reconocida.";
            break;
    }
}

$intentosActuales = $_SESSION['intentos'] ?? 0;
$historialActual = $_SESSION['historial'] ?? [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adivina el número</title>
</head>
<body>
    <h1>Adivina el número</h1>
    <p>Tengo un número entre el 1 y 100.</p>
    <p>Intentos realizados: <?= $intentosActuales ?> de <?= $intentosMax ?></p>

    <form action="" method="post">
        <label>
            Tu número:
            <input type="number" name="numero" min="1" max="100">
        </label>
        <button type="submit" name="orden" value="probar">Probar</button>
        <button type="submit" name="orden" value="nuevapartida">Nueva partida</button>
        <button type="submit" name="orden" value="salir">Salir</button>
    </form>

    <?php if ($msg !== ""): ?>
        <p><strong><?= $msg ?></strong></p>
    <?php endif; ?>

    <?php if (!empty($historialActual)): ?>
        <p>Historial de intentos: <?= implode(", ", $historialActual) ?></p>
    <?php endif; ?>
</body>
</html>