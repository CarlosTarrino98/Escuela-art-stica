<?php
require_once '../funciones/control-de-sesiones/session_control.php';
require_once '../config/database.php';
require_once '../clases/Usuario.php';
require_once '../clases/Consultas.php';

// Crear conexión a la base de datos
$db = new Database();
$conexion = $db->getConnection();
$usuarioObj = new Usuario($conexion);
$consultaObj = new Consultas($conexion);
// Obtener la información de los alumnos
// Obtener la información de los alumnos y sus calificaciones
$resultados = $usuarioObj->obtenerInfoAlumnosConCalificaciones();
$consultas = $consultaObj->obtenerTodasLasConsultas();
$consultas = $consultaObj->obtenerConsultasSinRespuesta();

// Procesar los resultados para agruparlos por alumno
$alumnos = [];
foreach ($resultados as $fila) {
    $alumnos[$fila['user_id']]['nombre'] = $fila['first_name'] . ' ' . $fila['last_name'];
    $alumnos[$fila['user_id']]['curso'] = $fila['course_name'];
    $alumnos[$fila['user_id']]['asignaturas'][$fila['subject_id']] = [
        'nombre' => $fila['subject_name'],
        'calificacion' => $fila['calification']
    ];
}


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de los profesores - Artistic's School</title>
    <link rel="stylesheet" href="../../public/css/estilos-gestion-profesores.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
</head>

<div id="click-effect-container"></div>

<body>
    <header>
        <h1>Gestión de los profesores</h1>
        <?php include 'components/nav.php'; ?>
    </header>

    <main>
        <section id="navegacion-gestion-profesores">
            <nav>
                <ul>
                    <li><a href="#presentación"><img src="../../public/multimedia/img/aula-virtual/navegacion-aula/presentacion.png" alt="Presentación"><br>Presentación</a></li>
                    <li><a href="#evaluacion-alumnos"><img src="../../public/multimedia/img/gestion-profesores/evaluacion.png" alt="evaluacion de alumnos"><br>Evaluación de alumnos</a></li>
                    <li><a href="#consultas-de-alumnos"><img src="../../public/multimedia/img/aula-virtual/navegacion-aula/consultas.png" alt="Consultas"><br>Consultas de alumnos</a></li>
                    <li><a href="#foro"><img src="../../public/multimedia/img/aula-virtual/navegacion-aula/foro.png" alt="Foro"><br>Foro</a></li>
                </ul>
            </nav>
        </section>

        <section id="contenido-principal">
            <section id="presentacion">
                <h2>Presentación</h2>
                <p>
                    Bienvenido a la sección de gestión para profesores. Aquí podrás encontrar herramientas y recursos
                    útiles para la administración de tus clases, evaluaciones, y consultas de alumnos. Utiliza esta
                    plataforma para mejorar la experiencia educativa en Artistic's School.
                </p>
            </section>

            <section id="evaluacion-alumnos">
                <h2>Evaluación de Alumnos</h2>
                <p>
                    Esta área está dedicada a la evaluación y seguimiento del rendimiento de los alumnos. Encuentra y
                    gestiona las evaluaciones, calificaciones y comentarios sobre el progreso de tus estudiantes.
                </p>
                <form method="post" action="../funciones/gestionProfesores/evaluacion-alumnos/procesar-evaluacion-alumno.php">
                    <table class="tabla">
                        <thead>
                            <tr>
                                <th>Nombre del Alumno</th>
                                <th>Curso</th>
                                <th>Asignaturas y Calificaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($alumnos as $user_id => $alumno) : ?>
                                <tr>
                                    <td><?= htmlspecialchars($alumno['nombre']) ?></td>
                                    <td><?= htmlspecialchars($alumno['curso']) ?></td>
                                    <td id="calificaciones">
                                        <ol>
                                            <?php foreach ($alumno['asignaturas'] as $subject_id => $asignatura) : ?>
                                                <li>
                                                    <label>
                                                        <?= htmlspecialchars($asignatura['nombre']) ?>:
                                                        <input type="number" name="grades[<?= $user_id ?>][<?= $subject_id ?>]" value="<?= $asignatura['calificacion'] ?>" min="0" max="10">
                                                    </label>
                                                </li>
                                            <?php endforeach; ?>
                                        </ol>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <button type="submit" class="action-link full-width">Actualizar Calificaciones</button>
                </form>
            </section>

            <section id="consultas-de-alumnos">
                <h2>Consultas de Alumnos</h2>
                <p>
                    En esta sección podrás manejar las consultas y dudas de tus alumnos. Responde a preguntas, ofrece
                    orientación adicional y mantén una comunicación efectiva con tus estudiantes para apoyar su proceso
                    educativo.
                </p>
                <div>
                    <?php
                    foreach ($consultas as $consulta) {
                        $tituloConsulta = htmlspecialchars($consulta['subject']); // Obtener el título de la consulta
                        $mensaje = htmlspecialchars($consulta['message']);
                        $nombreAlumno = htmlspecialchars($consulta['first_name'] . ' ' . $consulta['last_name']);
                        $consultaId = $consulta['consultation_id'];
                        echo "<div>";
                        echo "<h3>{$tituloConsulta}</h3>"; // Mostrar el título de la consulta
                        echo "<p>{$mensaje}</p>";
                        echo "<p><b>Escrito por: {$nombreAlumno}</b></p>";
                        // Formulario para responder a la consulta
                        echo "<form action='../funciones/consultas/responder-consulta.php' method='post'>";
                        echo "<input type='hidden' name='consulta_id' value='{$consultaId}'>";
                        echo "<textarea name='respuesta' placeholder='Escribe tu respuesta aquí' required ></textarea>";
                        echo "<button type='submit' class='action-link full-width'>Enviar Respuesta</button>";
                        echo "</form>";
                        echo "</div>";
                    }
                    ?>

                </div>
            </section>

            <section id="foro">
                <h2>Foro de Discusión</h2>
                <div>
                    <p>Participa en el foro para discutir temas del curso, compartir ideas y resolver dudas con la
                        comunidad de Artistic's School. Este espacio está diseñado para fomentar la colaboración y el
                        intercambio de conocimientos entre estudiantes y profesores. Aquí puedes plantear preguntas,
                        ofrecer respuestas, compartir tus proyectos y obtener retroalimentación constructiva. Únete a
                        las discusiones y forma parte de nuestra comunidad artística en crecimiento.
                    </p>
                    <a href="foro.php">Ir al Foro</a>
                </div>
            </section>
        </section>
    </main>

    <?php include 'components/footer.php'; ?>

    <script src="../../public/js/efecto-click.js"></script>
</body>

</html>