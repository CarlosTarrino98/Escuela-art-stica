<?php
session_start();

require_once '../../config/database.php';
require_once '../../clases/Consultas.php';

$db = new Database();
$conexion = $db->getConnection();

$consultaObj = new Consultas($conexion);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $consulta_id = $_POST['consulta_id'] ?? '';
    $respuesta = $_POST['respuesta'] ?? '';

    // Validar que se hayan recibido los datos necesarios
    if (empty($consulta_id) || empty($respuesta)) {
        echo "Todos los campos son obligatorios.";
    } else {
        // Obtener el ID del usuario actual (profesor)
        $profesor_id = $_SESSION['user_id'];

        // Intentar enviar la respuesta
        if ($consultaObj->responderConsulta($consulta_id, $profesor_id, $respuesta)) {
            echo "Respuesta enviada con éxito.";
            header('Location: ../../vistas/gestion-profesores.php#consultas-de-alumnos');
            exit();
        } else {
            echo "Ocurrió un error al enviar la respuesta.";
        }
    }
} else {
    // Redireccionar si se intenta acceder a este script de forma incorrecta
    header('Location: ../../vistas/gestion-profesores.php#consultas-de-alumnos');
    exit();
}
?>
