<p>Hola <?= $_SESSION['nombre'] ?> (<?= $_SESSION['puesto'] ?>) · <a href="?orden=Salir">Salir</a></p>

<?php if ($msg != ""): ?>
    <p class="msg"><?= $msg ?></p>
<?php endif; ?>

<h2>Mis datos</h2>
<!-- Un array con UN solo objeto: [$emp] -->
<?= pintarTabla(
    [$emp],
    ['cod_emp' => 'Código', 'nombre' => 'Nombre', 'puesto' => 'Puesto', 'sueldo' => 'Sueldo', 'fecha_alta' => 'Fecha alta', 'accesos' => 'Accesos']
) ?>

<h2>Cambiar clave</h2>
<form method="POST">
    Clave actual: <input type="password" name="actual"><br>
    Nueva clave: <input type="password" name="nueva"><br>
    Repetir nueva: <input type="password" name="repetida"><br>
    <input type="submit" name="orden" value="CambiarClave">
</form>
