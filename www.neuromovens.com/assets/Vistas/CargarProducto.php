<?php
use Entidades\Producto;
include '../Compartido/header.php';
?>

<?php if (isset($producto) && $producto instanceof Producto): ?>
<style>
    .form-container {
        background-color: var(--color-blanco);
        padding: 30px;
        border-radius: 8px;
        box-shadow: var(--color-sombra);
        max-width: 600px;
        margin: 50px auto;
    }

    .form-container h2 {
        color: var(--color-principal);
        margin-bottom: 20px;
    }

    .form-container label {
        font-weight: bold;
        color: var(--color-oscuro);
    }

    .form-container input[type="text"],
    .form-container input[type="number"],
    .form-container textarea,
    .form-container input[type="file"] {
        width: 100%;
        padding: 10px;
        margin: 10px 0 20px 0;
        border-radius: 5px;
        border: 1px solid var(--color-gris-suave);
        box-sizing: border-box;
    }

    .form-container input[type="submit"] {
        background-color: var(--color-principal);
        color: var(--color-blanco);
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .form-container input[type="submit"]:hover {
        background-color: var(--color-suave);
    }

    .form-container .image-preview {
        margin-bottom: 20px;
    }

    .form-container .image-preview img {
        width: 200px;
        height: auto;
        border-radius: 5px;
        box-shadow: var(--color-sombra-intensa);
    }

    .form-container .form-group {
        margin-bottom: 20px;
    }
</style>

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
