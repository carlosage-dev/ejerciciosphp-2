<?php
/*
 * ================================================================
 *  2a · CONTADOR DE VISITAS CON COOKIE                   (~10 min)
 * ================================================================
 *  1. Cookie "visitas" que dura 1 año.
 *       - No existe   -> "Bienvenido, es tu primera visita"  y la creas con 1
 *       - Existe      -> "Has visitado esta página N veces"   y la guardas con N+1
 *     (Recuerda: primero LEES, luego ESCRIBES. Y setcookie antes del HTML)
 *
 *  2. Enlace "Reiniciar contador" -> ?borrar=1  (GET)
 *       - Borra la cookie: setcookie con la MISMA clave y un tiempo en el pasado
 *       - Redirige a esta misma página con header("Location: ...") y exit()
 *       (¿Por qué hace falta redirigir? Prueba sin la redirección y mira qué sale)
 *
 *  PRUEBA: recarga varias veces, cierra el navegador y vuelve a abrir.
 *          ¿Se mantiene la cuenta? (Compáralo luego con el 3a de sesiones)
 * ================================================================
 */

$msg = "";

// TODO 2: borrar si llega ?borrar
if (isset($_GET["borrar"])){
    setcookie("visitas","", time()-3600);
    header("Location: 2a_contador_visitas.php");
    exit();
}

// TODO 1: leer / escribir la cookie
$visitas=0;
if (isset($_COOKIE['visitas'])){
    $visitas = $_COOKIE['visitas']+1;
    $msg = "Has visitado esta página ".$visitas. " VECES";
}else{
    $visitas=1;
    $msg ="Bienvenido es tu primera visita";
}

setcookie("visitas", $visitas , time()+3600*24*365);




?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>2a Contador</title></head>
<body>
    <h2>Contador de visitas</h2>
    <p><?= $msg ?></p>
    <a href="?borrar=1">Reiniciar contador</a>
</body>
</html>
