<p>Hola <?= $_SESSION['nombre'] ?> (<?= $_SESSION['puesto'] ?>) · <a href="?orden=Salir">Salir</a></p>

<?php if ($msg != ""): ?>
    <p class="msg"><?= $msg ?></p>
<?php endif; ?>

<h2>Empleados de tu departamento</h2>
<form method="POST">
    <?= pintarTabla(
        $empleados,
        ['cod_emp' => 'Código', 'nombre' => 'Nombre', 'puesto' => 'Puesto', 'sueldo' => 'Sueldo', 'accesos' => 'Accesos'],
        'cod_emp'
    ) ?>

    <button name="orden" value="PagaExtra">💶 Paga extra (+5%) a los seleccionados</button>
</form>
