<?php if ($msg != ""): ?>
    <p class="error"><?= $msg ?></p>
<?php endif; ?>

<form method="POST">
    <table>
        <tr>
            <td>Código empleado:</td>
            <!-- TODO: value con el código de la cookie 'usuario' si existe -->
            <td><input type="text" name="cod_emp" value=""></td>
        </tr>
        <tr>
            <td>Clave:</td>
            <td><input type="password" name="clave"></td>
        </tr>
        <tr>
            <!-- TODO: que aparezca marcado si ya existe la cookie -->
            <td colspan="2"><label><input type="checkbox" name="recordar" value="si"> Recordar mi usuario</label></td>
        </tr>
    </table>
    <input type="submit" name="orden" value="Entrar">
</form>
