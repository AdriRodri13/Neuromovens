<?php
require '../Entidades/Entidad.php';
require '../Entidades/Categoria.php';
use Entidades\Categoria;
include '../Compartido/header.php';

?>


    <div class="form-container">
        <h2>Insertar Nuevo Producto</h2>
        <form action="../Controlador/ControladorProductos.php" method="post" enctype="multipart/form-data">
            <!-- Campo oculto para indicar la acción de inserción -->
            <input type="hidden" name="accion" value="insertar">

            <!-- Campo para el nombre del producto -->
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="producto[nombre]" required>
            </div>

            <!-- Campo para la descripción del producto -->
            <div class="form-group">
                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="producto[descripcion]" rows="4" required></textarea>
            </div>

            <!-- Campo para el precio del producto -->
            <div class="form-group">
                <label for="precio">Precio:</label>
                <input type="number" id="precio" name="producto[precio]" required step="0.01">
            </div>


            <div class="form-group">
                <label for="categoria_id">Categoría:</label>
                <select id="categoria_id" name="producto[categoria_id]" required>
                    <option value="" disabled selected>Selecciona una categoría</option>
                    <?php if (isset($_SESSION['categorias'])): ?>
                        <?php
                        // Deserializa las categorías de la sesión
                        $categorias = unserialize($_SESSION['categorias']);
                        ?>
                        <?php foreach ($categorias as $categoria): ?>
                        <?php if($categoria instanceof Categoria) :?>
                            <option value="<?= htmlspecialchars($categoria->getIdCategoria()); ?>">
                                <?= htmlspecialchars($categoria->getNombreCategoria()); ?> - ID:<?= htmlspecialchars($categoria->getIdCategoria()); ?>
                            </option>
                        <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="" disabled>No hay categorías disponibles</option>
                    <?php endif; ?>
                </select>
            </div>

            <!-- Campo para la imagen del producto -->
            <div class="form-group">
                <label for="imagen_url">Selecciona una imagen:</label>
                <input type="file" id="imagen_url" name="imagen_url" accept="image/jpeg, image/png">
            </div>

            <!-- Botón de envío -->
            <input type="submit" value="Insertar Producto">
        </form>
    </div>

<?php include '../Compartido/footer.php'; ?>