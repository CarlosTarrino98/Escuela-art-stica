<?php
require_once '../../../config/database.php';
require_once '../../../clases/Respuestas-foro.php';

$db = new Database();
$conexion = $db->getConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reply_id'])) {
    $reply_id = $_POST['reply_id'];
    $content = $_POST['content'];

    // Crear una instancia de la clase RespuestasForo
    $respuestasForo = new RespuestasForo($conexion);

    // Establecer las propiedades
    $respuestasForo->reply_id = $reply_id;
    $respuestasForo->content = $content;

    // Intentar actualizar la respuesta
    if ($respuestasForo->update()) {
        // Redirigir al foro con un mensaje de éxito
        header('Location: ../../../vistas/foro.php?mensaje=Respuesta actualizada con éxito#temas');
    } else {
        // Redirigir al foro con un mensaje de error
        header('Location: ../../../vistas/foro.php?error=Error al actualizar la respuesta#temas');
    }
} else {
    // Redirigir al foro si no se proporcionó un ID de respuesta o si no se utiliza el método POST
    header('Location: ../../../vistas/foro.php?error=Acceso denegado#temas');
}
?>
