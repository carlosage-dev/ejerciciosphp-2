# PRÁCTICA POR CONCEPTOS · PHP

Ejercicios **cortos y separados**, de un concepto cada uno. Al final hay unos combinados de nivel intermedio.
Cada archivo lleva el **enunciado en el comentario de arriba** y `TODO` donde tienes que escribir.

## Instalación
1. Copia `practica_conceptos` en `C:\xampp\htdocs\`.
2. Solo para los bloques 6 y 7: ejecuta `empresadb.sql` en phpMyAdmin. Es la misma BD de antes.
3. Abre cada archivo en el navegador: `http://localhost/practica_conceptos/1_get_post/1a_saludo_get.php`

## Índice
| Bloque | Archivos | Concepto | Tiempo |
|---|---|---|---|
| **1 · GET / POST** | 1a, 1b, 1c, 1d | `$_GET`, `$_POST`, `REQUEST_METHOD`, `isset`, varios botones con `switch`, GET y POST en la misma página | 10-15 min cada uno |
| **2 · Cookies** | 2a, 2b, 2c | contador, borrar cookie, preferencias, guardar una lista con `explode`/`implode` | 10-15 min |
| **3 · Sesiones** | 3a, 3b, 3c | `session_start`, contador, array en sesión, zona privada con login e inactividad | 10-20 min |
| **4 · Arrays y strings** | `arrays.php`, `strings.php` | bucles y funciones de PHP. **Se corrigen solos** (✔ / ✘) | 3-5 min por función |
| **5 · Objetos** | `pruebas_objetos.php` | clase con constructor y métodos, objeto que guarda un array de objetos. **Se corrige solo** | 30 min |
| **6 · PDO** | `pruebas_lectura.php`, `modificar.php` | SELECT, INSERT, UPDATE, DELETE, `FETCH_CLASS` / `FETCH_ASSOC`, `try/catch`, y **métodos dentro de Empleado y Departamento** que usas después | 5 min por método |
| **7 · Combinados** | 7a, 7b, 7c | login BD + sesión · filtro por enlaces + cookie · checkbox + 2 botones + sesión | ~20 min cada uno |

El bloque 7 usa el `AccesoDatos` del bloque 6, así que **haz el 6 primero**.

## Cómo funcionan los que se corrigen solos (bloques 4, 5 y 6)
Abres el archivo en el navegador y ves una lista de pruebas en **verde ✔** o **rojo ✘**. En rojo te dice qué esperaba y qué ha devuelto tu función. Vas rellenando hasta que todo salga verde.
Alguna prueba sale en verde sin hacer nada (las que esperan `false`, por ejemplo). Fíjate en el total.

## Orden recomendado si vas justo de tiempo
1b → 1c → 2a → 3c → `strings.php` (5, 8, 9) → bloque 6 → 7a → 7c
