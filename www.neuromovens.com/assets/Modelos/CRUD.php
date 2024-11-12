<?php

namespace Modelos;

use Entidades\Entidad;

interface CRUD
{
    public function add(Entidad $entidad);

    public function modificar(Entidad $entidad);

    public function eliminar(string $id);

    public function obtener();
}