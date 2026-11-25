<?php
// Guardar en XAMPP como: htdocs/repaso/ejemplo1_id_comun.php

$registros = [
    1 => ["nombre" => "Ana",   "rol" => "Admin"],
    2 => ["nombre" => "Luis",  "rol" => "Editor"],
    3 => ["nombre" => "Marta", "rol" => "Lector"],
];

$idActual = "";
$mensaje = "";
$error = "";

// 1) LÓGICA GET: consultar / cargar por id
if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['id'])) {
    if (is_numeric($_GET['id']) && isset($registros[$_GET['id']])) {
        $idActual = $_GET['id'];
        $mensaje = "Cargado por GET -> ID #" . $idActual . ": " . $registros[$idActual]['nombre'] . " (" . $registros[$idActual]['rol'] . ")";
    } else {
        $error = "No existe ningun registro con ese ID.";
    }
}

// 2) LÓGICA POST: modificar el registro del mismo id
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['guardar_post'])) {
    if (isset($_POST['id']) && is_numeric($_POST['id']) && isset($registros[$_POST['id']])) {
        $idActual = $_POST['id'];
        if (isset($_POST['rol']) && trim($_POST['rol']) != "") {
            $nuevoRol = trim($_POST['rol']);
            $mensaje = "Actualizado por POST -> ID #" . $idActual . " (" . $registros[$idActual]['nombre'] . ") ahora tiene rol: " . $nuevoRol;
        } else {
            $error = "Error por POST: el rol no puede estar vacio.";
        }
    } else {
        $error = "Error por POST: el ID indicado no es valido.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ejemplo 1: GET y POST con ID comun</title>
</head>
<body>
    <h1>Ejemplo 1: Parametro comun id (GET + POST)</h1>

    <!-- FORMULARIO 1: GET -->
    <h2>1. Buscar registro (GET)</h2>
    <form method="get">
        <label>ID del registro (1-3):</label>
        <input type="number" name="id" min="1" max="3" value="<?php echo $idActual; ?>">
        <button type="submit">Buscar por GET</button>
    </form>

    <hr>

    <!-- FORMULARIO 2: POST -->
    <h2>2. Cambiar rol del registro actual (POST)</h2>
    <form method="post">
        <input type="hidden" name="id" value="<?php echo $idActual; ?>">

        <p>ID activo: <strong><?php echo ($idActual != "") ? "#" . $idActual : "Ninguno"; ?></strong></p>

        <label>Nuevo rol:</label>
        <input type="text" name="rol" placeholder="Ej. Moderador">
        <button type="submit" name="guardar_post" value="1">Guardar por POST</button>
    </form>

    <hr>

    <?php if ($error != ""): ?>
        <p style="color: red;"><strong><?php echo $error; ?></strong></p>
    <?php endif; ?>

    <?php if ($mensaje != ""): ?>
        <p style="color: green;"><strong><?php echo $mensaje; ?></strong></p>
    <?php endif; ?>
</body>
</html>