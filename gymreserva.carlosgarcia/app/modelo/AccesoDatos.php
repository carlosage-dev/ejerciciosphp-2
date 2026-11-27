<?php
require_once "Clase.php";
require_once "Socio.php";

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
    
    // CAMBIAR LA CONTRASEÑA
    private function __construct(){
        
        try {
            $dsn = "mysql:host=localhost;dbname=gestionfitness;charset=utf8";
            $this->dbh = new PDO($dsn, "root", "root");
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e){
            echo "Error de conexión ".$e->getMessage();
            exit();
        }
        // Construyo la consulta
        
        
    }

    public function __clone()
    {
        trigger_error('La clonación no permitida', E_USER_ERROR);
    }

    public function getSocio(string $cod_soc){
        $soc = false;

        $stmt = $this->dbh->prepare("select * from Socio where codigo_socio = :codigo_socio");
		$stmt->bindValue(":codigo_socio", $cod_soc);
		$stmt->setFetchMode(PDO::FETCH_CLASS, 'Socio');
		if ( $stmt->execute() ){
			$soc = $stmt->fetch();
		}

        return $soc;
    }

    public function getActividad(string $nomActividad){
        $acti = false;

        $stmt = $this->dbh->prepare("select * from Clase where actividad = :actividad");
		$stmt->bindValue(":actividad", $nomActividad);
		$stmt->setFetchMode(PDO::FETCH_CLASS, 'Clase');
		if ( $stmt->execute() ){
			$acti = $stmt->fetch();
		}

        return $acti;
    }

    public function incrementarContadorSocio(string $cod_soc): bool
    {
        $stmt = $this->dbh->prepare("update Socio set contador_asistencias = contador_asistencias + 1 where codigo_socio = :codigo_socio");
        $stmt->bindValue(":codigo_socio", $cod_soc);
        $stmt->execute();
		if ($stmt->rowCount()===1){
            return true;
        }
        return false;
    }

    public function decrementarPlazas(int $cod_act): bool
    {
        $stmt = $this->dbh->prepare("update Clase set plazas_libres = plazas_libres -1 where codigo_clase = :codigo_clase");
        $stmt->bindValue(":codigo_clase", $cod_act);
        $stmt->execute();
		if ($stmt->rowCount()===1){
            return true;
        }
        return false;
    }


}