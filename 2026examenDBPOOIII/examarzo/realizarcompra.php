<?php
require_once "Socio.php";
require_once "Producto.php";
require_once "AccesoDatos.php";
$socio="";
$clave="";
$producto ="";
$nombre_pro="";
$precio=0;
$codigo_soc = "";
$dinero_soc =0;
$exitoCompra=false;

$mensajeError ="";
if (empty($_POST['socio']) || empty($_POST['clave']) || empty($_POST['producto'])){
    echo "CAMPO/S VACIOS";
    die();
}

if (isset($_POST['socio']) && isset($_POST['clave']) && isset($_POST['producto'])){
    $socio = $_POST['socio'];
    $clave = $_POST['clave'];
    $producto = $_POST['producto'];
}


$ac = AccesoDatos::initModelo();

if ($_SERVER['REQUEST_METHOD']== "POST"){

    $ac = AccesoDatos::initModelo();
    $soc =$ac->haySocio($socio, $clave);
    $prod = $ac->hayProducto($producto);

    if (! $soc){
        echo "ERROR: Los valores de cóigo de cliente y contraseña no son válidos";
        die();
    }else if (! $prod){
        echo "ERROR:  No se encuentra ningún producto con el código ". $producto." en nuestro catálogo";
        die();
    }else {
        echo "BIENVENIDO/A ". $soc->nombre. "<br>";
        $cod_soc = $soc->cod_socio;
        $dinero_soc = $soc->cantidad_max;

        $producto = $prod->cod_pro;
        $precio = $prod->precio;
        $nombre_pro = $prod->nombre_pro;
    }

    $exitoCompra = $ac->procesarCompra($cod_soc, $dinero_soc, $precio);

    if (! $exitoCompra){
        echo "No se ha podido efecturar la compra del producto: ". $producto . " " . $nombre_pro. " por ". $precio . " euros" . "<br>";
        echo "Su límite actual de compra es ". $dinero_soc;
    }else{
        echo "Se ha efectuado la compra del producto: ". $producto . " ". $nombre_pro. " por ". $precio . " euros" . "<br>";
        echo "Su nuevo límite actual de compra es ". $soc->cantidad_max;
    }

}


?>

