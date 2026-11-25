<?php
require_once '_lab/lab.php';

/* =====================================================================
 *  FORMULARIOS · cada tipo de campo y lo que llega a PHP
 *  Todos los formularios se envían a esta misma página por POST.
 *  El botón de cada uno se llama name="form" con un value distinto:
 *  así PHP sabe QUÉ formulario se ha enviado.
 * ===================================================================== */

$enviado = "";
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['form'])) {
    $enviado = $_POST['form'];
}

// ---------- 1. Texto que conserva su valor ----------
$proc1 = <<<'PHP'
$nombre = "";
$edad = "";
$msg1 = "";

if ($enviado == "texto") {
    $nombre = trim($_POST['nombre']);      // se guarda SIEMPRE, para volver a pintarlo
    $edad = trim($_POST['edad']);

    if ($nombre == "" || $edad == "") {
        $msg1 = "Rellena los dos campos";
    } elseif (!is_numeric($edad)) {
        $msg1 = "La edad tiene que ser un número";
    } else {
        $msg1 = "✅ " . htmlspecialchars($nombre) . " tendrá " . ((int) $edad + 1) . " años el año que viene";
    }
}
PHP;
$form1 = <<<'HTML'
<form method="post">
    Nombre: <input type="text" name="nombre" value="<?= htmlspecialchars($nombre) ?>">
    Edad: <input type="text" name="edad" value="<?= htmlspecialchars($edad) ?>" size="4">
    <button name="form" value="texto">Enviar</button>
</form>
HTML;

// ---------- 2. Select generado desde un array, con selected ----------
$proc2 = <<<'PHP'
$ciudades = ["MAD" => "Madrid", "BCN" => "Barcelona", "VLC" => "Valencia"];
$ciudadElegida = "";
$msg2 = "";

if ($enviado == "select") {
    $ciudadElegida = $_POST['ciudad'];
    if (isset($ciudades[$ciudadElegida])) {            // ¿existe? (lista blanca)
        $msg2 = "✅ Has elegido " . $ciudades[$ciudadElegida] . " (llega el value: " . $ciudadElegida . ")";
    } else {
        $msg2 = "Elige una ciudad";
    }
}
PHP;
$form2 = <<<'HTML'
<form method="post">
    <select name="ciudad">
        <option value="">-- Elige --</option>
        <?php foreach ($ciudades as $cod => $nombreCiudad): ?>
            <option value="<?= $cod ?>" <?= ($cod == $ciudadElegida) ? "selected" : "" ?>><?= $nombreCiudad ?></option>
        <?php endforeach; ?>
    </select>
    <button name="form" value="select">Enviar</button>
</form>
HTML;

// ---------- 3. Radio con checked ----------
$proc3 = <<<'PHP'
$tema = "claro";          // valor por defecto
$msg3 = "";

if ($enviado == "radio") {
    if (isset($_POST['tema'])) {                       // si no marcas ninguno, NO llega
        $tema = $_POST['tema'];
        $msg3 = "✅ Tema: " . htmlspecialchars($tema);
    } else {
        $msg3 = "No has marcado ningún tema";
    }
}
PHP;
$form3 = <<<'HTML'
<form method="post">
    <label><input type="radio" name="tema" value="claro" <?= ($tema == "claro") ? "checked" : "" ?>> Claro</label>
    <label><input type="radio" name="tema" value="oscuro" <?= ($tema == "oscuro") ? "checked" : "" ?>> Oscuro</label>
    <button name="form" value="radio">Enviar</button>
</form>
HTML;

// ---------- 4. Checkbox simple y checkbox múltiple name="algo[]" ----------
$proc4 = <<<'PHP'
$opciones = ["php" => "PHP", "sql" => "SQL", "js" => "JavaScript", "css" => "CSS"];
$marcadas = [];           // las que vienen marcadas (para volver a marcarlas)
$acepto = false;
$msg4 = "";

if ($enviado == "checkbox") {
    $acepto = isset($_POST['acepto']);                 // checkbox simple: ¿existe?

    if (isset($_POST['lenguajes'])) {                  // múltiple: llega un ARRAY
        $marcadas = $_POST['lenguajes'];
    }

    if (!$acepto) {
        $msg4 = "Tienes que aceptar las condiciones";
    } elseif (count($marcadas) == 0) {
        $msg4 = "Marca al menos un lenguaje";
    } else {
        $msg4 = "✅ Has marcado " . count($marcadas) . ": " . implode(", ", $marcadas);
    }
}
PHP;
$form4 = <<<'HTML'
<form method="post">
    <?php foreach ($opciones as $valor => $texto): ?>
        <label>
            <input type="checkbox" name="lenguajes[]" value="<?= $valor ?>" <?= in_array($valor, $marcadas) ? "checked" : "" ?>>
            <?= $texto ?>
        </label>
    <?php endforeach; ?>
    <br>
    <label><input type="checkbox" name="acepto" <?= $acepto ? "checked" : "" ?>> Acepto las condiciones</label>
    <button name="form" value="checkbox">Enviar</button>
</form>
HTML;

// ---------- 5. readonly, disabled y hidden ----------
$form5 = <<<'HTML'
<form method="post">
    readonly: <input type="text" name="cod_emp" value="ana01" readonly>
    disabled: <input type="text" name="sueldo" value="3200" disabled>
    <input type="hidden" name="secreto" value="42">
    <button name="form" value="especiales">Enviar</button>
</form>
HTML;

// ---------- 6. textarea ----------
$proc6 = <<<'PHP'
$comentario = "";
$msg6 = "";

if ($enviado == "textarea") {
    $comentario = trim($_POST['comentario']);
    if ($comentario == "") {
        $msg6 = "El comentario está vacío";
    } else {
        $msg6 = "✅ " . strlen($comentario) . " caracteres";
    }
}
PHP;
$form6 = <<<'HTML'
<form method="post">
    <textarea name="comentario" rows="3" cols="50"><?= htmlspecialchars($comentario) ?></textarea>
    <button name="form" value="textarea">Enviar</button>
</form>
HTML;

eval($proc1);
eval($proc2);
eval($proc3);
eval($proc4);
eval($proc6);

/** Pinta el formulario, su mensaje y lo que ha llegado (si es el que se ha enviado) */
function pintarFormulario(string $plantilla, string $id, string $msg = ""): void
{
    echo '<div class="form-lab">';
    eval('global $enviado, $nombre, $edad, $ciudades, $ciudadElegida, $tema, $opciones, $marcadas, $acepto, $comentario; ?>' . $plantilla);
    if ($GLOBALS['enviado'] == $id) {
        if ($msg != "") {
            echo '<div class="resultado ' . (str_starts_with($msg, '✅') ? '' : 'mal') . '">' . $msg . '</div>';
        }
        echo '<div style="margin-top:10px">' . volcado('Lo que ha llegado: $_POST', $_POST) . '</div>';
    }
    echo '</div>';
}

cabecera("Formularios", "Cada tipo de campo, cómo se escribe en HTML y <b>qué llega exactamente a PHP</b>. "
    . "Envía cada formulario y mira el volcado de <code>\$_POST</code> que aparece debajo.");

seccion("Chuleta rápida");
chuleta([
    ['value="<?= $nombre ?>"', 'El input vuelve a mostrar lo que escribió (guárdalo en una variable antes de validar)'],
    ['<option ... selected>', 'Deja marcada una opción del select (ternario en cada option)'],
    ['<input type="radio" ... checked>', 'Deja marcado un radio o un checkbox'],
    ['name="lenguajes[]"', 'Con <b>corchetes</b>: varios marcados llegan como <b>array</b>'],
    ['isset($_POST["acepto"])', 'Un checkbox <b>sin marcar NO se envía</b>: se comprueba con isset'],
    ['readonly', 'No se puede editar, pero <b>sí se envía</b>'],
    ['disabled', 'No se puede editar y <b>NO se envía</b>'],
    ['<input type="hidden">', 'No se ve, pero se envía (para pasar un dato de una petición a otra)'],
    ['<button name="x" value="y">', 'Varios botones con el mismo name: llega solo el value del pulsado'],
]);

// =====================================================================
seccion("1. Texto que conserva lo escrito");
// =====================================================================
pintarFormulario($form1, "texto", $msg1);
codigo("HTML", 'El <code>value</code> pinta la variable. La primera vez vale <code>""</code> y el campo sale vacío.', "?>\n" . $form1);
codigo("PHP", 'Se guarda lo escrito <b>antes</b> de validar, para que siga en el input aunque haya error.', $proc1,
    'escribe "abc" en la edad: sale el error y tu nombre sigue escrito.');

// =====================================================================
seccion("2. Select desde un array, con selected");
// =====================================================================
pintarFormulario($form2, "select", $msg2);
codigo("HTML", 'Un <code>foreach</code> pinta las opciones. El <code>value</code> es lo que llega a PHP; el texto es lo que ve el usuario.', "?>\n" . $form2);
codigo("PHP", 'Con <code>isset($ciudades[...])</code> compruebas que lo que llega existe (el usuario puede cambiar el value con F12).', $proc2,
    'añade "SEV" => "Sevilla" al array: aparece sola en el select.');

// =====================================================================
seccion("3. Radio con checked");
// =====================================================================
pintarFormulario($form3, "radio", $msg3);
codigo("HTML", 'Todos los radio del grupo se llaman igual. <code>checked</code> va en el que coincide.', "?>\n" . $form3);
codigo("PHP", 'Si no hay ninguno marcado, <code>$_POST[\'tema\']</code> no existe.', $proc3);

// =====================================================================
seccion("4. Checkbox simple y múltiple (name con [])");
// =====================================================================
pintarFormulario($form4, "checkbox", $msg4);
codigo("HTML", 'Con <code>name="lenguajes[]"</code> llega un array con los value marcados. Para volver a marcarlos: <code>in_array</code>.', "?>\n" . $form4);
codigo("PHP", 'Este es el patrón de examII (<code>tuser[]</code>) y del Ej2 (<code>templeados[]</code>): si no marcas ninguno, la clave no existe.', $proc4,
    'envía sin marcar nada y mira el volcado de <code>$_POST</code>: no aparece <code>lenguajes</code>.');

// =====================================================================
seccion("5. readonly, disabled y hidden", "Envíalo y mira qué campos llegan de verdad.");
// =====================================================================
pintarFormulario($form5, "especiales");
codigo("HTML", '<code>readonly</code> llega; <code>disabled</code> <b>no llega</b> (por eso en el formulario de Modificar nunca se pone disabled); '
    . '<code>hidden</code> llega aunque no se vea.', "?>\n" . $form5);
caja('aviso', 'Ojo con disabled', '<p>Un <code>&lt;select disabled&gt;</code> no tiene <code>readonly</code>: se usa disabled, pero entonces ese dato <b>no se envía</b>. '
    . 'En "Detalles" da igual porque no se guarda nada; en "Modificar" perderías el dato.</p>');

// =====================================================================
seccion("6. Textarea");
// =====================================================================
pintarFormulario($form6, "textarea", $msg6);
codigo("HTML", 'En un textarea el valor no va en <code>value</code>: va <b>entre las etiquetas</b>.', "?>\n" . $form6);
codigo("PHP", '<code>trim</code> evita que un comentario de solo espacios cuente como válido.', $proc6);

pie();
