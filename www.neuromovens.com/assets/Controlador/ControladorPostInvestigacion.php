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
        // Obtener los posts
        $posts = $this->modeloPostInvestigacion->obtener();

        // Almacenar los datos en la sesión
        session_start();
        $_SESSION['posts'] = serialize($posts);

        // Redirigir a la página de investigación
        header('Location: ../Vistas/investigacion.php');
        exit();
    }

    private function insertarPost(){
        if (isset($_POST['post'])) {
            // Obtener los datos del formulario
            $titulo = $_POST['post']['titulo'];
            $descripcion = $_POST['post']['descripcion'];

            // Verificar si se recibió la imagen
            if (isset($_FILES['imagen_url']) && $_FILES['imagen_url']['error'] === UPLOAD_ERR_OK) {

                $fileTmpPath = $_FILES['imagen_url']['tmp_name'];
                $fileName = $_FILES['imagen_url']['name'];
                $fileSize = $_FILES['imagen_url']['size'];
                $fileType = $_FILES['imagen_url']['type'];


                $newFileName = 'imagen_' . time() . '.jpg';


                $uploadDir = '../images/';
                $destPath = $uploadDir . $newFileName;


                if (move_uploaded_file($fileTmpPath, $destPath)) {

                    $imagenUrl = '../images/' . $newFileName;


                    $post = new PostInvestigacion($titulo, $descripcion, $imagenUrl);


                    $this->modeloPostInvestigacion->add($post);


                    $this->listarPost();
                } else {
                    // Error al mover la imagen
                    echo "Hubo un problema al subir la imagen.";
                }
            } else {
                // No se seleccionó una imagen o hubo un error con el archivo
                echo "Por favor, selecciona una imagen válida para subir.";
            }
        }
    }

    private function actualizarPost(){
        if (isset($_POST['post'])) {
            $id = $_POST['post']['id'];
            $titulo = $_POST['post']['titulo'];
            $descripcion = $_POST['post']['descripcion'];
            $imagenUrl = "";

            // Recuperar el post actual desde la base de datos
            $post = $this->modeloPostInvestigacion->obtenerPorId($id);

            // Comprobar si se sube una nueva imagen
            if (isset($_FILES['imagen_url']) && $_FILES['imagen_url']['error'] === UPLOAD_ERR_OK) {
                // Si se sube una nueva imagen, primero eliminamos la imagen anterior
                $imagenAnterior = $post->getImagenUrl();

                // Verificar si existe el archivo de imagen anterior y eliminarlo
                if (file_exists( $imagenAnterior)) {
                    unlink($imagenAnterior);  // Eliminar el archivo de la imagen anterior
                }

                // Aquí va la lógica para subir la nueva imagen, igual que en el método de insertarPost
                $fileTmpPath = $_FILES['imagen_url']['tmp_name'];
                $fileName = $_FILES['imagen_url']['name'];
                $newFileName = 'imagen_' . time() . '.jpg';
                $uploadDir = '../images/';
                $destPath = $uploadDir . $newFileName;

                if (move_uploaded_file($fileTmpPath, $destPath)) {
                    // Modificar la URL para que contenga el prefijo ../
                    $imagenUrl = '../images/' . $newFileName;
                } else {
                    // Manejar error de subida
                    echo "Hubo un problema al subir la imagen.";
                }
            } else {
                // Si no se sube nueva imagen, usamos la URL anterior
                $imagenUrl = $_POST['imagenAntigua'];
            }

            // Crear el objeto post y actualizar en la base de datos
            $post = new PostInvestigacion($titulo, $descripcion, $imagenUrl, $id);
            $this->modeloPostInvestigacion->modificar($post);
            $this->listarPost();
        }
    }



    private function eliminarPost(){
        if(isset($_GET['id'])){
            $id = $_GET['id'];

            $post = $this->modeloPostInvestigacion->obtenerPorId($id);
            $imagenUrl= $post->getImagenUrl();
            if (file_exists($imagenUrl)) {
                unlink($imagenUrl); // Elimina el archivo de imagen
            }
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