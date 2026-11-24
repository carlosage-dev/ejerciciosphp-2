<?php
require_once __DIR__ . "/Empleado.php";

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

    // =====================================================================
    //  MÉTODOS A DESARROLLAR
    // =====================================================================

    /** @return Empleado|false  (OJO: solo por código, la clave la compruebas en PHP) */
    public function getEmpleado(string $cod_emp)
    {
        return false;
    }

    public function incrementarAccesos(string $cod_emp): bool
    {
        return false;
    }

    public function bloquearEmpleado(string $cod_emp): bool
    {
        return false;
    }

    /** @return array de Empleado del departamento ordenados por nombre */
    public function getEmpleadosDepartamento(int $cod_dep): array
    {
        return [];
    }

    /**
     * Sube el sueldo un porcentaje HACIENDO EL CÁLCULO EN SQL (sueldo = sueldo * ...)
     * (En el Ej2 lo calculabas en PHP: practica la otra forma)
     */
    public function subirSueldo(string $cod_emp, float $porcentaje): bool
    {
        return false;
    }

    public function cambiarClave(string $cod_emp, string $nuevaClave): bool
    {
        return false;
    }
}
