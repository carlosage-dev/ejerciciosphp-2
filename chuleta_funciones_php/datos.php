<?php
/*
 * Datos de ejemplo que usan todos los archivos (los tuyos, tal cual).
 */

// Forma antigua de definir un array en PHP: array( ... )
$departamentos = array(
    'Informática'      => array("Jefe" => "Marta Ruiz",   "Presupuesto" => 9000),
    'Ventas'           => array("Jefe" => "Pablo Soler",  "Presupuesto" => 9500),
    'Marketing'        => array("Jefe" => "Lucía Prieto", "Presupuesto" => 7000),
    'Recursos Humanos' => array("Jefe" => "Andrés Gil",   "Presupuesto" => 4000),
    'Logística'        => array("Jefe" => "Irene Cano",   "Presupuesto" => 9000),
);

// Forma moderna, más compacta: [ ... ]
$empleados = [
    'Informática' => [
        ["Nombre" => "Marta Ruiz", "Salario" => 3200],
        ["Nombre" => "Luis Pérez", "Salario" => 2400],
        ["Nombre" => "Sara Vidal", "Salario" => 2600],
        ["Nombre" => "Hugo Mora",  "Salario" => 2100],
    ],
    'Ventas' => [
        ["Nombre" => "Pablo Soler",  "Salario" => 3000],
        ["Nombre" => "Carmen Ortiz", "Salario" => 1900],
        ["Nombre" => "Raúl Díaz",    "Salario" => 1800],
        ["Nombre" => "Elena Ríos",   "Salario" => 2000],
    ],
    'Marketing' => [
        ["Nombre" => "Lucía Prieto", "Salario" => 2800],
        ["Nombre" => "Álvaro Nieto", "Salario" => 2000],
        ["Nombre" => "Nuria Pardo",  "Salario" => 1900],
    ],
    'Recursos Humanos' => [
        ["Nombre" => "Andrés Gil",   "Salario" => 2700],
        ["Nombre" => "Beatriz Lara", "Salario" => 2000],
    ],
    'Logística' => [
        ["Nombre" => "Irene Cano", "Salario" => 2500],
        ["Nombre" => "Tomás Vega", "Salario" => 1700],
        ["Nombre" => "Ana Blanco", "Salario" => 1600],
        ["Nombre" => "Jorge León", "Salario" => 1600],
        ["Nombre" => "Pilar Sanz", "Salario" => 1500],
    ],
];

/*
 * La misma plantilla en forma de LISTA PLANA, con un id para cada empleado.
 * Es lo que usan las tablas con checkbox (cada fila necesita un id único).
 * Se construye recorriendo $empleados con dos foreach (ver 01_recorrer_arrays.php).
 */
function plantillaPlana(array $empleados): array
{
    $plantilla = [];
    $id = 1;
    foreach ($empleados as $nombreDep => $lista) {
        foreach ($lista as $emp) {
            $plantilla[] = [
                "id"           => $id,
                "nombre"       => $emp["Nombre"],
                "departamento" => $nombreDep,
                "salario"      => $emp["Salario"],
            ];
            $id++;
        }
    }
    return $plantilla;
}
