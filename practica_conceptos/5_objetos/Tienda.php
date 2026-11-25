<?php
require_once 'Producto.php';

/*
 * ================================================================
 *  5 · CLASE Tienda  (un objeto que GUARDA un array de objetos)  (~15 min)
 * ================================================================
 *  Es la misma idea que cuando un método de AccesoDatos te devuelve
 *  un array de Empleado y tú lo recorres con foreach.
 * ================================================================
 */
class Tienda
{
    private $nombre;
    private $productos = [];   // array de objetos Producto

    public function __construct(string $nombre)
    {
        // TODO
    }

    public function __get($atributo)
    {
        if (property_exists($this, $atributo)) {
            return $this->$atributo;
        }
    }

    /** Añade un producto al array. */
    public function agregar(Producto $p)
    {
        // TODO
    }

    /** Número de productos distintos. */
    public function numProductos(): int
    {
        return 0;
    }

    /** Suma del valorInventario() de todos (usa el método del Producto). */
    public function totalInventario(): float
    {
        return 0;
    }

    /** Nombres de los productos SIN stock (array de strings). */
    public function sinStock(): array
    {
        return [];
    }

    /** Busca por nombre. Devuelve el objeto Producto o null si no está. */
    public function buscar(string $nombre)
    {
        return null;
    }

    /** El objeto Producto más caro. */
    public function masCaro()
    {
        return null;
    }
}
