<?php include '../Compartido/header.php'; ?>

<body>

<div class="form-container">
    <h2>Insertar Nuevo Post</h2>
    <form action="../Controlador/ControladorPostInvestigacion.php" method="post" enctype="multipart/form-data">
        <!-- Campo oculto para indicar la acción de inserción -->
        <input type="hidden" name="accion" value="insertar">

        <!-- Campo para el título del post -->
        <div class="form-group">
            <label for="titulo">Título:</label>
            <input type="text" id="titulo" name="post[titulo]" required>
        </div>

        <!-- Campo para la descripción del post -->
        <div class="form-group">
            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="post[descripcion]" rows="4" required></textarea>
        </div>

        <!-- Campo para la URL de la imagen -->
        <div class="form-group">
            <label for="imagen_url">Selecciona una imagen:</label>
            <input type="file" id="imagen_url" name="imagen_url" accept="image/jpeg, image/png" required>
        </div>

        <!-- Botón de envío -->
        <input type="submit" value="Insertar Post">
    </form>
</div>


<?php include '../Compartido/footer.php'; ?>
