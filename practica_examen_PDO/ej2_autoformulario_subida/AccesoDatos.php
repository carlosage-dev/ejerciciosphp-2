<?php
require_once "Empleado.php";
require_once "Departamento.php";

class AccesoDatos
{
    private static $modelo = null;
    private $dbh = null;

    public static function initModelo()
    {
        if (self::$modelo == null) {
            self::$modelo = new AccesoDatos();
        }
        return self::$modelo;
    }

    private function __construct()
    {
        try {
            $dsn = "mysql:host=localhost;dbname=empresadb;charset=utf8";
            $this->dbh = new PDO($dsn, "root", "");
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Error de conexión " . $e->getMessage();
            exit();
        }
    }

    public function __clone()
    {
        trigger_error('La clonación no permitida', E_USER_ERROR);
    }

    // =====================================================================
    //  MÉTODOS A DESARROLLAR
    // =====================================================================

    /**
     * TODO 1
     * @return array de objetos Departamento ordenados por nombre
     */
    public function getDepartamentos(): array
    {
        $tablaresu=[];
        $stmt = $this->dbh->prepare("Select * from departamentos order by cod_dep, nombre");
        $stmt->setFetchMode(PDO::FETCH_CLASS,"Departamento");
        if ($stmt->execute()){
            $tablaresu = $stmt->fetchAll();
        }


        return $tablaresu;
    }

    /**
     * TODO 2
     * Si $cod_dep es null devuelve TODOS los empleados; si no, solo los de ese departamento.
     * Ordenados por cod_dep y luego por nombre.
     * (¿Una sola consulta o dos? Tú decides. Las dos formas valen.)
     * @return array de objetos Empleado
     */
    public function getEmpleados(?int $cod_dep): array
    {   
        $tabla = [];
        if ($cod_dep ==null){
            $stmt = $this->dbh->prepare("select * from empleados");
            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Empleado');
            if ( $stmt->execute() ){
			$tabla = $stmt->fetchAll();
        }   else{

            $stmt = $this->dbh->prepare("select * from empleados where cod_dep = :cod_dep");
            $stmt->bindValue(":cod_dep", $cod_dep);
            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Empleado');
		    if ( $stmt->execute() ){
			    $tabla = $stmt->fetchAll();
		    }

        }
        
        
    }
    return $tabla;
    }

    /**
     * TODO 3
     * @return Empleado|false
     */
    public function getEmpleado(string $cod_emp)
    {
        $emp= null;
        $stmt = $this->dbh->prepare("select * from empleados where cod_emp = :cod_emp");
        $stmt->bindValue(":cod_emp",$cod_emp);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Empleado');
        if ( $stmt->execute() ){
			$emp = $stmt->fetch();
		}
        return $emp;
    }

    /**
     * TODO 4
     * Pone al empleado el sueldo que se le pasa (el cálculo NO se hace aquí).
     * @return bool true si se ha modificado la fila
     */
    public function actualizarSueldo(string $cod_emp, float $nuevoSueldo): bool
    {
        $actualizado = false;
        $stmt = $this->dbh->prepare("update empleados set sueldo = :nuevoSueldo where cod_emp = :cod_emp");
        $stmt->bindValue(":nuevoSueldo",$nuevoSueldo);
        $stmt->bindValue(":cod_emp",$cod_emp);
        $stmt->execute();
        if ($stmt->rowCount()===1){
            $actualizado= true;
        }

        return $actualizado;

    }
}
