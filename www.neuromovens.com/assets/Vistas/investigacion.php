<?php use Entidades\PostInvestigacion;

include '../Compartido/header.php';

$sesion_usuario = false;
if(isset($_SESSION['usuario']) && $_SESSION['usuario'] === true){
    $sesion_usuario = $_SESSION['usuario'];
}

?>




    <h1 class="title">Investigación</h1>

<main>


    <?php if ($sesion_usuario): ?>
        <a href="../Vistas/InsertarPostInvestigacion.php?accion=insertar">Insertar</a>
    <?php endif; ?>


    <?php if (isset($posts)) : ?>
        <?php $contador = 1; ?>
        <?php foreach ($posts as $post): ?>
            <?php if ($post instanceof PostInvestigacion): ?>
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
                    <?php if ($sesion_usuario): ?>
                        <a href="../Controlador/ControladorPostInvestigacion.php?accion=cargar&id=<?php echo $post->getId(); ?>">Editar</a>
                        <a href="../Controlador/ControladorPostInvestigacion.php?accion=eliminar&id=<?php echo $post->getId(); ?>">Eliminar</a>
                    <?php endif; ?>
                </section>
                <?php $contador = ($contador == 1) ? 2 : 1; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
    </main>
    <script src="../js/aparicion.js"></script>

    <?php include_once '../Compartido/footer.php'?>

