<?php

namespace Modelos;

use Entidades\Entidad;
use Entidades\Usuario;
use PDO;

require 'Modelo.php';
require '../Entidades/Entidad.php';

class ModeloUsuario extends Modelo
{


    public function comprobarUsuario(Usuario $usuario): bool {
        // Consulta SQL con los parámetros marcados correctamente
        $sql = "SELECT * FROM usuarios WHERE nombre_usuario = :nombre AND contraseña = :contra";

        // Preparar la consulta
        $stmt = $this->getConexion()->prepare($sql);

        // Vincular los parámetros a las variables
        $stmt->bindValue(':nombre', $usuario->getNombreUsuario(), PDO::PARAM_STR);
        $stmt->bindValue(':contra', $usuario->getContra(), PDO::PARAM_STR);

        // Ejecutar la consulta
        $stmt->execute();

        // Verificar si hay resultados
        if ($stmt->rowCount() > 0) {
            return true; // Usuario encontrado
        }

        return false; // Usuario no encontrado
    }

    public function add(Entidad $entidad)
    {
        // Sin funcionalidad todavia
    }

    public function modificar(Entidad $entidad)
    {
        // Sin funcionalidad todavia
    }

    public function eliminar(string $id)
    {
        // Sin funcionalidad todavia
    }

    public function obtener()
    {
        // Sin funcionalidad todavia
    }

    public function obtenerPorId(string $id)
    {
        // Sin funcionalidad todavia
    }
}