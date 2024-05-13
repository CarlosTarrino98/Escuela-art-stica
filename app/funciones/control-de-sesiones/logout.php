<?php
session_start();

// Verificar si la sesión está iniciada antes de cerrarla
if (isset($_SESSION['user_id'])) {
    // Destruir todas las variables de sesión
    session_unset();

    // Destruir la sesión
    session_destroy();

    // Redirigir al usuario a la página de acceso u otra página después de cerrar sesión
    header("Location: ../../vistas/acceso.php");
    exit();
} else {
    // Si la sesión no está iniciada, redirigir al usuario a la página de acceso
    header("Location: ../../vistas/acceso.php");
    exit();
}
?>
