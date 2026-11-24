# EJERCICIO 5 · Resumen de departamentos (2 tablas, JOIN, switch y cookie)

**Tiempo orientativo:** 35-45 min
**Forma de trabajar:** un archivo `departamentos.php` (controlador + vista) y `AccesoDatos.php`.
**Pintar tablas:** **las dos formas en el mismo ejercicio**
- Resumen de departamentos → función `pintarResumen()` que devuelve un string con `$html .=`
- Empleados del departamento elegido → directamente en el HTML con `foreach:` / `endforeach;`

## Qué se practica
- Consulta con **`LEFT JOIN` + `GROUP BY`** (`COUNT`, `SUM`, `AVG`) sobre las dos tablas.
- **`FETCH_ASSOC`** en vez de `FETCH_CLASS`. ¿Por qué aquí? Las columnas calculadas (`num_empleados`, `gasto`…) **no existen** como atributo en `Departamento`. Su `__set` comprueba `property_exists` y las ignora en silencio, así que se perderían. Compruébalo tú: hazlo primero con `FETCH_CLASS` y mira qué pasa.
- **Enlaces GET con parámetros** (`?dep=3`), como los Detalles de examII.
- **ORDER BY elegido por el usuario**. **No** se puede hacer `bindValue` de un nombre de columna, así que se usa una **lista blanca con `switch`**. Si metes `$_GET['orden']` tal cual en el SQL, hay inyección SQL.
- `switch (true)` para clasificar rangos.
- **Cookie `ultimodep`** que recuerda el último departamento consultado.
- Un POST que solo se permite si se cumple una condición, y que se comprueba **otra vez en el servidor**.

## Requisitos
1. **Tabla resumen** (todos los departamentos, también los que no tienen empleados):
   `Departamento | Localidad | Nº empleados | Gasto sueldos | Sueldo medio | Presupuesto | Estado | (acción)`
   - Nombre del departamento como **enlace** `?dep=COD`.
   - Departamento sin empleados: nº 0, gasto 0,00 y media "—". `COALESCE` en SQL o control en PHP: practica uno.
   - Estado con `estadoPresupuesto()` y su clase CSS con `claseEstado()`:
     - gasto < 80 % del presupuesto → `OK` (clase `ok`)
     - 80 % – 100 % → `AL LÍMITE` (clase `limite`)
     - más del 100 % → `EXCEDIDO` (clase `excedido`)
   - Solo en las filas **EXCEDIDO**: un mini-formulario POST con `cod_dep` hidden y el botón `name="accion" value="Ampliar"`.
2. **Ordenación (GET):** enlaces en la cabecera o en un `<select>`: `?orden=nombre`, `?orden=gasto` o `?orden=empleados`.
   Por defecto, por nombre. Cualquier otro valor → por nombre. **Lista blanca con switch.**
   - Al pinchar un departamento, la ordenación **no** se pierde: el enlace queda como `?orden=gasto&dep=2`.
3. **Detalle (GET `?dep=N`):** debajo del resumen, *"Empleados de NOMBRE"* con
   `Código | Nombre | Puesto | Sueldo | % del gasto del departamento`
   - % = sueldo del empleado / gasto total del departamento × 100, con 1 decimal.
   - Guarda la cookie `ultimodep` (30 días).
   - Si **no** llega `?dep` pero sí existe la cookie → muestra ese departamento con el aviso *"(último departamento consultado)"*.
   - `?dep=99` (no existe) → *"Departamento no encontrado"*, y no se guarda la cookie.
4. **Ampliar presupuesto (POST):**
   - Vuelve a calcular en el servidor si el departamento está EXCEDIDO. Si no lo está → *"Ese departamento no necesita ampliación"*.
   - Si lo está → nuevo presupuesto = gasto × 1,10, redondeado a 2 decimales con `round`. Mensaje *"Presupuesto de X ampliado a Y €"*.

## Pruebas
| Acción | Resultado |
|---|---|
| Entrar | 4 departamentos. Administración EXCEDIDO (5750/5000), Sistemas AL LÍMITE (89,7 %), Desarrollo AL LÍMITE (89,2 %), Marketing OK con 0 empleados |
| `?orden=gasto` | Orden de mayor a menor gasto: Desarrollo, Sistemas, Administración, Marketing |
| `?orden=DROP TABLE` | Ordena por nombre y no pasa nada |
| Pinchar Sistemas | 3 empleados; jorge06 = 47,8 % |
| Cerrar el navegador y volver a entrar sin `?dep` | Aparece Sistemas "(último departamento consultado)" |
| Ampliar Administración | Presupuesto 6325,00 → pasa a AL LÍMITE |
| Mandar el POST de Ampliar con `cod_dep=4` (cambia el hidden con F12) | "No necesita ampliación" |
