<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Error de acceso</title>
    <link href="default.css" rel="stylesheet" type="text/css">
    <!-- A los 4 segundos vuelve a acceso.html -->
    <meta http-equiv="refresh" content="4;url=acceso.html">
</head>
<body>
<div id="container" style="max-width: 420px;">
    <h1>❌ Error de acceso</h1>
    <p class="error"><?= $mensajeError ?></p>
    <p>Serás redirigido automáticamente en 4 segundos...</p>
    <a href="acceso.html">Volver al login</a>
</div>
</body>
</html>
