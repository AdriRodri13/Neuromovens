<?php use Entidades\PostInvestigacion;

include '../Compartido/header.php';

?>

<?php if (isset($post) && $post instanceof PostInvestigacion): ?>

<body>

<div class="form-container">
    <h2>Actualizar Post</h2>
    <form action="../Controlador/ControladorPostInvestigacion.php" method="post" enctype="multipart/form-data">
        <!-- Campo oculto para indicar la acción de actualización -->
        <input type="hidden" name="accion" value="actualizar">

        <!-- Campo oculto para el ID del post -->
        <input type="hidden" name="post[id]" value="<?= $post->getId(); ?>">

        <!-- Campo para el título del post -->
        <div class="form-group">
            <label for="titulo">Título:</label>
            <input type="text" id="titulo" name="post[titulo]" value="<?= $post->getTitulo(); ?>" required>
        </div>

        <!-- Campo para la descripción del post -->
        <div class="form-group">
            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="post[descripcion]" rows="4"
                      required><?= $post->getContenido(); ?></textarea>
        </div>

        <!-- Mostrar la imagen actual -->
        <div class="form-group image-preview">
            <label>Imagen Actual:</label><br>
            <img src="../images/<?= basename($post->getImagenUrl()); ?>" alt="Imagen Actual">
            <input type="hidden" name="imagenAntigua" value="../images/<?= basename($post->getImagenUrl()); ?>">
        </div>

        <!-- Campo para subir una nueva imagen -->
        <div class="form-group">
            <label for="imagen_url">Seleccionar nueva imagen (si desea cambiarla):</label>
            <input type="file" id="imagen_url" name="imagen_url" accept="image/jpeg, image/png">
        </div>

        <!-- Botón de envío -->
        <input type="submit" value="Actualizar Post">
    </form>
</div>
<?php endif; ?>




<?php include '../Compartido/footer.php'; ?>
