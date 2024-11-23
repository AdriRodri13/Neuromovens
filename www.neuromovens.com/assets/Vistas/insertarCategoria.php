<?php include '../Compartido/header.php'; ?>


<div class="form-container">
    <h2>Insertar Nueva Categoría</h2>
    <form action="../Controlador/ControladorCategoria.php" method="post">
        <!-- Campo oculto para indicar la acción de inserción -->
        <input type="hidden" name="accion" value="insertar">

        <!-- Campo para el nombre de la categoría -->
        <div class="form-group">
            <label for="nombre_categoria">Nombre de la Categoría:</label>
            <input type="text" id="nombre_categoria" name="nombreCategoria" required>
        </div>

        <!-- Botón de envío -->
        <input type="submit" value="Insertar Categoría">
    </form>
</div>
<?php include '../Compartido/footer.php'; ?>
