<?php
use Entidades\Producto;
include '../Compartido/header.php';
?>

<?php if (isset($producto) && $producto instanceof Producto): ?>

<body>

<div class="form-container">
    <h2>Actualizar Producto</h2>
    <form action="../Controlador/ControladorProductos.php" method="post" enctype="multipart/form-data">
        <!-- Campo oculto para indicar la acción de actualización -->
        <input type="hidden" name="accion" value="actualizar">

        <!-- Campo oculto para el ID del producto -->
        <input type="hidden" name="producto[id]" value="<?= $producto->getId(); ?>">

        <!-- Campo para el nombre del producto -->
        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="producto[nombre]" value="<?= $producto->getNombre(); ?>" required>
        </div>

        <!-- Campo para la descripción del producto -->
        <div class="form-group">
            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="producto[descripcion]" rows="4" required><?= $producto->getDescripcion(); ?></textarea>
        </div>

        <!-- Campo para el precio del producto -->
        <div class="form-group">
            <label for="precio">Precio:</label>
            <input type="number" id="precio" name="producto[precio]" value="<?= $producto->getPrecio(); ?>" required step="0.01">
        </div>

        <!-- Campo para la categoría del producto -->
        <div class="form-group">
            <label for="categoria_id">Categoría ID:</label>
            <input type="number" id="categoria_id" name="producto[categoria_id]" value="<?= $producto->getCategoriaId(); ?>" required>
        </div>

        <!-- Mostrar la imagen actual -->
        <div class="form-group image-preview">
            <label>Imagen Actual:</label><br>
            <img src="../images/<?= basename($producto->getImagenUrl()); ?>" alt="Imagen Actual">
            <input type="hidden" name="imagenAntigua" value="<?= basename($producto->getImagenUrl()); ?>">
        </div>

        <!-- Campo para subir una nueva imagen -->
        <div class="form-group">
            <label for="imagen_url">Seleccionar nueva imagen (si desea cambiarla):</label>
            <input type="file" id="imagen_url" name="imagen_url">
        </div>

        <!-- Botón de envío -->
        <input type="submit" value="Actualizar Producto">
    </form>
</div>
<?php endif; ?>

<?php include '../Compartido/footer.php'; ?>
