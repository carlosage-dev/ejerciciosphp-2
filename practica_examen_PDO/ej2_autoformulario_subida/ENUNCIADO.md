# EJERCICIO 2 · Subida de sueldos con checkbox (estilo ControlDB2)

**Tiempo orientativo:** 40-50 min
**Forma de trabajar:** UN solo archivo (`subida.php`) que se llama a sí mismo: arriba el PHP, abajo el HTML.
**Pintar la tabla:** dentro del HTML con `<?= ?>` y `foreach:` / `endforeach;` (como `consulta.php` de ControlDB2).

## Qué se practica
- **GET y POST en la misma página**, cada uno para lo suyo:
  - **GET** → filtrar (un `<select>` de departamentos). Solo *pide/muestra* datos; la URL queda como `subida.php?dep=2` y se puede guardar en marcadores.
  - **POST** → subir sueldos. *Modifica* datos.
- Pintar un `<select>` desde la BD y dejar **marcada** (`selected`) la opción elegida.
- Checkbox `name="templeados[]"` con `value` = `cod_emp`. Es lo mismo que el `tuser[]` de examII.
- Pasar un dato del GET al POST con un `<input type="hidden">`.
- Una regla de negocio que **no** se puede resolver solo con un UPDATE: primero se lee el empleado, se calcula y luego se actualiza.
- Cookie que **cuenta** (no solo comprueba si existe, como en ControlDB2).

## Archivos
| Archivo | Estado |
|---|---|
| `Empleado.php`, `Departamento.php`, `default.css` | Hechos |
| `AccesoDatos.php` | **4 métodos a desarrollar** |
| `subida.php` | **Función `aplicarSubida()` + controlador + rellenar el HTML** |

## Requisitos
1. **Filtro (GET):** un formulario `method="get"` con un `<select name="dep">`:
   - primera opción `value="todos"` → *Todos los departamentos*
   - luego una opción por departamento de la BD (value = `cod_dep`, texto = `nombre`)
   - después de filtrar, la opción elegida sigue seleccionada.
2. **Tabla** con los empleados del departamento elegido, o de todos si no hay filtro:
   `☐ | Código | Nombre | Puesto | Departamento (NOMBRE, no código) | Sueldo`
   - Empleados bloqueados: fila con clase `bloqueado` y checkbox **`disabled`** (no se pueden marcar).
   - Sueldo con formato `number_format($x, 2, ',', '.')` + ` €`.
3. **Subida (POST):** debajo de la tabla, un `input type="number" name="porcentaje"` y un botón `name="orden" value="Subir"`.
   Validaciones, en este orden, cada una con su mensaje:
   - No hay ningún empleado marcado.
   - El porcentaje no está entre 1 y 20.
   - La cookie `subidas` ya vale `MAX_SUBIDAS_DIA` (2) → *"Límite de subidas diarias alcanzado"*.
4. Si todo está bien → `aplicarSubida()`, la cookie `subidas` suma 1 (dura 24 h) y aparece el mensaje
   *"Sueldo actualizado a N empleado(s). Subidas hoy: X/2"*.
5. **Regla de `aplicarSubida()`:**
   - Si es **Becario**, su sueldo nunca puede pasar de `TOPE_BECARIO` (1000). Si con la subida se pasa, se queda en 1000 exactos.
   - Si el empleado está **bloqueado**, no se le sube, aunque llegue su código. Nunca te fíes de lo que manda el navegador: alguien podría quitar el `disabled` con F12.
   - Devuelve cuántos empleados se han actualizado de verdad.
6. **Después de subir, el filtro tiene que seguir igual.** Si estabas viendo Sistemas, sigues viendo Sistemas.

## EXTRA (si te sobra tiempo)
- **Patrón PRG (Post/Redirect/Get):** después de procesar el POST, guarda el mensaje en `$_SESSION['flash']`, haz `header("Location: subida.php?dep=...")` y `exit()`. Al cargar, muestras el flash y lo borras con `unset`. Así el F5 ya no vuelve a enviar el formulario.
- Haz que la cookie caduque a medianoche: `strtotime('tomorrow')`.

## Pruebas
| Acción | Resultado esperado |
|---|---|
| Entrar sin filtro | 11 empleados |
| Filtrar Sistemas | 3 empleados; el select sigue en "Sistemas" |
| Marcar david08 (Becario, 980) + 5 % | Queda en **1000,00** (no 1029) |
| Marcar pablo04 (850) + 10 % | 935,00 |
| Marcar elena07 + 25 % | Error de porcentaje |
| Tercera subida en el mismo día | Error de límite (borra la cookie con F12 para seguir probando) |
| Filtrar Marketing | Tabla vacía → muestra *"No hay empleados en este departamento"* |
