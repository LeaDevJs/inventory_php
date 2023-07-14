<?php
require_once "main.php";

//almacenando datos
$nombre=limpiar_cadena($_POST["usuario_nombre"]);
$apellido=limpiar_cadena($_POST["usuario_apellido"]);
$usuario=limpiar_cadena($_POST["usuario_usuario"]);
$email=limpiar_cadena($_POST["usuario_email"]);
$clave_1=limpiar_cadena($_POST["usuario_clave_1"]);
$clave_2=limpiar_cadena($_POST["usuario_clave_2"]);

//verificar campos obligatorios

if($nombre=="" or $apellido=="" or $usuario=="" or $clave_1=="" or $clave_2==""){
    echo "<div class='notification is-danger is-light'>
    <strong>¡Ocurrio un error inesperado!</strong><br>
    No has llenado todos los campos que son obligatorios
</div>";
    exit();
}
// verificando integridad de los datos
if(verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}",$nombre)){
    echo "<div class='notification is-danger is-light'>
    <strong>El nombre no coincide con el formato solicitado
</div>";
    exit();
}
if(verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}",$apellido)){
    echo "<div class='notification is-danger is-light'>
    <strong>El apellido no coincide con el formato solicitado
</div>";
    exit();
}
if(verificar_datos("[a-zA-Z0-9]{4,20}",$usuario)){
    echo "<div class='notification is-danger is-light'>
    <strong>El usuario no coincide con el formato solicitado
</div>";
    exit();
}
if(verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$clave_1) or verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$clave_2)){
    echo "<div class='notification is-danger is-light'>
    <strong>Las claves no coinciden con el formato solicitado
</div>";
    exit();
}
//verificando email
if($email!=""){
    //filter_var filtra una cadena con un metodo. El metodo de al lado
    //valida si el mail es correcto
    if(filter_var($email,FILTER_VALIDATE_EMAIL)){
        $check_email=conexion();
        $check_email=$check_email->query("SELECT usuario_email 
        FROM usuario WHERE usuario_email='$email'");
        if($check_email->rowCount()>0){
            echo "<div class='notification is-danger is-light'>
                <strong>El email ya esta registrado
                </div>";
            exit();
        }
        $check_email=null;
    }else{
        echo "<div class='notification is-danger is-light'>
    <strong>El email no coincide con el formato solicitado
</div>";
    exit();
    }
}

//verificando usuario

$check_usuario=conexion();
$check_usuario=$check_usuario->query("SELECT usuario_usuario 
FROM usuario WHERE usuario_usuario='$usuario'");
if($check_usuario->rowCount()>0){
    echo "<div class='notification is-danger is-light'>
        El usuario ya se encuentra registrado
        </div>";
    exit();
}
$check_usuario=null;

//verificando claves
if($clave_1!=$clave_2){
    echo "<div class='notification is-danger is-light'>
        Las claves no coinciden
        </div>";
    exit();
}else{
    $clave=password_hash($clave_1,PASSWORD_BCRYPT,["cost"=>10]);
}

//guardando datos con metodo query
/* 
$guardar_usuario=conexion();
$guardar_usuario=$guardar_usuario->query("INSERT INTO usuario(usuario_nombre,usuario_apellido,usuario_usuario,usuario_clave,usuario_email) VALUES('$nombre', '$apellido', '$usuario','$clave','$email')");
*/
//metodo prepare evita inyeccion sql
$guardar_usuario=conexion();
$guardar_usuario=$guardar_usuario->prepare("INSERT INTO usuario(usuario_nombre,usuario_apellido,usuario_usuario,usuario_clave,usuario_email) VALUES(:nombre,:apellido,:usuario,:clave,:email)");
$marcadores=[
    ":nombre"=>$nombre,
    ":apellido"=>$apellido,
    ":usuario"=>$usuario,
    ":clave"=>$clave,
    ":email"=>$email
];
$guardar_usuario->execute($marcadores);

if($guardar_usuario->rowCount()==1){
    echo "<div class='notification is-info is-light'>
        Usuario registrado con exito!
        </div>";
}else{
    echo "<div class='notification is-danger is-light'>
        No se pudo registrar el usuario, por favor intente nuevamente
        </div>";
}
$guardar_usuario=null;