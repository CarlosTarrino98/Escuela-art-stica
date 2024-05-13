<?php
require_once '../funciones/control-de-sesiones/session_control.php';
require_once '../config/database.php';
require_once '../clases/Respuestas-foro.php';

$db = new Database();
$conexion = $db->getConnection();

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['reply_id'])) {
    $reply_id = $_GET['reply_id'];

    $respuestasForo = new RespuestasForo($conexion);
    $respuestasForo->reply_id = $reply_id;
    $respuesta = $respuestasForo->obtenerRespuestaPorId();

    if ($respuesta) {
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Respuesta - Artistic's School</title>
    <link rel="stylesheet" href="../../public/css/formularios.css">
</head>
<body>
    <header>
        <h1>Editar Respuesta</h1>
        <?php include 'components/nav.php'; ?>
    </header>
    <section>
        <form action="../funciones/foro/respuestas/procesar-actualizar-respuesta.php" method="post">
            <h2>Editar Respuesta</h2>

            <input type="hidden" name="reply_id" value="<?= htmlspecialchars($respuesta['reply_id']); ?>">

            <label for="content">Respuesta:</label>
            <textarea id="content" name="content" required><?= htmlspecialchars($respuesta['content']); ?></textarea>

            <input type="submit" value="Actualizar Respuesta">
        </form>
    </section>
    <?php include 'components/footer.php'; ?>
</body>
</html>
<?php
    } else {
        echo "Respuesta no encontrada.";
    }
} else {
    echo "ID de la respuesta no proporcionado.";
}
?>
