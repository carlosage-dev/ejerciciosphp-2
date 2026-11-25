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
 * Recorre los códigos recibidos y aplica la subida según las reglas del enunciado
 * (becario con tope, bloqueados no).
 * @return int número de empleados realmente actualizados
 */
function aplicarSubida(array $listaCod, float $porcentaje): int
{
    // Dentro de la función no existe $db: pedimos el modelo (Singleton = el mismo objeto)
    $db = AccesoDatos::initModelo();
    $cont = 0;

    foreach ($listaCod as $cod) {
        $emp = $db->getEmpleado($cod);

        // No existe o está bloqueado -> al siguiente
        if (!$emp || $emp->bloqueado == 1) {
            continue;
        }

        // Subir un X% = multiplicar por (1 + X/100)
        $nuevo = $emp->sueldo * (1 + $porcentaje / 100);

        // Tope del becario
        if ($emp->puesto == "Becario" && $nuevo > TOPE_BECARIO) {
            $nuevo = TOPE_BECARIO;
        }

        if ($db->actualizarSueldo($emp->cod_emp, round($nuevo, 2))) {
            $cont++;
        }
    }
    return $cont;
}

// =====================================================================
//  CONTROLADOR
// =====================================================================

// 1. ¿Qué departamento se está viendo?  (GET del select o POST del hidden)
$depParam = "todos";

if (isset($_GET["dep"])) {
    $depParam = $_GET["dep"];          // viene del select (Filtrar)
} elseif (isset($_POST["dep"])) {
    $depParam = $_POST["dep"];         // viene del hidden (Subir)
}

if ($depParam == "todos") {
    $depSeleccionado = null;
} else {
    $depSeleccionado = (int) $depParam;
}

// 2. ¿Se ha pulsado Subir? (POST)
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['orden']) && $_POST['orden'] == "Subir") {

    // Subidas que lleva hoy: lo que diga la cookie, o 0 si no existe
    $subidasHoy = 0;
    if (isset($_COOKIE['subidas'])) {
        $subidasHoy = (int) $_COOKIE['subidas'];
    }

    // Porcentaje: lo que llegue, o vacío si no llega
    $porcentaje = "";
    if (isset($_POST['porcentaje'])) {
        $porcentaje = $_POST['porcentaje'];
    }

    // Validaciones (solo sale el primer error)
    if (!isset($_POST['templeados'])) {
        $msg = "No has marcado ningún empleado";
    } elseif (!is_numeric($porcentaje) || $porcentaje < 1 || $porcentaje > 20) {
        $msg = "El porcentaje tiene que estar entre 1 y 20";
    } elseif ($subidasHoy >= MAX_SUBIDAS_DIA) {
        $msg = "Límite de subidas diarias alcanzado";
    } else {
        // Todo correcto
        $n = aplicarSubida($_POST['templeados'], (float) $porcentaje);
        $subidasHoy = $subidasHoy + 1;
        setcookie('subidas', $subidasHoy, time() + 24 * 3600);
        $msg = "Sueldo actualizado a " . $n . " empleado(s). Subidas hoy: " . $subidasHoy . "/" . MAX_SUBIDAS_DIA;
    }
}

// 3. Datos para la vista (siempre DESPUÉS de modificar, para ver los sueldos nuevos)
$tdepartamentos = $db->getDepartamentos();

$nombresDep = [];
foreach ($tdepartamentos as $dep) {
    $nombresDep[$dep->cod_dep] = $dep->nombre;
}

$templeados = $db->getEmpleados($depSeleccionado);
if (count($templeados) == 0 && $msg == "") {
    $msg = "No hay empleados en este departamento";
}
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
            <?php foreach ($tdepartamentos as $departamento): ?>
                <option value="<?= $departamento->cod_dep ?>" <?= ($departamento->cod_dep == $depSeleccionado) ? "selected" : "" ?>>
                    <?= $departamento->nombre ?>
                </option>
            <?php endforeach; ?>
        </select>
        <input type="submit" value="Filtrar">
    </form>

    <!-- Mensaje: solo si hay algo -->
    <?php if ($msg != ""): ?>
        <p class="msg"><?= $msg ?></p>
    <?php endif; ?>

    <!-- ================= SUBIDA (POST) ================= -->
    <form method="post">
        <!-- Hidden para no perder el departamento al hacer POST -->
        <input type="hidden" name="dep" value="<?= ($depSeleccionado === null) ? "todos" : $depSeleccionado ?>">

        <table>
            <tr>
                <th></th><th>Código</th><th>Nombre</th><th>Puesto</th><th>Departamento</th><th>Sueldo</th>
            </tr>
            <?php foreach ($templeados as $empleado): ?>
                <tr class="<?= ($empleado->bloqueado == 1) ? "bloqueado" : "" ?>">
                    <td><input type="checkbox" name="templeados[]" value="<?= $empleado->cod_emp ?>" <?= ($empleado->bloqueado == 1) ? "disabled" : "" ?>></td>
                    <td><?= $empleado->cod_emp ?></td>
                    <td><?= $empleado->nombre ?></td>
                    <td><?= $empleado->puesto ?></td>
                    <td><?= $nombresDep[$empleado->cod_dep] ?></td>
                    <td><?= number_format($empleado->sueldo, 2, ',', '.') ?> €</td>
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