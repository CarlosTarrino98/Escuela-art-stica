<?php
require_once '../../../funciones/control-de-sesiones/session_control.php';
require_once '../../../config/database.php';
require_once '../../../clases/Curso.php';

$db = new Database();
$conexion = $db->getConnection();
$cursoObj = new Curso($conexion);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['curso_id'])) {
    try {
        // Establecer el ID del curso a eliminar
        $cursoObj->course_id = $_POST['curso_id'];

        // Obtener el curso por su ID para verificar la existencia de una imagen asociada
        $curso = $cursoObj->obtenerPorId();

        // Si se encontró el curso y tiene una imagen asociada, eliminar la imagen del sistema de archivos
        if ($curso && isset($curso['cover_image']) && !empty($curso['cover_image'])) {
            $rutaImagen = "../../../../public/multimedia/img/pagina-principal/cursos/uploads/" . $curso['cover_image'];
            if (file_exists($rutaImagen)) {
                unlink($rutaImagen); // Eliminar la imagen del sistema de archivos
            }
        }

        // Eliminar el curso de la base de datos
        $cursoObj->eliminar();

        // Agregar mensaje de éxito a la sesión
        $_SESSION['mensaje'] = "El curso se eliminó correctamente.";

        // Redireccionar al usuario a la página de gestión del director
        header("Location: ../../../vistas/gestion-director.php#gestion-de-cursos");
        exit(); // Asegurar que el script se detenga después de la redirección
    } catch (Exception $e) {
        // En caso de error, agregar mensaje de error a la sesión
        $_SESSION['error'] = "Error al eliminar el curso: " . $e->getMessage();
        header("Location: ../../../vistas/gestion-director.php#gestion-de-cursos"); // Redireccionar a la página de gestión del director
        exit(); // Asegurar que el script se detenga después de la redirección
    }
} else {
    // Si no se especifica un curso para eliminar, agregar mensaje de error a la sesión
    $_SESSION['error'] = "No se especificó un curso para eliminar.";
    header("Location: ../../../vistas/gestion-director.php#gestion-de-cursos"); // Redireccionar a la página de gestión del director
    exit(); // Asegurar que el script se detenga después de la redirección
}
?>
