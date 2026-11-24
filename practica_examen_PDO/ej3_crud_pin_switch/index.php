<?php
session_start();

include_once 'app/config.php';
include_once 'app/funciones.php';
include_once 'app/acciones.php';

$contenido    = "";
$msg          = "";
$bloqueadoPin = false;

// =====================================================================
// 1. CONTROL DE ACCESO POR PIN  (el PIN llega por POST)
//    - ¿demasiados intentos?        -> formulariopin con $bloqueadoPin = true
//    - ¿no llega pin?               -> formulariopin
//    - ¿pin incorrecto?             -> sumar intento + msg -> formulariopin
//    - ¿correcto?                   -> $_SESSION['acceso'], borrar intentos, guardar hora
//    Después de incluir formulariopin.php -> exit();
// =====================================================================


// =====================================================================
// 2. CONTROL DE INACTIVIDAD
//    Más de TIEMPO_INACTIVIDAD segundos desde la última petición -> accionTerminar(); exit();
//    Si no -> renovar la hora
// =====================================================================


// =====================================================================
// 3. GET: mostrar / pedir datos
// =====================================================================
if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isset($_GET['orden'])) {
        switch ($_GET['orden']) {
            // TODO: Nuevo, Detalles, Modificar, Borrar, Terminar
        }
    }
}
// =====================================================================
// 4. POST: modificar datos  (cada acción devuelve el mensaje -> $msg)
// =====================================================================
else {
    if (isset($_POST['orden'])) {
        switch ($_POST['orden']) {
            // TODO: Nuevo, Modificar, ConfirmarBorrar, CambiarBloqueo, Trasladar
            // Volver y Cancelar: no hacen nada
        }
    }
}

$contenido .= mostrarDatos();
include_once "app/layout/principal.php";
