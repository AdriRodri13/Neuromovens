<?php

namespace Modelos;

use Entidades\Entidad;
use Entidades\Rol;
use Entidades\Usuario;
use PDO;

require 'Modelo.php';
require_once '../Entidades/Entidad.php';

class ModeloUsuario extends Modelo
{


    public function comprobarUsuario(Entidad $usuario): bool
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
        $_SESSION['rol'] = $fila['rol'];

        if(password_verify($usuario->getContra(), $contraCifrada)){
            return true;
        }else{
            return false;
        }

    }

    public function add(Entidad $usuario)
    {
        if($usuario instanceof Usuario){
            if(!$this->comprobarUsuario($usuario)){
                $contraHash = password_hash($usuario->getContra(), PASSWORD_DEFAULT);
                $sql = "INSERT INTO usuarios (nombre_usuario, email, contraseña) VALUES (:nombre, :email, :contra)";
                $stmt = $this->getConexion()->prepare($sql);
                $stmt->bindValue(':nombre', $usuario->getNombreUsuario(), PDO::PARAM_STR);
                $stmt->bindValue(':email', $usuario->getEmail(), PDO::PARAM_STR);
                $stmt->bindValue(':contra', $contraHash, PDO::PARAM_STR);
                $stmt->execute();
                header('Location: ../../index.php');
            }else{
                //usuario ya existe
            }
        }

    }

    public function modificar(Entidad $usuario)
    {
        if($usuario instanceof Usuario){
            $sql = "UPDATE usuarios SET nombre_usuario = :nombre_usuario, email = :email, rol = :rol  WHERE id = :id";
            $stmt = $this->getConexion()->prepare($sql);

            $stmt->bindValue(':nombre_usuario', $usuario->getNombreUsuario());
            $stmt->bindValue(':email', $usuario->getEmail());
            $stmt->bindValue(':rol', $usuario->getRol()->name);
            $stmt->bindValue(':id', $usuario->getId());


            return $stmt->execute();
        }
    }

    public function eliminar(string $id)
    {
        // Sin funcionalidad todavia
    }

    public function obtener()
    {
        $sql = "SELECT * FROM usuarios";
        $stmt = $this->getConexion()->prepare($sql);
        $stmt->execute();
        $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $usuarios = [];
        foreach ($filas as $fila) {
            $usuarios[] = new Usuario(
                $fila['nombre_usuario'],
                $fila['contraseña'],
                $fila['email'],
                Rol::tryFrom($fila['rol']) ?? Rol::visitante,
                 $fila['id']
            );
        }
        return $usuarios;

    }

    //En este caso obtenemos el usuario por NOMBRE no por id
    public function obtenerPorId(string $id)
    {
        $sql = "SELECT * FROM usuarios WHERE id = :id";
        $stmt = $this->getConexion()->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);
        $stmt->execute();
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);
        $usuario = new Usuario(
            $fila['nombre_usuario'],
            $fila['contraseña'],
            $fila['email'],
            Rol::tryFrom($fila['rol']) ?? Rol::visitante,
            $fila['id']
        );
        return $usuario;
    }
}