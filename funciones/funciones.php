<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    function phpMailer($email, $usuario){
        require_once('vendor/PHPMailer/src/Exception.php');
        require_once('vendor/PHPMailer/src/PHPMailer.php');
        require_once('vendor/PHPMailer/src/SMTP.php');

        $mail = new PHPMailer(true);

        try {
            //Servidor
            $mail->SMTPDebug = 2;
            $mail->isSMTP();
            $mail->Host = 'in-v3.mailjet.com';
            $mail->SMTPAuth = true;
            $mail->Username = '';
            $mail->Password = '';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            //Usuarios
            $mail->setFrom('prueba@mastersdeveloping.com', 'MiProjecto.com');
            $mail->addAddress($email, $usuario);

            //Contenido
            $mail->isHTML(true);
            $mail->Subject = 'Bienvenido a MiProjecto.com';
            $mail->Body    = 'Gracias por registrarte. Ahora ya eres parte de una gran <b>comunidad</b>.';
            $mail->AltBody = 'Gracias por registrarte. Ahora ya eres parte de una gran comunidad.';

            $mail->send();
        } catch (Exception $e) {
            echo 'El mensaje no pudo ser enviado: '. $mail->ErrorInfo;
        }
    }

    function registro(){
        require_once('recursos/conexion.php');
        $errores = duplicacion($con);

        if(!empty($errores)){
            return $errores;
        }

        $nombre = limpiar($_POST['nombre']);
        $apellido = limpiar($_POST['apellido']);
        $usuario = limpiar($_POST['usuario']);
        $email = limpiar($_POST['email']);
        $clave = limpiar($_POST['clave']);

        $dec = $con -> prepare("INSERT INTO `perfiles` (`Nombre`, `Apellido`, `Usuario`, `Email`, `Clave`) VALUES (?, ?, ?, ?, ?)");
        $dec -> bind_param("sssss", $nombre, $apellido, $usuario, $email, password_hash($clave, PASSWORD_DEFAULT));
        $dec -> execute();
        $resultado = $dec -> affected_rows;
        $dec -> free_result();
        $dec -> close();
        $con -> close();

        if($resultado == 1){
            $_SESSION['usuario'] = $usuario;
            header('Location: index.php');
            phpMailer($email, $usuario);
        }
        else{
            $errores[] = 'Oops, estamos experimentando problemas tecnicos y no podemos crear tu perfil en este momento. Porfavor intentalo de nuevo mas tarde.';
        }

        return $errores;
    }

    function duplicacion($con){
        $errores = [];

        $usuario = limpiar($_POST['usuario']);
        $email = limpiar($_POST['email']);

        $dec = $con -> prepare("SELECT `Usuario`, `Email` FROM `perfiles` WHERE `Usuario` = ? OR `Email` = ?");
        $dec -> bind_param("ss", $usuario, $email);
        $dec -> execute();
        $resultado = $dec -> get_result();
        $cantidad = mysqli_num_rows($resultado);
        $linea = $resultado -> fetch_assoc();
        $dec -> free_result();
        $dec -> close();

        if($cantidad > 0){
            if($_POST['usuario'] == $linea['Usuario']){
                $errores[] = 'El NOMBRE DE USUARIO no esta deisponible.';
            }
            if($_POST['email'] == $linea['Email']){
                $errores[] = 'El CORROE ELCTRONICO ya esta siendo usado por alguien mas.';
            }
        }

        return $errores;
    }

    function login(){
        require_once('recursos/conexion.php');
        $errores = [];

        $usuario = limpiar($_POST['usuarioOEmail']);
        $clave = limpiar($_POST['clave']);

        $dec = $con -> prepare("SELECT `Usuario`, `Clave`, `Intento`, `Id`, `Tiempo` FROM `perfiles` WHERE `Usuario` = ? OR `Email` = ?");
        $dec -> bind_param("ss", $usuario, $usuario);
        $dec -> execute();
        $resultado = $dec -> get_result();
        $cantidad = mysqli_num_rows($resultado);
        $linea = $resultado -> fetch_assoc();
        $dec -> free_result();
        $dec -> close();

        if($cantidad == 1){

            $errores = fuerzaBruta($con, $linea['Intento'], $linea['Id'], $linea['Tiempo']);
            if(!empty($errores)){return $errores;}

            if(password_verify($clave, $linea['Clave'])){

                $intento = 0;
                $tiempo = NULL;
                $id = $linea['Id'];
                $dec = $con -> prepare("UPDATE `perfiles` SET `Intento` = ?, `Tiempo` = ? WHERE `Id` = ?");
                $dec -> bind_param("isi", $intento, $tiempo, $id);
                $dec -> execute();
                $dec -> close();
                $con -> close();

                $_SESSION['usuario'] = $linea['Usuario'];
                header('Location: index.php');
            }
            else{
                $errores[] = 'La combinacion de (NOMBRE DE USUARIO o CORREO ELECTRONICO) y CONTRASEñA no son validos.';
            }
        }
        else{
            $errores[] = 'La combinacion de (NOMBRE DE USUARIO o CORREO ELECTRONICO) y CONTRASEñA no son validos.';
        }

        return $errores;
    }

    function fuerzaBruta($con, $intento, $id, $tiempo){
        $errores = [];
        $intento = $intento + 1;

        $dec = $con -> prepare("UPDATE `perfiles` SET `Intento` = ? WHERE `Id` = ?");
        $dec -> bind_param("ii", $intento, $id);
        $dec -> execute();
        $dec -> close();

        if($intento == 5){
            $ahora = date('Y-m-d H:i:s');
            $dec = $con -> prepare("UPDATE `perfiles` SET `Tiempo` = ? WHERE `Id` = ?");
            $dec -> bind_param("si", $ahora, $id);
            $dec -> execute();
            $dec -> close();
            $con -> close();
            $errores[] = 'Esta cuenta ha sido bloqueada por los proximos 15 minutos.';
        }
        elseif($intento > 5){
            $espera = strtotime(date('Y-m-d H:i:s')) - strtotime($tiempo);
            $minutos = ceil((900-$espera)/60);
            if($espera < 100){
                $errores[] = 'Esta cuenta ha sido bloqueada por los proximos '.$minutos.' minutos.';
            }
            else{
                $intento = 1;
                $tiempo = NULL;
                $dec = $con -> prepare("UPDATE `perfiles` SET `Intento` = ?, `Tiempo` = ? WHERE `Id` = ?");
                $dec -> bind_param("isi", $intento, $tiempo, $id);
                $dec -> execute();
                $dec -> close();
                $con -> close();
            }
        }

        return $errores;
    }

    function limpiar($datos){
        $datos = trim($datos);
        $datos = stripslashes($datos);
        $datos = htmlspecialchars($datos);
        return $datos;
    }

    function mostrarErrores($errores){
        $resultado = '<div class="alert alert-danger errores"><ul>';
        foreach($errores as $error){
            $resultado .= '<li>' . htmlspecialchars($error) . '</li>';
        }
        $resultado .= '</ul></div>';
        return $resultado;
    }

    function ficha_csrf(){
        $ficha = bin2hex(random_bytes(32));
        return $_SESSION['ficha'] = $ficha;
    }

    function validar_ficha($ficha){
        if(isset($_SESSION['ficha']) && hash_equals($_SESSION['ficha'], $ficha)){
            unset($_SESSION['ficha']);
            return true;
        }
        return false;
    }

    function validar($campos){
        $errores = [];
        foreach($campos as $nombre => $mostrar){
            if(!isset($_POST[$nombre]) || $_POST[$nombre] == NULL){
                $errores[] = $mostrar . ' es un campo requerido.';
            }
            else{
                $valides = campos();
                foreach($valides as $campo => $opcion){
                    if($nombre == $campo){
                        if(!preg_match($opcion['patron'], $_POST[$nombre])){
                            $errores[] = $opcion['error'];
                        }
                    }
                }
            }
        }
        return $errores;
    }

    function campo($nombre){
        echo $_POST[$nombre] ?? '';
    }

    function campos(){
        $validacion = [
            'nombre' => [
                'patron' => '/^[a-z\s]{2,50}$/i',
                'error' => 'NOMBRES solo puede usar letras y espacios. Ademas debe de tener de 2 a 50 caracteres.'
            ],
            'apellido' => [
                'patron' => '/^[a-z\s]{2,50}$/i',
                'error' => 'APELLIDO solo puede usar letras y espacions. Ademas debe de tener de 2 a 50 caracteres.'
            ],
            'usuario' => [
                'patron' => '/^[a-z][\w]{2,30}$/i',
                'error' => 'NOMBRE DE USUARIO debe tener pos lo menos 3 caracteres. Debe de comensar con una letra y solo puede usar letras, numeros, y guion bajo.'
            ],
            'email' => [
                'patron' => '/^[a-z]+[\w-\.]{2,}@([\w-]{2,}\.)+[\w-]{2,4}$/i',
                'error' => 'El CORREO ELECTRONICO debe de ser en un formato valido.'
            ],
            'clave' => [
                'patron' => '/(?=^[\w\!@#\$%\^&\*\?]{8,30}$)(?=(.*\d){2,})(?=(.*[a-z]){2,})(?=(.*[A-Z]){2,})(?=(.*[\!@#\$%\^&\*\?_]){2,})^.*/',
                'error' => 'Porfavor entre una contraseña valida. La contraseña debe tener por lo menos 2 letras mayusculas, 2 letras minusculas, 2 numeros y 2 simbolos.'
            ],
            'usuarioOEmail' => [
                'patron' => '/(?=^[a-z]+[\w@\.]{2,50}$)/i',
                'error' => 'Porfavor use un NONBRE DE USUARIO o COREEO ELECTRONICO valido.'
            ]
        ];
        return $validacion;
    }

    function comparadorDeClaves($clave, $reclave){
        $errores = [];
        if($clave !== $reclave){
            $errores[] = 'Las contraseñas proveidas no son iguales.';
        }
        return $errores;
    }
?>
