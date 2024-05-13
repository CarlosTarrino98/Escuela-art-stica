<?php
require_once '../funciones/control-de-sesiones/session_control.php';
require_once '../config/database.php';
require_once '../clases/Curso.php';
require_once '../clases/Profesor.php';
require_once '../clases/Usuario.php';

$db = new Database();
$conexion = $db->getConnection();

$cursoObj = new Curso($conexion);
$listaCursos = $cursoObj->listar();

$profesorObj = new Profesor($conexion);
$listaProfesores = $profesorObj->listar();

$usuarioObj = new Usuario($conexion);
$listaUsuarios = $usuarioObj->listarAlumnos();

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión del director - Artistic's School</title>
    <link rel="stylesheet" href="../../public/css/estilos-gestion-directores.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
</head>

<div id="click-effect-container"></div>

<body>
    <header>
        <h1>Gestión del director</h1>
        <?php include 'components/nav.php'; ?>
    </header>

    <main>
        <section id="navegacion-gestion-director">
            <nav>
                <ul>
                    <li><a href="#presentacion"><img src="../../public/multimedia/img/aula-virtual/navegacion-aula/presentacion.png" alt="Presentación"><br>Presentación</a></li>
                    <li><a href="#gestion-de-cursos"><img src="../../public/multimedia/img/gestion-director/gestion-cursos.png" alt="Gestión de cursos"><br>Gestión de cursos</a></li>
                    <li><a href="#gestion-de-profesores"><img src="../../public/multimedia/img/gestion-director/gestion-profesores.png" alt="Gestión de profesores"><br>Gestión de profesores</a></li>
                    <li><a href="#gestion-de-alumnos"><img src="../../public/multimedia/img/gestion-director/gestion-alumnos.png" alt="Gestión de alumnos"><br>Gestión de alumnos</a></li>
                </ul>
            </nav>
        </section>

        <section id="contenido-principal">
            <section id="presentacion">
                <h2>Presentación</h2>
                <p>
                    Esta es la sección de gestión para el director de Artistic's School. Aquí podrás supervisar y
                    gestionar aspectos clave de la institución, incluyendo cursos y personal docente. Utiliza esta
                    plataforma para mantener el estándar educativo y administrativo de la escuela.
                </p>
            </section>

            <section id="gestion-de-cursos">
                <h2>Gestión de Cursos</h2>
                <p>
                    En esta área, podrás administrar todos los cursos ofrecidos en Artistic's School.
                    Esto incluye la creación, la actualización y la eliminación de nuevos cursos.
                    Mantén los cursos actualizados y relevantes para las necesidades de los estudiantes.
                </p>

                <table class="tabla">
                    <thead>
                        <tr>
                            <th>Nombre del Curso</th>
                            <th>Imagen de portada</th>
                            <th>Asignaturas</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($listaCursos as $curso) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($curso['course_name']); ?></td>
                                <td>
                                    <?php if ($curso['cover_image']) : ?>
                                        <img src="../../public/multimedia/img/pagina-principal/cursos/uploads/<?php echo htmlspecialchars($curso['cover_image']); ?>" alt="Imagen del Curso">
                                    <?php else : ?>
                                        Sin Imagen
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <ol>
                                        <?php
                                        $asignaturas = explode(',', $curso['asignaturas']);
                                        foreach ($asignaturas as $asignatura) :
                                            echo '<li>' . htmlspecialchars(trim($asignatura)) . '</li>';
                                        endforeach;
                                        ?>
                                    </ol>
                                </td>
                                <td>
                                    <!-- enlace para editar el curso -->
                                    <a href="actualizar-curso.php?curso_id=<?php echo htmlspecialchars($curso['course_id']); ?>" class="action-link">Editar curso</a>

                                    <!-- Formulario para eliminar el curso -->
                                    <form action="../funciones/GestionDelDirector/gestion-cursos/eliminar-curso.php" method="post" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este curso?')">
                                        <!-- Campo oculto para enviar el ID del curso -->
                                        <input type="hidden" name="curso_id" value="<?php echo htmlspecialchars($curso['course_id']); ?>">
                                        <!-- Botón para enviar el formulario de eliminación -->
                                        <button type="submit" class="action-button">Eliminar curso</button>
                                    </form>

                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- Enlace al formulario de creación de cursos -->
                <a href="crear-curso.php" class="action-link full-width">Crear nuevo curso</a>
            </section>

            <section id="gestion-de-profesores">
                <h2>Gestión de Profesores</h2>
                <p>
                    Esta sección está dedicada a la gestión del personal docente. Aquí podrás dar de alta, de baja y
                    modificar los datos del profesorado.
                </p>
                <table class="tabla">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Foto</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($listaProfesores as $profesor) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($profesor['first_name'] . ' ' . $profesor['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($profesor['description']); ?></td>
                                <td>
                                    <?php if ($profesor['photo']) : ?>
                                        <img src="../../public/multimedia/img/pagina-principal/profesores/uploads/<?php echo htmlspecialchars($profesor['photo']); ?>" alt="Foto del Profesor" id="img-profes">
                                    <?php else : ?>
                                        Sin Foto
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <!-- Enlace para editar al profesor -->
                                    <a href="actualizar-profesor.php?teacher_id=<?php echo $profesor['teacher_id']; ?>" class="action-link">Editar profesor</a>

                                    <!-- Formulario para eliminar al profesor -->
                                    <form action="../funciones/GestionDelDirector/gestion-profesores/eliminar-profesor.php" method="post" onsubmit="return confirm('¿Estás seguro de que quieres eliminar a este profesor?')">
                                        <!-- Campo oculto para enviar el ID del profesor -->
                                        <input type="hidden" name="teacher_id" value="<?php echo htmlspecialchars($profesor['teacher_id']); ?>">
                                        <!-- Botón para enviar el formulario de eliminación -->
                                        <button type="submit" class="action-button">Eliminar profesor</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- Enlace al formulario de creación de profesores -->
                <a href="crear-profesor.php" class="action-link full-width">Crear nuevo profesor</a>
            </section>

            <section id="gestion-de-alumnos">
                <h2>Gestión de Alumnos</h2>
                <p>
                    En este espacio, podrás administrar todo lo relacionado con los alumnos de Artistic's School. Desde
                    la inscripción hasta su baja. Supervisa y apoya el progreso de cada estudiante para garantizar una
                    experiencia educativa excepcional.
                </p>
                <table class="tabla">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Dirección</th>
                            <th>Curso</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($listaUsuarios as $usuario) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($usuario['first_name']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['phone']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['address']); ?></td>
                                <td>
                                    <?php
                                    if ($usuario['course_id']) {
                                        $cursoObj->course_id = $usuario['course_id'];
                                        $curso = $cursoObj->obtenerPorId();
                                        echo htmlspecialchars($curso['course_name']);
                                    } else {
                                        echo 'No asignado';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <a href="actualizar-alumno.php?user_id=<?php echo htmlspecialchars($usuario['user_id']); ?>" class="action-link">Editar</a>
                                    <form action="../funciones/GestionDelDirector/gestion-alumnos/eliminar-alumno.php" method="post" onsubmit="return confirm('¿Estás seguro de que quieres eliminar a este alumno?')">
                                        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($usuario['user_id']); ?>">
                                        <button type="submit" class="action-button">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <a href="crear-alumno.php" class="action-link full-width">Crear nuevo alumno</a>
            </section>
        </section>
    </main>

    <?php include 'components/footer.php'; ?>

    <script src="../../public/js/efecto-click.js"></script>
</body>

</html>