<?php
session_start();

require_once '../../../config/database.php';
require_once '../../../clases/Usuario.php';

$db = new Database();
$conexion = $db->getConnection();
$usuarioObj = new Usuario($conexion);

// Verificar si la solicitud es POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $db = new Database();
        $conexion = $db->getConnection();

        $usuarioObj = new Usuario($conexion);

        // Asignar valores al objeto usuario
        $usuarioObj->user_id = $_POST['user_id'];
        $usuarioObj->first_name = $_POST['first_name'];
        $usuarioObj->last_name = $_POST['last_name'];
        $usuarioObj->email = $_POST['email']; // Asegúrate de manejar adecuadamente la actualización del email
        $usuarioObj->phone = $_POST['phone'];
        $usuarioObj->address = $_POST['address'];
        $usuarioObj->course_id = $_POST['course_id'];
        $usuarioObj->role = 'alumno'; // Asegurar que el role se mantenga como 'alumno'

        // Actualizar la contraseña si se ha proporcionado una nueva
        if (!empty($_POST['new_password'])) {
            $usuarioObj->setPassword($_POST['new_password']);
        }

        // Actualizar el usuario en la base de datos
        if ($usuarioObj->update()) {
            $_SESSION['mensaje'] = "Alumno actualizado con éxito.";
        } else {
            throw new Exception("Error al actualizar el alumno.");
        }
    } catch (Exception $e) {
        // Manejar y almacenar el mensaje de error
        $_SESSION['error'] = $e->getMessage();
    }
    // Redireccionar de vuelta a la página de gestión de alumnos
    header("Location: ../../../vistas/gestion-director.php#gestion-de-alumnos");
    exit();
}
?>
