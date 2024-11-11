<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NeuroMovens</title>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="shortcut icon" href="assets/images/favicon.ico" type="image/x-icon">
</head>

<body>
    <header class="container-fluid d-flex flex-column flex-md-row justify-content-between align-items-center p-3">
        <div class="logo d-flex flex-column align-items-center">
            <img src="assets/images/neuronaBuena.png" alt="Logo Neuromuvens" class="mb-3 mb-md">
            <p class="texto_logo">NeuroMovens</p>
        </div>


        <!-- Navegación alineada a la derecha -->
        <nav class="d-flex flex-column justify-content-end flex-sm-row w-100 flex-wrap mt-3 mt-sm-0">
            <a href="index.php" class="d-flex align-items-center justify-content-center mx-2 my-1 active">Quiénes
                Somos<i class="fa-solid fa-magnifying-glass"></i></a>
            <a href="assets/Vistas/investigacion.php"
                class="d-flex align-items-center justify-content-center mx-2 my-1">Investigación<i
                    class="fa-solid fa-flask"></i></a>
            <a href="assets/Vistas/productos.php"
                class="d-flex align-items-center justify-content-center mx-2 my-1">Productos<i
                    class="fa-solid fa-wheelchair"></i></a>
            <a href="assets/Vistas/contacto.php"
                class="d-flex align-items-center justify-content-center mx-2 my-1">Contacto<i
                    class="fa-solid fa-phone"></i></a>
        </nav>
    </header>
    <main class="container py-5">

        <h1 class="title">Quienes somos</h1>


        <!-- Sección de equipo -->
        <section class="row justify-content-center mb-5">
            <div class="col-12 col-md-12">
                <div class="row align-items-center p-4 shadow-lg equipo">
                    <!-- Imagen a la izquierda en pantallas grandes -->
                    <div class="col-md-5 mb-4 mb-md-0">
                        <img src="assets/images/imagen_personal.jpg" alt="Foto Equipo Neuromuvens"
                            class="img-fluid rounded shadow-sm imagen_equipo" id="imagen_equipo">
                    </div>
                    <!-- Texto a la derecha en pantallas grandes, ocupa el 80% en pantallas medianas y el 100% en móviles -->
                    <div class="col-12 col-md-7">
                        <div class="p-4 bg-white rounded">
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
            <div class="col-12 col-md-12">
                <!-- Tarjetas dispuestas en fila en pantallas medianas y grandes -->
                <div class="row g-4">
                    <!-- Tarjeta 1 -->
                    <div class="col-12 col-md-4">
                        <div class="card text-center tarjeta shadow">
                            <div class="card-body card-body-1">
                                <i class="fa-solid fa-bullseye fa-2x"></i>
                                <h2 class="mt-3">Misión</h2>
                            </div>
                            <div class="card-body card-body-2">
                                <p>En <span class="destacado">NeuroMovens</span>, nuestra misión es proporcionar
                                    soluciones innovadoras en el campo de
                                    la neurociencia y facilitar la <span class="destacado">movilidad</span> de personas
                                    que padecen <span class="destacado">esclerosis múltiple</span>. Nos comprometemos a
                                    investigar un tratamiento <span class="destacado">remielinizante</span> para tratar
                                    esta enfermedad neurodegenerativa.</p>
                            </div>
                        </div>
                    </div>
                    <!-- Tarjeta 2 -->
                    <div class="col-12 col-md-4">
                        <div class="card text-center tarjeta shadow">
                            <div class="card-body card-body-1">
                                <i class="fa-solid fa-eye fa-2x"></i>
                                <h2 class="mt-3">Visión</h2>
                            </div>
                            <div class="card-body card-body-2">
                                <p>En <span class="destacado">NeuroMovens</span> aspiramos a ser un referente
                                    internacional en el campo de la
                                    neurociencia, liderando la investigación y el desarrollo de un tratamiento basado en
                                    la raíz del problema. De aquí a <span class="destacado">5 años</span> esperamos
                                    poder ser reconocidos y avanzar en
                                    la investigación de nuestro tratamiento <span class="destacado">innovador</span>.
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- Tarjeta 3 -->
                    <div class="col-12 col-md-4">
                        <div class="card text-center tarjeta shadow">
                            <div class="card-body card-body-1">
                                <i class="fa-regular fa-heart fa-2x"></i>
                                <h2 class="mt-3">Valores</h2>
                            </div>
                            <div class="card-body card-body-2">
                                <p>Nos esforzamos por estar actualizados científicamente y tecnológicamente usando la
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



    <footer class="bg-secondary text-white py-4">
        <div class="container">
            <div class="row">
                <!-- Columna de contacto ampliada a la izquierda -->
                <div class="col-md-8">
                    <h3 class="mb-3">Contacto</h3>
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-phone-alt me-2"></i>
                        <a href="tel:+123456789" class="text-white">+1 234 567 89</a>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-envelope me-2"></i>
                        <a href="mailto:contacto@ejemplo.com" class="text-white">contacto@ejemplo.com</a>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        <span class="text-white">Calle San Ignacio de Loyola 30, 03013 Alicante, España</span>
                    </div>
                </div>

                <!-- Columna de necesidades legales a la derecha -->
                <div class="col-md-4 text-md-right">
                    <h3 class="mb-3">Información Legal</h3>
                    <div class="mb-2">
                        <a href="#" class="text-white">Términos y Condiciones</a>
                    </div>
                    <div class="mb-2">
                        <a href="#" class="text-white">Política de Privacidad</a>
                    </div>
                    <div>
                        <a href="#" class="text-white">Aviso Legal</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>

</html>