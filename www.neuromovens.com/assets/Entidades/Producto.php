<?php

namespace Entidades;

class Producto implements Entidad
{


    public function __construct(
        private string $nombre,
        private string $descripcion,
        private float $precio,
        private int $categoria_id,
        private string $imagen_url,
        private string $id = "opcional"
    )
    {}

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    public function getDescripcion(): string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): void
    {
        $this->descripcion = $descripcion;
    }

    public function getPrecio(): float
    {
        return $this->precio;
    }

    public function setPrecio(float $precio): void
    {
        $this->precio = $precio;
    }

    public function getCategoriaId(): int
    {
        return $this->categoria_id;
    }

    public function setCategoriaId(int $categoria_id): void
    {
        $this->categoria_id = $categoria_id;
    }

    public function getImagenUrl(): string
    {
        return $this->imagen_url;
    }

    public function setImagenUrl(string $imagen_url): void
    {
        $this->imagen_url = $imagen_url;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }




}