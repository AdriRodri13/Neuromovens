<?php

namespace Entidades;
require_once 'Rol.php';      // Evita cargar este archivo más de una vez
require_once 'Entidad.php';  // Evita la redeclaración de la interfaz
use Entidades\Rol;





class Usuario implements Entidad
{

    public function __construct(
        private string $nombre_usuario,
        private string $contra,
        private string $email = "pordefecto@gmail.com",
        private Rol $rol = Rol::visitante,
        private string $id = '999'
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

    public function getRol(): Rol
    {
        return $this->rol;
    }

    public function setRol(Rol $rol): void
    {
        $this->rol = $rol;
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