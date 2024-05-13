<?php
session_start();
require_once '../../../config/database.php';
require_once '../../../clases/Respuestas-foro.php';

// Asegúrate de que solo los usuarios logueados puedan acceder
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Conexión a la base de datos
$db = new Database();
$conexion = $db->getConnection();

// Crear instancia de la clase RespuestasForo
$respuestasForo = new RespuestasForo($conexion);

// Obtener el ID de la respuesta a eliminar
if (isset($_GET['reply_id'])) {
    $respuestasForo->reply_id = $_GET['reply_id'];

    // Aquí puedes agregar una verificación adicional para asegurarte de que
    // el usuario actual es el propietario de la respuesta o tiene permisos
    // para eliminarla.

    // Intentar eliminar la respuesta
    if ($respuestasForo->delete()) {
        $_SESSION['mensaje'] = "Respuesta eliminada correctamente.";
    } else {
        $_SESSION['error'] = "Error al intentar eliminar la respuesta.";
    }
} else {
    $_SESSION['error'] = "ID de respuesta no proporcionado.";
}

// Redireccionar de nuevo al foro o a la página de origen
header("Location: ../../../vistas/foro.php#temas");
exit();
?>
