<?php
session_start();
require_once '../../clases/Usuario.php';
require_once '../../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['name'] ?? '';
    $last_name = $_POST['surname'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmacionPassword = $_POST['confirm_password'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    $role = $_POST['role'] ?? '';
    $course_id = $_POST['course_id'] ?? '';

    if ($password !== $confirmacionPassword) {
        $_SESSION['error'] = 'Las contraseñas no coinciden.';
        header("Location: ../../vistas/acceso.php");
        exit();
    }

    $db = new Database();
    $conexion = $db->getConnection();
    $usuario = new Usuario($conexion);

    $usuario->first_name = $first_name;
    $usuario->last_name = $last_name;
    $usuario->email = $email;
    $usuario->setPassword($password);
    $usuario->phone = $phone;
    $usuario->address = $address;
    $usuario->role = $role;

     // Si el rol es 'alumno' y se ha seleccionado un curso, asignar el valor, de lo contrario, asignar null
     if (isset($_POST['role']) && $_POST['role'] == 'alumno' && !empty($_POST['course'])) {
        $usuario->course_id = $_POST['course'];
    } else {
        $usuario->course_id = null;
    }
    try {
        $userId = $usuario->register();
        if ($userId) {
            $userInfo = $usuario->getById(); // Asegúrate de que el método getById() devuelva toda la información del usuario
            $_SESSION['user_id'] = $userId;
            $_SESSION['first_name'] = $userInfo['first_name']; // Almacenar el nombre en la sesión
            $_SESSION['role'] = $usuario->role;
            header("Location: ../../vistas/inicio.php");
            exit();
        }
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        header("Location: ../../vistas/acceso.php");
        exit();
    }
} else {
    header("Location: ../../vistas/acceso.php");
    exit();
}
