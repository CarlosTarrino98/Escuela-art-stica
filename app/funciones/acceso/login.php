<?php
session_start();
require_once '../../clases/Usuario.php';
require_once '../../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger datos del formulario
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Crear instancia de la base de datos
    $db = new Database();
    $conexion = $db->getConnection();

    try {
        // Crear instancia de Usuario
        $usuario = new Usuario($conexion);

        // Establecer propiedades del usuario
        $usuario->email = $email;
        $usuario->password = $password; // No cifrar la contraseña aquí

        // Intentar iniciar sesión
        if ($usuario->login()) {
            // Si el inicio de sesión es exitoso, almacenar el ID del usuario, su rol y su nombre en la sesión
            $_SESSION['user_id'] = $usuario->user_id;
            $_SESSION['role'] = $usuario->role;
            $_SESSION['first_name'] = $usuario->first_name; 
            // Redirigir al usuario según su rol
            if ($_SESSION['role'] == 'director' || $_SESSION['role'] == 'profesor' || $_SESSION['role'] == 'alumno' || $_SESSION['role'] == 'administrador') {
                var_dump($_SESSION);
                header('Location: ../../vistas/inicio.php');
                exit();
            }
        }
    } catch (Exception $e) {
        // Si ocurre un error, establecer un mensaje de error y redirigir a la página de acceso
        $_SESSION['error'] = $e->getMessage();
        header('Location: ../../vistas/acceso.php');
        exit();
    }
} else {
    // Redirigir si el script se accede directamente
    header("Location: ../../vistas/acceso.php");
    exit();
}
?>
