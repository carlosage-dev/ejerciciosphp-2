<?php
/*
 * =====================================================================
 *  LABORATORIO PHP · utilidades comunes de todas las páginas
 * =====================================================================
 *  Este archivo NO hace falta entenderlo para usar el laboratorio.
 *  Solo pinta la cabecera, el menú, las tarjetas de ejemplo, etc.
 *
 *  ⚠️ Para enseñarte el código y su resultado a la vez, demo() usa eval():
 *     ejecuta el texto del ejemplo. Es SOLO un truco del laboratorio:
 *     en tus ejercicios y en el examen NO lo uses nunca.
 * =====================================================================
 */

date_default_timezone_set('Europe/Madrid');

// Colores del código (tema oscuro)
ini_set('highlight.default', '#e6edf3');
ini_set('highlight.keyword', '#ff7b72');
ini_set('highlight.string', '#a5d6ff');
ini_set('highlight.comment', '#8b949e');
ini_set('highlight.html', '#7ee787');

const LAB_PAGINAS = [
    'index.php'           => ['🏠', 'Inicio'],
    '00_basicos.php'      => ['🧱', 'Lo básico'],
    '01_get_post.php'     => ['📨', 'GET y POST'],
    '02_cookies.php'      => ['🍪', 'Cookies'],
    '03_sesiones.php'     => ['🔐', 'Sesiones'],
    '04_arrays.php'       => ['📚', 'Arrays'],
    '05_strings.php'      => ['🔤', 'Strings'],
    '06_objetos.php'      => ['🧩', 'Objetos'],
    '07_formularios.php'  => ['📝', 'Formularios'],
    '08_tablas.php'       => ['📊', 'Pintar tablas'],
    '09_pdo.php'          => ['🗄️', 'PDO'],
    '10_estructuras.php'  => ['🏗️', 'Estructuras y errores'],
    '11_fechas.php' => ['📅', 'Fechas'],
];

$GLOBALS['LAB_INDICE'] = [];
$GLOBALS['LAB_NUM'] = 0;

/** Escapa texto para HTML */
function e($texto): string
{
    return htmlspecialchars((string) $texto, ENT_QUOTES, 'UTF-8');
}

/** Cabecera + menú. Abre el buffer para poder montar el índice al final. */
function cabecera(string $titulo, string $subtitulo = "", bool $conIndice = true): void
{
    ob_start();
    $actual = basename($_SERVER['PHP_SELF']);
    $icono = isset(LAB_PAGINAS[$actual]) ? LAB_PAGINAS[$actual][0] : '🧪';

    echo '<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8">';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
    echo '<title>' . e($titulo) . ' · Laboratorio PHP</title>';
    echo '<link rel="stylesheet" href="_lab/lab.css"></head><body>';

    echo '<header class="cabecera"><div class="marca">🧪 Laboratorio PHP · DAW</div>';
    echo '<h1>' . $icono . ' ' . e($titulo) . '</h1>';
    if ($subtitulo != "") {
        echo '<p>' . $subtitulo . '</p>';
    }
    echo '</header>';

    echo '<nav class="nav">';
    foreach (LAB_PAGINAS as $archivo => $datos) {
        $clase = ($archivo == $actual) ? 'activa' : '';
        echo '<a class="' . $clase . '" href="' . $archivo . '">' . $datos[0] . ' ' . e($datos[1]) . '</a>';
    }
    echo '</nav>';

    if ($conIndice) {
        echo '<div class="layout"><aside class="indice"><!--LAB_INDICE--></aside><main>';
    } else {
        echo '<div class="layout sin-indice"><main>';
    }
}

/** Cierra la página y coloca el índice lateral. */
function pie(): void
{
    echo '</main></div><div class="pie">Laboratorio PHP · hecho para repasar el examen de BBDD + POO + PDO</div>';
    echo '</body></html>';
    $html = ob_get_clean();

    $indice = '<div class="titulo-indice">En esta página</div>';
    foreach ($GLOBALS['LAB_INDICE'] as $item) {
        if ($item[0] == 'grupo') {
            $indice .= '<div class="grupo"><a href="#' . $item[1] . '" style="padding-left:0">' . e($item[2]) . '</a></div>';
        } else {
            $indice .= '<a href="#' . $item[1] . '">' . e($item[2]) . '</a>';
        }
    }
    echo str_replace('<!--LAB_INDICE-->', $indice, $html);
}

/** Título de sección */
function seccion(string $titulo, string $intro = ""): void
{
    $id = 's' . count($GLOBALS['LAB_INDICE']);
    $GLOBALS['LAB_INDICE'][] = ['grupo', $id, $titulo];
    echo '<h2 class="seccion" id="' . $id . '">' . e($titulo) . '</h2>';
    if ($intro != "") {
        echo '<p class="intro-seccion">' . $intro . '</p>';
    }
}

/** Colorea código PHP */
function resaltar(string $codigo): string
{
    $codigo = rtrim($codigo);
    if (str_starts_with($codigo, '?>')) {
        // Es HTML con trocitos de PHP: se colorea tal cual
        $html = highlight_string(ltrim(substr($codigo, 2)), true);
    } elseif (str_starts_with($codigo, '<?php')) {
        // Ya trae su <?php: se enseña completo
        $html = highlight_string($codigo, true);
    } else {
        $html = highlight_string("<?php\n" . $codigo, true);
        // quitamos el "<?php" que hemos añadido solo para que lo coloree
        $html = preg_replace('/&lt;\?php(<br \/>|\n)/', '', $html, 1);
    }
    // PHP 8.2 (XAMPP) usa <br /> y &nbsp;; PHP 8.3 usa saltos reales. Se deja todo igual:
    $html = str_replace(['<br />', '&nbsp;'], ["\n", ' '], $html);
    $html = preg_replace('/(<code[^>]*>(?:<span[^>]*>)?)\n/', '$1', $html, 1);
    return '<div class="src">' . $html . '</div>';
}

/**
 * Tarjeta de ejemplo: explicación + código + RESULTADO REAL + reto.
 * $html = true  -> el resultado se pinta como HTML (tablas, formularios...)
 */
function demo(string $titulo, string $explicacion, string $codigo, string $reto = "", bool $html = false): void
{
    $GLOBALS['LAB_NUM']++;
    $num = $GLOBALS['LAB_NUM'];
    $id = 'd' . $num;
    $GLOBALS['LAB_INDICE'][] = ['demo', $id, $titulo];

    ob_start();
    try {
        (function () use ($codigo) {
            eval($codigo);
        })();
    } catch (Throwable $ex) {
        echo "⚠️ " . get_class($ex) . ": " . $ex->getMessage();
    }
    $salida = ob_get_clean();

    echo '<div class="demo" id="' . $id . '">';
    echo '<div class="demo-cabecera"><h3><span class="num">#' . $num . '</span> ' . e($titulo) . '</h3>';
    echo '<p>' . $explicacion . '</p></div>';
    echo '<div class="demo-cuerpo">';
    echo '<div class="panel-codigo"><div class="etiqueta">Código</div>' . resaltar($codigo) . '</div>';
    echo '<div class="panel-salida"><div class="etiqueta">▶ Resultado</div>';
    if (trim($salida) == "") {
        echo '<pre class="vacio">(no muestra nada)</pre>';
    } elseif ($html) {
        echo '<div class="html-out">' . $salida . '</div>';
    } else {
        echo '<pre>' . e($salida) . '</pre>';
    }
    echo '</div></div>';
    if ($reto != "") {
        echo '<div class="reto"><b>💪 Prueba tú:</b> ' . $reto . '</div>';
    }
    echo '</div>';
}

/** Tarjeta solo con código (no se ejecuta): para cosas que no se pueden ejecutar aquí */
function codigo(string $titulo, string $explicacion, string $codigo, string $reto = ""): void
{
    $GLOBALS['LAB_NUM']++;
    $num = $GLOBALS['LAB_NUM'];
    $id = 'd' . $num;
    $GLOBALS['LAB_INDICE'][] = ['demo', $id, $titulo];

    echo '<div class="demo" id="' . $id . '">';
    echo '<div class="demo-cabecera"><h3><span class="num">#' . $num . '</span> ' . e($titulo) . '</h3>';
    echo '<p>' . $explicacion . '</p></div>';
    echo '<div class="demo-cuerpo solo-codigo"><div class="panel-codigo"><div class="etiqueta">Código</div>'
        . resaltar($codigo) . '</div></div>';
    if ($reto != "") {
        echo '<div class="reto"><b>💪 Prueba tú:</b> ' . $reto . '</div>';
    }
    echo '</div>';
}

/** Caja de color: nota (azul), aviso (naranja), error (rojo), ok (verde) */
function caja(string $tipo, string $titulo, string $contenido): void
{
    $iconos = ['nota' => 'ℹ️', 'aviso' => '⚠️', 'error' => '🚫', 'ok' => '✅'];
    $icono = isset($iconos[$tipo]) ? $iconos[$tipo] : '';
    echo '<div class="caja ' . $tipo . '"><div class="caja-titulo">' . $icono . ' ' . e($titulo) . '</div>'
        . $contenido . '</div>';
}

/** Tabla de referencia rápida: [['función', 'qué hace'], ...] */
function chuleta(array $filas): void
{
    echo '<table class="tabla-lab"><tr><th style="width:34%">Función / sintaxis</th><th>Qué hace</th></tr>';
    foreach ($filas as $f) {
        echo '<tr><td><code>' . e($f[0]) . '</code></td><td>' . $f[1] . '</td></tr>';
    }
    echo '</table>';
}

/** Muestra el contenido de una variable (para los paneles "en vivo") */
function volcado(string $titulo, $valor): string
{
    if (is_array($valor) && count($valor) == 0) {
        $texto = "(vacío)";
    } else {
        $texto = print_r($valor, true);
    }
    return '<div><div class="volcado-titulo">' . e($titulo) . '</div><pre class="volcado">' . e($texto) . '</pre></div>';
}
