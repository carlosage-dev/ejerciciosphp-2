<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        $suma=0;
    ?>
    <h2>Bienvenido usuario<?= $cli->nombre?></h2>
    <h3>Has entrado <?= $cli->veces ?> veces en nuestra web</h3><br>
    
    <?php if (count($pedidosCliente)==0):?>
        <p>No existen pedidos para este usuario</p>
    
    <?php else: ?>
        <p>Esta es su lista de pedidos de cliente con código: <?= $cli->cod_cliente ?></p>
    <table>
        <?php foreach($pedidosCliente as $pedido): ?>
            <?php
            $suma += $pedido->precio;
             ?>
        <tr>
            <td><?= $pedido->producto ?></td>
            <td><?= $pedido->precio ?></td>   
        </tr>
        <?php endforeach ?>
        <tr><td>TOTAL PEDIDOS</td><td><?= $suma ?></td></tr>
    </table>
    <?php endif ?>
</body>
</html>