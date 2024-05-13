<?php
require_once '../funciones/control-de-sesiones/session_control.php';
require_once '../config/database.php';
require_once '../clases/Curso.php';
require_once '../clases/Profesor.php';


$db = new Database();
$conexion = $db->getConnection();

$profesorObj = new Profesor($conexion);
$stmt = $profesorObj->listar();
$profesores = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Curso - Artistic's School</title>
    <link rel="stylesheet" href="../../public/css/formularios.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
</head>

<div id="click-effect-container"></div>

<body>
    <header>
        <h1>Crear Curso - Artistic's School</h1>
        <?php include 'components/nav.php'; ?>
    </header>
    <section>
        <form action="../funciones/GestionDelDirector/gestion-cursos/procesar-crear-curso.php" method="post" enctype="multipart/form-data">
            <h2>Crear un Nuevo Curso</h2>

            <label for="course_name">Nombre del Curso:</label>
            <input type="text" id="course_name" name="course_name" placeholder="Nombre del curso" required>

            <label for="profesor_id">Profesor asignado:</label>
            <select id="profesor_id" name="profesor_id" required>
                <option value="">Seleccione un profesor</option>
                <?php foreach ($profesores as $profesor) : ?>
                    <option value="<?php echo htmlspecialchars($profesor['teacher_id']); ?>">
                        <?php echo htmlspecialchars($profesor['first_name'] . " " . $profesor['last_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="cover_image">Imagen de Portada:</label>
            <input type="file" name="cover_image" id="cover_image">

            <div class="asignaturas">
                <label>Asignaturas:</label>
                <input type="text" name="asignaturas[]" placeholder="Asignatura 1">
                <input type="text" name="asignaturas[]" placeholder="Asignatura 2">
                <input type="text" name="asignaturas[]" placeholder="Asignatura 3">
                <input type="text" name="asignaturas[]" placeholder="Asignatura 4">
                <input type="text" name="asignaturas[]" placeholder="Asignatura 5">
            </div>

            <input type="submit" value="Crear Curso">
        </form>
    </section>
    <?php include 'components/footer.php'; ?>
    <script src="../../public/js/efecto-click.js"></script>
</body>

</html>