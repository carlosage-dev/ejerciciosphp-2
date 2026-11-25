<?php
/*
 * ================================================================
 *  1d · GET Y POST EN LA MISMA PÁGINA                    (~15 min)
 * ================================================================
 *  En esta página conviven dos cosas independientes:
 *
 *  A) ENLACES (GET): una lista de ciudades generada con foreach desde
 *     el array $ciudades. Cada enlace es:  ?ciudad=Madrid
 *     Al pinchar -> "Has elegido Madrid: 3.300.000 habitantes"
 *     Si la ciudad del GET no está en el array -> "Ciudad desconocida"
 *     (prueba a escribir ?ciudad=Narnia en la barra)
 *
 *  B) FORMULARIO (POST): un textarea "comentario" + botón Enviar.
 *     Al enviar -> "Comentario recibido (N caracteres): ..."
 *     Vacío -> error.  Pista: strlen(), trim()
 *
 *  Organiza el PHP de arriba así:
 *     if ($_SERVER['REQUEST_METHOD'] == "GET") { ...parte A... }
 *     if ($_SERVER['REQUEST_METHOD'] == "POST") { ...parte B... }
 *
 *  PREGUNTA PARA PENSAR: cuando envías el comentario (POST), ¿se ve la
 *  ciudad que habías elegido antes? ¿Por qué no?
 * ================================================================
 */

$ciudades = [
    "Madrid"    => 3300000,
    "Barcelona" => 1600000,
    "Valencia"  => 800000,
    "Sevilla"   => 690000,
];

$msgCiudad = "";
$msgComentario = "";

// TODO

// ---------------------------------------------------------------
// A) GET: se ha pinchado un enlace ?ciudad=...
// ---------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isset($_GET['ciudad'])) {
        $ciudad = $_GET['ciudad'];

        // ¿Existe esa ciudad como clave del array?
        if (isset($ciudades[$ciudad])) {
            $habitantes = number_format($ciudades[$ciudad], 0, ',', '.');
            $msgCiudad = "Has elegido " . $ciudad . ": " . $habitantes . " habitantes";
        } else {
            $msgCiudad = "Ciudad desconocida";
        }
    }
}

// ---------------------------------------------------------------
// B) POST: se ha enviado el formulario del comentario
// ---------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $comentario = "";
    if (isset($_POST['comentario'])) {
        $comentario = trim($_POST['comentario']);   // trim quita espacios al principio y al final
    }

    if ($comentario == "") {
        $msgComentario = "ERROR: el comentario está vacío";
    } else {
        $msgComentario = "Comentario recibido (" . strlen($comentario) . " caracteres): " . $comentario;
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>1d GET y POST</title></head>
<body>
    <h2>A) Elige ciudad (GET)</h2>
    <ul>
        <?php foreach ($ciudades as $nombre => $habitantes): ?>
            <li><a href="?ciudad=<?= $nombre ?>"><?= $nombre ?></a></li>
        <?php endforeach; ?>
    </ul>

    <?php if ($msgCiudad != ""): ?>
        <p><?= $msgCiudad ?></p>
    <?php endif; ?>

    <h2>B) Deja un comentario (POST)</h2>
    <form method="post">
        <textarea name="comentario" rows="3" cols="40"></textarea><br>
        <input type="submit" value="Enviar">
    </form>

    <?php if ($msgComentario != ""): ?>
        <p><?= $msgComentario ?></p>
    <?php endif; ?>
</body>
</html>
