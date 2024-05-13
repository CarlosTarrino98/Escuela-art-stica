<?php
session_start();
require_once '../config/database.php';
require_once '../clases/Usuario.php';
require_once '../clases/Profesor.php';
require_once '../clases/Consultas.php';

$db = new Database();
$conexion = $db->getConnection();
$usuarioObj = new Usuario($conexion);
$profesorObj = new Profesor($conexion);
$consultaObj = new Consultas($conexion); // Crear una instancia de la clase Consultas

// Llama al método para obtener todos los profesores
$profesores = $profesorObj->listarProfesores();
$consultas = $consultaObj->obtenerTodasLasConsultas();

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    // Si no hay un user_id en la sesión, redirige o muestra un mensaje de error
    // Por ejemplo: Redirigir a la página de inicio de sesión
    header("Location: acceso.php");
    exit();
}

$calificaciones = $usuarioObj->obtenerInfoAlumnosConCalificaciones();

$calificacionesPorUsuario = [];
foreach ($calificaciones as $calificacion) {
    if ($calificacion['user_id'] == $user_id) {
        $calificacionesPorUsuario[$calificacion['subject_id']] = $calificacion;
    }
}

$promedio = 0;
if (count($calificacionesPorUsuario) > 0) {
    $totalCalificaciones = array_sum(array_column($calificacionesPorUsuario, 'calification'));
    $promedio = $totalCalificaciones / count($calificacionesPorUsuario);
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aula Virtual - Artistic's School</title>
    <link rel="stylesheet" href="../../public/css/estilos-aula-acceso.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
</head>

<div id="click-effect-container"></div>

<body>
    <header>
        <h1>Aula Virtual</h1>
        <?php include 'components/nav.php'; ?>
    </header>

    <main>
        <section id="navegacion-aula">
            <nav>
                <ul>
                    <li><a href="#presentacion"><img src="../../public/multimedia/img/aula-virtual/navegacion-aula/presentacion.png" alt="Presentación"><br>Presentación</a></li>
                    <li><a href="#curso"><img src="../../public/multimedia/img/aula-virtual/navegacion-aula/curso.png" alt="Curso"><br>Curso</a></li>
                    <li><a href="#foro"><img src="../../public/multimedia/img/aula-virtual/navegacion-aula/foro.png" alt="Foro"><br>Foro</a></li>
                    <li><a href="#consulta-a-profesores"><img src="../../public/multimedia/img/aula-virtual/navegacion-aula/consultas.png" alt="Consultas"><br>Consultas a profesores</a></li>
                </ul>
            </nav>
        </section>

        <section id="contenido-principal">
            <section id="presentacion">
                <h2>Bienvenidos al Aula Virtual</h2>
                <p>Este es tu espacio de aprendizaje y creación donde podrás acceder a todo el contenido de tus
                    cursos, interactuar con profesores y compañeros, y desarrollar tu proyecto artístico personal.
                    Aquí encontrarás una variedad de recursos educativos, desde video tutoriales y lecturas
                    recomendadas hasta ejercicios prácticos y evaluaciones interactivas. Todo está diseñado para
                    ofrecerte una experiencia de aprendizaje completa e integradora, que se adapta a tu ritmo y
                    estilo de aprendizaje.
                </p>
            </section>

            <section id="curso">
                <h2>Curso</h2>
                <p>En nuestra sección de cursos, puedes explorar y acceder a una amplia gama de materiales formativos
                    especializados en diferentes áreas artísticas. Ya sea que te apasione la pintura, la música, la
                    danza o la fotografía, aquí encontrarás cursos que se ajustan a tus intereses y te ayudarán a
                    alcanzar tus metas artísticas. Cada curso está cuidadosamente estructurado para guiarte paso a paso
                    en tu proceso de aprendizaje.
                </p>
                <table class="tabla">
                    <thead>
                        <tr>
                            <th>Asignatura</th>
                            <th>Calificación</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($calificacionesPorUsuario as $calificacion) : ?>
                            <tr>
                                <td><?= htmlspecialchars($calificacion['subject_name']) ?></td>
                                <td><?= htmlspecialchars($calificacion['calification']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>Promedio del Curso</td>
                            <td><?= htmlspecialchars($promedio) ?></td>
                        </tr>
                    </tfoot>
                </table>
            </section>

            <section id="consulta-a-profesores">
                <h2>Consultas con Profesores</h2>
                <div>
                    <p>¿Necesitas ayuda adicional o tienes alguna consulta? Contacta directamente con tus profesores aquí. Esta sección te permite realizar preguntas específicas y obtener orientación personalizada para tus proyectos artísticos. Nuestros profesores están aquí para apoyarte en tu proceso creativo, ayudándote a superar cualquier desafío y a aprovechar al máximo tu experiencia de aprendizaje en Artistic's School.</p>
                </div>

                <div id="formulario-consulta">
                    <h3>Realizar una Consulta al Profesor</h3>
                    <form action="../funciones/consultas/crear-consulta.php" method="post">
                        <input type="text" name="subject" placeholder="Asunto de la consulta" required>
                        <textarea name="message" placeholder="Escribe tu consulta aquí" required></textarea>
                        <button type="submit">Enviar Consulta</button>
                    </form>
                </div>

                <div id="historial-de-consultas">
                    <h3>Historial de consultas</h3>
                    <?php foreach ($consultas as $consulta) : ?>
                        <?php if ($consulta['student_id'] == $user_id) : ?>
                            <div class="consulta">
                                <h3><?= $consulta['subject'] ?></h3>
                                <p><?= $consulta['message'] ?></p>
                                <p><strong>Por: <?= $consulta['first_name'] ?> <?= $consulta['last_name'] ?></strong></p>
                                <?php if (!empty($consulta['respuesta'])) : ?>
                                    <div class="respuesta">
                                        <h4>Respuesta del Profesor</h4>
                                        <p><?= $consulta['respuesta'] ?></p>
                                    </div>
                                <?php endif; ?>
                                <!-- Agregar un formulario para eliminar la consulta -->
                                <form action="../funciones/consultas/eliminar-consulta.php" method="post">
                                    <input type="hidden" name="consulta_id" value="<?= $consulta['consultation_id'] ?>">
                                    <button type="submit">Eliminar Consulta</button>
                                </form>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
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