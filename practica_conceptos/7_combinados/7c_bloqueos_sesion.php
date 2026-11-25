<?php
/*
 * ================================================================
 *  7c · CHECKBOX + DOS BOTONES + SWITCH + SESIÓN          (~20 min)
 * ================================================================
 *  Usa el AccesoDatos del bloque 6. (Como no hay filtro, NO hace falta
 *  pasar nada más en el formulario.)
 *
 *  1. Tabla con TODOS los empleados (getTodos) dentro de un form POST:
 *        ☐ | Código | Nombre | Puesto | Estado
 *        checkbox name="tcod[]" value="código"
 *        Estado: "🔒 Bloqueado" o "Activo" (estaBloqueado())
 *  2. Dos botones: name="accion" value="Bloquear" / value="Desbloquear"
 *  3. Al enviar (POST):
 *        - si no hay ninguno marcado -> "Marca al menos un empleado"
 *        - switch ($_POST['accion']):
 *              Bloquear    -> cambiarBloqueo($cod, 1) para cada marcado
 *              Desbloquear -> cambiarBloqueo($cod, 0) para cada marcado
 *          Cuenta cuántos han CAMBIADO de verdad (cambiarBloqueo devuelve true)
 *          msg: "Bloqueados 2 empleado(s)"
 *              (si ya estaba bloqueado y lo vuelves a bloquear, no cuenta: ¿por qué?)
 *  4. SESIÓN: $_SESSION['cambios'] acumula el total de cambios de esta sesión.
 *     Arriba: "Cambios realizados en esta sesión: N"
 *     Enlace ?reset=1 (GET) -> pone el contador a 0.
 * ================================================================
 */
session_start();
require_once '../6_pdo/AccesoDatos.php';

$db = AccesoDatos::initModelo();
$msg = "";

// TODO

$empleados = $db->getTodos();
?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>7c Bloqueos</title></head>
<body>
    <h2>Bloqueo de empleados</h2>
    <!-- TODO: contador de la sesión + enlace reset -->
    <!-- TODO: $msg -->

    <form method="post">
        <table border="1" cellpadding="5">
            <tr><th></th><th>Código</th><th>Nombre</th><th>Puesto</th><th>Estado</th></tr>
            <!-- TODO: filas -->
        </table>
        <br>
        <button name="accion" value="Bloquear">🔒 Bloquear</button>
        <button name="accion" value="Desbloquear">🔓 Desbloquear</button>
    </form>
</body>
</html>
