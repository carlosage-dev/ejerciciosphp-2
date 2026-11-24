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
     * Resumen de TODOS los departamentos (también sin empleados) con:
     *   cod_dep, nombre, localidad, presupuesto, num_empleados, gasto, media
     * @param string $columnaOrden  YA validada con la lista blanca (p.ej. "d.nombre ASC")
     * @return array de arrays ASOCIATIVOS  (FETCH_ASSOC)
     */
    public function getResumen(string $columnaOrden): array
    {
        return [];
    }

    /**
     * TODO 2
     * @return Departamento|false
     */
    public function getDepartamento(int $cod_dep)
    {
        return false;
    }

    /**
     * TODO 3
     * @return array de Empleado ordenados por sueldo DESC
     */
    public function getEmpleadosDep(int $cod_dep): array
    {
        return [];
    }

    /**
     * TODO 4
     * Suma de sueldos de un departamento (0 si no tiene empleados).
     * La necesitas para el % y para volver a comprobar en el POST.
     */
    public function getGastoDep(int $cod_dep): float
    {
        return 0;
    }

    /**
     * TODO 5
     */
    public function ampliarPresupuesto(int $cod_dep, float $nuevoPresupuesto): bool
    {
        return false;
    }
}
