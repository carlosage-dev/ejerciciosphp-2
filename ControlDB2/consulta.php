<?php
require_once 'AccesoDatos.php';
require_once 'Producto.php';
// Procesar POST si hay datos
// Chequear cookie
// Obtener la lista de productos
// HAY QUE USAR $_SERVER['REQUEST METHOD'] == GET, enseño solo los productos
// Si es POST y si isset($_POST['orden']) procesamos los datos y actualizamos los precios
$ac = AccesoDatos::initModelo();

$tproductos = $ac->getProductos();

if (isset($_POST["tproductos"]) && isset($_POST['orden'])){
    
    if (isset($_COOKIE["precioscambiados"])){
        $msg = "SOLO SE PUEDE HACER LA REBAJA 1 DIA";
    }else{
        setcookie("precioscambiados", date("d/m/Y"), time() + 3600 *24);
        $tProductoActualizar = $_POST["tproductos"];
        $num = $ac->actualizarPrecios($tProductoActualizar);
        $msg= "PRODUCTOS ACTUALIZADOS : ". $num;
    }
}

$tproductos = $ac->getProductos();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <link href="default.css" rel="stylesheet" type="text/css" />
</head>

<body>
    <div id="container" style="width: 600px;">
        <div id="header">
            <h1>BAJAR PRECIOS PRODUCTOS SIN PEDIDOS</h1>
        </div>
        <div id="content">
            
            <?= $msg ?? ' '; ?>
            <form name='entrada' method="post">
                <table>
                    <tr>
                        <th></th>
                        <th>no</th>
                        <th>Descripción</th>
                        <th>stock</th>
                        <th>precio</th>
                    </tr>
                    <?php  foreach ($tproductos as $pro): ?>
                        <tr>
                        <td><input type="checkbox" name="tproductos[]" value="<?=$pro->producto_no ?>"></td>
                        <td><?=$pro->producto_no ?></td>
                        <td><?=$pro->descripcion  ?></td>
                        <td><?=$pro->stock_disponible ?></td>
                        <td><?=$pro->precio_actual ?></td>
                        <tr>
                    <?php endforeach; ?>

                </table>

                <input type="submit" name="orden" value="ACTUALIZAR">
            </form>

        </div>
    </div>
</body>

</html>