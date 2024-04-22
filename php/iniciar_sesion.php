<?php

#almacenar datos#
  $usuario = limpiar_cadena($_POST['login_usuario']);
  $clave = limpiar_cadena($_POST['login_clave']);


  #verificar campos obligatorios#
  if($usuario == "" || $clave == ""){
      echo '
      <div class="notification is-danger ">
        <strong>¡Ocurrio un error!</strong><br>
        No has llenado todos los campos obligatorios.
      </div>
      ';
      exit();
  }

  #verificar que todo este correcto#
  if(!verificar_datos("[a-zA-Z0-9]{4,20}", $usuario)){ 
    echo '<div class="notification is-danger">
            <strong class=" has-background-danger">¡Ocurrio un error!</strong><br>
              El <strong class=" has-background-danger"> USUARIO </strong> no coincide con el formato solicitado.
            </div>';
      exit();
  }

  if(!verificar_datos("[a-zA-Z0-9$@.-]{7,100}", $clave)){ 
    echo '<div class="notification is-danger">
            <strong class=" has-background-danger">¡Ocurrio un error!</strong><br>
              La <strong class=" has-background-danger"> CONTRASEÑA </strong> no coincide con el formato solicitado.
            </div>';
      exit();
  }

  $check_user = conexion();
  $check_user = $check_user->query("SELECT * FROM usuario WHERE usuario_usuario = '$usuario'");    

  if($check_user -> rowCount() == 1){
    $check_user = $check_user -> fetch();

    if($check_user['usuario_usuario'] == $usuario && password_verify($clave, $check_user['usuario_clave'])){
      
      $_SESSION['id'] = $check_user['usuario_id'];
      $_SESSION['nombre'] = $check_user['usuario_nombre'];
      $_SESSION['apellido'] = $check_user['usuario_apellido'];
      $_SESSION['usuario'] = $check_user['usuario_usuario'];

      if(headers_sent()){
        echo "<script> window.location.href='index.php?vista=home'; </script>";
      }else{
        header("Location: index.php?vista=home");
      }

    }else{
      echo '<div class="notification is-danger">
      <strong class=" has-background-danger">¡Ocurrio un error!</strong><br>
        <strong class=" has-background-danger"> USUARIO O CONTRASEÑA </strong> incorrectos.
      </div>';
    }
  }else{
    echo '<div class="notification is-danger">
    <strong class=" has-background-danger">¡Ocurrio un error!</strong><br>
      <strong class=" has-background-danger"> USUARIO O CONTRASEÑA </strong> incorrectos.
    </div>';
  }

  $check_user = null;
