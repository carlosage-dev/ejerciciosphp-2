<?php
/*
 * ================================================================
 *  3c · ZONA PRIVADA CON SESIÓN  (login.php + privada.php)  (~20 min)
 * ================================================================
 *  login.php
 *   1. Si YA hay sesión iniciada ($_SESSION['usuario'] existe)
 *      -> header("Location: privada.php") directamente.
 *   2. Formulario POST usuario/clave, comprobado contra $usuarios.
 *   3. Máximo 3 intentos fallidos por sesión ($_SESSION['intentos']).
 *      Al 3º -> "Demasiados intentos" y ya no se muestra el formulario.
 *   4. Correcto:
 *        $_SESSION['usuario']  = el usuario
 *        $_SESSION['rol']      = el rol (está en el array)
 *        $_SESSION['ultimo']   = time()
 *        borrar los intentos
 *        header("Location: privada.php"); exit();
 *
 *  privada.php  -> mira las instrucciones dentro del archivo
 * ================================================================
 */

session_start();

$usuarios = [
    "admin"  => ["clave" => "1234", "rol" => "administrador"],
    "carlos" => ["clave" => "php",  "rol" => "alumno"],
];
// Fíjate: ahora cada usuario es un ARRAY. La clave de carlos es $usuarios["carlos"]["clave"]

define('MAX_INTENTOS', 3);
$msg = "";
$bloqueado = false;

// TODO

?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Login</title></head>
<body>
    <h2>Acceso</h2>
    <!-- TODO: $msg -->
    <!-- TODO: formulario solo si no está bloqueado -->
    <form method="post">
        Usuario: <input type="text" name="usuario"><br>
        Clave: <input type="password" name="clave"><br>
        <input type="submit" value="Entrar">
    </form>
</body>
</html>
