<?php
session_start();
require_once '../../config/database.php';
require_once '../../clases/Consultas.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    // Si no hay un user_id en la sesión, redirige o muestra un mensaje de error
    // Por ejemplo: Redirigir a la página de inicio de sesión
    header("Location: acceso.php");
    exit();
}

// Verificar si se recibió el ID de la consulta a eliminar
if (!isset($_POST['consulta_id'])) {
    // Manejo del error si no se recibió el ID de la consulta
    echo "Error: ID de consulta no recibido.";
    exit();
}

// Obtener el ID de la consulta a eliminar
$consulta_id = $_POST['consulta_id'];

// Crear una instancia de la clase Consultas
$db = new Database();
$conexion = $db->getConnection();
$consultaObj = new Consultas($conexion);

// Intentar eliminar la consulta con el ID proporcionado
$consultaEliminada = $consultaObj->eliminarConsulta($consulta_id);

if ($consultaEliminada) {
    // La consulta fue eliminada con éxito
    // Redirigir al usuario a una página de confirmación o a la misma página donde se encontraba
    header("Location: ../../vistas/aula-virtual.php#historial-de-consultas");
    exit();
} else {
    // Manejar el caso en que la consulta no pudo ser eliminada
    echo "Error: La consulta no pudo ser eliminada.";
    exit();
}
?>
