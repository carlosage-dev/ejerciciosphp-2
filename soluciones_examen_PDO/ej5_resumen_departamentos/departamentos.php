<?php
require_once 'AccesoDatos.php';

$db  = AccesoDatos::initModelo();
$msg = "";

// =====================================================================
//  FUNCIONES
// =====================================================================

/** switch(true): se ejecuta el primer case cuya condición sea verdadera */
function estadoPresupuesto(float $gasto, float $presupuesto): string
{
    $porcentaje = $gasto / $presupuesto * 100;
    switch (true) {
        case $porcentaje < 80:
            return "OK";
        case $porcentaje <= 100:
            return "AL LÍMITE";
        default:
            return "EXCEDIDO";
    }
}

function claseEstado(string $estado): string
{
    switch ($estado) {
        case "OK":
            return "ok";
        case "AL LÍMITE":
            return "limite";
        default:
            return "excedido";
    }
}

/**
 * LISTA BLANCA: solo estos ORDER BY son posibles.
 * Lo que escriba el usuario nunca llega al SQL tal cual.
 */
function columnaOrden(string $orden): string
{
    switch ($orden) {
        case "gasto":
            return "gasto DESC";
        case "empleados":
            return "num_empleados DESC";
        default:
            return "d.nombre ASC";
    }
}

/** Tabla resumen con $html .= ; cada $fila es un array asociativo */
function pintarResumen(array $resumen, string $orden): string
{
    $html  = "<table>";
    $html .= "<tr><th>Departamento</th><th>Localidad</th><th>Nº empleados</th><th>Gasto sueldos</th>"
           . "<th>Sueldo medio</th><th>Presupuesto</th><th>Estado</th><th></th></tr>";

    foreach ($resumen as $fila) {
        $estado = estadoPresupuesto($fila['gasto'], $fila['presupuesto']);

        // Media: si no hay empleados, AVG da NULL -> "—"
        $media = "—";
        if ($fila['num_empleados'] > 0) {
            $media = number_format($fila['media'], 2, ',', '.') . " €";
        }

        $html .= "<tr>";
        $html .= "<td><a href='?orden=" . $orden . "&dep=" . $fila['cod_dep'] . "'>" . $fila['nombre'] . "</a></td>";
        $html .= "<td>" . $fila['localidad'] . "</td>";
        $html .= "<td>" . $fila['num_empleados'] . "</td>";
        $html .= "<td>" . number_format($fila['gasto'], 2, ',', '.') . " €</td>";
        $html .= "<td>" . $media . "</td>";
        $html .= "<td>" . number_format($fila['presupuesto'], 2, ',', '.') . " €</td>";
        $html .= "<td class='" . claseEstado($estado) . "'>" . $estado . "</td>";

        // Botón solo en los EXCEDIDO
        $html .= "<td>";
        if ($estado == "EXCEDIDO") {
            $html .= "<form method='post' action='?orden=" . $orden . "'>"
                   . "<input type='hidden' name='cod_dep' value='" . $fila['cod_dep'] . "'>"
                   . "<button name='accion' value='Ampliar'>Ampliar</button>"
                   . "</form>";
        }
        $html .= "</td>";
        $html .= "</tr>";
    }
    $html .= "</table>";
    return $html;
}

// =====================================================================
//  CONTROLADOR
// =====================================================================

// 1. Orden (GET). Solo valores conocidos, para poder escribirlo en los enlaces
$orden = "nombre";
if (isset($_GET['orden'])) {
    if ($_GET['orden'] == "gasto" || $_GET['orden'] == "empleados") {
        $orden = $_GET['orden'];
    }
}

// 2. POST Ampliar: se vuelve a comprobar EN EL SERVIDOR
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['accion']) && $_POST['accion'] == "Ampliar") {
    $cod = (int) $_POST['cod_dep'];
    $dep = $db->getDepartamento($cod);
    if (!$dep) {
        $msg = "Departamento no encontrado";
    } else {
        $gasto = $db->getGastoDep($cod);
        if (estadoPresupuesto($gasto, $dep->presupuesto) != "EXCEDIDO") {
            $msg = "Ese departamento no necesita ampliación";
        } else {
            $nuevo = round($gasto * 1.10, 2);
            $db->ampliarPresupuesto($cod, $nuevo);
            $msg = "Presupuesto de " . $dep->nombre . " ampliado a " . number_format($nuevo, 2, ',', '.') . " €";
        }
    }
}

// 3. Departamento a detallar: ?dep o, si no, la cookie
$depDetalle  = null;
$templeados  = [];
$gastoDep    = 0;
$desdeCookie = false;
$codDetalle  = null;

if (isset($_GET['dep'])) {
    $codDetalle = (int) $_GET['dep'];
} elseif (isset($_COOKIE['ultimodep'])) {
    $codDetalle  = (int) $_COOKIE['ultimodep'];
    $desdeCookie = true;
}

if ($codDetalle !== null) {
    $depDetalle = $db->getDepartamento($codDetalle);
    if (!$depDetalle) {
        $depDetalle = null;
        if (!$desdeCookie) {
            $msg = "Departamento no encontrado";
        }
    } else {
        if (!$desdeCookie) {
            setcookie("ultimodep", $codDetalle, time() + 30 * 24 * 3600);   // solo si existe
        }
        $templeados = $db->getEmpleadosDep($codDetalle);
        $gastoDep   = $db->getGastoDep($codDetalle);
    }
}

// 4. Resumen (al final: refleja el POST)
$resumen = $db->getResumen(columnaOrden($orden));
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resumen de departamentos</title>
    <link href="default.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="container">
    <div id="header"><h1>RESUMEN DE DEPARTAMENTOS</h1></div>

    <p>Ordenar por:
        <a href="?orden=nombre">Nombre</a> |
        <a href="?orden=gasto">Gasto</a> |
        <a href="?orden=empleados">Nº empleados</a>
    </p>

    <?php if ($msg != ""): ?>
        <p class="msg"><?= $msg ?></p>
    <?php endif; ?>

    <?= pintarResumen($resumen, $orden) ?>

    <!-- ===================== DETALLE (foreach en el HTML) ===================== -->
    <?php if ($depDetalle != null): ?>
        <h2>Empleados de <?= $depDetalle->nombre ?>
            <?php if ($desdeCookie): ?><small>(último departamento consultado)</small><?php endif; ?>
        </h2>

        <?php if (count($templeados) == 0): ?>
            <p>Este departamento no tiene empleados</p>
        <?php else: ?>
            <table>
                <tr><th>Código</th><th>Nombre</th><th>Puesto</th><th>Sueldo</th><th>% del gasto</th></tr>
                <?php foreach ($templeados as $emp): ?>
                    <tr>
                        <td><?= $emp->cod_emp ?></td>
                        <td><?= $emp->nombre ?></td>
                        <td><?= $emp->puesto ?></td>
                        <td><?= number_format($emp->sueldo, 2, ',', '.') ?> €</td>
                        <td><?= number_format($emp->sueldo / $gastoDep * 100, 1, ',', '.') ?> %</td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    <?php endif; ?>
</div>
</body>
</html>
