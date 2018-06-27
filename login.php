<?php
    session_start();
    require_once('funciones/funciones.php');
    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ficha']) && validar_ficha($_POST['ficha'])){

        if(!empty($_POST['miel'])){return header('Location: index.php');}
        
        $campos = [
            'usuarioOEmail' => 'Nombre de Usuario o Correo Electronico',
            'clave' => 'Contraseña'
        ];

        $errores = validar($campos);

        if(empty($errores)){
            $errores = login();
        }
    }

    $titulo = 'Mi Projecto | Login';
    require_once('parciales/arriba.php');
    require_once('parciales/nav.php');
?>

    <!-- Contenedor principal (cuerpo de la pagina) -->
    <div class="container" id="pagina-login">
    <div class="row">
            <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
                <h1 class="titulo-de-pagina">Pagina de Login</h1>

                <hr>

                <?php if(!empty($errores)){echo mostrarErrores($errores);} ?>
                <!-- Formulario de login -->
                <form method="POST" id="formulario-login">
                    <input type="hidden" name="ficha" value="<?php echo ficha_csrf(); ?>">
                    <input type="hidden" name="miel" value="">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <input type="text" class="form-control input-lg" name="usuarioOEmail" value="<?php campo('usuarioOEmail')?>" placeholder="Nombre de Usuario o Email" tabindex="1" autofocus>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <input type="password" class="form-control input-lg" name="clave" placeholder="Contraseña" tabindex="2">  
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-sm-6">
                            <button type="submit" class="btn btn-success btn-lg btn-block" name="loginBtn" tabindex="3">Login</button>
                        </div>
                        <div class="col-sm-6">
                            <a href="index.php" class="btn btn-danger btn-lg btn-block" tabindex="4">Cancelar</a>
                        </div>
                    </div>

                </form><!-- /Formulario de login -->
            </div>
        </div>  
    </div><!-- /Contenedor principal (cuerpo de la pagina) -->


<?php
    require_once('parciales/pie.php');
    require_once('parciales/abajo.php');
?>
