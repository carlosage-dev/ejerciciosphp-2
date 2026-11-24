<?php

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

    // Getter con método mágico
    public function __get($atributo)
    {
        if (property_exists($this, $atributo)) {
            return $this->$atributo;
        }
    }

    // Setter con método mágico
    public function __set($atributo, $valor)
    {
        if (property_exists($this, $atributo)) {
            $this->$atributo = $valor;
        }
    }
}
