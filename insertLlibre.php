<?php
require_once __DIR__ . '/config.php';

$mensaje = '';
$error = false;
$titol = '';
$numPag = '';
$reu = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titolInput = $_POST['titol'] ?? '';
    $numPagInput = $_POST['numPag'] ?? '';
    $reuInput = $_POST['reu'] ?? '';
    $titol = is_string($titolInput) ? trim($titolInput) : '';
    $numPagRaw = is_string($numPagInput) ? trim($numPagInput) : '';
    $reu = is_string($reuInput) ? trim($reuInput) : '';
    $numPag = $numPagRaw;

    $numPagValid = filter_var($numPagRaw, FILTER_VALIDATE_INT);
    $reuValid = preg_match('/^\d+(?:\.\d{1,2})?$/', $reu) === 1
        && (float) $reu <= 99999999.99;
    $titolValid = preg_match('/^.{1,255}$/us', $titol) === 1;

    if (!$titolValid || $numPagValid === false || $numPagValid <= 0 || !$reuValid) {
        $mensaje = 'Error: introduce un título, un número de páginas mayor que cero y un precio válido.';
        $error = true;
    } else {
        $titolSeguro = mysqli_real_escape_string($conexion, $titol);
        $numPagSql = (int) $numPagValid;
        $reuSql = number_format((float) $reu, 2, '.', '');
        $consulta = "INSERT INTO libros (titol, numPag, reu) VALUES ('$titolSeguro', $numPagSql, $reuSql)";
        $resultado = mysqli_query($conexion, $consulta);

        if ($resultado) {
            $mensaje = 'El libro se ha insertado correctamente.';
            $titol = '';
            $numPag = '';
            $reu = '';
        } else {
            $mensaje = 'Error al insertar el libro: ' . mysqli_error($conexion);
            $error = true;
        }
    }

}

mysqli_close($conexion);
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro de libros</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 40px auto; }
        form { display: grid; gap: 12px; }
        label { display: grid; gap: 4px; }
        input { padding: 8px; }
        button { padding: 10px; cursor: pointer; }
        .mensaje { padding: 12px; background: #dff0d8; }
        .error { background: #f2dede; }
    </style>
</head>
<body>
    <h1>Registrar libro</h1>

    <?php if ($mensaje !== ''): ?>
        <p class="mensaje<?= $error ? ' error' : '' ?>"><?= htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <form method="post" action="insertLlibre.php">
        <label>
            Título del libro
            <input type="text" name="titol" required maxlength="255"
                   value="<?= htmlspecialchars($titol, ENT_QUOTES, 'UTF-8') ?>">
        </label>

        <label>
            Número de páginas
            <input type="number" name="numPag" min="1" step="1" required
                   value="<?= htmlspecialchars((string) $numPag, ENT_QUOTES, 'UTF-8') ?>">
        </label>

        <label>
            Precio
            <input type="number" name="reu" min="0" max="99999999.99" step="0.01" required
                   value="<?= htmlspecialchars($reu, ENT_QUOTES, 'UTF-8') ?>">
        </label>

        <button type="submit">Insertar libro</button>
    </form>
</body>
</html>
