<?php
require_once '_lab/lab.php';
cabecera("Estructuras de ejercicio y errores típicos", "Las 4 formas de montar un ejercicio que han salido en tu repo, con su esqueleto para copiar, "
    . "el orden de comprobaciones de siempre y la lista de fallos que hemos ido viendo estos días.");

// =====================================================================
seccion("1. Formulario aparte + action (ControlDB1, Ej1)", "Un HTML con el formulario envía los datos a otro archivo PHP, que decide qué vista enseñar.");
// =====================================================================
?>
<div class="dos-columnas">
<div class="arbol">ej1/
├── acceso.html        ← &lt;form action="consulta.php" method="post"&gt;
├── consulta.php       ← el controlador: valida y elige vista
├── AccesoDatos.php    ← PDO (Singleton)
├── Empleado.php       ← la clase
├── vistaerror.php     ← mensaje + meta refresh
└── vistaempleado.php  ← los datos y la tabla</div>
<div class="caja nota"><div class="caja-titulo">ℹ️ Cuándo</div>
<p>Login sencillo o consulta de una sola pantalla. El formulario no vuelve a aparecer: cada resultado es una vista distinta.</p></div>
</div>
<?php
codigo("consulta.php (esqueleto)", 'Cada comprobación que falla incluye la vista de error y corta con <code>exit()</code>.',
<<<'PHP'
<?php
require_once "AccesoDatos.php";

// 1. Solo por POST
if ($_SERVER['REQUEST_METHOD'] != "POST") {
    header("Location: acceso.html");
    exit();
}

// 2. Campos vacíos
if (empty($_POST['cod_emp']) || empty($_POST['clave'])) {
    $mensajeError = "Debe rellenar todos los campos";
    include 'vistaerror.php';
    exit();
}

// 3. Buscar en la BD
$db = AccesoDatos::initModelo();
$emp = $db->getEmpleadoLogin($_POST['cod_emp'], $_POST['clave']);
if (!$emp) {
    $mensajeError = "Código o clave incorrectos";
    include 'vistaerror.php';
    exit();
}

// 4. Cookies / cambios en BD / más datos...

// 5. La vista usa $emp y lo que hayas preparado
include 'vistaempleado.php';
PHP);

// =====================================================================
seccion("2. Una sola página que se llama a sí misma (ControlDB2, Ej2)", "Arriba el PHP, abajo el HTML. Los formularios no llevan action (o llevan el mismo archivo).");
// =====================================================================
?>
<div class="dos-columnas">
<div class="arbol">ej2/
├── subida.php         ← PHP arriba + HTML abajo
├── AccesoDatos.php
├── Empleado.php
└── Departamento.php</div>
<div class="caja nota"><div class="caja-titulo">ℹ️ Cuándo</div>
<p>Una pantalla con un filtro (GET) y una acción sobre la tabla (POST). Todo se ve en la misma página.</p></div>
</div>
<?php
codigo("subida.php (esqueleto)", 'Primero se procesa (modificar), <b>después</b> se cargan los datos: así la tabla ya sale actualizada.',
<<<'PHP'
<?php
require_once 'AccesoDatos.php';
$db = AccesoDatos::initModelo();
$msg = "";

// 1. Datos que llegan por GET (filtros)
$filtro = null;
if (isset($_GET['dep']) && $_GET['dep'] != "todos") {
    $filtro = (int) $_GET['dep'];
}

// 2. Acciones POST (modificar)
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['orden'])) {
    if (!isset($_POST['templeados'])) {
        $msg = "No has marcado ninguno";
    } else {
        // ... foreach y llamadas al modelo
    }
}

// 3. Cargar datos para la vista (SIEMPRE después de modificar)
$templeados = $db->getEmpleados($filtro);
?>
<!DOCTYPE html>
<html>
<body>
    <form method="get"> ...select... </form>
    <?php if ($msg != ""): ?><p><?= $msg ?></p><?php endif; ?>
    <form method="post">
        <table> <?php foreach ($templeados as $e): ?> ... <?php endforeach; ?> </table>
        <button name="orden" value="Subir">Subir</button>
    </form>
</body>
</html>
PHP);

// =====================================================================
seccion("3. index.php + switch + carpeta app/ (examII, BD_PDO_Tareas, Ej3)", "El más típico de examen. index.php es el único punto de entrada.");
// =====================================================================
?>
<div class="dos-columnas">
<div class="arbol">ej3/
├── index.php              ← controlador: acceso + switch GET + switch POST
├── app/
│   ├── config.php         ← define() de BD, PIN, tiempos
│   ├── AccesoDatos.php
│   ├── Empleado.php
│   ├── funciones.php      ← mostrarDatos() con $msg .=
│   ├── acciones.php       ← accionAlta(), accionPostAlta()...
│   └── layout/
│       ├── formulariopin.php
│       ├── formulario.php ← termina con exit()
│       └── principal.php  ← pinta $msg y $contenido
└── web/default.css</div>
<div class="caja nota"><div class="caja-titulo">ℹ️ Cuándo</div>
<p>CRUD completo: tabla con enlaces (Detalles, Modificar, Borrar), botones (Nuevo, Terminar), acciones con checkbox y control de acceso.</p></div>
</div>
<?php
codigo("index.php (esqueleto)", 'El orden de siempre: sesión → acceso → caducidad → GET → POST → tabla.',
<<<'PHP'
<?php
session_start();
include_once 'app/config.php';
include_once 'app/funciones.php';
include_once 'app/acciones.php';

$msg = "";
$contenido = "";

// 1. CONTROL DE ACCESO (PIN o login)
if (!isset($_SESSION['acceso'])) {
    if (isset($_POST['pin']) && $_POST['pin'] == PIN_ACCESO) {
        $_SESSION['acceso'] = true;
        $_SESSION['ultimo'] = time();
    } else {
        include 'app/layout/formulariopin.php';
        exit();
    }
}

// 2. CADUCIDAD
if (time() - $_SESSION['ultimo'] > TIEMPO_INACTIVIDAD) {
    accionTerminar();
}
$_SESSION['ultimo'] = time();

// 3. GET: enlaces y botones que MUESTRAN algo
if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isset($_GET['orden'])) {
        switch ($_GET['orden']) {
            case "Nuevo":     accionAlta(); break;
            case "Detalles":  accionDetalles($_GET['id']); break;
            case "Modificar": accionModificar($_GET['id']); break;
            case "Borrar":    accionBorrar($_GET['id']); break;
            case "Terminar":  accionTerminar(); break;
        }
    }
}
// 4. POST: lo que MODIFICA la BD (cada acción devuelve un mensaje)
else {
    if (isset($_POST['orden'])) {
        switch ($_POST['orden']) {
            case "Nuevo":     $msg = accionPostAlta(); break;
            case "Modificar": $msg = accionPostModificar(); break;
            case "Bloquear":  $msg = accionBloquear(); break;
        }
    }
}

// 5. La tabla
$contenido .= mostrarDatos();
include_once "app/layout/principal.php";
PHP);

codigo("accionTerminar()", 'Cierra todo y vuelve al principio.',
<<<'PHP'
function accionTerminar()
{
    AccesoDatos::closeModelo();
    session_destroy();
    header("Location: index.php");     // o header("Refresh:0; url=index.php");
    exit();
}
PHP);

// =====================================================================
seccion('4. $_REQUEST + ob_start + plantillas (foro_vo, Ej4)', "Una sola variable de orden para GET y POST, y la salida se guarda en un buffer.");
// =====================================================================
?>
<div class="dos-columnas">
<div class="arbol">ej4/
├── index.php            ← ob_start + switch ($_REQUEST['orden'])
└── app/
    ├── funciones.php
    ├── modelo/
    │   ├── AccesoDatos.php
    │   └── Empleado.php
    └── plantillas/
        ├── principal.php ← pinta $contenido_php
        ├── entrada.php
        └── panel.php</div>
<div class="caja nota"><div class="caja-titulo">ℹ️ Cuándo</div>
<p>Aplicación con login y varias pantallas según el rol. <code>ob_start()</code> permite poner cookies o redirigir aunque ya se haya hecho <code>echo</code>.</p></div>
</div>
<?php
codigo("index.php (esqueleto)", '<code>$_REQUEST</code> junta GET y POST. Cómodo, pero no distingue el método: valida los permisos en el servidor.',
<<<'PHP'
<?php
session_start();
include_once 'app/funciones.php';

ob_start();                      // todo lo que se pinte va a un buffer
$msg = "";

$orden = "";
if (isset($_REQUEST['orden'])) {
    $orden = $_REQUEST['orden'];
}

switch ($orden) {
    case "":
        if (isset($_SESSION['cod_emp'])) {
            include 'app/plantillas/panel.php';
        } else {
            include 'app/plantillas/entrada.php';
        }
        break;
    case "Entrar":
        // procesar login...
        break;
    case "Salir":
        session_destroy();
        include 'app/plantillas/entrada.php';
        break;
}

$contenido_php = ob_get_clean(); // recoge el buffer
include_once 'app/plantillas/principal.php';
PHP);

// =====================================================================
seccion("5. El orden de comprobaciones de siempre");
// =====================================================================
?>
<table class="tabla-lab">
    <tr><th style="width:40px">#</th><th>Qué</th><th>Cómo</th></tr>
    <tr><td>1</td><td><b>Arrancar</b></td><td><code>session_start()</code> / <code>require_once</code> de clases y modelo</td></tr>
    <tr><td>2</td><td><b>¿Puede entrar?</b></td><td>¿Hay sesión? ¿PIN/login correcto? ¿Intentos agotados? Si no → formulario de acceso + <code>exit()</code></td></tr>
    <tr><td>3</td><td><b>¿Ha caducado?</b></td><td><code>time() - $_SESSION['ultimo'] &gt; TIEMPO</code> → terminar. Si no, renovar la hora</td></tr>
    <tr><td>4</td><td><b>Procesar POST</b></td><td>Modificar la BD. Validar en orden: ¿llega? (<code>isset</code>) → ¿vacío? → ¿formato? (<code>is_numeric</code>) → ¿existe? → ¿tiene permiso?</td></tr>
    <tr><td>5</td><td><b>Procesar GET</b></td><td>Filtros, <code>?orden=...&amp;id=...</code>, mostrar formularios</td></tr>
    <tr><td>6</td><td><b>Cookies / header</b></td><td>Siempre <b>antes</b> del HTML. Después de <code>header("Location")</code>, <code>exit()</code></td></tr>
    <tr><td>7</td><td><b>Cargar datos</b></td><td>Las consultas para la vista, <b>después</b> de modificar</td></tr>
    <tr><td>8</td><td><b>Pintar</b></td><td>La vista / el HTML con la tabla y el mensaje</td></tr>
</table>
<?php
// =====================================================================
seccion("6. Errores típicos (los que hemos visto estos días)");
// =====================================================================
$errores = [
    ['Concatenar con +', '<code>"Hola " + $n</code>', 'En PHP se une con <code>.</code> — <code>"Hola " . $n</code>'],
    ['Llaves mal cerradas', 'el <code>else</code> se pega al <code>if</code> equivocado', 'Indenta bien; pon el <code>if/else</code> solo para lo que cambia y lo común fuera'],
    ['Usar algo sin comprobar que existe', '<code>$productos[$_GET[\'id\']]</code>', '<code>if (isset($productos[$id]))</code> antes de usarlo'],
    ['empty() con números', '<code>empty("0")</code> es true', 'Para números: <code>$x == ""</code>'],
    ['Convertir antes de validar', '<code>(int) "abc"</code> da 0', 'Primero <code>is_numeric()</code>, luego <code>(int)</code>'],
    ['=== con float', '<code>0.0 === 0</code> es false', 'Para comparar números: <code>==</code>'],
    ['array_search / strpos con ==', 'si está en la posición 0, <code>0 == false</code>', '<code>!== false</code>'],
    ['isset de más', '<code>isset($_GET[\'a\']) && isset($_GET[\'b\'])</code> y no sale el error si falta uno', 'Un <code>isset</code> para saber si se ha enviado; los demás datos se validan dentro'],
    ['Leer la cookie después de escribirla', 'esperar ver el valor nuevo en <code>$_COOKIE</code>', 'Primero leer, luego <code>setcookie</code>; el mensaje usa tu variable'],
    ['Borrar cookie sin redirigir', 'el resto del código la vuelve a crear', '<code>setcookie(..., time() - 3600); header(...); exit();</code>'],
    ['No volver a guardar en la sesión', '<code>$v = $_SESSION[\'n\'] + 1;</code> y ya', '<code>$_SESSION[\'n\'] = $_SESSION[\'n\'] + 1;</code>'],
    ['Inicializar la sesión en cada carga', '<code>$_SESSION[\'lista\'] = [];</code> sin if', '<code>if (!isset($_SESSION[\'lista\'])) { ... }</code>'],
    ['Usar $db dentro de una función', 'la variable de fuera no existe dentro', '<code>$db = AccesoDatos::initModelo();</code> dentro'],
    ['header sin exit', 'el código sigue ejecutándose', 'Siempre <code>exit();</code> después'],
    ['Redirigir al archivo equivocado', '<code>header("Location: 2a_...")</code> desde el 2b', 'Revisa el nombre del archivo'],
    ['$_POST[\'REQUEST_METHOD\']', 'no existe', '<code>$_SERVER[\'REQUEST_METHOD\']</code>'],
    ['foreach sin clave en un array de arrays', '<code>foreach ($productos as $id)</code> → <code>$id</code> es un array', '<code>foreach ($productos as $id => $producto)</code>'],
    ['Nombre de atributo mal escrito', '<code>$emp->cod_emple</code> → null sin avisar', 'Copia el nombre exacto de la clase'],
    ['Checkbox sin marcar', 'esperar que llegue <code>$_POST[\'tcod\']</code> vacío', 'No llega: <code>isset($_POST[\'tcod\'])</code>'],
    ['Campo disabled', 'esperar que se envíe', '<code>disabled</code> NO se envía; <code>readonly</code> sí'],
    ['sort en un asociativo', 'se pierden las claves', '<code>asort</code> / <code>arsort</code>'],
    ['setcookie / header después del HTML', '"headers already sent"', 'Todo eso va arriba del todo, antes de <code>?&gt;</code> y del <code>&lt;!DOCTYPE&gt;</code>'],
];
?>
<table class="tabla-lab">
    <tr><th>Error</th><th>Cómo se ve</th><th>La solución</th></tr>
    <?php foreach ($errores as $err): ?>
        <tr><td><b><?= $err[0] ?></b></td><td><?= $err[1] ?></td><td><?= $err[2] ?></td></tr>
    <?php endforeach; ?>
</table>
<?php
pie();
