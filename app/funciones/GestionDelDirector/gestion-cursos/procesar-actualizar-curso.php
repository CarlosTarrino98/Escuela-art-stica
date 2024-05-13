<?php
session_start();
require_once '../../../config/database.php';
require_once '../../../clases/Curso.php';

$db = new Database();
$conexion = $db->getConnection();
$cursoObj = new Curso($conexion);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['course_id'])) {
    try {
        $cursoObj->course_id = $_POST['course_id'];
        $cursoObj->course_name = $_POST['course_name'];
        $cursoObj->profesor_id = $_POST['profesor_id'];

        // Conservar la imagen actual si no se carga una nueva
        $cursoActual = $cursoObj->obtenerPorId($_POST['course_id']);
        $cursoObj->cover_image = $cursoActual['cover_image'];

        // Si se carga una nueva imagen, reemplazar la existente
        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] == 0) {
            $carpetaDestino = "../../../../public/multimedia/img/pagina-principal/cursos/uploads/";
            $nombreArchivo = uniqid() . "-" . basename($_FILES["cover_image"]["name"]);
            $rutaDestino = $carpetaDestino . $nombreArchivo;

            if (move_uploaded_file($_FILES["cover_image"]["tmp_name"], $rutaDestino)) {
                $cursoObj->cover_image = $nombreArchivo;
            } else {
                throw new Exception("Error al subir la imagen.");
            }
        }

        $cursoObj->asignaturas = isset($_POST['asignaturas']) ? $_POST['asignaturas'] : [];
        $cursoObj->actualizar();
        $_SESSION['mensaje'] = "El curso se actualizó correctamente.";
    } catch (Exception $e) {
        $_SESSION['error'] = "Error al actualizar el curso: " . $e->getMessage();
    }

    header("Location: ../../../vistas/gestion-director.php#gestion-de-cursos");
    exit();
} else {
    $_SESSION['error'] = "No se especificó un curso para actualizar.";
    header("Location: ../../../vistas/gestion-director.php#gestion-de-cursos");
    exit();
}

?>
