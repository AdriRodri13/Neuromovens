<?php

namespace Controlador;
use AllowDynamicProperties;
use Entidades\Usuario;

use Modelos\ModeloUsuario;
session_start();

require'../connection.php';
require '../Modelos/ModeloUsuario.php';
require '../Entidades/Usuario.php';
class ControladorUsuario{

    private $modeloUsuario;

    public function __construct(){
        $this->conexion = establecerConexion();
        $this->modeloUsuario = new ModeloUsuario($this->conexion);
    }

    public function manejarPeticion(string $accion){
        match ($accion) {
            'iniciarSesion' => $this->abrirSesion(),
            'registro' => $this->registroUsuario(),
            default => $this->error()
        };
    }

    private function abrirSesion(){
        if(isset($_POST['nombre_usuario'])){
            $nombre = $_POST['nombre_usuario'];
        }else{
            $this->error();
        }

        if(isset($_POST['contra'])){
            $contra = $_POST['contra'];
        }else{
            $this->error();
        }

        $usuario = new Usuario($nombre, $contra);
        if($this->modeloUsuario->comprobarUsuario($usuario)){
            $_SESSION['usuario'] = true;
            $_SESSION['nombre_usuario'] = $usuario->getNombreUsuario();
        }
        header('location: ../../index.php');
        die;


    }

    private function registroUsuario(){
        if(isset($_POST['nombre_usuario'])){
            $nombre = $_POST['nombre_usuario'];
        }else{
            $this->error();
        }

        if(isset($_POST['email'])){
            $email = $_POST['email'];
        }else{
            $this->error();
        }

        if(isset($_POST['contra'])){
            $contra = $_POST['contra'];
        }else{
            $this->error();
        }

        $usuario = new Usuario($nombre, $contra, $email);
        $this->modeloUsuario->add($usuario);
    }

    private function error(){
        header('location: ../../index.php');
    }

}

$controladorUsuario = new ControladorUsuario();

if(isset($_POST['accion'])){
    $accion = $_POST['accion'];
}else{
    header('location: ../index.php');
}

$controladorUsuario->manejarPeticion($accion);



