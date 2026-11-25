<?php
require_once '_lab/lab.php';

/* =====================================================================
 *  COOKIES · página interactiva
 *  Todo lo que usa setcookie() o header() va AQUÍ ARRIBA, antes del HTML.
 * ===================================================================== */

// ---------- Bloque 1: acciones por enlace (GET) ----------
$bloqueAcciones = <<<'PHP'
$msgAccion = "";

if (isset($_GET['accion'])) {
    switch ($_GET['accion']) {

        case "crear":
            // Crear una cookie que dura 1 hora
            setcookie("lab_saludo", "Hola desde una cookie", time() + 3600);
            if (isset($_COOKIE['lab_saludo'])) {
                $msgAccion = "La cookie ya existía, se ha vuelto a guardar (1 hora más).";
            } else {
                $msgAccion = "setcookie() ejecutado... pero \$_COOKIE todavía NO la tiene. "
                           . "Llegará en la próxima petición: recarga la página (F5).";
            }
            break;

        case "borrar":
            // Borrar = guardarla con una fecha que ya pasó
            setcookie("lab_saludo", "", time() - 3600);
            header("Location: 02_cookies.php");   // recarga limpia
            exit();

        case "reiniciar":
            setcookie("lab_visitas", "", time() - 3600);
            header("Location: 02_cookies.php");
            exit();
    }
}
PHP;

// ---------- Bloque 2: contador de visitas (en cada carga) ----------
$bloqueContador = <<<'PHP'
// 1º LEER lo que trae el navegador
if (isset($_COOKIE['lab_visitas'])) {
    $visitas = $_COOKIE['lab_visitas'] + 1;
} else {
    $visitas = 1;
}
// 2º ESCRIBIR el valor nuevo (crear y actualizar es lo mismo)
setcookie("lab_visitas", $visitas, time() + 365 * 24 * 3600);   // 1 año
PHP;

// ---------- Bloque 3: preferencia elegida en un formulario (POST) ----------
$bloqueColor = <<<'PHP'
$colores = ["azul" => "#4f46e5", "verde" => "#15803d", "rosa" => "#db2777"];

// Guardar (POST) y recargar
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['color'])) {
    if (isset($colores[$_POST['color']])) {             // lista blanca
        setcookie("lab_color", $_POST['color'], time() + 30 * 24 * 3600);
    }
    header("Location: 02_cookies.php");
    exit();
}

// Leer (en cada carga). Valor por defecto si no hay cookie.
$color = "azul";
if (isset($_COOKIE['lab_color']) && isset($colores[$_COOKIE['lab_color']])) {
    $color = $_COOKIE['lab_color'];                      // ¡se valida también al leer!
}
PHP;

// ---------- Bloque 4: guardar una LISTA en una cookie ----------
$bloqueLista = <<<'PHP'
$productos = ["Teclado", "Ratón", "Monitor", "Cascos"];

// Leer: texto -> array
$vistos = [];
if (isset($_COOKIE['lab_vistos'])) {
    $vistos = explode(",", $_COOKIE['lab_vistos']);
}

// Si se pincha un producto: ponerlo el primero, sin repetir, máximo 3
if (isset($_GET['ver']) && in_array($_GET['ver'], $productos)) {
    $pos = array_search($_GET['ver'], $vistos);
    if ($pos !== false) {
        unset($vistos[$pos]);
    }
    array_unshift($vistos, $_GET['ver']);
    $vistos = array_slice($vistos, 0, 3);

    // Guardar: array -> texto
    setcookie("lab_vistos", implode(",", $vistos), time() + 7 * 24 * 3600);
}
PHP;

eval($bloqueAcciones);
eval($bloqueContador);
eval($bloqueColor);
eval($bloqueLista);

cabecera("Cookies", "Una cookie es un texto pequeño que la web guarda en <b>tu navegador</b>. "
    . "En cada petición el navegador la devuelve, y así la web te \"recuerda\". Pulsa los botones y mira el panel verde.");

// Cookies del laboratorio para el panel
$cookiesLab = [];
foreach ($_COOKIE as $nombre => $valor) {
    if (str_starts_with($nombre, 'lab_') || $nombre == 'PHPSESSID') {
        $cookiesLab[$nombre] = $valor;
    }
}
?>

<div class="vivo">
    <h3>🟢 En vivo: las cookies que ha mandado tu navegador en ESTA petición ($_COOKIE)</h3>
    <?php if (count($cookiesLab) == 0): ?>
        <p>Ninguna todavía (recarga una vez y aparecerá <code>lab_visitas</code>).</p>
    <?php else: ?>
        <table class="tabla-lab">
            <tr><th>Nombre (clave)</th><th>Valor</th></tr>
            <?php foreach ($cookiesLab as $nombre => $valor): ?>
                <tr><td><code><?= e($nombre) ?></code></td><td><?= e($valor) ?></td></tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    <p style="margin:8px 0 0;font-size:14px;color:var(--muted)">También las ves en <b>F12 → Aplicación → Cookies → localhost</b> (ahí puedes borrarlas o cambiarlas a mano).</p>
</div>

<?php
seccion("Chuleta rápida");
chuleta([
    ['setcookie("nombre", "valor", time() + 3600);', 'Crea o <b>actualiza</b> la cookie. Dura 1 hora. Va <b>antes de cualquier HTML</b>.'],
    ['time() + 30 * 24 * 3600', '30 días (segundos). Sin tiempo, la cookie muere al cerrar el navegador.'],
    ['$_COOKIE["nombre"]', 'Leer. Es un array como <code>$_GET</code>. Siempre con <code>isset</code>.'],
    ['setcookie("nombre", "", time() - 3600);', 'Borrar: fecha en el pasado.'],
    ['header("Location: ..."); exit();', 'Después de crear/borrar, recargar para ver el cambio.'],
    ['implode / explode', 'Una cookie solo guarda <b>texto</b>: una lista se guarda unida con comas.'],
]);

caja('aviso', 'La trampa nº 1 de las cookies',
    '<p><code>setcookie()</code> <b>no cambia <code>$_COOKIE</code> en la misma petición</b>: solo le dice al navegador que la guarde. '
    . 'El valor nuevo llega en la <b>siguiente</b> petición. Por eso: <b>primero se lee, luego se escribe</b>, y en el mensaje usas tu variable, no <code>$_COOKIE</code>.</p>');

// =====================================================================
seccion("1. Crear, leer y borrar", "Pulsa \"Crear\" y fíjate en el mensaje. Luego recarga (F5).");
// =====================================================================
?>
<div class="form-lab">
    <a class="boton" href="?accion=crear">🍪 Crear cookie lab_saludo</a>
    <a class="boton rojo" href="?accion=borrar">🗑 Borrar lab_saludo</a>
    <?php if ($msgAccion != ""): ?>
        <div class="resultado"><?= $msgAccion ?></div>
    <?php endif; ?>
    <div class="resultado" style="background:#fff;border-color:var(--border)">
        Leyendo ahora: <code>$_COOKIE['lab_saludo']</code> =
        <b><?= isset($_COOKIE['lab_saludo']) ? e($_COOKIE['lab_saludo']) : 'no existe' ?></b>
    </div>
</div>
<?php
codigo("El código (está al principio del archivo)",
    'Un <code>switch</code> con la acción que llega en el enlace. Al borrar se redirige: si no, el resto del código podría volver a crearla.',
    $bloqueAcciones,
    'cambia el tiempo de "crear" a <code>time() + 10</code>, créala y espera 10 segundos: desaparece sola.');

// =====================================================================
seccion("2. Contador de visitas", "Este código se ejecuta en cada carga de la página. Cierra el navegador y vuelve: la cuenta sigue (dura 1 año).");
// =====================================================================
?>
<div class="form-lab">
    <p style="margin:0 0 6px;font-size:18px">Has visitado esta página <b><?= $visitas ?></b> <?= ($visitas == 1) ? 'vez' : 'veces' ?>.</p>
    <a class="boton gris" href="?accion=reiniciar">↺ Reiniciar contador</a>
</div>
<?php
codigo("El código", 'Leer → sumar → guardar. En la primera visita no existe, así que se empieza en 1.', $bloqueContador,
    'cambia el contador para que cuente de 2 en 2.');

// =====================================================================
seccion("3. Recordar una preferencia (formulario POST)");
// =====================================================================
?>
<div class="form-lab" style="border:3px solid <?= $colores[$color] ?>">
    <form method="post" action="02_cookies.php">
        Color favorito:
        <?php foreach ($colores as $nombreColor => $hex): ?>
            <label><input type="radio" name="color" value="<?= $nombreColor ?>" <?= ($nombreColor == $color) ? 'checked' : '' ?>> <?= $nombreColor ?></label>
        <?php endforeach; ?>
        <button>Guardar</button>
    </form>
    <p style="margin:6px 0 0">El borde de esta caja usa el color guardado en la cookie <code>lab_color</code>: <b style="color:<?= $colores[$color] ?>"><?= $color ?></b>.</p>
</div>
<?php
codigo("El código", 'Al guardar (POST) se escribe la cookie y se <b>redirige</b>: en la carga siguiente <code>$_COOKIE</code> ya trae el color nuevo. '
    . 'El valor se valida también al leer, porque el usuario puede cambiar una cookie con F12.', $bloqueColor,
    'cambia la cookie <code>lab_color</code> a mano en F12 por "amarillo" y recarga: no se rompe gracias a la validación.');

// =====================================================================
seccion("4. Guardar una lista (explode / implode)", "Pincha productos: se guardan los 3 últimos, sin repetir.");
// =====================================================================
?>
<div class="form-lab">
    <?php foreach ($productos as $p): ?>
        <a class="boton gris" href="?ver=<?= urlencode($p) ?>">👁 <?= $p ?></a>
    <?php endforeach; ?>
    <div class="resultado">Vistos recientemente: <b><?= count($vistos) > 0 ? e(implode(", ", $vistos)) : '(ninguno)' ?></b></div>
</div>
<?php
codigo("El código", 'La lista se lee con <code>explode</code> y se guarda con <code>implode</code>. Lo que se enseña es <code>$vistos</code> (ya actualizado), no <code>$_COOKIE</code>.', $bloqueLista,
    'cambia el máximo de 3 a 5 productos.');

// =====================================================================
seccion("5. Cookie con nombre distinto para cada usuario");
// =====================================================================
codigo("El patrón del Ej1 (último acceso por empleado)",
    'El nombre de la cookie se construye con el código del usuario. Así cada uno tiene la suya en el mismo navegador.',
<<<'PHP'
$nombreCookie = "ultimoacceso_" . $emp->cod_emp;      // ultimoacceso_ana01

if (isset($_COOKIE[$nombreCookie])) {
    $msgCookie = "Tu último acceso fue el " . $_COOKIE[$nombreCookie];
} else {
    $msgCookie = "Es tu primer acceso desde este navegador";
}
setcookie($nombreCookie, date("d/m/Y H:i:s"), time() + 30 * 24 * 3600);
PHP);

pie();
