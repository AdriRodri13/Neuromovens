<?php

namespace Entidades;

class Categoria implements Entidad
{
    public function __construct(
        private int $id_categoria = 0 ,
        private string $nombre_categoria="provisional"
    )
    {}

    public function getIdCategoria(): int
    {
        return $this->id_categoria;
    }

    public function setIdCategoria(int $id_categoria): void
    {
        $this->id_categoria = $id_categoria;
    }

    public function getNombreCategoria(): string
    {
        return $this->nombre_categoria;
    }

    public function setNombreCategoria(string $nombre_categoria): void
    {
        $this->nombre_categoria = $nombre_categoria;
    }




}