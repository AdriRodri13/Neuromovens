<?php

namespace Controlador;
use AllowDynamicProperties;
use Entidades\Rol;
use Entidades\Usuario;
use Exception;
use Modelos\ModeloUsuario;
session_start();

require'../connection.php';
require_once '../Entidades/Usuario.php';
require_once '../Modelos/ModeloUsuario.php';

class ControladorUsuario{

    private $conexion;
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
            'buscar' => $this->buscarUsuarios(),
            'listar_paginado' => $this->listarUsuariosPaginado(),
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
        // Validar que los campos no estén vacíos
        if(empty($_POST['nombre_usuario']) || empty($_POST['email']) || empty($_POST['contra'])){
            header('location: ../Vistas/registroUsuario.php?error=campos_vacios');
            exit();
        }

        $nombre = trim($_POST['nombre_usuario']);
        $email = trim($_POST['email']);
        $contra = $_POST['contra'];

        // Validar email en el servidor también
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            header('location: ../Vistas/registroUsuario.php?error=email_invalido');
            exit();
        }

        // Validaciones adicionales (opcional)
        if(strlen($nombre) < 3){
            header('location: ../Vistas/registroUsuario.php?error=nombre_muy_corto');
            exit();
        }

        if(strlen($contra) < 6){
            header('location: ../Vistas/registroUsuario.php?error=contra_muy_corta');
            exit();
        }

        try {
            $usuario = new Usuario($nombre, $contra, $email);
            $resultado = $this->modeloUsuario->add($usuario);

            if($resultado === true){
                // Usuario creado exitosamente
                header('location: ../Vistas/IniciarSesion.php?mensaje=registro_exitoso');
            } else if($resultado === 'usuario_existe'){
                // Usuario ya existe
                header('location: ../Vistas/registroUsuario.php?error=usuario_existe');
            } else {
                // Error en la inserción
                header('location: ../Vistas/registroUsuario.php?error=error_insercion');
            }
        } catch (Exception $e) {
            // Log del error para debugging
            error_log("Error en registro de usuario: " . $e->getMessage());
            header('location: ../Vistas/registroUsuario.php?error=error_interno');
        }

        exit();
    }

    // FUNCIÓN MODIFICADA: Redirigir a la versión paginada
    private function listarUsuario(){
        header('location: ../Controlador/ControladorUsuario.php?accion=listar_paginado&pagina=1');
        exit();
    }

    // NUEVA FUNCIÓN: Buscar usuarios con AJAX
    private function buscarUsuarios(){
        // Verificar que sea una petición AJAX
        if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) ||
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
            http_response_code(400);
            exit('Petición inválida');
        }

        $termino = trim($_POST['termino'] ?? '');
        $pagina = intval($_POST['pagina'] ?? 1);
        $porPagina = 5; // Usuarios por página

        try {
            $resultado = $this->modeloUsuario->buscarUsuarios($termino, $pagina, $porPagina);


            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'usuarios' => $this->formatearUsuariosParaJSON($resultado['usuarios']),
                'total' => $resultado['total'],
                'pagina_actual' => $pagina,
                'total_paginas' => $resultado['total_paginas']
            ]);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => 'Error interno del servidor'
            ]);
        }
        exit();
    }

    // NUEVA FUNCIÓN: Listar usuarios con paginación
    private function listarUsuariosPaginado(){
        $pagina = intval($_GET['pagina'] ?? 1);
        $porPagina = 5;

        try {
            $resultado = $this->modeloUsuario->obtenerPaginado($pagina, $porPagina);
            $_SESSION['usuarios_paginados'] = serialize($resultado);
            header('location: ../Vistas/listaUsuarios.php');
            exit();
        } catch (Exception $e) {
            error_log("Error en listado paginado: " . $e->getMessage());
            $this->error();
        }
    }

    // FUNCIÓN AUXILIAR: Formatear usuarios para JSON (evita problemas de serialización)
    private function formatearUsuariosParaJSON($usuarios) {
        $usuariosArray = [];
        foreach ($usuarios as $usuario) {
            $usuariosArray[] = [
                'id' => $usuario->getId(),
                'nombre_usuario' => $usuario->getNombreUsuario(),
                'email' => $usuario->getEmail(),
                'rol' => $usuario->getRol()->name
            ];
        }
        return $usuariosArray;
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