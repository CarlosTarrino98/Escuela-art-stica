<nav>
    <ul>
        <li><a href="inicio.php">Inicio</a></li>
        <li><a href="pagina-principal.php">Página Principal</a></li>
        <?php
        // Verificar el rol del usuario y mostrar los enlaces correspondientes
        if (isset($_SESSION['role'])) {
            $user_role = $_SESSION['role'];
            if ($user_role == 'alumno') {
                echo '<li><a href="aula-virtual.php">Aula Virtual</a></li>';
            } elseif ($user_role == 'profesor') {
                echo '<li><a href="gestion-profesores.php">Gestión de Profesores</a></li>';
            } elseif ($user_role == 'director') {
                echo '<li><a href="gestion-director.php">Gestión del Director</a></li>';
            } elseif ($user_role == 'administrador') {
                echo '<li><a href="aula-virtual.php">Aula Virtual</a></li>';
                echo '<li><a href="gestion-profesores.php">Gestión de Profesores</a></li>';
                echo '<li><a href="gestion-director.php">Gestión del Director</a></li>';
            }
        }
        ?>
        <li><a href="acceso.php">Acceso</a></li>
        <?php
        // Verificar si el usuario está autenticado y mostrar el enlace de cerrar sesión si es así
        if (isset($_SESSION['user_id'])) {
            echo '<li><a href="../funciones/control-de-sesiones/logout.php">Cerrar sesión</a></li>';
        }
        ?>
    </ul>
</nav>
