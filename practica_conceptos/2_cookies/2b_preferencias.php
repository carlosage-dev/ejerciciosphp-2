<?php
$temasPermitidos = ["claro", "oscuro"];
$msg = "";
$nombre = "";
$tema = "claro";

// ===== 1. POST: se ha pulsado un botón =====
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['accion'])) {
    switch ($_POST['accion']) {

        case "Guardar":
            $nombreNuevo = "";
            if (isset($_POST['nombre'])) {
                $nombreNuevo = trim($_POST['nombre']);
            }
            $temaNuevo = "";
            if (isset($_POST['tema'])) {               // si no marcas ningún radio, no llega
                $temaNuevo = $_POST['tema'];
            }

            if ($nombreNuevo == "") {
                $msg = "Pon un nombre";               // SIN exit: hay que pintar la página
            } elseif (!in_array($temaNuevo, $temasPermitidos)) {
                $msg = "Elige un tema";
            } else {
                setcookie("nombre", $nombreNuevo, time() + 30 * 24 * 3600);
                setcookie("tema", $temaNuevo, time() + 30 * 24 * 3600);
                header("Location: 2b_preferencias.php");
                exit();
            }
            break;

        case "Olvidar":
            setcookie("nombre", "", time() - 3600);
            setcookie("tema", "", time() - 3600);
            header("Location: 2b_preferencias.php");
            exit();
    }
}

// ===== 2. Leer las cookies (en todas las cargas) =====
if (isset($_COOKIE['nombre'])) {
    $nombre = $_COOKIE['nombre'];
    if ($msg == "") {                                 // no tapar un mensaje de error
        $msg = "Bienvenido de nuevo, " . $nombre;
    }
}
if (isset($_COOKIE['tema']) && in_array($_COOKIE['tema'], $temasPermitidos)) {
    $tema = $_COOKIE['tema'];
}

// Colores según el tema
if ($tema == "oscuro") {
    $estilo = "background:#222; color:white;";
} else {
    $estilo = "background:white; color:black;";
}
?>

<body style="<?= $estilo ?>">
    <h2>Preferencias</h2>
    <?php if ($msg != ""): ?>
        <p><?= $msg ?></p>
    <?php endif; ?>

    <form method="post">
        Nombre: <input type="text" name="nombre" value="<?= $nombre ?>"><br>
        Tema:
        <label><input type="radio" name="tema" value="claro" <?= ($tema == "claro") ? "checked" : "" ?>> Claro</label>
        <label><input type="radio" name="tema" value="oscuro" <?= ($tema == "oscuro") ? "checked" : "" ?>> Oscuro</label><br><br>
        <button name="accion" value="Guardar">Guardar</button>
        <button name="accion" value="Olvidar">Olvidar</button>
    </form>
</body>