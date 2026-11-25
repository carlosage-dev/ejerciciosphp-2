<?php
require_once '_lab/lab.php';

/* =====================================================================
 *  PINTAR TABLAS · todas las formas que han salido en clase
 * ===================================================================== */

// Datos de ejemplo: en un ejercicio real saldrían de PDO con fetchAll(FETCH_CLASS)
$codigoDatos = <<<'PHP'
class Empleado
{
    private $cod_emp;
    private $nombre;
    private $puesto;
    private $sueldo;
    private $cod_dep;
    private $bloqueado;

    public function __get($atributo)
    {
        if (property_exists($this, $atributo)) {
            return $this->$atributo;
        }
    }

    public function __set($atributo, $valor)
    {
        if (property_exists($this, $atributo)) {
            $this->$atributo = $valor;
        }
    }
}

// Hace de "base de datos": devuelve un array de objetos Empleado
function datosEmpleados(): array
{
    $filas = [
        ["ana01",   "Ana López",    "Jefe",        3200, 1, 0],
        ["luis02",  "Luis García",  "Programador", 2100, 1, 0],
        ["sara05",  "Sara Gil",     "Analista",    2600, 1, 1],
        ["jorge06", "Jorge Martín", "Jefe",        3000, 2, 0],
        ["david08", "David Romero", "Becario",      980, 2, 0],
    ];
    $empleados = [];
    foreach ($filas as $f) {
        $e = new Empleado();
        $e->cod_emp = $f[0];
        $e->nombre = $f[1];
        $e->puesto = $f[2];
        $e->sueldo = $f[3];
        $e->cod_dep = $f[4];
        $e->bloqueado = $f[5];
        $empleados[] = $e;
    }
    return $empleados;
}

// Mapa cod_dep => nombre (en un ejercicio se monta con un foreach sobre los departamentos)
function datosDepartamentos(): array
{
    return [1 => "Desarrollo", 2 => "Sistemas"];
}
PHP;
eval($codigoDatos);

// ---------- Lo que pasa al enviar la tabla con checkbox (POST) ----------
$bloqueCheckbox = <<<'PHP'
$msgCheck = "";

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['accion'])) {
    if (!isset($_POST['tcod'])) {                  // ningún checkbox marcado
        $msgCheck = "No has marcado ningún empleado";
    } else {
        switch ($_POST['accion']) {
            case "Bloquear":
                // En un ejercicio: foreach ($_POST['tcod'] as $cod) { $db->cambiarBloqueo($cod, 1); }
                $msgCheck = "Bloquearía a: " . implode(", ", $_POST['tcod']);
                break;
            case "Desbloquear":
                $msgCheck = "Desbloquearía a: " . implode(", ", $_POST['tcod']);
                break;
        }
    }
}
PHP;

// ---------- Lo que pasa al pinchar un enlace de la tabla (GET) ----------
$bloqueDetalle = <<<'PHP'
$detalle = null;

if (isset($_GET['orden']) && $_GET['orden'] == "Detalles" && isset($_GET['id'])) {
    // En un ejercicio: $detalle = $db->getEmpleado($_GET['id']);
    foreach (datosEmpleados() as $emp) {
        if ($emp->cod_emp == $_GET['id']) {
            $detalle = $emp;
        }
    }
}
PHP;

eval($bloqueCheckbox);
eval($bloqueDetalle);

cabecera("Pintar tablas", "El profesor insistió en esto: hay que saber pintar una tabla de varias formas. "
    . "Todas usan los mismos datos: un array de objetos <code>Empleado</code>, igual que el que te devuelve PDO.");

seccion("0. Los datos");
codigo("Clase Empleado y datos de ejemplo",
    'Aquí los objetos se crean a mano; en un ejercicio vienen de <code>$stmt->fetchAll()</code> con <code>FETCH_CLASS</code>. '
    . 'La forma de pintarlos es <b>exactamente la misma</b>.',
    $codigoDatos);

// =====================================================================
seccion("1. Forma A: foreach dentro del HTML", 'Como en ControlDB2 y el Ej1/Ej2. Se sale de PHP con <code>?&gt;</code> y se va entrando con <code>&lt;?= ?&gt;</code>.');
// =====================================================================

demo("foreach: ... endforeach; con fila de total",
    'La <b>sintaxis alternativa</b> (<code>foreach (...):</code> / <code>endforeach;</code>) se lee mejor mezclada con HTML. '
    . 'La clase de la fila se elige con un ternario y el total se va acumulando dentro del bucle.',
<<<'PHP'
$empleados = datosEmpleados();
$nombresDep = datosDepartamentos();
$total = 0;
?>
<table class="tabla-lab">
    <tr><th>Código</th><th>Nombre</th><th>Puesto</th><th>Departamento</th><th>Sueldo</th></tr>
    <?php foreach ($empleados as $emp): ?>
        <?php $total += $emp->sueldo; ?>
        <tr class="<?= ($emp->bloqueado == 1) ? 'bloqueado' : '' ?>">
            <td><?= $emp->cod_emp ?></td>
            <td><?= $emp->nombre ?></td>
            <td><?= $emp->puesto ?></td>
            <td><?= $nombresDep[$emp->cod_dep] ?></td>
            <td><?= number_format($emp->sueldo, 2, ',', '.') ?> €</td>
        </tr>
    <?php endforeach; ?>
    <tr class="total">
        <td colspan="4">TOTAL</td>
        <td><?= number_format($total, 2, ',', '.') ?> €</td>
    </tr>
</table>
PHP,
    'añade una columna "Anual" con el sueldo × 14 y una fila de MEDIA debajo del total.', true);

demo("Tabla vacía: mensaje en vez de filas",
    'Si el array está vacío, el <code>foreach</code> no pinta nada. Lo correcto es avisar con una fila que ocupe todas las columnas (<code>colspan</code>).',
<<<'PHP'
$empleados = [];      // imagina un departamento sin empleados
?>
<table class="tabla-lab">
    <tr><th>Código</th><th>Nombre</th><th>Sueldo</th></tr>
    <?php if (count($empleados) == 0): ?>
        <tr><td colspan="3">No hay empleados en este departamento</td></tr>
    <?php else: ?>
        <?php foreach ($empleados as $emp): ?>
            <tr><td><?= $emp->cod_emp ?></td><td><?= $emp->nombre ?></td><td><?= $emp->sueldo ?></td></tr>
        <?php endforeach; ?>
    <?php endif; ?>
</table>
PHP,
    'cambia <code>[]</code> por <code>datosEmpleados()</code>.', true);

// =====================================================================
seccion('2. Forma B: una función que monta la tabla con $msg .=', 'Como mostrarDatos() de examII. La función devuelve un string y se pinta con echo.');
// =====================================================================

demo('Función con $msg .=',
    'Todo el HTML se va añadiendo a un string. Cuidado con las comillas: fuera, dobles <code>"</code>; dentro, en los atributos, simples <code>\'</code>.',
<<<'PHP'
function tablaEmpleados(array $empleados): string
{
    $msg = "<table class='tabla-lab'>";
    $msg .= "<tr><th>Código</th><th>Nombre</th><th>Sueldo</th><th>Estado</th></tr>";

    foreach ($empleados as $emp) {
        $estado = "Activo";
        $clase = "";
        if ($emp->bloqueado == 1) {
            $estado = "🔒 Bloqueado";
            $clase = "bloqueado";
        }
        $msg .= "<tr class='" . $clase . "'>";
        $msg .= "<td>" . $emp->cod_emp . "</td>";
        $msg .= "<td>" . $emp->nombre . "</td>";
        $msg .= "<td>" . number_format($emp->sueldo, 2, ',', '.') . " €</td>";
        $msg .= "<td>" . $estado . "</td>";
        $msg .= "</tr>";
    }
    $msg .= "</table>";
    return $msg;
}

echo tablaEmpleados(datosEmpleados());
PHP,
    'añade a la función un segundo parámetro <code>string $puesto</code> y pinta solo los de ese puesto.', true);

// =====================================================================
seccion('3. Forma C: función genérica con $obj->$campo', "Como pintarTabla() del Ej4. Le dices qué columnas quieres y sirve para cualquier clase.");
// =====================================================================

demo('pintarTabla($objetos, $columnas)',
    '<code>$columnas</code> es un array <code>atributo => título</code>. Dentro, <code>$obj->$campo</code> lee el atributo cuyo nombre está en <code>$campo</code>.',
<<<'PHP'
function pintarTabla(array $objetos, array $columnas): string
{
    $html = "<table class='tabla-lab'><tr>";
    foreach ($columnas as $campo => $titulo) {
        $html .= "<th>" . $titulo . "</th>";
    }
    $html .= "</tr>";

    foreach ($objetos as $obj) {
        $html .= "<tr>";
        foreach ($columnas as $campo => $titulo) {
            $html .= "<td>" . $obj->$campo . "</td>";     // propiedad variable
        }
        $html .= "</tr>";
    }
    return $html . "</table>";
}

$empleados = datosEmpleados();
echo pintarTabla($empleados, ["cod_emp" => "Código", "nombre" => "Nombre"]);
echo "<br>";
echo pintarTabla($empleados, ["nombre" => "Nombre", "puesto" => "Puesto", "sueldo" => "Sueldo"]);
PHP,
    'pinta solo el primer empleado: <code>pintarTabla([$empleados[0]], [...])</code> (un array con un solo objeto).', true);

// =====================================================================
seccion("4. Tabla con checkbox y botones (POST)", "Marca empleados y pulsa un botón. Los bloqueados no tienen checkbox.");
// =====================================================================

demo("Checkbox name=\"tcod[]\" + dos botones",
    'Cada fila tiene <code>&lt;input type="checkbox" name="tcod[]" value="CÓDIGO"&gt;</code>. Al enviar, llega un array con los códigos marcados. '
    . 'Los dos botones se llaman <code>accion</code>.',
<<<'PHP'
$empleados = datosEmpleados();
?>
<form method="post" action="08_tablas.php">
<table class="tabla-lab">
    <tr><th></th><th>Código</th><th>Nombre</th><th>Estado</th></tr>
    <?php foreach ($empleados as $emp): ?>
        <tr>
            <td>
                <?php if ($emp->bloqueado != 1): ?>
                    <input type="checkbox" name="tcod[]" value="<?= $emp->cod_emp ?>">
                <?php endif; ?>
            </td>
            <td><?= $emp->cod_emp ?></td>
            <td><?= $emp->nombre ?></td>
            <td><?= ($emp->bloqueado == 1) ? "🔒" : "Activo" ?></td>
        </tr>
    <?php endforeach; ?>
</table>
<button class="boton" name="accion" value="Bloquear">🔒 Bloquear</button>
<button class="boton gris" name="accion" value="Desbloquear">🔓 Desbloquear</button>
</form>
PHP,
    'cambia el <code>if</code> para que en vez de no pintar el checkbox lo pinte con <code>disabled</code>.', true);

if ($msgCheck != "") {
    echo '<div class="resultado' . (str_starts_with($msgCheck, 'No ') ? ' mal' : '') . '">' . e($msgCheck) . '</div>';
    echo '<div style="margin:8px 0 18px">' . volcado('Lo que ha llegado: $_POST', $_POST) . '</div>';
}
codigo("El PHP que lo recibe (al principio del archivo)", 'Si no marcas ninguno, <code>$_POST[\'tcod\']</code> <b>no existe</b>: primero <code>isset</code>, luego el <code>switch</code>.',
    $bloqueCheckbox);

// =====================================================================
seccion("5. Tabla con enlaces (GET ?orden=...&id=...)", "Como los enlaces Detalles / Modificar / Borrar de examII.");
// =====================================================================

demo("Columna de enlaces",
    'Cada enlace lleva dos datos: la <b>acción</b> y el <b>código</b> del empleado. Llegan a <code>$_GET[\'orden\']</code> y <code>$_GET[\'id\']</code>.',
<<<'PHP'
$empleados = datosEmpleados();
?>
<table class="tabla-lab">
    <tr><th>Código</th><th>Nombre</th><th>Acciones</th></tr>
    <?php foreach ($empleados as $emp): ?>
        <tr>
            <td><?= $emp->cod_emp ?></td>
            <td><?= $emp->nombre ?></td>
            <td><a href="08_tablas.php?orden=Detalles&id=<?= $emp->cod_emp ?>">Detalles</a></td>
        </tr>
    <?php endforeach; ?>
</table>
PHP,
    'añade un enlace "Modificar" con <code>?orden=Modificar&id=...</code> (luego en el PHP habría otro case).', true);

if ($detalle !== null) {
    echo '<div class="resultado"><b>Detalles de ' . e($detalle->cod_emp) . ':</b> ' . e($detalle->nombre) . ' · ' . e($detalle->puesto)
        . ' · ' . number_format($detalle->sueldo, 2, ',', '.') . ' €</div>';
}
codigo("El PHP que lo recibe", 'Comprueba la acción y que llegue el id. Luego se busca ese empleado (en un ejercicio, con <code>$db->getEmpleado()</code>).',
    $bloqueDetalle);

pie();
