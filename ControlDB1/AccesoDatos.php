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

    public function hayCliente(string $nombre, string $clave): mixed{
        $resu = false;
        $stmt = $this->dbh->prepare("Select * from clientes where nombre = :nombre and clave = :clave");
        $stmt->bindValue(":nombre", $nombre);
        $stmt->bindValue(":clave", $clave);
        $stmt->setFetchMode(PDO::FETCH_CLASS,"Cliente");
        if ($stmt->execute()){
           $resu = $stmt->fetch();
        }
        return $resu;
    }

    public function getPedidosCliente (int $cod_cliente){
        $tablapedido=[];
        $stmt = $this->dbh->prepare("Select * from pedidos where cod_cliente = :cod_cliente");
        $stmt->bindValue(":cod_cliente", $cod_cliente);
        $stmt->setFetchMode(PDO::FETCH_CLASS,"Pedido");
        if ($stmt->execute()){
            $tablaresu = $stmt->fetchAll();
        }


        return $tablaresu;
    }


  
}

