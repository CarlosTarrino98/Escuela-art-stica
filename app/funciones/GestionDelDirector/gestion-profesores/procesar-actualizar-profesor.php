<?php
session_start();
require_once '../../../config/database.php';
require_once '../../../clases/Profesor.php';

$db = new Database();
$conexion = $db->getConnection();
$profesorObj = new Profesor($conexion);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['teacher_id'])) {
    try {
        $profesorObj->teacher_id = $_POST['teacher_id'];
        $profesorObj->first_name = $_POST['first_name'];
        $profesorObj->last_name = $_POST['last_name'];
        $profesorObj->description = $_POST['description'];

        // Conservar la foto actual si no se carga una nueva
        $profesorActual = $profesorObj->obtenerPorId($_POST['teacher_id']);
        $profesorObj->photo = $profesorActual['photo'];

        // Si se carga una nueva foto, reemplazar la existente
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
            // Procesar la carga de la nueva foto
            $carpetaDestino = "../../../../public/multimedia/img/pagina-principal/profesores/uploads/";
            $nombreArchivo = uniqid() . "-" . basename($_FILES["photo"]["name"]);
            $rutaDestino = $carpetaDestino . $nombreArchivo;

            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $rutaDestino)) {
                $profesorObj->photo = $nombreArchivo;
            } else {
                throw new Exception("Error al subir la foto.");
            }
        }

        $profesorObj->actualizar();
        $_SESSION['mensaje'] = 'Profesor actualizado correctamente.';
    } catch (Exception $e) {
        $_SESSION['error'] = 'Error al actualizar el profesor: ' . $e->getMessage();
    }

    header("Location: ../../../vistas/gestion-director.php#gestion-de-profesores");
    exit();
} else {
    $_SESSION['error'] = 'No se especificó un profesor para actualizar.';
    header("Location: ../../../vistas/gestion-director.php#gestion-de-profesores");
    exit();
}

?>