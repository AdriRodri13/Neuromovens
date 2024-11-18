<?php

function establecerConexion(): PDO {

    $host = 'srv1582.hstgr.io';  // Dirección del servidor
    $db = 'u178676236_neuromovens';  // Nombre de la base de datos
    $user = 'u178676236_adrian';  // Usuario de la base de datos
    $pass = '257226Aa';  // Contraseña del usuario
    $charset = 'utf8mb4';  // Codificación de caracteres

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Manejo de errores con excepciones
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Modo de fetch predeterminado
        PDO::ATTR_EMULATE_PREPARES => false, // Desactivar la emulación de consultas preparadas
    ];

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";  // Data Source Name

    try {
        // Crear una nueva instancia de PDO
        $pdo = new PDO($dsn, $user, $pass, $options);
        return $pdo;
    } catch (PDOException $e) {
        // Manejar el error de conexión
        echo 'Error en la conexión: ' . $e->getMessage();
    }
}