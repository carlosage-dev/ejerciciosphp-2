<?php
/*
 * ================================================================
 *  1b · LOGIN POST + $_SERVER['REQUEST_METHOD']           (~10 min)
 * ================================================================
 *  Los usuarios válidos están en un array (sin base de datos).
 *
 *  1. Si REQUEST_METHOD es GET   -> se muestra el formulario.
 *  2. Si REQUEST_METHOD es POST:
 *       - campos vacíos                       -> error y formulario
 *       - el usuario no existe en el array    -> "Usuario o clave incorrectos"
 *       - la clave no coincide                -> "Usuario o clave incorrectos"
 *       - correcto -> "Bienvenido, admin" y NO se muestra el formulario
 *  3. Si falla, el campo usuario tiene que seguir relleno con lo que escribió
 *     (value="..."), la clave NO.
 *
 *  PISTAS de funciones: array_key_exists() o isset($usuarios[...])
 *  PRUEBA: mira la URL al enviar. ¿Sale la clave? Compáralo con el 1a.
 * ================================================================
 */

$usuarios = [
    "admin"  => "1234",
    "carlos" => "php",
    "profe"  => "daw2",
];

$msg = "";
$loginOk = false;
$usuario = "";

// TODO

if ($_SERVER['REQUEST_METHOD']=="POST"){
    $usuario = $_POST['usuario'];
    if (empty($_POST['usuario']) || empty($_POST['clave']) ){
        $msg = "ERROR CAMPOS VACIOS";
    }else{
        
        if (! array_key_exists($usuario, $usuarios) || $usuarios[$usuario]!=$_POST['clave']){
            $msg ="Usuario o clave incorrectos";
        }else{
            $loginOk =true;
            $msg = "Bienvenido ". $usuario;
        }
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>1b POST</title></head>
<body>
    <h2>Login (POST)</h2>

    <!-- TODO: mensaje -->
     <?= $msg ?>

    <!-- TODO: el formulario solo se muestra si no ha entrado -->
     <?php if (!$loginOk): ?>
    <form method="post">
        Usuario: <input type="text" name="usuario" value=""><br>
        Clave: <input type="password" name="clave"><br>
        <input type="submit" value="Entrar">
    </form>
    <?php endif; ?>
</body>
</html>
