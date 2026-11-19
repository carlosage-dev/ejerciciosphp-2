<?php
require_once "Cliente.php";
require_once "Pedido.php";
//ATENCIONOOOOOOOOOOOOOO, SI TRABAJASEMOS CON SESION DEBERIAMOS PONERLO EN TODOS LOS ARCHIVOS EXCEPTO SI LO PONEMOS EN EL INDEX
/*
 * Acceso a datos con BD y Patrón Singleton
 */
class AccesoDatos {
    
    private static $modelo = null;
    private $dbh = null;
    private $stmt = null;
    
    public static function initModelo(){
        if (self::$modelo == null){
            self::$modelo = new AccesoDatos();
        }
        return self::$modelo;
    }
    
    
    private function __construct(){
        // Cambiar le password !!!!!
        try {
            $dsn = "mysql:host=localhost;dbname=etienda;charset=utf8";
            $this->dbh = new PDO($dsn, "root", "");
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e){
            echo "Error de conexión ".$e->getMessage();
            exit();
        }
          
    }

     // Evito que se pueda clonar el objeto.
    public function __clone()
    { 
        trigger_error('La clonación no permitida', E_USER_ERROR); 
    }

    public function getPedidosCliente (int $cod_cliente){
        $tablapedido=[];
        $stmt = $this->dbh->prepare("Select cod_cliente,producto,precio from pedidos where cod_cliente = :cod_cliente");
        $stmt->bindValue(":cod_cliente", $cod_cliente);
        $stmt->setFetchMode(PDO::FETCH_CLASS,"Pedido");
        if ($stmt->execute()){
            $tablapedido = $stmt->fetchAll();
        }


        return $tablapedido;
    }

    public function actualizarAccesoCliente(int $cod_cliente){
        $stmt = $this->dbh->prepare("Update clientes set veces = veces + 1 where cod_cliente = :cod_cliente");
        $stmt->bindValue(":cod_cliente", $cod_cliente);
        $stmt->execute();
    }


  
}

