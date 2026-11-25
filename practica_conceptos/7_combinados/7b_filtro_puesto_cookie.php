<?php
/*
 * ================================================================
 *  7b · FILTRO POR ENLACES (GET) + COOKIE + MÉTODOS DEL OBJETO  (~20 min)
 * ================================================================
 *  Usa el AccesoDatos del bloque 6.
 *
 *  1. Arriba, una lista de enlaces generada con foreach desde PUESTOS:
 *        ?puesto=Jefe   ?puesto=Programador ...
 *  2. ¿Qué puesto se muestra?
 *        - si llega ?puesto= y está en PUESTOS (in_array)   -> ese
 *        - si llega pero NO está                             -> error "Puesto no válido"
 *        - si no llega pero existe la cookie "ultimo_puesto" -> ese, con el aviso
 *                                                              "(último puesto consultado)"
 *        - si no hay nada                                    -> "Elige un puesto"
 *  3. Cuando el puesto llega por GET y es válido -> cookie "ultimo_puesto" 7 días.
 *  4. Tabla del puesto (getEmpleadosPuesto):
 *        Código | Nombre | Sueldo (sueldoFormateado) | Anual (sueldoAnual) | Estado
 *        Estado: "Bloqueado" si estaBloqueado(), si no "Activo"
 *     y debajo: "Total mensual del puesto: X €"   (suma de los sueldos)
 *
 *  PRUEBA: elige Administrativo, cierra el navegador, vuelve a abrir esta página
 *          sin nada en la URL -> sale Administrativo con el aviso.
 * ================================================================
 */
require_once '../6_pdo/AccesoDatos.php';

define('PUESTOS', ['Jefe', 'Analista', 'Programador', 'Administrativo', 'Becario']);

$db = AccesoDatos::initModelo();
$msg = "";
$puesto = "";          // puesto que se va a mostrar ("" = ninguno)
$desdeCookie = false;
$tabla = [];

// TODO

?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>7b Puestos</title></head>
<body>
    <h2>Empleados por puesto</h2>
    <p>
        <!-- TODO: enlaces -->
    </p>

    <!-- TODO: $msg / título con el puesto y el aviso de cookie -->

    <!-- TODO: tabla + total -->
</body>
</html>
