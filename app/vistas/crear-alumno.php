<?php
require_once '../funciones/control-de-sesiones/session_control.php';
require_once '../config/database.php';
require_once '../clases/Curso.php';

$db = new Database();
$conexion = $db->getConnection();

$cursoObj = new Curso($conexion);
$cursos = $cursoObj->listar();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Alumno - Artistic's School</title>
    <link rel="stylesheet" href="../../public/css/formularios.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
</head>

<div id="click-effect-container"></div>

<body>
    <header>
        <h1>Crear Alumno - Artistic's School</h1>
        <?php include 'components/nav.php'; ?>
    </header>
    <section>
        <form action="../funciones/GestionDelDirector/gestion-alumnos/procesar-crear-alumno.php" method="post">
            <h2>Crear un Nuevo Alumno</h2>

            <label for="first_name">Nombre:</label>
            <input type="text" id="first_name" name="first_name" placeholder="Nombre del alumno" required>

            <label for="last_name">Apellido:</label>
            <input type="text" id="last_name" name="last_name" placeholder="Apellido del alumno" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Email del alumno" required>

            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" placeholder="Contraseña" required>

            <label for="phone">Teléfono:</label>
            <input type="text" id="phone" name="phone" placeholder="Teléfono del alumno">

            <label for="address">Dirección:</label>
            <input type="text" id="address" name="address" placeholder="Dirección del alumno">

            <label for="course_id">Curso asignado:</label>
            <select id="course_id" name="course_id">
                <option value="">Seleccione un curso</option>
                <?php foreach ($cursos as $curso): ?>
                    <option value="<?php echo htmlspecialchars($curso['course_id']); ?>">
                        <?php echo htmlspecialchars($curso['course_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <input type="submit" value="Crear Alumno">
        </form>
    </section>
    <?php include 'components/footer.php'; ?>
    <script src="../../public/js/efecto-click.js"></script>
</body>
</html>
