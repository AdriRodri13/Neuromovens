<?php



namespace Modelos;
require 'Modelo.php';
require '../Entidades/PostInvestigacion.php';
use PDO;
use Entidades\Entidad;
use Entidades\PostInvestigacion;
class ModeloPostInvestigacion extends Modelo
{


    private function comprobarPost(string $tituloPost): bool
    {
        $sql = "SELECT * FROM post_investigacion WHERE titulo = :tituloPost";
        $stmt = $this->getConexion()->prepare($sql);
        $stmt->bindValue(':tituloPost', $tituloPost);
        $stmt->execute();
        return $stmt->fetch() !== false;
    }

    // Insertar un nuevo post de investigación
    public function add(Entidad $post)
    {
        if ($post instanceof PostInvestigacion) {
            // Comprobar si el post ya existe
            if (!$this->comprobarPost($post->getTitulo())) {
                $sql = "INSERT INTO post_investigacion (titulo, contenido, imagen_url) 
                        VALUES (:titulo, :contenido, :imagen_url)";
                $stmt = $this->getConexion()->prepare($sql);
                $stmt->bindValue(':titulo', $post->getTitulo());
                $stmt->bindValue(':contenido', $post->getContenido());
                $stmt->bindValue(':imagen_url', $post->getImagenUrl());
                return $stmt->execute();
            } else {
                return false;
            }
        }
        return false;
    }

    // Modificar un post existente
    public function modificar(Entidad $post)
    {
        if ($post instanceof PostInvestigacion) {
            $sql = "UPDATE post_investigacion 
                    SET titulo = :titulo, contenido = :contenido, imagen_url = :imagen_url 
                    WHERE id = :id";
            $stmt = $this->getConexion()->prepare($sql);
            $stmt->bindValue(':titulo', $post->getTitulo());
            $stmt->bindValue(':contenido', $post->getContenido());
            $stmt->bindValue(':imagen_url', $post->getImagenUrl());
            $stmt->bindValue(':id', $post->getId());
            return $stmt->execute();
        }
        return false;
    }

    // Eliminar un post por ID
    public function eliminar(string $id)
    {
        $sql = "DELETE FROM post_investigacion WHERE id = :id";
        $stmt = $this->getConexion()->prepare($sql);
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }

    // Obtener todos los posts de investigación
    public function obtener()
    {
        $sql = "SELECT * FROM post_investigacion";
        $stmt = $this->getConexion()->prepare($sql);
        $stmt->execute();
        $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $posts = [];
        foreach ($filas as $fila) {
            $posts[] = new PostInvestigacion(
                $fila['titulo'],
                $fila['contenido'],
                $fila['imagen_url'],
                $fila['id']
            );
        }
        return $posts;
    }
}