# 🧪 Laboratorio PHP

Chuleta **funcional** para el examen: cada página explica una parte de PHP con ejemplos que se ejecutan de verdad (código a la izquierda, resultado a la derecha) y un pequeño reto.

## Cómo abrirlo
1. Copia la carpeta `laboratoriophp` en `C:\xampp\htdocs\`.
2. Arranca **Apache** en XAMPP (y **MySQL** para la página de PDO).
3. Abre `http://localhost/laboratoriophp/`.
4. Para la página de **PDO**: ejecuta `empresadb.sql` en phpMyAdmin (es la misma base de datos de los ejercicios).

Necesita PHP 8 o superior (el de XAMPP actual vale).

## Páginas
| Archivo | Tema |
|---|---|
| `00_basicos.php` | echo, tipos, isset vs empty, == vs ===, conversiones, if, ternario, ??, switch, bucles, funciones, fechas |
| `01_get_post.php` | GET y POST en un solo documento, con panel "en vivo" |
| `02_cookies.php` | crear, leer, actualizar y borrar cookies (interactivo) |
| `03_sesiones.php` | sesiones, arrays en sesión, PIN con intentos y caducidad (interactivo) |
| `04_arrays.php` | todas las funciones de arrays con ejemplo |
| `05_strings.php` | todas las funciones de strings con ejemplo |
| `06_objetos.php` | clases, constructor, __get/__set, métodos, arrays de objetos, Singleton |
| `07_formularios.php` | cada tipo de campo y lo que llega a `$_POST` (interactivo) |
| `08_tablas.php` | todas las formas de pintar tablas |
| `09_pdo.php` | consultas reales: SELECT, bindValue, JOIN, INSERT/UPDATE/DELETE, try/catch |
| `10_estructuras.php` | las 4 estructuras de ejercicio + errores típicos |

## Cómo practicar
- En cada ejemplo hay un **💪 Prueba tú**: abre el `.php` en VS Code, cambia el código del ejemplo, guarda y recarga el navegador.
- Los ejemplos de PDO que modifican datos se deshacen solos (`rollBack`), así que puedes recargar sin estropear la base de datos.

⚠️ Por dentro, `_lab/lab.php` usa `eval()` para enseñar el código y su resultado a la vez. Es solo un truco del laboratorio: no lo uses en tus ejercicios.
