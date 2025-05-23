<?php
require '../Entidades/Entidad.php';
require '../Entidades/Categoria.php';
use Entidades\Categoria;

// Verificar si existe cookie de última inserción
$ultimaInsercion = '';

if (isset($_COOKIE['ultima_insercion_producto'])) {

    $ultimaInsercion = $_COOKIE['ultima_insercion_producto'];
}

include '../Compartido/header.php';
?>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <!-- Mostrar información de última inserción si existe -->
                <?php if ($ultimaInsercion): ?>
                    <div class="alert alert-info alert-dismissible fade show mb-3" role="alert">
                        <i class="fas fa-clock me-2"></i>
                        <small>Última inserción de producto: <strong><?php echo date('d/m/Y H:i:s', $ultimaInsercion); ?></strong></small>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="card shadow border-0">
                    <div class="card-body p-4">
                        <h2 class="text-center mb-4">
                            <i class="fas fa-box text-primary me-2"></i>
                            Insertar Nuevo Producto
                        </h2>

                        <!-- Contenedor para mostrar mensajes de éxito o error -->
                        <div id="mensaje-respuesta" class="alert" style="display: none;"></div>

                        <form id="form-insertar-producto" action="../Controlador/ControladorProductos.php" method="post" enctype="multipart/form-data">
                            <!-- Campo oculto para indicar la acción de inserción -->
                            <input type="hidden" name="accion" value="insertar">

                            <!-- Campo para el nombre del producto -->
                            <div class="form-group mb-4">
                                <label for="nombre" class="form-label fw-semibold">Nombre:</label>
                                <input type="text" id="nombre" name="producto[nombre]" class="form-control form-control-lg" required>
                                <div id="nombre-feedback" class="invalid-feedback"></div>
                            </div>

                            <!-- Campo para la descripción del producto -->
                            <div class="form-group mb-4">
                                <label for="descripcion" class="form-label fw-semibold">Descripción:</label>
                                <textarea id="descripcion" name="producto[descripcion]" rows="4" class="form-control" required></textarea>
                                <div id="descripcion-feedback" class="invalid-feedback"></div>
                                <small id="contador-caracteres" class="form-text text-muted">0/500 caracteres</small>
                            </div>

                            <!-- Campo para el precio del producto -->
                            <div class="form-group mb-4">
                                <label for="precio" class="form-label fw-semibold">Precio:</label>
                                <div class="input-group">
                                    <span class="input-group-text">€</span>
                                    <input type="number" id="precio" name="producto[precio]" class="form-control" required step="0.01">
                                </div>
                                <div id="precio-feedback" class="invalid-feedback"></div>
                            </div>

                            <!-- Campo para la categoría -->
                            <div class="form-group mb-4">
                                <label for="categoria_id" class="form-label fw-semibold">Categoría:</label>
                                <select id="categoria_id" name="producto[categoria_id]" class="form-select" required>
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
                            <div class="form-group mb-4">
                                <label for="imagen_url" class="form-label fw-semibold">Selecciona una imagen:</label>
                                <input type="file" id="imagen_url" name="imagen_url" class="form-control" accept="image/jpeg, image/png">
                                <div id="imagen-feedback" class="invalid-feedback"></div>
                            </div>

                            <!-- Vista previa de imagen -->
                            <div id="imagen-preview" class="mb-4" style="display: none;">
                                <h5 class="fw-semibold">Vista previa:</h5>
                                <img id="preview-img" src="#" alt="Vista previa" class="img-fluid rounded shadow-sm" style="max-width: 200px; max-height: 200px;">
                            </div>

                            <!-- Botones de acción -->
                            <div class="d-flex gap-3 justify-content-end">
                                <button type="button" id="btn-cancelar" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i> Cancelar
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i> Insertar Producto
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Incluir archivo JavaScript separado -->
    <script src="../js/InsertarProducto.js"></script>

<?php include '../Compartido/footer.php'; ?>