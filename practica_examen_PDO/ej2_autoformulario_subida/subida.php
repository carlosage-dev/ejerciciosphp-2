<?php
require_once 'AccesoDatos.php';

define('MAX_SUBIDAS_DIA', 2);
define('TOPE_BECARIO', 1000);

$msg = "";
$db  = AccesoDatos::initModelo();

// =====================================================================
//  FUNCIONES
// =====================================================================

/**
 * TODO A
 * Recorre los códigos recibidos y aplica la subida según las reglas del enunciado
 * (becario con tope, bloqueados no).
 * @param array $listaCod   códigos de empleado (lo que llega en $_POST['templeados'])
 * @param float $porcentaje 1..20
 * @return int número de empleados realmente actualizados
 */
function aplicarSubida(array $listaCod, float $porcentaje): int
{
    $cont = 0;

    return $cont;
}

// =====================================================================
//  CONTROLADOR
// =====================================================================

// 1. ¿Qué departamento se está viendo?  (GET ?dep=...  o el hidden del POST)
//    null = todos
$depSeleccionado = null;
if (isset($_GET["dep"])){
    if ($_GET["dep"]!="todos"){
        $depSeleccionado = (int) $_GET["dep"];
    }else{
        $depSeleccionado=null;
    }
}


// 2. ¿Se ha pulsado Subir? (POST)  -> validaciones, cookie, aplicarSubida, $msg


// 3. Datos para la vista (siempre DESPUÉS de modificar, para ver los sueldos nuevos)
$tdepartamentos = [];
$templeados     = [];

$tdepartamentos = $db->getDepartamentos();
$templeados = $db->getEmpleados($depSeleccionado);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Subida de sueldos</title>
    <link href="default.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="container">
    <div id="header"><h1>SUBIDA DE SUELDOS</h1></div>

    <!-- ================= FILTRO (GET) ================= -->
    <form method="get">
        Departamento:
        <select name="dep">
            <option value="todos">Todos los departamentos</option>
            <?php  foreach($tdepartamentos as $departamento): ?>
            <option value="<?=$departamento->cod_dep  ?>"><?=$departamento->nombre  ?></option>
            <!-- TODO: una option por departamento, marcando "selected" el elegido -->
             <?php endforeach; ?>
        </select>
        <input type="submit" value="Filtrar">
    </form>
    
    
    <!-- TODO: mostrar $msg solo si hay algo -->
    
    <!-- ================= SUBIDA (POST) ================= -->
    <form method="post">
        <!-- TODO: input hidden para no perder el departamento al hacer POST -->

        <table>
            <tr>
                <th></th><th>Código</th><th>Nombre</th><th>Puesto</th><th>Departamento</th><th>Sueldo</th>
            </tr>
            <?php foreach($templeados as $empleado): ?>
            <!-- TODO: filas de empleados (checkbox templeados[] / disabled si bloqueado)
                       o fila "No hay empleados en este departamento" -->
                <tr>
                    <td><input type="checkbox" name="" id=""></td>
                    <td><?=$empleado->cod_emp ?></td>
                    <td><?= $empleado->nombre ?></td>
                    <td><?= $empleado->puesto ?></td>
                    <td><?= $empleado->cod_dep ?></td>
                    <td><?= $empleado->sueldo ?></td>
                </tr>
            <?php endforeach; ?>           
        </table>

        Porcentaje de subida: <input type="number" name="porcentaje"> %
        <!-- Sin min/max a propósito: la validación la tiene que hacer PHP -->

        <button name="orden" value="Subir">Subir sueldo a los marcados</button>
    </form>

</div>
</body>
</html>
