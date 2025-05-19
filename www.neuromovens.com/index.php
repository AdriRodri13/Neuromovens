<?php

session_start();
$sesion_usuario = false;

if(isset($_SESSION['usuario']) && $_SESSION['usuario'] === true){
    $sesion_usuario = $_SESSION['usuario'];
    $nombre_usuario = $_SESSION['nombre_usuario'];
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NeuroMovens</title>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <!-- CSS personalizado -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="shortcut icon" href="assets/images/favicon.ico" type="image/x-icon">

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <!-- Slick Carousel -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
</head>

<body>
<header class="container-fluid p-3">
    <div class="d-flex justify-content-center justify-content-lg-between align-items-center">
        <!-- Logo y marca -->
        <div class="logo d-flex flex-column align-items-center">
            <img src="assets/images/neuronaBuena.png" alt="Logo NeuroMovens" class="mb-3 mb-md-0">
            <h1 class="texto_logo">NeuroMovens</h1>
        </div>

        <!-- Navegación para desktop -->
        <nav class="d-none d-lg-flex">
            <a href="index.php" class="d-flex align-items-center justify-content-center mx-2 my-1 active">
                Quiénes Somos <i class="fa-solid fa-magnifying-glass ms-2"></i>
            </a>
            <a href="assets/Controlador/ControladorPostInvestigacion.php?accion=listar" class="d-flex align-items-center justify-content-center mx-2 my-1">
                Investigación <i class="fa-solid fa-flask ms-2"></i>
            </a>
            <a href="assets/Controlador/ControladorProductos.php?accion=listar" class="d-flex align-items-center justify-content-center mx-2 my-1">
                Productos <i class="fa-solid fa-wheelchair ms-2"></i>
            </a>
            <a href="assets/Vistas/contacto.php" class="d-flex align-items-center justify-content-center mx-2 my-1">
                Contacto <i class="fa-solid fa-phone ms-2"></i>
            </a>
            <?php
            if(!$sesion_usuario){
                echo '<a href="assets/Vistas/iniciarSesion.php" class="d-flex align-items-center justify-content-center mx-2 my-1">Iniciar Sesión <i class="fa-solid fa-user ms-2"></i></a>';
            } else {
                echo '<a id="cerrarSesion" href="assets/Vistas/cerraSesion.php" class="d-flex align-items-center justify-content-center mx-2 my-1"><span id="nombreUsuario">' . htmlspecialchars($nombre_usuario) . '</span> <i class="fa-solid fa-user ms-2"></i></a>';
                if($_SESSION['rol'] == 'jefe'){
                    echo '<a id="listaUsuarios" href="assets/Controlador/ControladorUsuario.php?accion=listar" class="d-flex align-items-center justify-content-center mx-2 my-1">Administración <i class="fa-regular fa-note-sticky ms-2"></i></a>';
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
            <a href="index.php" class="d-flex align-items-center justify-content-center mx-2 my-1 active">
                Quiénes Somos <i class="fa-solid fa-magnifying-glass ms-2"></i>
            </a>
            <a href="assets/Controlador/ControladorPostInvestigacion.php?accion=listar" class="d-flex align-items-center justify-content-center mx-2 my-1">
                Investigación <i class="fa-solid fa-flask ms-2"></i>
            </a>
            <a href="assets/Controlador/ControladorProductos.php?accion=listar" class="d-flex align-items-center justify-content-center mx-2 my-1">
                Productos <i class="fa-solid fa-wheelchair ms-2"></i>
            </a>
            <a href="assets/Vistas/contacto.php" class="d-flex align-items-center justify-content-center mx-2 my-1">
                Contacto <i class="fa-solid fa-phone ms-2"></i>
            </a>
            <?php
            if(!$sesion_usuario){
                echo '<a href="assets/Vistas/iniciarSesion.php" class="d-flex align-items-center justify-content-center mx-2 my-1">Iniciar Sesión <i class="fa-solid fa-user ms-2"></i></a>';
            } else {
                echo '<a id="cerrarSesion" href="assets/Vistas/cerraSesion.php" class="d-flex align-items-center justify-content-center mx-2 my-1"><span id="nombreUsuario">' . htmlspecialchars($nombre_usuario) . '</span> <i class="fa-solid fa-user ms-2"></i></a>';
                if($_SESSION['rol'] == 'jefe'){
                    echo '<a id="listaUsuarios" href="assets/Controlador/ControladorUsuario.php?accion=listar" class="d-flex align-items-center justify-content-center mx-2 my-1">Administración <i class="fa-regular fa-note-sticky ms-2"></i></a>';
                }
            }
            ?>
        </nav>
    </div>
</header>

<!-- Main content -->
<main class="container py-4 py-md-5">
    <h1 class="title">Quiénes Somos</h1>

    <!-- Sección de equipo -->
    <section class="row justify-content-center mb-5">
        <div class="col-12">
            <div class="row align-items-center p-3 p-md-4 shadow-lg equipo">
                <!-- Imagen a la izquierda en pantallas grandes -->
                <div class="col-12 col-md-5 mb-4 mb-md-0">
                    <div class="equipo-slideshow">
                        <div><img src="assets/images/imagen_personal.jpg" alt="Equipo NeuroMovens" class="img-fluid rounded shadow-sm"></div>
                        <div><img src="assets/images/imagen_personal2.jpg" alt="Equipo NeuroMovens" class="img-fluid rounded shadow-sm"></div>
                        <div><img src="assets/images/imagen_personal3.jpg" alt="Equipo NeuroMovens" class="img-fluid rounded shadow-sm"></div>
                        <div><img src="assets/images/imagen_personal4.jpg" alt="Equipo NeuroMovens" class="img-fluid rounded shadow-sm"></div>
                    </div>
                </div>
                <!-- Texto a la derecha en pantallas grandes -->
                <div class="col-12 col-md-7">
                    <div class="p-3 p-md-4 bg-white rounded">
                        <h2 class="mb-3 text-dark font-weight-bold">Equipo NeuroMovens</h2>
                        <p class="lead text-muted mb-4">
                            En nuestro laboratorio, contamos con un equipo de profesionales altamente capacitados y
                            comprometidos para llevar a cabo un excelente tratamiento remielinizante:
                        </p>
                        <ul class="list-unstyled text-dark">
                            <li><strong>Sandra</strong> - Coordinadora de Investigación y Desarrollo (I+D)</li>
                            <li><strong>Sofía</strong> - Responsable de Calidad y Control de Procesos</li>
                            <li><strong>Lucía</strong> - Comunicación Científica y Relaciones Comerciales</li>
                            <li><strong>Laura</strong> - Coordinadora de Finanzas y Vinculación Estratégica</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sección de tarjetas -->
    <section class="row justify-content-center">
        <div class="col-12">
            <div class="row g-3 g-md-4">
                <!-- Tarjeta Misión -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card text-center tarjeta shadow h-100 d-flex flex-column">
                        <div class="card-body card-body-1 flex-shrink-0" style="min-height: 120px;">
                            <i class="fa-solid fa-bullseye fa-2x"></i>
                            <h2 class="mt-3">Misión</h2>
                        </div>
                        <div class="card-body card-body-2 flex-grow-1 d-flex align-items-center">
                            <p class="mb-0">En <span class="destacado">NeuroMovens</span>, nuestra misión es proporcionar
                                soluciones innovadoras en el campo de
                                la neurociencia y facilitar la <span class="destacado">movilidad</span> de personas
                                que padecen <span class="destacado">esclerosis múltiple</span>. Nos comprometemos a
                                investigar un tratamiento <span class="destacado">remielinizante</span> para tratar
                                esta enfermedad neurodegenerativa.</p>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta Visión -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card text-center tarjeta shadow h-100 d-flex flex-column">
                        <div class="card-body card-body-1 flex-shrink-0" style="min-height: 120px;">
                            <i class="fa-solid fa-eye fa-2x"></i>
                            <h2 class="mt-3">Visión</h2>
                        </div>
                        <div class="card-body card-body-2 flex-grow-1 d-flex align-items-center">
                            <p class="mb-0">En <span class="destacado">NeuroMovens</span> aspiramos a ser un referente
                                internacional en el campo de la
                                neurociencia, liderando la investigación y el desarrollo de un tratamiento basado en
                                la raíz del problema. De aquí a <span class="destacado">5 años</span> esperamos
                                poder ser reconocidos y avanzar en
                                la investigación de nuestro tratamiento <span class="destacado">innovador</span>.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta Valores -->
                <div class="col-12 col-md-12 col-lg-4">
                    <div class="card text-center tarjeta shadow h-100 d-flex flex-column">
                        <div class="card-body card-body-1 flex-shrink-0" style="min-height: 120px;">
                            <i class="fa-regular fa-heart fa-2x"></i>
                            <h2 class="mt-3">Valores</h2>
                        </div>
                        <div class="card-body card-body-2 flex-grow-1 d-flex align-items-center">
                            <p class="mb-0">Nos esforzamos por estar actualizados científicamente y tecnológicamente usando la
                                <span class="destacado">terapia celular</span>. Actuamos siempre con responsabilidad
                                y respeto por nuestros
                                pacientes. Estamos comprometidos con las normas <span class="destacado">ISO
                                        9001</span> y <span class="destacado">15189</span>, además valoramos
                                el trabajo conjunto entre profesionales sanitarios.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- Footer -->
<footer class="bg-secondary text-white py-4">
    <div class="container">
        <div class="row g-3">
            <!-- Columna de contacto -->
            <div class="col-12 col-md-8">
                <h3 class="mb-3">Contacto</h3>
                <div class="row g-2">
                    <div class="col-12 col-sm-6">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-phone-alt me-2"></i>
                            <a href="tel:+123456789" class="text-white text-decoration-none">+1 234 567 89</a>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-envelope me-2"></i>
                            <a href="mailto:contacto@ejemplo.com" class="text-white text-decoration-none">contacto@ejemplo.com</a>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-map-marker-alt me-2 mt-1"></i>
                            <span class="text-white">Calle San Ignacio de Loyola 30, 03013 Alicante, España</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna de enlaces legales -->
            <div class="col-12 col-md-4 text-md-end">
                <h3 class="mb-3">Información Legal</h3>
                <div class="d-flex flex-column gap-2">
                    <a href="#" class="text-white text-decoration-none">Términos y Condiciones</a>
                    <a href="#" class="text-white text-decoration-none">Política de Privacidad</a>
                    <a href="#" class="text-white text-decoration-none">Aviso Legal</a>
                </div>
            </div>
        </div>
    </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function(){
            $('.equipo-slideshow').slick({
                dots: true,
                arrows: false,
                autoplay: true,
                autoplaySpeed: 4000,
                fade: true,
                cssEase: 'linear',
                speed: 500,
                adaptiveHeight: true
            });
        });
    </script>
</body>

</html>