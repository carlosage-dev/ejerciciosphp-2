<?php

    class Producto{
        private $cod_pro;
        private $nombre_pro;
        private $precio;

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