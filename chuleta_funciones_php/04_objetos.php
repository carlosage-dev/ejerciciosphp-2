<?php
/*
 * =====================================================================
 *  04 · OBJETOS: clases con métodos que usan las funciones de arrays
 * =====================================================================
 *  - Empleado: un objeto por persona (atributos privados + __get/__set + métodos)
 *  - Departamento: un objeto que GUARDA un array de objetos Empleado.
 *    Sus métodos recorren ese array con foreach y usan count, usort, array_map...
 *  Se rellenan a partir de tus arrays $departamentos y $empleados.
 * =====================================================================
 */
require_once '_ver.php';
require_once 'datos.php';

class Empleado
{
    private $nombre;
    private $salario;
    private $departamento;

    // El constructor se ejecuta al hacer new Empleado(...)
    // (Las clases que rellena PDO con FETCH_CLASS normalmente NO llevan constructor)
    public function __construct(string $nombre, float $salario, string $departamento)
    {
        $this->nombre = $nombre;
        $this->salario = $salario;
        $this->departamento = $departamento;
    }

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

    // ---- Métodos: trabajan con $this (= este empleado) ----
    public function salarioFormateado(): string
    {
        return number_format($this->salario, 2, ',', '.') . " €";
    }

    public function salarioAnual(): float
    {
        return $this->salario * 14;
    }

    /** Solo CALCULA: no cambia el salario */
    public function salarioConSubida(float $porcentaje): float
    {
        return round($this->salario * (1 + $porcentaje / 100), 2);
    }

    /** CAMBIA el salario del objeto */
    public function subirSalario(float $porcentaje): void
    {
        $this->salario = $this->salarioConSubida($porcentaje);
    }

    public function iniciales(): string
    {
        $ini = "";
        foreach (explode(" ", $this->nombre) as $palabra) {
            $ini .= mb_substr($palabra, 0, 1);
        }
        return $ini;
    }
}

// Funciones para ordenar objetos con usort (comparan dos Empleado)
function compararSalarioDesc(Empleado $a, Empleado $b): int
{
    return $b->salario <=> $a->salario;          // mayor primero
}
function compararNombre(Empleado $a, Empleado $b): int
{
    return strcmp($a->nombre, $b->nombre);       // alfabético
}

class Departamento
{
    private $nombre;
    private $jefe;
    private $presupuesto;
    private $empleados = [];                     // array de objetos Empleado

    public function __construct(string $nombre, string $jefe, float $presupuesto)
    {
        $this->nombre = $nombre;
        $this->jefe = $jefe;
        $this->presupuesto = $presupuesto;
    }

    public function __get($atributo)
    {
        if (property_exists($this, $atributo)) {
            return $this->$atributo;
        }
    }

    public function agregar(Empleado $e): void
    {
        $this->empleados[] = $e;                 // [] añade al final del array
    }

    public function numEmpleados(): int
    {
        return count($this->empleados);
    }

    public function gasto(): float
    {
        $total = 0;
        foreach ($this->empleados as $e) {
            $total += $e->salario;
        }
        return $total;
    }

    public function media(): float
    {
        if ($this->numEmpleados() == 0) {
            return 0;                            // evitar dividir entre 0
        }
        return round($this->gasto() / $this->numEmpleados(), 2);
    }

    public function excedePresupuesto(): bool
    {
        return $this->gasto() > $this->presupuesto;
    }

    /** El objeto Empleado que más cobra (o null si no hay nadie) */
    public function mejorPagado()
    {
        $max = null;
        foreach ($this->empleados as $e) {
            if ($max === null || $e->salario > $max->salario) {
                $max = $e;
            }
        }
        return $max;
    }

    /** Busca por nombre: devuelve el objeto o null */
    public function buscar(string $nombre)
    {
        foreach ($this->empleados as $e) {
            if ($e->nombre == $nombre) {
                return $e;
            }
        }
        return null;
    }

    /** Array con solo los nombres (array de strings) */
    public function nombres(): array
    {
        $lista = [];
        foreach ($this->empleados as $e) {
            $lista[] = $e->nombre;
        }
        return $lista;
    }

    /** Los que cobran al menos $minimo: devuelve un array NUEVO de objetos */
    public function cobranMasDe(float $minimo): array
    {
        $lista = [];
        foreach ($this->empleados as $e) {
            if ($e->salario >= $minimo) {
                $lista[] = $e;
            }
        }
        return $lista;
    }

    /** [MODIFICA] ordena SU PROPIO array (el orden se queda cambiado) */
    public function ordenarPorSalario(): void
    {
        usort($this->empleados, "compararSalarioDesc");
    }

    /** [NUEVO] devuelve una copia ordenada; el orden interno NO cambia */
    public function ordenadosPorNombre(): array
    {
        $copia = $this->empleados;               // copia del array
        usort($copia, "compararNombre");
        return $copia;
    }

    /** Sube un % a todos (modifica cada objeto Empleado) */
    public function subirATodos(float $porcentaje): void
    {
        foreach ($this->empleados as $e) {
            $e->subirSalario($porcentaje);       // los objetos SÍ se cambian dentro del foreach
        }
    }
}

// =====================================================================
titulo("1. Crear los objetos a partir de tus arrays");
// =====================================================================
// Dos foreach: uno para crear cada Departamento y otro para meter sus empleados.
$listaDeps = [];                                 // nombre => objeto Departamento
foreach ($departamentos as $nombreDep => $datos) {
    $dep = new Departamento($nombreDep, $datos["Jefe"], $datos["Presupuesto"]);
    foreach ($empleados[$nombreDep] as $emp) {
        $dep->agregar(new Empleado($emp["Nombre"], $emp["Salario"], $nombreDep));
    }
    $listaDeps[$nombreDep] = $dep;
}
echo count($listaDeps) . " departamentos creados\n";        // → 5 departamentos creados

// =====================================================================
titulo("2. Usar atributos y métodos de un objeto");
// =====================================================================
$marta = new Empleado("Marta Ruiz", 3200, "Informática");
echo $marta->nombre . "\n";                      // → Marta Ruiz          (__get)
echo $marta->salarioFormateado() . "\n";         // → 3.200,00 €
echo $marta->salarioAnual() . "\n";              // → 44800
echo $marta->iniciales() . "\n";                 // → MR
echo $marta->salarioConSubida(10) . "\n";        // → 3520   (solo calcula)
echo $marta->salario . "\n";                     // → 3200   (no ha cambiado)
$marta->subirSalario(10);
echo $marta->salario . "\n";                     // → 3520   (ahora sí)
$marta->salario = 3000;                          // (__set)
echo $marta->salario . "\n";                     // → 3000

// =====================================================================
titulo("3. Recorrer los departamentos (array de objetos)");
// =====================================================================
foreach ($listaDeps as $dep) {
    $estado = "OK";
    if ($dep->excedePresupuesto()) {
        $estado = "EXCEDIDO";
    }
    echo $dep->nombre . ": " . $dep->numEmpleados() . " empleados, gasto " . $dep->gasto()
        . ", media " . $dep->media() . " → " . $estado . "\n";
}
// → Informática: 4 empleados, gasto 10300, media 2575 → EXCEDIDO
// → Ventas: 4 empleados, gasto 8700, media 2175 → OK
// → Marketing: 3 empleados, gasto 6700, media 2233.33 → OK
// → Recursos Humanos: 2 empleados, gasto 4700, media 2350 → EXCEDIDO
// → Logística: 5 empleados, gasto 8900, media 1780 → OK

// =====================================================================
titulo("4. Métodos que devuelven objetos o arrays");
// =====================================================================
$ventas = $listaDeps["Ventas"];

$top = $ventas->mejorPagado();
echo $top->nombre . " " . $top->salarioFormateado() . "\n";      // → Pablo Soler 3.000,00 €

$encontrado = $ventas->buscar("Raúl Díaz");
if ($encontrado !== null) {
    echo "Encontrado: " . $encontrado->salario . "\n";           // → Encontrado: 1800
}
var_dump($ventas->buscar("Nadie"));                              // → NULL

echo implode(", ", $ventas->nombres()) . "\n";
// → Pablo Soler, Carmen Ortiz, Raúl Díaz, Elena Ríos

foreach ($ventas->cobranMasDe(2000) as $e) {
    echo $e->nombre . " ";
}
echo "\n";                                                       // → Pablo Soler Elena Ríos

// =====================================================================
titulo("5. Ordenar: [NUEVO] copia vs [MODIFICA] el propio array");
// =====================================================================
$porNombre = $ventas->ordenadosPorNombre();      // devuelve un array NUEVO
foreach ($porNombre as $e) {
    echo $e->nombre . " ";
}
echo "\n";                                       // → Carmen Ortiz Elena Ríos Pablo Soler Raúl Díaz
echo implode(", ", $ventas->nombres()) . "\n";   // → Pablo Soler, Carmen Ortiz, Raúl Díaz, Elena Ríos  (sin cambiar)

$ventas->ordenarPorSalario();                    // cambia el orden DENTRO del objeto
echo implode(", ", $ventas->nombres()) . "\n";   // → Pablo Soler, Elena Ríos, Carmen Ortiz, Raúl Díaz

// =====================================================================
titulo("6. OJO: los objetos NO se copian al asignar");
// =====================================================================
// Con arrays, $b = $a hace una copia. Con objetos, las dos variables
// apuntan al MISMO objeto.
$a = new Empleado("Hugo Mora", 2100, "Informática");
$b = $a;
$b->salario = 9999;
echo $a->salario . "\n";                         // → 9999   ¡ha cambiado también $a!

$c = clone $a;                                   // clone sí hace una copia
$c->salario = 1;
echo $a->salario . " / " . $c->salario . "\n";   // → 9999 / 1

// Por eso subirATodos() funciona con un foreach normal (sin &):
// $e es el mismo objeto que hay dentro del array.
$marketing = $listaDeps["Marketing"];
$marketing->subirATodos(10);
echo $marketing->gasto() . "\n";                 // → 7370   (antes 6700)

// =====================================================================
titulo("7. Recorrer TODOS los empleados de TODOS los departamentos");
// =====================================================================
// Dos niveles de listas (departamentos → empleados) = dos foreach
$total = 0;
foreach ($listaDeps as $dep) {
    foreach ($dep->empleados as $e) {
        $total += $e->salarioAnual();
    }
}
echo "Gasto anual de toda la empresa: " . number_format($total, 2, ',', '.') . " €\n";
// → Gasto anual de toda la empresa: 559.580,00 €
//   (39.300 €/mes × 14, más la subida de Marketing: 39.970 × 14)
