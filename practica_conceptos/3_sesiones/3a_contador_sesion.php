<?php
/*
 * ================================================================
 *  3a · CONTADOR CON SESIÓN                              (~10 min)
 * ================================================================
 *  Es el 2a pero con $_SESSION en vez de cookie. Así ves la diferencia.
 *
 *  1. session_start() lo primero de todo.
 *  2. $_SESSION['visitas']: si no existe -> 1, si existe -> +1
 *     Mensaje "Visitas en esta sesión: N"
 *  3. Guarda también la hora de la PRIMERA visita en $_SESSION['inicio']
 *     (solo la primera vez) y muestra: "Llevas X segundos en la sesión"
 *     Pista: time()
 *  4. Dos enlaces (GET):
 *       ?accion=reiniciar -> unset($_SESSION['visitas']) (la sesión sigue viva)
 *       ?accion=destruir  -> session_destroy() y redirigir a esta página
 *     Usa un switch con $_GET['accion'].
 *
 *  PRUEBA: cierra el navegador entero y vuelve a abrir. ¿Qué pasa con el
 *  contador? Compáralo con el 2a. (Esa es LA diferencia cookie vs sesión)
 *  EXTRA: F12 -> Aplicación -> Cookies -> verás una cookie PHPSESSID. ¿Qué es?
 * ================================================================
 */

// TODO

session_start();


$msg="";
if (isset($_GET['accion'])){
    switch($_GET['accion']){
        case "reiniciar":
            unset($_SESSION['visitas']);
            header("Location: 3a_contador_sesion.php");
        break;

        case "destruir":
            session_destroy();
            header("Location: 3a_contador_sesion.php");
            exit();
        break;
    }
}

if (isset($_SESSION['visitas'])){
    $_SESSION['visitas'] = $_SESSION['visitas']+1;
}else{
    $_SESSION['visitas']=1;
}

if (!isset($_SESSION['inicio'])){
    $_SESSION['inicio']=time();
}
$msg = "Visitas en esta sesión: " . $_SESSION['visitas'];
$segundos = time() - $_SESSION['inicio'];
$msgTiempo = "Llevas " . $segundos . " segundos en la sesión";
?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>3a Sesión</title></head>
<body>
    <h2>Contador de sesión</h2>
    <!-- TODO: mensajes -->
     <p><?= $msg ?></p><br>
     <p><?= $msgTiempo ?></p>
    <a href="?accion=reiniciar">Reiniciar contador</a> |
    <a href="?accion=destruir">Destruir sesión</a>
</body>
</html>
