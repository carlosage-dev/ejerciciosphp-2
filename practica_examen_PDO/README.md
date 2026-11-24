# PRÁCTICA EXAMEN · BBDD + POO + PDO (+ sesiones y cookies)

## Instalación
1. Copia la carpeta `practica_examen_PDO` dentro de `C:\xampp\htdocs\`.
2. En phpMyAdmin, pestaña **SQL**, pega y ejecuta `empresadb.sql`.
3. Abre en el navegador, por ejemplo, `http://localhost/practica_examen_PDO/ej1_acceso_externo/acceso.html`.

> **Antes de empezar cada ejercicio, vuelve a ejecutar `empresadb.sql`.** Empieza con `DROP DATABASE`, así que deja los datos como al principio. Los resultados de las tablas de **Pruebas** cuentan con los datos originales.

## Base de datos `empresadb`
```
departamentos (cod_dep PK, nombre, localidad, presupuesto)
      1
      |
      N
empleados (cod_emp PK, nombre, clave, puesto, sueldo, cod_dep FK,
           bloqueado, accesos, fecha_alta)
```
Usuarios para probar: `ana01/1234` (Jefe), `luis02/abcd`, `jorge06/jorge` (Jefe), `sara05/sara` (bloqueada).

## Los 5 ejercicios
Todos usan la misma BD, pero cada uno está hecho con un **estilo distinto** para que practiques todas las formas.

| Ej | Se parece a… | Estructura | Pintar tabla | Control de acceso | Sesión / Cookie | Novedad |
|---|---|---|---|---|---|---|
| **1** | ControlDB1 | `acceso.html` → `action=consulta.php` → vista | `foreach:` en HTML | login BD (POST) | cookie con nombre por usuario | redirigir si llega por GET |
| **2** | ControlDB2 | 1 archivo que se llama a sí mismo | `foreach:` en HTML | — | cookie **contador** | GET filtra + POST modifica, `select` con `selected`, hidden, regla Becario |
| **3** | examII / BD_PDO_Tareas | `index.php` + `switch` + `app/` | función `$msg .=` | **PIN** con intentos | sesión por **inactividad** | 2 acciones masivas con checkbox, confirmar borrado sin JS |
| **4** | foro_vo + controldeaccesoMVC | `$_REQUEST` + `switch` + `ob_start` | **función genérica** `$obj->$campo` | login BD + bloqueo tras 3 fallos | sesión + cookie "recordar" | roles Jefe/empleado, permisos en servidor, escribir la clase |
| **5** | — (2 tablas) | 1 archivo + modelo | **las dos formas** | — | cookie último consultado | `LEFT JOIN` + `GROUP BY`, `FETCH_ASSOC`, `switch(true)`, ORDER BY con lista blanca |

**Orden recomendado:** 1 → 2 → 3 (el más parecido a examII; si solo te da tiempo a uno, haz este) → 5 → 4.

Cada carpeta tiene su `ENUNCIADO.md` con los requisitos y una tabla de **pruebas con los resultados esperados**, para que compruebes si lo tienes bien.

## Chuleta rápida: ¿GET o POST?
| Usa **GET** cuando… | Usa **POST** cuando… |
|---|---|
| Solo pides o muestras datos: filtrar, ordenar, ver detalles, abrir un formulario | **Cambias** algo: INSERT, UPDATE, DELETE |
| Es un enlace `<a href="?orden=Detalles&id=...">` | Envías una **contraseña o un PIN** |
| Tiene sentido guardarlo en marcadores o compartir la URL | Repetirlo con F5 haría daño (subir el sueldo dos veces) |

Tu profesor tiene razón, y es la regla de HTTP: GET es "seguro" (no cambia nada) y POST cambia datos. En examII el PIN y "Incrementar Saldo" iban por GET: funciona, pero el PIN se queda en la URL y en el historial, y el F5 puede repetir la subida.
