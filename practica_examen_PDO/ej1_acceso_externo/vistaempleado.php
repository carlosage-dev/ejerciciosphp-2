<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Portal del empleado</title>
    <link href="default.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="container">
    <div id="header">
        <!-- TODO: nombre del empleado -->
        <h1>Bienvenido/a NOMBRE_EMPLEADO</h1>
    </div>

    <!-- TODO: puesto, departamento (nombre + localidad) y nº de accesos -->
    <p>Puesto: ... | Departamento: ... (...) | Accesos: ...</p>

    <!-- TODO: mensaje de la cookie -->
    <p class="msg">MENSAJE COOKIE</p>

    <h2>Compañeros de tu departamento</h2>
    <table>
        <tr>
            <th>Código</th><th>Nombre</th><th>Puesto</th><th>Sueldo</th><th>Antigüedad (años)</th>
        </tr>

        <!-- TODO: una fila por empleado con la sintaxis alternativa  foreach (...):  ...  endforeach;
                   - clase "yo" para el empleado conectado + "(tú)" junto al nombre
                   - clase "bloqueado" si bloqueado == 1
                   - ve acumulando lo que necesites para la fila del total            -->


        <!-- TODO: fila de TOTAL (suma) y MEDIA -->
        <tr class="total">
            <td colspan="3">TOTAL / MEDIA</td><td>...</td><td>...</td>
        </tr>
    </table>

    <a href="acceso.html">Salir</a>
</div>
</body>
</html>
