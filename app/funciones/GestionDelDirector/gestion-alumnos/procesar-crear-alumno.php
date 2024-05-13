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
        $usuarioObj->first_name = $_POST['first_name'];
        $usuarioObj->last_name = $_POST['last_name'];
        $usuarioObj->email = $_POST['email'];
        $usuarioObj->setPassword($_POST['password']); // Usar el método setPassword para hashear la contraseña
        $usuarioObj->phone = $_POST['phone'];
        $usuarioObj->address = $_POST['address'];
        $usuarioObj->role = 'alumno'; // Establecer el rol como 'alumno'
        $usuarioObj->course_id = $_POST['course_id'];

        // Validar y sanitizar los datos aquí según sea necesario

        // Crear el usuario en la base de datos
        if ($usuarioObj->register()) {
            $_SESSION['mensaje'] = "Alumno creado con éxito.";
        } else {
            throw new Exception("Error al crear el alumno.");
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
