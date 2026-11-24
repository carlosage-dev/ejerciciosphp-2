<?php
// __DIR__ = carpeta donde está ESTE archivo (app/). Así el include funciona
// da igual desde qué archivo se llame.
require_once __DIR__ . "/config.php";
require_once __DIR__ . "/Empleado.php";
require_once __DIR__ . "/Departamento.php";

/*
 * Acceso a datos con PDO y patrón Singleton.
 * Puedes elegir:
 *   a) preparar las sentencias en el constructor y guardarlas en atributos (como examII)
 *   b) preparar cada sentencia dentro de su método (como BD_PDO_Tareas / ControlDB)
 * Practica la que MENOS hayas usado.
 */
class AccesoDatos
{
    private static $modelo = null;
    private $dbh = null;

    // Si eliges la opción a), declara aquí los atributos $stmt_...

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
        // Opción a): prepara aquí las sentencias
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

    // =====================================================================
    //  MÉTODOS A DESARROLLAR
    // =====================================================================

    /** @return array de Empleado ordenados por cod_dep y nombre */
    public function getEmpleados(): array
    {
        return [];
    }

    /** @return Empleado|false */
    public function getEmpleado(string $cod_emp)
    {
        return false;
    }

    /** INSERT con TODOS los campos de la tabla. @return bool */
    public function addEmpleado(Empleado $emp): bool
    {
        return false;
    }

    /** UPDATE de nombre, clave, puesto, sueldo y cod_dep. @return bool */
    public function modEmpleado(Empleado $emp): bool
    {
        return false;
    }

    /** @return bool */
    public function borrarEmpleado(string $cod_emp): bool
    {
        return false;
    }

    /** @return array de Departamento ordenados por nombre */
    public function getDepartamentos(): array
    {
        return [];
    }

    /**
     * Invierte el bloqueo de cada empleado de la lista (0->1, 1->0).
     * ¿Se puede hacer con un único UPDATE sin consultar antes? Piénsalo.
     * @param array $listaCod códigos de empleado
     * @return int nº de empleados modificados
     */
    public function cambiarBloqueo(array $listaCod): int
    {
        return 0;
    }

    /**
     * Cambia el departamento de todos los empleados de la lista.
     * @return int nº de empleados modificados
     */
    public function trasladar(array $listaCod, int $cod_dep): int
    {
        return 0;
    }
}
