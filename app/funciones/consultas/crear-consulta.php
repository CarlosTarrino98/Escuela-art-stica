<?php
session_start();

require_once '../../config/database.php';
require_once '../../clases/Consultas.php';

try {
    $db = new Database();
    $conexion = $db->getConnection();

    $consultaObj = new Consultas($conexion);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $subject = $_POST['subject'] ?? '';
        $message = $_POST['message'] ?? '';

        if (empty($subject) || empty($message)) {
            throw new Exception("Todos los campos son obligatorios.");
        }

        $student_id = $_SESSION['user_id']; // Asumiendo que el ID del alumno está almacenado en $_SESSION['user_id']

        if (!$consultaObj->crearConsulta($student_id, $subject, $message)) {
            throw new Exception("Ocurrió un error al enviar la consulta.");
        }

        header('Location: ../../vistas/aula-virtual.php#consulta-a-profesores');
        exit();
    } else {
        throw new Exception("Acceso no válido.");
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
