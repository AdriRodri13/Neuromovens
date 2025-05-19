<?php require '../Entidades/Entidad.php'; require '../Entidades/Categoria.php'; use Entidades\Categoria; include '../Compartido/header.php';  ?>

    <div class="form-container">
        <h2>Insertar Nuevo Producto</h2>

        <!-- Contenedor para mostrar mensajes de éxito o error -->
        <div id="mensaje-respuesta" class="alert" style="display: none;"></div>

        <form id="form-insertar-producto" action="../Controlador/ControladorProductos.php" method="post" enctype="multipart/form-data">
            <!-- Campo oculto para indicar la acción de inserción -->
            <input type="hidden" name="accion" value="insertar">

            <!-- Campo para el nombre del producto -->
            <div class="form-group mb-3">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="producto[nombre]" class="form-control" required>
                <div id="nombre-feedback" class="invalid-feedback"></div>
            </div>

            <!-- Campo para la descripción del producto -->
            <div class="form-group mb-3">
                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="producto[descripcion]" rows="4" class="form-control" required></textarea>
                <div id="descripcion-feedback" class="invalid-feedback"></div>
                <small id="contador-caracteres" class="form-text text-muted">0/500 caracteres</small>
            </div>

            <!-- Campo para el precio del producto -->
            <div class="form-group mb-3">
                <label for="precio">Precio:</label>
                <input type="number" id="precio" name="producto[precio]" class="form-control" required step="0.01">
                <div id="precio-feedback" class="invalid-feedback"></div>
            </div>

            <!-- Campo para la categoría -->
            <div class="form-group mb-3">
                <label for="categoria_id">Categoría:</label>
                <select id="categoria_id" name="producto[categoria_id]" class="form-control" required>
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
                <div id="categoria-feedback" class="invalid-feedback"></div>
            </div>

            <!-- Campo para la imagen del producto -->
            <div class="form-group mb-3">
                <label for="imagen_url">Selecciona una imagen:</label>
                <input type="file" id="imagen_url" name="imagen_url" class="form-control" accept="image/jpeg, image/png">
                <div id="imagen-feedback" class="invalid-feedback"></div>
            </div>

            <!-- Vista previa de imagen -->
            <div id="imagen-preview" class="mb-3" style="display: none;">
                <h5>Vista previa:</h5>
                <img id="preview-img" src="#" alt="Vista previa" style="max-width: 200px; max-height: 200px;">
            </div>

            <!-- Botones de acción -->
            <div class="d-flex justify-content-between">
                <button type="button" id="btn-cancelar" class="btn btn-secondary">Cancelar</button>
                <button type="submit" class="btn btn-primary">Insertar Producto</button>
            </div>
        </form>
    </div>

    <!-- Incluir archivo JavaScript separado -->
    <script src="../js/InsertarProducto.js"></script>

<?php include '../Compartido/footer.php'; ?>