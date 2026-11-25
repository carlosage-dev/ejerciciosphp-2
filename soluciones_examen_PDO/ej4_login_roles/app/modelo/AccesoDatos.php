<?php
require_once __DIR__ . "/Empleado.php";

/*
 * SOLUCIÓN EJ4 · Acceso a datos
 */
class AccesoDatos
{
    private static $modelo = null;
    private $dbh = null;

    public static function getModelo()
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

    /** Solo por código: la clave se compara en PHP para poder contar los fallos */
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

    public function incrementarAccesos(string $cod_emp): bool
    {
        $stmt = $this->dbh->prepare("UPDATE empleados SET accesos = accesos + 1 WHERE cod_emp = :cod_emp");
        $stmt->bindValue(":cod_emp", $cod_emp);
        $stmt->execute();
        return $stmt->rowCount() == 1;
    }

    public function bloquearEmpleado(string $cod_emp): bool
    {
        $stmt = $this->dbh->prepare("UPDATE empleados SET bloqueado = 1 WHERE cod_emp = :cod_emp");
        $stmt->bindValue(":cod_emp", $cod_emp);
        $stmt->execute();
        return $stmt->rowCount() == 1;
    }

    public function getEmpleadosDepartamento(int $cod_dep): array
    {
        $tabla = [];
        $stmt = $this->dbh->prepare("SELECT * FROM empleados WHERE cod_dep = :cod_dep ORDER BY nombre");
        $stmt->bindValue(":cod_dep", $cod_dep);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Empleado');
        if ($stmt->execute()) {
            $tabla = $stmt->fetchAll();
        }
        return $tabla;
    }

    /**
     * El cálculo se hace en el SQL.
     * sueldo * (100 + 5) / 100  es lo mismo que  sueldo * 1.05
     */
    public function subirSueldo(string $cod_emp, float $porcentaje): bool
    {
        $stmt = $this->dbh->prepare("UPDATE empleados SET sueldo = sueldo * (100 + :porcentaje) / 100 WHERE cod_emp = :cod_emp");
        $stmt->bindValue(":porcentaje", $porcentaje);
        $stmt->bindValue(":cod_emp", $cod_emp);
        $stmt->execute();
        return $stmt->rowCount() == 1;
    }

    public function cambiarClave(string $cod_emp, string $nuevaClave): bool
    {
        $stmt = $this->dbh->prepare("UPDATE empleados SET clave = :clave WHERE cod_emp = :cod_emp");
        $stmt->bindValue(":clave", $nuevaClave);
        $stmt->bindValue(":cod_emp", $cod_emp);
        $stmt->execute();
        return $stmt->rowCount() == 1;
    }
}
