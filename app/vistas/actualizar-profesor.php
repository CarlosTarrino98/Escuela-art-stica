<?php
require_once '../funciones/control-de-sesiones/session_control.php';
require_once '../config/database.php';
require_once '../clases/Profesor.php';

$db = new Database();
$conexion = $db->getConnection();

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['teacher_id'])) {
    $teacher_id = $_GET['teacher_id'];

    $profesorObj = new Profesor($conexion);
    $profesorObj->teacher_id = $teacher_id;
    $profesor = $profesorObj->obtenerPorId($teacher_id);

    if ($profesor) {
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Profesor - Artistic's School</title>
    <link rel="stylesheet" href="../../public/css/formularios.css">
</head>
<body>
    <header>
        <h1>Editar Profesor</h1>
        <?php include 'components/nav.php'; ?>
    </header>
    <section>
        <form action="../funciones/GestionDelDirector/gestion-profesores/procesar-actualizar-profesor.php" method="post" enctype="multipart/form-data">
            <h2>Editar Profesor</h2>

            <input type="hidden" name="teacher_id" value="<?php echo htmlspecialchars($profesor['teacher_id']); ?>">

            <label for="first_name">Nombre:</label>
            <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($profesor['first_name']); ?>" required>

            <label for="last_name">Apellido:</label>
            <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($profesor['last_name']); ?>" required>

            <label for="description">Descripción:</label>
            <textarea id="description" name="description"><?php echo htmlspecialchars($profesor['description']); ?></textarea>

            <label for="photo">Foto:</label>
            <?php if ($profesor['photo']): ?>
                <img src="../../public/multimedia/img/pagina-principal/profesores/uploads/<?php echo $profesor['photo']; ?>" alt="Foto del Profesor">
            <?php endif; ?>
            <input type="file" name="photo" id="photo">

            <input type="submit" value="Actualizar Profesor">
        </form>
    </section>
    <?php include 'components/footer.php'; ?>
</body>
</html>
<?php
    } else {
        echo "Profesor no encontrado.";
    }
} else {
    echo "ID del profesor no proporcionado.";
}
?>
