# EJERCICIO 4 · Login con roles, intentos y "Recordar usuario" (estilo foro_vo + controldeaccesoMVC)

**Tiempo orientativo:** 50-60 min
**Forma de trabajar:** todo entra por `index.php`, que lee `$_REQUEST['orden']` con un `switch`. Las plantillas se meten en un buffer con `ob_start()` / `ob_get_clean()` y al final se incluye `principal.php`, igual que en foro_vo.
**Pintar la tabla:** con una **función genérica** `pintarTabla($objetos, $columnas)` que sirve para cualquier clase. Se apoya en las **propiedades variables**: `$obj->$campo`, donde `$campo` es un string como `"nombre"`.

## Qué se practica
- `$_REQUEST`: recoge tanto GET como POST. Es cómodo, pero aquí verás que el controlador ya no distingue el método.
- Login contra la BD guardando en sesión **solo lo necesario** (`cod_emp`, `nombre`, `puesto`, `cod_dep`).
- **Bloqueo en BD tras 3 fallos seguidos** con el mismo usuario (como `controldeaccesoMVC`).
- **Cookie "Recordar mi usuario"**: si marcas el checkbox, se guarda el código 7 días y el login sale relleno. Si no lo marcas, se **borra**.
- **Roles**: lo que ves y lo que puedes hacer depende de `$_SESSION['puesto']`.
- Validar **en el servidor** que quien hace la acción tiene permiso. Esconder el botón no basta.
- Escribir tú la **clase** `Empleado` con sus propiedades privadas y `__get`/`__set`.

## Archivos
| Archivo | Estado |
|---|---|
| `app/modelo/Empleado.php` | **Escríbela tú entera** (campos = columnas de la tabla `empleados`) |
| `app/modelo/AccesoDatos.php` | Singleton hecho. **Métodos a desarrollar** |
| `app/funciones.php` | **Todas las funciones** |
| `index.php` | **El switch** |
| `app/plantillas/principal.php` | Hecha |
| `app/plantillas/entrada.php` | Falta rellenar el usuario desde la cookie |
| `app/plantillas/panel_jefe.php`, `panel_empleado.php` | Estructura hecha; faltan las tablas |

## Requisitos
### Login (`orden = Entrar`)
1. Campos vacíos → mensaje.
2. No existe el código → *"Usuario o clave incorrectos"*. No digas cuál de los dos falla: es una buena práctica de seguridad.
3. Existe pero está bloqueado → *"Cuenta bloqueada"*.
4. Clave mal:
   - Si es el mismo usuario que falló la vez anterior → intentos +1. Si no → empieza la cuenta en 1 con este usuario.
   - Al **3er** fallo seguido → `bloquearEmpleado()` en la BD y *"Cuenta bloqueada por demasiados intentos"*.
5. Correcto → guarda en sesión los 4 datos, borra los datos de intentos, accesos +1 en la BD, gestiona la cookie `usuario` y muestra el panel.

### Paneles (`orden` vacía y sesión iniciada → panel según el puesto)
- Cabecera común: *"Hola NOMBRE (PUESTO) · [Salir]"*.
- **Jefe → `panel_jefe.php`**
  - Tabla `pintarTabla()` con los empleados de **su** departamento: Código, Nombre, Puesto, Sueldo, Accesos. Con checkbox `seleccion[]`, **salvo** en su propia fila y en las de empleados bloqueados.
  - Botón **`PagaExtra`** → sube un 5 % a los seleccionados. En el servidor comprueba, por cada código: que sea de su departamento, que no sea él mismo y que no esté bloqueado.
- **Resto de puestos → `panel_empleado.php`**
  - Tabla `pintarTabla()` con **sus** datos (un array con un solo objeto): Código, Nombre, Puesto, Sueldo, Fecha alta, Accesos.
  - Formulario **`CambiarClave`**: clave actual, nueva y repetir. Reglas: la actual es correcta, la nueva tiene al menos 4 caracteres, las dos nuevas coinciden y la nueva es distinta de la actual.

### Seguridad
- Si llega `PagaExtra` o `CambiarClave` **sin sesión** → pantalla de entrada.
- Si un **no jefe** manda `PagaExtra` (por ejemplo escribiendo `index.php?orden=PagaExtra&seleccion[]=luis02` en la URL; con `$_REQUEST` eso entra) → *"No tienes permiso"*.

### Salir
- Destruye la sesión, **pero la cookie `usuario` se mantiene**: es del navegador, no de la sesión.

## EXTRA
- Usa `session_regenerate_id(true)` justo después de un login correcto y averigua para qué sirve.
- Cambia `$_REQUEST` por `$_POST` en las órdenes que modifican datos. Prueba otra vez la URL maliciosa del apartado de seguridad.

## Pruebas
| Entrada | Resultado |
|---|---|
| `ana01`/`1234` | Panel de jefe: 5 filas; sin checkbox en ana01 ni en sara05 |
| Paga extra a luis02 + marta03 | 2205,00 y 2047,50 |
| `luis02`/`abcd` | Panel de empleado con sus datos |
| `marta03` con clave mal ×3 | Bloqueada en la BD (compruébalo en phpMyAdmin) |
| `marta03` mal ×2 → `luis02` mal ×1 → `marta03` mal ×1 | **No** se bloquea (los fallos no fueron seguidos) |
| Recordar usuario marcado → Salir | El login aparece con el código relleno |
| URL maliciosa siendo `luis02` | "No tienes permiso" |
