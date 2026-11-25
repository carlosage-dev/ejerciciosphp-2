<?php
/* =====================================================================
 *  SESIONES · página interactiva
 *  session_start() SIEMPRE lo primero, antes de cualquier HTML.
 * ===================================================================== */
session_start();
require_once '_lab/lab.php';

define('PIN_LAB', '1234');
define('MAX_INTENTOS_LAB', 3);
define('TIEMPO_LAB', 60);          // segundos de inactividad

// ---------- Bloque 1: acciones por enlace (GET) ----------
$bloqueAcciones = <<<'PHP'
if (isset($_GET['accion'])) {
    switch ($_GET['accion']) {

        case "reiniciar":
            unset($_SESSION['lab_visitas']);      // borra UNA variable; la sesión sigue
            break;

        case "vaciar":
            $_SESSION['lab_lista'] = [];          // vaciar un array: asignar []
            break;

        case "quitar":
            if (isset($_GET['pos']) && isset($_SESSION['lab_lista'][$_GET['pos']])) {
                unset($_SESSION['lab_lista'][$_GET['pos']]);
            }
            break;

        case "salir_pin":
            unset($_SESSION['lab_pin_ok'], $_SESSION['lab_pin_ultimo']);
            break;

        case "destruir":
            session_destroy();                    // borra TODA la sesión del servidor
            header("Location: 03_sesiones.php");
            exit();
    }
}
PHP;

// ---------- Bloque 2: contador y hora de inicio ----------
$bloqueContador = <<<'PHP'
// Guardar es ASIGNAR: el valor está disponible al momento
if (isset($_SESSION['lab_visitas'])) {
    $_SESSION['lab_visitas'] = $_SESSION['lab_visitas'] + 1;
} else {
    $_SESSION['lab_visitas'] = 1;
}

// Solo la PRIMERA vez de la sesión
if (!isset($_SESSION['lab_inicio'])) {
    $_SESSION['lab_inicio'] = time();
}
$segundos = time() - $_SESSION['lab_inicio'];
PHP;

// ---------- Bloque 3: un array guardado en la sesión ----------
$bloqueLista = <<<'PHP'
// Si no existe, se crea vacía (¡solo si no existe!)
if (!isset($_SESSION['lab_lista'])) {
    $_SESSION['lab_lista'] = [];
}

$msgLista = "";
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['producto'])) {
    $producto = strtolower(trim($_POST['producto']));
    if ($producto == "") {
        $msgLista = "Escribe un producto";
    } elseif (in_array($producto, $_SESSION['lab_lista'])) {
        $msgLista = "Ya tienes " . htmlspecialchars($producto) . " en la lista";
    } else {
        $_SESSION['lab_lista'][] = $producto;
    }
}
PHP;

// ---------- Bloque 4: zona protegida con PIN, intentos e inactividad ----------
$bloquePin = <<<'PHP'
$msgPin = "";

// a) ¿Ha caducado por inactividad?
if (isset($_SESSION['lab_pin_ok']) && time() - $_SESSION['lab_pin_ultimo'] > TIEMPO_LAB) {
    unset($_SESSION['lab_pin_ok'], $_SESSION['lab_pin_ultimo']);
    $msgPin = "⏰ Has estado más de " . TIEMPO_LAB . " s sin hacer nada. Vuelve a meter el PIN.";
}
// b) Si sigue dentro, se renueva la hora (cada petición cuenta como actividad)
if (isset($_SESSION['lab_pin_ok'])) {
    $_SESSION['lab_pin_ultimo'] = time();
}

// c) Procesar el PIN (POST)
if (!isset($_SESSION['lab_intentos'])) {
    $_SESSION['lab_intentos'] = 0;
}
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['pin'])) {
    if ($_SESSION['lab_intentos'] >= MAX_INTENTOS_LAB) {
        $msgPin = "Bloqueado: demasiados intentos";
    } elseif ($_POST['pin'] == PIN_LAB) {
        $_SESSION['lab_pin_ok'] = true;
        $_SESSION['lab_pin_ultimo'] = time();
        $_SESSION['lab_intentos'] = 0;
        $msgPin = "✅ PIN correcto";
    } else {
        $_SESSION['lab_intentos']++;
        $quedan = MAX_INTENTOS_LAB - $_SESSION['lab_intentos'];
        if ($quedan == 0) {
            $msgPin = "PIN incorrecto. Has gastado todos los intentos";
        } else {
            $msgPin = "PIN incorrecto. Te quedan " . $quedan . " intentos";
        }
    }
}
$bloqueadoPin = $_SESSION['lab_intentos'] >= MAX_INTENTOS_LAB;
PHP;

// Botón del laboratorio para poder volver a probar (no es parte de la teoría)
if (isset($_GET['accion']) && $_GET['accion'] == 'reset_intentos') {
    $_SESSION['lab_intentos'] = 0;
}

eval($bloqueAcciones);
eval($bloqueContador);
eval($bloqueLista);
eval($bloquePin);

cabecera("Sesiones", "Una sesión es memoria en el <b>servidor</b> para cada usuario. El navegador solo guarda un \"ticket\" "
    . "(la cookie <code>PHPSESSID</code>) y con él PHP recupera tus datos en cada página.");
?>

<div class="vivo">
    <h3>🟢 En vivo: lo que hay guardado en tu sesión ($_SESSION)</h3>
    <div class="rejilla">
        <?= volcado('$_SESSION', $_SESSION) ?>
        <div>
            <div class="volcado-titulo">Tu ticket: session_id() (es el valor de la cookie PHPSESSID)</div>
            <pre class="volcado"><?= e(session_id()) ?></pre>
            <p style="font-size:14px;color:var(--muted);margin:8px 0 0">Los datos de la izquierda están en el servidor.
                El navegador solo tiene este código. Por eso el usuario <b>no puede</b> cambiar <code>$_SESSION</code> con F12.</p>
            <p style="margin:8px 0 0"><a class="boton rojo" href="?accion=destruir">💥 session_destroy()</a></p>
        </div>
    </div>
</div>

<?php
seccion("Chuleta rápida");
chuleta([
    ['session_start();', 'La <b>primera línea</b> de cada página que use la sesión (antes de cualquier HTML).'],
    ['$_SESSION["usuario"] = "ana01";', 'Guardar. Está disponible <b>al momento</b> (no como las cookies).'],
    ['isset($_SESSION["usuario"])', 'Leer siempre comprobando que existe. Así se sabe si alguien ha hecho login.'],
    ['$_SESSION["lista"][] = "pan";', 'Puede guardar <b>arrays</b> (y objetos).'],
    ['$_SESSION["n"]++;', 'Modificar = volver a asignar. Si calculas en otra variable, ¡vuelve a guardarla en $_SESSION!'],
    ['unset($_SESSION["intentos"]);', 'Borra una variable. La sesión sigue.'],
    ['session_destroy();', 'Borra toda la sesión del servidor. Después, redirigir.'],
    ['time() - $_SESSION["ultimo"] > 300', 'Patrón de caducidad: ¿han pasado más de 5 min?'],
]);

caja('nota', 'Cookie o sesión',
    '<p><b>Cookie</b>: en el navegador, el usuario la ve y la cambia, dura lo que digas (días, meses). Para preferencias o "recordar usuario". '
    . '<b>Sesión</b>: en el servidor, segura, dura hasta cerrar el navegador o <code>session_destroy()</code>. Para login, intentos, carrito, PIN.</p>');

// =====================================================================
seccion("1. Contador de la sesión", "Recarga varias veces. Luego cierra el navegador ENTERO y vuelve: empieza de cero (en las cookies no pasaba).");
// =====================================================================
?>
<div class="form-lab">
    <p style="margin:0;font-size:18px">Visitas en esta sesión: <b><?= $_SESSION['lab_visitas'] ?></b></p>
    <p style="margin:2px 0 8px">Llevas <b><?= $segundos ?></b> segundos en la sesión.</p>
    <a class="boton gris" href="?accion=reiniciar">↺ unset del contador</a>
    <a class="boton rojo" href="?accion=destruir">💥 Destruir la sesión</a>
</div>
<?php
codigo("El código", 'Con sesiones no hay <code>setcookie</code>: se guarda <b>asignando</b>. La hora de inicio va en su propio <code>if</code> para que el '
    . '<code>unset</code> del contador no la pise.', $bloqueContador,
    'pulsa "unset del contador": el contador vuelve a 1 pero los segundos siguen. Ahora "Destruir": todo a cero.');
codigo("Las acciones de los enlaces", 'Un <code>switch</code> con lo que llega en <code>?accion=</code>.', $bloqueAcciones);

// =====================================================================
seccion("2. Guardar un array en la sesión (lista de la compra)");
// =====================================================================
?>
<div class="form-lab">
    <form method="post" action="03_sesiones.php">
        Producto: <input type="text" name="producto">
        <button>Añadir</button>
        <a class="boton gris" href="?accion=vaciar">Vaciar</a>
    </form>
    <?php if ($msgLista != ""): ?>
        <div class="resultado mal"><?= $msgLista ?></div>
    <?php endif; ?>
    <p style="margin:8px 0 4px">Tienes <b><?= count($_SESSION['lab_lista']) ?></b> productos:</p>
    <ul style="margin:0">
        <?php foreach ($_SESSION['lab_lista'] as $pos => $prod): ?>
            <li><?= e($prod) ?> <a href="?accion=quitar&amp;pos=<?= $pos ?>">[quitar]</a></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php
codigo("El código", 'El <code>if (!isset(...))</code> es clave: si pones <code>$_SESSION[\'lab_lista\'] = [];</code> sin el <code>if</code>, '
    . 'la lista se vacía en cada carga (te pasó en el 3b).', $bloqueLista,
    'añade "pan", luego "  PAN  ": no se repite gracias a <code>strtolower(trim())</code>.');

// =====================================================================
seccion("3. Zona protegida: PIN + intentos + caducidad por inactividad", "PIN: 1234. Máximo 3 fallos. Si pasas 60 s sin recargar la página, te echa.");
// =====================================================================
?>
<div class="form-lab">
    <?php if ($msgPin != ""): ?>
        <div class="resultado <?= (strpos($msgPin, '✅') === false) ? 'mal' : '' ?>" style="margin:0 0 10px"><?= $msgPin ?></div>
    <?php endif; ?>

    <?php if (isset($_SESSION['lab_pin_ok'])): ?>
        <p style="margin:0">🔓 <b>Estás dentro.</b> La sesión caduca en
            <b><?= TIEMPO_LAB - (time() - $_SESSION['lab_pin_ultimo']) ?></b> s si no haces nada.</p>
        <a class="boton gris" href="03_sesiones.php">🔄 Recargar (cuenta como actividad)</a>
        <a class="boton rojo" href="?accion=salir_pin">Salir</a>
    <?php elseif ($bloqueadoPin): ?>
        <p style="margin:0">🔒 Formulario bloqueado por demasiados intentos.</p>
        <a class="boton gris" href="?accion=reset_intentos">(solo laboratorio) Reiniciar intentos</a>
    <?php else: ?>
        <form method="post" action="03_sesiones.php">
            PIN: <input type="password" name="pin" size="6">
            <button>Entrar</button>
            <span style="color:var(--muted)">Intentos fallidos: <?= $_SESSION['lab_intentos'] ?> / <?= MAX_INTENTOS_LAB ?></span>
        </form>
    <?php endif; ?>
</div>
<?php
codigo("El código", 'El orden importa: primero se mira si ha caducado, luego se renueva la hora y por último se procesa el PIN. '
    . 'Si solo guardaras la hora al entrar (como en examII), te echaría a los 60 s aunque estuvieras usando la página.', $bloquePin,
    'cambia <code>TIEMPO_LAB</code> a 10, entra y espera 11 segundos antes de recargar.');

// =====================================================================
seccion("4. Proteger otra página");
// =====================================================================
codigo("Lo primero de una página privada",
    'Si no hay sesión iniciada, fuera. Es lo que hace <code>panel.php</code> o el <code>index.php</code> de examII.',
<<<'PHP'
<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

// Salir
if (isset($_GET['salir'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}
PHP);

pie();
