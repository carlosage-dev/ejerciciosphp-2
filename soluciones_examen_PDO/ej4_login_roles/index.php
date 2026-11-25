<?php
session_start();
include_once 'app/funciones.php';

// Todo lo que se haga echo / include va a un buffer
// (por eso se pueden poner cookies aunque ya se haya hecho echo)
ob_start();

$orden = "";
if (isset($_REQUEST['orden'])) {
    $orden = $_REQUEST['orden'];
}

switch ($orden) {

    case "":
        if (estaIdentificado()) {
            mostrarPanel("");
        } else {
            mostrarEntrada("");
        }
        break;

    case "Entrar":
        $cod = "";
        $clave = "";
        if (isset($_POST['cod_emp'])) {
            $cod = trim($_POST['cod_emp']);
        }
        if (isset($_POST['clave'])) {
            $clave = trim($_POST['clave']);
        }
        $recordar = isset($_POST['recordar']);     // checkbox marcado = existe

        $error = procesarLogin($cod, $clave, $recordar);
        if ($error == "") {
            mostrarPanel("");
        } else {
            mostrarEntrada($error);
        }
        break;

    case "PagaExtra":
        if (!estaIdentificado()) {
            mostrarEntrada("Tienes que identificarte");
        } elseif (!esJefe()) {
            mostrarPanel("No tienes permiso");
        } elseif (!isset($_REQUEST['seleccion'])) {
            mostrarPanel("Selecciona al menos un empleado");
        } else {
            mostrarPanel(pagaExtra($_REQUEST['seleccion']));
        }
        break;

    case "CambiarClave":
        if (!estaIdentificado()) {
            mostrarEntrada("Tienes que identificarte");
        } else {
            mostrarPanel(cambiarClave($_POST['actual'], $_POST['nueva'], $_POST['repetida']));
        }
        break;

    case "Salir":
        $_SESSION = [];            // vacía las variables de esta petición
        session_destroy();         // borra la sesión del servidor
        // la cookie 'usuario' NO se toca: es del navegador
        mostrarEntrada("Has cerrado la sesión");
        break;

    default:
        if (estaIdentificado()) {
            mostrarPanel("Orden desconocida");
        } else {
            mostrarEntrada("Orden desconocida");
        }
}

$contenido_php = ob_get_clean();
include_once 'app/plantillas/principal.php';
