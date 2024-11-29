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

<h1 class="title">Investigación</h1>

<main>
    <!-- Si el usuario está logueado, mostrar enlace para insertar nuevo post -->
    <?php if ($sesion_usuario && $rol_usuario !== 'visitante'): ?>
        <a href="../Vistas/InsertarPostInvestigacion.php?accion=insertar" class="btn btn-success">Insertar</a>
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
                <section class="section-container section-<?php echo $contador; ?>">
                    <div class="section-content">
                        <!-- Imagen del Post -->
                        <div class="col-md-6">
                            <img src="<?php echo $post->getImagenUrl(); ?>" alt="Imagen de Post de Investigación">
                        </div>

                        <!-- Información del Post -->
                        <div class="col-md-6 text-container">
                            <h2><?php echo $post->getTitulo(); ?></h2>
                            <p><?php echo $post->getContenido(); ?></p>
                        </div>
                    </div>

                    <!-- Mostrar botones de editar y eliminar si el usuario está logueado -->
                    <?php if ($sesion_usuario && $rol_usuario !== 'visitante'): ?>
                        <a href="../Controlador/ControladorPostInvestigacion.php?accion=cargar&id=<?php echo $post->getId(); ?>" class="btn btn-info">Editar</a>
                        <a href="../Controlador/ControladorPostInvestigacion.php?accion=eliminar&id=<?php echo $post->getId(); ?>" class="btn btn-danger">Eliminar</a>
                    <?php endif; ?>
                </section>

                <?php
                // Alternar contador para cambiar el estilo de la sección
                $contador = ($contador == 1) ? 2 : 1;
            endif;
        endforeach;
        ?>
    <?php else: ?>
        <p>No hay posts disponibles.</p>
    <?php endif; ?>
</main>

<script src="../js/aparicion.js"></script>

<?php include_once '../Compartido/footer.php'; ?>

