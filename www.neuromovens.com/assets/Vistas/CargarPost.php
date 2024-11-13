<?php use Entidades\PostInvestigacion;

include '../Compartido/header.php';

?>




<?php if (isset($post) && $post instanceof PostInvestigacion): ?>
    <form action="../Controlador/ControladorPostInvestigacion.php" method="post">
        <!-- Campo oculto para indicar la acción de actualización -->
        <input type="hidden" name="accion" value="actualizar">

        <!-- Campo oculto para el ID del post -->
        <input type="hidden" name="post[id]" value="<?= $post->getId(); ?>">

        <!-- Campo para el título del post -->
        <label for="titulo">Título:</label><br>
        <input type="text" id="titulo" name="post[titulo]" value="<?= $post->getTitulo(); ?>" required><br><br>

        <!-- Campo para la descripción del post -->
        <label for="descripcion">Descripción:</label><br>
        <textarea id="descripcion" name="post[descripcion]" rows="4" required><?= $post->getContenido(); ?></textarea><br><br>

        <!-- Campo para la URL de la imagen -->
        <label for="imagen_url">URL de la imagen:</label><br>
        <input type="url" id="imagen_url" name="post[imagen_url]" value="<?= $post->getImagenUrl(); ?>"
               required><br><br>

        <!-- Botón de envío -->
        <input type="submit" value="Actualizar Post">
    </form>
<?php endif; ?>




<?php include '../Compartido/footer.php'; ?>
