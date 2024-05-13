<?php
require_once '../funciones/control-de-sesiones/session_control.php';
require_once '../config/database.php';
require_once '../clases/Curso.php';
require_once '../clases/Profesor.php';

$db = new Database();
$conexion = $db->getConnection();

$profesorObj = new Profesor($conexion);
$listaProfesores = $profesorObj->listar()->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['curso_id'])) {
    $curso_id = $_GET['curso_id'];

    // Crear una instancia de la clase Curso
    $cursoObj = new Curso($conexion);

    try {
        // Establecer el ID del curso en el objeto Curso
        $cursoObj->course_id = $curso_id;

        // Obtener los datos del curso por su ID
        $curso = $cursoObj->obtenerPorId();

        // Verificar si se encontró el curso
        if ($curso) {
            // Mostrar el formulario de edición con los datos del curso encontrado
?>
            <!DOCTYPE html>
            <html lang="es">

            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Editar Curso - Artistic's School</title>
                <link rel="stylesheet" href="../../public/css/formularios.css">
                <link rel="preconnect" href="https://fonts.googleapis.com">
                <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
                <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
            </head>

            <div id="click-effect-container"></div>

            <body>
                <header>
                    <h1>Editar Curso - Artistic's School</h1>
                    <?php include 'components/nav.php'; ?>
                </header>
                <section>
                    <form action="../funciones/GestionDelDirector/gestion-cursos/procesar-actualizar-curso.php" method="post" enctype="multipart/form-data">
                        <h2>Editar Curso</h2>

                        <input type="hidden" name="course_id" value="<?php echo htmlspecialchars($curso['course_id']); ?>">

                        <label for="course_name">Nombre del Curso:</label>
                        <input type="text" id="course_name" name="course_name" value="<?php echo htmlspecialchars($curso['course_name']); ?>" required>

                        <label for="profesor_id">Profesor asignado:</label>
                        <select id="profesor_id" name="profesor_id" required>
                            <?php foreach ($listaProfesores as $profesor) : ?>
                                <option value="<?php echo $profesor['teacher_id']; ?>" <?php echo $profesor['teacher_id'] == $curso['profesor_id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($profesor['first_name'] . ' ' . $profesor['last_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        
                        <label for="cover_image">Imagen de Portada Actual:</label>
                        <?php if (!empty($curso['cover_image'])) : ?>
                            <img src="../../public/multimedia/img/pagina-principal/cursos/uploads/<?php echo htmlspecialchars($curso['cover_image']); ?>" alt="Imagen de Portada Actual">
                        <?php else : ?>
                            <p>Sin Imagen</p>
                        <?php endif; ?>
                        <input type="file" name="cover_image" id="cover_image">


                        <div class="asignaturas">
                            <label>Asignaturas:</label>
                            <?php
                            foreach ($curso['asignaturas'] as $asignatura) {
                                echo '<input type="text" name="asignaturas[]" value="' . htmlspecialchars($asignatura) . '" placeholder="Asignatura">';
                            }
                            ?>
                        </div>


                        <input type="submit" value="Actualizar Curso">
                    </form>
                </section>
                <?php include 'components/footer.php'; ?>
                <script src="../../public/js/efecto-click.js"></script>
            </body>

            </html>
<?php
        } else {
            echo "No se encontró el curso.";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "ID de curso no proporcionado.";
}
?>