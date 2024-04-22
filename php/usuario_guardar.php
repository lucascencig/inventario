<?php
// echo 'hola mundo';
  require_once "../php/main.php";


  #almacenamiento de datos#
  $nombre = limpiar_cadena($_POST["usuario_nombre"]);
  $apellido = limpiar_cadena($_POST["usuario_apellido"]);

  $usuario = limpiar_cadena($_POST["usuario_usuario"]);
  $email = limpiar_cadena($_POST["usuario_email"]);

  $clave_1 = limpiar_cadena($_POST["usuario_clave_1"]);
  $clave_2 = limpiar_cadena($_POST["usuario_clave_2"]);


  #verificar campos obligatorios#
  if($nombre == "" || $apellido == "" || $usuario == "" || $clave_1 == "" || $clave_2 == ""){
      echo '
      <div class="notification is-danger ">
        <strong>¡Ocurrio un error!</strong><br>
        No has llenado todos los campos obligatorios.
      </div>
      ';
      exit();
  }


  #integridad de datos#
  if(!verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}", $nombre)){ 
    echo '<div class="notification is-danger">
            <strong class=" has-background-danger">¡Ocurrio un error!</strong><br>
              El <strong class=" has-background-danger"> NOMBRE </strong> no coincide con el formato solicitado.
            </div>';
      exit();
  }

  if(!verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}", $apellido)){ 
    echo '<div class="notification is-danger">
            <strong class=" has-background-danger">¡Ocurrio un error!</strong><br>
              El <strong class=" has-background-danger"> APELLIDO </strong> no coincide con el formato solicitado.
            </div>';
      exit();
  }

  if(!verificar_datos("[a-zA-Z0-9]{4,20}", $usuario)){ 
    echo '<div class="notification is-danger">
            <strong class=" has-background-danger">¡Ocurrio un error!</strong><br>
              El <strong class=" has-background-danger"> USUARIO </strong> no coincide con el formato solicitado.
            </div>';
      exit();
  }

  if(!verificar_datos("[a-zA-Z0-9$@.-]{7,100}", $clave_1) || !verificar_datos("[a-zA-Z0-9$@.-]{7,100}", $clave_2)){ 
    echo '<div class="notification is-danger">
            <strong class=" has-background-danger">¡Ocurrio un error!</strong><br>
              Las <strong class=" has-background-danger"> CLAVES </strong> no coincide con el formato solicitado.
            </div>';
      exit();
  }

  #VERIFICAR EMAIL#
  if($email != ""){
    if(filter_var($email, FILTER_VALIDATE_EMAIL)){
      $check_email = conexion();
      $check_email = $check_email->query("SELECT usuario_email FROM usuario WHERE usuario_email = '$email'");

      if($check_email -> rowCount() > 0){
          echo '<div class="notification is-danger">
          <strong class=" has-background-danger">¡Ocurrio un error!</strong><br>
            El <strong class=" has-background-danger"> EMAIL </strong> ingresado YA ESTA REGISTRADO. Elija otro.
          </div>';
  exit();
      }

      $check_email = null;

    }else{
      echo '<div class="notification is-danger">
      <strong class=" has-background-danger">¡Ocurrio un error!</strong><br>
        El <strong class=" has-background-danger"> EMAIL </strong> ingresado no es valido.
      </div>';
  exit();
    }
  }


  #VERIFICAR USUARIO#

      $check_usuario = conexion();
      $check_usuario = $check_usuario->query("SELECT usuario_usuario FROM usuario WHERE usuario_usuario = '$usuario'");

      if($check_usuario -> rowCount() > 0){
          echo '<div class="notification is-danger">
          <strong class=" has-background-danger">¡Ocurrio un error!</strong><br>
            El <strong class=" has-background-danger"> USUARIO </strong> ingresado YA ESTA REGISTRADO. Elija otro.
          </div>';
  exit();
      }

      $check_usuario = null;


    #VERIFICAR AMBAS CONTRASEÑAS#
      if($clave_1 != $clave_2){
        echo '<div class="notification is-danger">
          <strong class=" has-background-danger">¡Ocurrio un error!</strong><br>
          Las <strong class=" has-background-danger"> CONTRASEÑAS </strong> ingresadas no coinciden.
          </div>';
  exit();
      }else{
        $clave = password_hash($clave_1, PASSWORD_BCRYPT, ["cost" => 10]);
      }



      #GUARDAR DATOS#
      $guardar_usuario = conexion();
      $guardar_usuario = $guardar_usuario -> prepare("INSERT INTO usuario(usuario_nombre, usuario_apellido, usuario_usuario, usuario_clave, usuario_email) VALUES(:nombre, :apellido, :usuario, :clave, :email)");


      $marcadores = [
        ":nombre" => $nombre,
        ":apellido" => $apellido,
        ":usuario" => $usuario,
        ":clave" => $clave,
        ":email" => $email
      ];

      $guardar_usuario -> execute($marcadores);


      if($guardar_usuario -> rowCount() == 1){
        echo '<div class="notification is-info">
        <strong class=" has-background-info">¡Usuario registrado!</strong><br>
        Se registro correctamente el usuario.
        </div>';
      }else {
        echo '<div class="notification is-danger">
        <strong class=" has-background-danger">¡Ocurrio un error!</strong><br>
        No se pudo registrar el usuario.
        </div>';
      }

      $guardar_usuario = null;

  ?>