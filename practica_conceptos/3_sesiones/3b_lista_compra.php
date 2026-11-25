<?php
/*
 * ================================================================
 *  3b · LISTA DE LA COMPRA EN SESIÓN (array en $_SESSION)  (~15 min)
 * ================================================================
 *  $_SESSION puede guardar ARRAYS (una cookie no).
 *
 *  1. Si no existe $_SESSION['lista'] -> créala como array vacío.
 *  2. POST (formulario):
 *       - botón name="accion" value="Añadir": añade el producto escrito
 *           · vacío                     -> error
 *           · ya está en la lista       -> "Ya tienes pan en la lista"  (in_array)
 *           · guarda en minúsculas y sin espacios sobrantes (strtolower, trim)
 *       - botón name="accion" value="Vaciar": deja la lista vacía
 *  3. GET (enlaces): cada producto de la lista lleva al lado un enlace
 *       [quitar] -> ?quitar=POSICION   (la posición en el array: foreach ($lista as $i => $p))
 *       Al pinchar: unset de esa posición. Si no existe esa posición, no hagas nada.
 *  4. Muestra: "Tienes N productos" y la lista ordenada alfabéticamente
 *     (ojo: ordena una COPIA para mostrar, o las posiciones de [quitar] no cuadrarán... ¿o sí?
 *      Piénsalo / pruébalo)
 * ================================================================
 */

session_start();
$msg = "";

if (!isset($_SESSION['lista'])) {
    $_SESSION['lista'] = [];
}

// TODO

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['accion'])) {
    $contador = 0;
    switch ($_POST['accion']) {
        case "Añadir":
            if ($_POST['producto'] == "") {
                $msg = "ERROR PRODUCTO VACIO";
            } else {
                $pro = $_POST['producto'];
                array_push($_SESSION['lista'], $pro);
                $msg = "Tienes " . count($_SESSION['lista']) . " Productos";
            }
            break;

        case "Vaciar":
            $_SESSION['lista'] = [];
            break;
    }
}

// GET: quitar un producto por su posición
if (isset($_GET['quitar'])) {
    $pos = (int) $_GET['quitar'];
    if (isset($_SESSION['lista'][$pos])) {        // ¿existe esa posición?
        unset($_SESSION['lista'][$pos]);
    }
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>3b Lista compra</title>
</head>

<body>
    <h2>Lista de la compra</h2>
    <form method="post">
        Producto: <input type="text" name="producto">
        <button name="accion" value="Añadir">Añadir</button>
        <button name="accion" value="Vaciar">Vaciar lista</button>
    </form>

    <!-- TODO: $msg -->
    <!-- TODO: "Tienes N productos" + <ul> con [quitar] -->
    <?php if ($msg != ""): ?>
        <p><?= $msg ?></p>
    <?php endif; ?>

    <p>Tienes <?= count($_SESSION['lista']) ?> productos</p>

    <?php
    $ordenada = $_SESSION['lista'];   // COPIA para ordenar
    asort($ordenada);                 // ordena alfabéticamente SIN cambiar las posiciones
    ?>
    <ul>
        <?php foreach ($ordenada as $i => $p): ?>
            <li><?= $p ?> <a href="?quitar=<?= $i ?>">[quitar]</a></li>
        <?php endforeach; ?>
    </ul>

</body>

</html>