<?php

    class Producto{
        private $producto_no;
        private  $descripcion;
        private $precio_actual;
        private $stock_disponible;

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