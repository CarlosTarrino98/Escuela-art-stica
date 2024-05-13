<?php
// Este archivo es eliminar-tema.php
session_start();
require_once '../../config/database.php'; // Asegúrate de cambiar la ruta según sea necesario
require_once '../../clases/Foro.php';

// Asegurarte de que el usuario esté logueado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$db = new Database();
$conexion = $db->getConnection();
$foro = new Foro($conexion);

$topic_id = $_GET['topic_id'] ?? null;

// Eliminar tema
$foro->topic_id = $topic_id;
if ($foro->eliminarTema()) {
    $_SESSION['mensaje'] = 'Tema eliminado con éxito';
} else {
    $_SESSION['error'] = 'Error al eliminar el tema';
}

header('Location: ../../vistas/foro.php');
exit();
?>
