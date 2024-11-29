<?php

namespace Modelos;

use Entidades\Entidad;
use Entidades\Usuario;
use PDO;

require 'Modelo.php';
require '../Entidades/Entidad.php';

class ModeloUsuario extends Modelo
{


    public function comprobarUsuario(Usuario $usuario): bool
    {
        // Consulta SQL con los parámetros marcados correctamente
        $sql = "SELECT * FROM usuarios WHERE nombre_usuario = :nombre";

        // Preparar la consulta
        $stmt = $this->getConexion()->prepare($sql);

        // Vincular los parámetros a las variables
        $stmt->bindValue(':nombre', $usuario->getNombreUsuario(), PDO::PARAM_STR);

        // Ejecutar la consulta
        $stmt->execute();

        // Obtener toda la fila como un array asociativo
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar si se encontró la fila
        if (!$fila) {
            return false;
        }

        // Acceder a la contraseña cifrada
        $contraCifrada = $fila['contraseña'];

        if(password_verify($usuario->getContra(), $contraCifrada)){
            $_SESSION['usuario'] = true;
            $_SESSION['nombre_usuario'] = $usuario->getNombreUsuario();
            $_SESSION['rol'] = $fila['rol'];
            return true;
        }else{
            return false;
        }

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
        //Obtener un usuario usando el metodo de cifrado de contraseña,
        //Objetivo, recoger nombre y rol para manejar el resto.


    }

    public function obtenerPorId(string $id)
    {
        // Sin funcionalidad todavia
    }
}