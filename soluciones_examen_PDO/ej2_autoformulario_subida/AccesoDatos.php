<?php
require_once "Empleado.php";
require_once "Departamento.php";

/*
 * SOLUCIÓN EJ2 · Acceso a datos
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

    /** Departamentos ordenados por nombre */
    public function getDepartamentos(): array
    {
        $tabla = [];
        $stmt = $this->dbh->prepare("SELECT * FROM departamentos ORDER BY nombre");
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Departamento');
        if ($stmt->execute()) {
            $tabla = $stmt->fetchAll();
        }
        return $tabla;
    }

    /**
     * null -> todos los empleados; número -> solo los de ese departamento.
     * El if/else solo ELIGE la consulta; lo demás es igual para las dos.
     */
    public function getEmpleados(?int $cod_dep): array
    {
        $tabla = [];
        if ($cod_dep === null) {
            $stmt = $this->dbh->prepare("SELECT * FROM empleados ORDER BY cod_dep, nombre");
        } else {
            $stmt = $this->dbh->prepare("SELECT * FROM empleados WHERE cod_dep = :cod_dep ORDER BY nombre");
            $stmt->bindValue(":cod_dep", $cod_dep);
        }
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Empleado');
        if ($stmt->execute()) {
            $tabla = $stmt->fetchAll();
        }
        return $tabla;
    }

    /** Un empleado o false */
    public function getEmpleado(string $cod_emp)
    {
        $emp = false;
        $stmt = $this->dbh->prepare("SELECT * FROM empleados WHERE cod_emp = :cod_emp");
        $stmt->bindValue(":cod_emp", $cod_emp);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Empleado');
        if ($stmt->execute()) {
            $emp = $stmt->fetch();
        }
        return $emp;
    }

    /** Pone el sueldo indicado (el cálculo se hace fuera, en PHP) */
    public function actualizarSueldo(string $cod_emp, float $nuevoSueldo): bool
    {
        $stmt = $this->dbh->prepare("UPDATE empleados SET sueldo = :sueldo WHERE cod_emp = :cod_emp");
        $stmt->bindValue(":sueldo", $nuevoSueldo);
        $stmt->bindValue(":cod_emp", $cod_emp);
        $stmt->execute();
        if ($stmt->rowCount() == 1) {
            return true;
        }
        return false;
    }
}
