# Chuleta de funciones PHP (código puro)

Copia la carpeta en `C:\xampp\htdocs\` y abre cada archivo en el navegador, por ejemplo:
`http://localhost/chuleta_funciones_php/01_recorrer_arrays.php`

Cada línea lleva al lado `// →` con lo que muestra. Todos los comentarios se han comprobado ejecutando el código.

| Archivo | Qué tiene |
|---|---|
| `datos.php` | Tus arrays `$departamentos` y `$empleados` (los usan todos) |
| `01_recorrer_arrays.php` | foreach simple, `clave => valor`, cuándo anidar, cálculos, combinar arrays, modificar recorriendo |
| `02_arrays.php` | Contar, buscar, añadir/quitar, ordenar (sort, asort, usort...), dar la vuelta, slice, merge, column, map, filter |
| `03_strings.php` | `strlen` vs `mb_strlen` y compañía, mayúsculas, trim, substr, buscar, reemplazar, explode/implode, formato |
| `04_objetos.php` | Clases Empleado y Departamento con métodos que usan las funciones anteriores |
| `05_tablas/` | **a)** foreach en el HTML · **b)** tabla por GET · **c)** tabla por POST · **d)** función `$msg .=` con checkbox |
| `06_numeros_fechas_validar.php` | round, %, conversiones, validar formularios, date, strtotime, DateTime |

## Lo más importante: ¿hace falta otra variable?

| Tipo | Funciones | Cómo se usan |
|---|---|---|
| **MODIFICAN el original** (devuelven `true` o el elemento quitado, no el array) | `sort rsort asort arsort ksort krsort usort shuffle array_push array_pop array_shift array_unshift unset` | `sort($a);` y luego usas `$a`. Si quieres conservar el original: `$copia = $a; sort($copia);` |
| **DEVUELVEN uno nuevo** (no tocan el original) | `array_reverse array_slice array_merge array_unique array_values array_keys array_column array_map array_filter implode explode count array_sum` | `$nuevo = array_reverse($a);` (si no lo guardas, se pierde) |
| **Strings: todas devuelven uno nuevo** | `trim strtoupper mb_strtoupper str_replace substr...` | `$nombre = trim($nombre);` |

✗ Error típico: `$ordenado = sort($a);` → `$ordenado` vale `true`.

## ¿Qué foreach uso?
- `foreach ($lista as $valor)` → solo te interesan los valores.
- `foreach ($lista as $clave => $valor)` → necesitas también la clave (nombre, id, posición) o vas a modificar el array.
- **foreach anidados** → cuando un valor es a su vez una **lista** que quieres recorrer (varios empleados en cada departamento).
- Campos fijos (`"Jefe"`, `"Presupuesto"`) → no se recorren: `$datos["Jefe"]`.

## ¿Hace falta un `if` alrededor de la tabla?
- Los datos están siempre (listado al cargar) → **no**.
- La tabla depende de un formulario (GET o POST) → **sí**: `<?php if ($buscado): ?> ... <?php endif; ?>`
- La lista puede salir vacía → **if / else** con un mensaje.
