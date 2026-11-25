<?php
// Usuario recordado (cookie)
$usuarioCookie = "";
$marcado = "";
if (isset($_COOKIE['usuario'])) {
    $usuarioCookie = $_COOKIE['usuario'];
    $marcado = "checked";
}
?>
<?php if ($msg != ""): ?>
    <p class="error"><?= $msg ?></p>
<?php endif; ?>

<form method="POST">
    <table>
        <tr>
            <td>Código empleado:</td>
            <td><input type="text" name="cod_emp" value="<?= $usuarioCookie ?>"></td>
        </tr>
        <tr>
            <td>Clave:</td>
            <td><input type="password" name="clave"></td>
        </tr>
        <tr>
            <td colspan="2"><label><input type="checkbox" name="recordar" value="si" <?= $marcado ?>> Recordar mi usuario</label></td>
        </tr>
    </table>
    <input type="submit" name="orden" value="Entrar">
</form>
