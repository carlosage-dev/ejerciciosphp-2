<?php
/*
 *  panel.php
 *   1. Sin $_SESSION['cod_emp'] -> a login.php
 *   2. ?salir=1 (GET) -> session_destroy() -> a login.php
 *   3. Con el código de la sesión, pide el empleado a la BD (getEmpleado)
 *      -> así los datos están siempre actualizados.
 *   4. Muestra con los MÉTODOS del objeto:
 *        "Hola Ana López"
 *        Puesto · Sueldo mensual (sueldoFormateado) · Sueldo anual (sueldoAnual con number_format)
 *        Accesos: N
 *        Si es becario: "Eres becario: tu sueldo con una subida del 5% sería X"  (sueldoConSubida)
 *   5. Tabla con sus compañeros de departamento (getEmpleadosDep):
 *        Nombre | Puesto | Sueldo   (pintada como quieras: foreach en HTML o $tabla .=)
 *
 *  PRUEBA: entra con pablo04/pablo para ver el mensaje de becario.
 */
session_start();
require_once '../../6_pdo/AccesoDatos.php';

// TODO

?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Panel</title></head>
<body>
    <!-- TODO -->
    <a href="?salir=1">Salir</a>
</body>
</html>
