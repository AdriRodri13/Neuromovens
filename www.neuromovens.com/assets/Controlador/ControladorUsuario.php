<?php

namespace Controlador;
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
        if($accion=='iniciarSesion'){
            $this->abrirSesion();
        }else{
            $this->cerrarSesion();
        }
    }

    private function abrirSesion(){
        if(isset($_POST['nombre_usuario'])){
            $nombre = $_POST['nombre_usuario'];
        }else{
            header('location: ../index.php');
            die;
        }

        if(isset($_POST['contra'])){
            $contra = $_POST['contra'];
        }else{
            header('location: ../index.php');
            die;
        }

        $usuario = new Usuario($nombre, $contra);
        if($this->modeloUsuario->comprobarUsuario($usuario)){
            $_SESSION['usuario'] = true;
            $_SESSION['nombre_usuario'] = $usuario->getNombreUsuario();
        }

        header('location: ../../index.php');
        die;


    }

}

$controladorUsuario = new ControladorUsuario();

if(isset($_POST['accion'])){
    $accion = $_POST['accion'];
}else{
    header('location: ../index.php');
}

$controladorUsuario->manejarPeticion($accion);



