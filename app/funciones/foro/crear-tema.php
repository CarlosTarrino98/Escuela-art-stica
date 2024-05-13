<?php
require_once '../../funciones/control-de-sesiones/session_control.php';
require_once '../../config/database.php';
require_once '../../clases/Foro.php';
require_once '../../clases/Usuario.php';

$db = new Database();
$conexion = $db->getConnection();
$foroObj = new Foro($conexion);
$usuarioObj = new Usuario($conexion);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Asumiendo que el ID del usuario está almacenado en la sesión
    $user_id = $_SESSION['user_id'] ?? null;
    $curso_id = $_POST['curso-tema'] ?? null;
    $titulo = $_POST['titulo-tema'] ?? '';
    $contenido = $_POST['contenido-tema'] ?? '';

    // Verifica que el usuario está logueado y los datos están completos
    if ($user_id && $curso_id && $titulo && $contenido) {
        // Verifica si el usuario existe
        $usuarioObj->user_id = $user_id;
        try {
            $usuarioExiste = $usuarioObj->getById();
            if ($usuarioExiste) {
                // Intenta crear el tema en el foro
                $foroObj->user_id = $user_id;
                $foroObj->course_id = $curso_id;
                $foroObj->title = $titulo;
                $foroObj->content = $contenido;

                if ($foroObj->crearTema()) {
                    $_SESSION['mensaje'] = "Tema publicado con éxito";
                } else {
                    $_SESSION['error'] = "Error al intentar publicar el tema.";
                }
            } else {
                $_SESSION['error'] = "Usuario no válido o inexistente";
            }
        } catch (Exception $e) {
            $_SESSION['error'] = "Error: " . $e->getMessage();
        }
    } else {
        $_SESSION['error'] = "Información incompleta para publicar el tema";
    }
    header('Location: ../../vistas/foro.php');
    exit();
}
?>
