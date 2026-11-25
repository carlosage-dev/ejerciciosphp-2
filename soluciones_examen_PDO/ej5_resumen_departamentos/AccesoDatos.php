<?php
require_once "Empleado.php";
require_once "Departamento.php";

/*
 * SOLUCIÓN EJ5 · Acceso a datos
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

    /**
     * LEFT JOIN: salen TODOS los departamentos, también los que no tienen empleados.
     * COUNT(e.cod_emp) cuenta 0 en Marketing (COUNT(*) contaría 1: ¡ojo!).
     * COALESCE(SUM(...), 0): si no hay empleados SUM da NULL -> lo cambia por 0.
     * $columnaOrden viene YA validada por la lista blanca: por eso se puede concatenar.
     */
    public function getResumen(string $columnaOrden): array
    {
        $sql = "SELECT d.cod_dep, d.nombre, d.localidad, d.presupuesto,
                       COUNT(e.cod_emp)          AS num_empleados,
                       COALESCE(SUM(e.sueldo), 0) AS gasto,
                       AVG(e.sueldo)             AS media
                FROM departamentos d
                LEFT JOIN empleados e ON d.cod_dep = e.cod_dep
                GROUP BY d.cod_dep, d.nombre, d.localidad, d.presupuesto
                ORDER BY " . $columnaOrden;

        $tabla = [];
        $stmt = $this->dbh->prepare($sql);
        if ($stmt->execute()) {
            $tabla = $stmt->fetchAll(PDO::FETCH_ASSOC);   // arrays asociativos, no objetos
        }
        return $tabla;
    }

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

    public function getEmpleadosDep(int $cod_dep): array
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

    public function getGastoDep(int $cod_dep): float
    {
        $stmt = $this->dbh->prepare("SELECT COALESCE(SUM(sueldo), 0) AS gasto FROM empleados WHERE cod_dep = :cod_dep");
        $stmt->bindValue(":cod_dep", $cod_dep);
        $stmt->execute();
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);
        return (float) $fila['gasto'];
    }

    public function ampliarPresupuesto(int $cod_dep, float $nuevoPresupuesto): bool
    {
        $stmt = $this->dbh->prepare("UPDATE departamentos SET presupuesto = :presupuesto WHERE cod_dep = :cod_dep");
        $stmt->bindValue(":presupuesto", $nuevoPresupuesto);
        $stmt->bindValue(":cod_dep", $cod_dep);
        $stmt->execute();
        return $stmt->rowCount() == 1;
    }
}
