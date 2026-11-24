<?php

class Departamento
{
    private $cod_dep;
    private $nombre;
    private $localidad;
    private $presupuesto;

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
