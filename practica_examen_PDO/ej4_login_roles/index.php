<?php
session_start();
include_once 'app/funciones.php';

// Todo lo que se haga echo / include a partir de aquí va a un buffer
ob_start();
$msg = "";

/*
 * Pista de organización: piensa qué plantilla se muestra en cada caso
 *   - sin sesión                -> app/plantillas/entrada.php
 *   - con sesión y Jefe         -> app/plantillas/panel_jefe.php
 *   - con sesión y no Jefe      -> app/plantillas/panel_empleado.php
 * Te puede venir bien una función mostrarPanel() para no repetir código.
 */

switch ($_REQUEST['orden'] ?? '') {

    case '':
        // TODO: sin orden -> panel si hay sesión, si no entrada
        break;

    case 'Entrar':
        // TODO: procesarLogin(...) y según el resultado panel o entrada con $msg
        break;

    case 'PagaExtra':
        // TODO: ¿sesión? ¿es jefe? ¿hay seleccionados? -> pagaExtra()
        break;

    case 'CambiarClave':
        // TODO: ¿sesión? -> cambiarClave()
        break;

    case 'Salir':
        // TODO: cerrar sesión (la cookie 'usuario' NO se toca)
        break;

    default:
        // TODO: orden desconocida
}

$contenido_php = ob_get_clean();
include_once 'app/plantillas/principal.php';
