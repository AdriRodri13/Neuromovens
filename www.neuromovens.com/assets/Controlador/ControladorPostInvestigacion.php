<?php

namespace Controlador;

require '../Entidades/View.php';
require'../connection.php';
require '../Modelos/ModeloPostInvestigacion.php';


use Entidades\PostInvestigacion;
use Modelos\ModeloPostInvestigacion;
use \Entidades\View as View;

class ControladorPostInvestigacion
{
    private $conexion;
    private $modeloPostInvestigacion;  // Declaramos la propiedad

    public function __construct(){
        $this->conexion = establecerConexion();
        $this->modeloPostInvestigacion = new ModeloPostInvestigacion($this->conexion);
    }

    public function manejarAccion(string $accion){
        match ($accion) {
            'insertar' => $this->insertarPost(),
            'actualizar' => $this->actualizarPost(),
            'eliminar' => $this->eliminarPost(),
            'cargar' => $this->cargarPost(),
            default => $this->listarPost()
        };
    }

    private function listarPost(){
        $posts = $this->modeloPostInvestigacion->obtener();
        View::render('../Vistas/investigacion.php', ['posts' => $posts]);
    }

    private function insertarPost(){
        if(isset($_POST['post'])) {
            $titulo = $_POST['post']['titulo'];
            $descripcion = $_POST['post']['descripcion'];
            $imagen = $_POST['post']['imagen_url'];
            $post = new PostInvestigacion($titulo, $descripcion, $imagen);
            $this->modeloPostInvestigacion->add($post);
            $this->listarPost();
        }
    }

    private function actualizarPost(){
        if(isset($_POST['post'])){
            $id = $_POST['post']['id'];
            $titulo = $_POST['post']['titulo'];
            $descripcion = $_POST['post']['descripcion'];
            $imagen = $_POST['post']['imagen_url'];
            $post = new PostInvestigacion( $titulo, $descripcion, $imagen, $id);
            $this->modeloPostInvestigacion->modificar($post);
            $this->listarPost();
        }
    }

    private function eliminarPost(){
        if(isset($_GET['id'])){
            $id = $_GET['id'];
            $this->modeloPostInvestigacion->eliminar($id);
            $this->listarPost();
        }
    }

    private function cargarPost(){
        if(isset($_GET['id'])){
            $id = $_GET['id'];
            $post = $this->modeloPostInvestigacion->obtenerPorId($id);
            View::render('../Vistas/CargarPost.php', ['post' => $post]);
        }else{
            $this->listarPost();
        }

    }
}


$accion = "";

if (isset($_POST['accion']) && is_string($_POST['accion'])) {
    // Si la acción llega por POST
    $accion = $_POST['accion'];
} elseif (isset($_GET['accion']) && is_string($_GET['accion'])) {
    // Si la acción llega por GET
    $accion = $_GET['accion'];
} else {
    // Acción por defecto
    $accion = 'listar';
}
$controlador = new ControladorPostInvestigacion();
$controlador->manejarAccion($accion);