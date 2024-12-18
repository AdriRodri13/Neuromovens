<?php

namespace Controlador;
use Entidades\Rol;
use Entidades\Usuario;
use Modelos\ModeloUsuario;
session_start();

require'../connection.php';
require_once '../Entidades/Usuario.php'; // Esto ya incluye indirectamente Entidad.php
require_once '../Modelos/ModeloUsuario.php'; // Esto también puede incluir Entidad.php

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
            'listar' => $this->listarUsuario(),
            'cargar' => $this->cargarUsuario(),
            'actualizar' => $this->actualizarUsuario(),
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

    private function listarUsuario(){
        $usuarios = $this->modeloUsuario->obtener();
        $_SESSION['usuarios'] = serialize($usuarios);
        header('location: ../Vistas/listaUsuarios.php');
        exit();
    }

    private function cargarUsuario(){
        $id = $_GET['id'];
        $usuario = $this->modeloUsuario->obtenerPorId($id);
        $_SESSION['usuarioUpdate'] = serialize($usuario);
        header('location: ../Vistas/cargarUsuario.php');
        exit();
    }

    private function actualizarUsuario(){
        $rol = Rol::tryFrom($_POST['usuario']['rol']);
        $usuario = new Usuario(
            $_POST['usuario']['nombre_usuario'],
            $_POST['usuario']['contra'],
            $_POST['usuario']['email'],
            $rol,
            $_POST['usuario']['id']
        );
        $this->modeloUsuario->modificar($usuario);
        $this->listarUsuario();

    }

    private function error(){
        header('location: ../../index.php');
    }

}

$controladorUsuario = new ControladorUsuario();

$accion = $_POST['accion'] ?? $_GET['accion'] ?? 'error';

$controladorUsuario->manejarPeticion($accion);



