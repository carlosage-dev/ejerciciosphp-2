<?php
/*
 * ================================================================
 *  Prueba de INSERT / UPDATE / DELETE (métodos 7, 8 y 9)
 * ================================================================
 *  TODO: cuando se pulse un botón (POST), un switch con $_POST['accion']:
 *
 *   "Insertar"   -> crea un objeto Departamento con new, rellénalo con __set
 *                   (nombre "I+D", localidad "Bilbao", presupuesto 3000)
 *                   y llama a insertarDepartamento($dep)
 *                   msg: "Departamento insertado" / "Error al insertar"
 *   "Becarios"   -> subirSueldoPuesto("Becario", 10)
 *                   msg: "Se ha subido el sueldo a N becario(s)"      (esperado: 2)
 *   "BorrarVacio"-> borrarDepartamento(4)   (Marketing, sin empleados)
 *                   msg: "Departamento borrado"
 *   "BorrarLleno"-> borrarDepartamento(1)   (Desarrollo, tiene empleados)
 *                   msg: "No se puede borrar: tiene empleados"
 *
 *  Abajo ya se pintan los departamentos y los becarios para ver los cambios.
 *  Para volver a empezar: ejecuta empresadb.sql
 * ================================================================
 */
require_once 'AccesoDatos.php';

$db = AccesoDatos::initModelo();
$msg = "";

// TODO

$deps = $db->getDepartamentos();
$becarios = $db->getEmpleadosPuesto("Becario");
?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Modificar</title></head>
<body>
    <h2>INSERT / UPDATE / DELETE</h2>
    <form method="post">
        <button name="accion" value="Insertar">Insertar "I+D"</button>
        <button name="accion" value="Becarios">Subir 10% a becarios</button>
        <button name="accion" value="BorrarVacio">Borrar Marketing (vacío)</button>
        <button name="accion" value="BorrarLleno">Borrar Desarrollo (con empleados)</button>
    </form>

    <?php if ($msg != ""): ?>
        <p style="background:#fff7d6;padding:8px"><?= $msg ?></p>
    <?php endif; ?>

    <h3>Departamentos</h3>
    <ul>
        <?php foreach ($deps as $d): ?>
            <li><?= $d->cod_dep ?> - <?= $d->nombre ?> (<?= $d->localidad ?>) - <?= $d->presupuesto ?></li>
        <?php endforeach; ?>
    </ul>

    <h3>Becarios</h3>
    <ul>
        <?php foreach ($becarios as $b): ?>
            <li><?= $b->nombre ?>: <?= $b->sueldo ?></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
