<?php
/*
 * ================================================================
 *  1a · FORMULARIO GET                                   (~10 min)
 * ================================================================
 *  La página se llama a sí misma con un formulario GET.
 *
 *  1. Si NO se ha enviado el formulario (no existe $_GET['nombre'])
 *     -> solo se ve el formulario.
 *  2. Si se ha enviado:
 *       - nombre o edad vacíos       -> "Rellena los dos campos"
 *       - edad no numérica           -> "La edad tiene que ser un número"
 *       - todo bien                  -> "Hola Ana, el año que viene tendrás 21 años"
 *  3. Mira la URL después de enviar: ¿qué aparece? ¿por qué?
 *
 *  PRUEBA: escribe directamente en la barra
 *     1a_saludo_get.php?nombre=Luis&edad=30
 *  ¿Funciona sin usar el formulario? Eso es GET.
 * ================================================================
 */

$msg = "";

// TODO: comprobar si se ha enviado el formulario y montar $msg

if (isset($_GET['nombre'])){

    $edad_num=0;

    if (empty($_GET['nombre']) || empty($_GET['edad'])){
        $msg = "Rellena los dos campos";
    }else if (!is_numeric($_GET['edad'])){
        $msg = "La edad tiene que ser un numero";
    }else{
        $edad = $_GET['edad'];
        $nombre = $_GET['nombre'];
        $edad_num = (int) $edad;
        $msg = "Hola ".$nombre. ", el año que viene tendras ". ($edad_num+1). " años";
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>1a GET</title></head>
<body>
    <h2>Saludo (GET)</h2>
    <form method="get">
        Nombre: <input type="text" name="nombre"><br>
        Edad: <input type="text" name="edad"><br>
        <input type="submit" value="Enviar">
    </form>

    <!-- TODO: mostrar $msg si tiene algo -->
    <?php if ($msg!=""): ?>
        <?= $msg ?>
    <?php endif; ?>    
</body>
</html>
