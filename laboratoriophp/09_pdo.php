<?php
require_once '_lab/lab.php';

/* =====================================================================
 *  PDO · consultas reales contra la base de datos empresadb
 *  Necesita: XAMPP con MySQL encendido + empresadb.sql ejecutado.
 * ===================================================================== */

$codigoConexion = <<<'PHP'
function conectar()
{
    static $pdo = null;               // se conecta UNA vez (como el Singleton)
    if ($pdo === null) {
        try {
            $dsn = "mysql:host=localhost;dbname=empresadb;charset=utf8";
            $pdo = new PDO($dsn, "root", "");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            $pdo = false;             // no se ha podido conectar
        }
    }
    return $pdo;
}
PHP;

$codigoClases = <<<'PHP'
class Empleado
{
    private $cod_emp;
    private $nombre;
    private $clave;
    private $puesto;
    private $sueldo;
    private $cod_dep;
    private $bloqueado;
    private $accesos;
    private $fecha_alta;

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

    // Métodos propios que podrás usar con lo que devuelve PDO
    public function sueldoFormateado(): string
    {
        return number_format($this->sueldo, 2, ',', '.') . " €";
    }

    public function esBecario(): bool
    {
        return $this->puesto == "Becario";
    }
}

class Departamento
{
    private $cod_dep;
    private $nombre;
    private $localidad;
    private $presupuesto;

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
PHP;

eval($codigoConexion);
eval($codigoClases);
$hayBD = (conectar() !== false);

/** Si hay BD se ejecuta el ejemplo; si no, solo se enseña el código */
function demoBD(string $titulo, string $explicacion, string $codigo, string $reto = "", bool $html = false): void
{
    if ($GLOBALS['hayBD']) {
        demo($titulo, $explicacion, $codigo, $reto, $html);
    } else {
        codigo($titulo, $explicacion, $codigo, $reto);
    }
}

cabecera("PDO", "Cómo hablar con MySQL desde PHP. Todos los ejemplos hacen consultas <b>de verdad</b> contra <code>empresadb</code>. "
    . "Los que modifican datos se deshacen solos al final, así que puedes recargar sin miedo.");

if ($hayBD) {
    caja('ok', 'Conectado a empresadb', '<p>Los ejemplos de abajo se están ejecutando contra tu base de datos.</p>');
} else {
    caja('error', 'No se ha podido conectar a empresadb',
        '<p>Para ver los resultados: enciende <b>MySQL</b> en el panel de XAMPP, entra en <b>phpMyAdmin</b> y ejecuta '
        . '<code>empresadb.sql</code> (está en la carpeta del laboratorio). Mientras tanto, puedes leer todo el código.</p>');
}

seccion("Chuleta rápida");
chuleta([
    ['$stmt = $pdo->prepare("SELECT ... WHERE x = :x");', 'Prepara la consulta con un <b>marcador</b> <code>:x</code> (nunca metas variables en el SQL)'],
    ['$stmt->bindValue(":x", $valor);', 'Da valor al marcador'],
    ['$stmt->setFetchMode(PDO::FETCH_CLASS, "Empleado");', 'Cada fila se convertirá en un objeto Empleado'],
    ['$stmt->execute();', 'Ejecuta. Devuelve true/false'],
    ['$stmt->fetchAll();', 'TODAS las filas → array (vacío si no hay)'],
    ['$stmt->fetch();', 'UNA fila → objeto, o <code>false</code> si no hay'],
    ['$stmt->fetch(PDO::FETCH_ASSOC);', 'Una fila como array asociativo (para COUNT, SUM, JOIN...)'],
    ['$stmt->rowCount();', 'Filas afectadas por un INSERT / UPDATE / DELETE'],
    ['try { ... } catch (PDOException $e) { ... }', 'Capturar errores (conexión, clave foránea, duplicados...)'],
]);

// =====================================================================
seccion("1. Conectar y las clases");
// =====================================================================
codigo("La conexión", 'Con <code>ERRMODE_EXCEPTION</code>, cualquier error de SQL lanza una excepción que puedes capturar con <code>try/catch</code>. '
    . 'En los ejercicios esto va en el constructor privado de <code>AccesoDatos</code> (Singleton).', $codigoConexion);
codigo("Las clases", 'Atributos con el <b>mismo nombre que las columnas</b>: así <code>FETCH_CLASS</code> sabe dónde meter cada dato.', $codigoClases);

// =====================================================================
seccion("2. SELECT");
// =====================================================================

demoBD("Todas las filas → array de objetos (fetchAll)",
    'El esquema de siempre: <b>prepare → setFetchMode → execute → fetchAll</b>. Luego recorres los objetos y puedes usar sus métodos.',
<<<'PHP'
$pdo = conectar();

$stmt = $pdo->prepare("SELECT * FROM empleados ORDER BY nombre");
$stmt->setFetchMode(PDO::FETCH_CLASS, 'Empleado');
$stmt->execute();
$empleados = $stmt->fetchAll();          // array de objetos Empleado

echo "Hay " . count($empleados) . " empleados\n\n";
foreach ($empleados as $emp) {
    echo $emp->cod_emp . " · " . $emp->nombre . " · " . $emp->sueldoFormateado() . "\n";
}
PHP,
    'ordena por sueldo de mayor a menor (<code>ORDER BY sueldo DESC</code>).');

demoBD("Con un parámetro: WHERE + bindValue",
    'El valor nunca se escribe dentro del SQL: va un <b>marcador</b> <code>:cod_dep</code> y se le da valor con <code>bindValue</code>. Así se evita la inyección SQL.',
<<<'PHP'
$pdo = conectar();
$cod_dep = 2;        // imagina que llega de $_GET['dep']

$stmt = $pdo->prepare("SELECT * FROM empleados WHERE cod_dep = :cod_dep ORDER BY sueldo DESC");
$stmt->bindValue(":cod_dep", $cod_dep);
$stmt->setFetchMode(PDO::FETCH_CLASS, 'Empleado');
$stmt->execute();
$empleados = $stmt->fetchAll();

foreach ($empleados as $emp) {
    echo $emp->nombre . " (" . $emp->puesto . ")\n";
}
PHP,
    'saca los del departamento 4 (Marketing): el array viene vacío. Escribe "No hay empleados" en ese caso.');

demoBD("Una sola fila (fetch) y qué pasa si no existe",
    '<code>fetch()</code> devuelve el objeto o <code>false</code>. Por eso siempre se comprueba con <code>if (!$emp)</code>.',
<<<'PHP'
function getEmpleado(PDO $pdo, string $cod)
{
    $stmt = $pdo->prepare("SELECT * FROM empleados WHERE cod_emp = :cod");
    $stmt->bindValue(":cod", $cod);
    $stmt->setFetchMode(PDO::FETCH_CLASS, 'Empleado');
    $stmt->execute();
    return $stmt->fetch();           // objeto o false
}

$pdo = conectar();
$ana = getEmpleado($pdo, "ana01");
if ($ana) {
    echo "Encontrado: " . $ana->nombre . "\n";
}

$nadie = getEmpleado($pdo, "noexiste");
var_dump($nadie);
if (!$nadie) {
    echo "No existe ese empleado";
}
PHP,
    'busca "sara05" y escribe si está bloqueada.');

demoBD("Varios parámetros: un login",
    'Varios marcadores, un <code>bindValue</code> por cada uno.',
<<<'PHP'
$pdo = conectar();
$usuario = "luis02";     // de $_POST['cod_emp']
$clave = "abcd";         // de $_POST['clave']

$stmt = $pdo->prepare("SELECT * FROM empleados WHERE cod_emp = :cod AND clave = :clave");
$stmt->bindValue(":cod", $usuario);
$stmt->bindValue(":clave", $clave);
$stmt->setFetchMode(PDO::FETCH_CLASS, 'Empleado');
$stmt->execute();
$emp = $stmt->fetch();

if (!$emp) {
    echo "Usuario o clave incorrectos";
} elseif ($emp->bloqueado == 1) {
    echo "Cuenta bloqueada";
} else {
    echo "Bienvenido, " . $emp->nombre;
}
PHP,
    'prueba con la clave mal y con "sara05" / "sara".');

demoBD("FETCH_ASSOC: la fila como array asociativo",
    'Sin objetos: cada fila es un array con los nombres de columna como claves.',
<<<'PHP'
$pdo = conectar();

$stmt = $pdo->prepare("SELECT cod_emp, nombre, sueldo FROM empleados WHERE cod_emp = :cod");
$stmt->bindValue(":cod", "ana01");
$stmt->execute();
$fila = $stmt->fetch(PDO::FETCH_ASSOC);

print_r($fila);
echo $fila['nombre'] . " cobra " . $fila['sueldo'];
PHP,
    'haz un <code>fetchAll(PDO::FETCH_ASSOC)</code> de todos los departamentos y recórrelo.');

demoBD("COUNT, SUM, AVG (con alias y FETCH_ASSOC)",
    'Las columnas calculadas necesitan un <b>alias</b> (<code>AS total</code>) para poder leerlas. Se usa FETCH_ASSOC porque '
    . '<code>total</code> no es un atributo de ninguna clase: con FETCH_CLASS se perdería.',
<<<'PHP'
$pdo = conectar();

$stmt = $pdo->prepare("SELECT COUNT(*) AS total, SUM(sueldo) AS gasto, AVG(sueldo) AS media FROM empleados");
$stmt->execute();
$fila = $stmt->fetch(PDO::FETCH_ASSOC);

echo "Empleados: " . $fila['total'] . "\n";
echo "Gasto mensual: " . number_format($fila['gasto'], 2, ',', '.') . " €\n";
echo "Sueldo medio: " . number_format($fila['media'], 2, ',', '.') . " €";
PHP,
    'cuenta solo los becarios (<code>WHERE puesto = :puesto</code>).');

demoBD("LEFT JOIN + GROUP BY (las dos tablas)",
    '<code>LEFT JOIN</code> saca TODOS los departamentos, también los que no tienen empleados (Marketing). '
    . '<code>COUNT(e.cod_emp)</code> da 0 en ese caso y <code>COALESCE</code> cambia el NULL de la suma por 0.',
<<<'PHP'
$pdo = conectar();

$sql = "SELECT d.nombre, d.presupuesto,
               COUNT(e.cod_emp) AS num,
               COALESCE(SUM(e.sueldo), 0) AS gasto
        FROM departamentos d
        LEFT JOIN empleados e ON d.cod_dep = e.cod_dep
        GROUP BY d.cod_dep, d.nombre, d.presupuesto
        ORDER BY d.nombre";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$resumen = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<table class='tabla-lab'><tr><th>Departamento</th><th>Empleados</th><th>Gasto</th><th>Presupuesto</th></tr>";
foreach ($resumen as $fila) {
    echo "<tr><td>" . $fila['nombre'] . "</td><td>" . $fila['num'] . "</td>";
    echo "<td>" . number_format($fila['gasto'], 2, ',', '.') . " €</td>";
    echo "<td>" . number_format($fila['presupuesto'], 2, ',', '.') . " €</td></tr>";
}
echo "</table>";
PHP,
    'cambia <code>LEFT JOIN</code> por <code>JOIN</code>: Marketing desaparece.', true);

demoBD("Usar los métodos del objeto después de la consulta",
    'Lo que devuelve PDO son objetos de TU clase: puedes llamar a sus métodos.',
<<<'PHP'
$pdo = conectar();

$stmt = $pdo->prepare("SELECT * FROM empleados");
$stmt->setFetchMode(PDO::FETCH_CLASS, 'Empleado');
$stmt->execute();

$becarios = [];
foreach ($stmt->fetchAll() as $emp) {
    if ($emp->esBecario()) {
        $becarios[] = $emp->nombre . " (" . $emp->sueldoFormateado() . ")";
    }
}
echo "Becarios: " . implode(", ", $becarios);
PHP,
    'añade a la clase un método <code>sueldoAnual()</code> y suma el de todos.');

// =====================================================================
seccion("3. INSERT, UPDATE y DELETE", "Para no estropear tus datos, aquí cada ejemplo va dentro de una transacción que se deshace con rollBack(). En tus ejercicios NO hace falta: quita esas dos líneas.");
// =====================================================================

demoBD("UPDATE + rowCount",
    '<code>rowCount()</code> dice cuántas filas ha cambiado. Ojo: si el valor nuevo es igual al que había, MySQL cuenta 0.',
<<<'PHP'
$pdo = conectar();
$pdo->beginTransaction();            // (solo laboratorio)

$stmt = $pdo->prepare("UPDATE empleados SET sueldo = sueldo * 1.10 WHERE puesto = :puesto");
$stmt->bindValue(":puesto", "Becario");
$stmt->execute();
echo "Becarios con subida: " . $stmt->rowCount() . "\n";

$stmt = $pdo->prepare("UPDATE empleados SET bloqueado = 1 - bloqueado WHERE cod_emp = :cod");
$stmt->bindValue(":cod", "sara05");
$stmt->execute();
echo "Bloqueo invertido: " . $stmt->rowCount() . " fila";

$pdo->rollBack();                    // (solo laboratorio) deshace los cambios
PHP,
    'haz un UPDATE con un código que no existe y mira el rowCount.');

demoBD("INSERT a partir de un objeto",
    'Se rellena un objeto con <code>__set</code> y se pasan sus datos a los marcadores. <code>cod_dep</code> es AUTO_INCREMENT: no se pone.',
<<<'PHP'
$pdo = conectar();
$pdo->beginTransaction();            // (solo laboratorio)

$dep = new Departamento();
$dep->nombre = "I+D";
$dep->localidad = "Bilbao";
$dep->presupuesto = 3000;

$stmt = $pdo->prepare("INSERT INTO departamentos (nombre, localidad, presupuesto) VALUES (:nombre, :localidad, :presupuesto)");
$stmt->bindValue(":nombre", $dep->nombre);
$stmt->bindValue(":localidad", $dep->localidad);
$stmt->bindValue(":presupuesto", $dep->presupuesto);
$stmt->execute();

if ($stmt->rowCount() == 1) {
    echo "Insertado con código " . $pdo->lastInsertId();
}

$pdo->rollBack();                    // (solo laboratorio)
PHP,
    'inserta un empleado nuevo con todos sus campos.');

demoBD("DELETE y el error de clave foránea (try / catch)",
    'Un departamento con empleados <b>no se puede borrar</b> (los empleados apuntan a él). MySQL lanza una <code>PDOException</code> que se captura con <code>try/catch</code>.',
<<<'PHP'
$pdo = conectar();
$pdo->beginTransaction();            // (solo laboratorio)

foreach ([4, 1] as $cod) {          // 4 = Marketing (vacío), 1 = Desarrollo (con empleados)
    try {
        $stmt = $pdo->prepare("DELETE FROM departamentos WHERE cod_dep = :cod");
        $stmt->bindValue(":cod", $cod);
        $stmt->execute();
        echo "Departamento $cod: borrado (" . $stmt->rowCount() . " fila)\n";
    } catch (PDOException $e) {
        echo "Departamento $cod: NO se puede borrar, tiene empleados\n";
    }
}

$pdo->rollBack();                    // (solo laboratorio)
PHP,
    'intenta insertar un empleado con un <code>cod_emp</code> que ya existe ("ana01") y captura el error.');

// =====================================================================
seccion("4. Trucos");
// =====================================================================

demoBD("Preparar una vez, ejecutar muchas",
    'Con un bucle se reutiliza la misma sentencia: solo cambia el valor del marcador. Así funcionan las acciones con checkbox (<code>tcod[]</code>).',
<<<'PHP'
$pdo = conectar();
$marcados = ["ana01", "david08", "noexiste"];    // como $_POST['tcod']

$stmt = $pdo->prepare("SELECT nombre FROM empleados WHERE cod_emp = :cod");
foreach ($marcados as $cod) {
    $stmt->bindValue(":cod", $cod);
    $stmt->execute();
    $fila = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($fila) {
        echo $cod . " → " . $fila['nombre'] . "\n";
    } else {
        echo $cod . " → no existe\n";
    }
}
PHP,
    'cuenta cuántos de los marcados existen de verdad.');

demoBD("ORDER BY elegido por el usuario (lista blanca)",
    'Un nombre de columna <b>no se puede</b> poner con <code>bindValue</code>. Se traduce con un <code>switch</code> a un valor fijo. '
    . 'Nunca concatenes <code>$_GET[\'orden\']</code> directamente en el SQL.',
<<<'PHP'
$pdo = conectar();
$ordenPedido = "sueldo";        // de $_GET['orden'] (prueba "DROP TABLE")

switch ($ordenPedido) {
    case "sueldo":
        $orderBy = "sueldo DESC";
        break;
    case "fecha":
        $orderBy = "fecha_alta";
        break;
    default:
        $orderBy = "nombre";
}

$stmt = $pdo->prepare("SELECT nombre, sueldo FROM empleados ORDER BY " . $orderBy . " LIMIT 3");
$stmt->execute();
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $f) {
    echo $f['nombre'] . " - " . $f['sueldo'] . "\n";
}
PHP,
    'cambia <code>$ordenPedido</code> a "fecha" y a "loquesea".');

// =====================================================================
seccion("5. Plantilla: el AccesoDatos completo", "Cópialo como punto de partida en un ejercicio.");
// =====================================================================
codigo("AccesoDatos (Singleton + CRUD de empleados)",
    'Constructor privado, <code>initModelo()</code> estático y un método por operación. Cada método sigue el mismo esquema.',
<<<'PHP'
<?php
require_once "Empleado.php";

class AccesoDatos
{
    private static $modelo = null;
    private $dbh = null;

    public static function initModelo()
    {
        if (self::$modelo == null) {
            self::$modelo = new AccesoDatos();
        }
        return self::$modelo;
    }

    private function __construct()
    {
        try {
            $dsn = "mysql:host=localhost;dbname=empresadb;charset=utf8";
            $this->dbh = new PDO($dsn, "root", "");
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Error de conexión " . $e->getMessage();
            exit();
        }
    }

    // SELECT varias filas
    public function getEmpleados(): array
    {
        $tabla = [];
        $stmt = $this->dbh->prepare("SELECT * FROM empleados ORDER BY nombre");
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Empleado');
        if ($stmt->execute()) {
            $tabla = $stmt->fetchAll();
        }
        return $tabla;
    }

    // SELECT una fila
    public function getEmpleado(string $cod)
    {
        $emp = false;
        $stmt = $this->dbh->prepare("SELECT * FROM empleados WHERE cod_emp = :cod");
        $stmt->bindValue(":cod", $cod);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Empleado');
        if ($stmt->execute()) {
            $emp = $stmt->fetch();
        }
        return $emp;
    }

    // INSERT
    public function addEmpleado(Empleado $e): bool
    {
        $stmt = $this->dbh->prepare("INSERT INTO empleados (cod_emp, nombre, clave, puesto, sueldo, cod_dep)
                                     VALUES (:cod, :nombre, :clave, :puesto, :sueldo, :dep)");
        $stmt->bindValue(":cod", $e->cod_emp);
        $stmt->bindValue(":nombre", $e->nombre);
        $stmt->bindValue(":clave", $e->clave);
        $stmt->bindValue(":puesto", $e->puesto);
        $stmt->bindValue(":sueldo", $e->sueldo);
        $stmt->bindValue(":dep", $e->cod_dep);
        $stmt->execute();
        return $stmt->rowCount() == 1;
    }

    // UPDATE
    public function modEmpleado(Empleado $e): bool
    {
        $stmt = $this->dbh->prepare("UPDATE empleados SET nombre = :nombre, sueldo = :sueldo WHERE cod_emp = :cod");
        $stmt->bindValue(":nombre", $e->nombre);
        $stmt->bindValue(":sueldo", $e->sueldo);
        $stmt->bindValue(":cod", $e->cod_emp);
        $stmt->execute();
        return $stmt->rowCount() == 1;
    }

    // DELETE
    public function borrarEmpleado(string $cod): bool
    {
        $stmt = $this->dbh->prepare("DELETE FROM empleados WHERE cod_emp = :cod");
        $stmt->bindValue(":cod", $cod);
        $stmt->execute();
        return $stmt->rowCount() == 1;
    }
}

// Uso en cualquier sitio (también dentro de funciones):
// $db = AccesoDatos::initModelo();
// $empleados = $db->getEmpleados();
PHP);

pie();
