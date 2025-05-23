<?php
// Asegurar que la respuesta sea JSON
header('Content-Type: application/json');

// Evitar que los errores de PHP se muestren en la salida
ini_set('display_errors', 0);
error_reporting(0);

// Incluir archivos necesarios
require '../connection.php';
require '../Entidades/PostInvestigacion.php';
require '../Modelos/ModeloPostInvestigacion.php';

use Entidades\PostInvestigacion;
use Modelos\ModeloPostInvestigacion;

try {
    // Establecer conexión
    $conexion = establecerConexion();
    $modeloPostInvestigacion = new ModeloPostInvestigacion($conexion);

    if (isset($_POST['post'])) {
        // Obtener datos del formulario
        $titulo = $_POST['post']['titulo'];
        $descripcion = $_POST['post']['descripcion'];

        // Verificar si se recibió la imagen
        if (isset($_FILES['imagen_url']) && $_FILES['imagen_url']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['imagen_url']['tmp_name'];
            $newFileName = 'imagen_' . time() . '.jpg';
            $uploadDir = '../images/';
            $destPath = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $imagenUrl = '../images/' . $newFileName;

                // Crear y guardar el post
                $post = new PostInvestigacion($titulo, $descripcion, $imagenUrl);
                $resultado = $modeloPostInvestigacion->add($post);

                if ($resultado) {
                    echo json_encode([
                        'exito' => true,
                        'mensaje' => 'Post de investigación publicado correctamente.'
                    ]);
                } else {
                    echo json_encode([
                        'exito' => false,
                        'mensaje' => 'Error al guardar el post en la base de datos.'
                    ]);
                }
            } else {
                echo json_encode([
                    'exito' => false,
                    'mensaje' => 'Error al subir la imagen. Comprueba los permisos de la carpeta.'
                ]);
            }
        } else {
            echo json_encode([
                'exito' => false,
                'mensaje' => 'No se recibió ninguna imagen o hubo un error con el archivo.'
            ]);
        }
    } else {
        echo json_encode([
            'exito' => false,
            'mensaje' => 'Datos del formulario incompletos.'
        ]);
    }
} catch (Exception $e) {
    // Capturar cualquier error y responder con JSON
    echo json_encode([
        'exito' => false,
        'mensaje' => 'Error del servidor: ' . $e->getMessage()
    ]);
}