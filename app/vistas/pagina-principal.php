<?php
require_once '../funciones/control-de-sesiones/session_control.php';
require_once '../config/database.php';
require_once '../clases/Curso.php';
require_once '../clases/Profesor.php';

$db = new Database();
$conexion = $db->getConnection();

$cursoObj = new Curso($conexion);
$listaCursos = $cursoObj->listar();
$profesorObj = new Profesor($conexion);
$listaProfesores = $profesorObj->listar()->fetchAll(PDO::FETCH_ASSOC);

// Convertir las asignaturas de cada curso de cadena a array
foreach ($listaCursos as &$curso) {
    $curso['asignaturas'] = explode(', ', $curso['asignaturas']);
}
unset($curso); // Después del bucle, eliminar la referencia a &$curso

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página principal - Artistic's School</title>
    <link rel="stylesheet" href="../../public/css/estilos-pagina-principal.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
</head>

<div id="click-effect-container"></div>

<body>
    <header>
        <section id="imagen-cabecera">
            <img src="../../public/multimedia/img/pagina-principal/imagenes-de-fondo/imagen-de-cabecera.webp" alt="Imagen de inicio">
        </section>
        <h1>ARTISTIC'S SCHOOL</h1>
        <?php include 'components/nav.php'; ?>
        <hr>
    </header>

    <div id="menu">
        <nav>
            <ul>
                <li><a href="#imagen-cabecera">Portada</a></li>
                <li><a href="#quienes-somos">Quiénes somos</a></li>
                <li><a href="#cursos">Cursos</a></li>
                <li><a href="#centro">Centro</a></li>
                <li><a href="#profesores">Profesores</a></li>
                <li><a href="#acceso">Información de acceso</a></li>
            </ul>
        </nav>
    </div>

    <main>
        <section id="quienes-somos">
            <h2>Bienvenidos a Artistic's School</h2>
            <p>En el corazón de la expresión y la innovación, Artistic's School es más que un lugar de aprendizaje:
                es un espacio donde la pasión se encuentra con la posibilidad. Aquí, cada pincelada, cada nota y
                cada pixel son partes de un diálogo más grande entre los artistas y el mundo que los rodea.</p>
            <p>Nuestros programas están diseñados para inspirar a estudiantes de todas las edades y habilidades a
                explorar su creatividad sin límites. Con una amplia gama de cursos que abarcan desde las técnicas
                tradicionales de dibujo y pintura, hasta la fotografía digital, el diseño gráfico y las artes
                escénicas, ofrecemos un espectro completo de oportunidades para que encuentres tu voz única en el
                mundo del arte.</p>
            <p>Nuestros maestros, todos artistas y diseñadores practicantes, están comprometidos con la enseñanza
                basada en la experiencia, proporcionando una educación que se extiende más allá de los límites del
                aula. Cada lección es una oportunidad para el descubrimiento personal a través de la
                experimentación, la crítica constructiva y el diálogo continuo.</p>
            <p>Ya sea que estés dando tus primeros pasos en el arte o que estés buscando perfeccionar tus
                habilidades con una formación avanzada, la Escuela de Arte Creativa es tu compañera en el camino
                hacia la excelencia artística. Te invitamos a unirte a nosotros en esta aventura creativa y a dejar
                tu huella en el lienzo infinito que nos espera.</p>

            <p>Tu futuro artístico comienza aquí. Descubre tu pasión por el arte y desarrolla tu talento con
                nosotros.</p>
        </section>

        <div class="imagen-de-transiccion">
            <img src="../../public/multimedia/img/pagina-principal/imagenes-de-fondo/imagen-de-transicion1.png" alt="Imagen de transicción">
        </div>

        <section id="cursos">
            <h2>Nuestros Cursos</h2>
            <p>Ofrecemos una amplia gama de cursos en diversas disciplinas artísticas</p>
            <div id="diferentes-cursos">
                <?php foreach ($listaCursos as $curso) : ?>
                    <div>
                        <h3><?php echo htmlspecialchars($curso['course_name']); ?></h3>
                        <img src="../../public/multimedia/img/pagina-principal/cursos/uploads/<?php echo htmlspecialchars($curso['cover_image']); ?>" alt="<?php echo htmlspecialchars($curso['course_name']); ?>">
                        <ol>
                            <?php foreach ($curso['asignaturas'] as $asignatura) : ?>
                                <li><?php echo htmlspecialchars($asignatura); ?></li>
                            <?php endforeach; ?>
                        </ol>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <section id="centro">
            <h2>Nuestro Centro</h2>
            <div>
                <div class="detalles-centro">
                    <div>
                        <h3>Un Espacio para la Creatividad y el Arte</h3>
                        <p>Bienvenidos al corazón de Artistic's School, un espacio donde la creatividad florece y los
                            sueños
                            artísticos cobran vida. En nuestro centro, creemos firmemente que un entorno inspirador es
                            esencial
                            para
                            el desarrollo artístico, y hemos diseñado cada rincón pensando en ello.</p>
                    </div>

                    <div>
                        <img src="../../public/multimedia/img/pagina-principal/aulas/aula-de-dibujo.png" alt="Aula de dibujo">
                    </div>
                </div>

                <div class="detalles-centro">
                    <div>
                        <img src="../../public/multimedia/img/pagina-principal/aulas/aula-de-escultura.webp" alt="Aula de escultura">
                    </div>

                    <div>
                        <h3>Instalaciones Modernas y Equipadas</h3>
                        <p>Nuestras instalaciones combinan lo moderno con lo funcional, creando un ambiente ideal para
                            el
                            aprendizaje y la creación artística. Desde amplios estudios de pintura y escultura hasta
                            salas
                            de
                            música
                            y diseño digital, cada espacio está equipado con todo lo necesario para explorar y
                            perfeccionar
                            tu
                            arte.
                            Con tecnología de vanguardia y recursos de alta calidad a tu disposición, el único límite es
                            tu
                            imaginación.
                        </p>
                    </div>
                </div>

                <div class="detalles-centro">
                    <div>
                        <h3>Fomentando la Innovación y la Experimentación</h3>
                        <p>Cada espacio ha sido cuidadosamente diseñado no solo para proporcionar las herramientas
                            necesarias,
                            sino
                            también para inspirar y fomentar la innovación y la experimentación. Las áreas comunes y
                            salas
                            de
                            exposiciones ofrecen un lugar para compartir, discutir y exhibir trabajos, fomentando un
                            sentido
                            de
                            comunidad y colaboración artística.</p>
                    </div>

                    <div>
                        <img src="../../public/multimedia/img/pagina-principal/aulas/aula-de-diseño-digital.webp" alt="Aula de diseño digital">
                    </div>
                </div>

                <div class="detalles-centro">
                    <div>
                        <img src="../../public/multimedia/img/pagina-principal/aulas/aula-de-musica.webp" alt="Aula de musica">
                    </div>

                    <div>
                        <h3>Un Entorno Seguro y Acogedor</h3>
                        <p>La seguridad y el bienestar de nuestros estudiantes y docentes son primordiales. Las
                            instalaciones
                            están
                            equipadas con sistemas de ventilación adecuados, áreas de almacenamiento seguro y equipos de
                            seguridad,
                            garantizando un entorno de trabajo cómodo y protegido.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section id="profesores">
            <h2>Nuestros Profesores</h2>
            <div id="detalles-profes">
            <?php foreach ($listaProfesores as $profesor): ?>
                <div class="profesor">
                    <img src="../../public/multimedia/img/pagina-principal/profesores/uploads/<?php echo htmlspecialchars($profesor['photo']); ?>" alt="Foto del Profesor <?php echo htmlspecialchars($profesor['first_name']); ?>">
                    <h3>Profesor/a <?php echo htmlspecialchars($profesor['first_name']) . " " . htmlspecialchars($profesor['last_name']); ?></h3>
                    <p><?php echo htmlspecialchars($profesor['description']); ?></p>
                </div>
            <?php endforeach; ?>
            </div>
        </section>

        <section id="acceso">
            <h2>Información de Acceso</h2>

            <p>En Artistic's School, buscamos estudiantes apasionados por el arte, independientemente de su experiencia
                previa. Nuestro proceso de admisión es simple y está diseñado para entender tus intereses y aspiraciones
                artísticas.</p>

            <ol>
                <li><b>Solicitud en Línea: </b>Completa nuestra solicitud en línea, donde te pediremos información
                    básica y detalles sobre tu interés en el arte.</li>
                <li><b>Portafolio Creativo: </b>Envíanos un portafolio con tus obras. No buscamos perfección, sino
                    pasión y creatividad.</li>
                <li><b>Entrevista Personal: </b>Tendrás una entrevista con un miembro de nuestro equipo docente
                    para hablar sobre tus objetivos y expectativas.</li>
            </ol>

            <div id="orden-acceso">
                <div>
                    <h3>Requisitos</h3>
                    <ul>
                        <li>Edad mínima de 16 años.</li>
                        <li>Completar la solicitud en línea.</li>
                        <li>Portafolio de trabajos artísticos.</li>
                        <li>Carta de motivación explicando por qué quieres unirte a nuestra escuela.</li>
                    </ul>

                    <h3>Fechas Importantes</h3>
                    <ol>
                        <li><b>Apertura de Inscripciones: </b>01/03/2024</li>
                        <li><b>Fecha Límite para Solicitud: </b>31/05/2024</li>
                        <li><b>Entrevistas Personales: </b>06/2024</li>
                        <li><b>Anuncio de Admisiones: </b>15/07/2024</li>
                        <li><b>Inicio del Curso Académico: </b>01/09/2024</li>
                    </ol>
                </div>
                <div>
                    <img src="../../public/multimedia/img/pagina-principal/acceso/entrevista.webp" alt="acceso de alumnos">
                </div>
            </div>

            <h3>Información Adicional</h3>
            <ul>
                <li>Ofrecemos visitas guiadas a nuestras instalaciones para los aspirantes y sus familias. Por favor,
                    contacta con nuestra oficina para agendar una visita.</li>
                <li>Para estudiantes internacionales, proporcionamos asesoramiento sobre visados y alojamiento.</li>
                <li>Becas y ayudas financieras disponibles para candidatos elegibles.</li>
            </ul>

            <p>Para más información, no dudes en contactarnos llamando directamente
                a la oficina de admisiones de Artistic's School. ¡Estamos aquí para ayudarte en tu viaje artístico!
            </p>

        </section>
        <div class="imagen-de-transiccion">
            <img src="../../public/multimedia/img/pagina-principal/imagenes-de-fondo/imagen-de-transicion2.png" alt="Imagen de transicción">
        </div>
    </main>

    <?php include 'components/footer.php'; ?>

</body>

<script src="../../public/js/efecto-click.js"></script>

</html>