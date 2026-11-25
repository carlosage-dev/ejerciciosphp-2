<?php
require_once '_lab/lab.php';

/* =====================================================================
 *  FECHAS Y TIEMPO · time(), date(), strtotime(), mktime(), DateTime
 *  Va en la carpeta del laboratorio (usa _lab/lab.php para el diseño).
 * ===================================================================== */

// ---------- Mini calculadora con un formulario (input type="date") ----------
$bloqueForm = <<<'PHP'
$msgFecha = "";
$fechaElegida = "";

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['fecha'])) {
    $fechaElegida = $_POST['fecha'];          // un input type="date" envía "AAAA-MM-DD"

    $partes = explode("-", $fechaElegida);
    if (count($partes) != 3 || !checkdate((int) $partes[1], (int) $partes[2], (int) $partes[0])) {
        $msgFecha = "Elige una fecha válida";
    } else {
        $elegida = new DateTime($fechaElegida);
        $hoy = new DateTime("today");
        $dif = $hoy->diff($elegida);

        $msgFecha = "Has elegido el " . $elegida->format("d/m/Y") . ". ";
        if ($elegida < $hoy) {
            $msgFecha .= "Fue hace " . $dif->days . " días (" . $dif->y . " años, " . $dif->m . " meses y " . $dif->d . " días).";
        } elseif ($elegida > $hoy) {
            $msgFecha .= "Faltan " . $dif->days . " días.";
        } else {
            $msgFecha .= "¡Es hoy!";
        }
    }
}
PHP;
eval($bloqueForm);

cabecera("Fechas y tiempo", "Todo lo que necesitas para trabajar con fechas: la hora actual, darles formato, sumar o restar días, "
    . "calcular diferencias (antigüedad, edad) y caducidades de sesiones y cookies.");

seccion("Chuleta rápida");
chuleta([
    ['time()', 'Segundos desde el 1/1/1970 (el "timestamp"). Para caducidades: <code>time() + 3600</code>'],
    ['date("d/m/Y H:i:s")', 'Fecha y hora actuales con el formato que quieras'],
    ['date("Y-m-d")', 'Formato de MySQL (columnas DATE)'],
    ['date("d/m/Y", $timestamp)', 'Da formato a un timestamp concreto (no al de ahora)'],
    ['strtotime("2026-11-26")', 'Texto → timestamp. También entiende <code>"+1 day"</code>, <code>"tomorrow"</code>, <code>"next monday"</code>'],
    ['mktime(h, i, s, mes, día, año)', 'Crea un timestamp a partir de sus partes'],
    ['checkdate(mes, día, año)', '¿Existe esa fecha? (el 30/02 no)'],
    ['new DateTime("2015-03-01")', 'Objeto fecha. Sin parámetro = ahora'],
    ['$f->format("d/m/Y")', 'Da formato a un DateTime'],
    ['$f->modify("+1 month")', 'Suma o resta tiempo'],
    ['$a->diff($b)', 'Diferencia → <code>->y</code> años, <code>->m</code> meses, <code>->d</code> días, <code>->days</code> días totales'],
    ['$a < $b', 'Los DateTime se pueden comparar directamente'],
    ['DateTime::createFromFormat("d/m/Y", $t)', 'Lee una fecha escrita con un formato concreto'],
    ['date_default_timezone_set("Europe/Madrid")', 'Zona horaria (si la hora sale desfasada)'],
]);

// =====================================================================
seccion("1. La hora actual: time() y date()");
// =====================================================================

demo("time(): el timestamp",
    'Un número: los segundos que han pasado desde el 1 de enero de 1970. No sirve para enseñarlo, sino para <b>calcular</b> (sumar segundos, restar, comparar).',
<<<'PHP'
$ahora = time();
echo "Timestamp: " . $ahora . "\n";
echo "Dentro de 1 hora: " . ($ahora + 3600) . "\n";
echo "Dentro de 30 días: " . ($ahora + 30 * 24 * 3600);
PHP,
    'calcula el timestamp de dentro de 1 semana.');

demo("date(): fecha con formato",
    'Cada letra del formato se cambia por una parte de la fecha. Lo demás (<code>/ - : espacio</code>) se escribe tal cual.',
<<<'PHP'
echo date("d/m/Y") . "\n";            // 26/11/2026
echo date("H:i:s") . "\n";            // 18:45:03
echo date("d/m/Y H:i") . "\n";
echo date("Y-m-d") . "\n";            // formato MySQL
echo date("Y-m-d H:i:s") . "\n";      // formato MySQL DATETIME
echo date("j/n/y") . "\n";            // sin ceros y año con 2 cifras
PHP,
    'escribe la fecha como "2026.11.26 - 18h45".');

demo("Todas las letras de date()",
    'Las más usadas son <code>d m Y H i s</code>. Los nombres de días y meses salen en inglés (abajo verás cómo ponerlos en español).',
<<<'PHP'
$letras = [
    "d" => "día con 2 cifras (01-31)",
    "j" => "día sin cero (1-31)",
    "m" => "mes con 2 cifras (01-12)",
    "n" => "mes sin cero (1-12)",
    "Y" => "año con 4 cifras",
    "y" => "año con 2 cifras",
    "H" => "hora 00-23",
    "h" => "hora 01-12",
    "i" => "minutos",
    "s" => "segundos",
    "A" => "AM / PM",
    "D" => "día de la semana corto (inglés)",
    "l" => "día de la semana largo (inglés)",
    "N" => "día de la semana 1 (lunes) - 7 (domingo)",
    "w" => "día de la semana 0 (domingo) - 6 (sábado)",
    "M" => "mes corto (inglés)",
    "F" => "mes largo (inglés)",
    "t" => "días que tiene este mes",
    "z" => "día del año (empieza en 0)",
    "L" => "1 si el año es bisiesto",
];

echo "<table class='tabla-lab'><tr><th>Letra</th><th>Ahora vale</th><th>Qué es</th></tr>";
foreach ($letras as $letra => $explicacion) {
    echo "<tr><td><code>$letra</code></td><td><b>" . date($letra) . "</b></td><td>$explicacion</td></tr>";
}
echo "</table>";
PHP,
    'usa <code>t</code> para escribir "Este mes tiene 30 días".', true);

demo("Días y meses en español",
    '<code>date()</code> los da en inglés. Lo más fácil: un array con los nombres y usar el número (<code>w</code> o <code>n</code>) como posición.',
<<<'PHP'
$dias = ["domingo", "lunes", "martes", "miércoles", "jueves", "viernes", "sábado"];
$meses = ["", "enero", "febrero", "marzo", "abril", "mayo", "junio",
          "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre"];

$diaSemana = $dias[date("w")];       // w: 0 = domingo
$mes = $meses[date("n")];            // n: 1 = enero (por eso la posición 0 está vacía)

echo "Hoy es " . $diaSemana . ", " . date("j") . " de " . $mes . " de " . date("Y");
PHP,
    'escribe qué día de la semana será dentro de 3 días (pista: segundo parámetro de date).');

// =====================================================================
seccion("2. Otras fechas: strtotime() y mktime()", "date() con un segundo parámetro da formato a cualquier timestamp, no solo al de ahora.");
// =====================================================================

demo("strtotime(): texto → timestamp",
    'Entiende fechas en formato inglés/MySQL y expresiones como <code>"+1 day"</code>. Lo combinas con <code>date()</code> para mostrarlas.',
<<<'PHP'
echo date("d/m/Y", strtotime("2026-11-26")) . "  ← desde formato MySQL\n";
echo date("d/m/Y", strtotime("+1 day")) . "  ← mañana\n";
echo date("d/m/Y", strtotime("+1 week")) . "  ← dentro de una semana\n";
echo date("d/m/Y", strtotime("-1 month")) . "  ← hace un mes\n";
echo date("d/m/Y", strtotime("next monday")) . "  ← el próximo lunes\n";
echo date("d/m/Y", strtotime("last day of this month")) . "  ← último día del mes\n";
echo date("d/m/Y", strtotime("2026-11-26 +10 days")) . "  ← 10 días después de una fecha";
PHP,
    'saca la fecha de dentro de 2 semanas y 3 días.');

demo("mktime(): fecha a partir de sus partes",
    'El orden es raro: <b>hora, minuto, segundo, mes, día, año</b>.',
<<<'PHP'
$navidad = mktime(0, 0, 0, 12, 25, 2026);
echo "Navidad: " . date("d/m/Y", $navidad) . "\n";

$segundos = $navidad - time();
echo "Faltan " . floor($segundos / (24 * 3600)) . " días";
PHP,
    'calcula cuántos días faltan para tu cumpleaños.');

demo("checkdate(): ¿existe la fecha?",
    'Para validar una fecha que escribe el usuario. Orden: <b>mes, día, año</b>.',
<<<'PHP'
var_dump(checkdate(2, 28, 2026));   // 28 de febrero: sí
var_dump(checkdate(2, 30, 2026));   // 30 de febrero: no
var_dump(checkdate(2, 29, 2028));   // 2028 es bisiesto: sí
var_dump(checkdate(13, 1, 2026));   // mes 13: no
PHP,
    'comprueba si existe el 31 de abril.');

// =====================================================================
seccion("3. DateTime (orientado a objetos)", "Más cómodo para sumar, restar, comparar y calcular diferencias. Es un objeto: se usa con ->");
// =====================================================================

demo("Crear y dar formato",
    '<code>new DateTime()</code> sin nada es ahora. Con un texto, esa fecha. <code>->format()</code> usa las mismas letras que <code>date()</code>.',
<<<'PHP'
$ahora = new DateTime();
$alta = new DateTime("2015-03-01");          // como viene de MySQL

echo "Ahora: " . $ahora->format("d/m/Y H:i") . "\n";
echo "Alta: " . $alta->format("d/m/Y") . "\n";
echo "Año de alta: " . $alta->format("Y");
PHP,
    'crea el DateTime de tu fecha de nacimiento y enséñala como 01/01/2000.');

demo("Sumar y restar: modify()",
    'Cambia el propio objeto. Si quieres conservar el original, haz <code>clone</code> antes.',
<<<'PHP'
$fecha = new DateTime("2026-11-26");

$fecha->modify("+1 day");
echo $fecha->format("d/m/Y") . "  ← +1 día\n";

$fecha->modify("+1 month");
echo $fecha->format("d/m/Y") . "  ← +1 mes más\n";

$copia = clone $fecha;
$copia->modify("-1 year");
echo $copia->format("d/m/Y") . "  ← la copia, 1 año antes\n";
echo $fecha->format("d/m/Y") . "  ← el original no cambia";
PHP,
    'calcula la fecha de fin de un contrato de 6 meses que empieza hoy.');

demo("Diferencia entre fechas: diff()",
    '<code>diff()</code> devuelve un objeto con <code>y</code> (años), <code>m</code> (meses), <code>d</code> (días) y <code>days</code> (días totales). '
    . 'Es lo que usaba <code>antiguedad()</code> del Ej1.',
<<<'PHP'
$alta = new DateTime("2015-03-01");
$hoy = new DateTime();
$dif = $alta->diff($hoy);

echo "Antigüedad: " . $dif->y . " años, " . $dif->m . " meses y " . $dif->d . " días\n";
echo "En total: " . $dif->days . " días\n";

function antiguedad(string $fechaAlta): int
{
    $alta = new DateTime($fechaAlta);
    return $alta->diff(new DateTime())->y;
}
echo "antiguedad('2015-03-01') = " . antiguedad("2015-03-01");
PHP,
    'haz una función <code>edad($fechaNacimiento)</code> igual que <code>antiguedad()</code>.');

demo("Comparar fechas",
    'Dos DateTime se comparan con <code>&lt; &gt; ==</code>. Con textos en formato <code>Y-m-d</code> también funciona, porque se ordenan como texto.',
<<<'PHP'
$hoy = new DateTime("today");
$examen = new DateTime("2026-11-26");

if ($examen < $hoy) {
    echo "El examen ya pasó\n";
} elseif ($examen == $hoy) {
    echo "¡El examen es HOY!\n";
} else {
    echo "Faltan " . $hoy->diff($examen)->days . " días para el examen\n";
}

var_dump("2026-11-26" > "2026-03-01");      // texto Y-m-d: también vale
PHP,
    'cambia la fecha del examen a ayer y a hoy.');

demo("Convertir entre formatos (formulario ↔ MySQL)",
    'MySQL guarda <code>AAAA-MM-DD</code>, pero el usuario escribe <code>DD/MM/AAAA</code>. '
    . '<code>createFromFormat</code> lee una fecha con el formato que le digas.',
<<<'PHP'
// Del usuario a MySQL
$escrita = "26/11/2026";
$f = DateTime::createFromFormat("d/m/Y", $escrita);
echo "Para guardar en MySQL: " . $f->format("Y-m-d") . "\n";

// De MySQL al usuario
$deMysql = "2015-03-01";
echo "Para enseñar: " . date("d/m/Y", strtotime($deMysql)) . "\n";

// Sin DateTime, con explode
$p = explode("/", $escrita);                 // [26, 11, 2026]
echo "Con explode: " . $p[2] . "-" . $p[1] . "-" . $p[0];
PHP,
    'convierte "2026-01-05" en "5 de enero de 2026" con el array de meses.');

// =====================================================================
seccion("4. Caducidades: sesiones y cookies", "time() es lo que se usa para saber si algo ha caducado.");
// =====================================================================

demo("¿Ha caducado? (el patrón del PIN y de la sesión)",
    'Se guarda <code>time()</code> en un momento y luego se compara con la hora actual. Aquí lo simulamos con una hora de hace 7 minutos.',
<<<'PHP'
define('TIEMPO_SESION', 300);                // 5 minutos

$ultimaActividad = time() - 7 * 60;          // simulado: hace 7 minutos
$pasados = time() - $ultimaActividad;

echo "Han pasado " . $pasados . " segundos\n";
if ($pasados > TIEMPO_SESION) {
    echo "❌ Sesión caducada\n";
} else {
    echo "✅ Sigue activa. Le quedan " . (TIEMPO_SESION - $pasados) . " s\n";
}

// En un ejercicio sería:
// if (time() - $_SESSION['ultimo'] > TIEMPO_SESION) { accionTerminar(); }
// $_SESSION['ultimo'] = time();
PHP,
    'cambia los 7 minutos por 2 minutos.');

demo("Tiempos típicos de una cookie",
    'El tercer parámetro de <code>setcookie</code> es el momento en que caduca: <code>time()</code> + segundos.',
<<<'PHP'
$tiempos = [
    "1 hora"    => time() + 3600,
    "1 día"     => time() + 24 * 3600,
    "30 días"   => time() + 30 * 24 * 3600,
    "1 año"     => time() + 365 * 24 * 3600,
    "medianoche" => strtotime("tomorrow"),
    "BORRAR"    => time() - 3600,
];

foreach ($tiempos as $texto => $momento) {
    echo str_pad($texto, 12) . " → caduca el " . date("d/m/Y H:i", $momento) . "\n";
}
// setcookie("nombre", "valor", time() + 30 * 24 * 3600);
PHP,
    'añade "1 semana" a la lista.');

// =====================================================================
seccion("5. Pruébalo con un formulario", "Un <code>input type=\"date\"</code> envía la fecha en formato <code>AAAA-MM-DD</code>.");
// =====================================================================
?>
<div class="form-lab">
    <form method="post" action="11_fechas.php">
        Elige una fecha: <input type="date" name="fecha" value="<?= e($fechaElegida) ?>">
        <button>Calcular</button>
    </form>
    <?php if ($msgFecha != ""): ?>
        <div class="resultado <?= ($msgFecha == "Elige una fecha válida") ? 'mal' : '' ?>"><?= e($msgFecha) ?></div>
    <?php endif; ?>
</div>
<?php
codigo("El código que lo procesa", 'Se valida con <code>checkdate</code> y se calcula con <code>DateTime</code> y <code>diff</code>.', $bloqueForm,
    'añade que, si la fecha es de hace más de 18 años, diga "Mayor de edad".');

pie();
