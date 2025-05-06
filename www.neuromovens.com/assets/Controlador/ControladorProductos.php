<?php

namespace Controlador;
use Entidades\Categoria;
use Modelos\ModeloCategoria;
use Modelos\ModeloProducto;
use Entidades\Producto;
use \Entidades\View as View;

require '../Modelos/ModeloProducto.php';
require'../connection.php';
require '../Entidades/View.php';
require '../Modelos/ModeloCategoria.php';

class ControladorProductos {
    private $conexion;
    private $modeloProducto;
    private $modeloCategoria;

    public function __construct() {
        $this->conexion = establecerConexion();
        $this->modeloProducto = new ModeloProducto($this->conexion);
        $this->modeloCategoria = new ModeloCategoria($this->conexion);
    }

    public function manejarAccion(string $accion) {
        match ($accion) {
            'insertar' => $this->insertarProducto(),
            'actualizar' => $this->actualizarProducto(),
            'eliminar' => $this->eliminarProducto(),
            'cargar' => $this->cargarProducto(),
            'mostrarTodos' => $this->mostrarTodos(),
            'cargarInserccion' => $this->cargarInserccion(),
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

        // Leer el parámetro de redirección
        if(isset($_GET['redirect'])){
            $this->mostrarTodos();
            exit;
        }

        // Redirigir a la vista correspondiente
        header('Location: ../Vistas/productos.php');

        exit();
    }



    private function insertarProducto() {
        if (isset($_POST['producto'])) {
            // Obtener datos del formulario
            $nombre = $_POST['producto']['nombre'];
            $descripcion = $_POST['producto']['descripcion'];
            $precio = $_POST['producto']['precio'];
            $categoriaId = $_POST['producto']['categoria_id'];

            if (!$this->modeloProducto->comprobarProducto($nombre)) {
                $imagenUrl = "https://via.placeholder.com/200x200"; // Valor por defecto para la imagen

                if (isset($_FILES['imagen_url']) && $_FILES['imagen_url']['error'] === UPLOAD_ERR_OK) {
                    $fileTmpPath = $_FILES['imagen_url']['tmp_name'];
                    $newFileName = 'imagen_' . time() . '.jpg';
                    $uploadDir = '../images/';
                    $destPath = $uploadDir . $newFileName;

                    if (move_uploaded_file($fileTmpPath, $destPath)) {
                        $imagenUrl = '../images/' . $newFileName; // Actualizar si la subida fue exitosa
                    }
                }

                // Crear y guardar el producto
                $producto = new Producto($nombre, $descripcion, $precio, $categoriaId, $imagenUrl);
                $this->modeloProducto->add($producto);

                // Redirigir a la lista de productos
                $this->listarProductos();
            }
            $this->listarProductos();
        }
    }

    private function actualizarProducto() {
        if (isset($_POST['producto'])) {
            $id = $_POST['producto']['id'];
            $nombre = $_POST['producto']['nombre'];
            $descripcion = $_POST['producto']['descripcion'];
            $precio = $_POST['producto']['precio'];
            $categoriaId = $_POST['producto']['categoria_id'];
            $imagenUrl = "";

            $producto = $this->modeloProducto->obtenerPorId($id);

            if (isset($_FILES['imagen_url']) && $_FILES['imagen_url']['error'] === UPLOAD_ERR_OK) {
                $imagenAnterior = $producto->getImagenUrl();
                if (file_exists($imagenAnterior)) {
                    unlink($imagenAnterior);
                }
                $fileTmpPath = $_FILES['imagen_url']['tmp_name'];
                $newFileName = 'imagen_' . time() . '.jpg';
                $uploadDir = '../images/';
                $destPath = $uploadDir . $newFileName;

                if (move_uploaded_file($fileTmpPath, $destPath)) {
                    $imagenUrl = '../images/' . $newFileName;
                } else {
                    echo "Hubo un problema al subir la imagen.";
                }
            } else {
                $imagenUrl = $_POST['imagenAntigua'];
            }

            $producto = new Producto($nombre, $descripcion, $precio, $categoriaId, $imagenUrl, $id);
            $this->modeloProducto->modificar($producto);
            $this->listarProductos();
        }
    }

    private function eliminarProducto() {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $producto = $this->modeloProducto->obtenerPorId($id);
            $imagenUrl = $producto->getImagenUrl();

            if (file_exists($imagenUrl)) {
                unlink($imagenUrl);
            }
            $this->modeloProducto->eliminar($id);
            $this->listarProductos();
        }
    }

    private function cargarProducto() {
        session_start();
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $categorias = $this->modeloCategoria->obtener();
            $_SESSION['categorias'] = serialize($categorias);
            $producto = $this->modeloProducto->obtenerPorId($id);

            View::render('../Vistas/CargarProducto.php', ['producto' => $producto]);
        } else {
            $this->listarProductos();
        }
    }

    private function mostrarTodos(){
        $productos = $this->modeloProducto->obtener();
        session_start();
        $_SESSION['productos'] = serialize($productos);
        header('Location: ../Vistas/listaProductos.php');
    }

    private function cargarInserccion()
    {
        $categorias = $this->modeloCategoria->obtener();
        session_start();
        $_SESSION['categorias'] = serialize($categorias);

        header('Location: ../Vistas/InsertarProducto.php');
        exit();
    }
}

// Ejemplo de manejo de acción
$accion = $_POST['accion'] ?? $_GET['accion'] ?? 'listar';
$controlador = new ControladorProductos();
$controlador->manejarAccion($accion);