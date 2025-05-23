<?php
use Entidades\PostInvestigacion;

include '../Compartido/header.php';
require '../Entidades/PostInvestigacion.php';
// Verificación de sesión de usuario
$sesion_usuario = isset($_SESSION['usuario']) && $_SESSION['usuario'] === true;
if($sesion_usuario){
    $rol_usuario = $_SESSION['rol'];
}else{
    $rol_usuario = 'visitante';
}

?>

    <h1 class="title d-flex justify-content-center">Investigación</h1>

    <main class="container py-4">
        <!-- Si el usuario está logueado, mostrar enlace para insertar nuevo post -->
        <?php if ($sesion_usuario && $rol_usuario !== 'visitante'): ?>
            <div class="text-end mb-4">
                <a href="../Vistas/InsertarPostInvestigacion.php?accion=insertar" class="btn btn-success">Insertar</a>
            </div>
        <?php endif; ?>

        <?php
        // Deserializar los posts desde la sesión
        $posts = isset($_SESSION['posts']) ? unserialize($_SESSION['posts']) : [];
        ?>

        <?php if (!empty($posts)): ?>
            <?php
            $contador = 1;
            foreach ($posts as $post):
                if ($post instanceof PostInvestigacion):
                    ?>
                    <section class="section-container section-<?php echo $contador; ?> mb-5">
                        <!-- Estructura rediseñada con Bootstrap para mobile-first -->
                        <div class="card shadow">
                            <div class="row g-0">
                                <!-- Imagen - En móvil aparece arriba, en desktop a la izquierda -->
                                <div class="col-12 col-lg-6">
                                    <div class="image-container p-2 p-md-3 h-100">
                                        <img src="<?php echo $post->getImagenUrl(); ?>"
                                             alt="Imagen de Post de Investigación"
                                             class="img-fluid rounded d-block mx-auto h-100 object-fit-cover"
                                             style="max-height: 400px; width: 100%;">
                                    </div>
                                </div>

                                <!-- Texto - En móvil aparece abajo, en desktop a la derecha -->
                                <div class="col-12 col-lg-6">
                                    <div class="card-body p-4">
                                        <h2 class="card-title mb-3"><?php echo $post->getTitulo(); ?></h2>
                                        <p class="card-text text-justify"><?php echo $post->getContenido(); ?></p>

                                        <!-- Botones de editar y eliminar -->
                                        <?php if ($sesion_usuario && $rol_usuario !== 'visitante'): ?>
                                            <div class="mt-3 text-end">
                                                <a href="../Controlador/ControladorPostInvestigacion.php?accion=cargar&id=<?php echo $post->getId(); ?>"
                                                   class="btn btn-info me-2">Editar</a>
                                                <a href="../Controlador/ControladorPostInvestigacion.php?accion=eliminar&id=<?php echo $post->getId(); ?>"
                                                   class="btn btn-danger">Eliminar</a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <?php
                    // Alternar contador para cambiar el estilo de la sección
                    $contador = ($contador == 1) ? 2 : 1;
                endif;
            endforeach;
            ?>
        <?php else: ?>
            <div class="alert alert-info text-center">
                <p class="mb-0">No hay posts disponibles.</p>
            </div>
        <?php endif; ?>
    </main>

    <script src="../js/aparicion.js"></script>

<?php include_once '../Compartido/footer.php'; ?>