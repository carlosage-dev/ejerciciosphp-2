<?php
/*
 * ================================================================
 *  5 · CLASE Producto                                    (~15 min)
 * ================================================================
 *  Atributos privados: nombre, precio, stock
 *  - Constructor que recibe los 3 valores
 *  - __get y __set mágicos (como en tus clases de siempre)
 *  - Métodos: rellénalos. Usan $this->...
 * ================================================================
 */
class Producto
{
    private $nombre;
    private $precio;
    private $stock;

    public function __construct(string $nombre, float $precio, int $stock)
    {
        // TODO
    }

    public function __get($atributo)
    {
        // TODO
    }

    public function __set($atributo, $valor)
    {
        // TODO
    }

    /** Precio con el 21% de IVA, redondeado a 2 decimales. */
    public function precioConIva(): float
    {
        return 0;
    }

    /** true si queda al menos 1 unidad. */
    public function hayStock(): bool
    {
        return false;
    }

    /**
     * Vende $cantidad unidades: si hay suficientes, resta del stock y devuelve true.
     * Si no hay suficientes, NO toca el stock y devuelve false.
     */
    public function vender(int $cantidad): bool
    {
        return false;
    }

    /** Dinero que hay en el almacén de este producto: precio * stock */
    public function valorInventario(): float
    {
        return 0;
    }

    /** Texto: "Teclado - 25,00 € (10 uds.)"   (number_format) */
    public function descripcion(): string
    {
        return "";
    }
}
