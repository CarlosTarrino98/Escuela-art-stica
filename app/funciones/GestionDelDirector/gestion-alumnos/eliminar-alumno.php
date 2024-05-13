<?php
require_once '../../../funciones/control-de-sesiones/session_control.php';
require_once '../../../config/database.php';
require_once '../../../clases/Usuario.php';

// Crear conexión a la base de datos
$db = new Database();
$conexion = $db->getConnection();

// Crear una instancia de Usuario
$usuarioObj = new Usuario($conexion);

// Verificar si la solicitud es POST y si se proporcionó el ID del usuario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id'])) {
    try {
        // Asignar el ID del usuario a eliminar
        $usuarioObj->user_id = $_POST['user_id'];

        // Eliminar el usuario de la base de datos
        if ($usuarioObj->delete()) {
            $_SESSION['mensaje'] = "Alumno eliminado con éxito.";
        } else {
            throw new Exception("Error al eliminar el alumno.");
        }
    } catch (Exception $e) {
        // Manejar y almacenar el mensaje de error
        $_SESSION['error'] = $e->getMessage();
    }
    // Redireccionar de vuelta a la página de gestión de alumnos
    header("Location: ../../../vistas/gestion-director.php#gestion-de-alumnos");
    exit();
} else {
    // Redireccionar si no se proporcionó un ID de usuario o si no es un método POST
    header("Location: ../../../vistas/gestion-director.php#gestion-de-alumnos");
    exit();
}
?>
