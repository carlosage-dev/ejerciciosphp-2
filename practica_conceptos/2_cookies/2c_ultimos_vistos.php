<?php
/*
 *  2c · ÚLTIMOS PRODUCTOS VISTOS (cookie + explode/implode)  (SOLUCIÓN)
 */

$productos = [
    "Teclado"  => 25,
    "Ratón"    => 12,
    "Monitor"  => 180,
    "Portátil" => 750,
    "Cascos"   => 40,
];

$msg = "";
$vistos = [];

// 1. Leer la cookie y convertirla en array
//    "Teclado,Ratón"  ->  ["Teclado", "Ratón"]
if (isset($_COOKIE['vistos'])) {
    $vistos = explode(",", $_COOKIE['vistos']);
}

// 2. ¿Se ha pinchado un producto?
if (isset($_GET['ver'])) {
    $prod = $_GET['ver'];

    if (isset($productos[$prod])) {
        $msg = "Estás viendo: " . $prod . " (" . $productos[$prod] . " €)";

        // Si ya estaba en la lista, lo quitamos (para no repetirlo)
        $posicion = array_search($prod, $vistos);
        if ($posicion !== false) {
            unset($vistos[$posicion]);
        }

        // Lo ponemos el PRIMERO
        array_unshift($vistos, $prod);

        // Nos quedamos solo con los 3 primeros
        $vistos = array_slice($vistos, 0, 3);

        // 3. Guardar: array -> texto   ["Monitor","Teclado"] -> "Monitor,Teclado"
        setcookie("vistos", implode(",", $vistos), time() + 7 * 24 * 3600);
    } else {
        $msg = "Ese producto no existe";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>2c Últimos vistos</title></head>
<body>
    <h2>Tienda</h2>
    <ul>
        <?php foreach ($productos as $nombre => $precio): ?>
            <li><a href="?ver=<?= $nombre ?>"><?= $nombre ?></a></li>
        <?php endforeach; ?>
    </ul>

    <?php if ($msg != ""): ?>
        <p><?= $msg ?></p>
    <?php endif; ?>

    <?php if (count($vistos) > 0): ?>
        <p>Vistos recientemente: <?= implode(", ", $vistos) ?></p>
    <?php endif; ?>
</body>
</html>