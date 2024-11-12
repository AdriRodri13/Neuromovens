<?php

namespace Controlador;

require '../Entidades/View.php';
require'../connection.php';
require '../Modelos/ModeloPostInvestigacion.php';


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

    }

    private function actualizarPost(){

    }

    private function eliminarPost(){

    }

    private function cargarPost(){

    }
}


$accion = $_GET['accion'];
$controlador = new ControladorPostInvestigacion();
$controlador->manejarAccion($accion);