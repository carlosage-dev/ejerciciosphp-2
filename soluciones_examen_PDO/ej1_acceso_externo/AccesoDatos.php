<?php
require_once "Empleado.php";
require_once "Departamento.php";

/*
 * SOLUCIÓN EJ1 · Acceso a datos (cada método prepara su sentencia)
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

    /** Empleado con ese código Y esa clave, o false */
    public function getEmpleadoLogin(string $cod_emp, string $clave)
    {
        $emp = false;
        $stmt = $this->dbh->prepare("SELECT * FROM empleados WHERE cod_emp = :cod_emp AND clave = :clave");
        $stmt->bindValue(":cod_emp", $cod_emp);
        $stmt->bindValue(":clave", $clave);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Empleado');
        if ($stmt->execute()) {
            $emp = $stmt->fetch();          // fetch() devuelve false si no hay fila
        }
        return $emp;
    }

    /** accesos + 1 */
    public function incrementarAccesos(string $cod_emp): bool
    {
        $stmt = $this->dbh->prepare("UPDATE empleados SET accesos = accesos + 1 WHERE cod_emp = :cod_emp");
        $stmt->bindValue(":cod_emp", $cod_emp);
        $stmt->execute();
        if ($stmt->rowCount() == 1) {
            return true;
        }
        return false;
    }

    /** Departamento o false */
    public function getDepartamento(int $cod_dep)
    {
        $dep = false;
        $stmt = $this->dbh->prepare("SELECT * FROM departamentos WHERE cod_dep = :cod_dep");
        $stmt->bindValue(":cod_dep", $cod_dep);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Departamento');
        if ($stmt->execute()) {
            $dep = $stmt->fetch();
        }
        return $dep;
    }

    /** Empleados del departamento, de mayor a menor sueldo */
    public function getEmpleadosDepartamento(int $cod_dep): array
    {
        $tabla = [];
        $stmt = $this->dbh->prepare("SELECT * FROM empleados WHERE cod_dep = :cod_dep ORDER BY sueldo DESC");
        $stmt->bindValue(":cod_dep", $cod_dep);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Empleado');
        if ($stmt->execute()) {
            $tabla = $stmt->fetchAll();
        }
        return $tabla;
    }
}
