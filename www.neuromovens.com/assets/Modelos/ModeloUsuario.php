<?php

namespace Modelos;

use Entidades\Entidad;
use Entidades\Rol;
use Entidades\Usuario;
use Exception;
use PDO;
use PDOException;

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

    public function add(Entidad $usuario) {
        if($usuario instanceof Usuario){
            if(!$this->comprobarUsuario($usuario)){
                $contraHash = password_hash($usuario->getContra(), PASSWORD_DEFAULT);
                $sql = "INSERT INTO usuarios (nombre_usuario, email, contraseña) VALUES (:nombre, :email, :contra)";
                $stmt = $this->getConexion()->prepare($sql);
                $stmt->bindValue(':nombre', $usuario->getNombreUsuario(), PDO::PARAM_STR);
                $stmt->bindValue(':email', $usuario->getEmail(), PDO::PARAM_STR);
                $stmt->bindValue(':contra', $contraHash, PDO::PARAM_STR);

                if($stmt->execute()){
                    return true; // Usuario creado exitosamente
                } else {
                    return false; // Error en la inserción
                }
            } else {
                // Usuario ya existe
                return 'usuario_existe';
            }
        }
        return false;
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

    // MÉTODO MODIFICADO: Usar la versión paginada
    public function obtener()
    {
        $resultado = $this->obtenerPaginado(1, 1000); // Obtener todos
        return $resultado['usuarios'];
    }

    // NUEVO MÉTODO: Obtener usuarios con paginación
    public function obtenerPaginado(int $pagina = 1, int $porPagina = 5): array {
        return $this->buscarUsuarios('', $pagina, $porPagina);
    }

    // NUEVO MÉTODO: Buscar usuarios con paginación
    // VERSIÓN CON DEBUG - Para el ModeloUsuario.php

    // VERSIÓN CORREGIDA del método buscarUsuarios() en ModeloUsuario.php

    public function buscarUsuarios(string $termino, int $pagina = 1, int $porPagina = 5): array {
        $offset = ($pagina - 1) * $porPagina;

        // Construir la consulta de búsqueda
        $whereClauses = [];
        $params = [];

        if (!empty($termino)) {
            $whereClauses[] = "(nombre_usuario LIKE ? OR email LIKE ?)";
            $params[] = '%' . $termino . '%';
            $params[] = '%' . $termino . '%';
        }

        $whereSQL = !empty($whereClauses) ? 'WHERE ' . implode(' AND ', $whereClauses) : '';

        try {
            // Consulta para obtener el total de registros
            $sqlCount = "SELECT COUNT(*) as total FROM usuarios $whereSQL";
            $stmtCount = $this->getConexion()->prepare($sqlCount);

            // Ejecutar con los parámetros de búsqueda (si los hay)
            if (!empty($params)) {
                $stmtCount->execute($params);
            } else {
                $stmtCount->execute();
            }

            $total = $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];

            // Consulta para obtener los usuarios
            $sql = "SELECT * FROM usuarios $whereSQL ORDER BY nombre_usuario ASC LIMIT ? OFFSET ?";
            $stmt = $this->getConexion()->prepare($sql);

            // Preparar parámetros para la consulta principal
            $paramsConLimit = $params; // Copiar parámetros de búsqueda
            $paramsConLimit[] = $porPagina; // Añadir LIMIT
            $paramsConLimit[] = $offset;    // Añadir OFFSET

            $stmt->execute($paramsConLimit);
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Convertir a objetos Usuario
            $usuarios = [];
            foreach ($resultados as $fila) {
                $rol = Rol::tryFrom($fila['rol'] ?? 'visitante') ?? Rol::visitante;
                $usuarios[] = new Usuario(
                    $fila['nombre_usuario'],
                    $fila['contraseña'],
                    $fila['email'],
                    $rol,
                    $fila['id']
                );
            }

            return [
                'usuarios' => $usuarios,
                'total' => intval($total),
                'total_paginas' => ceil($total / $porPagina),
                'pagina_actual' => $pagina,
                'por_pagina' => $porPagina
            ];

        } catch (PDOException $e) {
            throw new Exception("Error en la base de datos: " . $e->getMessage());
        }
    }


    // En este caso obtenemos el usuario por ID
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