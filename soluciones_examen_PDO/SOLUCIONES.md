# SOLUCIONES · práctica examen PDO

Son las soluciones completas de los 5 ejercicios. Se instalan igual que los enunciados: copia la carpeta en `htdocs` y ejecuta `empresadb.sql` antes de cada ejercicio.
Las he probado con los casos de la tabla **Pruebas** de cada `ENUNCIADO.md`.

## Lo importante de cada uno (lo que tienes que saber explicar)

### Ej1 · Portal del empleado
- `consulta.php`: si no llega por POST, `header("Location: ...")`. Comprueba **un `empty()` por campo**.
- Accesos: se suman en la BD **y** en el objeto (`$emp->accesos + 1`), porque la vista enseña el objeto.
- Cookie: el nombre se construye con `"ultimoacceso_" . $emp->cod_emp`. **Primero se lee, luego se escribe.**
- Vista: la clase de la fila (`yo` / `bloqueado`) se calcula en una variable `$clase` antes del `<tr>`.

### Ej2 · Subida de sueldos
- `dep` llega por GET (el select) o por POST (el hidden): `if (isset($_GET)) ... elseif (isset($_POST)) ...`
- Un checkbox sin marcar **no llega**: `isset($_POST['templeados'])`.
- Dentro de `aplicarSubida()` no existe `$db`: se vuelve a pedir con `AccesoDatos::initModelo()`.
- La regla del becario necesita leer el empleado antes del UPDATE, porque el cálculo se hace en PHP.

### Ej3 · CRUD con PIN (el más parecido a examII)
- `index.php`: PIN con intentos → inactividad (se **renueva** `$_SESSION['ultimo']` en cada petición) → `switch` GET → `switch` POST.
- `mostrarDatos()` monta la tabla con `$msg .=` y saca el nombre del departamento de un array `cod_dep => nombre`.
- `cambiarBloqueo()`: `SET bloqueado = 1 - bloqueado` invierte el valor sin consultar antes.
- `formulario.php`: las variables `$soloLectura`, `$desactivado` y `$codReadonly` se calculan arriba según `$orden`.
- `limpiarArrayEntrada()` mejorada para que no falle con arrays (`is_string`).

### Ej4 · Login con roles
- Fallos **seguidos**: en la sesión se guarda **qué usuario** falló y cuántas veces; si cambia de usuario, la cuenta vuelve a 1.
- `pintarTabla()` genérica: `$obj->$campo`, donde `$campo` es un string como `"nombre"`.
- Permisos en el servidor: `PagaExtra` comprueba la sesión, que sea jefe, y en cada empleado su departamento, que no sea él mismo y que no esté bloqueado.
- `ob_start()` permite hacer `setcookie()` aunque ya se haya hecho `echo`.

### Ej5 · Resumen de departamentos
- `LEFT JOIN` + `GROUP BY` + `COUNT(e.cod_emp)` + `COALESCE(SUM(...), 0)` → Marketing sale con 0.
- `FETCH_ASSOC` porque `num_empleados`, `gasto` y `media` no son atributos de `Departamento`.
- ORDER BY con **lista blanca** (`switch`): nunca se mete `$_GET['orden']` directamente en el SQL.
- `switch (true)` para los rangos de OK, AL LÍMITE y EXCEDIDO.
