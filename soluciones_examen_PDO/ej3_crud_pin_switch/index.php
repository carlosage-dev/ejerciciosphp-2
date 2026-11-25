<?php
session_start();

include_once 'app/config.php';
include_once 'app/funciones.php';
include_once 'app/acciones.php';

$contenido    = "";
$msg          = "";
$bloqueadoPin = false;

// =====================================================================
// 1. CONTROL DE ACCESO POR PIN (POST)
// =====================================================================
if (!isset($_SESSION['acceso'])) {

    if (!isset($_SESSION['intentos'])) {
        $_SESSION['intentos'] = 0;
    }

    // ¿Ya ha gastado todos los intentos?
    if ($_SESSION['intentos'] >= MAX_INTENTOS_PIN) {
        $bloqueadoPin = true;
        $msg = "Demasiados intentos. Cierra el navegador y vuelve a intentarlo";
        include 'app/layout/formulariopin.php';
        exit();
    }

    // Todavía no ha escrito el PIN
    if (!isset($_POST['pin'])) {
        include 'app/layout/formulariopin.php';
        exit();
    }

    // PIN incorrecto
    if ($_POST['pin'] != PIN_ACCESO) {
        $_SESSION['intentos']++;
        $quedan = MAX_INTENTOS_PIN - $_SESSION['intentos'];
        if ($quedan == 0) {
            $bloqueadoPin = true;
            $msg = "Demasiados intentos. Cierra el navegador y vuelve a intentarlo";
        } else {
            $msg = "PIN incorrecto. Te quedan " . $quedan . " intentos";
        }
        include 'app/layout/formulariopin.php';
        exit();
    }

    // PIN correcto
    $_SESSION['acceso'] = true;
    unset($_SESSION['intentos']);
    $_SESSION['ultimo'] = time();
}

// =====================================================================
// 2. CONTROL DE INACTIVIDAD (se renueva en cada petición)
// =====================================================================
if (time() - $_SESSION['ultimo'] > TIEMPO_INACTIVIDAD) {
    accionTerminar();
}
$_SESSION['ultimo'] = time();

// =====================================================================
// 3. GET: mostrar / pedir
// =====================================================================
if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isset($_GET['orden'])) {
        switch ($_GET['orden']) {
            case "Nuevo":
                accionAlta();
                break;
            case "Detalles":
                if (isset($_GET['id'])) {
                    accionDetalles($_GET['id']);
                }
                break;
            case "Modificar":
                if (isset($_GET['id'])) {
                    accionModificar($_GET['id']);
                }
                break;
            case "Borrar":
                if (isset($_GET['id'])) {
                    accionBorrar($_GET['id']);
                }
                break;
            case "Terminar":
                accionTerminar();
                break;
        }
    }
}
// =====================================================================
// 4. POST: modificar
// =====================================================================
else {
    if (isset($_POST['orden'])) {
        switch ($_POST['orden']) {
            case "Nuevo":
                $msg = accionPostAlta();
                break;
            case "Modificar":
                $msg = accionPostModificar();
                break;
            case "ConfirmarBorrar":
                $msg = accionPostBorrar();
                break;
            case "CambiarBloqueo":
                $msg = accionCambiarBloqueo();
                break;
            case "Trasladar":
                $msg = accionTrasladar();
                break;
            // "Volver" y "Cancelar": no hacen nada, se vuelve a la tabla
        }
    }
}

$contenido .= mostrarDatos();
include_once "app/layout/principal.php";
