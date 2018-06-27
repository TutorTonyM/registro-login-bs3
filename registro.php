<?php
    session_start();
    require_once('funciones/funciones.php');
    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ficha']) && validar_ficha($_POST['ficha'])){

        if(!empty($_POST['miel'])){return header('Location: index.php');}

        $campos = [
            'nombre' => 'Nombre',
            'apellido' => 'Apellido',
            'usuario' => 'Nombre de Usuario',
            'email' => 'Correo Electronico',
            'clave' => 'Contraseña',
            'reclave' => 'Re-Contraseña',
            'terminos' => 'Terminos y Condiciones'
        ];

        $errores = validar($campos);
        $errores = array_merge($errores, comparadorDeClaves($_POST['clave'], $_POST['reclave']));

        if(empty($errores)){$errores = registro();}
    }

    $titulo = 'Mi Projecto | Registro';
    require_once('parciales/arriba.php');
    require_once('parciales/nav.php');
    require_once('recursos/conexion.php');
?>

    <!-- Contenedor principal (cuerpo de la pagina) -->
    <div class="container" id="pagina-registro">
        <div class="row">
            <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
                <h1 class="titulo-de-pagina">Pagina de Registro</h1>

                <hr>

                <?php if(!empty($errores)){echo mostrarErrores($errores);} ?>
                <!-- <ul class="errores"></ul> -->
                <!-- Formulario de registro -->
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" id="formulario-registro">
                    <input type="hidden" name="ficha" value="<?php echo ficha_csrf(); ?>">
                    <input type="hidden" name="miel" value="">
                    <h2>Registrate <small>para unirte a nuestra comunidad</small></h2>
                    <hr>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="campo-contenedor">
                                        <input type="text" class="form-control input-lg" name="nombre" value="<?php campo('nombre')?>" placeholder="Nombre" tabindex="1" autofocus>
                                        <span class="glyphicon icono-derecho"></span>
                                        <span class="glyphicon glyphicon-user icono-izquierdo"></span>
                                    </div>
                                    <div class="input-group-addon" data-toggle="tooltip" data-placement="bottom" title="Nombres(s) de la persona registrandose">
                                        <span class="glyphicon glyphicon-info-sign"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="campo-contenedor">
                                        <input type="text" class="form-control input-lg" name="apellido" value="<?php campo('apellido')?>" placeholder="Apellido" tabindex="2">
                                        <span class="glyphicon icono-derecho"></span>
                                        <span class="glyphicon glyphicon-user icono-izquierdo"></span>
                                    </div>
                                    <div class="input-group-addon" data-toggle="tooltip" data-placement="bottom" title="Aperllido(s) de la persona registrandose">
                                        <span class="glyphicon glyphicon-info-sign"></span>
                                    </div>
                                </div> 
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="campo-contenedor">
                                        <input type="text" class="form-control input-lg" name="usuario" value="<?php campo('usuario')?>" placeholder="Nombre de Usuario" tabindex="3">
                                        <span class="glyphicon icono-derecho"></span>
                                        <span class="glyphicon glyphicon-user icono-izquierdo"></span>
                                    </div>
                                    <div class="input-group-addon" data-toggle="tooltip" data-placement="bottom" title="Nombre de Usuario que decea usar">
                                        <span class="glyphicon glyphicon-info-sign"></span>
                                    </div>
                                </div> 
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="campo-contenedor">
                                        <input type="text" class="form-control input-lg" name="email" value="<?php campo('email')?>" placeholder="Email" tabindex="4">
                                        <span class="glyphicon icono-derecho"></span>
                                        <span class="glyphicon glyphicon-envelope icono-izquierdo"></span>
                                    </div>
                                    <div class="input-group-addon" data-toggle="tooltip" data-placement="bottom" title="Correo Electronico valido">
                                        <span class="glyphicon glyphicon-info-sign"></span>
                                    </div>
                                </div> 
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="campo-contenedor">
                                        <input type="password" class="form-control input-lg" name="clave" placeholder="Contraseña" tabindex="5" id="clave">
                                        <span class="glyphicon icono-derecho"></span>
                                        <span class="glyphicon glyphicon-lock icono-izquierdo"></span>
                                    </div>
                                    <div class="input-group-addon" data-toggle="tooltip" data-placement="bottom" title="Contraseña segura para la cuenta">
                                        <span class="glyphicon glyphicon-info-sign"></span>
                                    </div>
                                </div> 
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="campo-contenedor">
                                        <input type="password" class="form-control input-lg" name="reclave" placeholder="Re-Contraseña" tabindex="6">
                                        <span class="glyphicon icono-derecho"></span>
                                        <span class="glyphicon glyphicon-lock icono-izquierdo"></span>
                                    </div>
                                    <div class="input-group-addon" data-toggle="tooltip" data-placement="bottom" title="Contraseña segura para la cuenta">
                                        <span class="glyphicon glyphicon-info-sign"></span>
                                    </div>
                                </div>   
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <label class="btn btn-primary btn-lg btn-block">
                                <input type="checkbox" name="terminos" tabindex="7" <?php if(isset($_POST['terminos'])){echo "checked='checked'";} ?>>
                                Acepto
                            </label>
                        </div>
                        <div class="col-sm-9">
                            Al registrarme estoy aceptando los terminos y condiciones acordados por esta pagina incluyendo el uso de Cookies.
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-sm-6">
                            <button type="submit" class="btn btn-success btn-lg btn-block" name="registroBtn" tabindex="8">Registrar</button>
                        </div>
                        <div class="col-sm-6">
                            <a href="index.php" class="btn btn-danger btn-lg btn-block" tabindex="9">Cancelar</a>
                        </div>
                    </div>

                </form><!-- /Formulario de registro -->
            </div>
        </div>        
    </div><!-- /Contenedor principal (cuerpo de la pagina) -->


<?php
    require_once('parciales/pie.php');
    require_once('parciales/abajo.php');
?>
