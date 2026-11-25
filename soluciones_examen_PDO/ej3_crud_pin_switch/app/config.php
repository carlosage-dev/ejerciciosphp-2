<?php
define('SERVER_DB', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWD', '');
define('DATABASE', 'empresadb');

// Control de acceso
define('PIN_ACCESO', '2026');
define('MAX_INTENTOS_PIN', 3);
define('TIEMPO_INACTIVIDAD', 300);   // 5 minutos (ponlo a 20 para probar)

// Puestos posibles (para el <select> del formulario)
define('PUESTOS', ['Jefe', 'Analista', 'Programador', 'Administrativo', 'Becario']);
