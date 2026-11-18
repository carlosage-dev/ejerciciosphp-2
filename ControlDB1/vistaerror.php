<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error de Acceso</title>
    <!-- Esta etiqueta hace que a los 4 segundos redirija a acceso.html -->
    <meta http-equiv="refresh" content="4;url=acceso.html">
</head>
<body>

<div class="container">
    
    <div class="icon-no-results">❌</div>
    
    <h1>Error de Acceso</h1>
     
    <!-- Aquí mostramos el mensaje de error que viene de controlpedidos.php -->
    <p><b><?= $mensajeError ?></b></p>
    
    <p>Serás redirigido automáticamente en 4 segundos...</p>

    <div class="button-group">
        <a href="acceso.html" class="btn btn-primary">Volver al login</a>
    </div>
    
</div>

</body>
</html>