<?php
class Socio {

    private $codigo_socio;

    private $nombre;

    private $contador_asistencias;

    private $bloqueado;

    public function __get($name){
        if (property_exists($this,$name)){
            return $this->$name;
        }else{
            return "ERROR ATRIBUTO";
        }
    }

    public function __set($name, $value){
        if (property_exists($this,$name)){
            $this->$name = $value;
    }
}

}
