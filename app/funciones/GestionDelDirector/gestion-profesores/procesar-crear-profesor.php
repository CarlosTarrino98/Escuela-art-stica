<?php
session_start(); // Inicia la sesión al principio del script

require_once '../../../config/database.php';
require_once '../../../clases/Profesor.php';

$db = new Database();
$conexion = $db->getConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $profesorObj = new Profesor($conexion);

    // Obtener datos del formulario
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $description = $_POST['description'];
    $course_id = $_POST['course_id'];
    $photo = $_FILES['photo']; // Datos del archivo de la foto

    // Asignar datos al objeto profesor
    $profesorObj->first_name = $first_name;
    $profesorObj->last_name = $last_name;
    $profesorObj->description = $description;
    $profesorObj->course_id = $course_id;

    // Verificar si se ha seleccionado un archivo de imagen
    if ($photo['size'] > 0) {
        // Obtener información del archivo
        $fileName = $photo['name'];
        $fileTmpName = $photo['tmp_name'];
        $fileSize = $photo['size'];
        $fileError = $photo['error'];
        $fileType = $photo['type'];

        // Obtener la extensión del archivo
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Extensiones de archivo permitidas
        $allowedExtensions = array('jpg', 'jpeg', 'png', 'webp');

        // Verificar si la extensión del archivo es válida
        if (in_array($fileExt, $allowedExtensions)) {
            // Generar un nombre único para el archivo
            $newFileName = uniqid('', true) . '.' . $fileExt;

            // Ruta de destino para guardar la imagen
            $fileDestination = '../../../../public/multimedia/img/pagina-principal/profesores/uploads/' . $newFileName;

            // Mover el archivo a la ubicación de destino
            move_uploaded_file($fileTmpName, $fileDestination);

            // Asignar el nombre de la foto al objeto profesor
            $profesorObj->photo = $newFileName;
        } else {
            $_SESSION['error'] = 'Solo se permiten archivos JPG, JPEG, PNG y WebP.';
            header("Location: ../../../vistas/crear-profesor.php");
            exit();
        }
    }

    try {
        // Intentar crear el profesor
        $profesorObj->crear();
        $_SESSION['mensaje'] = 'Profesor creado exitosamente.';
    } catch (Exception $e) {
        $_SESSION['error'] = 'Error al crear el profesor: ' . $e->getMessage();
    }

    // Redireccionar a la página de gestión de profesores
    header("Location: ../../../vistas/gestion-director.php#gestion-de-profesores");
    exit();
}
?>
