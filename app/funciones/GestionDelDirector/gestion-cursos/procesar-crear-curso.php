<?php
session_start(); // Inicia la sesión al principio del script

require_once '../../../config/database.php';
require_once '../../../clases/Curso.php';

$db = new Database();
$conexion = $db->getConnection();
$cursoObj = new Curso($conexion);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $cursoObj->course_name = $_POST['course_name'];
        $cursoObj->profesor_id = $_POST['profesor_id'];
        $cursoObj->asignaturas = isset($_POST['asignaturas']) ? array_filter($_POST['asignaturas']) : [];

        // Procesar la carga de la imagen
        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] == 0) {
            $carpetaDestino = "../../../../public/multimedia/img/pagina-principal/cursos/uploads/"; // Ajusta esta ruta
            $nombreArchivo = uniqid() . "-" . basename($_FILES["cover_image"]["name"]);
            $rutaDestino = $carpetaDestino . $nombreArchivo;

            if (move_uploaded_file($_FILES["cover_image"]["tmp_name"], $rutaDestino)) {
                $cursoObj->cover_image = $nombreArchivo; // Guarda solo el nombre del archivo
            } else {
                throw new Exception("Error al subir la imagen.");
            }
        }

        $cursoObj->crear();

        // Almacenar mensaje de éxito en la sesión
        $_SESSION['mensaje'] = "Curso creado con éxito.";

        header("Location: ../../../vistas/gestion-director.php#gestion-de-cursos");
        exit;
    } catch (Exception $e) {
        // Almacenar mensaje de error en la sesión
        $_SESSION['error'] = "Error al crear el curso: " . $e->getMessage();
        header("Location: ../../../vistas/gestion-director.php#gestion-de-cursos");
        exit;
    }
}
?>
