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
        return [];
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
        return [];
    }

    /**
     * TODO 3
     * @return Empleado|false
     */
    public function getEmpleado(string $cod_emp)
    {
        return false;
    }

    /**
     * TODO 4
     * Pone al empleado el sueldo que se le pasa (el cálculo NO se hace aquí).
     * @return bool true si se ha modificado la fila
     */
    public function actualizarSueldo(string $cod_emp, float $nuevoSueldo): bool
    {
        return false;
    }
}
