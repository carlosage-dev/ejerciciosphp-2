<?php
/*
 *  Abre este archivo en el navegador.
 *  Prueba los SELECT (métodos 1-6 de AccesoDatos) y los métodos de los objetos.
 *  (Si has ejecutado modificar.php, vuelve a ejecutar empresadb.sql antes)
 *
 *  Además, rellena aquí estas 2 funciones, que USAN los métodos de los objetos:
 */
require_once '_test.php';
require_once 'AccesoDatos.php';

/** A. Suma del sueldoAnual() de todos los empleados del array. */
function masaSalarialAnual(array $empleados): float
{
    return 0;
}

/** B. Nombres de los empleados que son becarios (usa esBecario()). */
function nombresBecarios(array $empleados): array
{
    return [];
}

// =====================================================================
//  PRUEBAS (no tocar)
// =====================================================================
$db = AccesoDatos::initModelo();

echo "<h2>SELECT</h2>";
$deps = $db->getDepartamentos();
comprobar("1. getDepartamentos: cuántos", count($deps), 4);
comprobar("1. getDepartamentos: el primero (orden)", isset($deps[0]) ? $deps[0]->nombre : null, "Administración");
$todos = $db->getTodos();
comprobar("2. getTodos: cuántos", count($todos), 11);
comprobar("3. getEmpleadosDep(2): cuántos", count($db->getEmpleadosDep(2)), 3);
$ana = $db->getEmpleado("ana01");
comprobar("4. getEmpleado('ana01') nombre", $ana ? $ana->nombre : null, "Ana López");
comprobar("4. getEmpleado('nadie') -> false", $db->getEmpleado("nadie"), false);
comprobar("5. getEmpleadosPuesto('Programador'): cuántos", count($db->getEmpleadosPuesto("Programador")), 3);
comprobar("6. contarEmpleados", $db->contarEmpleados(), 11);

echo "<h2>Métodos de Departamento</h2>";
if (isset($deps[0])) {
    comprobar("descripcion()", $deps[0]->descripcion(), "Administración (Barcelona)");
    comprobar("presupuestoAnual()", $deps[0]->presupuestoAnual(), 60000.0);
}

echo "<h2>Métodos de Empleado</h2>";
if ($ana) {
    comprobar("sueldoFormateado()", $ana->sueldoFormateado(), "3.200,00 €");
    comprobar("sueldoAnual()", $ana->sueldoAnual(), 44800.0);
    comprobar("esBecario() de ana01", $ana->esBecario(), false);
    comprobar("sueldoConSubida(10) NO cambia el sueldo", [$ana->sueldoConSubida(10), (float) $ana->sueldo], [3520.0, 3200.0]);
}
$pablo = $db->getEmpleado("pablo04");
if ($pablo) {
    comprobar("esBecario() de pablo04", $pablo->esBecario(), true);
}
$sara = $db->getEmpleado("sara05");
if ($sara) {
    comprobar("estaBloqueado() de sara05", $sara->estaBloqueado(), true);
}

echo "<h2>Funciones que usan los objetos</h2>";
comprobar("A. masaSalarialAnual(getTodos())", masaSalarialAnual($todos), 318220.0);
comprobar("B. nombresBecarios(getTodos())", nombresBecarios($todos), ["David Romero", "Pablo Sanz"]);

resumen();
