<?php
// Obtener el nombre del archivo actual
$current_page = basename($_SERVER['PHP_SELF']);
echo '<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NeuroMovens - '.$current_page. '</title>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon">
</head>

<body>
    <header class="container-fluid d-flex flex-column flex-md-row justify-content-between align-items-center p-3">
        <div class="logo d-flex flex-column align-items-center">
            <img src="../images/neuronaBuena.png" alt="Logo Neuromuvens" class="mb-3 mb-md">
            <p class="texto_logo">NeuroMovens</p>
        </div>

        <!-- Navegación alineada a la derecha -->
        <nav class="d-flex flex-column justify-content-end flex-sm-row w-100 flex-wrap mt-3 mt-sm-0">
            <a href="../../index.php" class="d-flex align-items-center justify-content-center mx-2 my-1 ' . ($current_page == 'index.php' ? 'active' : '') . '">Quiénes Somos<i class="fa-solid fa-magnifying-glass"></i></a>
            <a href="investigacion.php" class="d-flex align-items-center justify-content-center mx-2 my-1 ' . ($current_page == 'investigacion.php' ? 'active' : '') . '">Investigación<i class="fa-solid fa-flask"></i></a>
            <a href="productos.php" class="d-flex align-items-center justify-content-center mx-2 my-1 ' . ($current_page == 'productos.php' ? 'active' : '') . '">Productos<i class="fa-solid fa-wheelchair"></i></a>
            <a href="contacto.php" class="d-flex align-items-center justify-content-center mx-2 my-1 ' . ($current_page == 'contacto.php' ? 'active' : '') . '">Contacto<i class="fa-solid fa-phone"></i></a>
        </nav>
    </header>';

