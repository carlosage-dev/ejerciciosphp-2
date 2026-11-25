<?php
require_once '_lab/lab.php';

/* =====================================================================
 *  GET y POST · todo en un archivo
 *  Cada bloque de código está en un texto para poder ejecutarlo aquí
 *  arriba (antes del HTML) Y enseñártelo más abajo tal cual.
 * ===================================================================== */

// ---------- Bloque 1: lo que llega por GET ----------
$bloqueGet = <<<'PHP'
// GET: los datos llegan en la URL  ->  $_GET
$msgGet = "";

// a) desde el formulario GET (su input se llama "nombre")
if (isset($_GET['nombre'])) {
    $nombre = trim($_GET['nombre']);
    if ($nombre == "") {
        $msgGet = "Escribe un nombre";
    } else {
        $msgGet = "Hola " . htmlspecialchars($nombre) . " → mira la URL: ?nombre=...";
    }
}

// b) desde un enlace con dos datos:  ?color=rojo&talla=M
if (isset($_GET['color']) && isset($_GET['talla'])) {
    $msgGet = "Enlace pulsado: color = " . htmlspecialchars($_GET['color'])
            . ", talla = " . htmlspecialchars($_GET['talla']);
}
PHP;

// ---------- Bloque 2: lo que llega por POST ----------
$bloquePost = <<<'PHP'
// POST: los datos van "dentro" de la petición  ->  $_POST
$usuarios = ["admin" => "1234", "carlos" => "php"];
$msgPost = "";
$usuarioEscrito = "";          // para volver a pintarlo en el input

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['entrar'])) {
    if (isset($_POST['usuario'])) {
        $usuarioEscrito = trim($_POST['usuario']);
    }
    $clave = "";
    if (isset($_POST['clave'])) {
        $clave = $_POST['clave'];
    }

    if ($usuarioEscrito == "" || $clave == "") {
        $msgPost = "Rellena usuario y clave";
    } elseif (!isset($usuarios[$usuarioEscrito]) || $usuarios[$usuarioEscrito] != $clave) {
        $msgPost = "Usuario o clave incorrectos";
    } else {
        $msgPost = "✅ Bienvenido, " . htmlspecialchars($usuarioEscrito) . ". La clave NO ha salido en la URL.";
    }
}
PHP;

// ---------- Bloque 3: varios botones con el mismo name ----------
$bloqueBotones = <<<'PHP'
// Todos los botones se llaman "operacion": solo llega el value del pulsado
$msgBotones = "";

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['operacion'])) {
    switch ($_POST['operacion']) {
        case "Sumar":
            $msgBotones = "Has pulsado SUMAR → 8 + 2 = " . (8 + 2);
            break;
        case "Restar":
            $msgBotones = "Has pulsado RESTAR → 8 - 2 = " . (8 - 2);
            break;
        case "Borrar":
            $msgBotones = "Has pulsado BORRAR";
            break;
    }
}
PHP;

// ---------- Los formularios (HTML con trocitos de PHP) ----------
$formGet = <<<'HTML'
<form method="get" action="01_get_post.php">
    Nombre: <input type="text" name="nombre">
    <button>Enviar por GET</button>
</form>

<p>O pincha un enlace que ya lleva datos:
    <a href="?color=rojo&talla=M">?color=rojo&amp;talla=M</a> ·
    <a href="?color=azul&talla=XL">?color=azul&amp;talla=XL</a>
</p>
HTML;

$formPost = <<<'HTML'
<form method="post" action="01_get_post.php">
    Usuario: <input type="text" name="usuario" value="<?= htmlspecialchars($usuarioEscrito) ?>">
    Clave: <input type="password" name="clave">
    <button name="entrar" value="1">Entrar por POST</button>
</form>
HTML;

$formBotones = <<<'HTML'
<form method="post" action="01_get_post.php">
    <button name="operacion" value="Sumar">+ Sumar</button>
    <button name="operacion" value="Restar">− Restar</button>
    <button name="operacion" value="Borrar">🗑 Borrar</button>
</form>
HTML;

// Ejecutamos la lógica ANTES de pintar nada
eval($bloqueGet);
eval($bloquePost);
eval($bloqueBotones);

cabecera("GET y POST", "Las dos formas de mandar datos del navegador a PHP. Prueba los formularios y mira el panel verde: "
    . "te dice en cada momento qué método se ha usado y qué ha llegado.");
?>

<div class="vivo">
    <h3>🟢 En vivo: lo que ha recibido PHP en esta petición</h3>
    <p style="margin:0 0 8px">
        Método: <span class="badge <?= strtolower($_SERVER['REQUEST_METHOD']) ?>"><?= $_SERVER['REQUEST_METHOD'] ?></span>
        &nbsp; URL: <code><?= e($_SERVER['REQUEST_URI']) ?></code>
    </p>
    <div class="rejilla">
        <?= volcado('$_GET', $_GET) ?>
        <?= volcado('$_POST', $_POST) ?>
    </div>
</div>

<?php
seccion("1. GET vs POST en un minuto");
?>
<table class="tabla-lab">
    <tr><th></th><th><span class="badge get">GET</span></th><th><span class="badge post">POST</span></th></tr>
    <tr><td><b>Dónde van los datos</b></td><td>En la URL: <code>?nombre=Ana&amp;edad=20</code></td><td>Dentro de la petición (no se ven en la URL)</td></tr>
    <tr><td><b>En PHP se leen con</b></td><td><code>$_GET['nombre']</code></td><td><code>$_POST['nombre']</code></td></tr>
    <tr><td><b>Se guardan en el historial</b></td><td>Sí (¡también una clave!)</td><td>No</td></tr>
    <tr><td><b>Al pulsar F5</b></td><td>Recarga sin preguntar</td><td>El navegador avisa: "¿reenviar formulario?"</td></tr>
    <tr><td><b>Se usa para</b></td><td>Pedir o mostrar: buscar, filtrar, ver detalles, enlaces</td><td>Cambiar datos o mandar algo privado: login, alta, borrar</td></tr>
    <tr><td><b>Lo generan</b></td><td><code>&lt;form method="get"&gt;</code>, un enlace <code>&lt;a href="?..."&gt;</code> o escribir en la barra</td><td>Solo <code>&lt;form method="post"&gt;</code></td></tr>
</table>
<?php
caja('nota', 'Para PHP son casi lo mismo',
    '<p><code>$_GET</code> y <code>$_POST</code> son dos <b>arrays asociativos</b>: la clave es el <code>name</code> del campo y el valor, lo que se escribió. '
    . 'Se usan igual (<code>isset</code>, <code>empty</code>, <code>$_POST[\'x\']</code>). Lo que cambia es <b>cómo viajan</b>. '
    . 'PHP no hace nada distinto solo: eres tú quien pregunta con <code>$_SERVER[\'REQUEST_METHOD\']</code> o con <code>isset</code>.</p>');

// =====================================================================
seccion("2. GET: formulario y enlaces", "Envía el formulario o pincha un enlace y mira cómo cambia la URL y el panel verde.");
// =====================================================================
?>
<div class="form-lab">
    <?php eval('?>' . $formGet); ?>
    <?php if ($msgGet != ""): ?>
        <div class="resultado"><?= $msgGet ?></div>
    <?php endif; ?>
</div>
<?php
codigo("El HTML del formulario y de los enlaces",
    'El <code>name</code> del input es la <b>clave</b> que llega a <code>$_GET</code>. En un enlace, la clave es lo que va antes del <code>=</code>. '
    . '<code>?</code> marca dónde empiezan los datos y <code>&amp;</code> separa uno de otro.',
    "?>\n" . $formGet,
    'escribe en la barra de direcciones <code>01_get_post.php?nombre=Luis</code> y pulsa Enter: funciona sin tocar el formulario.');

codigo("El PHP que lo procesa (está al principio del archivo)", 'Siempre con <code>isset</code>: la primera vez que entras no existe nada en <code>$_GET</code>.', $bloqueGet);

// =====================================================================
seccion("3. POST: un login", "Usuarios de prueba: admin / 1234 · carlos / php. Si fallas, el usuario se queda escrito; la clave no.");
// =====================================================================
?>
<div class="form-lab">
    <?php eval('?>' . $formPost); ?>
    <?php if ($msgPost != ""): ?>
        <div class="resultado <?= (strpos($msgPost, '✅') === false) ? 'mal' : '' ?>"><?= $msgPost ?></div>
    <?php endif; ?>
</div>
<?php
codigo("El HTML del formulario", 'El <code>value</code> del usuario vuelve a pintar lo que escribió. El botón tiene <code>name="entrar"</code> para saber que se ha pulsado ESTE formulario.',
    "?>\n" . $formPost);
codigo("El PHP que lo procesa", 'Primero se mira el método y que se haya pulsado el botón; después, validaciones en orden. '
    . 'Con <code>||</code>, si el usuario no existe ya no se mira la clave (cortocircuito).', $bloquePost,
    'entra bien y pulsa F5: el navegador te preguntará si quieres reenviar el formulario. Eso es POST.');

// =====================================================================
seccion("4. Varios botones con el mismo name + switch");
// =====================================================================
?>
<div class="form-lab">
    <?php eval('?>' . $formBotones); ?>
    <?php if ($msgBotones != ""): ?>
        <div class="resultado"><?= $msgBotones ?></div>
    <?php endif; ?>
</div>
<?php
codigo("El HTML", 'Los tres botones se llaman <code>operacion</code>. El navegador solo manda el del botón pulsado.', "?>\n" . $formBotones);
codigo("El PHP", 'Un <code>switch</code> sobre ese único valor. Es lo mismo que el <code>$_POST[\'orden\']</code> de examII.', $bloqueBotones,
    'añade un botón "Multiplicar" y su <code>case</code>.');

// =====================================================================
seccion("5. Resumen: la estructura de siempre");
// =====================================================================
codigo("Página que se procesa a sí misma",
    'Arriba el PHP (decide qué hacer), abajo el HTML (enseña el resultado). Así son ControlDB2, el Ej2 y casi todos los ejercicios.',
<<<'PHP'
<?php
$msg = "";

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    // pedir / mostrar: filtros, ?id=..., enlaces
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // cambiar datos: login, alta, modificar, borrar
}
?>
<!DOCTYPE html>
<html>
<body>
    <form method="post"> ... </form>
    <?= $msg ?>
</body>
</html>
PHP);

pie();
