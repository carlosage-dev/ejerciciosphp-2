<?php
require_once '_lab/lab.php';

cabecera(
    "Laboratorio PHP",
    "Tu chuleta <b>funcional</b> para el examen: cada página explica una parte de PHP con ejemplos que se ejecutan de verdad. "
    . "A la izquierda ves el código y a la derecha lo que hace. Úsalo como plantilla cuando estés con un ejercicio.",
    false
);

$descripciones = [
    '00_basicos.php'     => 'echo, tipos, isset vs empty, == vs ===, conversiones, ternario, ??, switch, bucles, funciones, fechas.',
    '01_get_post.php'    => 'Qué viaja por la URL y qué no. Formularios GET y POST, enlaces con datos y REQUEST_METHOD.',
    '02_cookies.php'     => 'Crear, leer, actualizar y borrar cookies. Contador, preferencias y una lista guardada en texto.',
    '03_sesiones.php'    => 'session_start, guardar y leer $_SESSION, arrays en sesión, PIN con intentos y caducidad por inactividad.',
    '04_arrays.php'      => 'Indexados, asociativos y de arrays. Añadir, quitar, buscar, ordenar, contar, sumar y recorrer.',
    '05_strings.php'     => 'Longitud, mayúsculas, trim, substr, strpos, str_replace, explode/implode, formato de números.',
    '06_objetos.php'     => 'Clases, constructor, __get/__set, métodos, arrays de objetos, $obj->$campo y Singleton.',
    '07_formularios.php' => 'Inputs que conservan el valor, select con selected, radio checked, checkbox[], readonly vs disabled.',
    '08_tablas.php'      => 'Las formas de pintar tablas: foreach en el HTML, $msg .=, función genérica, checkbox y enlaces.',
    '09_pdo.php'         => 'Conexión, SELECT con FETCH_CLASS y FETCH_ASSOC, bindValue, JOIN, INSERT/UPDATE/DELETE y errores.',
    '10_estructuras.php' => 'Las 4 estructuras de ejercicio (ControlDB, examII, foro_vo...) y los errores típicos que hemos visto.',
];
?>
<div class="caja nota">
    <div class="caja-titulo">ℹ️ Cómo usarlo</div>
    <ul>
        <li>Copia la carpeta <code>laboratoriophp</code> en <code>C:\xampp\htdocs\</code> y abre <code>http://localhost/laboratoriophp/</code>.</li>
        <li>Para la página de <b>PDO</b> necesitas la base de datos: ejecuta <code>empresadb.sql</code> en phpMyAdmin (la misma de los ejercicios).</li>
        <li>Cada ejemplo tiene un <b>💪 Prueba tú</b>: cambia el código en el archivo, guarda y recarga para ver qué pasa.</li>
        <li>Las páginas de GET/POST, cookies, sesiones y formularios son <b>interactivas</b>: pulsa los botones y mira el panel verde "en vivo".</li>
    </ul>
</div>

<div class="tarjetas">
    <?php foreach ($descripciones as $archivo => $texto): ?>
        <a class="tarjeta" href="<?= $archivo ?>">
            <div class="icono"><?= LAB_PAGINAS[$archivo][0] ?></div>
            <h3><?= e(LAB_PAGINAS[$archivo][1]) ?></h3>
            <p><?= $texto ?></p>
        </a>
    <?php endforeach; ?>
</div>

<div class="caja aviso">
    <div class="caja-titulo">⚠️ Un detalle técnico</div>
    <p>Para enseñarte el código y su resultado a la vez, el laboratorio usa <code>eval()</code> por dentro
    (en <code>_lab/lab.php</code>). Es solo un truco para este repaso: <b>no lo uses nunca</b> en tus ejercicios.</p>
</div>
<?php pie(); ?>
