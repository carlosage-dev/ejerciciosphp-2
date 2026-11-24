<?php

    class Socio{
        private $cod_socio;
        private $nombre;
        private $clave;
        private $cantidad_max;

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

    


?>