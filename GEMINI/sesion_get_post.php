<?php
// Guardar en XAMPP como: htdocs/repaso/ejemplo_sesion_get_post.php
session_start();

if (!isset($_SESSION['puntos'])) {
    $_SESSION['puntos'] = 0;
}

$mensaje = "";

// 1) Acciones rápidas por GET (?accion=reset o ?accion=restar)
if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['accion'])) {
    if ($_GET['accion'] == "reset") {
        $_SESSION['puntos'] = 0;
        $mensaje = "Sesion reseteada a 0 puntos por GET.";
    } elseif ($_GET['accion'] == "restar") {
        $_SESSION['puntos'] = $_SESSION['puntos'] - 1;
        $mensaje = "Restado 1 punto en sesion por GET.";
    }
}

// 2) Sumar cantidad por POST
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['sumar_post'])) {
    if (isset($_POST['cantidad']) && is_numeric($_POST['cantidad'])) {
        $cantidad = (int) $_POST['cantidad'];
        $_SESSION['puntos'] = $_SESSION['puntos'] + $cantidad;
        $mensaje = "Sumados " . $cantidad . " puntos en sesion por POST.";
    } else {
        $mensaje = "Error: introduce un numero valido en POST.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sesion con POST y GET</title>
</head>
<body>
    <h1>Sesion <code>$_SESSION['puntos']</code> controlada por POST y GET</h1>

    <p>Puntos actuales en sesion: <strong><?php echo $_SESSION['puntos']; ?></strong></p>

    <?php if ($mensaje != ""): ?>
        <p><strong><?php echo $mensaje; ?></strong></p>
    <?php endif; ?>

    <!-- Control por POST: sumar puntos -->
    <h2>Sumar puntos (POST)</h2>
    <form method="post">
        <label>Cantidad:</label>
        <input type="text" name="cantidad" value="5" size="4">
        <button type="submit" name="sumar_post" value="1">Sumar por POST</button>
    </form>

    <hr>

    <!-- Control por GET: restar o resetear -->
    <h2>Acciones por enlace (GET)</h2>
    <p>
        <a href="?accion=restar">-1 punto (GET)</a> |
        <a href="?accion=reset">Resetear a 0 (GET)</a>
    </p>
</body>
</html>