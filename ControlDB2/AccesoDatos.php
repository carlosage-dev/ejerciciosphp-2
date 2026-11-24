<?php
require_once "Producto.php";
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
            $dsn = "mysql:host=localhost;dbname=almacendb;charset=utf8";
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

    

    public function getProductos ():array{
        $tablaresu=[];
        $stmt = $this->dbh->prepare("Select * from productos where stock_disponible >10");
        $stmt->setFetchMode(PDO::FETCH_CLASS,"Producto");
        if ($stmt->execute()){
            $tablaresu = $stmt->fetchAll();
        }


        return $tablaresu;
    }

    public function actualizarPrecios($lista):int{
        $cont =0;
        $stmt = $this->dbh->prepare("UPDATE Productos SET precio_actual=precio_actual*0.9 where producto_no = ?");
        // Devuelvo una tabla de objetos
        foreach ($lista as $producto_no){
             $stmt->bindValue(1, $producto_no);
             if ( $stmt->execute() ){
                 $cont++;
             }
             }
        return $cont;
    }


  
}

