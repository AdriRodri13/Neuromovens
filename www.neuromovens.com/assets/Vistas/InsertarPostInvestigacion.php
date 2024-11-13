<?php include '../Compartido/header.php'; ?>

<form action="../Controlador/ControladorPostInvestigacion.php" method="post">
    <!-- Campo oculto para indicar la acción de inserción -->
    <input type="hidden" name="accion" value="insertar">

    <!-- Campo para el título del post -->
    <label for="titulo">Título:</label><br>
    <input type="text" id="titulo" name="post[titulo]" required><br><br>

    <!-- Campo para la descripción del post -->
    <label for="descripcion">Descripción:</label><br>
    <textarea id="descripcion" name="post[descripcion]" rows="4" required></textarea><br><br>

    <!-- Campo para la URL de la imagen -->
    <label for="imagen_url">URL de la imagen:</label><br>
    <input type="url" id="imagen_url" name="post[imagen_url]" required><br><br>

    <!-- Botón de envío -->
    <input type="submit" value="Insertar Post">
</form>


<?php include '../Compartido/footer.php'; ?>
