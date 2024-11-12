<?php

namespace Modelos;
use Entidades\Entidad;
use Entidades\Producto;
use PDO;

class ModeloProducto extends Modelo
{
    private function comprobarProducto(string $nombreProducto){
        $sql = "SELECT * FROM producto WHERE nombre = :nombreProducto";
        $stmt = $this->getConexion()->prepare($sql);

        $stmt->bindValue(':nombreProducto', $nombreProducto);
        $stmt->execute();

        return $stmt->fetch() !== false;
    }

    public function add(Entidad $producto)
    {
        if($producto instanceof Producto){
            if(!$this->comprobarProducto($producto->getNombre())){
                $sql = "INSERT INTO productos (nombre, descripcion, precio, categoria_id, imagen_url) 
                        VALUES (:nombre, :descripcion, :precio, :categoria_id, :imagen_url)";
                $stmt = $this->getConexion()->prepare($sql);
                $stmt->bindValue(':nombre', $producto->getNombre());
                $stmt->bindValue(':descripcion', $producto->getDescripcion());
                $stmt->bindValue(':precio', $producto->getPrecio());
                $stmt->bindValue(':categoria_id', $producto->getCategoriaId());
                $stmt->bindValue(':imagen_url', $producto->getImagenUrl());
                return $stmt->execute();
            }else{
                return false;
            }
        }
        return false;
    }

    public function modificar(Entidad $producto)
    {
        if($producto instanceof Producto){
            $sql = "UPDATE productos 
                    SET nombre = :nombre, descripcion = :descripcion, precio = :precio, categoria_id = :categoria_id, imagen_url = :imagen_url 
                    WHERE id = :id";
            $stmt = $this->getConexion()->prepare($sql);
            $stmt->bindValue(':nombre', $producto->getNombre());
            $stmt->bindValue(':descripcion', $producto->getDescripcion());
            $stmt->bindValue(':precio', $producto->getPrecio());
            $stmt->bindValue(':categoria_id', $producto->getCategoriaId());
            $stmt->bindValue(':imagen_url', $producto->getImagenUrl());
            $stmt->bindValue(':id', $producto->getId());

            return $stmt->execute();

        }
        return false;
    }

    public function eliminar(string $id)
    {
        $sql = "DELETE FROM productos WHERE id = :id";
        $stmt = $this->getConexion()->prepare($sql);
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }

    public function obtener()
    {
        $sql = "SELECT * FROM productos";
        $stmt = $this->getConexion()->prepare($sql);
        $stmt->execute();
        $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $productos = [];
        foreach ($filas as $fila) {
            $productos[] = new Producto(
                $fila['nombre'],
                $fila['descripcion'],
                $fila['precio'],
                $fila['categoria_id'],
                $fila['imagen_url'],
                $fila['id']
            );
        }
        return $productos;
    }
}