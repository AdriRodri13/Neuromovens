<?php
use Entidades\Producto;
use Entidades\Categoria;
include '../Compartido/header.php';
?>

<?php if (isset($producto) && $producto instanceof Producto): ?>
    <body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white text-center">
                        <h2 class="card-title mb-0">
                            <i class="fas fa-edit me-2"></i>
                            Actualizar Producto
                        </h2>
                    </div>

                    <div class="card-body p-4">
                        <form id="form-actualizar-producto" action="../Controlador/ControladorProductos.php" method="post" enctype="multipart/form-data">
                            <!-- Campos ocultos -->
                            <input type="hidden" name="accion" value="actualizar">
                            <input type="hidden" name="producto[id]" value="<?= $producto->getId(); ?>">

                            <!-- Campo Nombre del Producto -->
                            <div class="row mb-4">
                                <div class="col-md-8">
                                    <label for="nombre" class="form-label fw-semibold">
                                        <i class="fas fa-box me-1"></i>
                                        Nombre del Producto
                                    </label>
                                    <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-tag"></i>
                                            </span>
                                        <input type="text"
                                               id="nombre"
                                               name="producto[nombre]"
                                               class="form-control form-control-lg"
                                               value="<?= htmlspecialchars($producto->getNombre()); ?>"
                                               placeholder="Ingrese el nombre del producto..."
                                               required
                                               autocomplete="off">
                                    </div>
                                    <div id="nombre-error" class="invalid-feedback"></div>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Entre 3 y 100 caracteres
                                    </small>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-muted">
                                        <i class="fas fa-key me-1"></i>
                                        ID del Producto
                                    </label>
                                    <div class="form-control-plaintext bg-light px-3 py-2 rounded border text-center">
                                        <strong>#<?= $producto->getId(); ?></strong>
                                    </div>
                                </div>
                            </div>

                            <!-- Campo Descripción -->
                            <div class="mb-4">
                                <label for="descripcion" class="form-label fw-semibold">
                                    <i class="fas fa-align-left me-1"></i>
                                    Descripción del Producto
                                </label>
                                <textarea id="descripcion"
                                          name="producto[descripcion]"
                                          class="form-control"
                                          rows="6"
                                          placeholder="Describa las características y detalles del producto..."
                                          required><?= htmlspecialchars($producto->getDescripcion()); ?></textarea>
                                <div id="descripcion-error" class="invalid-feedback"></div>
                                <div class="d-flex justify-content-between mt-2">
                                    <small class="form-text text-muted">
                                        <i class="fas fa-lightbulb me-1"></i>
                                        Mínimo 10 caracteres
                                    </small>
                                    <small id="contador-caracteres" class="form-text text-muted fw-bold">
                                        0 caracteres
                                    </small>
                                </div>
                            </div>

                            <!-- Precio y Categoría -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="precio" class="form-label fw-semibold">
                                        <i class="fas fa-euro-sign me-1"></i>
                                        Precio
                                    </label>
                                    <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-money-bill-wave"></i>
                                            </span>
                                        <input type="number"
                                               id="precio"
                                               name="producto[precio]"
                                               class="form-control form-control-lg"
                                               value="<?= $producto->getPrecio(); ?>"
                                               placeholder="0.00"
                                               required
                                               step="0.01"
                                               min="0.01">
                                        <span class="input-group-text">€</span>
                                    </div>
                                    <div id="precio-error" class="invalid-feedback"></div>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Precio mayor que 0
                                    </small>
                                </div>
                                <div class="col-md-6">
                                    <label for="categoria_id" class="form-label fw-semibold">
                                        <i class="fas fa-folder me-1"></i>
                                        Categoría
                                    </label>
                                    <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-list"></i>
                                            </span>
                                        <select id="categoria_id"
                                                name="producto[categoria_id]"
                                                class="form-select form-select-lg"
                                                required>
                                            <option value="" disabled>Selecciona una categoría</option>
                                            <?php if (isset($_SESSION['categorias'])): ?>
                                                <?php $categorias = unserialize($_SESSION['categorias']); ?>
                                                <?php foreach ($categorias as $categoria): ?>
                                                    <?php if($categoria instanceof Categoria) :?>
                                                        <option value="<?= htmlspecialchars($categoria->getIdCategoria()); ?>"
                                                            <?= ($categoria->getIdCategoria() == $producto->getCategoriaId()) ? 'selected' : ''; ?>>
                                                            <?= htmlspecialchars($categoria->getNombreCategoria()); ?>
                                                        </option>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <option value="" disabled>No hay categorías disponibles</option>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    <div id="categoria-error" class="invalid-feedback"></div>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Seleccione una categoría
                                    </small>
                                </div>
                            </div>

                            <!-- Gestión de Imágenes -->
                            <div class="row mb-4">
                                <!-- Imagen Actual -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-image me-1"></i>
                                        Imagen Actual
                                    </label>
                                    <div class="border rounded p-3 bg-light">
                                        <div class="text-center">
                                            <img id="imagen-actual"
                                                 src="../images/<?= basename($producto->getImagenUrl()); ?>"
                                                 alt="Imagen Actual"
                                                 class="img-fluid rounded shadow-sm"
                                                 style="max-height: 200px;">
                                            <input type="hidden" name="imagenAntigua" value="../images/<?= basename($producto->getImagenUrl()); ?>">
                                        </div>
                                        <div class="text-center mt-2">
                                            <small class="text-muted">
                                                <i class="fas fa-file-image me-1"></i>
                                                <?= basename($producto->getImagenUrl()); ?>
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Nueva Imagen -->
                                <div class="col-md-6 mb-3">
                                    <label for="imagen_url" class="form-label fw-semibold">
                                        <i class="fas fa-upload me-1"></i>
                                        Nueva Imagen (Opcional)
                                    </label>
                                    <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-camera"></i>
                                            </span>
                                        <input type="file"
                                               id="imagen_url"
                                               name="imagen_url"
                                               class="form-control"
                                               accept="image/jpeg,image/png,image/jpg">
                                    </div>
                                    <div id="imagen-error" class="invalid-feedback"></div>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        JPG, JPEG, PNG • Máx. 5MB
                                    </small>

                                    <!-- Vista previa de nueva imagen -->
                                    <div id="nueva-imagen-preview" class="mt-3 border rounded p-3 bg-light" style="display: none;">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="mb-0">
                                                <i class="fas fa-eye me-1"></i>
                                                Vista Previa
                                            </h6>
                                            <button type="button"
                                                    id="cancelar-imagen"
                                                    class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-times"></i>
                                                Quitar
                                            </button>
                                        </div>
                                        <div class="text-center">
                                            <img id="preview-img"
                                                 src="#"
                                                 alt="Vista previa"
                                                 class="img-fluid rounded shadow-sm"
                                                 style="max-height: 200px;">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Fecha de Actualización -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-clock me-1"></i>
                                    Fecha de Actualización
                                </label>
                                <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-calendar-alt"></i>
                                        </span>
                                    <input type="text"
                                           id="fecha_actualizacion"
                                           class="form-control bg-light"
                                           readonly>
                                </div>
                                <small class="form-text text-muted">
                                    <i class="fas fa-lightbulb me-1"></i>
                                    Esta fecha es solo informativa y se establece automáticamente
                                </small>
                            </div>

                            <!-- Botones de Acción -->
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-5">
                                <button type="button"
                                        id="btn-cancelar"
                                        class="btn btn-outline-secondary btn-lg me-md-2">
                                    <i class="fas fa-times me-2"></i>
                                    Cancelar
                                </button>
                                <button type="submit"
                                        id="btn-actualizar"
                                        class="btn btn-success btn-lg">
                                    <i class="fas fa-save me-2"></i>
                                    Actualizar Producto
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Footer del Card -->
                    <div class="card-footer bg-light text-center text-muted">
                        <small>
                            <i class="fas fa-shield-alt me-1"></i>
                            Todos los cambios se aplicarán inmediatamente tras la confirmación
                            <span class="mx-2">•</span>
                            <i class="fas fa-check-circle me-1"></i>
                            Información validada automáticamente
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Incluir archivo JavaScript separado -->
    <script src="../js/CargarProducto.js"></script>
    </body>
<?php endif; ?>

<?php include '../Compartido/footer.php'; ?>