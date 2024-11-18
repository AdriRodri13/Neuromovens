<?php

namespace Entidades;

class Usuario implements Entidad
{

    public function __construct(
        private string $nombre_usuario,
        private string $contra,
        private string $email = "pordefecto@gmail.com"
    )
    {}

    public function getNombreUsuario(): string
    {
        return $this->nombre_usuario;
    }

    public function setNombreUsuario(string $nombre_usuario): void
    {
        $this->nombre_usuario = $nombre_usuario;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getContra(): string
    {
        return $this->contra;
    }

    public function setContra(string $contra): void
    {
        $this->contra = $contra;
    }

}