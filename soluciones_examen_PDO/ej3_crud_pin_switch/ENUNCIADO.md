# EJERCICIO 3 · Gestión de empleados con PIN (estilo examII / BD_PDO_Tareas)

**Tiempo orientativo:** 60-75 min (es el más largo, el más parecido a examII)
**Forma de trabajar:** `index.php` como controlador con `switch`, `app/acciones.php`, `app/funciones.php`, `app/layout/*`.
**Pintar la tabla:** con una función que construye un string `$msg .= "<tr>..."` y lo devuelve (como `mostrarDatos()` de examII).

## Qué se practica
- Control de acceso por **PIN**, guardado en sesión y con **número máximo de intentos**.
- **Caducidad de la sesión por inactividad** (5 min sin hacer nada), no por tiempo total.
  > Diferencia con examII: allí `$_SESSION["timeout"]` se guardaba una sola vez al entrar, así que a los 5 minutos te echaba aunque estuvieras trabajando. Aquí el contador se **renueva en cada petición**.
- `switch` separado para GET y para POST:
  - **GET** = enlaces y botones que *muestran* algo: Detalles, formulario de Modificar o de Nuevo, pantalla de confirmación de Borrar, Terminar.
  - **POST** = lo que *cambia* la BD: guardar el alta o la modificación, confirmar el borrado, bloquear, trasladar.
- Checkbox `templeados[]` con **dos acciones masivas distintas** sobre los marcados.
- Mostrar el **nombre** del departamento en la tabla de empleados (datos de la 2ª tabla).
- Una función que genera las `<option>` de un `<select>` y se usa en **dos sitios**.

## Archivos
| Archivo | Estado |
|---|---|
| `app/config.php` | Hecho (constantes: BD, PIN, intentos, tiempo, puestos) |
| `app/Empleado.php`, `app/Departamento.php` | Hechos |
| `app/AccesoDatos.php` | Singleton y constructor hechos. **Métodos a desarrollar** |
| `app/funciones.php` | **`mostrarDatos()`, `opcionesDepartamentos()`, `tiempoRestante()`** |
| `app/acciones.php` | **Todas las acciones** |
| `app/layout/formulariopin.php` | Hecho |
| `app/layout/confirmarborrar.php` | Hecho |
| `app/layout/principal.php` | Casi hecho: falta el `<select>` de traslado y el tiempo restante |
| `app/layout/formulario.php` | **Rellenar los value/readonly/disabled** (como el de examII) |
| `index.php` | **Controlador: PIN, inactividad y los dos switch** |

## Requisitos
### Acceso
1. Sin `$_SESSION['acceso']` → `formulariopin.php`. El PIN viaja por **POST**, así no aparece en la URL como pasaba en examII con GET.
2. PIN incorrecto → *"PIN incorrecto. Te quedan X intentos"*. Al llegar a `MAX_INTENTOS_PIN` fallos en la sesión, el formulario se oculta (`$bloqueadoPin = true`) y sale *"Demasiados intentos. Cierra el navegador y vuelve a intentarlo"*.
3. PIN correcto → `$_SESSION['acceso'] = true`, se borran los intentos y se guarda la hora.
4. Si pasan más de `TIEMPO_INACTIVIDAD` segundos entre dos peticiones → `accionTerminar()`. Si no, se renueva la hora.
5. `principal.php` muestra *"La sesión caduca en X s"*, con `tiempoRestante()`.

### Tabla (`mostrarDatos()`)
`☐ | Código | Nombre | Puesto | Departamento | Sueldo | Estado | Detalles | Modificar | Borrar`
- Departamento con el **nombre**. Pista de diseño: pide los departamentos **una vez** y monta un array `cod_dep => nombre`. No hagas una consulta por cada fila.
- Estado: `🔒 Bloqueado` o `Activo`.
- Detalles, Modificar y Borrar son **enlaces GET**: `?orden=Detalles&id=ana01`.

### Acciones
| Orden | Método | Qué hace |
|---|---|---|
| Nuevo | GET | formulario vacío. El departamento sale en un select y el puesto también |
| Nuevo | POST | valida: código no vacío y **que no exista ya**; sueldo numérico y > 0. `fecha_alta` = hoy, `bloqueado` = 0, `accesos` = 0 |
| Detalles | GET | formulario en solo lectura |
| Modificar | GET / POST | se puede cambiar todo menos el código |
| Borrar | GET | **no borra**: muestra `confirmarborrar.php` (sin JS) |
| ConfirmarBorrar | POST | borra de verdad |
| CambiarBloqueo | POST | **invierte** el bloqueo de los marcados (0→1, 1→0) |
| Trasladar | POST | mueve los marcados al departamento elegido en el select |
| Terminar | GET | cierra la conexión, destruye la sesión y vuelve a index |
| Volver / Cancelar | POST | no hace nada (vuelve a la tabla) |

Cada acción POST devuelve un **string** con el mensaje, que se muestra encima de la tabla. Por ejemplo: *"Se ha cambiado el bloqueo a 2 empleado(s)"*, *"Error: ya existe un empleado con código ana01"*.

### ⚠️ Trampas típicas (de examen)
- `limpiarArrayEntrada($_POST)` **falla** si `$_POST` contiene `templeados[]`, porque `limpiarEntrada()` espera un string y le llega un array. Llámala solo en el alta y en la modificación, o mejórala.
- Un `<select disabled>` **no se envía** en el formulario. En Detalles da igual, porque no se guarda nada; en Modificar, nunca lo pongas disabled.
- Si pulsas CambiarBloqueo o Trasladar sin marcar a nadie, `$_POST['templeados']` **no existe**: compruébalo con `isset`.
- Trasladar sin elegir departamento → mensaje de error.

## Pruebas
| Acción | Resultado esperado |
|---|---|
| PIN `1111` ×3 | Bloqueado el formulario de PIN |
| PIN `2026` | Tabla con 11 empleados |
| Marcar sara05 + raul10 → CambiarBloqueo | sara05 pasa a Activo y raul10 a Bloqueado |
| Marcar pablo04 + david08 → Trasladar a Marketing | Salen con departamento Marketing |
| Nuevo con código `ana01` | Error: ya existe |
| Borrar marta03 → Cancelar | No se borra |
| Esperar 5 min sin tocar nada y pulsar algo | Vuelve a pedir PIN |
| *(Para probar rápido el tiempo, pon `TIEMPO_INACTIVIDAD` a 20)* | |
