<?php
require_once '../funciones/control-de-sesiones/session_control.php';
require_once '../config/database.php';
require_once '../clases/Usuario.php';
require_once '../clases/Curso.php';

$db = new Database();
$conexion = $db->getConnection();

$usuarioObj = new Usuario($conexion);
$cursoObj = new Curso($conexion);
$cursos = $cursoObj->listar();

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    try {
        // Establecer el ID del alumno en el objeto Usuario
        $usuarioObj->user_id = $user_id;

        // Obtener los datos del alumno por su ID
        $alumno = $usuarioObj->getById();

        // Verificar si se encontró el alumno
        if ($alumno) {
            // Mostrar el formulario de edición con los datos del alumno encontrado
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
                    <h1>Editar Alumno - Artistic's School</h1>
                    <?php include 'components/nav.php'; ?>
                </header>

                <div id="click-effect-container"></div>

                <section>
                    <form action="../funciones/GestionDelDirector/gestion-alumnos/procesar-actualizar-alumno.php" method="post">
                        <h2>Editar Alumno</h2>

                        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($alumno['user_id']); ?>">

                        <label for="first_name">Nombre:</label>
                        <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($alumno['first_name']); ?>" required>

                        <label for="last_name">Apellido:</label>
                        <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($alumno['last_name']); ?>" required>

                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($alumno['email']); ?>" required>

                        <label for="new_password">Nueva Contraseña (dejar en blanco para no cambiar):</label>
                        <input type="password" id="new_password" name="new_password" placeholder="Nueva Contraseña">

                        <label for="phone">Teléfono:</label>
                        <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($alumno['phone']); ?>">

                        <label for="address">Dirección:</label>
                        <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($alumno['address']); ?>">

                        <label for="course_id">Curso asignado:</label>
                        <select id="course_id" name="course_id">
                            <option value="">Seleccione un curso</option>
                            <?php foreach ($cursos as $curso) : ?>
                                <option value="<?php echo htmlspecialchars($curso['course_id']); ?>" <?php echo $curso['course_id'] == $alumno['course_id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($curso['course_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <input type="submit" value="Actualizar Alumno">
                    </form>
                </section>
                <?php include 'components/footer.php'; ?>
                <script src="../../public/js/efecto-click.js"></script>
            </body>

            </html>
<?php
        } else {
            echo "Alumno no encontrado.";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "ID de alumno no proporcionado.";
}
?>