<?php
require_once '../funciones/control-de-sesiones/session_control.php';
require_once '../config/database.php';
require_once '../clases/Curso.php';

$db = new Database();
$conexion = $db->getConnection();
$cursoObj = new Curso($conexion);
$listaCursos = $cursoObj->listar();

// Verificar si ya se ha enviado el formulario de acceso o registro
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Incluir los scripts de procesamiento de login y registro
    require_once '../funciones/acceso/login.php';
    require_once '../funciones/acceso/registrar.php';
}

if (isset($_SESSION['error'])) : ?>
    <div class="alert alert-danger" role="alert">
        <?php echo $_SESSION['error']; ?>
        <?php unset($_SESSION['error']); // Limpia el mensaje después de mostrarlo 
        ?>
    </div>
<?php endif;
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Artistic's School</title>
    <link rel="stylesheet" href="../../public/css/estilos-aula-acceso.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
</head>

<div id="click-effect-container"></div>

<body>
    <header>
        <h1>ACCESO</h1>
        <?php include 'components/nav.php'; ?>
    </header>

    <section id="formularios">
        <form action="../funciones/acceso/login.php" method="post">
            <h2>Formulario de Acceso</h2>

            <div>
                <label for="login-email">Email:</label>
                <input type="email" id="login-email" name="email" required>
            </div>
            <div>
                <label for="login-password">Contraseña:</label>
                <input type="password" id="login-password" name="password" required>
            </div>
            <div class="botones">
                <button type="submit">Acceder</button>
            </div>
        </form>

        <form action="../funciones/acceso/registrar.php" method="post">
            <h2>Formulario de Registro</h2>

            <div>
                <label for="reg-email">Email:</label>
                <input type="email" id="reg-email" name="email" required>
            </div>
            <div>
                <label for="reg-password">Contraseña:</label>
                <input type="password" id="reg-password" name="password" required>
            </div>
            <div>
                <label for="reg-confirm-password">Confirmar Contraseña:</label>
                <input type="password" id="reg-confirm-password" name="confirm_password" required>
            </div>
            <div>
                <label for="reg-name">Nombre:</label>
                <input type="text" id="reg-name" name="name" required>
            </div>
            <div>
                <label for="reg-surname">Apellidos:</label>
                <input type="text" id="reg-surname" name="surname" required>
            </div>
            <div>
                <label for="reg-phone">Teléfono:</label>
                <input type="tel" id="reg-phone" name="phone">
            </div>
            <div>
                <label for="reg-address">Dirección:</label>
                <input type="text" id="reg-address" name="address">
            </div>
            <div>
                <label for="reg-role">Tipo de cuenta:</label>
                <select id="reg-role" name="role" required>
                    <option value="" disabled selected>Seleccione el tipo de cuenta</option>
                    <option value="alumno">Alumno</option>
                    <option value="profesor">Profesor</option>
                    <option value="director">Director</option>
                    <option value="administrador">Administrador</option>
                </select>
            </div>
            <!-- Haz lo de la eleccion del curso si es un alumno con php-->
            <div id="student-course-selection" style="display: none;">
                <label for="reg-course">Curso:</label>
                <select id="reg-course" name="course">
                    <option value="" disabled selected>Seleccione su curso</option>
                    <?php foreach ($listaCursos as $curso) : ?>
                        <option value="<?php echo htmlspecialchars($curso['course_id']); ?>"><?php echo htmlspecialchars($curso['course_name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="botones">
                <button type="submit">Registrarse</button>
                <button type="reset">Restablecer</button>
            </div>
        </form>
    </section>

    <?php include 'components/footer.php'; ?>

    <script src="../../public/js/efecto-click.js"></script>
    <script src="../../public/js/eleccion-de-curso.js"></script>

</body>

</html>