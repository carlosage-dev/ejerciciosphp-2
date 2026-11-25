<?php
require_once __DIR__ . "/config.php";
require_once __DIR__ . "/Empleado.php";
require_once __DIR__ . "/Departamento.php";

/*
 * SOLUCIÓN EJ3 · Acceso a datos
 * Aquí se ha elegido la opción b): cada método prepara su sentencia.
 * (La opción a), la de examII, sería preparar todas en el constructor
 *  y guardarlas en atributos $this->stmt_...)
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
            $dsn = "mysql:host=" . SERVER_DB . ";dbname=" . DATABASE . ";charset=utf8";
            $this->dbh = new PDO($dsn, DB_USER, DB_PASSWD);
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Error de conexión " . $e->getMessage();
            exit();
        }
    }

    public static function closeModelo()
    {
        if (self::$modelo != null) {
            $obj = self::$modelo;
            $obj->dbh = null;
            self::$modelo = null;
        }
    }

    public function __clone()
    {
        trigger_error('La clonación no permitida', E_USER_ERROR);
    }

    public function getEmpleados(): array
    {
        $tabla = [];
        $stmt = $this->dbh->prepare("SELECT * FROM empleados ORDER BY cod_dep, nombre");
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Empleado');
        if ($stmt->execute()) {
            $tabla = $stmt->fetchAll();
        }
        return $tabla;
    }

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

    public function addEmpleado(Empleado $emp): bool
    {
        $stmt = $this->dbh->prepare(
            "INSERT INTO empleados (cod_emp, nombre, clave, puesto, sueldo, cod_dep, bloqueado, accesos, fecha_alta)
             VALUES (:cod_emp, :nombre, :clave, :puesto, :sueldo, :cod_dep, :bloqueado, :accesos, :fecha_alta)"
        );
        $stmt->bindValue(":cod_emp", $emp->cod_emp);
        $stmt->bindValue(":nombre", $emp->nombre);
        $stmt->bindValue(":clave", $emp->clave);
        $stmt->bindValue(":puesto", $emp->puesto);
        $stmt->bindValue(":sueldo", $emp->sueldo);
        $stmt->bindValue(":cod_dep", $emp->cod_dep);
        $stmt->bindValue(":bloqueado", $emp->bloqueado);
        $stmt->bindValue(":accesos", $emp->accesos);
        $stmt->bindValue(":fecha_alta", $emp->fecha_alta);
        $stmt->execute();
        return $stmt->rowCount() == 1;
    }

    public function modEmpleado(Empleado $emp): bool
    {
        $stmt = $this->dbh->prepare(
            "UPDATE empleados SET nombre = :nombre, clave = :clave, puesto = :puesto,
                    sueldo = :sueldo, cod_dep = :cod_dep
             WHERE cod_emp = :cod_emp"
        );
        $stmt->bindValue(":nombre", $emp->nombre);
        $stmt->bindValue(":clave", $emp->clave);
        $stmt->bindValue(":puesto", $emp->puesto);
        $stmt->bindValue(":sueldo", $emp->sueldo);
        $stmt->bindValue(":cod_dep", $emp->cod_dep);
        $stmt->bindValue(":cod_emp", $emp->cod_emp);
        $stmt->execute();
        // Ojo: si no cambias nada, MySQL devuelve 0 filas afectadas
        return $stmt->rowCount() == 1;
    }

    public function borrarEmpleado(string $cod_emp): bool
    {
        $stmt = $this->dbh->prepare("DELETE FROM empleados WHERE cod_emp = :cod_emp");
        $stmt->bindValue(":cod_emp", $cod_emp);
        $stmt->execute();
        return $stmt->rowCount() == 1;
    }

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
     * Invierte el bloqueo con UN solo UPDATE por empleado y sin consultar antes:
     * si vale 0 -> 1 - 0 = 1 ; si vale 1 -> 1 - 1 = 0
     */
    public function cambiarBloqueo(array $listaCod): int
    {
        $cont = 0;
        $stmt = $this->dbh->prepare("UPDATE empleados SET bloqueado = 1 - bloqueado WHERE cod_emp = :cod_emp");
        foreach ($listaCod as $cod) {
            $stmt->bindValue(":cod_emp", $cod);
            $stmt->execute();
            $cont += $stmt->rowCount();
        }
        return $cont;
    }

    public function trasladar(array $listaCod, int $cod_dep): int
    {
        $cont = 0;
        $stmt = $this->dbh->prepare("UPDATE empleados SET cod_dep = :cod_dep WHERE cod_emp = :cod_emp");
        foreach ($listaCod as $cod) {
            $stmt->bindValue(":cod_dep", $cod_dep);
            $stmt->bindValue(":cod_emp", $cod);
            $stmt->execute();
            $cont += $stmt->rowCount();
        }
        return $cont;
    }
}
