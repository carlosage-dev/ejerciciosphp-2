<?php
/*
 * ================================================================
 *  1e · TIENDA: GET Y POST EN LA MISMA PÁGINA            (~15 min)
 * ================================================================
 *  A) ENLACES (GET) con DOS datos
 *     1. Genera con foreach una lista de enlaces, uno por producto:
 *            <a href="?id=P01">Teclado</a>
 *        (la clave del array es el id, y el nombre está en ['nombre'])
 *     2. Al pinchar uno -> muestra su ficha:
 *            "Teclado · Precio: 25,00 € · Stock: 10 uds."
 *        Si el stock es 0 -> añade " · AGOTADO"
 *        Si el id no existe (prueba ?id=P99) -> "Producto no encontrado"
 *     3. Debajo de la ficha, DOS enlaces para ver el precio en otra moneda:
 *            <a href="?id=P01&moneda=usd">Ver en dólares</a>
 *            <a href="?id=P01&moneda=gbp">Ver en libras</a>
 *        Si llega moneda -> "Precio en USD: 27,00 $"  (usa el array $cambio)
 *        Si la moneda no existe en $cambio (prueba &moneda=yen) -> "Moneda no válida"
 *
 *  B) FORMULARIO (POST): "Calcular pedido"
 *     - un <select name="producto"> con un <option> por producto (value = id),
 *       generado con foreach
 *     - un input "unidades"
 *     - botón Calcular
 *     Al enviar:
 *       - unidades vacías o no numéricas o menores que 1 -> error
 *       - el producto no existe                          -> error
 *       - hay menos stock que unidades pedidas           -> "Solo quedan 2 uds. de Monitor"
 *       - todo bien -> "3 x Teclado = 75,00 €"
 *
 *  Organiza el PHP como en el 1d:
 *     if ($_SERVER['REQUEST_METHOD'] == "GET")  { ... parte A ... }
 *     if ($_SERVER['REQUEST_METHOD'] == "POST") { ... parte B ... }
 *
 *  Pista para el precio bonito: number_format($precio, 2, ',', '.')
 * ================================================================
 */

$productos = [
    "P01" => ["nombre" => "Teclado",  "precio" => 25,  "stock" => 10],
    "P02" => ["nombre" => "Ratón",    "precio" => 12,  "stock" => 0],
    "P03" => ["nombre" => "Monitor",  "precio" => 180, "stock" => 2],
    "P04" => ["nombre" => "Cascos",   "precio" => 40,  "stock" => 5],
];

// 1 euro = ...
$cambio = [
    "usd" => ["simbolo" => "$", "valor" => 1.08],
    "gbp" => ["simbolo" => "£", "valor" => 0.85],
];

$msgFicha  = "";
$msgMoneda = "";
$msgPedido = "";
$idActual  = "";   // id del producto que se está viendo (para montar los enlaces de moneda)

// TODO

if ($_SERVER['REQUEST_METHOD']== "GET"){
    if (isset($_GET['id'])){
        $idActual=$_GET['id'];
        
        if ($productos[$idActual]['stock']==0){
        $msgFicha = " ". $productos[$idActual]['nombre'] . " Precio: ". $productos[$idActual]['precio'] . " AGOTADO";

        }else{
        $msgFicha = " ". $productos[$idActual]['nombre'] . " Precio: ". number_format($productos[$idActual]['precio'],2,',','.'). "€ " . " Stock: ".$productos[$idActual]['stock'];

        }

        if (isset($_GET['moneda'])){
            $moneda = $_GET['moneda'];

            foreach($cambio as $idCambio =>$divisa){
                if ($idCambio == $moneda){
                    $msgMoneda = "Precio en ".$idCambio. " : ". $productos[$idActual]['precio'] * $cambio[$moneda]['valor'] . " " . $cambio[$moneda]['simbolo'];
                }
            }
        }
    }
}

if ($_SERVER['REQUEST_METHOD']=="POST"){
    $producto = $_POST['producto'];
    if ($_POST['unidades']==""){
        $msgPedido ="ERROR, UNIDADES VACIAS";
    }else if (!is_numeric($_POST['unidades'])){
        $msgPedido ="ERROR, NO ES UN NUMERO";
    }else{
        $unidades = (int) $_POST['unidades'];
        if ($unidades <=0){
            $msgPedido ="ERROR, NO SE PUEDEN PONER EN 0 O NEGATIVO";
        }else{
            $msgPedido = $unidades ." x ". $productos[$producto]["nombre"]. " = " . $productos[$producto]['precio']*$unidades;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>1e Tienda</title></head>
<body>
    <h2>A) Productos (GET)</h2>
    <ul>
        <?php foreach($productos as $id =>$producto): ?>
        <!-- TODO: un enlace por producto -->
                <li><a href="?id=<?=$id?>"><?= $producto["nombre"] ?></a></li>
        <?php endforeach; ?>
    </ul>

    <!-- TODO: $msgFicha -->
    <?php if($msgFicha != ""): ?>
        <?= $msgFicha ?>
    <?php endif;?>        
    <!-- TODO: si hay $idActual, los dos enlaces de moneda (con ?id=...&moneda=...) -->
     <?php if (!empty($idActual)): ?>
        <a href="?id=<?= $idActual ?>&moneda=usd">Ver en dólares</a>
        <a href="?id=<?= $idActual ?>&moneda=gbp">Ver en libras</a>
        <?= $msgMoneda ?>
    <?php endif;?>
    <!-- TODO: $msgMoneda -->

    <h2>B) Calcular pedido (POST)</h2>
    <form method="post">
        Producto:
        <select name="producto">
            <!-- TODO: un option por producto -->
            <?php foreach($productos as $id =>$producto): ?>
                <option value="<?= $id ?>"><?= $producto['nombre'] ?></option>
            <?php endforeach; ?>
        </select>
        Unidades: <input type="text" name="unidades" size="3">
        <input type="submit" value="Calcular">
    </form>

    <!-- TODO: $msgPedido -->
    <?php if($msgPedido != ""): ?>
        <?= $msgPedido ?>
    <?php endif;?>  
</body>
</html>