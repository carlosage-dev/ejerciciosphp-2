# EJERCICIO 1 · Portal del empleado (estilo ControlDB1)

**Tiempo orientativo:** 35-45 min
**Forma de trabajar:** formulario HTML independiente → `action="consulta.php"` → vista de resultado.
**Pintar la tabla:** dentro del HTML con `<?php foreach (...): ?> ... <?php endforeach; ?>`

## Qué se practica
- Formulario con `action` hacia otro archivo (como `acceso.html` → `controlpedido.php`).
- Comprobar `$_SERVER['REQUEST_METHOD']` en el script que recibe los datos.
- Login contra la BD con `FETCH_CLASS`.
- UPDATE del contador de accesos (en la BD **y** en el objeto).
- Cookie **con nombre distinto para cada empleado**.
- Consulta sobre la segunda tabla (departamento) usando un dato del primer objeto (`$emp->cod_dep`).

## Archivos
| Archivo | Estado |
|---|---|
| `acceso.html` | Hecho. Fíjate en que usa **POST** (se envía una contraseña). |
| `Empleado.php`, `Departamento.php` | Hechos. |
| `vistaerror.php` | Hecho. Muestra `$mensajeError` y vuelve a los 4 s. |
| `AccesoDatos.php` | **Tienes que hacer los 4 métodos.** |
| `utilidades.php` | **Tienes que hacer la función `antiguedad()`.** |
| `consulta.php` | **El controlador: lo haces tú siguiendo los PASOS.** |
| `vistaempleado.php` | **Tienes que pintar la tabla.** |

## Requisitos
1. Si alguien abre `consulta.php` directamente (por GET, sin pasar por el formulario) → redirige a `acceso.html` con `header("Location: ...")`.
2. Si hay algún campo vacío → `vistaerror.php` con *"Debe rellenar todos los campos"*.
3. Si el código o la clave no son correctos → error *"Código de empleado o clave incorrectos"*.
4. Si el empleado está bloqueado → error *"Cuenta bloqueada. Contacte con RRHH"*. **No** se le suman accesos.
5. Si todo está bien:
   - Súmale 1 a los accesos en la BD y también en el objeto, para que la vista muestre el número correcto.
   - **Cookie `ultimoacceso_<cod_emp>`** (por ejemplo `ultimoacceso_ana01`), que dura 30 días y guarda la fecha y hora del acceso (`date("d/m/Y H:i:s")`).
     - Si ya existía, la vista dice: *"Tu último acceso desde este navegador fue el XX"*.
     - Si no existía: *"Es tu primer acceso desde este navegador"*.
     - Lee el valor antiguo **antes** de sobrescribirlo.
6. La vista muestra: nombre, puesto, nombre y localidad del departamento, número de accesos y el mensaje de la cookie.
7. Tabla con los **compañeros de su departamento** (él incluido), ordenados por sueldo de mayor a menor:
   `Código | Nombre | Puesto | Sueldo | Antigüedad (años)`
   - La fila del propio empleado lleva la clase CSS `yo` y el texto "(tú)" junto al nombre.
   - Las filas de empleados bloqueados llevan la clase `bloqueado`.
   - Última fila (clase `total`): **suma** de sueldos y **media** con 2 decimales.

## Pruebas
| Entrada | Resultado esperado |
|---|---|
| `ana01` / `1234` | Entra. Desarrollo (Madrid), 5 filas, total 10.700,00, media 2.140,00 |
| `ana01` / `mal` | Error de credenciales |
| `sara05` / `sara` | Cuenta bloqueada |
| Recargas con F5 después de entrar | El navegador pregunta si reenvía el formulario (es POST). Si aceptas, accesos sube y la cookie muestra la hora anterior. |
| Entras con `ana01` y luego con `luis02` | Cada uno tiene su propia cookie (*"primer acceso"* para luis02) |
| Escribes `consulta.php` en la barra | Te devuelve a `acceso.html` |
