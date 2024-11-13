<?php include '../Compartido/header.php'; ?>

<form action="../Controlador/ControladorPostInvestigacion.php" method="post" enctype="multipart/form-data">
    <!-- Campo oculto para indicar la acción de inserción -->
    <input type="hidden" name="accion" value="insertar">

    <!-- Campo para el título del post -->
    <label for="titulo">Título:</label><br>
    <input type="text" id="titulo" name="post[titulo]" required><br><br>

    <!-- Campo para la descripción del post -->
    <label for="descripcion">Descripción:</label><br>
    <textarea id="descripcion" name="post[descripcion]" rows="4" required></textarea><br><br>

    <!-- Campo para la URL de la imagen -->
    <label for="imagen_url">Selecciona una imagen:</label><br>
    <input type="file" id="imagen_url" name="imagen_url" accept="image/jpeg" required><br><br>

    <!-- Botón de envío -->
    <input type="submit" value="Insertar Post">
</form>


<?php include '../Compartido/footer.php'; ?>
