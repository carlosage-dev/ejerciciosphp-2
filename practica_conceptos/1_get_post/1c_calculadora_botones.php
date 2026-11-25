<?php
/*
 * ================================================================
 *  1c · VARIOS BOTONES + SWITCH                          (~15 min)
 * ================================================================
 *  Un formulario POST con dos números y CUATRO botones que se llaman
 *  igual (name="operacion") pero con distinto value.
 *  PHP solo recibe el value del botón que se ha pulsado.
 *
 *  1. Si es POST y existe $_POST['operacion']:
 *       - si algún número está vacío o no es numérico -> error
 *       - switch ($_POST['operacion']) con Sumar, Restar, Multiplicar, Dividir
 *       - Dividir entre 0 -> "No se puede dividir entre 0"
 *       - resultado: "8 + 2 = 10"
 *  2. Después de calcular, los números siguen escritos en los inputs.
 *     Pista: guarda los números en $n1 / $n2 y úsalos en value="..."
 *  3. EXTRA: botón "Limpiar" (otro value más en el switch) que deja todo vacío.
 * ================================================================
 */

$msg = "";
$n1 = "";
$n2 = "";

// TODO

if ($_SERVER['REQUEST_METHOD']=="POST" && isset($_POST['operacion'])){
   
    if ($_POST['n1']=="" || $_POST['n2']==""){
        //MEJOR CON COMILLAS
        $msg = "ERROR CAMPO/S VACIOS";
    }else if (!is_numeric($_POST['n1']) || !is_numeric($_POST['n2']) ){
        $msg = "ERROR tienen que ser numeros";
    }else{
        $n1 =(float) $_POST['n1'];
        $n2 =(float) $_POST['n2'];

        switch($_POST['operacion']){
            case "Sumar": $msg = "Suma ". $n1 + $n2;
                break;
            
            case "Restar": $msg = "Resta ". $n1 - $n2;
                break;

            case "Multiplicar": $msg = "Multiplicacion ". $n1 * $n2;
                break;
            
            case "Dividir": ($n2 == 0) ? $msg = "ERROR NO SE PUEDE DIVIDIR POR CERO" : $msg= "Divison ". $n1 /$n2;
                break;
        }
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>1c Calculadora</title></head>
<body>
    <h2>Calculadora</h2>
    <form method="post">
        <input type="text" name="n1" value="" size="5">
        <input type="text" name="n2" value="" size="5"><br><br>
        <button name="operacion" value="Sumar">+</button>
        <button name="operacion" value="Restar">-</button>
        <button name="operacion" value="Multiplicar">x</button>
        <button name="operacion" value="Dividir">/</button>
    </form>

    <!-- TODO: resultado / error -->
    <?php if ($_SERVER['REQUEST_METHOD']=="POST"): ?>
        <?= $msg ?>
    <?php endif; ?>
</body>
</html>
