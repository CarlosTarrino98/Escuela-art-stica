<?php
session_start();
require_once '../../config/database.php';
require_once '../../clases/Foro.php';

$db = new Database();
$conexion = $db->getConnection();
$foroObj = new Foro($conexion);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $topic_id = $_POST['topic_id'] ?? null;
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';

    if ($topic_id && $title && $content) {
        try {
            $foroObj->topic_id = $topic_id;
            $foroObj->title = $title;
            $foroObj->content = $content;

            if ($foroObj->actualizarTema()) {
                $_SESSION['mensaje_exito'] = "Tema actualizado con éxito";
            } else {
                $_SESSION['mensaje_error'] = "Error al actualizar el tema";
            }
        } catch (Exception $e) {
            $_SESSION['mensaje_error'] = "Error: " . $e->getMessage();
        }
    } else {
        $_SESSION['mensaje_error'] = "Información incompleta para actualizar el tema";
    }

    header("Location: ../../vistas/foro.php");
    exit();
} else {
    header("Location: ../../vistas/foro.php");
    exit();
}
?>
