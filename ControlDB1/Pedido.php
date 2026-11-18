<?php
    class Pedido{
        private int $numped;
        private string $cod_cliente;
        private string $producto;
        private int $precio;

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