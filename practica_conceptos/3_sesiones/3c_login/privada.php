<?php
/*
 *  privada.php
 *   1. Si NO hay $_SESSION['usuario'] -> header("Location: login.php"); exit();
 *   2. INACTIVIDAD: si han pasado más de TIEMPO segundos desde $_SESSION['ultimo']
 *        -> session_destroy() y a login.php
 *      si no -> renovar $_SESSION['ultimo'] = time()
 *   3. Mostrar: "Hola carlos (alumno)" y "Tu sesión caduca en X segundos"
 *   4. Si el rol es "administrador" -> mostrar además un párrafo
 *      "Panel de administración: tienes permisos totales"
 *   5. Enlace Salir -> ?salir=1 -> session_destroy() -> login.php
 *
 *  PRUEBA: entra, espera 30 s sin tocar nada y recarga -> vuelve al login.
 *          Entra y recarga cada 10 s -> nunca caduca (se renueva).
 */

session_start();
define('TIEMPO', 30);   // segundos (pequeño para poder probarlo)

// TODO

?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Zona privada</title></head>
<body>
    <h2>Zona privada</h2>
    <!-- TODO -->
    <a href="?salir=1">Salir</a>
</body>
</html>
