<?php

namespace Controlador;
use Modelos\ModeloCategoria;
use Modelos\ModeloProducto;
use Entidades\Categoria;
use \Entidades\View as View;

require '../Modelos/ModeloProducto.php';
require'../connection.php';
require '../Entidades/View.php';
require '../Modelos/ModeloCategoria.php';

class ControladorCategoria
{
    private $conexion;
    private $modeloCategoria;
    private $modeloProducto;

    public function __construct(){
        $this->conexion = establecerConexion();
        $this->modeloCategoria = new ModeloCategoria($this->conexion);
        $this->modeloProducto = new ModeloProducto($this->conexion);
    }

    public function manejarAccion(string $accion){
        match ($accion) {
            'insertar' => $this->insertarCategoria(),
            'actualizar' => $this->actualizarCategoria(),
            'eliminar' => $this->eliminarCategoria(),
            'cargar' => $this->cargarCategoria(),
            default => $this->listarProductos()
        };
    }

    private function listarProductos()
    {
        // Obtener todas las categorías
        $categorias = $this->modeloCategoria->obtener();

        // Crear un array para almacenar los productos por categoría
        $productosPorCategoria = [];

        // Recorrer cada categoría y obtener sus productos
        foreach ($categorias as $categoria) {
            // Obtener los productos de la categoría actual
            $productos = $this->modeloProducto->obtenerPorCategoria($categoria->getIdCategoria());

            // Guardar los productos bajo el ID de la categoría
            $productosPorCategoria[$categoria->getIdCategoria()] = [
                'id_categoria' => $categoria->getIdCategoria(),
                'nombre_categoria' => $categoria->getNombreCategoria(),
                'productos' => $productos
            ];
        }

        // Almacenar los datos estructurados en la sesión
        session_start();
        $_SESSION['productos_por_categoria'] = serialize($productosPorCategoria);

        // Redirigir a la página de productos
        header('Location: ../Vistas/productos.php');
        exit();
    }

    private function insertarCategoria(){
        if(isset($_POST['nombreCategoria'])){
            $nombre = $_POST['nombreCategoria'];
            $categoria = new Categoria(0,$nombre);
            $this->modeloCategoria->add($categoria);
            $this->listarProductos();
        }
    }

    private function actualizarCategoria(){
        if(isset($_POST['id'])){
            $id = $_POST['id'];
            $nombre = $_POST['nombre'];
            $categoria = new Categoria($id,$nombre);
            $this->modeloCategoria->modificar($categoria);
            $this->listarProductos();
        }
    }

    private function eliminarCategoria(){
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $categoria = $this->modeloCategoria->obtenerPorId($id);
            $this->modeloCategoria->eliminar($id);
            $this->listarProductos();
        }
    }

    private function cargarCategoria(){
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $categoria = $this->modeloCategoria->obtenerPorId($id);
            View::render('../Vistas/CargarCategoria.php', ['categoria' => $categoria]);
        } else {
            $this->listarProductos();
        }
    }

}

$accion = $_POST['accion'] ?? $_GET['accion'] ?? 'listar';
$controlador = new ControladorCategoria();
$controlador->manejarAccion($accion);