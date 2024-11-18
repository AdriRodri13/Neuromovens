<?php

namespace Entidades;
use Exception;
class View
{
    public static function render($viewPath, $data = []) {
        // Extrae las claves del array como variables para la vista
        extract($data);

        // Verifica si la vista existe
        if (file_exists($viewPath)) {
            include $viewPath; // Incluye el archivo de vista especificado
        } else {
            throw new Exception("La vista $viewPath no existe.");
        }
    }

}