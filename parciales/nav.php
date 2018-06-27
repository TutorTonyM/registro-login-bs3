<nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container">

            <!-- Logo y boton de expander y colapsar los enlaces -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#enlaces">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="index.php" class="navbar-brand">Mi Projecto</a>
            </div><!-- /Logo y boton de expander y colapsar los enlaces -->

            <!-- Enlaces de navgacion -->
            <div class="collapse navbar-collapse navbar-right" id="enlaces">
                <ul class="nav navbar-nav">
                    <li><a href="index.php">Principio</a></li>
                    <li><a href="#">Nosotros</a></li>
                    <li><a href="#">Galeria</a></li>
                    <li><a href="#">Contacto</a></li>

                    <?php
                        if(isset($_SESSION['usuario'])){
                            echo '
                                <li>
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        '.$_SESSION['usuario'].' <span class="caret"></span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a href="#">Mi Perfil</a></li>
                                        <li><a href="#">Mi Cuenta</a></li>
                                        <li><a href="#">Mis Preferencias</a></li>
                                        <li class="divider"></li>
                                        <li><a href="logout.php">Logout</a></li>
                                    </ul>
                                </li>
                            ';
                        }
                        else{
                            echo '
                                <li><a href="login.php">Login</a></li>
                                <li><a href="registro.php">Registro</a></li>
                            ';
                        }
                    ?>

                </ul>
            </div><!-- /Enlaces de navgacion -->
        
        </div>
    </nav>