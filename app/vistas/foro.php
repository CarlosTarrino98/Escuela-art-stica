<?php
session_start();

require_once '../clases/Usuario.php';
require_once '../clases/Curso.php';
require_once '../clases/Foro.php';
require_once '../clases/Respuestas-foro.php';
require_once '../config/database.php';

$db = new Database();
$conexion = $db->getConnection();

$cursoObj = new Curso($conexion);
$foroObj = new Foro($conexion);

// Aquí recuperas el user_id de la sesión
$user_id = $_SESSION['user_id'] ?? null;

// Obtener lista de cursos
$cursos = $cursoObj->listar();

$selectedCourseId = $_GET['course_id'] ?? null;
$busqueda = $_GET['busqueda'] ?? '';

if (!empty($busqueda)) {
    // Cargar temas basados en la búsqueda
    $temasRecientes = $foroObj->buscarTemasPorTitulo($busqueda);
} elseif ($selectedCourseId) {
    if ($selectedCourseId == 'all') {
        // Cargar todos los temas
        $temasRecientes = $foroObj->listarTemasConDetallesUsuario();
    } else {
        // Cargar temas filtrados por el curso seleccionado
        $temasRecientes = $foroObj->obtenerTemasPorCurso($selectedCourseId);
    }
} else {
    // Si no se selecciona un curso, también cargar todos los temas
    $temasRecientes = $foroObj->listarTemasConDetallesUsuario();
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foro - Artistic's School</title>
    <link rel="stylesheet" href="../../public/css/estilos-foro.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
</head>

<div id="click-effect-container"></div>

<body>
    <header>
        <h1>Foro</h1>
        <?php include 'components/nav.php'; ?>
    </header>

    <main>
        <section id="buscador-foro">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get">
                <input type="text" name="busqueda" placeholder="Buscar en el foro por título del tema" value="<?= htmlspecialchars($busqueda); ?>">
                <button type="submit">Buscar</button>
            </form><br>
        </section>

        <section id="cursos-foro">
            <h2>Cursos</h2>
            <p>Ver todos los temas o filtrar por curso:</p>
            <a href="foro.php#temas" class="mostrar-todos">Mostrar todos los temas </a>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get">
                <select name="course_id" onchange="this.form.submit()">
                    <option disabled selected value="">Selecciona un curso</option>
                    <?php foreach ($cursos as $curso) : ?>
                        <option value="<?= $curso['course_id'] ?>" <?= (isset($_GET['course_id']) && $_GET['course_id'] == $curso['course_id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($curso['course_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
        </section>

        <section id="temas">
            <h2>Temas</h2>
            <?php foreach ($temasRecientes as $tema) : ?>
                <div class="tema-item">
                    <h3><?= htmlspecialchars($tema['title']) ?></h3>
                    <p><?= htmlspecialchars($tema['content']) ?></p>
                    <p>Publicado por: <b><?= htmlspecialchars($tema['first_name']) . " " . htmlspecialchars($tema['last_name']) ?> (<?= htmlspecialchars($tema['role']) ?>)</b></p>

                    <!-- Botones de Editar y Eliminar para el tema -->
                    <?php if ($tema['user_id'] == $user_id) : ?>
                        <a href="actualizar-tema.php?topic_id=<?= $tema['topic_id'] ?>">Editar</a>
                        <a href="../funciones/foro/eliminar-tema.php?topic_id=<?= $tema['topic_id'] ?>" onclick="return confirm('¿Estás seguro de querer eliminar este tema?');">Eliminar</a>
                    <?php endif; ?>

                    <!-- Respuestas al tema -->
                    <div class="respuestas">
                        <?php
                        $respuestasForo = new RespuestasForo($conexion);
                        $respuestasForo->topic_id = $tema['topic_id'];
                        $respuestas = $respuestasForo->readByTopicId()->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($respuestas as $respuesta) {
                            echo "<div class='respuesta'>";
                            echo "<p>" . htmlspecialchars($respuesta['content']) . "</p>";
                            echo "<p>Respondido por: " . "<b>" . htmlspecialchars($respuesta['first_name']) . " " . htmlspecialchars($respuesta['last_name']) . " " . "(". htmlspecialchars($tema['role']) . ")". "</b>" . "</p>";

                            // Verifica si la sesión actual pertenece al usuario que escribió la respuesta
                            if ($respuesta['user_id'] == $user_id) {
                                echo "<a href='actualizar-respuesta.php?reply_id=" . $respuesta['reply_id'] . "'>Editar</a>";
                                echo "<a href='../funciones/foro/respuestas/eliminar-respuesta.php?reply_id=" . $respuesta['reply_id'] . "' onclick='return confirm(\"¿Estás seguro de querer eliminar esta respuesta?\");'>Eliminar</a>";
                            }
                            echo "</div>";
                        }
                        ?>

                        <!-- Formulario para añadir una nueva respuesta -->
                        <div class="formulario-respuesta">
                            <form action="../funciones/foro/respuestas/procesar-respuesta.php" method="post">
                                <textarea name="contenido-respuesta" placeholder="Escribe tu respuesta aquí"></textarea>
                                <input type="hidden" name="topic_id" value="<?= $tema['topic_id']; ?>">
                                <input type="hidden" name="user_id" value="<?= $user_id; ?>">
                                <button type="submit">Responder</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </section>

        <section id="crear-tema">
            <h2>Crear un Nuevo Tema</h2>
            <form action="../funciones/foro/crear-tema.php" method="post">
                <input type="hidden" name="user_id" value="<?= htmlspecialchars($user_id) ?>">

                <select name="curso-tema" id="curso-tema" required>
                    <option value="">Seleccione un curso</option>
                    <?php foreach ($cursos as $curso) : ?>
                        <option value="<?= htmlspecialchars($curso['course_id']) ?>"><?= htmlspecialchars($curso['course_name']) ?></option>
                    <?php endforeach; ?>
                </select>

                <input type="text" name="titulo-tema" placeholder="Título del tema" required>

                <textarea name="contenido-tema" placeholder="Escribe tu mensaje aquí" required></textarea>

                <button type="submit">Publicar Tema</button>
            </form>
        </section>

    </main>

    <?php include 'components/footer.php'; ?>

    <script src="../../public/js/efecto-click.js"></script>
</body>

</html>