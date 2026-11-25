<?php
require_once __DIR__ . '/modelo/AccesoDatos.php';

define('MAX_FALLOS', 3);
define('PORCENTAJE_PAGA_EXTRA', 5);

// =====================================================================
//  SESIÓN / ROLES
// =====================================================================

function estaIdentificado(): bool
{
    return isset($_SESSION['cod_emp']);
}

function esJefe(): bool
{
    return estaIdentificado() && $_SESSION['puesto'] == "Jefe";
}

// =====================================================================
//  PANTALLAS (para no repetir los include en el switch)
// =====================================================================

function mostrarEntrada(string $msg)
{
    include __DIR__ . '/plantillas/entrada.php';
}

function mostrarPanel(string $msg)
{
    $db = AccesoDatos::getModelo();
    if (esJefe()) {
        $empleados = $db->getEmpleadosDepartamento($_SESSION['cod_dep']);
        include __DIR__ . '/plantillas/panel_jefe.php';
    } else {
        $emp = $db->getEmpleado($_SESSION['cod_emp']);   // datos frescos de la BD
        include __DIR__ . '/plantillas/panel_empleado.php';
    }
}

// =====================================================================
//  LOGIN
// =====================================================================

/**
 * @return string "" si todo va bien, o el mensaje de error
 */
function procesarLogin(string $cod_emp, string $clave, bool $recordar): string
{
    if ($cod_emp == "" || $clave == "") {
        return "Rellena el código y la clave";
    }

    $db  = AccesoDatos::getModelo();
    $emp = $db->getEmpleado($cod_emp);

    if (!$emp) {
        return "Usuario o clave incorrectos";
    }
    if ($emp->bloqueado == 1) {
        return "Cuenta bloqueada";
    }

    // Clave incorrecta: contar fallos SEGUIDOS del mismo usuario
    if ($emp->clave != $clave) {
        if (isset($_SESSION['usuarioFallo']) && $_SESSION['usuarioFallo'] == $cod_emp) {
            $_SESSION['fallos']++;
        } else {
            $_SESSION['usuarioFallo'] = $cod_emp;    // otro usuario: la cuenta empieza de nuevo
            $_SESSION['fallos'] = 1;
        }

        if ($_SESSION['fallos'] >= MAX_FALLOS) {
            $db->bloquearEmpleado($cod_emp);
            unset($_SESSION['usuarioFallo'], $_SESSION['fallos']);
            return "Cuenta bloqueada por demasiados intentos";
        }
        return "Usuario o clave incorrectos";
    }

    // Login correcto
    unset($_SESSION['usuarioFallo'], $_SESSION['fallos']);
    session_regenerate_id(true);      // EXTRA: id de sesión nuevo (evita robo de sesión)

    $_SESSION['cod_emp'] = $emp->cod_emp;
    $_SESSION['nombre']  = $emp->nombre;
    $_SESSION['puesto']  = $emp->puesto;
    $_SESSION['cod_dep'] = $emp->cod_dep;

    $db->incrementarAccesos($emp->cod_emp);

    // Cookie "Recordar mi usuario"
    if ($recordar) {
        setcookie("usuario", $emp->cod_emp, time() + 7 * 24 * 3600);
    } else {
        setcookie("usuario", "", time() - 3600);    // borrarla
    }
    return "";
}

// =====================================================================
//  PINTAR TABLA GENÉRICA
// =====================================================================

/**
 * $obj->$campo  -> "propiedad variable": si $campo vale "nombre", es $obj->nombre
 */
function pintarTabla(array $objetos, array $columnas, string $campoCheck = ""): string
{
    $html = "<table><tr>";
    if ($campoCheck != "") {
        $html .= "<th></th>";
    }
    foreach ($columnas as $campo => $titulo) {
        $html .= "<th>" . $titulo . "</th>";
    }
    $html .= "</tr>";

    foreach ($objetos as $obj) {
        $clase = "";
        if ($obj->bloqueado == 1) {
            $clase = "bloqueado";
        }
        $html .= "<tr class='" . $clase . "'>";

        if ($campoCheck != "") {
            $html .= "<td>";
            // Sin checkbox en su propia fila ni en los bloqueados
            if ($obj->$campoCheck != $_SESSION['cod_emp'] && $obj->bloqueado != 1) {
                $html .= "<input type='checkbox' name='seleccion[]' value='" . $obj->$campoCheck . "'>";
            }
            $html .= "</td>";
        }

        foreach ($columnas as $campo => $titulo) {
            $html .= "<td>" . $obj->$campo . "</td>";
        }
        $html .= "</tr>";
    }
    $html .= "</table>";
    return $html;
}

// =====================================================================
//  ACCIONES
// =====================================================================

/** Se comprueba TODO en el servidor, aunque el botón solo lo vea el jefe */
function pagaExtra(array $listaCod): string
{
    $db = AccesoDatos::getModelo();
    $cont = 0;
    foreach ($listaCod as $cod) {
        $emp = $db->getEmpleado($cod);
        if (!$emp) {
            continue;
        }
        if ($emp->cod_dep != $_SESSION['cod_dep']) {     // no es de su departamento
            continue;
        }
        if ($emp->cod_emp == $_SESSION['cod_emp']) {     // él mismo
            continue;
        }
        if ($emp->bloqueado == 1) {
            continue;
        }
        if ($db->subirSueldo($emp->cod_emp, PORCENTAJE_PAGA_EXTRA)) {
            $cont++;
        }
    }
    return "Paga extra aplicada a " . $cont . " empleado(s)";
}

function cambiarClave(string $actual, string $nueva, string $repetida): string
{
    $db  = AccesoDatos::getModelo();
    $emp = $db->getEmpleado($_SESSION['cod_emp']);

    if ($emp->clave != $actual) {
        return "La clave actual no es correcta";
    }
    if (strlen($nueva) < 4) {
        return "La nueva clave tiene que tener al menos 4 caracteres";
    }
    if ($nueva != $repetida) {
        return "Las dos claves nuevas no coinciden";
    }
    if ($nueva == $actual) {
        return "La nueva clave tiene que ser distinta de la actual";
    }
    if ($db->cambiarClave($emp->cod_emp, $nueva)) {
        return "Clave cambiada correctamente";
    }
    return "Error al cambiar la clave";
}
