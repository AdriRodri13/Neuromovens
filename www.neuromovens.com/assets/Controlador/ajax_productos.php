<?php
namespace Controlador;
use Entidades\Producto;
use Modelos\ModeloProducto;
use Modelos\ModeloCategoria;

require '../Modelos/ModeloProducto.php';
require '../connection.php';
require '../Modelos/ModeloCategoria.php';

// Cabeceras para asegurar que la respuesta sea tratada como JSON
header('Content-Type: application/json');

class AjaxProductos {
    private $conexion;
    private $modeloProducto;

    public function __construct() {
        $this->conexion = establecerConexion();
        $this->modeloProducto = new ModeloProducto($this->conexion);
    }

    // Método para comprobar si un nombre de producto ya existe
    public function comprobarNombre($nombre) {
        $existe = $this->modeloProducto->comprobarProducto($nombre);

        echo json_encode([
            'disponible' => !$existe
        ]);
    }

    // Método para insertar un nuevo producto
    public function insertarProducto() {
        $exito = false;
        $mensaje = '';

        if (isset($_POST['producto'])) {
            // Obtener datos del formulario
            $nombre = $_POST['producto']['nombre'];
            $descripcion = $_POST['producto']['descripcion'];
            $precio = $_POST['producto']['precio'];
            $categoriaId = $_POST['producto']['categoria_id'];

            // Comprobar si el producto ya existe
            if (!$this->modeloProducto->comprobarProducto($nombre)) {
                $imagenUrl = "https://via.placeholder.com/200x200"; // Valor por defecto

                // Comprobar si se subió una imagen
                if (isset($_FILES['imagen_url']) && $_FILES['imagen_url']['error'] === UPLOAD_ERR_OK) {
                    $fileTmpPath = $_FILES['imagen_url']['tmp_name'];
                    $newFileName = 'imagen_' . time() . '.jpg';
                    $uploadDir = '../images/';
                    $destPath = $uploadDir . $newFileName;

                    if (move_uploaded_file($fileTmpPath, $destPath)) {
                        $imagenUrl = '../images/' . $newFileName;
                    } else {
                        $mensaje = 'Error al subir la imagen';
                        echo json_encode(['exito' => false, 'mensaje' => $mensaje]);
                        return;
                    }
                }

                // Crear y guardar el producto
                $producto = new Producto($nombre, $descripcion, $precio, $categoriaId, $imagenUrl);
                $exito = $this->modeloProducto->add($producto);

                if ($exito) {
                    $mensaje = 'Producto creado correctamente';
                } else {
                    $mensaje = 'Error al guardar el producto en la base de datos';
                }
            } else {
                $mensaje = 'Ya existe un producto con ese nombre';
            }
        } else {
            $mensaje = 'Datos del formulario incompletos';
        }
        setcookie('ultima_insercion_producto', time(), time() + (30 * 24 * 60 * 60), '/');
        echo json_encode(['exito' => $exito, 'mensaje' => $mensaje]);
    }

    // Método para manejar diferentes acciones
    public function procesarSolicitud() {
        $accion = $_GET['accion'] ?? $_POST['accion'] ?? '';

        switch ($accion) {
            case 'comprobar_nombre':
                if (isset($_GET['nombre'])) {
                    $this->comprobarNombre($_GET['nombre']);
                }
                break;
            case 'insertar':
                $this->insertarProducto();
                break;
            default:
                echo json_encode(['exito' => false, 'mensaje' => 'Acción no reconocida']);
                break;
        }
    }
}

// Crear una instancia y procesar la solicitud
$ajaxHandler = new AjaxProductos();
$ajaxHandler->procesarSolicitud();