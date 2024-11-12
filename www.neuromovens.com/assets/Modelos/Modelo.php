<?php

namespace Modelos;
require 'CRUD.php';
use PDO;

abstract class Modelo implements CRUD
{
    public function __construct(
        private PDO $conexion
    ) {}

    public function getConexion(): PDO
    {
        return $this->conexion;
    }



}