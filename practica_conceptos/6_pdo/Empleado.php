<?php
/*
 * Clase Empleado: los atributos y __get/__set ya están.
 * TODO: los MÉTODOS de abajo. Fíjate en que trabajan con $this->...
 * Cuando PDO te devuelve un Empleado con FETCH_CLASS, ya puedes llamar
 * a estos métodos:   $emp->sueldoFormateado()
 *
 * OJO: PDO te trae los números como STRING ("3200.00").
 *      Si necesitas un número de verdad: (float) $this->sueldo
 */
class Empleado
{
    private $cod_emp;
    private $nombre;
    private $clave;
    private $puesto;
    private $sueldo;
    private $cod_dep;
    private $bloqueado;
    private $accesos;
    private $fecha_alta;

    public function __get($atributo)
    {
        if (property_exists($this, $atributo)) {
            return $this->$atributo;
        }
    }

    public function __set($atributo, $valor)
    {
        if (property_exists($this, $atributo)) {
            $this->$atributo = $valor;
        }
    }

    // =====================================================================
    //  MÉTODOS A DESARROLLAR
    // =====================================================================

    /** "3.200,00 €" */
    public function sueldoFormateado(): string
    {
        return "";
    }

    /** true si el puesto es "Becario" */
    public function esBecario(): bool
    {
        return false;
    }

    /** true si bloqueado vale 1 */
    public function estaBloqueado(): bool
    {
        return false;
    }

    /** Sueldo de todo el año: 14 pagas */
    public function sueldoAnual(): float
    {
        return 0;
    }

    /**
     * Cuánto cobraría con una subida de $porcentaje, redondeado a 2 decimales.
     * NO cambia el sueldo del objeto, solo lo calcula.
     */
    public function sueldoConSubida(float $porcentaje): float
    {
        return 0;
    }
}
