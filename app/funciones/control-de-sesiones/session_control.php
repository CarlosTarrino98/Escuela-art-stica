<?php
session_start();

// Verificar si el usuario ha iniciado sesión
function verificarSesion() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: acceso.php");
        exit();
    }
}

// Verificar si el usuario tiene permiso para acceder a una página específica según su rol
function verificarPermiso($rolesPermitidos) {
    if (!in_array($_SESSION['role'], $rolesPermitidos)) {
        header("Location: acceso.php");
        exit();
    }
}
?>
