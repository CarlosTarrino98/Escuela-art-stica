<?php
session_start();
require_once '../../../config/database.php';

$db = new Database();
$conexion = $db->getConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['grades'])) {
    foreach ($_POST['grades'] as $user_id => $subjects) {
        foreach ($subjects as $subject_id => $grade) {
            // Preparar la consulta para actualizar o insertar la calificación
            $query = "INSERT INTO student_progress (user_id, subject_id, calification) 
                      VALUES (:user_id, :subject_id, :calification) 
                      ON DUPLICATE KEY UPDATE calification = :calification";

            $stmt = $conexion->prepare($query);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':subject_id', $subject_id, PDO::PARAM_INT);
            $stmt->bindParam(':calification', $grade, PDO::PARAM_INT);

            if (!$stmt->execute()) {
                // Manejar el error
                $_SESSION['error'] = 'Error al actualizar las calificaciones.';
                header('Location: ../../../vistas/gestion-profesores.php');
                exit();
            }
        }
    }

    $_SESSION['mensaje'] = 'Calificaciones actualizadas correctamente.';
}

header('Location: ../../../vistas/gestion-profesores.php');
exit();
?>
