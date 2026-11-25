<?php
// Guardar en XAMPP como: htdocs/repaso/ejemplo_cookie_get_post.php

$mensaje = "";

// 1) Borrar cookie por GET (?borrar_cookie=1)
if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['borrar_cookie'])) {
    setcookie("tema", "", time() - 3600);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Valor actual de la cookie (si no existe, "claro")
$temaActual = "claro";
if (isset($_COOKIE['tema'])) {
    $temaActual = $_COOKIE['tema'];
}

// 2) Cambiar o guardar cookie por POST
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['guardar_tema'])) {
    if (isset($_POST['tema']) && ($_POST['tema'] == "claro" || $_POST['tema'] == "oscuro")) {
        $temaActual = $_POST['tema'];
        setcookie("tema", $temaActual, time() + 3600);
        $mensaje = "Cookie 'tema' guardada por POST con valor: " . $temaActual;
    } else {
        $mensaje = "Valor de tema no valido.";
    }
}

// Estilo visual sencillo según el valor actual
$fondo = ($temaActual == "oscuro") ? "#222222" : "#ffffff";
$texto = ($temaActual == "oscuro") ? "#f5f5f5" : "#111111";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cookie con POST y GET</title>
</head>
<body style="background: <?php echo $fondo; ?>; color: <?php echo $texto; ?>;">
    <h1>Cookie <code>tema</code> controlada por POST y GET</h1>

    <p>Tema activo ahora mismo: <strong><?php echo $temaActual; ?></strong></p>

    <?php if ($mensaje != ""): ?>
        <p><strong><?php echo $mensaje; ?></strong></p>
    <?php endif; ?>

    <!-- Control por POST: elegir y guardar cookie -->
    <h2>Cambiar cookie (POST)</h2>
    <form method="post">
        <select name="tema">
            <option value="claro" <?php if ($temaActual == "claro") echo "selected"; ?>>Claro</option>
            <option value="oscuro" <?php if ($temaActual == "oscuro") echo "selected"; ?>>Oscuro</option>
        </select>
        <button type="submit" name="guardar_tema" value="1">Guardar por POST</button>
    </form>

    <hr>

    <!-- Control por GET: borrar cookie -->
    <h2>Borrar cookie (GET)</h2>
    <p><a href="?borrar_cookie=1">Borrar cookie por GET</a></p>
</body>
</html>