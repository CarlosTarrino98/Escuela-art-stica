<?php
session_start();

// Asegúrate de incluir las clases necesarias
require_once '../../../config/database.php';
require_once '../../../clases/Respuestas-foro.php';

// Obtener la conexión a la base de datos
$db = new Database();
$conexion = $db->getConnection();

// Crear una instancia de la clase RespuestasForo
$respuestasForo = new RespuestasForo($conexion);

// Comprobar si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger los datos del formulario
    $topic_id = $_POST['topic_id'] ?? null;
    $user_id = $_SESSION['user_id'] ?? null; // Asumiendo que el user_id se almacena en la sesión
    $contenido = $_POST['contenido-respuesta'] ?? '';

    // Establecer las propiedades de la respuesta
    $respuestasForo->topic_id = $topic_id;
    $respuestasForo->user_id = $user_id;
    $respuestasForo->content = $contenido;

    // Intentar guardar la respuesta en la base de datos
    if ($respuestasForo->create()) {
        // Respuesta guardada con éxito
        $_SESSION['mensaje'] = "Respuesta publicada con éxito.";
    } else {
        // Error al guardar la respuesta
        $_SESSION['error'] = "Ocurrió un error al publicar la respuesta.";
    }

    // Redirigir de nuevo al foro o al tema específico
    header("Location: ../../../vistas/foro.php#temas");
    exit();
}

// Redirigir al foro si se accede al script directamente
header("Location: ../../../vistas/foro.php#temas");
exit();
?>
