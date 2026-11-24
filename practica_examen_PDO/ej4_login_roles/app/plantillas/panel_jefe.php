<!-- TODO: cabecera "Hola NOMBRE (PUESTO)" -->
<p>Hola ... (...) · <a href="?orden=Salir">Salir</a></p>

<?php if ($msg != ""): ?>
    <p class="msg"><?= $msg ?></p>
<?php endif; ?>

<h2>Empleados de tu departamento</h2>
<form method="POST">
    <!-- TODO: echo pintarTabla(...) con los empleados de su departamento y checkbox por cod_emp -->

    <button name="orden" value="PagaExtra">💶 Paga extra (+5%) a los seleccionados</button>
</form>
