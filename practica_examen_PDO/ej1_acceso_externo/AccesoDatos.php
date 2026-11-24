<?php
require_once "Empleado.php";
require_once "Departamento.php";

/*
 * Acceso a datos con PDO y patrón Singleton.
 * En este ejercicio cada método prepara su propia sentencia (como ControlDB1).
 */
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
     * Busca el empleado con ese código y esa clave.
     * @return Empleado|false  objeto Empleado si coinciden, false si no
     */
    public function getEmpleadoLogin(string $cod_emp, string $clave)
    {
        $emp = false;

        $stmt = $this->dbh->prepare("select * from empleados where cod_emp = :cod_emp and clave = :clave");
		$stmt->bindValue(":cod_emp", $cod_emp);
		$stmt->bindValue(":clave", $clave);
		$stmt->setFetchMode(PDO::FETCH_CLASS, 'Empleado');
		if ( $stmt->execute() ){
			$emp = $stmt->fetch();
		}


        return $emp;
    }

    /**
     * TODO 2
     * Suma 1 al campo accesos del empleado.
     * @return bool true si se ha modificado exactamente 1 fila
     */
    public function incrementarAccesos(string $cod_emp): bool
    {
        $stmt = $this->dbh->prepare("update empleados set accesos = accesos + 1 where cod_emp = :cod_emp");
        $stmt->bindValue(":cod_emp", $cod_emp);
        $stmt->execute();
		if ($stmt->rowCount()===1){
            return true;
        }
        return false;
    }

    /**
     * TODO 3
     * @return Departamento|false
     */
    public function getDepartamento(int $cod_dep)
    {
        $dep= false;

        $stmt = $this->dbh->prepare("select * from departamentos where cod_dep = :cod_dep");
		$stmt->bindValue(":cod_dep", $cod_dep);
		$stmt->setFetchMode(PDO::FETCH_CLASS, 'Departamento');
		if ( $stmt->execute() ){
			$dep = $stmt->fetch();
		}


        return $dep;
    }

    /**
     * TODO 4
     * Empleados del departamento, ordenados por sueldo de MAYOR a MENOR.
     * @return array de objetos Empleado (vacío si no hay ninguno)
     */
    public function getEmpleadosDepartamento(int $cod_dep): array
    {
        $tabla = [];
        $stmt = $this->dbh->prepare("select * from empleados where cod_dep = :cod_dep order by sueldo desc");
        $stmt->bindValue(":cod_dep", $cod_dep);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Empleado');
		if ( $stmt->execute() ){
			$tabla = $stmt->fetchAll();
		}
        return $tabla;
    }
}
