<?php
session_start(); // Asegúrate de que esto está al principio de tu archivo PHP
// El resto de tu código PHP...
?>

<div id="lienzo">
    <?php if (isset($_SESSION['role']) && isset($_SESSION['first_name'])): ?>
        <!-- Contenido específico por rol -->
        <h2>Bienvenido <?php echo $_SESSION['role'] . " " . $_SESSION['first_name']; ?></h2>
        <div id="lienzo-enlaces">
            <?php switch ($_SESSION['role']):
                case 'alumno': ?>                    
                    <!-- Enlaces específicos para alumno -->
                    <a href="pagina-principal.php"><img src="../../public/multimedia/img/inicio/icono-casa2.png" alt="Página Principal" title="Página Principal"><br><b>Página principal</b></a>
                    <a href="aula-virtual.php"><img src="../../public/multimedia/img/inicio/alumno.png" alt="Aula virtual" title="Aula virtual"><br><b>Aula virtual</b></a>
                    <?php break; ?>
                <?php
                case 'profesor': ?>
                    <!-- Enlaces específicos para profesor -->
                    <a href="pagina-principal.php"><img src="../../public/multimedia/img/inicio/icono-casa2.png" alt="Página Principal" title="Página Principal"><br><b>Página principal</b></a>
                    <a href="gestion-profesores.php"><img src="../../public/multimedia/img/inicio/profesor.png" alt="Gestion profesor" title="Gestion profesor"><br><b>Gestion profesor</b></a>
                    <?php break; ?>
                <?php
                case 'director': ?>
                    <!-- Enlaces específicos para director -->
                    <a href="pagina-principal.php"><img src="../../public/multimedia/img/inicio/icono-casa2.png" alt="Página Principal" title="Página Principal"><br><b>Página principal</b></a>
                    <a href="gestion-director.php"><img src="../../public/multimedia/img/inicio/director.png" alt="Gestion director" title="Gestion director"><br><b>Gestion director</b></a>
                    <?php break; ?>
                <?php
                case 'administrador': ?>
                    <!-- Enlaces específicos para administrador -->
                    <a href="pagina-principal.php"><img src="../../public/multimedia/img/inicio/icono-casa2.png" alt="Página Principal" title="Página Principal" id="admin"><br><b>Página principal</b></a>
                    <?php break; ?>
            <?php endswitch; ?>
        </div>
    <?php else : ?>
        <!-- Contenido original si no hay sesión o no hay rol definido -->
        <h1>Artistic School</h1>
        <div id="lienzo-enlaces">
            <a href="pagina-principal.php"><img src="../../public/multimedia/img/inicio/icono-casa2.png" alt="Página Principal" title="Página Principal"><br><b>Página principal</b></a>
            <a href="acceso.php"><img src="../../public/multimedia/img/inicio/acceso.png" alt="Acceso" title="Acceso"><br><b>Acceso</b></a>
        </div>
    <?php endif; ?>
</div>