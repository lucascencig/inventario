<?php
require_once "../inc/session_start.php";

require_once "main.php";

  /*== Almacenando id ==*/
  $id=limpiar_cadena($_POST['usuario_id']);

  /*== Verificando usuario ==*/
$check_usuario=conexion();
$check_usuario=$check_usuario->query("SELECT * FROM usuario WHERE usuario_id='$id'");

  if($check_usuario->rowCount()<=0){
    echo '
          <div class="notification is-danger is-light">
              <strong>¡Ocurrio un error inesperado!</strong><br>
              El usuario no existe en el sistema
          </div>
      ';
      exit();
  }else{
    $datos=$check_usuario->fetch();
  }
  $check_usuario=null;


  /*== Almacenando datos del administrador ==*/
  $admin_usuario=limpiar_cadena($_POST['administrador_usuario']);
  $admin_clave=limpiar_cadena($_POST['administrador_clave']);


  /*== Verificando campos obligatorios del administrador ==*/
  if($admin_usuario=="" || $admin_clave==""){
      echo '
          <div class="notification is-danger is-light">
              <strong>¡Ocurrio un error inesperado!</strong><br>
              No ha llenado los campos que corresponden a su USUARIO o CLAVE
          </div>
      ';
      exit();
  }

  /*== Verificando integridad de los datos (admin) ==*/
  if(verificar_datos("[a-zA-Z0-9]{4,20}",$admin_usuario)){
      echo '
          <div class="notification is-danger is-light">
              <strong>¡Ocurrio un error inesperado!</strong><br>
              Su USUARIO no coincide con el formato solicitado
          </div>
      ';
      exit();
  }

  if(verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$admin_clave)){
      echo '
          <div class="notification is-danger is-light">
              <strong>¡Ocurrio un error inesperado!</strong><br>
              Su CLAVE no coincide con el formato solicitado
          </div>
      ';
      exit();
  }


  /*== Verificando el administrador en DB ==*/
  $check_admin=conexion();
  $check_admin=$check_admin->query("SELECT usuario_usuario,usuario_clave FROM usuario WHERE usuario_usuario='$admin_usuario' AND usuario_id='".$_SESSION['id']."'");
  if($check_admin->rowCount()==1){

    $check_admin=$check_admin->fetch();

    if($check_admin['usuario_usuario']!=$admin_usuario || !password_verify($admin_clave, $check_admin['usuario_clave'])){
      echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                USUARIO o CLAVE de administrador incorrectos
            </div>
        ';
        exit();
    }

  }else{
    echo '
          <div class="notification is-danger is-light">
              <strong>¡Ocurrio un error inesperado!</strong><br>
              USUARIO o CLAVE de administrador incorrectos
          </div>
      ';
      exit();
  }
  $check_admin=null;


  /*== Almacenando datos del usuario ==*/
  $nombre=limpiar_cadena($_POST['usuario_nombre']);
  $apellido=limpiar_cadena($_POST['usuario_apellido']);

  $usuario=limpiar_cadena($_POST['usuario_usuario']);
  $email=limpiar_cadena($_POST['usuario_email']);

  $clave_1=limpiar_cadena($_POST['usuario_clave_1']);
  $clave_2=limpiar_cadena($_POST['usuario_clave_2']);


  /*== Verificando campos obligatorios del usuario ==*/
  if($nombre=="" || $apellido=="" || $usuario==""){
      echo '
          <div class="notification is-danger is-light">
              <strong>¡Ocurrio un error inesperado!</strong><br>
              No has llenado todos los campos que son obligatorios
          </div>
      ';
      exit();
  }


  /*== Verificando integridad de los datos (usuario) ==*/
  if(verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}",$nombre)){
      echo '
          <div class="notification is-danger is-light">
              <strong>¡Ocurrio un error inesperado!</strong><br>
              El NOMBRE no coincide con el formato solicitado
          </div>
      ';
      exit();
  }

  if(verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}",$apellido)){
      echo '
          <div class="notification is-danger is-light">
              <strong>¡Ocurrio un error inesperado!</strong><br>
              El APELLIDO no coincide con el formato solicitado
          </div>
      ';
      exit();
  }

  if(verificar_datos("[a-zA-Z0-9]{4,20}",$usuario)){
      echo '
          <div class="notification is-danger is-light">
              <strong>¡Ocurrio un error inesperado!</strong><br>
              El USUARIO no coincide con el formato solicitado
          </div>
      ';
      exit();
  }

?>