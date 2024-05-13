<?php
require_once '../../../funciones/control-de-sesiones/session_control.php';
require_once '../../../config/database.php';
require_once '../../../clases/Profesor.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['teacher_id'])) {
    $profesor_id = $_POST['teacher_id'];

    try {
        $db = new Database();
        $conexion = $db->getConnection();
        $profesorObj = new Profesor($conexion);
        $profesorObj->teacher_id = $profesor_id;

        $profesor = $profesorObj->obtenerPorId($profesor_id);
        $foto_profesor = $profesor['photo'];

        if (!empty($foto_profesor)) {
            $rutaImagen = '../../../../public/multimedia/img/pagina-principal/profesores/uploads/' . $foto_profesor;

            if (file_exists($rutaImagen)) {
                unlink($rutaImagen);
            }
        }

        if (!$profesorObj->eliminar()) {
            throw new Exception("No se pudo eliminar el profesor.");
        }

        $_SESSION['mensaje'] = "El profesor ha sido eliminado correctamente.";
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
    }

    header("Location: ../../../vistas/gestion-director.php#gestion-de-profesores");
    exit();
} else {
    header("Location: ../../../vistas/gestion-director.php#gestion-de-profesores");
    exit();
}
?>
