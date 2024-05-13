<?php
require_once '../funciones/control-de-sesiones/session_control.php';
require_once '../config/database.php';
require_once '../clases/Foro.php';

$db = new Database();
$conexion = $db->getConnection();

$foroObj = new Foro($conexion);

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['topic_id'])) {
    $topic_id = $_GET['topic_id'];

    // Configurar el ID del tema en el objeto Foro
    $foroObj->topic_id = $topic_id;

    try {
        // Obtener los datos del tema por su ID
        $tema = $foroObj->obtenerTema();

        // Verificar si se encontró el tema
        if ($tema) {
            // Mostrar el formulario de edición con los datos del tema encontrado
?>
            <!DOCTYPE html>
            <html lang="es">

            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Editar alumno - Artistic's School</title>
                <link rel="stylesheet" href="../../public/css/formularios.css">
                <link rel="preconnect" href="https://fonts.googleapis.com">
                <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
                <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
            </head>

            <body>
                <header>
                    <h1>Editar Tema del Foro</h1>
                    <?php include 'components/nav.php'; ?>
                </header>

                <section>
                    <form action="../funciones/foro/procesar-actualizar-tema.php" method="post">
                        <h2>Editar Tema</h2>
                        <input type="hidden" name="topic_id" value="<?= htmlspecialchars($tema['topic_id']); ?>">

                        <label for="title">Título del Tema:</label>
                        <input type="text" id="title" name="title" value="<?= htmlspecialchars($tema['title']); ?>" required>

                        <label for="content">Contenido del Tema:</label>
                        <textarea id="content" name="content" required><?= htmlspecialchars($tema['content']); ?></textarea>

                        <input type="submit" value="Actualizar Tema">
                    </form>
                </section>

                <?php include 'components/footer.php'; ?>
                <script src="../../public/js/efecto-click.js"></script>
            </body>

            </html>
<?php
        } else {
            echo "No se encontró el tema.";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "ID del tema no proporcionado.";
}
?>