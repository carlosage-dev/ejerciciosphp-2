<?php
require_once '_lab/lab.php';

// ---------------------------------------------------------------------
// Las clases de esta página. Se guardan en un texto para poder
// enseñártelas abajo Y ejecutarlas (truco del laboratorio).
// ---------------------------------------------------------------------
$codigoEmpleado = <<<'PHP'
class Empleado
{
    // Un atributo PRIVADO por cada columna de la tabla
    private $cod_emp;
    private $nombre;
    private $puesto;
    private $sueldo;
    private $bloqueado;

    // __get: se ejecuta al LEER un atributo privado ($emp->nombre)
    public function __get($atributo)
    {
        if (property_exists($this, $atributo)) {
            return $this->$atributo;
        }
    }

    // __set: se ejecuta al ESCRIBIR un atributo privado ($emp->nombre = "Ana")
    public function __set($atributo, $valor)
    {
        if (property_exists($this, $atributo)) {
            $this->$atributo = $valor;
        }
    }

    // ---- Métodos propios: usan $this (= "este objeto") ----
    public function sueldoFormateado(): string
    {
        return number_format($this->sueldo, 2, ',', '.') . " €";
    }

    public function esBecario(): bool
    {
        return $this->puesto == "Becario";
    }

    public function sueldoAnual(): float
    {
        return $this->sueldo * 14;
    }
}
PHP;

$codigoProducto = <<<'PHP'
class Producto
{
    private $nombre;
    private $precio;
    private $stock;

    // El constructor se ejecuta al hacer new Producto(...)
    public function __construct(string $nombre, float $precio, int $stock)
    {
        $this->nombre = $nombre;
        $this->precio = $precio;
        $this->stock = $stock;
    }

    public function __get($atributo)
    {
        if (property_exists($this, $atributo)) {
            return $this->$atributo;
        }
    }

    public function hayStock(): bool
    {
        return $this->stock > 0;
    }

    // Cambia el estado del objeto y avisa si se ha podido
    public function vender(int $cantidad): bool
    {
        if ($cantidad > $this->stock) {
            return false;
        }
        $this->stock = $this->stock - $cantidad;
        return true;
    }

    public function valorInventario(): float
    {
        return $this->precio * $this->stock;
    }
}
PHP;

$codigoTienda = <<<'PHP'
class Tienda
{
    private $nombre;
    private $productos = [];     // array de objetos Producto

    public function __construct(string $nombre)
    {
        $this->nombre = $nombre;
    }

    public function agregar(Producto $p)
    {
        $this->productos[] = $p;
    }

    public function totalInventario(): float
    {
        $total = 0;
        foreach ($this->productos as $p) {
            $total += $p->valorInventario();     // usa el método de CADA producto
        }
        return $total;
    }

    public function sinStock(): array
    {
        $nombres = [];
        foreach ($this->productos as $p) {
            if (!$p->hayStock()) {
                $nombres[] = $p->nombre;
            }
        }
        return $nombres;
    }
}
PHP;

$codigoSingleton = <<<'PHP'
class Conexion
{
    private static $instancia = null;   // static = de la CLASE, no de cada objeto
    public $numero;

    private function __construct()      // private: nadie puede hacer new Conexion()
    {
        $this->numero = rand(1, 1000);
    }

    public static function get()
    {
        if (self::$instancia == null) {
            self::$instancia = new Conexion();
        }
        return self::$instancia;        // siempre el MISMO objeto
    }
}
PHP;

eval($codigoEmpleado);
eval($codigoProducto);
eval($codigoTienda);
eval($codigoSingleton);

cabecera("Objetos (POO)", "Una <b>clase</b> es el molde; un <b>objeto</b> es cada cosa creada con ese molde. "
    . "PDO te devuelve objetos de tus clases con <code>FETCH_CLASS</code>, por eso tienes que saber usarlos.");

seccion("Chuleta rápida");
chuleta([
    ['class Empleado { ... }', 'Define el molde'],
    ['private $nombre;', 'Atributo que solo se toca desde dentro de la clase'],
    ['$emp = new Empleado();', 'Crea un objeto'],
    ['$emp->nombre', 'Accede a un atributo (con privados, pasa por <code>__get</code>)'],
    ['$emp->nombre = "Ana";', 'Cambia un atributo (con privados, pasa por <code>__set</code>)'],
    ['$emp->sueldoAnual()', 'Llama a un método (lleva paréntesis)'],
    ['$this->sueldo', 'Dentro de la clase: "el sueldo de ESTE objeto"'],
    ['__construct(...)', 'Se ejecuta solo al hacer <code>new</code>'],
    ['$obj->$campo', 'Atributo cuyo nombre está en una variable (<code>$campo = "nombre"</code>)'],
    ['static / self::', 'De la clase, no de cada objeto (Singleton)'],
    ['Clase::metodo()', 'Llamar a un método estático: <code>AccesoDatos::initModelo()</code>'],
    ['$obj instanceof Clase', '¿Es un objeto de esa clase?'],
]);

// =====================================================================
seccion("1. Las clases de esta página", "Estas son las clases que usan todos los ejemplos de abajo.");
// =====================================================================

codigo("Clase Empleado (sin constructor, como las de PDO)",
    'Atributos privados con el <b>mismo nombre que las columnas</b> de la tabla, <code>__get</code>/<code>__set</code> y métodos propios. '
    . 'No tiene constructor porque PDO la rellena él solo con <code>FETCH_CLASS</code>.',
    $codigoEmpleado);

codigo("Clase Producto (con constructor)",
    'El constructor recibe los datos al crear el objeto: <code>new Producto("Teclado", 25, 10)</code>.',
    $codigoProducto);

// =====================================================================
seccion("2. Crear y usar objetos");
// =====================================================================

demo("new, __set y __get",
    'Al escribir <code>$emp->nombre = "Ana"</code>, como <code>nombre</code> es privado, PHP llama a <code>__set</code>. Al leerlo, a <code>__get</code>.',
<<<'PHP'
$emp = new Empleado();
$emp->cod_emp = "ana01";
$emp->nombre = "Ana López";
$emp->puesto = "Jefe";
$emp->sueldo = 3200;

echo $emp->nombre . " (" . $emp->cod_emp . ") es " . $emp->puesto;
PHP,
    'crea otro empleado "pablo04", Becario, 850 €.');

demo("Llamar a métodos",
    'Los métodos llevan <b>paréntesis</b>. Dentro usan <code>$this</code>, que es el objeto con el que los llamas.',
<<<'PHP'
$emp = new Empleado();
$emp->nombre = "Pablo";
$emp->puesto = "Becario";
$emp->sueldo = 850;

echo "Sueldo: " . $emp->sueldoFormateado() . "\n";
echo "Anual: " . number_format($emp->sueldoAnual(), 2, ',', '.') . " €\n";
echo "¿Becario? " . ($emp->esBecario() ? "sí" : "no");
PHP,
    'añade a la clase un método <code>sueldoConSubida(float $p): float</code> y úsalo aquí.');

demo("Constructor",
    'Con constructor, los datos se pasan al hacer <code>new</code> y el objeto nace ya completo.',
<<<'PHP'
$teclado = new Producto("Teclado", 25, 10);

echo $teclado->nombre . ": " . $teclado->precio . " €, stock " . $teclado->stock . "\n";
echo "¿Hay stock? " . ($teclado->hayStock() ? "sí" : "no") . "\n";

var_dump($teclado->vender(3));
echo "Queda: " . $teclado->stock . "\n";
var_dump($teclado->vender(50));
echo "Queda: " . $teclado->stock;
PHP,
    'crea un Monitor con stock 0 y comprueba <code>hayStock()</code>.');

demo("__set ignora atributos que no existen",
    'Gracias a <code>property_exists</code>, si escribes mal un nombre no se crea nada. Por eso, si haces '
    . '<code>FETCH_CLASS</code> con una columna calculada (<code>COUNT(*) AS total</code>), ese dato <b>se pierde</b>: usa <code>FETCH_ASSOC</code>.',
<<<'PHP'
$emp = new Empleado();
$emp->nombre = "Ana";
$emp->nombr = "Error de tecleo";    // no existe ese atributo
$emp->total = 99;                    // tampoco

var_dump($emp->nombre);
var_dump($emp->nombr);
var_dump($emp->total);
PHP,
    'piensa qué pasaría con un error de tecleo en un examen: <code>$emp->cod_emple</code> en vez de <code>cod_emp</code> (te pasó en el Ej1).');

// =====================================================================
seccion("3. Arrays de objetos", "Lo que te devuelve <code>fetchAll()</code> con FETCH_CLASS es esto: un array de objetos.");
// =====================================================================

demo("Recorrer un array de objetos",
    'Cada vuelta del <code>foreach</code> te da un objeto: usas sus atributos y sus métodos.',
<<<'PHP'
$datos = [
    ["ana01", "Ana López", "Jefe", 3200],
    ["luis02", "Luis García", "Programador", 2100],
    ["pablo04", "Pablo Sanz", "Becario", 850],
];

$empleados = [];
foreach ($datos as $d) {
    $e = new Empleado();
    $e->cod_emp = $d[0];
    $e->nombre = $d[1];
    $e->puesto = $d[2];
    $e->sueldo = $d[3];
    $empleados[] = $e;
}

$total = 0;
foreach ($empleados as $emp) {
    echo $emp->nombre . " → " . $emp->sueldoFormateado();
    if ($emp->esBecario()) {
        echo "  (becario)";
    }
    echo "\n";
    $total += $emp->sueldo;
}
echo "Total: " . number_format($total, 2, ',', '.') . " €";
PHP,
    'cuenta cuántos becarios hay usando <code>esBecario()</code>.');

codigo("Clase Tienda (un objeto que guarda un array de objetos)",
    'Sus métodos recorren su array y llaman a los métodos de cada Producto.',
    $codigoTienda);

demo("Usar la Tienda",
    'Fíjate en que <code>totalInventario()</code> no sabe calcular nada: le pregunta a cada producto con <code>valorInventario()</code>.',
<<<'PHP'
$tienda = new Tienda("InformaTic");
$tienda->agregar(new Producto("Teclado", 25, 10));
$tienda->agregar(new Producto("Ratón", 12, 0));
$tienda->agregar(new Producto("Monitor", 180, 2));
$tienda->agregar(new Producto("Cascos", 40, 0));

echo "Inventario: " . $tienda->totalInventario() . " €\n";
echo "Sin stock: " . implode(", ", $tienda->sinStock());
PHP,
    'añade a Tienda un método <code>masCaro()</code> que devuelva el objeto Producto más caro.');

// =====================================================================
seccion("4. Trucos que salen en los ejercicios");
// =====================================================================

demo('Propiedad variable: $obj->$campo',
    'Si el nombre del atributo está en una variable, <code>$obj->$campo</code> lee ese atributo. '
    . 'Así funciona <code>pintarTabla($objetos, $columnas)</code> del Ej4: sirve para cualquier clase.',
<<<'PHP'
$emp = new Empleado();
$emp->cod_emp = "ana01";
$emp->nombre = "Ana";
$emp->puesto = "Jefe";

$columnas = ["cod_emp" => "Código", "nombre" => "Nombre", "puesto" => "Puesto"];

foreach ($columnas as $campo => $titulo) {
    echo $titulo . ": " . $emp->$campo . "\n";   // $emp->cod_emp, $emp->nombre...
}
PHP,
    'añade <code>"sueldo" => "Sueldo"</code> a <code>$columnas</code> (dale antes un sueldo al empleado).');

demo("Los objetos se comparten (no se copian)",
    '<code>$b = $a</code> con objetos NO hace una copia: las dos variables apuntan al <b>mismo</b> objeto. Para copiar: <code>clone</code>.',
<<<'PHP'
$a = new Producto("Teclado", 25, 10);
$b = $a;                 // el MISMO objeto
$b->vender(4);
echo "Stock según \$a: " . $a->stock . "\n";

$c = clone $a;           // una copia independiente
$c->vender(1);
echo "Stock según \$a: " . $a->stock . " / según \$c: " . $c->stock;
PHP,
    'haz lo mismo con un array normal: <code>$x = [1]; $y = $x; $y[] = 2;</code> ¿cambia <code>$x</code>?');

demo("instanceof",
    'Comprueba si una variable es un objeto de esa clase. Útil cuando una función puede devolver un objeto o <code>false</code>/<code>null</code>.',
<<<'PHP'
$p = new Producto("Teclado", 25, 10);
$nada = false;           // lo que devuelve fetch() si no encuentra nada

var_dump($p instanceof Producto);
var_dump($nada instanceof Producto);

if (!$nada) {
    echo "No se ha encontrado";
}
PHP,
    'comprueba si <code>$p</code> es un <code>Empleado</code>.');

codigo("Singleton (el patrón de AccesoDatos)",
    'Un atributo <code>static</code> guarda el único objeto. El constructor es <code>private</code> y se pide con <code>Clase::get()</code>. '
    . 'Es lo que hace <code>AccesoDatos::initModelo()</code>: la conexión se crea UNA vez.',
    $codigoSingleton);

demo("Singleton: siempre el mismo objeto",
    'Aunque lo pidas dos veces, te da el mismo (mismo número aleatorio y <code>===</code> da true).',
<<<'PHP'
$c1 = Conexion::get();
$c2 = Conexion::get();

echo "c1: " . $c1->numero . "\n";
echo "c2: " . $c2->numero . "\n";
var_dump($c1 === $c2);
PHP,
    'intenta hacer <code>new Conexion()</code> y lee el error.');

pie();
