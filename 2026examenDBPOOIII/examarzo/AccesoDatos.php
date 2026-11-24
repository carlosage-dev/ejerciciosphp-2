<?php
require_once "Socio.php";
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
            $dsn = "mysql:host=localhost;dbname=comprasdb;charset=utf8";
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

     public function haySocio(String $cod_socio, String $clave):mixed{

		$objSoc = null;
        $stmt = $this->dbh->prepare("select * from socios where cod_socio = :cod_socio and clave = :clave");
		$stmt->bindValue(":cod_socio", $cod_socio);
		$stmt->bindValue(":clave", $clave);
		$stmt->setFetchMode(PDO::FETCH_CLASS, 'Socio');
		if ( $stmt->execute() ){
			$objSoc = $stmt->fetch();
		}
		
		return $objSoc;
	}

    public function hayProducto(String $cod_pro):mixed{

		$objPro = null;
        $stmt = $this->dbh->prepare("select * from productos where cod_pro = :cod_pro");
		$stmt->bindValue(":cod_pro", $cod_pro);
		$stmt->setFetchMode(PDO::FETCH_CLASS, 'Producto');
		if ( $stmt->execute() ){
			$objPro = $stmt->fetch();
		}
		
		return $objPro;
	}


    public function procesarCompra(string $cod_socio, int $dinero_soc, string $precio_pro):bool{
        if ($dinero_soc > $precio_pro){
            $stmt = $this->dbh->prepare("Update socios set cantidad_max = cantidad_max - :precio_pro where cod_socio = :cod_socio");
            $stmt->bindValue(":cod_socio", $cod_socio);
            $stmt->bindValue(":precio_pro", $precio_pro);
            $stmt->execute();
            return true;
        }else{
            return false;
        }
    }


  
}

