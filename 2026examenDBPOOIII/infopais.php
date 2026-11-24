<?php
// Forma antigua de definir Array en PHP
$paises = array(
    'Argentina' => 	array("Capital" => "Buenos Aires", 	"Poblacion" => "40000000"),
    'Francia' => 	array("Capital" => "París", 		"Poblacion" => "50000000"),
    'España' => 	array("Capital" => "Madrid", 	"Poblacion" => "42000000"),
    'Italia' => 	array("Capital" => "Roma", 		"Poblacion" => "46000000"),
    'Suiza'=> 		array("Capital" => "Zúrich", 	"Poblacion" =>  "9000000"),
    'Colombia' => 	array("Capital" => "Bogotá", 	"Poblacion" => "36000000"),
    'Chile' => 	array("Capital" => "Santiago", 	"Poblacion" => "36000000"),
    'Suecia' => 	array("Capital" => "Estocolmo", 	"Poblacion" => "25000000"),
);
// Forma moderna, mas compacta
$ciudades = [
    'Argentina' =>  ["Buenos Aires", "Córdoba","Parana","Rosario"],
    'Francia' =>    ["París","Burdeos","Niza","Lille","Nantes"],
    'España' =>     ["Madrid", "Barcelona","León","Sevilla", "Valencia", "Málaga"],
    'Italia' =>     ["Roma", "Venecia","Florencia","Pisa", "Génova", "Milán", "Turín", "Nápoles"],
    'Suiza'  =>     ["Zúrich","Ginebra","Basilea","Lausana"],
    'Colombia' =>   ["Bogotá", "Medellín","Cali","Barranquilla", "Bucaramanga"],
    'Chile' =>      ["Santiago", "Arica","Iquique","Osorno", "Viña del Mar"],
    'Suecia' =>   ["Estocolmo", "Upsala","Gotemburgo","Lund"],
];

$paisExiste = false;
$msg=" ";



if ($_SERVER['REQUEST_METHOD']=="POST"){
    $pais = $_POST['pais'];
    if (isset($_POST['buscar']) ){
        if (array_key_exists($pais, $paises)){
            $paisExiste=true;
            $msg = "Pais : ".$pais. " encontrado. Ciudades: ";
            for ($i = 1;$i<count($ciudades[$pais]);$i++){
                $msg .= $ciudades[$pais][$i] . " ";
            }
            
        }else{
            $msg = "Pais : ".$pais. " NO encontrado";
        }
    }
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="" method="post">
        <label for="">Introduzca un Pais: <input type="text" name="pais"></label>
        <input type="submit" name="buscar" value="Buscar">
    </form>
    <?= $msg ?>
    <?php
        $msg2="";
        $msg2.="<table>";
        $poblacion=0;

        //si se quiere hacer con un array auxiliar:
        /* // 1. Crear el array auxiliar (País => Población)
            $auxiliar = [];
            foreach ($paises as $pais => $datos) {
                $auxiliar[$pais] = $datos['Poblacion'];
            }

            // 2. Ordenar por valor conservando las claves (de menor a mayor)
            uasort($auxiliar, function ($a, $b) {
                return $a <=> $b;
                print_r($auxiliar);
            });
            <table border="1" cellpadding="8" cellspacing="0">
                <thead>
                    <tr>
                        <th>País</th>
                        <th>Población</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($auxiliar as $pais => $poblacion): ?>
                        <tr>
                            <td><?= htmlspecialchars($pais) ?></td>
                            <td><?= number_format((int)$poblacion, 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>    
            
            */


        //forma de ordenar por valor, dejando las claves
        uasort($paises, function ($a, $b) {
        return $a['Poblacion'] <=> $b['Poblacion'];
        });

        foreach ($paises as $pais=> $parametros){
            $msg2 .= "<tr><td>$pais</td>";
            foreach($parametros as $parametro =>$value){
                if($parametro == "Poblacion"){
                    $poblacion = $value;
                    $msg2 .= "<td>$poblacion</td></tr>";
                }
            }
        }
        $msg2.="<table>";
    ?>
    <?= $msg2;?>
</body>
</html>