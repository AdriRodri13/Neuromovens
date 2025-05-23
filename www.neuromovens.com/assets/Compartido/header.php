<?php
// Obtener el nombre del archivo actual para marcar la página actual en la navegación
$current_page = basename($_SERVER['SCRIPT_NAME']);
$current_page = match ($current_page) {
    "investigacion.php" => "Investigacion",
    "productos.php" => "Productos",
    "contacto.php" => "Contacto",
    "iniciarSesion.php" => "Iniciar sesion",
    "index.php" => "Quienes Somos",
    default => "Quienes Somos"
};

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$sesion_usuario = false;

if(isset($_SESSION['usuario']) && $_SESSION['usuario'] === true){
    $sesion_usuario = $_SESSION['usuario'];
    $nombre_usuario = $_SESSION['nombre_usuario'];
}

// Determinar la ruta base para assets según la ubicación del archivo
$is_root = (strpos($_SERVER['SCRIPT_NAME'], '/assets/') === false && strpos($_SERVER['SCRIPT_NAME'], '/index.php') !== false);
$base_path = $is_root ? 'assets/' : '../';
$root_path = $is_root ? '' : '../../';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="NeuroMovens - Investigación y productos para neurorehabilitación">
    <title>NeuroMovens - <?php echo htmlspecialchars($current_page); ?></title>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <!-- jQuery UI (para widgets interactivos) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/themes/base/jquery-ui.min.css">

    <!-- jQuery Validate (para validación de formularios) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.20.0/jquery.validate.min.js"></script>

    <!-- Slick Carousel (para slideshows) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">

    <!-- SweetAlert2 para alertas mejoradas -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.7.32/sweetalert2.all.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.7.32/sweetalert2.min.css">

    <!-- CSS personalizado (solo estético) -->
    <link rel="stylesheet" href="<?php echo $base_path; ?>css/style.css">
    <link rel="shortcut icon" href="<?php echo $base_path; ?>images/favicon.ico" type="image/x-icon">
</head>

<body class="d-flex flex-column min-vh-100">
<header class="container-fluid p-3">
    <div class="d-flex justify-content-center justify-content-lg-between align-items-center">
        <!-- Logo y marca -->
        <div class="logo d-flex flex-column align-items-center p-2">
            <img src="<?php echo $base_path; ?>images/neuronaBuena.png" alt="Logo NeuroMovens" class="img-fluid mb-3 mb-md-0" style="max-height: 90px;">
            <h1 class="texto_logo fs-1">NeuroMovens</h1>
        </div>

        <!-- Navegación para desktop (visible en lg y superior) -->
        <nav class="d-none d-lg-flex">
            <a href="<?php echo $root_path; ?>index.php" class="d-flex align-items-center justify-content-center mx-2 my-1 py-2 px-3 <?php echo ($current_page == 'Quienes Somos' ? 'active' : ''); ?>">
                Quiénes Somos <i class="fa-solid fa-magnifying-glass ms-2"></i>
            </a>
            <a href="<?php echo $base_path; ?>Controlador/ControladorPostInvestigacion.php?accion=listar" class="d-flex align-items-center justify-content-center mx-2 my-1 py-2 px-3 <?php echo ($current_page == 'Investigacion' ? 'active' : ''); ?>">
                Investigación <i class="fa-solid fa-flask ms-2"></i>
            </a>
            <a href="<?php echo $base_path; ?>Controlador/ControladorProductos.php?accion=listar" class="d-flex align-items-center justify-content-center mx-2 my-1 py-2 px-3 <?php echo ($current_page == 'Productos' ? 'active' : ''); ?>">
                Productos <i class="fa-solid fa-wheelchair ms-2"></i>
            </a>
            <a href="<?php echo $base_path; ?>Vistas/contacto.php" class="d-flex align-items-center justify-content-center mx-2 my-1 py-2 px-3 <?php echo ($current_page == 'Contacto' ? 'active' : ''); ?>">
                Contacto <i class="fa-solid fa-phone ms-2"></i>
            </a>
            <?php
            if(!$sesion_usuario){
                echo '<a href="' . $base_path . 'Vistas/iniciarSesion.php" class="d-flex align-items-center justify-content-center mx-2 my-1 py-2 px-3 ' . ($current_page == 'Iniciar sesion' ? 'active' : '') . '">Iniciar Sesión <i class="fa-solid fa-user ms-2"></i></a>';
            } else {
                echo '<a id="cerrarSesion" href="' . $base_path . 'Vistas/cerraSesion.php" class="d-flex align-items-center justify-content-center mx-2 my-1 py-2 px-3">Cerrar Sesion <i class="fa-solid fa-user ms-2"></i></a>';
                if($_SESSION['rol'] == 'jefe'){
                    echo '<a id="listaUsuarios" href="' . $base_path . 'Controlador/ControladorUsuario.php?accion=listar" class="d-flex align-items-center justify-content-center mx-2 my-1 py-2 px-3">Administración <i class="fa-regular fa-note-sticky ms-2"></i></a>';
                }
            }
            ?>
        </nav>
    </div>

    <!-- Botón hamburguesa para móviles y tablets (debajo del logo) -->
    <div class="d-flex justify-content-center d-lg-none mt-3">
        <button class="navbar-toggler border-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fa fa-bars" aria-hidden="true"></i>
        </button>
    </div>

    <!-- Navegación colapsable para móviles y tablets -->
    <div class="collapse w-100 mt-3" id="navbarNav">
        <nav class="d-flex flex-column">
            <a href="<?php echo $root_path; ?>index.php" class="d-flex align-items-center justify-content-center mx-2 my-1 py-2 px-3 <?php echo ($current_page == 'Quienes Somos' ? 'active' : ''); ?>">
                Quiénes Somos <i class="fa-solid fa-magnifying-glass ms-2"></i>
            </a>
            <a href="<?php echo $base_path; ?>Controlador/ControladorPostInvestigacion.php?accion=listar" class="d-flex align-items-center justify-content-center mx-2 my-1 py-2 px-3 <?php echo ($current_page == 'Investigacion' ? 'active' : ''); ?>">
                Investigación <i class="fa-solid fa-flask ms-2"></i>
            </a>
            <a href="<?php echo $base_path; ?>Controlador/ControladorProductos.php?accion=listar" class="d-flex align-items-center justify-content-center mx-2 my-1 py-2 px-3 <?php echo ($current_page == 'Productos' ? 'active' : ''); ?>">
                Productos <i class="fa-solid fa-wheelchair ms-2"></i>
            </a>
            <a href="<?php echo $base_path; ?>Vistas/contacto.php" class="d-flex align-items-center justify-content-center mx-2 my-1 py-2 px-3 <?php echo ($current_page == 'Contacto' ? 'active' : ''); ?>">
                Contacto <i class="fa-solid fa-phone ms-2"></i>
            </a>
            <?php
            if(!$sesion_usuario){
                echo '<a href="' . $base_path . 'Vistas/iniciarSesion.php" class="d-flex align-items-center justify-content-center mx-2 my-1 py-2 px-3 ' . ($current_page == 'Iniciar sesion' ? 'active' : '') . '">Iniciar Sesión <i class="fa-solid fa-user ms-2"></i></a>';
            } else {
                echo '<a id="cerrarSesion" href="' . $base_path . 'Vistas/cerraSesion.php" class="d-flex align-items-center justify-content-center mx-2 my-1 py-2 px-3">Cerrar Sesion <i class="fa-solid fa-user ms-2"></i></a>';
                if($_SESSION['rol'] == 'jefe'){
                    echo '<a id="listaUsuarios" href="' . $base_path . 'Controlador/ControladorUsuario.php?accion=listar" class="d-flex align-items-center justify-content-center mx-2 my-1 py-2 px-3">Administración <i class="fa-regular fa-note-sticky ms-2"></i></a>';
                }
            }
            ?>
        </nav>
    </div>
</header>

<!-- Contenedor principal con clases móvil-primero -->
<main class="container py-4 py-md-5 flex-grow-1">