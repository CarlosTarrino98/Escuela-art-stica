<?php
require_once '../funciones/control-de-sesiones/session_control.php';
require_once '../config/database.php';
require_once '../clases/Curso.php';

$db = new Database();
$conexion = $db->getConnection();

// Obtener la lista de cursos disponibles
$cursoObj = new Curso($conexion);
$listaCursos = $cursoObj->listar();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Profesor - Artistic's School</title>
    <link rel="stylesheet" href="../../public/css/formularios.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
</head>

<div id="click-effect-container"></div>

<body>
    <header>
        <h1>Crear Profesor - Artistic's School</h1>
        <?php include 'components/nav.php'; ?>
    </header>
    <section>
        <form action="../funciones/GestionDelDirector/gestion-profesores/procesar-crear-profesor.php" method="post" enctype="multipart/form-data">
            <h2>Crear un Nuevo Profesor</h2>

            <label for="first_name">Nombre:</label>
            <input type="text" id="first_name" name="first_name" placeholder="Nombre del profesor" required>

            <label for="last_name">Apellido:</label>
            <input type="text" id="last_name" name="last_name" placeholder="Apellido del profesor" required>

            <label for="description">Descripción:</label>
            <textarea id="description" name="description" placeholder="Descripción del profesor" required></textarea>

            <label for="course_id">Curso Asignado:</label>
            <select name="course_id" id="course_id" required>
                <option value="">Selecciona un curso</option>
                <?php foreach ($listaCursos as $curso) : ?>
                    <option value="<?php echo $curso['course_id']; ?>"><?php echo $curso['course_name']; ?></option>
                <?php endforeach; ?>
            </select>

            <label for="photo">Foto del Profesor:</label>
            <input type="file" id="photo" name="photo" accept="image/*" required>

            <input type="submit" value="Crear Profesor">
        </form>
    </section>
    <?php include 'components/footer.php'; ?>
    <script src="../../public/js/efecto-click.js"></script>
</body>

</html>
