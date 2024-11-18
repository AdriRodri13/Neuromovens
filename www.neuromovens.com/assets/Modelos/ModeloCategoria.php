<?php

namespace Modelos;
use Entidades\Entidad;
use Entidades\Categoria;
use PDO;

require '../Entidades/Categoria.php';
class ModeloCategoria extends Modelo
{


    private function comprobarCategoria(string $nombreCategoria): bool
    {
        $sql = "SELECT * FROM categorias WHERE nombre_categoria = :nombreCategoria";
        $stmt = $this->getConexion()->prepare($sql);
        $stmt->bindValue(':nombreCategoria', $nombreCategoria);
        $stmt->execute();
        return $stmt->fetch() !== false;
    }

    // Insertar una nueva categoría
    public function add(Entidad $categoria)
    {
        if ($categoria instanceof Categoria) {
            // Comprobar si la categoría ya existe
            if (!$this->comprobarCategoria($categoria->getNombreCategoria())) {
                $sql = "INSERT INTO categoria (nombre_categoria) VALUES (:nombre_categoria)";
                $stmt = $this->getConexion()->prepare($sql);
                $stmt->bindValue(':nombre_categoria', $categoria->getNombreCategoria());
                return $stmt->execute();
            } else {
                return false;
            }
        }
        return false;
    }

    // Modificar una categoría existente
    public function modificar(Entidad $categoria)
    {
        if ($categoria instanceof Categoria) {
            $sql = "UPDATE categorias
                    SET nombre_categoria = :nombre_categoria 
                    WHERE id_categoria = :id_categoria";
            $stmt = $this->getConexion()->prepare($sql);
            $stmt->bindValue(':nombre_categoria', $categoria->getNombreCategoria());
            $stmt->bindValue(':id_categoria', $categoria->getIdCategoria());
            return $stmt->execute();
        }
        return false;
    }

    // Eliminar una categoría por ID
    public function eliminar(string $id)
    {
        $sql = "DELETE FROM categorias WHERE id_categoria = :id_categoria";
        $stmt = $this->getConexion()->prepare($sql);
        $stmt->bindValue(':id_categoria', $id);
        return $stmt->execute();
    }

    // Obtener todas las categorías
    public function obtener()
    {
        $sql = "SELECT * FROM categorias";
        $stmt = $this->getConexion()->prepare($sql);
        $stmt->execute();
        $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $categorias = [];
        foreach ($filas as $fila) {
            $categorias[] = new Categoria(
                $fila['id'],
                $fila['nombre']
            );
        }
        return $categorias;
    }

    public function obtenerPorId(string $id)
    {
        // TODO: Implement obtenerPorId() method.
    }
}