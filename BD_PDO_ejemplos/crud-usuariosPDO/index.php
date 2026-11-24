<?php
session_start();

$_SESSION['pin']="12345";
$_SESSION['tiempo']=300; // 5 minutos = 300 segundos

include_once 'app/dat/AccesoDatos.php';
include_once 'app/funciones.php';
include_once 'app/acciones.php';

// 1. Comprobar si han pasado 5 minutos desde que inició sesión
if (isset($_SESSION['inicio_sesion']) && (time() - $_SESSION['inicio_sesion'] > $_SESSION['tiempo'])) {
    session_unset();     // Borra las variables de sesión
    session_destroy();   // Destruye la sesión actual
    session_start();     // Inicia una sesión limpia
    $error = "Tu tiempo de acceso (5 minutos) ha finalizado.";
}

// 2. Comprobar si se ha enviado el formulario del PIN
if (isset($_POST['validar'])) {
    if ($_POST['pin'] === $_SESSION['pin']) {
        $_SESSION['autenticado'] = true;
        $_SESSION['inicio_sesion'] = time(); // Se fija el reloj UNA sola vez al entrar
    } else {
        $error = "PIN incorrecto";
    }
}

// 3. Si NO está autenticado (o ya caducó), mostramos el formulario y paramos
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    include_once "app/layout/formulariopin.php";
    exit();
}
$contenido = "";

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isset($_GET['orden'])) {
        switch ($_GET['orden']) {
            case "Nuevo"    : accionAlta(); break;
            case "Borrar"   : accionBorrar($_GET['id']); break;
            case "Modificar": accionModificar($_GET['id']); break;
            case "Detalles" : accionDetalles($_GET['id']); break;
            case "Terminar" : accionTerminar(); break;
            case "Incrementar-Saldo" : 
        }
    }
} else {
    if (isset($_POST['orden'])) {
        limpiarArrayEntrada($_POST);
        switch ($_POST['orden']) {
            case "Nuevo"    : accionPostAlta(); break;
            case "Modificar": accionPostModificar(); break;
            case "Detalles" : break;
        }
    }
}

$contenido .= mostrarDatos();
include_once "app/layout/principal.php";
?>
