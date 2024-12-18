<?php
// Obtener el nombre del archivo actual
$current_page = basename($_SERVER['SCRIPT_NAME']);
$current_page = match ($current_page) {
    "investigacion.php" => "Investigacion",
    "productos.php" => "Productos",
    "contacto.php" => "Contacto",
    "iniciarSesion.php" => "Iniciar sesion",
    default => "Pruebas"
};

session_start();
$sesion_usuario = false;

if(isset($_SESSION['usuario']) && $_SESSION['usuario'] === true){
    $sesion_usuario = $_SESSION['usuario'];
    $nombre_usuario = $_SESSION['nombre_usuario'];

}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NeuroMovens - <?php echo htmlspecialchars($current_page); ?></title>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon">
    <script src="../js/cambioTexto.js"></script>
</head>

<body>
<header class="container-fluid d-flex flex-column flex-md-row justify-content-between align-items-center p-3">
    <div class="logo d-flex flex-column align-items-center">
        <img src="../images/neuronaBuena.png" alt="Logo NeuroMovens" class="mb-3 mb-md">
        <p class="texto_logo">NeuroMovens</p>
    </div>

    <!-- Navegación alineada a la derecha -->
    <nav class="d-flex flex-column justify-content-end flex-sm-row w-100 flex-wrap mt-3 mt-sm-0">
        <a href="../../index.php" class="d-flex align-items-center justify-content-center mx-2 my-1 <?php echo ($current_page == 'index.php' ? 'active' : ''); ?>">Quiénes Somos<i class="fa-solid fa-magnifying-glass"></i></a>
        <a href="../Controlador/ControladorPostInvestigacion.php?accion=listar" class="d-flex align-items-center justify-content-center mx-2 my-1 <?php echo ($current_page == 'Investigacion' ? 'active' : ''); ?>">Investigación<i class="fa-solid fa-flask"></i></a>
        <a href="../Controlador/ControladorProductos.php?accion=listar" class="d-flex align-items-center justify-content-center mx-2 my-1 <?php echo ($current_page == 'Productos' ? 'active' : ''); ?>">Productos<i class="fa-solid fa-wheelchair"></i></a>
        <a href="contacto.php" class="d-flex align-items-center justify-content-center mx-2 my-1 <?php echo ($current_page == 'Contacto' ? 'active' : ''); ?>">Contacto<i class="fa-solid fa-phone"></i></a>
        <?php
        if(!$sesion_usuario){
        echo '<a id="iniciarSesion"  href="../Vistas/iniciarSesion.php" class="d-flex align-items-center justify-content-center mx-2 my-1">Iniciar Sesion
            <i class="fa-solid fa-user"></i></a>';
        } else {
        echo '<a id="cerrarSesion" href="../Vistas/cerraSesion.php" class="d-flex align-items-center justify-content-center mx-2 my-1"><span id="nombreUsuario">' . $nombre_usuario . '</span><i class="fa-solid fa-user"></i></a>';
            if($_SESSION['rol'] == 'jefe'){
                echo '<a id="listaUsuarios"  href="../Controlador/ControladorUsuario.php?accion=listar" class="d-flex align-items-center justify-content-center mx-2 my-1">Administracion
                <i class="fa-regular fa-note-sticky"></i></a>';
            }
        }
        ?>
    </nav>

</header>


