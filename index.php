<?php
    session_start();
    $titulo = 'Mi Projecto | Principio';
    require_once('parciales/arriba.php');
    require_once('parciales/nav.php');
?>

    <!-- Contenedor principal (cuerpo de la pagina) -->
    <div class="container" id="pagina-principio">
        <h1 class="titulo-de-pagina">Pagina Principal</h1>
        <?php
            if(isset($_SESSION['usuario'])){
                echo '
                    <p>Bienvenido '.$_SESSION['usuario'].'</p>
                ';
            }
            else{
                echo '
                <p>Por favor registrate o has login</p>
                ';
            }
        ?>
    </div><!-- /Contenedor principal (cuerpo de la pagina) -->


<?php
    require_once('parciales/pie.php');
    require_once('parciales/abajo.php');
?>
