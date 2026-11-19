<?php

    class Producto{
        private int $producto_no;
        private string $descripcion;
        private float $precio_anual;
        private int $stock_disponible;

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