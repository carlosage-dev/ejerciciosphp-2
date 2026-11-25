<?php

/**
 * Un atributo PRIVADO por cada columna de la tabla empleados
 * (mismo nombre que la columna para que FETCH_CLASS los rellene)
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
}
