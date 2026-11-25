<?php
// Guardar en XAMPP como: htdocs/repaso/ejemplo2_get_vs_post.php

$salidaGet = "";
$salidaPost = "";

// Caso GET: al enviar el formulario GET
if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['filtro'])) {
    $filtro = trim($_GET['filtro']);
    if ($filtro == "") {
        $salidaGet = "Has enviado GET con el filtro vacio (mostrando todo).";
    } else {
        $salidaGet = "Resultado de busqueda por GET para: " . $filtro;
    }
}

// Caso POST: al enviar el formulario POST
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['texto_post'])) {
    $texto = trim($_POST['texto_post']);
    if ($texto == "") {
        $salidaPost = "Has enviado el formulario POST sin escribir nada.";
    } else {
        $salidaPost = "Datos recibidos por POST (no salen en la URL): " . $texto;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ejemplo 2: GET muestra algo, POST otra cosa</title>
</head>
<body>
    <h1>Ejemplo 2: Dos formularios (GET vs POST)</h1>

    <!-- FORMULARIO GET -->
    <h2>Formulario por GET (filtrar / consultar)</h2>
    <form method="get">
        <label>Filtro:</label>
        <input type="text" name="filtro" placeholder="Ej. php">
        <button type="submit">Enviar GET</button>
    </form>

    <?php if ($salidaGet != ""): ?>
        <p style="background: #eef6ff; padding: 8px;"><?php echo $salidaGet; ?></p>
    <?php endif; ?>

    <hr>

    <!-- FORMULARIO POST -->
    <h2>Formulario por POST (enviar datos)</h2>
    <form method="post">
        <label>Mensaje:</label>
        <input type="text" name="texto_post" placeholder="Escribe algo...">
        <button type="submit">Enviar POST</button>
    </form>

    <?php if ($salidaPost != ""): ?>
        <p style="background: #eefbf0; padding: 8px;"><?php echo $salidaPost; ?></p>
    <?php endif; ?>
</body>
</html>