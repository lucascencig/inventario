<?php
  require_once "../inc/session_start.php";
  require_once "main.php";

  $id = limpiar_cadena($_POST['usuario_id']);


  //verificar el usuario
  $check_usuario = conexion();
  $check_usuario = $check_usuario->query("SELECT * FROM usuario WHERE usuario_id = '$id'"); 

  if($check_usuario -> rowCount() <= 0){
    echo '<div class="notification is-danger">
        <strong class=" has-background-danger">¡Ocurrio un error!</strong><br>
        El usuario no existe en el sistema.
        </div>';
        exit();
  }else{
    $datos = $check_usuario->fetch();
  }
  $check_usuario = null;
?>