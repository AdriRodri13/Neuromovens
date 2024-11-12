<?php

namespace Modelos;

use Entidades\Usuario;
use PDO;


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

    public function add()
    {
        // Sin funcionalidad todavia
    }

    public function modificar()
    {
        // Sin funcionalidad todavia
    }

    public function eliminar()
    {
        // Sin funcionalidad todavia
    }

    public function obtener()
    {
        // Sin funcionalidad todavia
    }
}