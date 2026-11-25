<?php
/*
 * ================================================================
 *  7a · LOGIN CONTRA LA BD + SESIÓN        (login.php + panel.php)  (~20 min)
 * ================================================================
 *  Usa el AccesoDatos y las clases del bloque 6 (termínalo antes).
 *
 *  login.php
 *   1. Si ya hay $_SESSION['cod_emp'] -> header("Location: panel.php")
 *   2. Si es POST:
 *        - campos vacíos                          -> error
 *        - getEmpleado() no lo encuentra          -> "Usuario o clave incorrectos"
 *        - la clave no coincide ($emp->clave)     -> "Usuario o clave incorrectos"
 *        - estaBloqueado()                        -> "Cuenta bloqueada"
 *        - correcto:
 *            incrementarAccesos()
 *            $_SESSION['cod_emp'] = ...   (guarda SOLO el código)
 *            header("Location: panel.php"); exit();
 *
 *  panel.php -> instrucciones dentro
 *
 *  Usuarios de prueba: ana01/1234 · luis02/abcd · sara05/sara (bloqueada)
 * ================================================================
 */
session_start();
require_once '../../6_pdo/AccesoDatos.php';

$msg = "";

// TODO

?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Login</title></head>
<body>
    <h2>Intranet · Login</h2>
    <!-- TODO: $msg -->
    <form method="post">
        Código: <input type="text" name="cod_emp"><br>
        Clave: <input type="password" name="clave"><br>
        <input type="submit" value="Entrar">
    </form>
</body>
</html>
