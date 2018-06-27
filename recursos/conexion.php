<?php
    $con = new mysqli('localhost', 'root', '', 'miprojecto');
    if($con -> connect_error){
        die('Conexion no establecida: ' . $con -> connect_error);
    }
    // else{
    //     echo 'Conexion exitosa';
    // }
?>