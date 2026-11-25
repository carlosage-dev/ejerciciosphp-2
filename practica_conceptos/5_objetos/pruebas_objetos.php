<?php
/*
 *  Abre ESTE archivo en el navegador. Rellena Producto.php y Tienda.php
 *  hasta que todo salga en verde. No toques las pruebas.
 */
require_once '_test.php';
require_once 'Tienda.php';

echo "<h2>Producto</h2>";
$teclado = new Producto("Teclado", 25, 10);
$monitor = new Producto("Monitor", 180, 0);

comprobar("__get nombre", $teclado->nombre, "Teclado");
comprobar("precioConIva", $teclado->precioConIva(), 30.25);
comprobar("hayStock (sí)", $teclado->hayStock(), true);
comprobar("hayStock (no)", $monitor->hayStock(), false);
comprobar("descripcion", $teclado->descripcion(), "Teclado - 25,00 € (10 uds.)");
comprobar("vender 3 -> true", $teclado->vender(3), true);
comprobar("stock después de vender 3", $teclado->stock, 7);
comprobar("vender 50 -> false", $teclado->vender(50), false);
comprobar("stock no cambia si no hay bastante", $teclado->stock, 7);
comprobar("valorInventario", $teclado->valorInventario(), 175.0);
$teclado->precio = 30;
comprobar("__set precio", $teclado->precio, 30);

echo "<h2>Tienda</h2>";
$tienda = new Tienda("InformaTic");
$tienda->agregar(new Producto("Teclado", 25, 10));
$tienda->agregar(new Producto("Ratón", 12, 0));
$tienda->agregar(new Producto("Monitor", 180, 2));
$tienda->agregar(new Producto("Cascos", 40, 0));

comprobar("nombre tienda", $tienda->nombre, "InformaTic");
comprobar("numProductos", $tienda->numProductos(), 4);
comprobar("totalInventario", $tienda->totalInventario(), 610.0);
comprobar("sinStock", $tienda->sinStock(), ["Ratón", "Cascos"]);
$encontrado = $tienda->buscar("Monitor");
comprobar("buscar (existe) -> es un Producto", $encontrado instanceof Producto, true);
comprobar("buscar (existe) -> su precio", $encontrado ? $encontrado->precio : null, 180.0);
comprobar("buscar (no existe)", $tienda->buscar("Impresora"), null);
$caro = $tienda->masCaro();
comprobar("masCaro", $caro ? $caro->nombre : null, "Monitor");

resumen();
