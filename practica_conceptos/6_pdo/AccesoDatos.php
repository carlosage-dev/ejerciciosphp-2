<?php
require_once __DIR__ . "/Empleado.php";   // __DIR__ = la carpeta de este archivo
require_once __DIR__ . "/Departamento.php";

/*
 * ================================================================
 *  6 · PDO PURO                               (~5 min cada método)
 * ================================================================
 *  El Singleton ya está. Haz cada método con el esquema de siempre:
 *     prepare -> bindValue (si hay parámetros) -> setFetchMode -> execute -> fetch / fetchAll
 *  y para INSERT / UPDATE / DELETE:
 *     prepare -> bindValue -> execute -> rowCount
 *
 *  Comprueba con:
 *     pruebas_lectura.php  (consultas SELECT + métodos de los objetos)
 *     modificar.php        (INSERT / UPDATE / DELETE)
 *  Antes de empezar, ejecuta empresadb.sql en phpMyAdmin.
 * ================================================================
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

    // =====================================================================
    //  SELECT
    // =====================================================================

    /** 1. Todos los departamentos ordenados por nombre. @return array de Departamento */
    public function getDepartamentos(): array
    {
        return [];
    }

    /** 2. Todos los empleados ordenados por cod_emp. @return array de Empleado */
    public function getTodos(): array
    {
        return [];
    }

    /** 3. Empleados de un departamento. @return array de Empleado */
    public function getEmpleadosDep(int $cod_dep): array
    {
        return [];
    }

    /** 4. Un empleado por su código. @return Empleado|false */
    public function getEmpleado(string $cod_emp)
    {
        return false;
    }

    /** 5. Empleados de un puesto ("Becario", "Programador"...). @return array de Empleado */
    public function getEmpleadosPuesto(string $puesto): array
    {
        return [];
    }

    /**
     * 6. Número total de empleados.
     *    SELECT COUNT(*) AS total ...  -> aquí NO hay objeto: usa PDO::FETCH_ASSOC
     *    y lee $fila['total']
     */
    public function contarEmpleados(): int
    {
        return 0;
    }

    // =====================================================================
    //  INSERT / UPDATE / DELETE
    // =====================================================================

    /**
     * 7. Inserta un departamento (cod_dep es AUTO_INCREMENT: no lo pongas en el INSERT).
     *    Recibe un OBJETO: saca los datos con $dep->nombre, $dep->localidad...
     *    @return bool true si se ha insertado
     */
    public function insertarDepartamento(Departamento $dep): bool
    {
        return false;
    }

    /**
     * 8. Sube un % el sueldo a TODOS los de un puesto, con un solo UPDATE
     *    (el cálculo en el SQL: sueldo = sueldo * ...)
     *    @return int cuántos empleados se han actualizado (rowCount)
     */
    public function subirSueldoPuesto(string $puesto, float $porcentaje): int
    {
        return 0;
    }

    /**
     * 9. Borra un departamento.
     *    Si tiene empleados, MySQL NO deja borrarlo (clave foránea) y lanza
     *    una PDOException. Captúrala con try/catch y devuelve false.
     *    @return bool
     */
    public function borrarDepartamento(int $cod_dep): bool
    {
        return false;
    }

    // =====================================================================
    //  PARA EL BLOQUE 7 (los combinados usan ESTE AccesoDatos)
    // =====================================================================

    /** 10. accesos = accesos + 1.  @return bool */
    public function incrementarAccesos(string $cod_emp): bool
    {
        return false;
    }

    /** 11. Pone bloqueado = $valor (0 ó 1) a ese empleado.  @return bool true si ha cambiado */
    public function cambiarBloqueo(string $cod_emp, int $valor): bool
    {
        return false;
    }
}
