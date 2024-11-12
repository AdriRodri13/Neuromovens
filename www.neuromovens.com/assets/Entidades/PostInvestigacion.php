<?php

namespace Entidades;

require 'Entidad.php';

class PostInvestigacion implements Entidad
{

    public function __construct(
        private string $titulo,
        private string $contenido,
        private string $imagen_url,
        private string $id = "opcional"
    )
    {}

    public function getTitulo(): string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): void
    {
        $this->titulo = $titulo;
    }

    public function getContenido(): string
    {
        return $this->contenido;
    }

    public function setContenido(string $contenido): void
    {
        $this->contenido = $contenido;
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